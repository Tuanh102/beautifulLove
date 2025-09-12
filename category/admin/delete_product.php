<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../account.php");
    exit();
}

include '../connect.php';

if (isset($_GET['id']) && isset($_GET['gender'])) {
    $product_id = intval($_GET['id']);
    $gender = $_GET['gender'];

    // Xác định tên bảng dựa trên giới tính
    $table_name = ($gender === 'girl') ? 'pd_girl' : 'pd_boy';
    $image_path_prefix = ($gender === 'girl') ? 'images/girl/' : 'images/boy/';

    // Lấy thông tin sản phẩm để xóa ảnh
    $sql_select = "SELECT img_url FROM $table_name WHERE img_id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $product_id);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($result_select->num_rows > 0) {
        $row = $result_select->fetch_assoc();
        $image_to_delete = "../" . $row['img_url'];

        // Xóa sản phẩm khỏi database
        $sql_delete = "DELETE FROM $table_name WHERE img_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $product_id);

        if ($stmt_delete->execute()) {
            // Xóa file ảnh trên server
            if (file_exists($image_to_delete)) {
                unlink($image_to_delete);
            }
            header("Location: indexAd.php?page=products&gender=" . $gender);
            exit();
        } else {
            echo "Lỗi: " . $stmt_delete->error;
        }
        $stmt_delete->close();
    } else {
        echo "Không tìm thấy sản phẩm.";
    }
    $stmt_select->close();
} else {
    echo "ID sản phẩm hoặc giới tính không hợp lệ.";
}

$conn->close();
?>