<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../account.php");
    exit();
}

include '../../connect.php';

// Lấy ID và gender từ URL
$img_id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['img_id']) ? $_POST['img_id'] : null);
$gender = isset($_GET['gender']) ? $_GET['gender'] : (isset($_POST['gender']) ? $_POST['gender'] : 'boy');

if (!$img_id) {
    echo "Thiếu ID sản phẩm.";
    exit();
}

// Xác định tên bảng
$table_name = ($gender === 'girl') ? 'pd_girl' : 'pd_boy';
$product_type = ($gender === 'girl') ? 'Nữ' : 'Nam';

$product = null;
$message = '';
$alert_type = '';

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $img_name = $_POST['img_name'];
    $img_price = $_POST['img_price'];
    $img_caption = $_POST['img_caption'];
    $img_product = $_POST['img_product'];
    $img_status = $_POST['img_status'];
    $current_img_url = $_POST['current_img_url'];
    $img_url = $current_img_url; // Mặc định giữ lại ảnh cũ

    // Xử lý upload ảnh mới nếu có
    if (!empty($_FILES["img_file"]["name"])) {
        $target_dir = "../../image/product/";
        $new_img_url = 'image/product/' . basename($_FILES["img_file"]["name"]);
        $target_file = $target_dir . basename($_FILES["img_file"]["name"]);

        if (move_uploaded_file($_FILES["img_file"]["tmp_name"], $target_file)) {
            $img_url = $new_img_url;
        } else {
            $message = "Đã xảy ra lỗi khi tải lên ảnh mới.";
            $alert_type = "danger";
        }
    }

    // Cập nhật sản phẩm vào CSDL
    if (empty($message)) {
        $sql = "UPDATE $table_name SET img_name=?, img_url=?, img_price=?, img_caption=?, img_product=?, img_status=? WHERE img_id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssiissi", $img_name, $img_url, $img_price, $img_caption, $img_product, $img_status, $img_id);
            if ($stmt->execute()) {
                header("Location: ../indexAd.php?pageLayout=sanPham&gender=$gender&message=edit_success");
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
    }
} else {
    // Lấy thông tin sản phẩm để hiển thị trên form
    $sql = "SELECT * FROM $table_name WHERE img_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $img_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        } else {
            echo "Không tìm thấy sản phẩm.";
            exit();
        }
        $stmt->close();
    } else {
        echo "Lỗi chuẩn bị truy vấn: " . $conn->error;
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm <?php echo $product_type; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .container { max-width: 800px; }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="bg-dark text-white p-3 rounded mb-4">Sửa sản phẩm <?php echo $product_type; ?></h2>
        <a href="indexAd.php?pageLayout=sanPham&gender=<?php echo $gender; ?>" class="btn btn-secondary mb-3">Quay lại</a>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($product): ?>
        <form action="editProduct.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="img_id" value="<?php echo htmlspecialchars($product['img_id']); ?>">
            <input type="hidden" name="gender" value="<?php echo htmlspecialchars($gender); ?>">
            <input type="hidden" name="current_img_url" value="<?php echo htmlspecialchars($product['img_url']); ?>">

            <div class="mb-3">
                <label for="img_name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="img_name" name="img_name" value="<?php echo htmlspecialchars($product['img_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label d-block">Ảnh hiện tại</label>
                <img src="../../<?php echo htmlspecialchars($product['img_url']); ?>" alt="Ảnh sản phẩm" style="width: 100px; height: auto; margin-bottom: 10px;">
                <label for="img_file" class="form-label">Chọn ảnh mới (để trống nếu không thay đổi)</label>
                <input type="file" class="form-control" id="img_file" name="img_file">
            </div>
            <div class="mb-3">
                <label for="img_price" class="form-label">Giá (VNĐ)</label>
                <input type="number" class="form-control" id="img_price" name="img_price" value="<?php echo htmlspecialchars($product['img_price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="img_caption" class="form-label">Mô tả</label>
                <textarea class="form-control" id="img_caption" name="img_caption" rows="3" required><?php echo htmlspecialchars($product['img_caption']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="img_product" class="form-label">Loại sản phẩm</label>
                <select class="form-select" id="img_product" name="img_product" required>
                    <option value="1" <?php if ($product['img_product'] == 1) echo 'selected'; ?>>Áo thun</option>
                    <option value="2" <?php if ($product['img_product'] == 2) echo 'selected'; ?>>Quần jeans</option>
                    <option value="3" <?php if ($product['img_product'] == 3) echo 'selected'; ?>>Áo khoác</option>
                    <option value="4" <?php if ($product['img_product'] == 4) echo 'selected'; ?>>Phụ kiện</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="img_status" id="status_good" value="good" <?php if ($product['img_status'] == 'good') echo 'checked'; ?>>
                    <label class="form-check-label" for="status_good">Sản phẩm nổi bật</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="img_status" id="status_not" value="not" <?php if ($product['img_status'] == 'not') echo 'checked'; ?>>
                    <label class="form-check-label" for="status_not">Sản phẩm thường</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
        </form>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>