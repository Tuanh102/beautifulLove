<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../account.php");
    exit();
}
include '../connect.php';

$message = '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : 'boy';
$table_name = ($gender === 'girl') ? 'pd_girl' : 'pd_boy';
$product_type = ($gender === 'girl') ? 'Nữ' : 'Nam';
$upload_dir = ($gender === 'girl') ? '../images/girl/' : '../images/boy/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $img_name = $_POST['img_name'];
    $img_price = $_POST['img_price'];
    $img_product = $_POST['img_product'];
    $img_caption = $_POST['img_caption'];
    $img_status = $_POST['img_status'];
    $img_url = '';

    if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] === UPLOAD_ERR_OK) {
        $file_name = uniqid() . '-' . basename($_FILES['img_file']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['img_file']['tmp_name'], $target_file)) {
            $img_url = 'images/' . $gender . '/' . $file_name;
        } else {
            $message = "Có lỗi khi tải lên ảnh.";
        }
    }

    if (!empty($img_url)) {
        $sql = "INSERT INTO $table_name (img_url, img_name, img_price, img_product, img_caption, img_status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisss", $img_url, $img_name, $img_price, $img_product, $img_caption, $img_status);

        if ($stmt->execute()) {
            header("Location: indexAd.php?page=products&gender=" . $gender . "&message=add_success");
            exit();
        } else {
            $message = "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm <?php echo $product_type; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style> body { padding: 20px; } </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Thêm sản phẩm <?php echo $product_type; ?></h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="add_product.php?gender=<?php echo $gender; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="img_name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="img_name" name="img_name" required>
            </div>
            <div class="mb-3">
                <label for="img_file" class="form-label">Ảnh sản phẩm</label>
                <input type="file" class="form-control" id="img_file" name="img_file" required>
            </div>
            <div class="mb-3">
                <label for="img_price" class="form-label">Giá (VNĐ)</label>
                <input type="number" class="form-control" id="img_price" name="img_price" required>
            </div>
            <div class="mb-3">
                <label for="img_product" class="form-label">Loại sản phẩm</label>
                <input type="text" class="form-control" id="img_product" name="img_product" required>
            </div>
            <div class="mb-3">
                <label for="img_caption" class="form-label">Mô tả</label>
                <textarea class="form-control" id="img_caption" name="img_caption" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="img_status" class="form-label">Trạng thái</label>
                <input type="text" class="form-control" id="img_status" name="img_status" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
            <a href="admin.php?page=products&gender=<?php echo $gender; ?>" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>