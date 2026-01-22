<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ChatController extends Controller
{
    // ==========================================
    // PHẦN KHÁCH HÀNG (FRONTEND)
    // ==========================================

    /**
     * Bắt đầu phiên chat
     */
    public function startChat(Request $request)
    {
        // 1. Kiểm tra đăng nhập (Guard Customer)
        $customer = Auth::guard('customer')->user();

        // Nếu chưa đăng nhập -> Báo Frontend mở Modal Login
        if (!$customer) {
            return response()->json([
                'status' => 'require_login',
                'message' => 'Vui lòng nhập thông tin để chat.'
            ]);
        }

        // 2. Tìm hoặc Tạo phiên chat
        // Tìm theo ID khách hàng trước
        $chatSession = ChatSession::firstOrCreate(
            ['khach_hang_id' => $customer->id],
            [
                'session_id' => Session::getId(),
                'user_phone' => $customer->getContactInfo(),
                'user_name' => $customer->ho_ten,
                'is_verified' => $customer->isVerified(),
                'context_url' => url()->previous()
            ]
        );

        return response()->json([
            'status' => 'success',
            'session_id' => $chatSession->id,
            'customer_name' => $customer->ho_ten,
            'messages' => $chatSession->messages // Load tin nhắn cũ
        ]);
    }

    /**
     * Gửi tin nhắn
     */
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required']);

        $customer = Auth::guard('customer')->user();
        if (!$customer) return response()->json(['status' => 'error', 'message' => 'Hết phiên làm việc'], 401);

        // Tìm session của khách
        $chatSession = ChatSession::where('khach_hang_id', $customer->id)->first();

        if (!$chatSession) {
            return response()->json(['status' => 'error', 'message' => 'Lỗi phiên chat'], 404);
        }

        // Lưu tin nhắn
        $msg = ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'user_id' => null, // Null = Khách hàng gửi
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json(['status' => 'success', 'data' => $msg]);
    }

    /**
     * Lấy tin nhắn mới (Polling)
     */
    public function getMessages()
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) return response()->json([]);

        $chatSession = ChatSession::where('khach_hang_id', $customer->id)->first();

        if ($chatSession) {
            $messages = ChatMessage::where('chat_session_id', $chatSession->id)
                ->with('user') // Để lấy avatar/tên admin nếu cần
                ->orderBy('created_at', 'asc')
                ->get();
            return response()->json(['data' => $messages]);
        }
        return response()->json(['data' => []]);
    }

    // ==========================================
    // PHẦN QUẢN TRỊ (ADMIN / SALE)
    // ==========================================

    public function adminIndex()
    {
        $sessions = ChatSession::with(['messages' => function ($q) {
            $q->latest()->limit(1);
        }])
            ->with('khachHang') // Load thông tin khách
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.chat.index', compact('sessions'));
    }

    public function adminShow($id)
    {
        $session = ChatSession::with('messages')->findOrFail($id);

        // Đánh dấu đã đọc
        ChatMessage::where('chat_session_id', $id)
            ->whereNull('user_id')
            ->update(['is_read' => true]);

        return view('admin.chat.show', compact('session'));
    }

    public function adminReply(Request $request)
    {
        $request->validate([
            'chat_session_id' => 'required',
            'message' => 'required'
        ]);

        // Admin trả lời
        ChatMessage::create([
            'chat_session_id' => $request->chat_session_id,
            'user_id' => Auth::id(), // ID của Admin đang login (Guard web)
            'message' => $request->message
        ]);

        // Update timestamp để session nổi lên đầu
        ChatSession::where('id', $request->chat_session_id)->touch();

        // AJAX response nếu gọi từ giao diện chat admin
        if ($request->ajax()) {
            return response()->json(['status' => 'success']);
        }

        return back()->with('success', 'Đã gửi tin nhắn.');
    }

    // API lấy tin nhắn cho Admin (để chat realtime-like)
    public function adminGetMessages(Request $request)
    {
        $sessionId = $request->chat_session_id;
        $messages = ChatMessage::where('chat_session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($messages);
    }
}
