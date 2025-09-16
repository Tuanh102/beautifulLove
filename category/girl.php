<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php';

// Lấy từ khóa tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Xác định loại sản phẩm
$product_type = isset($_GET['type']) ? (int)$_GET['type'] : 0; // 0 = tất cả

// Xác định sắp xếp
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$sort_sql = "";
switch ($sort) {
    case 'price_asc': $sort_sql = "ORDER BY img_price ASC"; break;
    case 'price_desc': $sort_sql = "ORDER BY img_price DESC"; break;
    case 'new': $sort_sql = "ORDER BY img_id DESC"; break;
    case 'popular': $sort_sql = "ORDER BY img_id ASC"; break;
    default: $sort_sql = ""; break;
}

// Điều kiện lọc sản phẩm
$where = "1";
if ($product_type == 1) $where .= " AND img_product=1";
if ($product_type == 2) $where .= " AND img_product=2";
if ($product_type == 3) $where .= " AND img_product=3";
if ($product_type == 4) $where .= " AND img_product=4";
if ($product_type == 5) $where .= " AND img_product=5";
if ($search !== '') {
    $search_like = $conn->real_escape_string($search);
    $where .= " AND img_name LIKE '%$search_like%'";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautiful Love</title>
    <link rel="icon" href="../image/logo/Logo.png"/>
    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style/main.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
    <style>
        .custom-slide { max-width: 90%; margin: 0 auto; border-radius: 15px; overflow: hidden;}
        .custom-slide img { width: 100%; height: 500px; object-fit: cover;}
        .vertical-nav-outer {position: sticky; top: 100px; width: 250px;}
        .vertical-nav-container {padding: 1rem; border-radius: 8px; background-color: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.1);}
        .vertical-nav-container .nav-title {font-size: 1.25rem; font-weight: bold; color: #333; margin-bottom: 1rem; border-bottom:1px solid #dee2e6; padding-bottom:0.5rem;}
        .vertical-nav-container .nav-link {color:#6c757d; padding:0.75rem 1rem; border-radius:4px; margin-bottom:0.5rem; transition: all 0.3s ease;}
        .vertical-nav-container .nav-link:hover {background-color:#e9ecef; color:#000; transform: translateX(5px);}
        .vertical-nav-container .nav-link.active {font-weight: bold;color: #000;}
        .swiper-slide img {width:100%; height:200px; object-fit:cover; border-radius:10px;}
        @media(max-width:768px){.vertical-nav-outer{position:relative;width:100%;top:0;margin-bottom:1rem;}}
        /* chỉnh màu mũi tên của Swiper */
        .swiper-button-next::after,
        .swiper-button-prev::after {
            color: #6c757d; /* xám */
            opacity: 0.6;   /* mờ mờ */
            font-size: 24px; /* tùy chỉnh kích thước */
        }

    </style>
</head>
<body>
<div>
<?php include 'header.php'; ?>

<main style="padding-top:90px;">
    <!-- Banner -->
    <div id="carouselExample" class="carousel slide custom-slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            <?php
            $sql = "SELECT id,img_url FROM banner WHERE sex IN ('girl','unisex')";
            $res = $conn->query($sql);
            if ($res && $res->num_rows > 0) {
                $first = true;
                while ($row = $res->fetch_assoc()) {
                    $active = $first ? 'active' : '';
                    $first = false;
                    echo "<div class='carousel-item $active'><img src='{$row['img_url']}' class='d-block w-100' alt='Slide'></div>";
                }
            } else {
                echo "<div class='carousel-item active'><img src='default.jpg' class='d-block w-100' alt='Không có ảnh'></div>";
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Trước</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Sau</span>
        </button>
    </div>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="vertical-nav-outer">
                    <div class="vertical-nav-container">
                        <h3 class="nav-title">Sản phẩm</h3>
                        <ul class="nav flex-column fw-bold">
                            <li class="nav-item"><a class="nav-link <?php echo ($product_type==0)?'active':'';?>" href="?type=0">Tất cả</a></li>
                            <li class="nav-item"><a class="nav-link <?php echo ($product_type==1)?'active':'';?>" href="?type=1">Đầm</a></li>
                            <li class="nav-item"><a class="nav-link <?php echo ($product_type==2)?'active':'';?>" href="?type=2">Áo</a></li>
                            <li class="nav-item"><a class="nav-link <?php echo ($product_type==3)?'active':'';?>" href="?type=3">Quần</a></li>
                            <li class="nav-item"><a class="nav-link <?php echo ($product_type==4)?'active':'';?>" href="?type=4">Chân váy</a></li>
                            <li class="nav-item"><a class="nav-link <?php echo ($product_type==5)?'active':'';?>" href="?type=5">Áo khoác</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sản phẩm chính -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center my-4 flex-wrap">
                    <h1 class="my-0">Nữ</h1>

                    <form method="get" class="d-flex align-items-center" style="gap:10px; flex-wrap: nowrap;">
                        <!-- Giữ type hiện tại -->
                        <input type="hidden" name="type" value="<?=$product_type?>">

                        <!-- Thanh tìm kiếm -->
                        <input type="text" name="search" class="form-control" 
                            placeholder="Tìm sản phẩm..." 
                            value="<?=htmlspecialchars($search)?>" style="min-width:200px;">

                        <!-- Dropdown sắp xếp -->
                        <select name="sort" class="form-select" style="width:auto;">
                            <option value="default" <?=($sort=='default')?'selected':''?>>Mặc định</option>
                            <option value="price_asc" <?=($sort=='price_asc')?'selected':''?>>Giá (Thấp → Cao)</option>
                            <option value="price_desc" <?=($sort=='price_desc')?'selected':''?>>Giá (Cao → Thấp)</option>
                            <option value="new" <?=($sort=='new')?'selected':''?>>Hàng mới về</option>
                        </select>

                        <!-- Nút tìm kiếm -->
                        <button type="submit" class="btn btn-dark"><i class="fa fa-search"></i></button>
                    </form>
                </div>


                <div class="row">
                <?php
                    $products_per_page = 20;
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    if ($current_page < 1) $current_page = 1;

                    // Đếm tổng sản phẩm
                    $sql_count = "SELECT COUNT(*) AS total FROM pd_girl WHERE $where";
                    $total_products = $conn->query($sql_count)->fetch_assoc()['total'];
                    $total_pages = ceil($total_products / $products_per_page);
                    $offset = ($current_page - 1) * $products_per_page;

                    // Lấy sản phẩm
                    $sql_sp = "SELECT img_id,img_name,img_url,img_price,img_caption FROM pd_girl WHERE $where $sort_sql LIMIT $products_per_page OFFSET $offset";
                    $res_sp = $conn->query($sql_sp);

                    if ($res_sp && $res_sp->num_rows > 0) {
                        while ($row = $res_sp->fetch_assoc()) {
                            echo '<div class="col-md-3 col-sm-6 mb-4">';
                            echo '<div class="card h-100">';
                            echo '<img src="'.$row['img_url'].'" class="card-img-top" alt="'.$row['img_name'].'">';
                            echo '<div class="card-body text-center">';
                            echo '<h5 class="card-title">'.$row['img_name'].'</h5>';
                            echo '<p class="card-text text-danger"><strong>'.number_format($row['img_price'],0,",",".").' đ</strong></p>';
                            echo '<a href="pay.php?id='.$row['img_id'].'&table=pd_girl" class="btn btn-dark">Mua ngay</a>';
                            echo '</div></div></div>';
                        }
                    } else {
                        echo '<p class="text-center">Không tìm thấy sản phẩm nào.</p>';
                    }
                ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Phân trang">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?type=<?php echo $product_type;?>&sort=<?php echo $sort;?>&search=<?php echo urlencode($search);?>&page=<?php echo $current_page-1;?>">Trước</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?type=<?php echo $product_type;?>&sort=<?php echo $sort;?>&search=<?php echo urlencode($search);?>&page=<?php echo $i;?>"><?php echo $i;?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?type=<?php echo $product_type;?>&sort=<?php echo $sort;?>&search=<?php echo urlencode($search);?>&page=<?php echo $current_page+1;?>">Sau</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sản phẩm nổi bật -->
    <div class="container my-5">
        <h1 class="mb-3 text-center">Sản phẩm nổi bật</h1>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
            <?php
                $sql_featured = "SELECT img_id,img_url FROM pd_girl WHERE img_status=1";
                $res_featured = $conn->query($sql_featured);
                if ($res_featured && $res_featured->num_rows > 0) {
                    while ($row = $res_featured->fetch_assoc()) {
                        echo '<div class="swiper-slide">';
                        echo '<a href="pay.php?id='.$row['img_id'].'&table=pd_girl">';
                        echo '<img src="'.$row['img_url'].'">';
                        echo '</a></div>';
                    }
                } else {
                    echo '<p class="text-center">Hiện chưa có sản phẩm nổi bật.</p>';
                }
            ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 6,
            spaceBetween: 15,
            navigation: {nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev"},
            loop: true,
            breakpoints: {
                1200: {slidesPerView: 6},
                992: {slidesPerView: 4},
                768: {slidesPerView: 3},
                576: {slidesPerView: 2},
                0: {slidesPerView: 1}
            }
        });
    </script>
</main>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>
