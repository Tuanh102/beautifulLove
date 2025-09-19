<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../account.php");
    exit();
}
include '../../connect.php';

if (isset($_GET['id'])) {
    $user_id_to_delete = $_GET['id'];
    $current_admin_id = $_SESSION['user_id']; // Giả sử user_id được lưu trong session

    // Ngăn admin tự xóa tài khoản của mình
    if ($user_id_to_delete == $current_admin_id) {
        echo "Bạn không thể tự xóa tài khoản của mình!";
        exit();
    }
    
    // Xóa tài khoản
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $user_id_to_delete);
        if ($stmt->execute()) {
            header("Location: ../indexAd.php?pageLayout=taiKhoan&message=delete_success");
            exit();
        } else {
            echo "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi chuẩn bị truy vấn: " . $conn->error;
    }
} else {
    echo "Thiếu ID để xóa tài khoản.";
}

$conn->close();
?>