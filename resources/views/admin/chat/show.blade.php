@extends('admin.layout.master')
@section('content')
<div class="card h-100">
    <div class="card-header fw-bold">Chat với: <span class="text-danger">{{ $session->phone }}</span></div>
    <div class="card-body bg-light" id="admin-chat-area" style="height: 400px; overflow-y: auto;">
        </div>
    <div class="card-footer">
        <div class="input-group">
            <input type="text" id="admin-input" class="form-control" placeholder="Nhập câu trả lời...">
            <button id="admin-send" class="btn btn-primary">Gửi</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let sessionId = {{ $session->id }};
    
    function loadMessages() {
        $.get("{{ route('chat.messages') }}", { session_id: sessionId }, function(msgs) {
            $('#admin-chat-area').html('');
            msgs.forEach(m => {
                // Admin thì hiện bên phải (màu xanh), Khách bên trái (màu xám)
                let align = m.is_admin ? 'text-end' : 'text-start';
                let bg = m.is_admin ? 'bg-primary text-white' : 'bg-white border';
                
                let html = `<div class="${align} mb-2">
                                <div class="d-inline-block p-2 rounded ${bg}" style="max-width: 70%;">${m.message}</div>
                            </div>`;
                $('#admin-chat-area').append(html);
            });
        });
    }

    // Tự động load mỗi 2 giây
    setInterval(loadMessages, 2000);
    loadMessages(); // Load ngay lần đầu

    $('#admin-send').click(function() {
        let msg = $('#admin-input').val();
        if(!msg) return;
        
        $.post("{{ route('chat.send') }}", {
            session_id: sessionId,
            message: msg,
            is_admin: 1, // Đánh dấu là Admin nhắn
            _token: '{{ csrf_token() }}'
        }, function() {
            $('#admin-input').val('');
            loadMessages();
        });
    });
</script>
@endsection