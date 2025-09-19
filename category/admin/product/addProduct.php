<?php
// Bắt đầu session và kiểm tra quyền truy cập admin
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../account.php");
    exit();
}
include '../../connect.php';

// Kiểm tra biến gender từ URL
$gender = isset($_GET['gender']) && ($_GET['gender'] === 'girl' || $_GET['gender'] === 'boy') ? $_GET['gender'] : 'boy';
$table_name = ($gender === 'girl') ? 'pd_girl' : 'pd_boy';
$product_type = ($gender === 'girl') ? 'Nữ' : 'Nam';

// Xử lý khi form được gửi
$message = '';
$alert_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $img_name = $_POST['img_name'];
    $img_price = $_POST['img_price'];
    $img_caption = $_POST['img_caption'];
    $img_product = $_POST['img_product'];
    $img_status = $_POST['img_status'];

    // Xử lý upload ảnh
    $target_dir = "../../../image/product_img/";
    $img_url = 'image/product_img/' . basename($_FILES["img_file"]["name"]);
    $target_file = $target_dir . basename($_FILES["img_file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra và di chuyển file
    if (!empty($_FILES["img_file"]["tmp_name"])) {
        if (move_uploaded_file($_FILES["img_file"]["tmp_name"], $target_file)) {
            // Thêm sản phẩm vào CSDL
            $sql = "INSERT INTO $table_name (img_name, img_url, img_price, img_caption, img_product, img_status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssiiss", $img_name, $img_url, $img_price, $img_caption, $img_product, $img_status);
                if ($stmt->execute()) {
                    // Redirect về trang quản lý sản phẩm với thông báo thành công
                    header("Location: ../indexAd.php?pageLayout=sanPham&gender=$gender&message=add_success");
                    exit();
                } else {
                    $message = "Lỗi: " . $stmt->error;
                    $alert_type = "danger";
                }
                $stmt->close();
            } else {
                $message = "Lỗi chuẩn bị truy vấn: " . $conn->error;
                $alert_type = "danger";
            }
        } else {
            $message = "Đã xảy ra lỗi khi tải lên ảnh.";
            $alert_type = "danger";
        }
    } else {
        $message = "Vui lòng chọn một file ảnh.";
        $alert_type = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm <?php echo $product_type; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .container { max-width: 800px; }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="bg-dark text-white p-3 rounded mb-4">Thêm sản phẩm <?php echo $product_type; ?></h2>
        <a href="indexAd.php?pageLayout=sanPham&gender=<?php echo $gender; ?>" class="btn btn-secondary mb-3">Quay lại</a>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="addProduct.php?gender=<?php echo $gender; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="img_name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="img_name" name="img_name" required>
            </div>
            <div class="mb-3">
                <label for="img_file" class="form-label">Chọn ảnh</label>
                <input type="file" class="form-control" id="img_file" name="img_file" required>
            </div>
            <div class="mb-3">
                <label for="img_price" class="form-label">Giá (VNĐ)</label>
                <input type="number" class="form-control" id="img_price" name="img_price" required>
            </div>
            <div class="mb-3">
                <label for="img_caption" class="form-label">Mô tả</label>
                <textarea class="form-control" id="img_caption" name="img_caption" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="img_product" class="form-label">Loại sản phẩm</label>
                <select class="form-select" id="img_product" name="img_product" required>
                    <option value="">-- Chọn loại --</option>
                    <option value="1">Đồ bộ</option>
                    <option value="2">Áo</option>
                    <option value="3">Quần</option>
                    <option value="4">Áo khoác</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="img_status" id="status_good" value="good" checked>
                    <label class="form-check-label" for="status_good">Sản phẩm nổi bật</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="img_status" id="status_not" value="not">
                    <label class="form-check-label" for="status_not">Sản phẩm thường</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>