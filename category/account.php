<?php
session_start();
require_once "connect.php"; // Kết nối CSDL

$error = "";

// Đăng nhập thường
if (isset($_POST['login_user'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['role'] === 'user') {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Sai tài khoản hoặc mật khẩu!";
    }
}

// Đăng nhập admin
if (isset($_POST['login_admin'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['role'] === 'admin') {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: admin/indexAd.php");
        exit();
    } else {
        $error = "Sai tài khoản hoặc mật khẩu Admin!";
    }
}

// Đăng ký
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $repass = trim($_POST['re_password']);

    if ($password !== $repass) {
        $error = "Mật khẩu nhập lại không khớp!";
    } else {
        $check = $conn->prepare("SELECT * FROM users WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows > 0) {
            $error = "Tên tài khoản đã tồn tại!";
        } else {
            $role = 'user';
            $insert = $conn->prepare("INSERT INTO users (username,password,role) VALUES (?,?,?)");
            $insert->bind_param("sss", $username, $password, $role);
            $insert->execute();
            $error = "Đăng ký thành công! Hãy đăng nhập.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautiful Love - Nam</title>
    <link rel="icon" href="../image/logo/Logo.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>  <style>
    body {
      margin: 0;
      height: 100vh;
      background: url('../image/account/nenTraiTim.jpg') center/cover no-repeat;
      font-family: Arial, sans-serif;
    }
    .page-container {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      height: 100vh;
      padding: 0 8%;
    }
    .account-box {
      width: 100%;
      max-width: 320px;
      background: rgba(237, 202, 224, 0.95);
      padding: 25px;
      border-radius: 50px 0 50px 0;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    .form-control, .btn { border-radius: 10px; }
    .form-toggle-link {
      cursor: pointer;
      color: #0d6efd;
      text-decoration: underline;
    }
    .form-toggle-link:hover { color: #0b5ed7; }
    #error-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1050; /* Hiển thị trên tất cả */
        min-width: 300px;
        max-width: 90%;
    }

  </style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <?php include 'header.php'; ?>
  <main style="padding-top:20px;">
    <div class="page-container">
      <div class="account-box">
        <?php if (!empty($error)) : ?>
            <div id="error-popup" class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

        <!-- Đăng nhập thường -->
        <div id="login-user">
          <h4 class="text-center mb-4"><strong>Đăng nhập</strong></h4>
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Tên đăng nhập</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Mật khẩu</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button name="login_user" class="btn btn-primary w-100">Đăng nhập</button>
          </form>
          <p class="text-center mt-3"><small>Bạn chưa có tài khoản? 
            <span class="form-toggle-link" onclick="showForm('register')">Đăng ký ngay</span></small></p>
          <p class="text-center">Đăng nhập với tư cách <small><span class="form-toggle-link" onclick="showForm('login-admin')">Quản trị viên</span></small></p>
        </div>

        <!-- Đăng nhập admin -->
        <div id="login-admin" class="d-none">
          <h4 class="text-center mb-4"><strong>Đăng nhập Admin</strong></h4>
          <form method="post">
            <div class="mb-3">
              <input type="text" name="username" class="form-control" placeholder="Tên Admin" required>
            </div>
            <div class="mb-3">
              <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
            </div>
            <button name="login_admin" class="btn btn-danger w-100">Đăng nhập Admin</button>
          </form>
          <p class="text-center mt-3"><small><span class="form-toggle-link" onclick="showForm('login-user')">Quay lại đăng nhập thường</span></small></p>
        </div>

        <!-- Đăng ký -->
        <div id="register" class="d-none">
          <h4 class="text-center mb-4"><strong>Đăng ký<strong></h4>
          <form method="post">
            <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" required></div>
            <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Mật khẩu" required></div>
            <div class="mb-3"><input type="password" name="re_password" class="form-control" placeholder="Nhập lại mật khẩu" required></div>
            <button name="register" class="btn btn-success w-100">Đăng ký</button>
          </form>
          <p class="text-center mt-3"><small>Đã có tài khoản? 
            <span class="form-toggle-link" onclick="showForm('login-user')">Đăng nhập</span></small></p>
        </div>
      </div>
    </div>
  </main>

  <script>
    function showForm(formId) {
      document.getElementById('login-user').classList.add('d-none');
      document.getElementById('login-admin').classList.add('d-none');
      document.getElementById('register').classList.add('d-none');
      document.getElementById(formId).classList.remove('d-none');
    }
  </script>
</body>
</html>
