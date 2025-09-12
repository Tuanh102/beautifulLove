<?php
session_start();
include 'connect.php';

// Số tin mỗi trang
$limit = 9;

// Lấy số trang hiện tại từ URL, mặc định trang 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Lấy tổng số tin để phân trang
$totalResult = $conn->query("SELECT COUNT(*) as total FROM news");
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);

// Lấy dữ liệu tin theo phân trang
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC LIMIT $start, $limit");
?>

<!DOCTYPE html>
<html lang="vi">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>Beautiful Love</title>
		<link rel="icon" href="../image/logo/Logo.png"/>
		<!-- Fonts & Icons -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
		<!-- Bootstrap only (KHÔNG dùng Tailwind để tránh đè .container) -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

		<style>
			.card {
				transition: transform 0.3s ease, box-shadow 0.3s ease;
				}

			.card:hover {
				transform: translateY(-5px) scale(1.03); /* nâng lên và phóng to nhẹ */
				box-shadow: 0 20px 40px rgba(0,0,0,0.25); /* bóng đổ mượt */
			}

			/* Nền đen, chữ trắng cho tất cả các nút phân trang */
			.pagination .page-link {
				background-color: black;
				color: white;
				border: 1px solid #333;
				transition: all 0.3s ease;
			}

			/* Hover: nền đỏ, chữ trắng */
			.pagination .page-link:hover {
				background-color: hotpink;
				color: white;
			}

			/* Trang hiện tại active */
			.pagination .page-item.active .page-link {
				background-color: black;
				color: white;
				border-color: #333;
			}
		</style>
	</head>
	<body>
		<?php include 'header.php';?>
		<main class="container" style="padding-top: 80px;">
			<h1 class="text-center mb-5" style="color: hotpink;">Tin Thời Trang Mới Nhất</h1>

			<div class="row g-4">
				<?php while($row = $result->fetch_assoc()): ?>
				<div class="col-md-4">
					<a href="<?= $row['link'] ?>" style="text-decoration: none; color: inherit;">
						<div class="card h-100 shadow-sm">
							<img src="<?= $row['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($row['title']) ?>">
							<div class="card-body">
								<h5 class="card-title"><?= $row['title'] ?></h5>
								<p class="card-text"><?= $row['description'] ?></p>
							</div>
						</div>
					</a>
				</div>
				<?php endwhile; ?>
			</div>

			<!-- Phân trang -->
			<nav class="mt-4">
				<ul class="pagination justify-content-center">
					<?php for($i = 1; $i <= $totalPages; $i++): ?>
					<li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
						<a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
					</li>
					<?php endfor; ?>
				</ul>
			</nav>
		</main>
		<?php include 'footer.php';?>
	</body>
</html>
