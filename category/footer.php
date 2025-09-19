<?php

include 'connect.php';

// Kiểm tra và khởi tạo session ID khách
if (!isset($_SESSION['sender_id'])) {
    $_SESSION['sender_id'] = rand(100000, 999999);
}
$sender_id = $_SESSION['sender_id'];

// Xử lý yêu cầu Load tin nhắn qua AJAX
if (isset($_GET['load_messages'])) {
    header('Content-Type: application/json');
    $sql = "SELECT * FROM chat_messages 
            WHERE (sender_id = ? AND receiver_id = 0) 
            OR (sender_id = 0 AND receiver_id = ?)
            ORDER BY timestamp ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $sender_id, $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
    exit;
}

// Xử lý yêu cầu Gửi tin nhắn qua AJAX POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $receiver_id = 0; // Admin ID
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        $stmt->execute();
        $stmt->close();
    }
    exit;
}

// Gửi tin nhắn chào 1 lần duy nhất cho khách hàng mới
try {
    $check_sql = "SELECT id FROM chat_messages WHERE sender_id = 0 AND receiver_id = ? LIMIT 1";
    $check_stmt = $conn->prepare($check_sql);
    if ($check_stmt) {
        $check_stmt->bind_param("i", $sender_id);
        $check_stmt->execute();
        $res = $check_stmt->get_result();

        if ($res->num_rows === 0) {
            $welcome = "BL SHOP Xin chào! Chúng tôi có thể giúp gì cho bạn?";
            $admin_id = 0;
            $insert_sql = "INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            if ($insert_stmt) {
                $insert_stmt->bind_param("iis", $admin_id, $sender_id, $welcome);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
        }
        $check_stmt->close();
    }
} catch (Exception $e) {
    error_log("Lỗi chat PHP: " . $e->getMessage());
}
?>

