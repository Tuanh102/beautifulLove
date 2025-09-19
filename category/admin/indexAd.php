<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../account.php");
    exit();
}
include '../connect.php';

// Lấy page hiện tại
$current_page = isset($_GET['pageLayout']) ? $_GET['pageLayout'] : 'trangChu';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Beautiful Love Admin</title>
    <link rel="icon" href="../../image/logo/Logo.png"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            transition: color 0.3s, background-color 0.3s;
        }
        .sidebar .nav-link:hover {
            color: rgba(255, 255, 255, 1);
            background-color: rgba(255, 255, 255, 0.1);
        }
        /* Khi active thì màu sáng hơn */
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.25);
            font-weight: bold;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        /* Dark Mode */
        .dark-mode {
            background-color: #121212;
            color: white;
        }
        .dark-mode .sidebar {
            background-color: #212529;
        }
        .dark-mode .main-content {
            background-color: #1a1a1a;
        }
        .dark-mode .table-dark,
        .dark-mode .bg-dark {
            background-color: #333 !important;
        }
        .dark-mode .table,
        .dark-mode .card,
        .dark-mode .form-control,
        .dark-mode .form-select {
            background-color: #2b2b2b;
            color: white;
            border-color: #444;
        }
        .dark-mode .form-control:focus,
        .dark-mode .form-select:focus {
            background-color: #2b2b2b;
            color: white;
        }
        .dark-mode .alert-info {
            background-color: #1f3b57;
            border-color: #1f3b57;
            color: #cce5ff;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <nav class="sidebar">
        <h4 class="text-white text-center mb-4">Xin chào Admin</h4>
        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= ($current_page == 'trangChu') ? 'active' : '' ?>" href="indexAd.php?pageLayout=trangChu">
                    <i class="fas fa-home fa-lg me-3"></i>
                    <span>Trang chủ</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= ($current_page == 'sanPham') ? 'active' : '' ?>" href="indexAd.php?pageLayout=sanPham">
                    <i class="fas fa-box-open fa-lg me-3"></i>
                    <span>Sản phẩm</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= ($current_page == 'taiKhoan') ? 'active' : '' ?>" href="indexAd.php?pageLayout=taiKhoan">
                    <i class="fas fa-users fa-lg me-3"></i>
                    <span>Tài khoản</span>
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link d-flex align-items-center <?= ($current_page == 'settings') ? 'active' : '' ?>" href="indexAd.php?pageLayout=settings">
                    <i class="fas fa-cog fa-lg me-3"></i>
                    <span>Cài đặt</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center text-danger" href="../logout.php">
                    <i class="fas fa-sign-out-alt fa-lg me-3"></i>
                    <span>Đăng xuất</span>
                </a>
            </li>
        </ul>
    </nav>

    <main class="main-content">
        <?php
        if(isset($_GET['pageLayout'])){
            switch($_GET['pageLayout']){
                case 'trangChu': include 'home.php'; break;
                case 'sanPham': include 'product/product.php'; break;
                case 'taiKhoan': include 'account/account.php'; break;
                case 'settings': include 'settings.php'; break;
                default: include 'home.php'; break;
            }
        }else{
            include 'home.php';
        }
        ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    });
</script>
</body>
</html>
