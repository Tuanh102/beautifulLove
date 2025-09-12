<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../account.php");
    exit();
}

// Kết nối cơ sở dữ liệu
include '../connect.php';

// Xác định trang hiện tại (mặc định là 'home')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$page_title = 'Trang chủ';

// Các biến cho trang quản lý sản phẩm
$active_gender = 'boy';
$table_name = 'pd_boy';
$search_query = '';
$result = null;

if ($page === 'products') {
    // Xác định giới tính sản phẩm (mặc định là boy)
    $active_gender = isset($_GET['gender']) && $_GET['gender'] === 'girl' ? 'girl' : 'boy';
    $table_name = $active_gender === 'girl' ? 'pd_girl' : 'pd_boy';
    $page_title = $active_gender === 'girl' ? 'Quản lý sản phẩm Nữ' : 'Quản lý sản phẩm Nam';

    // Xử lý tìm kiếm
    $search_query = isset($_GET['search']) ? $_GET['search'] : '';

    // Truy vấn dữ liệu sản phẩm
    $sql = "SELECT * FROM $table_name";
    if (!empty($search_query)) {
        $sql .= " WHERE img_name LIKE '%" . $conn->real_escape_string($search_query) . "%'";
    }
    $result = $conn->query($sql);

} elseif ($page === 'users') {
    $page_title = 'Quản lý tài khoản';

    // Xử lý tìm kiếm tài khoản
    $search_query = isset($_GET['search']) ? $_GET['search'] : '';

    // Truy vấn dữ liệu tài khoản
    $sql = "SELECT * FROM users";
    if (!empty($search_query)) {
        $sql .= " WHERE username LIKE '%" . $conn->real_escape_string($search_query) . "%'";
    }
    $result = $conn->query($sql);
} elseif ($page === 'notifications') {
    $page_title = 'Thông báo';
} elseif ($page === 'orders') {
    $page_title = 'Quản lý đơn hàng';
} elseif ($page === 'settings') {
    $page_title = 'Cài đặt';
}

