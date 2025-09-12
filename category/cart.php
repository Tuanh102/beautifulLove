<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$shipping_fee = 30000;

function getCartSubtotal() {
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    return $subtotal;
}

$subtotal = getCartSubtotal();
$total = $subtotal > 0 ? $subtotal + $shipping_fee : 0;

// Xử lý cập nhật / xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $key = $_POST['key'] ?? '';
    if ($action === 'update' && isset($_POST['quantity'])) {
        $_SESSION['cart'][$key]['quantity'] = max(1, intval($_POST['quantity']));
    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$key]);
    }
    header("Location: cart.php");
    exit;
}

// --- Sản phẩm nổi bật Nam ---
$featured_male = [];
$sql_male = "SELECT * FROM pd_boy WHERE img_status='good' LIMIT 4";
$result_male = $conn->query($sql_male);
if ($result_male && $result_male->num_rows > 0) {
    while($row = $result_male->fetch_assoc()) {
        $featured_male[] = $row;
    }
}

// --- Sản phẩm nổi bật Nữ ---
$featured_female = [];
$sql_female = "SELECT * FROM pd_girl WHERE img_status='good' LIMIT 4";
$result_female = $conn->query($sql_female);
if ($result_female && $result_female->num_rows > 0) {
    while($row = $result_female->fetch_assoc()) {
        $featured_female[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Beautiful Love</title>
    <link rel="icon" href="../image/logo/Logo.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
    <style>
        .product-hover {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 8px;
            background: #fff;
            padding: 10px;
        }
        .product-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .swiper-slide { text-align: center; }
        .swiper-slide img { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main style="padding-top: 80px;">
<div class="container mt-5">

    <h2 class="text-center mb-4">Giỏ hàng của bạn</h2>

    <?php if(empty($_SESSION['cart'])): ?>
        <div class="alert alert-info text-center">
            Giỏ hàng trống. <a href="index.php">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <ul class="list-group mb-3">
                    <?php foreach($_SESSION['cart'] as $key=>$item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6><?php echo htmlspecialchars($item['name']); ?></h6>
                                <small>Màu: <?php echo htmlspecialchars($item['color']); ?> | Size: <?php echo htmlspecialchars($item['size']); ?></small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span><?php echo number_format($item['price'],0,",","."); ?> đ</span>
                                <form method="POST" style="display:flex; gap:5px;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="key" value="<?php echo $key; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width:60px;">
                                    <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="key" value="<?php echo $key; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-lg-4">
                <div class="card p-3">
                    <h5>Tóm tắt đơn hàng</h5>
                    <hr>
                    <p>Tổng tiền sản phẩm: <strong><?php echo number_format($subtotal,0,",","."); ?> đ</strong></p>
                    <p>Phí vận chuyển: <strong><?php echo number_format($shipping_fee,0,",","."); ?> đ</strong></p>
                    <hr>
                    <p>Tổng cộng: <strong><?php echo number_format($total,0,",","."); ?> đ</strong></p>
                    <form method="POST" action="checkout.php">
                        <button type="submit" class="btn btn-success w-100">Tiến hành thanh toán</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <h3 class="mb-3 text-center mt-5">Sản phẩm nổi bật Nam</h3>
    <div class="swiper mySwiperMale">
        <div class="swiper-wrapper">
            <?php
            $sql_male = "SELECT * FROM pd_boy WHERE img_status=1";
            $res_male = $conn->query($sql_male);
            if ($res_male && $res_male->num_rows > 0) {
                while($product = $res_male->fetch_assoc()) {
                    echo '<div class="swiper-slide">';
                    echo '<div class="product-hover">';
                    echo '<img src="'.$product['img_url'].'" class="img-fluid mb-2">';
                    echo '<h6>'.htmlspecialchars($product['img_name']).'</h6>';
                    echo '<p class="text-danger"><strong>'.number_format($product['img_price'],0,",",".").' đ</strong></p>';
                    echo '<a href="pay.php?id='.$product['img_id'].'&table=pd_boy" class="btn btn-primary btn-sm">Mua ngay</a>';
                    echo '</div></div>';
                }
            }
            ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <h3 class="mb-3 text-center mt-5">Sản phẩm nổi bật Nữ</h3>
    <div class="swiper mySwiperFemale">
        <div class="swiper-wrapper">
            <?php
            $sql_female = "SELECT * FROM pd_girl WHERE img_status=1";
            $res_female = $conn->query($sql_female);
            if ($res_female && $res_female->num_rows > 0) {
                while($product = $res_female->fetch_assoc()) {
                    echo '<div class="swiper-slide">';
                    echo '<div class="product-hover">';
                    echo '<img src="'.$product['img_url'].'" class="img-fluid mb-2">';
                    echo '<h6>'.htmlspecialchars($product['img_name']).'</h6>';
                    echo '<p class="text-danger"><strong>'.number_format($product['img_price'],0,",",".").' đ</strong></p>';
                    echo '<a href="pay.php?id='.$product['img_id'].'&table=pd_girl" class="btn btn-primary btn-sm">Mua ngay</a>';
                    echo '</div></div>';
                }
            }
            ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

</div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
var swiperMale = new Swiper(".mySwiperMale", {
    slidesPerView: 6,
    spaceBetween: 15,
    navigation: {nextEl: ".mySwiperMale .swiper-button-next", prevEl: ".mySwiperMale .swiper-button-prev"},
    loop: true,
    breakpoints: {
        1200:{slidesPerView:6},
        992:{slidesPerView:4},
        768:{slidesPerView:3},
        0:{slidesPerView:1}
    }
});

var swiperFemale = new Swiper(".mySwiperFemale", {
    slidesPerView: 6,
    spaceBetween: 15,
    navigation: {nextEl: ".mySwiperFemale .swiper-button-next", prevEl: ".mySwiperFemale .swiper-button-prev"},
    loop: true,
    breakpoints: {
        1200:{slidesPerView:6},
        992:{slidesPerView:4},
        768:{slidesPerView:3},
        0:{slidesPerView:1}
    }
});
</script>

</body>
</html>
