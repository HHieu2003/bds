<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatController extends Controller
{
    // ==========================================
    // PHẦN 1: LOGIC KHÁCH HÀNG (FRONTEND)
    // ==========================================

    public function startChat(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits_between:10,11',
            'name' => 'required|string|max:50',
            'current_url' => 'nullable|string'
        ]);

        $otp = rand(100000, 999999);
        $chatSession = ChatSession::where('user_phone', $request->phone)->first();

        if ($chatSession) {
            if (!$chatSession->is_verified) {
                // Nếu chưa xác minh, gửi lại OTP mới
                $chatSession->verification_code = $otp;
                $chatSession->user_name = $request->name;
                $chatSession->expires_at = Carbon::now()->addDay();
                $chatSession->save();
            } else {
                // Đã xác minh thì kiểm tra lại danh tính (Security Check)
                $chatSession->verification_code = $otp;
                $chatSession->save();
            }
        } else {
            // Tạo mới
            $chatSession = ChatSession::create([
                'session_id' => Str::random(40),
                'user_name' => $request->name,
                'user_phone' => $request->phone,
                'is_verified' => false,
                'verification_code' => $otp,
                'expires_at' => Carbon::now()->addDay(),
                'context_url' => $request->current_url
            ]);
        }

        Log::info("OTP Chat cho SĐT {$request->phone}: {$otp}");

        return response()->json([
            'status' => 'otp_sent',
            'message' => 'Mã OTP đã được gửi.',
            'phone' => $request->phone
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['phone' => 'required', 'otp' => 'required']);
        $chatSession = ChatSession::where('user_phone', $request->phone)->first();

        if (!$chatSession) return response()->json(['status' => 'error', 'message' => 'Không tìm thấy phiên chat.'], 404);

        if ($chatSession->verification_code == $request->otp) {
            $chatSession->is_verified = true;
            $chatSession->verification_code = null;
            $chatSession->expires_at = null; // Vĩnh viễn không xóa
            $chatSession->save();

            $history = $chatSession->messages()->orderBy('created_at', 'asc')->get();
            return response()->json(['status' => 'success', 'session_id' => $chatSession->id, 'history' => $history]);
        }

        return response()->json(['status' => 'error', 'message' => 'Mã OTP không đúng.'], 400);
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required', 'chat_session_id' => 'required']);

        $session = ChatSession::find($request->chat_session_id);
        if (!$session || !$session->is_verified) {
            return response()->json(['status' => 'error', 'message' => 'Phiên chưa xác thực'], 403);
        }

        $msg = ChatMessage::create([
            'chat_session_id' => $request->chat_session_id,
            'user_id' => null, // Null = Khách hàng
            'message' => $request->message,
        ]);

        return response()->json(['status' => 'success', 'data' => $msg]);
    }

    public function getMessages(Request $request)
    {
        if (!$request->chat_session_id) return response()->json([]);
        // Lấy tin nhắn (có thể lọc theo last_id để tối ưu nếu cần)
        $messages = ChatMessage::where('chat_session_id', $request->chat_session_id)
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($messages);
    }

    // ==========================================
    // PHẦN 2: LOGIC QUẢN TRỊ (ADMIN)
    // ==========================================

    // Danh sách các cuộc hội thoại
    public function adminIndex()
    {
        // Chỉ lấy các cuộc hội thoại đã xác minh hoặc đang chờ (tùy nhu cầu)
        // Ở đây ưu tiên hiển thị tin nhắn mới nhất lên đầu
        $sessions = ChatSession::where('is_verified', true)
            ->with(['messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->orderByDesc(
                ChatMessage::select('created_at')
                    ->whereColumn('chat_session_id', 'chat_sessions.id')
                    ->orderByDesc('created_at')
                    ->limit(1)
            )
            ->paginate(20);

        return view('admin.chat.index', compact('sessions'));
    }

    // Giao diện chat chi tiết của Admin
    public function adminShow($id)
    {
        $session = ChatSession::findOrFail($id);

        // Đánh dấu tất cả tin nhắn của khách là "Đã đọc"
        ChatMessage::where('chat_session_id', $id)
            ->whereNull('user_id')
            ->update(['is_read' => true]);

        return view('admin.chat.show', compact('session'));
    }

    // API để Admin gửi tin nhắn (Reply)
    public function adminReply(Request $request)
    {
        $request->validate([
            'chat_session_id' => 'required',
            'message' => 'required'
        ]);

        $session = ChatSession::findOrFail($request->chat_session_id);

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'user_id' => Auth::id(), // ID người trả lời
            'message' => $request->message,
            'is_read' => 0 // Tin của admin thì mặc định là chưa đọc (với khách) hoặc tùy logic
        ]);

        // Cập nhật thời gian session để nó nổi lên đầu danh sách
        $session->touch();

        return response()->json(['status' => 'success']);
    }

    // API Polling dành riêng cho Admin (để check tin mới trong trang chat)
    public function adminGetMessages(Request $request)
    {
        $messages = ChatMessage::where('chat_session_id', $request->chat_session_id)
            ->with('user') // Nếu muốn hiện avatar/tên sale
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