// Hàm render nội dung trang
function renderContent($page, $page_title, $active_gender, $search_query, $result) {
    global $conn;

    // Dữ liệu đơn hàng ảo
    $orders = [
        [
            'id' => '#ORD001',
            'customer' => 'Nguyễn Văn A',
            'total' => '550,000',
            'status' => 'Đã giao hàng',
            'date' => '2025-10-25'
        ],
        [
            'id' => '#ORD002',
            'customer' => 'Trần Thị B',
            'total' => '820,000',
            'status' => 'Đang xử lý',
            'date' => '2025-10-25'
        ],
        [
            'id' => '#ORD003',
            'customer' => 'Lê Văn C',
            'total' => '300,000',
            'status' => 'Đã hủy',
            'date' => '2025-10-24'
        ],
        [
            'id' => '#ORD004',
            'customer' => 'Phạm Văn D',
            'total' => '1,200,000',
            'status' => 'Đang vận chuyển',
            'date' => '2025-10-24'
        ],
        [
            'id' => '#ORD005',
            'customer' => 'Hoàng Thị E',
            'total' => '450,000',
            'status' => 'Đã giao hàng',
            'date' => '2025-10-23'
        ],
        [
            'id' => '#ORD006',
            'customer' => 'Đỗ Văn F',
            'total' => '780,000',
            'status' => 'Đang xử lý',
            'date' => '2025-10-23'
        ],
        [
            'id' => '#ORD007',
            'customer' => 'Vũ Thị G',
            'total' => '950,000',
            'status' => 'Đã giao hàng',
            'date' => '2025-10-22'
        ],
        [
            'id' => '#ORD008',
            'customer' => 'Bùi Văn H',
            'total' => '650,000',
            'status' => 'Đang vận chuyển',
            'date' => '2025-10-22'
        ],
        [
            'id' => '#ORD009',
            'customer' => 'Nguyễn Thị I',
            'total' => '250,000',
            'status' => 'Đã hủy',
            'date' => '2025-10-21'
        ],
        [
            'id' => '#ORD010',
            'customer' => 'Trần Văn K',
            'total' => '1,500,000',
            'status' => 'Đang xử lý',
            'date' => '2025-10-21'
        ],
    ];

    if ($page === 'home') {
        ?>
        <h2 class="mb-4">Tổng quan</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Tổng số sản phẩm</h5>
                                <p class="card-text fs-3">550</p>
                            </div>
                            <i class="fa-solid fa-box fa-3x"></i>
                        </div>
                        <a href="?page=products" class="text-white text-decoration-none d-block mt-2">
                            <span class="small">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Tổng số đơn hàng</h5>
                                <p class="card-text fs-3">125</p>
                            </div>
                            <i class="fa-solid fa-receipt fa-3x"></i>
                        </div>
                        <a href="?page=orders" class="text-white text-decoration-none d-block mt-2">
                            <span class="small">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Đơn hàng mới</h5>
                                <p class="card-text fs-3">12</p>
                            </div>
                            <i class="fa-solid fa-bell fa-3x"></i>
                        </div>
                        <a href="?page=notifications" class="text-white text-decoration-none d-block mt-2">
                            <span class="small">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">Chào mừng trở lại, Admin!</h4>
            <p>Đây là bảng điều khiển chính. Bạn có thể xem các báo cáo chi tiết và thực hiện các tác vụ quản trị nhanh chóng.</p>
        </div>
        <?php
    } elseif ($page === 'products') {
        ?>
        <h2 class="mb-4"><?php echo $page_title; ?></h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex" role="search" method="GET">
                <input type="hidden" name="page" value="products">
                <input type="hidden" name="gender" value="<?php echo $active_gender; ?>">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Tìm kiếm sản phẩm ..." name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
            <a href="add_product.php?gender=<?php echo $active_gender; ?>" class="btn btn-primary">Thêm sản phẩm mới</a>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Loại</th>
                    <th>Mô tả</th>
                    <th>Trạng thái</th>
                    <th>Thay đổi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['img_id']}</td>";
                    echo "<td><img src='../".htmlspecialchars($row['img_url'])."' alt='".htmlspecialchars($row['img_name'])."' class='product-img-thumbnail'></td>";
                    echo "<td>".htmlspecialchars($row['img_name'])."</td>";
                    echo "<td>".number_format($row['img_price'], 0, ',', '.') . " VNĐ</td>";
                    echo "<td>{$row['img_product']}</td>";
                    echo "<td>".htmlspecialchars(substr($row['img_caption'], 0, 50))."...</td>";
                    echo "<td>".htmlspecialchars($row['img_status'])."</td>";
                    echo "<td><div class='action-buttons'>";
                    echo "<a href='edit_product.php?id={$row['img_id']}&gender={$active_gender}' class='btn btn-sm btn-info'>Sửa</a>";
                    echo "<a href='delete_product.php?id={$row['img_id']}&gender={$active_gender}' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn có chắc muốn xóa sản phẩm này?\");'>Xóa</a>";
                    echo "</div></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>Không tìm thấy sản phẩm nào.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <?php
    } elseif ($page === 'users') {
        ?>
        <h2 class="mb-4">Quản lý tài khoản</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex" role="search" method="GET">
                <input type="hidden" name="page" value="users">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Tìm kiếm tài khoản..." name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
            <a href="add_user.php" class="btn btn-primary">Thêm tài khoản mới</a>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Quyền</th>
                    <th>Thay đổi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                $stt = 1;
                while($row = $result->fetch_assoc()) {
                    $is_admin = $row['role'] === 'admin';
                    echo "<tr" . ($is_admin ? " class='table-warning fw-bold'" : "") . ">";
                    echo "<td>" . $stt++ . "</td>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                    echo "<td><div class='action-buttons'>";
                    echo "<a href='edit_user.php?id={$row['id']}' class='btn btn-sm btn-info'>Sửa</a>";
                    echo "<a href='delete_user.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn có chắc muốn xóa tài khoản này?\");'>Xóa</a>";
                    echo "</div></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Không tìm thấy tài khoản nào.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <?php
    } elseif ($page === 'orders') {
        ?>
        <h2 class="mb-4">Quản lý đơn hàng</h2>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['customer']; ?></td>
                    <td><?php echo $order['total']; ?> VNĐ</td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo $order['date']; ?></td>
                    <td><a href="#" class="btn btn-sm btn-info">Xem thêm</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    } elseif ($page === 'notifications') {
        ?>
        <h2 class="mb-4">Thông báo</h2>
        <div class="alert alert-info">
            <h4 class="alert-heading">Thông báo mới!</h4>
            <p>Một đơn hàng mới với mã **#12345** vừa được đặt. Vui lòng kiểm tra và xử lý.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <div class="alert alert-info">
            <h4 class="alert-heading">Cảnh báo tồn kho</h4>
            <p>Sản phẩm **"Áo thun nam"** sắp hết hàng. Chỉ còn 5 sản phẩm trong kho.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <div class="alert alert-info">
            <h4 class="alert-heading">Tài khoản mới</h4>
            <p>Một tài khoản người dùng mới có tên **`khachhang01`** đã đăng ký thành công.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <div class="alert alert-info">
            <h4 class="alert-heading">Đơn hàng đã hủy</h4>
            <p>Đơn hàng **#12344** đã bị hủy bởi khách hàng. Vui lòng cập nhật trạng thái.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <div class="alert alert-info">
            <h4 class="alert-heading">Phản hồi sản phẩm</h4>
            <p>Khách hàng đã gửi phản hồi mới về sản phẩm **"Váy công chúa"**.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <div class="alert alert-info">
            <h4 class="alert-heading">Cập nhật mật khẩu</h4>
            <p>Mật khẩu của tài khoản **`admin_hieu`** đã được thay đổi.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <div class="alert alert-info">
            <h4 class="alert-heading">Đơn hàng đã giao</h4>
            <p>Đơn hàng **#12343** đã được giao thành công đến khách hàng.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <div class="alert alert-info">
            <h4 class="alert-heading">Thông báo thanh toán</h4>
            <p>Thanh toán của đơn hàng **#12342** đã được xác nhận.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <div class="alert alert-info">
            <h4 class="alert-heading">Sản phẩm mới</h4>
            <p>Sản phẩm **"Áo khoác gió"** đã được thêm vào danh mục Sản phẩm Nam.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <div class="alert alert-info">
            <h4 class="alert-heading">Cảnh báo đăng nhập</h4>
            <p>Có một lần đăng nhập thất bại từ một địa chỉ IP lạ vào tài khoản **`admin`**.</p>
            <hr>
            <a href="#" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
        <?php
    } elseif ($page === 'settings') {
        ?>
        <h2 class="mb-4">Cài đặt</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Giao diện</h5>
                <p class="card-text">Chọn giao diện nền sáng hoặc nền tối cho trang quản trị.</p>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="themeSwitch">
                    <label class="form-check-label" for="themeSwitch">Nền sáng / Nền tối</label>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ngôn ngữ</h5>
                <p class="card-text">Chọn ngôn ngữ hiển thị trên trang quản trị.</p>
                <select class="form-select w-50" id="languageSelect">
                    <option value="vi">Tiếng Việt</option>
                    <option value="en">English</option>
                </select>
            </div>
        </div>
        <?php
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin:0; font-family:Arial,sans-serif; padding-top:60px; background-color:#f8f9fa; transition: background-color 0.3s, color 0.3s; }
        .header { background-color:#212529; color:#fff; padding:10px 20px; display:flex; justify-content:space-between; align-items:center; position:fixed; top:0; left:0; width:100%; z-index:1000; box-shadow:0 2px 5px rgba(0,0,0,0.3); }
        .brand { font-size:1.5rem; font-weight:bold; }
        .container-fluid { display:flex; }
        .sidebar { width:200px; background-color:#343a40; color:#fff; position:fixed; top:60px; left:0; height:calc(100vh - 60px); overflow-y:auto; display:flex; flex-direction:column; justify-content:space-between; box-shadow:2px 0 5px rgba(0,0,0,0.3); }
        .sidebar a { color:#adb5bd; text-decoration:none; padding:10px 20px; display:block; transition: background-color .3s,color .3s; }
        .sidebar a:hover { background-color:#495057; color:#fff; }
        .sidebar a.active { background-color:#0d6efd; color:#fff; }
        .sidebar .dropdown-menu { display:none; background-color:#495057; padding-left:15px; }
        .sidebar .nav-dropdown:hover .dropdown-menu { display:block; }
        .sidebar .dropdown-menu a { padding:8px 20px; }
        .sidebar .sidebar-footer { padding:20px; margin-top:auto; }
        .sidebar .sidebar-footer a { font-size:1rem; padding:10px 20px; display:flex; align-items:center; justify-content:center; color:#adb5bd; text-decoration:none; }
        .sidebar .sidebar-footer a:hover { color:#fff; }
        .main-content { margin-left:200px; padding:20px; width:100%; }
        .product-img-thumbnail { width:50px; height:50px; object-fit:cover; }
        .action-buttons { display:flex; gap:5px; }

        /* Styles for Dark Mode */
        body.dark-mode { background-color: #121212; color: #e0e0e0; }
        .dark-mode .header { background-color: #1f1f1f; }
        .dark-mode .sidebar { background-color: #1f1f1f; }
        .dark-mode .sidebar a { color: #b0b0b0; }
        .dark-mode .sidebar a:hover { background-color: #333; color: #fff; }
        .dark-mode .main-content { color: #e0e0e0; }
        .dark-mode .card, .dark-mode .form-control, .dark-mode .form-select, .dark-mode .input-group-text, .dark-mode .table, .dark-mode .alert { background-color: #2c2c2c; color: #e0e0e0; border-color: #444; }
        .dark-mode .card-body { color: #e0e0e0; }
        .dark-mode .form-control, .dark-mode .form-select { color: #e0e0e0; }
        .dark-mode .table { color: #e0e0e0; }
        .dark-mode .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: #333; }
        .dark-mode .table-hover > tbody > tr:hover > * { background-color: #444; }
    </style>
</head>
<body>
    <header class="header">
        <div class="brand">Beautiful Love</div>
        <div class="admin-actions">
            Chào <?php echo htmlspecialchars($_SESSION['username']); ?>
            <a href="../logout.php" class="btn btn-danger btn-sm ms-2">Đăng xuất</a>
        </div>
    </header>

    <div class="container-fluid">
        <nav class="sidebar">
            <ul class="nav flex-column p-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page === 'home' ? 'active' : ''); ?>" href="?page=home">
                        <i class="fa-solid fa-house me-2"></i> Trang chủ
                    </a>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link <?php echo ($page === 'products' ? 'active' : ''); ?>" href="?page=products&gender=boy">
                        <i class="fa-solid fa-box me-2"></i> Quản lý sản phẩm
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php echo ($page === 'products' && $active_gender === 'boy' ? 'active' : ''); ?>" href="?page=products&gender=boy">Sản phẩm Nam</a></li>
                        <li><a class="dropdown-item <?php echo ($page === 'products' && $active_gender === 'girl' ? 'active' : ''); ?>" href="?page=products&gender=girl">Sản phẩm Nữ</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page === 'orders' ? 'active' : ''); ?>" href="?page=orders">
                        <i class="fa-solid fa-receipt me-2"></i> Quản lý đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page === 'users' ? 'active' : ''); ?>" href="?page=users">
                        <i class="fa-solid fa-users me-2"></i> Quản lý tài khoản
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page === 'notifications' ? 'active' : ''); ?>" href="?page=notifications">
                        <i class="fa-solid fa-bell me-2"></i> Thông báo
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a class="nav-link <?php echo ($page === 'settings' ? 'active' : ''); ?>" href="?page=settings">
                    <i class="fa-solid fa-gear me-2"></i> Cài đặt
                </a>
            </div>
        </nav>

        <main class="main-content">
            <?php renderContent($page, $page_title, $active_gender, $search_query, $result); ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript for theme switching
        const themeSwitch = document.getElementById('themeSwitch');
        const body = document.body;

        themeSwitch.addEventListener('change', () => {
            if (themeSwitch.checked) {
                body.classList.add('dark-mode');
            } else {
                body.classList.remove('dark-mode');
            }
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>