<?php
session_start();
include 'connect.php';

// Biến lưu sản phẩm để hiển thị
$display_items = [];

// 1. Nếu đến từ nút Mua ngay (POST từ sản phẩm)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    $product_id = intval($_POST['id']);
    $quantity = intval($_POST['quantity']);
    $color = $_POST['color'];
    $size = $_POST['size'];

    $table = isset($_POST['table']) ? $_POST['table'] : '';
    $allowed_tables = ['pd_boy', 'pd_girl'];
    if (!in_array($table, $allowed_tables)) {
        die("Bảng không hợp lệ!");
    }
    
    $stmt = $conn->prepare("SELECT img_name, img_price, img_url FROM $table WHERE img_id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $p = $result->fetch_assoc();
        $display_items[] = [
            'id' => $product_id,
            'name' => $p['img_name'],
            'price' => $p['img_price'],
            'img_url' => $p['img_url'],
            'color' => $color,
            'size' => $size,
            'quantity' => $quantity
        ];
    }
}
// 2. Nếu vào từ giỏ hàng (không có POST Mua ngay)
else {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $display_items = $_SESSION['cart'];
    }
}

// 3. Tính tổng
$subtotal = 0;
foreach ($display_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping_fee = 30000;
$total = $subtotal > 0 ? $subtotal + $shipping_fee : 0;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Beautiful Love - Thanh toán</title>
        <link rel="icon" href="../image/logo/Logo.png"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <style>
            body {
                background-image: url('../image/checkOut/bodyCheckOut.jpg'); /* Thay URL ảnh của bạn vào đây */
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: #212529;
                font-family: sans-serif;
            }
            .checkout-container {
                max-width: 900px;
                width: 100%;
                background-color: rgba(255, 255, 255, 0.85); /* Nền trắng hơi trong suốt */
                backdrop-filter: blur(5px); /* Làm mờ nền phía sau */
                -webkit-backdrop-filter: blur(5px); /* Hỗ trợ Safari */
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            }
            .form-control {
                background-color: rgba(255, 255, 255, 0.7);
                border: 1px solid rgba(0,0,0,0.1);
            }
            .list-group-item {
                background-color: transparent;
                border: none;
                padding-left: 0;
                padding-right: 0;
            }
            .summary-list li {
                border-top: 1px dashed #ccc;
            }
            .summary-list li:last-child {
                font-size: 1.2rem;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="checkout-container">
            <h2 class="mb-4 text-center">Xác nhận đơn hàng</h2>
            <?php if (empty($display_items)): ?>
                <div class="alert alert-info text-center">
                    Giỏ hàng của bạn đang trống.
                    <br><a href="index.php" class="alert-link mt-2 d-inline-block">Quay lại mua sắm</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <div class="col-md-7">
                        <h4>Thông tin khách hàng</h4>
                        <hr>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Họ tên:</label>
                                <input type="text" name="fullname" id="fullname" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ:</label>
                                <input type="text" name="address" id="address" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại:</label>
                                <input type="text" name="phone" id="phone" class="form-control" required>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-5">
                        <h4>Chi tiết đơn hàng</h4>
                        <hr>
                        <ul class="list-group mb-3">
                            <?php foreach ($display_items as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="my-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                            <small class="text-muted">
                                                Màu: <?php echo htmlspecialchars($item['color']); ?>, 
                                                Size: <?php echo htmlspecialchars($item['size']); ?> 
                                                x <?php echo htmlspecialchars($item['quantity']); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <span class="text-muted">
                                        <?php echo number_format($item['price'] * $item['quantity'], 0, ",", "."); ?> đ
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <ul class="list-group summary-list">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Tổng tiền sản phẩm:</span>
                                <strong><?php echo number_format($subtotal, 0, ",", "."); ?> đ</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Phí vận chuyển:</span>
                                <strong><?php echo number_format($shipping_fee, 0, ",", "."); ?> đ</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Tổng cộng:</span>
                                <span class="fw-bold text-danger"><?php echo number_format($total, 0, ",", "."); ?> đ</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button type="submit" name="checkout" class="btn btn-dark btn-lg">
                        Xác nhận thanh toán
                    </button>
                    <a href="index.php" class="btn btn-link text-decoration-none text-dark d-block mt-3">
                        <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Lắng nghe sự kiện click trên nút xác nhận thanh toán
            document.querySelector('button[name="checkout"]').addEventListener('click', function(event) {
                // Ngăn form gửi đi
                event.preventDefault();
                
                // Hiển thị thông báo và chuyển hướng
                alert('Đơn hàng đã được đặt thành công!');
                window.location.href = 'index.php';
            });
        </script>
    </body>
</html>