<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../account.php");
    exit();
}
include '../connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Giữ nguyên dạng text
    $role = $_POST['role'];

    // Kiểm tra tên người dùng đã tồn tại chưa
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $message = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
    } else {
        // Thêm tài khoản mới vào CSDL (mật khẩu dạng text)
        $insert_sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sss", $username, $password, $role);

        if ($insert_stmt->execute()) {
            header("Location: indexAd.php?page=users&message=add_success");
            exit();
        } else {
            $message = "Lỗi: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    }
    $check_stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm tài khoản mới</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style> body { padding: 20px; } </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Thêm tài khoản mới</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="add_user.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="text" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Quyền</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user">Người dùng</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
            <a href="admin.php?page=users" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>
