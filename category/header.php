<?php
$current_page = basename($_SERVER['PHP_SELF']); // Lấy tên file hiện tại

$total_cart_items = 0;
if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $total_cart_items += $item['quantity'];
    }
}
?>
<header class="main-header <?php echo ($current_page == 'index.php') ? 'no-bg' : ''; ?>">
    <style>
        /* ====== HEADER CHUNG ====== */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            padding: 8px 0;
            transition: background 0.3s, box-shadow 0.3s;
        }

        /* Nền mặc định (trang khác index) */
        .main-header:not(.no-bg) {
            background: #212529;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Nền trong suốt khi ở index.php */
        .main-header.no-bg {
            background: transparent;
            box-shadow: none;
        }

        /* Logo */
        .main-header img {
            max-height: 44px;
        }

        /* Bố cục flex */
        .header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        /* Menu */
        nav a {
            font-size: 14px;
            margin-left: 16px;
            color: #fff;
            text-decoration: none;
            transition: color 0.2s, border-bottom 0.2s;
        }
        nav a.active {
            border-bottom: 2px solid #FF2400;
            color: #FF2400;
            font-weight: bold;
        }
        nav a:hover {
            color: #FF2400;
        }


        /* Link tiện ích */
        .utility-links a {
            font-size: 14px;
            margin-left: 16px;
            color: #fff;
            text-decoration: none;
        }


        /* Bọc logo trong khung tròn */
        .logo-circle {
            width: 40px;       /* đường kính khung */
            height: 40px;
            border-radius: 50%; 
            overflow: hidden;  /* ẩn phần ảnh thừa */
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid white /*#ff4d6d;*/ /* viền trang trí */
        }

        /* Logo bên trong */
        .logo-circle img {
            width: 100%; 
            height: auto;
        }

        /*Thanh tìm kiếm nhanh*/
        .menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .menu-item {
            position: relative;
            display: inline-block;
        }

        .menu-item > a {
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            color: #fff; /* chữ trắng */
            font-weight: bold;
        }

        .menu-item:hover > a {
            color: red;
        }

        /* Mega menu */
        .mega-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 400px; /* tổng 2 cột */
            background: #111; /* nền đen */
            color: #fff;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            z-index: 999;
        }

        .mega-menu .column {
            float: left;
            width: 50%;
        }

        .mega-menu .column h4 {
            margin-top: 0;
            color: #fff;
        }

        .mega-menu .column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mega-menu .column ul li {
            margin-bottom: 10px;
        }

        .mega-menu .column ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 5px 0;
        }

        .mega-menu .column ul li a:hover {
            color: red;
        }

        .menu-item:hover .mega-menu {
            display: block;
        }

        /* Clear float */
        .mega-menu::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>

    <div class="container">
        <div class="header-flex">
            <!-- Logo -->
            <a href="index.php" class="logo-circle"><img src="../image/logo/Logo.png" alt="Logo"></a>

            <!-- Menu -->
            <nav>
                <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">TRANG CHỦ</a>
                <a href="girl.php" class="<?php echo ($current_page == 'girl.php') ? 'active' : ''; ?>">NỮ</a>
                <a href="boy.php" class="<?php echo ($current_page == 'boy.php') ? 'active' : ''; ?>">NAM</a>
                <a href="introduce.php" class="<?php echo ($current_page == 'introduce.php') ? 'active' : ''; ?>">GIỚI THIỆU</a>
                <a href="news.php" class="<?php echo ($current_page == 'news.php') ? 'active' : ''; ?>">TIN THỜI TRANG</a>
            </nav>

            <nav class="header-nav">
                <ul class="menu">
                    <li class="menu-item">
                        <a href="#"><i class="fa fa-search"></i> <i class="fa fa-caret-down"></i></a>
                        <div class="mega-menu">
                            <div class="column">
                                <h4>Nữ</h4>
                                <ul>
                                    <li><a href="girl.php?type=1">Đầm</a></li>
                                    <li><a href="girl.php?type=2">Áo</a></li>
                                    <li><a href="girl.php?type=3">Quần</a></li>
                                    <li><a href="girl.php?type=4">Chân váy</a></li>
                                    <li><a href="girl.php?type=5">Áo khoác</a></li>
                                </ul>
                            </div>
                            <div class="column">
                                <h4>Nam</h4>
                                <ul>
                                    <li><a href="boy.php?type=1">Đồ bộ</a></li>
                                    <li><a href="boy.php?type=2">Áo</a></li>
                                    <li><a href="boy.php?type=3">Quần</a></li>
                                    <li><a href="boy.php?type=4">Áo khoác</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Liên kết tiện ích -->
            <div class="utility-links" style="display: flex; align-items: center; gap: 30px;">
                <a href="cart.php" style="position: relative; display: inline-block;">
                    <i class="fa-solid fa-cart-shopping fa-lg"></i>
                    <?php if($total_cart_items > 0): ?>
                        <span style="
                            position: absolute;
                            top: -8px;
                            right: -10px;
                            background: red;
                            color: white;
                            border-radius: 50%;
                            padding: 2px 6px;
                            font-size: 12px;
                            font-weight: bold;
                        ">
                            <?php echo $total_cart_items; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="account.php"><i class="fa-solid fa-user fa-lg"></i></a>
            </div>
        </div>
    </div>
</header>
