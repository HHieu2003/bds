<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    // 1. Khách bắt đầu chat (CÓ XỬ LÝ NGỮ CẢNH)
    public function startChat(Request $request)
    {
        $request->validate(['phone' => 'required|numeric']);

        // Tìm hoặc tạo phiên chat
        $session = ChatSession::firstOrCreate(['phone' => $request->phone]);

        // Cập nhật ngữ cảnh khách đang xem
        if ($request->has('project_id') && $request->project_id) {
            $session->update([
                'du_an_id' => $request->project_id,
                'bat_dong_san_id' => null
            ]);
        } elseif ($request->has('property_id') && $request->property_id) {
            $session->update([
                'bat_dong_san_id' => $request->property_id,
                'du_an_id' => null
            ]);
        }

        return response()->json(['status' => 'success', 'session_id' => $session->id]);
    }

    // 2. Gửi tin nhắn
    public function sendMessage(Request $request)
    {
        $msg = ChatMessage::create([
            'chat_session_id' => $request->session_id,
            'message' => $request->message,
            'is_admin' => $request->is_admin ?? false
        ]);

        if (!$request->is_admin) {
            $msg->session()->update(['is_read' => false]);
        }

        return response()->json(['status' => 'success']);
    }

    // 3. Lấy tin nhắn
    public function getMessages(Request $request)
    {
        // Kiểm tra xem session có tồn tại không
        $session = ChatSession::find($request->session_id);

        if (!$session) {
            // Trả về lỗi 404 nếu không tìm thấy phiên chat trong DB
            return response()->json(['error' => 'Session not found'], 404);
        }

        $messages = ChatMessage::where('chat_session_id', $request->session_id)->get();
        return response()->json($messages);
    }
    // 4. Admin xem danh sách
    public function adminIndex()
    {
        // Eager load để lấy luôn thông tin dự án/bđs cho nhanh
        $sessions = ChatSession::with(['duAn', 'batDongSan'])->orderBy('updated_at', 'desc')->get();
        return view('admin.chat.index', compact('sessions'));
    }

    // 5. Admin chat chi tiết
    public function adminShow($id)
    {
        $session = ChatSession::with('messages')->findOrFail($id);
        $session->update(['is_read' => true]);
        return view('admin.chat.show', compact('session'));
    }
}
