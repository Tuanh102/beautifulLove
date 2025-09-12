<?php
session_start();

// Khởi tạo giỏ hàng nếu chưa có
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Lấy dữ liệu POST
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity   = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$color      = isset($_POST['color']) ? $_POST['color'] : '';
$size       = isset($_POST['size']) ? $_POST['size'] : '';

if($product_id <= 0) {
    echo json_encode(['success'=>false, 'message'=>'ID sản phẩm không hợp lệ']);
    exit;
}

// Kết nối DB
include 'connect.php';

// Lấy thông tin sản phẩm
$table = isset($_POST['table']) ? $_POST['table'] : '';
$allowed_tables = ['pd_boy', 'pd_girl'];
if (!in_array($table, $allowed_tables)) {
    echo json_encode(['success'=>false, 'message'=>'Bảng sản phẩm không hợp lệ']);
    exit;
}

$stmt = $conn->prepare("SELECT img_name, img_price FROM $table WHERE img_id=?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows==0){
    echo json_encode(['success'=>false, 'message'=>'Sản phẩm không tồn tại']);
    exit;
}
$product = $result->fetch_assoc();


// Tạo unique ID cho sản phẩm dựa trên id + color + size
$unique_id = $product_id . '_' . $color . '_' . $size;

// Nếu đã có trong giỏ, cộng dồn số lượng
if(isset($_SESSION['cart'][$unique_id])) {
    $_SESSION['cart'][$unique_id]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$unique_id] = [
        'id'       => $product_id,
        'name'     => $product['img_name'],
        'price'    => $product['img_price'],
        'color'    => $color,
        'size'     => $size,
        'quantity' => $quantity
    ];
}

// Tính subtotal và total
$subtotal = 0;
foreach($_SESSION['cart'] as $item){
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping_fee = 30000;
$total = $subtotal + $shipping_fee;

// Trả về JSON
echo json_encode([
    'success' => true,
    'message' => 'Đã thêm vào giỏ hàng!',
    'subtotal'=> $subtotal,
    'total'   => $total,
    'total_items' => count($_SESSION['cart'])
]);
exit;
?>