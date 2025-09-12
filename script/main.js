// Hàm này sẽ tải nội dung từ các file header.html và footer.html
function loadHTML(id, file) {
    fetch(file)
        .then(response => response.text())
        .then(data => {
            ocument.getElementById(id).innerHTML = data;
        });
    }
        
// Gọi hàm để tải header và footer khi trang được tải xong
document.addEventListener('DOMContentLoaded', function() {
    oadHTML('header-container', 'header.html');
    loadHTML('footer-container', 'footer.html');
});

let current = 0;
const slides = document.querySelectorAll('.slide');

setInterval(() => {
    current = (current + 1) % slides.length;
    slides[current].scrollIntoView({ behavior: 'smooth' });
}, 5000);

// fullPage.js
new fullpage('#fullpage', {
    autoScrolling: true,
    navigation: true,
    scrollingSpeed: 800
});

// Swiper cho tất cả các section
document.querySelectorAll('.swiper').forEach(swiperEl => {
    new Swiper(swiperEl, {
        loop: true,
        effect: 'fade', // Hiệu ứng mờ dần
        autoplay: {
            delay: 4000,
            disableOnInteraction: false
        },
        navigation: {
            nextEl: swiperEl.querySelector('.swiper-button-next'),
            prevEl: swiperEl.querySelector('.swiper-button-prev'),
        },
        keyboard: true
    });
});




 // ----------------Đoạn mã JavaScript để xử lý hiệu ứng trượt từng ảnh một----------------
            
 document.addEventListener('DOMContentLoaded', () => {
                
    const carouselInner = document.querySelector('.brand-carousel-inner');                
    const prevBtn = document.getElementById('brand-prev-btn');              
    const nextBtn = document.getElementById('brand-next-btn');
    const totalItems = carouselInner.children.length;
    const itemsPerView = 5;
    let currentIndex = 0;

    function updateCarousel() {
        const itemWidth = carouselInner.children[0].offsetWidth;
        const containerWidth = carouselInner.offsetWidth;
        const scrollDistance = itemWidth; // Khoảng cách trượt là chiều rộng của 1 ảnh            
        const maxTranslateX = (totalItems - itemsPerView) * itemWidth;

        carouselInner.style.transform = `translateX(-${currentIndex * scrollDistance}px)`;

        // Xử lý nút prev/next khi ở cuối/đầu
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex >= totalItems - itemsPerView;
    }

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });
    nextBtn.addEventListener('click', () => {
        if (currentIndex < totalItems - itemsPerView) {
            currentIndex++;
            updateCarousel();
        }
    });
    // Khởi tạo ban đầu
    updateCarousel();
    // Cập nhật lại khi cửa sổ trình duyệt thay đổi kích thước
    window.addEventListener('resize', updateCarousel);
});