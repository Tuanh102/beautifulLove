<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../account.php");
    exit();
}

include '../connect.php';

$message = '';
$product = null;
$gender = isset($_GET['gender']) ? $_GET['gender'] : 'boy';
$table_name = ($gender === 'girl') ? 'pd_girl' : 'pd_boy';
$product_type = ($gender === 'girl') ? 'Nữ' : 'Nam';
$upload_dir = ($gender === 'girl') ? '../images/girl/' : '../images/boy/';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $sql = "SELECT * FROM $table_name WHERE img_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $message = "Không tìm thấy sản phẩm này.";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product) {
    $product_id = intval($_POST['id']);
    $img_name = $_POST['img_name'];
    $img_price = $_POST['img_price'];
    $img_product = $_POST['img_product'];
    $img_caption = $_POST['img_caption'];
    $img_status = $_POST['img_status'];
    $img_url = $product['img_url']; // Mặc định giữ lại ảnh cũ

    if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] === UPLOAD_ERR_OK) {
        $file_name = uniqid() . '-' . basename($_FILES['img_file']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['img_file']['tmp_name'], $target_file)) {
            // Xóa ảnh cũ
            if (file_exists("../" . $product['img_url'])) {
                unlink("../" . $product['img_url']);
            }
            $img_url = 'images/' . $gender . '/' . $file_name;
        } else {
            $message = "Có lỗi khi tải lên ảnh mới.";
        }
    }

    $sql = "UPDATE $table_name SET img_name=?, img_price=?, img_product=?, img_caption=?, img_status=?, img_url=? WHERE img_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssi", $img_name, $img_price, $img_product, $img_caption, $img_status, $img_url, $product_id);

    if ($stmt->execute()) {
        header("Location: indexAd.php?page=products&gender=" . $gender . "&message=edit_success");
        exit();
    } else {
        $message = "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm <?php echo $product_type; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style> body { padding: 20px; } .product-img { max-width: 200px; height: auto; } </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Sửa sản phẩm <?php echo $product_type; ?></h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($product): ?>
        <form action="edit_product.php?gender=<?php echo $gender; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['img_id']; ?>">
            <div class="mb-3 text-center">
                <img src="../<?php echo htmlspecialchars($product['img_url']); ?>" alt="Ảnh sản phẩm" class="product-img">
            </div>
            <div class="mb-3">
                <label for="img_name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="img_name" name="img_name" value="<?php echo htmlspecialchars($product['img_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="img_file" class="form-label">Chọn ảnh mới (nếu muốn thay đổi)</label>
                <input type="file" class="form-control" id="img_file" name="img_file">
            </div>
            <div class="mb-3">
                <label for="img_price" class="form-label">Giá (VNĐ)</label>
                <input type="number" class="form-control" id="img_price" name="img_price" value="<?php echo htmlspecialchars($product['img_price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="img_product" class="form-label">Loại sản phẩm</label>
                <input type="text" class="form-control" id="img_product" name="img_product" value="<?php echo htmlspecialchars($product['img_product']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="img_caption" class="form-label">Mô tả</label>
                <textarea class="form-control" id="img_caption" name="img_caption" rows="3" required><?php echo htmlspecialchars($product['img_caption']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="img_status" class="form-label">Trạng thái</label>
                <input type="text" class="form-control" id="img_status" name="img_status" value="<?php echo htmlspecialchars($product['img_status']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
            <a href="indexAd.php?page=products&gender=<?php echo $gender; ?>" class="btn btn-secondary">Hủy</a>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>