<footer class="bg-dark text-white mt-5 p-4 text-center position-relative">
    <style>
        .popup-box {
            position: fixed;
            bottom: 70px;
            right: 20px;
            width: 320px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            z-index: 1200;
            display: flex;
            flex-direction: column;
            height: 400px;
            transition: all 0.3s ease-in-out;
            transform: translateY(100%);
            opacity: 0;
        }
        .popup-box.show {
            transform: translateY(0);
            opacity: 1;
        }
        .popup-header {
            background: #007bff;
            color: white;
            padding: 8px 12px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            max-height: 400px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .msg {
            padding: 8px 12px;
            border-radius: 15px;
            max-width: 80%;
            word-wrap: break-word;
            animation: fadeIn 0.3s ease-out;
        }
        .msg.user {
            background: #09b4f2;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 0;
        }
        .msg.admin {
            background: #ffd966;
            color: black;
            align-self: flex-start;
            border-bottom-left-radius: 0;
        }
        .chat-input {
            display: flex;
            border-top: 1px solid #ccc;
            padding: 8px;
            align-items: flex-end;
        }
        .chat-input textarea {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 8px 12px;
            resize: none;
            overflow: hidden;
            min-height: 38px;
            max-height: 100px;
        }
        .chat-input button {
            border: none;
            background: #28a745;
            color: white;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 20px;
            margin-left: 8px;
            transition: background 0.3s ease;
        }
        .chat-input button:hover {
            background: #218838;
        }
        .btn-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        .d-none {
            display: none !important;
        }
        .loading-dots {
            align-self: flex-start;
            background-color: #f0f0f0;
            padding: 8px 12px;
            border-radius: 15px;
            animation: fadeIn 0.3s ease-out;
        }
        .loading-dots span {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #888;
            margin: 0 2px;
            animation: bounce 1.4s infinite ease-in-out both;
        }
        .loading-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }
        .loading-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }
        .loading-dots span:nth-child(3) {
            animation-delay: 0s;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1.0);
            }
        }
    </style>

    <div class="container py-5 border-top">
        <div class="row text-start">
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold">Giới thiệu</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white text-decoration-none">Về BL SHOP</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Trần Tuấn Anh</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Đỗ Đặng Anh Thư</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Lê Hồng Đức</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Đỗ Quốc Khánh</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Văn Đình Thành</a></li>
                    <li><a href="introduce.php#target1" class="text-white" style="text-decoration:underline;text-decoration-color:white"><em>Chi tiết</em></a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold">Dịch vụ khách hàng</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white text-decoration-none">Chính sách điều khoản</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Hướng dẫn mua hàng</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Chính sách đổi trả</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Chính sách bảo hành</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Chính sách giao nhận</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Chính sách thành viên</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Q&A</a></li>
                    <li><a href="introduce.php#target2" class="text-white" style="text-decoration:underline;text-decoration-color:white"><em>Chi tiết</em></a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold">Liên hệ</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white text-decoration-none">📞 Hotline: @ ### ### ###</a></li>
                    <li><a href="#" class="text-white text-decoration-none">✉ Email: hotro@blshop.vn</a></li>
                    <li><a href="#" class="text-white text-decoration-none">💬 Live Chat</a></li>
                    <li><a href="#" target="_blank" class="text-white text-decoration-none">💙 Messenger</a></li>
                    <li><a href="#" class="text-white text-decoration-none">📍 Liên hệ trực tiếp</a></li>
                </ul>
                <div class="d-flex gap-3 mt-3">
                    <a href="https://www.facebook.com/tuyensinhdaihoc.fbu" target="_blank" class="text-white fs-4"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://chat.zalo.me/" target="_blank" class="text-white fs-4"><i class="fa-solid fa-comment"></i></a>
                    <a href="https://www.youtube.com/watch?v=VoICTdqebcE" target="_blank" class="text-white fs-4"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://www.instagram.com/tuanhdz.102/" target="_blank" class="text-white fs-4"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold">Nhận thông tin</h5>
                <form class="d-flex mb-3">
                    <input type="email" class="form-control" placeholder="Nhập email">
                    <button class="btn btn-primary ms-2">Đăng ký</button>
                </form>
                <h5 class="fw-bold">Download App</h5>
                <div class="d-flex gap-2">
                    <a href="https://www.apple.com/app-store/"><img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="App Store" height="40"></a>
                    <a href="https://play.google.com/store/games?device=windows"><img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" height="40"></a>
                </div>
            </div>
        </div>
    </div>
    <hr style="width:80%;margin:auto">
    <div class="container">
        <p>© 2025 BL SHOP. All rights reserved.</p>
    </div>

    <div class="fixed-bottom d-flex justify-content-end p-3" style="gap: 10px; z-index: 1100;">
        <button id="btnMap" class="btn btn-primary rounded-circle p-2" style="width: 40px; height: 40px;">
            <i class="fa-solid fa-map-location-dot"></i>
        </button>
        <button id="btnChat" class="btn btn-success rounded-circle p-2" style="width: 40px; height: 40px;">
            <i class="fa-solid fa-comments"></i>
        </button>
    </div>

    <div id="popupMap" class="popup-box d-none">
        <div class="popup-header">
            <span>Bản đồ</span>
            <button class="btn-close" id="closeMap">×</button>
        </div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3725.12196024162!2d105.79512967586552!3d20.985956788594954!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac87ed4c3c33%3A0x6d9f7f45c92c9431!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBUw6BpIGNoYW5oIC0gQsaw4budbmcgSMOgIE7hu5lpIChGQlUp!5e0!3m2!1svi!2svn!4v1716300486383!5m2!1svi!2svn" width="300" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <div id="popupChat" class="popup-box d-none">
        <div class="popup-header">
            <span>Chat với chúng tôi</span>
            <button class="btn-close" id="closeChat">×</button>
        </div>
        <div class="chat-body" id="chatMessages"></div>
        <div class="chat-input">
            <textarea id="chatInput" rows="1" placeholder="Nhập tin nhắn..."></textarea>
            <button id="sendBtn" class="btn btn-success">Gửi</button>
        </div>
    </div>

    <script>
        const popupMap = document.getElementById('popupMap');
        const popupChat = document.getElementById('popupChat');
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        let isLoading = false;

        chatInput.addEventListener('input', () => {
            chatInput.style.height = 'auto';
            chatInput.style.height = chatInput.scrollHeight + 'px';
        });

        chatInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendBtn.click();
            }
        });

        document.getElementById('btnMap').onclick = () => {
            popupMap.classList.toggle('d-none');
            popupChat.classList.add('d-none');
            if (!popupMap.classList.contains('d-none')) {
                popupMap.classList.add('show');
            } else {
                popupMap.classList.remove('show');
            }
        };
        document.getElementById('closeMap').onclick = () => {
            popupMap.classList.add('d-none');
            popupMap.classList.remove('show');
        }

        document.getElementById('btnChat').onclick = () => {
            popupChat.classList.toggle('d-none');
            popupMap.classList.add('d-none');
            if (!popupChat.classList.contains('d-none')) {
                popupChat.classList.add('show');
            } else {
                popupChat.classList.remove('show');
            }
            loadMessages();
        };
        document.getElementById('closeChat').onclick = () => {
            popupChat.classList.add('d-none');
            popupChat.classList.remove('show');
        }

        sendBtn.onclick = () => {
            if (isLoading) return;

            let msg = chatInput.value.trim();
            if (msg) {
                // Hiển thị tin nhắn người dùng ngay lập tức
                const userMsgDiv = document.createElement('div');
                userMsgDiv.className = 'msg user';
                userMsgDiv.innerText = msg;
                chatMessages.appendChild(userMsgDiv);
                scrollToBottom(true);

                // Hiển thị hiệu ứng tải (admin đang nhập)
                showLoadingState();

                fetch(window.location.href, {
                    method: 'POST',
                    body: new URLSearchParams({
                        message: msg
                    })
                }).then(() => {
                    // Sau khi gửi xong, xóa hiệu ứng tải và cập nhật tin nhắn
                    hideLoadingState();
                    chatInput.value = '';
                    chatInput.style.height = 'auto';
                    loadMessages();
                }).catch(error => {
                    console.error('Error sending message:', error);
                    hideLoadingState();
                    alert('Có lỗi khi gửi tin nhắn, vui lòng thử lại.');
                });
            }
        };

        function showLoadingState() {
            isLoading = true;
            sendBtn.disabled = true;
            sendBtn.innerHTML = '...';
            const loadingDots = document.createElement('div');
            loadingDots.className = 'loading-dots';
            loadingDots.innerHTML = '<span></span><span></span><span></span>';
            chatMessages.appendChild(loadingDots);
            scrollToBottom(true);
        }

        function hideLoadingState() {
            isLoading = false;
            sendBtn.disabled = false;
            sendBtn.innerHTML = 'Gửi';
            const loadingDots = chatMessages.querySelector('.loading-dots');
            if (loadingDots) {
                loadingDots.remove();
            }
        }

        function loadMessages() {
            fetch(window.location.href + '?load_messages=1')
                .then(res => res.json())
                .then(data => {
                    // Lưu trạng thái cuộn trước khi cập nhật
                    const isAtBottom = chatMessages.scrollHeight - chatMessages.clientHeight <= chatMessages.scrollTop + 10;
                    
                    const newChatContent = data.map(m => {
                        let senderClass = m.sender_id == <?php echo $sender_id; ?> ? 'user' : 'admin';
                        return `<div class="msg ${senderClass}">${m.message}</div>`;
                    }).join('');
                    
                    chatMessages.innerHTML = newChatContent;

                    // Chỉ cuộn xuống cuối nếu người dùng đang ở cuối
                    if (isAtBottom) {
                        scrollToBottom(true);
                    }
                })
                .catch(error => console.error('Error loading messages:', error));
        }

        function scrollToBottom(force = false) {
            const isAtBottom = chatMessages.scrollHeight - chatMessages.clientHeight <= chatMessages.scrollTop + 10;
            if (force || isAtBottom) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

        window.onload = loadMessages;
        setInterval(loadMessages, 3000);
    </script>
</footer>