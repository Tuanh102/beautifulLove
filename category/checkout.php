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
        $size  = $_POST['size'];


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
        <title>Beautiful Love</title>
        <link rel="icon" href="../image/logo/Logo.png"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    </head>
    <body>
        
        <main class="padding-top: 100px !important;">
            <div class="container mt-5">
                <h2>Nhập thông tin quý khách</h2><br>

                <?php if(empty($display_items)): ?>
                <div class="alert alert-info">Không có sản phẩm để thanh toán.</div>
                <?php else: ?>
                <ul class="list-group mb-3">
                    <?php foreach($display_items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                    <div>
                    <h6 class="my-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                    <small class="text-muted">Màu: <?php echo htmlspecialchars($item['color']); ?>, Size: <?php echo htmlspecialchars($item['size']); ?> x <?php echo htmlspecialchars($item['quantity']); ?></small>
                    </div>
                    </div>
                    <span class="text-muted"><?php echo number_format($item['price']*$item['quantity'], 0, ",", "."); ?> đ</span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                    <span>Tổng tiền sản phẩm:</span>
                    <strong><?php echo number_format($subtotal,0,",","."); ?> đ</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                    <span>Phí vận chuyển:</span>
                    <strong><?php echo number_format($shipping_fee,0,",","."); ?> đ</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                    <span>Tổng cộng:</span>
                    <strong><?php echo number_format($total,0,",","."); ?> đ</strong>
                    </li>
                </ul>

                <form method="POST">
                    <div class="mb-3">
                    <label>Họ tên:</label>
                    <input type="text" name="fullname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                    <label>Địa chỉ:</label>
                    <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="mb-3">
                    <label>Số điện thoại:</label>
                    <input type="text" name="phone" class="form-control" required>
                    </div>

                    <button type="submit" name="checkout" class="btn btn-dark w-100" 
                            onclick="event.preventDefault(); 
                                    alert('Đơn hàng đã được đặt!');
                                    window.location.href='index.php';">
                        Xác nhận thanh toán
                    </button>
                    <center><a href="index.php" style="color: black;">Tiếp tục mua sắm</a></center>
                </form>
                <?php endif; ?>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </body>
</html>