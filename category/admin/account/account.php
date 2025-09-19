<?php
// Lấy dữ liệu từ bảng tài khoản (giả sử tên bảng là 'users')
// Bạn có thể thay đổi 'users' thành tên bảng của bạn nếu cần
$sql = "SELECT id, username, role FROM users ORDER BY id DESC";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<div class="container-fluid p-4">
    <h2 class="bg-dark text-white p-3 rounded mb-4">Quản lý Tài khoản</h2>
    
    <div class="d-flex justify-content-end mb-3">
        <a href="account/addAccount.php" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Thêm tài khoản
        </a>
    </div>

    <?php
    if (isset($_GET['message'])) {
        $message_text = '';
        $alert_type = '';
        if ($_GET['message'] === 'add_success') {
            $message_text = 'Thêm tài khoản thành công!';
            $alert_type = 'success';
        } elseif ($_GET['message'] === 'delete_success') {
            $message_text = 'Xóa tài khoản thành công!';
            $alert_type = 'success';
        }
        if ($message_text) {
            echo '<div class="alert alert-' . $alert_type . ' alert-dismissible fade show" role="alert">' . $message_text . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
    }
    ?>
    
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Quyền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="4" class="text-center">Chưa có tài khoản nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <a href="account/editAccount.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="account/deleteAccount.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?');">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>