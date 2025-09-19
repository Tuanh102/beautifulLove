<?php
// Đếm tổng số tài khoản
$sql_users = "SELECT COUNT(id) AS total_users FROM users";
$result_users = $conn->query($sql_users);
$total_users = 0;
if ($result_users && $result_users->num_rows > 0) {
    $row = $result_users->fetch_assoc();
    $total_users = $row['total_users'];
}

// Đếm tổng số sản phẩm từ cả hai bảng
$sql_products_boy = "SELECT COUNT(img_id) AS total_products FROM pd_boy";
$sql_products_girl = "SELECT COUNT(img_id) AS total_products FROM pd_girl";
$result_products_boy = $conn->query($sql_products_boy);
$result_products_girl = $conn->query($sql_products_girl);

$total_products = 0;
if ($result_products_boy && $result_products_boy->num_rows > 0) {
    $row = $result_products_boy->fetch_assoc();
    $total_products += $row['total_products'];
}
if ($result_products_girl && $result_products_girl->num_rows > 0) {
    $row = $result_products_girl->fetch_assoc();
    $total_products += $row['total_products'];
}

// Lấy danh sách sản phẩm nổi bật (có trạng thái 'good') từ cả hai bảng, giới hạn 8 sản phẩm
$sql_featured = "
    (SELECT img_name, img_url, img_price, img_id, 'boy' AS gender FROM pd_boy WHERE img_status = 'good')
    UNION
    (SELECT img_name, img_url, img_price, img_id, 'girl' AS gender FROM pd_girl WHERE img_status = 'good')
    ORDER BY RAND() LIMIT 8";
$result_featured = $conn->query($sql_featured);

$featured_products = [];
if ($result_featured && $result_featured->num_rows > 0) {
    while($row = $result_featured->fetch_assoc()) {
        $featured_products[] = $row;
    }
}
?>

<div class="container-fluid p-4">
    <h2 class="bg-dark text-white p-3 rounded mb-4">Chào mừng đến với trang quản trị!</h2>
    
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <div class="h3 mb-0"><?php echo htmlspecialchars($total_products); ?></div>
                        <p class="mb-0">Tổng số sản phẩm</p>
                    </div>
                    <i class="fas fa-box-open fa-3x"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <div class="h3 mb-0"><?php echo htmlspecialchars($total_users); ?></div>
                        <p class="mb-0">Tổng số tài khoản</p>
                    </div>
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <div class="h3 mb-0"><?php echo count($featured_products); ?></div>
                        <p class="mb-0">Sản phẩm nổi bật</p>
                    </div>
                    <i class="fas fa-star fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <h4 class="mb-4">Sản phẩm nổi bật</h4>
    <div class="row g-4">
        <?php if (empty($featured_products)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Chưa có sản phẩm nào được đánh dấu là nổi bật.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($featured_products as $product): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <img src="../<?php echo htmlspecialchars($product['img_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['img_name']); ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h6 class="card-title"><?php echo htmlspecialchars($product['img_name']); ?></h6>
                        <p class="card-text text-primary fw-bold"><?php echo number_format($product['img_price'], 0, ',', '.'); ?> VNĐ</p>
                        <a href="product/editProduct.php?id=<?php echo $product['img_id']; ?>&gender=<?php echo $product['gender']; ?>" class="btn btn-sm btn-outline-warning">Sửa</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>