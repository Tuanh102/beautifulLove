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
            body, html {
                height: 100%;
                margin: 0;
                overflow: hidden;
            }

            .scroll-section {
                width: 100%;
                height: 100vh;
                position: relative;
            }

            .horizontal-slider {
                width: 100%;
                height: 100%;
                position: relative;
            }

            .slide-wrapper {
                display: flex;
                height: 100%;
                transition: transform 0.5s ease;
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
            
            /* Container cho title và short_content */
            .slide-content {
                position: absolute;
                top: 50%;
                left: 50px;
                transform: translateY(-50%);
                color: white;
                z-index: 10;
                max-width: 50%; /* Giới hạn chiều rộng để nội dung không quá dài */
            }

            /* CSS cho Tiêu đề (Title) */
            .slide-title {
                font-size: 3.5rem; /* GIẢM TỪ 5rem XUỐNG 3.5rem */
                font-weight: bold;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                font-family: 'Times New Roman', Times, serif;
                margin-bottom: 10px; /* Khoảng cách với nội dung ngắn */
            }

            /* CSS cho Nội dung ngắn (Short Content) */
            .slide-short-content {
                font-size: 1.2rem; /* GIẢM TỪ 1.5rem XUỐNG 1.2rem */
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
                font-family: Arial, sans-serif;
                line-height: 1.6;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .slide-content {
                    left: 20px;
                    max-width: 80%;
                }
                .slide-title {
                    font-size: 2.5rem; /* GIẢM TỪ 3rem XUỐNG 2.5rem */
                }
                .slide-short-content {
                    font-size: 0.9rem; /* GIẢM TỪ 1rem XUỐNG 0.9rem */
                }
            }

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
                list-style: none;
                margin: 0;
                padding: 0;
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

            .vertical-controls {
                position: fixed;
                bottom: 40px;
                right: 40px;
                z-index: 100;
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .scroll-arrow {
                background-color: rgba(255, 255, 255, 0.5);
                border: 1px solid #ccc;
                color: #333;
                font-size: 20px;
                width: 45px;
                height: 45px;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .scroll-arrow:hover {
                background-color: #333;
                color: white;
            }

            .scroll-arrow:disabled {
                opacity: 0.3;
                cursor: not-allowed;
                background-color: #f0f0f0;
            }
        </style>
    </head>
    <body>
        <?php include 'header.php';?>

        <main class="main-section-wrapper">
            <?php
            $sections_sql = "SELECT DISTINCT section_id FROM slides ORDER BY section_id ASC";
            $sections_result = $conn->query($sections_sql);
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
                            $has_images = false;
                            while ($slide_row = $slides_result->fetch_assoc()) {
                                if ($slide_row['type'] === 'image') {
                                    $has_images = true;
                                }
                            ?>
                                <div class="slide" data-slide-index="<?php echo $slide_index; ?>">
                                    <div class="slide-content">
                                        <h1 class="slide-title"><?php echo htmlspecialchars($slide_row['title']); ?></h1>
                                        <p class="slide-short-content"><?php echo htmlspecialchars($slide_row['short_content']); ?></p>
                                    </div>
                                    
                                    <?php if ($slide_row['type'] === 'image'): ?>
                                        <img src="<?php echo $slide_row['src']; ?>" alt="<?php echo htmlspecialchars($slide_row['title']); ?>">
                                    <?php elseif ($slide_row['type'] === 'video'): ?>
                                        <video autoplay muted loop>
                                            <source src="<?php echo $slide_row['src']; ?>" type="video/mp4">
                                        </video>
                                    <?php endif; ?>
                                </div>
                            <?php
                                $slide_index++;
                            }
                            ?>
                        </div>
                    </div>

                    <?php if ($slide_count > 1 || ($slide_count === 1 && $has_images)): ?>
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

        <div class="vertical-controls">
            <button id="up-arrow" class="scroll-arrow"><i class="fas fa-chevron-up"></i></button>
            <button id="down-arrow" class="scroll-arrow"><i class="fas fa-chevron-down"></i></button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sections = document.querySelectorAll('.scroll-section');
                const upArrow = document.getElementById('up-arrow');
                const downArrow = document.getElementById('down-arrow');
                const sectionCount = sections.length;
                let currentSectionIndex = 0;
                let isScrolling = false;

                const sliders = [];

                function updateArrowStates() {
                    upArrow.disabled = currentSectionIndex === 0;
                    downArrow.disabled = currentSectionIndex === sectionCount - 1;
                }

                function scrollToSection(index) {
                    if (isScrolling) return;
                    isScrolling = true;

                    if (sliders[currentSectionIndex]) {
                        sliders[currentSectionIndex].stopAutoSlide();
                    }

                    sections[index].scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    currentSectionIndex = index;
                    updateArrowStates();

                    setTimeout(() => {
                        isScrolling = false;
                        if (sliders[currentSectionIndex]) {
                            sliders[currentSectionIndex].startAutoSlide();
                        }
                    }, 1000);
                }

                function setupHorizontalSlider(section, index) {
                    const sliderWrapper = section.querySelector('.slide-wrapper');
                    const slides = Array.from(sliderWrapper.querySelectorAll('.slide'));
                    const horizontalDots = section.querySelector('.horizontal-dot-navigation .dots');
                    const prevBtn = section.querySelector('.prev-slide');
                    const nextBtn = section.querySelector('.next-slide');

                    if (slides.length <= 1) return;

                    let currentSlideIndex = 0;
                    let autoSlideInterval;

                    function updateHorizontalDots(i) {
                        if (horizontalDots) {
                            const dots = horizontalDots.querySelectorAll('.dot');
                            dots.forEach((dot, idx) => dot.classList.toggle('active', idx === i));
                        }
                    }

                    function moveSlide(direction) {
                        currentSlideIndex += direction;
                        if (currentSlideIndex < 0) currentSlideIndex = slides.length - 1;
                        if (currentSlideIndex >= slides.length) currentSlideIndex = 0;
                        sliderWrapper.style.transform = `translateX(-${currentSlideIndex * 100}vw)`;
                        updateHorizontalDots(currentSlideIndex);
                    }

                    if (prevBtn) prevBtn.onclick = () => { clearInterval(autoSlideInterval); moveSlide(-1); };
                    if (nextBtn) nextBtn.onclick = () => { clearInterval(autoSlideInterval); moveSlide(1); };

                    if (horizontalDots) {
                        horizontalDots.querySelectorAll('.dot').forEach((dot, i) => {
                            dot.onclick = () => {
                                clearInterval(autoSlideInterval);
                                currentSlideIndex = i;
                                sliderWrapper.style.transform = `translateX(-${currentSlideIndex * 100}vw)`;
                                updateHorizontalDots(i);
                            };
                        });
                    }

                    sliders[index] = {
                        startAutoSlide: () => {
                            autoSlideInterval = setInterval(() => moveSlide(1), 5000);
                        },
                        stopAutoSlide: () => {
                            clearInterval(autoSlideInterval);
                        }
                    };

                    updateHorizontalDots(0);
                }

                upArrow.addEventListener('click', () => {
                    if (currentSectionIndex > 0) {
                        scrollToSection(currentSectionIndex - 1);
                    }
                });

                downArrow.addEventListener('click', () => {
                    if (currentSectionIndex < sectionCount - 1) {
                        scrollToSection(currentSectionIndex + 1);
                    }
                });

                let scrollTimeout;
                window.addEventListener('wheel', function(e) {
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(() => {
                        if (isScrolling) return;
                        const direction = e.deltaY > 0 ? 1 : -1;
                        if (direction > 0 && currentSectionIndex < sectionCount - 1) {
                            scrollToSection(currentSectionIndex + 1);
                        } else if (direction < 0 && currentSectionIndex > 0) {
                            scrollToSection(currentSectionIndex - 1);
                        }
                    }, 100);
                }, { passive: false });

                window.addEventListener('touchmove', function(e) {
                    e.preventDefault();
                }, { passive: false });

                sections.forEach((section, i) => setupHorizontalSlider(section, i));

                if (sliders[0]) sliders[0].startAutoSlide();
                updateArrowStates();
            });
        </script>
    </body>
</html>