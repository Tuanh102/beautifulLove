<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../account.php");
    exit();
}

include '../connect.php';

$message = '';
$user = null;

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    // Lấy thông tin tài khoản hiện tại từ CSDL
    $sql = "SELECT id, username, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $message = "Không tìm thấy tài khoản.";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $user_id = intval($_POST['id']);
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $sql = "";

    // Cập nhật tài khoản
    if (!empty($password)) {
        // Cập nhật cả mật khẩu nếu có
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, role=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $role, $hashed_password, $user_id);
    } else {
        // Chỉ cập nhật tên đăng nhập và quyền
        $sql = "UPDATE users SET username=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $role, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: indexAd.php?page=users&message=edit_success");
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
    <title>Sửa tài khoản</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style> body { padding: 20px; } </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Sửa tài khoản</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($user): ?>
        <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Quyền</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user" <?php echo ($user['role'] === 'user' ? 'selected' : ''); ?>>Người dùng</option>
                    <option value="admin" <?php echo ($user['role'] === 'admin' ? 'selected' : ''); ?>>Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật tài khoản</button>
            <a href="indexAd.php?page=users" class="btn btn-secondary">Hủy</a>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>