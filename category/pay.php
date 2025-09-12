<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php';

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die("ID sản phẩm không hợp lệ!");
    }

    $id = intval($_GET['id']);

    // Lấy bảng từ URL: ?id=5&table=pd_boy hoặc ?id=7&table=pd_girl
        $table = isset($_GET['table']) ? $_GET['table'] : '';
        $allowedTables = ['pd_boy', 'pd_girl']; // chỉ cho phép 2 bảng này để tránh hack SQL

        if (!in_array($table, $allowedTables)) {
            die("Bảng sản phẩm không hợp lệ!");
        }

        $sql = "SELECT img_id, img_name, img_url, img_price, img_caption 
                FROM $table 
                WHERE img_id = $id";
        $result = $conn->query($sql);


    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Không tìm thấy sản phẩm!");
    }

    $colors = [
        'white' => ['label' => 'Trắng', 'color' => '#f8f9fa'],
        'black' => ['label' => 'Đen', 'color' => '#212529'],
        'pink'  => ['label' => 'Hồng', 'color' => '#ffc0cb'],
    ];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Beautiful Love - <?php echo htmlspecialchars($product['img_name']); ?></title>
        <link rel="icon" href="../image/logo/Logo.png"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <style>
        .color-swatch { width: 30px; height: 30px; border-radius: 50%; cursor: pointer; border: 1px solid #ccc; }
        .comment-box { font-family: sans-serif; }
        .comment, .reply {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .reply { margin-left: 50px; background: #f9f9f9; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0; }
        .content { flex: 1; }
        .name { font-weight: bold; margin-bottom: 3px; }
        .stars { color: gold; margin-bottom: 5px; }
        .text { color: #333; }
        .img-zoom-container {
            position: relative;
            display: inline-block;
            max-width: 100%;  /* ảnh không vượt container */
            }

            .img-zoom-container img {
            width: 350px; /* hoặc 100% để responsive */
            border-radius: 10px;
            display: block;
            }


            #zoom-lens {
            position: absolute;
            border: 2px solid #ccc;
            border-radius: 50%;
            width: 100px;   /* kích thước kính lúp */
            height: 100px;
            overflow: hidden;
            display: none;  /* ẩn khi chưa hover */
            pointer-events: none; /* không cản trở chuột */
            background-repeat: no-repeat;
            background-size: 200% 200%; /* zoom 2x */
            }

            .img-container {
            width: 100%;          /* khung bằng card */
            aspect-ratio: 1 / 1;   /* giữ tỉ lệ vuông */
            overflow: hidden;      /* ẩn phần tràn */
            border-radius: 10px;   /* bo góc khung */
            position: relative;
            }

            .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;     /* ảnh không méo */
            transition: transform 0.5s ease-in-out;
            }

            .img-container:hover .product-img {
            transform: scale(1.3); /* zoom nhưng vẫn trong khung */
            }

        </style>
    </head>
    <body>
        <?php include 'header.php'; ?>
        <main style="padding-top: 80px !important;">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="img-container">
                            <img id="product-img" 
                                src="<?php echo $product['img_url']; ?>" 
                                class="product-img" 
                                alt="<?php echo htmlspecialchars($product['img_name']); ?>">
                            <div id="zoom-lens"></div>
                        </div>
                    </div>
                    <script>
                        const img = document.getElementById("product-img");
                        const lens = document.getElementById("zoom-lens");

                        img.addEventListener("mousemove", moveLens);
                        img.addEventListener("mouseenter", () => lens.style.display = "block");
                        img.addEventListener("mouseleave", () => lens.style.display = "none");

                        function moveLens(e) {
                        const rect = img.getBoundingClientRect();
                        let x = e.clientX - rect.left;
                        let y = e.clientY - rect.top;
                        const lensSize = lens.offsetWidth / 2;

                        // Giới hạn lens không vượt ra ngoài ảnh
                        if (x < lensSize) x = lensSize;
                        if (x > rect.width - lensSize) x = rect.width - lensSize;
                        if (y < lensSize) y = lensSize;
                        if (y > rect.height - lensSize) y = rect.height - lensSize;

                        // Di chuyển lens
                        lens.style.left = `${x - lensSize}px`;
                        lens.style.top = `${y - lensSize}px`;

                        // Thiết lập background ảnh phóng to
                        const zoom = 2; // độ zoom (2x)
                        lens.style.backgroundImage = `url(${img.src})`;
                        lens.style.backgroundSize = `${rect.width * zoom}px ${rect.height * zoom}px`;
                        lens.style.backgroundPosition = `-${x * zoom - lensSize}px -${y * zoom - lensSize}px`;
                        }
                    </script>

                    <div class="col-md-8">
                        <h3><?php echo htmlspecialchars($product['img_name']); ?></h3>
                        <p><strong><?php echo number_format($product['img_price'], 0, ",", "."); ?> đ</strong></p>

                        <form id="product-form" method="POST" action="checkout.php">
                            <input type="hidden" name="buy_now" value="1">
                            <input type="hidden" name="id" value="<?php echo (int)$product['img_id']; ?>">
                            <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">

                            <!-- Chọn màu -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chọn màu:</label>
                                <div class="d-flex gap-3">
                                    <?php $first = true; foreach($colors as $key => $info): ?>
                                        <input type="radio" class="btn-check" name="color" id="color-<?php echo $key; ?>" value="<?php echo $key; ?>" <?php if($first) echo 'checked'; ?>>
                                        <label class="color-swatch" for="color-<?php echo $key; ?>" style="background-color: <?php echo $info['color']; ?>;" title="<?php echo $info['label']; ?>"></label>
                                    <?php $first = false; endforeach; ?>
                                </div>
                            </div>

                            <!-- Chọn size -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chọn size:</label>
                                <div class="d-flex gap-2">
                                    <?php foreach(['S','M','L'] as $sizeOption): ?>
                                        <input type="radio" class="btn-check" name="size" id="size-<?php echo $sizeOption; ?>" value="<?php echo $sizeOption; ?>" <?php echo $sizeOption==='S'?'checked':''; ?>>
                                        <label class="btn btn-outline-secondary" for="size-<?php echo $sizeOption; ?>"><?php echo $sizeOption; ?></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Số lượng -->
                            <div class="mb-3">
                                <label for="quantity" class="form-label fw-bold">Số lượng:</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" style="max-width: 100px;">
                            </div>

                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-bag-check me-1"></i> Mua ngay
                                </button>
                                <button type="button" id="add-to-cart-btn" class="btn btn-outline-secondary">
                                    <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                </button>
                                <button type="button" id="favorite-btn" class="btn btn-outline-danger">
                                    <i class="bi bi-heart me-1"></i> Yêu thích
                                </button>
                                <button type="button" id="share-btn" class="btn btn-outline-success">
                                    <i class="bi bi-share me-1"></i> Chia sẻ
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="mt-5">
                    <h4>Mô tả sản phẩm</h4>
                    <p><?php echo htmlspecialchars($product['img_caption']); ?></p>
                </div>
                
                <div style="font-family: sans-serif;background: #f0f0f0;padding: 20px;">
                    <h4>Bình luận</h4>
                    <div class="comment-box" id="commentBox"></div>
                </div>

                    <script>
                        const comments = [
                            {
                                name: "Alice",
                                avatar: "https://i.pravatar.cc/40?img=1",
                                text: "Sản phẩm đẹp quá! 😍",
                                rating: 5,
                                replies: [
                                {name: "Bob", avatar: "https://i.pravatar.cc/40?img=2", text: "Đúng vậy, mình cũng thích!"},
                                {name: "Charlie", avatar: "https://i.pravatar.cc/40?img=3", text: "Mình chưa mua nhưng muốn thử."}
                                ]
                            },
                            {
                                name: "Diana",
                                avatar: "https://i.pravatar.cc/40?img=4",
                                text: "Giao hàng nhanh, đóng gói cẩn thận.",
                                rating: 4,
                                replies: [
                                {name: "Eve", avatar: "https://i.pravatar.cc/40?img=5", text: "Giao hàng nhanh thật!"}
                                ]
                            },
                            {
                                name: "Nina",
                                avatar: "https://i.pravatar.cc/40?img=15",
                                text: "Giao hàng hơi chậm, nhưng sản phẩm ok.",
                                rating: 4,
                                replies: [
                                {name: "Oscar", avatar: "https://i.pravatar.cc/40?img=16", text: "Cảm ơn review, mình sẽ kiên nhẫn chờ hàng."}
                                ]
                            },
                            {
                                name: "Karen",
                                avatar: "https://i.pravatar.cc/40?img=12",
                                text: "Mình rất thích chất liệu, mặc thoải mái cả ngày.",
                                rating: 5,
                                replies: [
                                {name: "Leo", avatar: "https://i.pravatar.cc/40?img=13", text: "Cảm ơn bồ, mình sẽ đặt thử!"},
                                {name: "Mona", avatar: "https://i.pravatar.cc/40?img=14", text: "Nghe hay đó, mình cũng muốn thử."}
                                ]
                            },
                            ];
                    comments.forEach(c => {
                        // comment cha
                        const div = document.createElement("div");
                        div.className = "comment";

                        let stars = "";
                        for(let i=0;i<c.rating;i++) stars += "★";

                        div.innerHTML = `
                            <img src="${c.avatar}" class="avatar">
                            <div class="content">
                            <div class="name">${c.name}</div>
                            <div class="stars">${stars}</div>
                            <div class="text">${c.text}</div>
                            <div class="replies"></div> <!-- chứa reply -->
                            </div>
                        `;
                        commentBox.appendChild(div);

                        const repliesDiv = div.querySelector(".replies");
                        c.replies.forEach(r => {
                            const rDiv = document.createElement("div");
                            rDiv.className = "reply";
                            rDiv.innerHTML = `
                            <img src="${r.avatar}" class="avatar">
                            <div class="content">
                                <div class="name">${r.name}</div>
                                <div class="text">${r.text}</div>
                            </div>
                            `;
                            repliesDiv.appendChild(rDiv);
                        });
                        });
                    </script>
                    <?php
                        // Lấy sản phẩm nổi bật theo bảng hiện tại (img_status = 'good')
                        $sql_featured = "SELECT img_id, img_name, img_url, img_price 
                                        FROM $table 
                                        WHERE img_status = 'good' 
                                        ORDER BY img_id DESC";
                        $result_featured = $conn->query($sql_featured);
                    ?>

                    <div class="mt-5">
                        <h4 class="mb-4">Sản phẩm nổi bật</h4>
                        <?php if($result_featured && $result_featured->num_rows > 0): ?>
                            <div class="swiper featuredSwiper">
                                <div class="swiper-wrapper">
                                    <?php while($f = $result_featured->fetch_assoc()): ?>
                                        <div class="swiper-slide">
                                            <div class="card h-100 shadow-sm">
                                                <a href="pay.php?id=<?php echo $f['img_id']; ?>&table=<?php echo $table; ?>">
                                                    <img src="<?php echo $f['img_url']; ?>" 
                                                        class="card-img-top" 
                                                        alt="<?php echo htmlspecialchars($f['img_name']); ?>">
                                                </a>
                                                <div class="card-body text-center">
                                                    <h6 class="card-title mb-2">
                                                        <?php echo htmlspecialchars($f['img_name']); ?>
                                                    </h6>
                                                    <p class="text-danger fw-bold mb-2">
                                                        <?php echo number_format($f['img_price'], 0, ",", "."); ?> đ
                                                    </p>
                                                    <a href="pay.php?id=<?php echo $f['img_id']; ?>&table=<?php echo $table; ?>" 
                                                    class="btn btn-sm btn-outline-primary">Xem</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <!-- Nút điều hướng -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        <?php else: ?>
                            <p>Không có sản phẩm nổi bật!</p>
                        <?php endif; ?>
                    </div>

                    <!-- SwiperJS -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
                    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
                    <script>
                        var swiper = new Swiper(".featuredSwiper", {
                            slidesPerView: 5,
                            spaceBetween: 15,
                            navigation: {
                                nextEl: ".swiper-button-next",
                                prevEl: ".swiper-button-prev",
                            },
                            loop: true,
                            breakpoints: {
                                1200: { slidesPerView: 5 },
                                992: { slidesPerView: 4 },
                                768: { slidesPerView: 3 },
                                576: { slidesPerView: 2 },
                                0: { slidesPerView: 1 }
                            }
                        });
                    </script>
            </div>
        </main>
        <?php include 'footer.php'; ?>

        <script>
            // Nút Thêm vào giỏ
            document.getElementById('add-to-cart-btn').addEventListener('click', async function(){
                const form = document.getElementById('product-form');
                const formData = new FormData(form);
                formData.append('action','add_to_cart');
                formData.append('table', form.table.value);
                formData.append('product_id', form.id.value);

                const response = await fetch('update_cart_api.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if(result.success){
                    alert('Bạn đã thêm vào giỏ hàng!');
                } else {
                    alert('Lỗi khi thêm vào giỏ hàng!');
                }
            });

            // Nút Yêu thích
            const favBtn = document.getElementById('favorite-btn');
            let isFav = false;
            favBtn.addEventListener('click', function(){
                isFav = !isFav;
                favBtn.innerHTML = isFav 
                    ? '<i class="bi bi-heart-fill me-1"></i> Đã thích' 
                    : '<i class="bi bi-heart me-1"></i> Yêu thích';
                favBtn.classList.toggle('btn-danger', isFav);
                favBtn.classList.toggle('btn-outline-danger', !isFav);
            });

            // Nút Chia sẻ
            document.getElementById('share-btn').addEventListener('click', async function(){
                const url = window.location.href;
                try {
                    await navigator.clipboard.writeText(url);
                    this.innerHTML = '<i class="bi bi-check2 me-1"></i> Đã sao chép link';
                    this.classList.remove('btn-outline-success');
                    this.classList.add('btn-success');
                    setTimeout(() => {
                        this.innerHTML = '<i class="bi bi-share me-1"></i> Chia sẻ';
                        this.classList.remove('btn-success');
                        this.classList.add('btn-outline-success');
                    }, 2000);
                } catch (err) {
                    alert('Không thể sao chép link!');
                }
            });
        </script>
    </body>
</html>