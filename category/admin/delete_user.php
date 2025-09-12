<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../account.php");
    exit();
}

include '../connect.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    // Ngăn chặn admin tự xóa tài khoản của mình
    if ($user_id === $_SESSION['user_id']) {
        echo "Bạn không thể xóa tài khoản của chính mình.";
        exit();
    }

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        header("Location: indexAd.php?page=users");
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "ID tài khoản không hợp lệ.";
}

$conn->close();
?>