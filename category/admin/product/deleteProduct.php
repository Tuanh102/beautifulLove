<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../account.php");
    exit();
}

include '../../connect.php';

// Kiểm tra xem ID và gender có được truyền vào không
if (isset($_GET['id']) && isset($_GET['gender'])) {
    $img_id = $_GET['id'];
    $gender = $_GET['gender'];

    // Xác định tên bảng dựa trên giới tính
    $table_name = ($gender === 'girl') ? 'pd_girl' : 'pd_boy';

    // Chuẩn bị câu lệnh SQL để xóa sản phẩm
    $sql = "DELETE FROM $table_name WHERE img_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $img_id);
        if ($stmt->execute()) {
            // Chuyển hướng về trang quản lý sản phẩm với thông báo thành công
            header("Location: ../indexAd.php?pageLayout=sanPham&gender=$gender&message=delete_success");
            exit();
        } else {
            echo "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi chuẩn bị truy vấn: " . $conn->error;
    }
} else {
    echo "Thiếu ID hoặc giới tính để xóa sản phẩm.";
}

$conn->close();
?>