<?php
// Kiểm tra biến gender từ URL, mặc định là 'boy'
$gender = isset($_GET['gender']) && ($_GET['gender'] === 'girl' || $_GET['gender'] === 'boy') ? $_GET['gender'] : 'boy';

$table_name = ($gender === 'girl') ? 'pd_girl' : 'pd_boy';
$product_type = ($gender === 'girl') ? 'Nữ' : 'Nam';

// Kết nối CSDL và lấy dữ liệu
$sql = "SELECT * FROM $table_name ORDER BY img_id DESC";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<div class="container-fluid p-4">
    <h2 class="bg-dark text-white p-3 rounded mb-4">Quản lý sản phẩm <?php echo $product_type; ?></h2>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="indexAd.php?pageLayout=sanPham&gender=boy" class="btn btn-<?php echo ($gender === 'boy') ? 'primary' : 'outline-primary'; ?> me-2">Sản phẩm Nam</a>
            <a href="indexAd.php?pageLayout=sanPham&gender=girl" class="btn btn-<?php echo ($gender === 'girl') ? 'primary' : 'outline-primary'; ?>">Sản phẩm Nữ</a>
        </div>
        <a href="product/addProduct.php?gender=<?php echo $gender; ?>" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Thêm sản phẩm
        </a>
    </div>

    <?php
    if (isset($_GET['message'])) {
        $message_text = '';
        $alert_type = '';
        if ($_GET['message'] === 'add_success') {
            $message_text = 'Thêm sản phẩm thành công!';
            $alert_type = 'success';
        } elseif ($_GET['message'] === 'delete_success') {
            $message_text = 'Xóa sản phẩm thành công!';
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
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Loại sản phẩm</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="8" class="text-center">Chưa có sản phẩm nào trong danh mục này.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['img_id']); ?></td>
                    <td><img src="../<?php echo htmlspecialchars($product['img_url']); ?>" alt="<?php echo htmlspecialchars($product['img_name']); ?>" style="width: 80px; height: 80px; object-fit: cover;"></td>
                    <td><?php echo htmlspecialchars($product['img_name']); ?></td>
                    <td><?php echo number_format($product['img_price'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo htmlspecialchars($product['img_product']); ?></td>
                    <td>
                        <?php 
                        $caption = htmlspecialchars($product['img_caption']);
                        if (strlen($caption) > 50) {
                            echo substr($caption, 0, 50) . '...';
                        } else {
                            echo $caption;
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($product['img_status']); ?></td>
                    <td>
                        <a href="product/editProduct.php?id=<?php echo $product['img_id']; ?>&gender=<?php echo $gender; ?>" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="product/deleteProduct.php?id=<?php echo $product['img_id']; ?>&gender=<?php echo $gender; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>