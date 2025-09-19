<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../account.php");
    exit();
}
include '../../connect.php';

$message = '';
$alert_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($username) || empty($password)) {
        $message = "Tên đăng nhập và mật khẩu không được để trống!";
        $alert_type = "danger";
    } else {
        // Mã hóa mật khẩu trước khi lưu vào CSDL
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("sss", $username, $hashed_password, $role);
            if ($stmt->execute()) {
                // Chuyển hướng về trang quản lý tài khoản với thông báo thành công
                header("Location: ../indexAd.php?pageLayout=taiKhoan&message=add_success");
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
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="bg-dark text-white p-3 rounded mb-4">Thêm tài khoản</h2>
        <a href="../indexAd.php?pageLayout=taiKhoan" class="btn btn-secondary mb-3">Quay lại</a>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="addAccount.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Quyền</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user">Người dùng</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>