<?php include 'connect.php';

    // Lấy session ID khách từ file index.php (session đã khởi tạo ở đó)
    if (!isset($_SESSION['sender_id'])) {
        $_SESSION['sender_id'] = rand(100000, 999999);
    }
    $sender_id = $_SESSION['sender_id'];

    // Load tin nhắn
    if (isset($_GET['load_messages'])) {
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

    // Gửi tin nhắn
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
        $receiver_id = 0;
        $message = trim($_POST['message']);
        if (!empty($message)) {
            $stmt = $conn->prepare("INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
            $stmt->execute();
            $stmt->close();
        }
        exit;
    }

    // Gửi tin nhắn chào 1 lần duy nhất
    $check = $conn->prepare("SELECT id FROM chat_messages WHERE sender_id = 0 AND receiver_id = ? LIMIT 1");
    $check->bind_param("i", $sender_id);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows === 0) {
        $welcome = "BL SHOP Xin chào! Chúng tôi có thể giúp gì cho bạn?";
        $admin_id = 0;
        $stmt = $conn->prepare("INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $admin_id, $sender_id, $welcome);
        $stmt->execute();
        $stmt->close();
    }
    $check->close();
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
            /* Tăng chiều cao popup để hiển thị được nhiều tin nhắn hơn */
            height: 400px; 
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
            /* Thêm hiệu ứng chuyển động cho tin nhắn khi xuất hiện */
            animation: fadeIn 0.3s ease-out;
        }
        .msg.user {
            background: #09b4f2;
            color: white;
            align-self: flex-end;
            /* Bo góc tin nhắn người dùng */
            border-bottom-right-radius: 0;
        }
        .msg.admin {
            background: #ffd966;
            color: black;
            align-self: flex-start;
            /* Bo góc tin nhắn admin */
            border-bottom-left-radius: 0;
        }
        .chat-input {
            display: flex;
            border-top: 1px solid #ccc;
            padding: 8px; /* Thêm padding để input không dính vào viền */
            align-items: flex-end; /* Căn chỉnh các phần tử ở dưới cùng */
        }
        .chat-input textarea {
            flex: 1;
            border: 1px solid #ddd; /* Thêm border để input rõ hơn */
            border-radius: 15px;
            padding: 8px 12px;
            resize: none;
            overflow: hidden; /* Ẩn thanh cuộn mặc định */
            min-height: 38px; /* Chiều cao tối thiểu */
            max-height: 100px; /* Chiều cao tối đa */
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
        .d-none { display: none !important; }
        
        /* CSS cho hiệu ứng tải tin nhắn */
        .loading-dots {
            align-self: flex-start;
            background-color: #f0f0f0;
            padding: 8px 12px;
            border-radius: 15px;
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
        .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loading-dots span:nth-child(2) { animation-delay: -0.16s; }
        .loading-dots span:nth-child(3) { animation-delay: 0s; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1.0); }
        }
    </style>

        <!-- Footer chính giống IVY moda -->
