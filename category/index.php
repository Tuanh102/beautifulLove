<?php
session_start();
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Beautiful Love</title>
        <link rel="icon" href="../image/logo/Logo.png"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

        <style>    
            body.index,
            html {
                height: 100%;
                overflow: hidden;
            }

            /* Các section lớn, cuộn dọc */
            .scroll-section {
                width: 100%;
                height: 100vh; /* Quan trọng: mỗi section chiếm toàn bộ màn hình */
                position: relative;
                overflow: hidden; /* Ngăn cuộn của phần tử con */
            }

            /* Container cho các slide, cuộn ngang */
            .horizontal-slider {
                display: flex;
                width: 100%;
                height: 100%;
                overflow: hidden; /* ẩn phần tràn */
                position: relative;
            }

            .slide-wrapper {
                display: flex;
                transition: transform 0.5s ease; /* mượt */
                width: 100%;
                height: 100%;
            }

            .slide {
                flex-shrink: 0;
                width: 100vw;
                height: 100vh;
                position: relative;
            }

            .slide img,
            .slide video {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            /* Các phần tử khác trên trang, ví dụ như button */
            .buy-now {
                position: absolute;
                bottom: 50px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 10;
                padding: 12px 30px;
                background-color: white;
                color: #333;
                text-decoration: none;
                font-weight: bold;
                border: 1px solid #333;
                transition: all 0.3s ease;
            }

            .buy-now:hover {
                background-color: #333;
                color: white;
            }

            /* Dấu chấm điều hướng dọc */
            .dot-navigation {
                position: fixed;
                top: 50%;
                right: 20px;
                transform: translateY(-50%);
                z-index: 100;
                }

            .dots {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .dot {
                width: 10px;
                height: 10px;
                background-color: #ccc;
                border-radius: 50%;
                margin-bottom: 15px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .dot.active {
                background-color: #333;
            }

            /* Mũi tên và thanh điều hướng ngang */
            .slide-controls {
                position: absolute;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 10;
                display: flex;
                align-items: center;
                gap: 20px;
            }

            .slide-controls button {
                background: none;
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
                transition: opacity 0.3s ease;
            }

            .slide-controls button:disabled {
                opacity: 0.3;
                cursor: not-allowed;
            }

            .horizontal-dot-navigation .dots {
                display: flex;
                gap: 10px;
            }

            .horizontal-dot-navigation .dot {
                width: 8px;
                height: 8px;
                background-color: rgba(255, 255, 255, 0.5);
                border: 1px solid white;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .horizontal-dot-navigation .dot.active {
                background-color: white;
                transform: scale(1.2);
            }
        </style>
    </head>

    <body>
        <?php include 'header.php';?>

        <main class="main-section-wrapper">
            <?php
            $sections_sql = "SELECT DISTINCT section_id FROM slides ORDER BY section_id ASC";
            $sections_result = $conn->query($sections_sql);
            $section_count = $sections_result->num_rows;
            $current_section_index = 0;

            while ($section_row = $sections_result->fetch_assoc()) {
                $section_id = $section_row['section_id'];
                $slides_sql = "SELECT * FROM slides WHERE section_id = {$section_id} ORDER BY slide_order ASC";
                $slides_result = $conn->query($slides_sql);
                $slide_count = $slides_result->num_rows;
                ?>

                <section class="scroll-section" data-index="<?php echo $current_section_index; ?>" id="section-<?php echo $section_id; ?>">
                    <div class="horizontal-slider">
                        <div class="slide-wrapper">
                            <?php
                            $slide_index = 0;
                            while ($slide_row = $slides_result->fetch_assoc()) {
                            ?>
                                <div class="slide" data-slide-index="<?php echo $slide_index; ?>">
                                    <?php if ($slide_row['type'] === 'image'): ?>
                                        <img src="<?php echo $slide_row['src']; ?>" alt="<?php echo htmlspecialchars($slide_row['title']); ?>">
                                    <?php elseif ($slide_row['type'] === 'video'): ?>
                                        <video autoplay muted loop>
                                            <source src="<?php echo $slide_row['src']; ?>" type="video/mp4">
                                        </video>
                                    <?php endif; ?>
                                    <?php if (!empty($slide_row['buy_link'])): ?>
                                        <a class="buy-now" href="<?php echo $slide_row['buy_link']; ?>">Mua ngay</a>
                                    <?php endif; ?>
                                </div>
                            <?php
                            $slide_index++;
                            }
                            ?>
                        </div>
                    </div>

                    <?php if ($slide_count > 1): ?>
                        <div class="slide-controls">
                            <button class="prev-slide"><i class="fas fa-chevron-left"></i></button>
                            <nav class="horizontal-dot-navigation">
                                <ul class="dots">
                                    <?php
                                    for ($i = 0; $i < $slide_count; $i++) {
                                        echo "<li class='dot' data-slide-index='{$i}'></li>";
                                    }
                                    ?>
                                </ul>
                            </nav>
                            <button class="next-slide"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    <?php endif; ?>
                </section>
            <?php
            $current_section_index++;
            }
            ?>
        </main>

        <nav class="dot-navigation">
            <ul class="dots">
                <?php
                for ($i = 0; $i < $section_count; $i++) {
                    echo "<li class='dot' data-index='{$i}'></li>";
                }
                ?>
            </ul>
        </nav>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sections = document.querySelectorAll('.scroll-section');
                const dotNav = document.querySelector('.dot-navigation');
                const dots = document.querySelectorAll('.dot');
                let currentSectionIndex = 0;
                let isScrolling = false;
                let autoSlideInterval;

                if (dots.length > 0) dots[0].classList.add('active');

                function updateVerticalDots() {
                    dots.forEach((dot, i) => dot.classList.toggle('active', i === currentSectionIndex));
                }

                function scrollToSection(index) {
                    if (isScrolling) return;
                    isScrolling = true;

                    const targetSection = sections[index];
                    targetSection.scrollIntoView({ behavior: 'smooth' });

                    currentSectionIndex = index;
                    updateVerticalDots();
                    initializeSection(index);

                    setTimeout(() => isScrolling = false, 1000);
                }

                function initializeSection(index) {
                    const section = sections[index];
                    const sliderWrapper = section.querySelector('.slide-wrapper');
                    const slides = Array.from(sliderWrapper.querySelectorAll('.slide'));
                    const horizontalDots = section.querySelectorAll('.horizontal-dot-navigation .dot');
                    const prevBtn = section.querySelector('.prev-slide');
                    const nextBtn = section.querySelector('.next-slide');

                    // Clone đầu/cuối
                    if (!section.querySelector('.clone-start')) {
                        const first = slides[0].cloneNode(true);
                        first.classList.add('clone-start');
                        sliderWrapper.appendChild(first);

                        const last = slides[slides.length - 1].cloneNode(true);
                        last.classList.add('clone-end');
                        sliderWrapper.insertBefore(last, slides[0]);
                    }

                    const allSlides = sliderWrapper.querySelectorAll('.slide');
                    let indexSlide = 1; // slide thật đầu
                    sliderWrapper.style.transform = `translateX(-${indexSlide * 100}vw)`;
                    updateHorizontalDots(horizontalDots, indexSlide - 1);

                    clearInterval(autoSlideInterval);
                    if(slides.length > 1){
                        autoSlideInterval = setInterval(() => {
                    const currentSlide = slides[indexSlide - 1]; // slide thật hiện tại
                    if(currentSlide.querySelector('img')){ // chỉ auto nếu là hình ảnh
                        moveSlide(1);
                    }
                    // nếu là video thì bỏ qua, video sẽ chơi bình thường
                    }, 5000);
            }

                    function updateHorizontalDots(dots, i) {
                        dots.forEach((dot, idx) => dot.classList.toggle('active', idx === i));
                    }

                    function moveSlide(direction) {
                        indexSlide += direction;
                        sliderWrapper.style.transition = 'transform 0.5s ease';
                        sliderWrapper.style.transform = `translateX(-${indexSlide * 100}vw)`;

                        let dotIndex = indexSlide - 1;
                        if (dotIndex < 0) dotIndex = slides.length - 1;
                        if (dotIndex >= slides.length) dotIndex = 0;
                        updateHorizontalDots(horizontalDots, dotIndex);
                    }

                    // Reset khi tới clone
                    sliderWrapper.addEventListener('transitionend', () => {
                        const slide = allSlides[indexSlide];
                        if (slide.classList.contains('clone-start')) {
                            sliderWrapper.style.transition = 'none';
                            indexSlide = 1;
                            sliderWrapper.style.transform = `translateX(-${indexSlide * 100}vw)`;
                            setTimeout(() => sliderWrapper.style.transition = 'transform 0.5s ease', 0);
                        } else if (slide.classList.contains('clone-end')) {
                            sliderWrapper.style.transition = 'none';
                            indexSlide = slides.length;
                            sliderWrapper.style.transform = `translateX(-${indexSlide * 100}vw)`;
                            setTimeout(() => sliderWrapper.style.transition = 'transform 0.5s ease', 0);
                        }
                    });

                    if (prevBtn) prevBtn.onclick = () => { clearInterval(autoSlideInterval); moveSlide(-1); };
                    if (nextBtn) nextBtn.onclick = () => { clearInterval(autoSlideInterval); moveSlide(1); };

                    horizontalDots.forEach((dot, i) => {
                        dot.onclick = () => {
                            clearInterval(autoSlideInterval);
                            indexSlide = i + 1;
                            sliderWrapper.style.transition = 'transform 0.5s ease';
                            sliderWrapper.style.transform = `translateX(-${indexSlide * 100}vw)`;
                            updateHorizontalDots(horizontalDots, i);
                        };
                    });
                }


                window.addEventListener('wheel', function(e) {
                    e.preventDefault();
                }, { passive: false });

                window.addEventListener('touchmove', function(e) {
                    e.preventDefault();
                }, { passive: false });

                    dotNav.addEventListener('click', e => {
                        if (e.target.classList.contains('dot')) scrollToSection(parseInt(e.target.getAttribute('data-index')));
                    });

                    sections.forEach((_, i) => initializeSection(i));
            });
        </script>
    </body>
</html>