<!-- Footer chính giống IVY moda -->
<div class="container py-5 border-top">
  <div class="row text-start">
    <!-- Cột Giới thiệu -->
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

    <!-- Cột Dịch vụ khách hàng -->
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

    <!-- Cột Liên hệ -->
    <div class="col-md-3 mb-4">
      <h5 class="fw-bold">Liên hệ</h5>
      <ul class="list-unstyled">
        <li><a href="#" class="text-white text-decoration-none">📞 Hotline: @ ### ### ###</a></li>
        <li><a href="#" class="text-white text-decoration-none">✉ Email: hotro@blshop.vn</a></li>
        <li><a href="#" class="text-white text-decoration-none">💬 Live Chat</a></li>
        <li><a href="#" target="_blank" class="text-white text-decoration-none">💙 Messenger</a></li>
        <li><a href="#" class="text-white text-decoration-none">📍 Liên hệ trực tiếp</a></li>
      </ul>

      <!-- Social icons -->
      <div class="d-flex gap-3 mt-3">
        <a href="https://www.facebook.com/tuyensinhdaihoc.fbu" target="_blank" class="text-white fs-4"><i class="fa-brands fa-facebook"></i></a>
        <a href="https://chat.zalo.me/" target="_blank" class="text-white fs-4"><i class="fa-solid fa-comment"></i></a>
        <a href="https://www.youtube.com/watch?v=VoICTdqebcE" target="_blank" class="text-white fs-4"><i class="fa-brands fa-youtube"></i></a>
        <a href="https://www.instagram.com/tuanhdz.102/" target="_blank" class="text-white fs-4"><i class="fa-brands fa-instagram"></i></a>
      </div>
    </div>

    <!-- Cột Nhận thông tin + App -->
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
        <p>&copy; 2025 BL SHOP. All rights reserved.</p>
    </div>

    <!-- Nút icon -->
    <div class="fixed-bottom d-flex justify-content-end p-3" style="gap: 10px; z-index: 1100;">
        <button id="btnMap" class="btn btn-primary rounded-circle p-2" style="width: 40px; height: 40px;">
            <i class="fa-solid fa-map-location-dot"></i>
        </button>
        <button id="btnChat" class="btn btn-success rounded-circle p-2" style="width: 40px; height: 40px;">
            <i class="fa-solid fa-comments"></i>
        </button>
    </div>

    <!-- Popup Map -->
    <div id="popupMap" class="popup-box d-none">
        <div class="popup-header">
            <span>Bản đồ</span>
            <button class="btn-close" id="closeMap">×</button>
        </div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.1377084300183!2d105.781006!3d21.028511!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab4eafed9d41%3A0x1a1f37e2b3c1ab1f!2zQ8O0bmcgVHkgVE5ISCBBTkggVsawxqFuZw!5e0!3m2!1svi!2s!4v1700000000000" width="300" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>

    <!-- Popup Chat -->
    <div id="popupChat" class="popup-box d-none">
        <div class="popup-header">
            <span>Chat với chúng tôi</span>
            <button class="btn-close" id="closeChat">×</button>
        </div>
        <div class="chat-body" id="chatMessages"></div>
        <div class="chat-input">
            <textarea id="chatInput" rows="1" placeholder="Nhập tin nhắn..."></textarea>
            <button id="sendBtn">Gửi</button>
        </div>
    </div>

    <script>
        const popupMap = document.getElementById('popupMap');
        const popupChat = document.getElementById('popupChat');
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        let isLoading = false; // Biến cờ để kiểm tra trạng thái gửi tin nhắn

        // Thêm tính năng tự động co giãn textarea
        chatInput.addEventListener('input', () => {
            chatInput.style.height = 'auto';
            chatInput.style.height = chatInput.scrollHeight + 'px';
        });

        // Thêm tính năng gửi tin nhắn khi nhấn Enter
        chatInput.addEventListener('keydown', (event) => {
            // Kiểm tra phím Enter và không phải Shift + Enter
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault(); // Ngăn chặn xuống dòng
                sendBtn.click(); // Gọi hàm click của nút Gửi
            }
        });

        document.getElementById('btnMap').onclick = () => {
            popupMap.classList.toggle('d-none');
            popupChat.classList.add('d-none');
        };
        document.getElementById('closeMap').onclick = () => popupMap.classList.add('d-none');

        document.getElementById('btnChat').onclick = () => {
            popupChat.classList.toggle('d-none');
            popupMap.classList.add('d-none');
            // Cải tiến: Chỉ load tin nhắn khi mở popup
            loadMessages();
        };
        document.getElementById('closeChat').onclick = () => popupChat.classList.add('d-none');
        
        // Cải tiến: Sử dụng hàm riêng để gửi tin nhắn
        sendBtn.onclick = () => {
            // Ngăn chặn gửi tin nhắn nếu đang có tin nhắn khác đang được gửi
            if (isLoading) return;

            let msg = chatInput.value.trim();
            if (msg) {
                // Hiển thị hiệu ứng tải
                showLoadingState();

                fetch(window.location.href, {
                    method: 'POST',
                    body: new URLSearchParams({message: msg})
                }).then(() => {
                    // Ẩn hiệu ứng tải và cập nhật tin nhắn
                    hideLoadingState();
                    chatInput.value = '';
                    chatInput.style.height = 'auto'; // Reset chiều cao
                    loadMessages();
                }).catch(error => {
                    console.error('Error sending message:', error);
                    hideLoadingState();
                });
            }
        };

        // Hàm để hiển thị hiệu ứng tải
        function showLoadingState() {
            isLoading = true;
            sendBtn.disabled = true; // Vô hiệu hóa nút gửi
            sendBtn.innerHTML = '...';
            // Thêm hiệu ứng loading vào chatbox
            chatMessages.innerHTML += `<div class="loading-dots"><span></span><span></span><span></span></div>`;
            scrollToBottom();
        }

        // Hàm để ẩn hiệu ứng tải
        function hideLoadingState() {
            isLoading = false;
            sendBtn.disabled = false;
            sendBtn.innerHTML = 'Gửi';
            // Xóa hiệu ứng loading khỏi chatbox
            const loadingDots = chatMessages.querySelector('.loading-dots');
            if (loadingDots) {
                loadingDots.remove();
            }
        }

        function loadMessages() {
            fetch(window.location.href + '?load_messages=1')
                .then(res => res.json())
                .then(data => {
                    // Kiểm tra xem nội dung có thay đổi không để tránh việc load lại toàn bộ
                    const currentChatContent = chatMessages.innerHTML;
                    const newChatContent = data.map(m => {
                        let senderClass = m.sender_id == <?php echo $sender_id; ?> ? 'user' : 'admin';
                        return `<div class="msg ${senderClass}">${m.message}</div>`;
                    }).join('');

                    if (currentChatContent !== newChatContent) {
                        chatMessages.innerHTML = newChatContent;
                        // Cải tiến: Chỉ cuộn xuống cuối khi người dùng đang ở cuối
                        scrollToBottom();
                    }
                })
                .catch(error => console.error('Error loading messages:', error));
        }

        // Cải tiến: Hàm cuộn xuống cuối thông minh
        function scrollToBottom() {
            // Kiểm tra xem người dùng có đang ở gần cuối không
            const isAtBottom = chatMessages.scrollHeight - chatMessages.clientHeight <= chatMessages.scrollTop + 100;
            if (isAtBottom) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

        // Tải tin nhắn lần đầu khi trang load xong
        window.onload = loadMessages;
        
        // Vẫn sử dụng setInterval để cập nhật tin nhắn định kỳ
        setInterval(loadMessages, 3000);
    </script>
</footer>
