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
    :root{
      /* chỉnh nếu header fixed cao hơn/thấp hơn */
      --header-space: 120px;
    }
    html, body { height: 100%; }
    body {
      font-family: 'Inter', sans-serif;
      background-color: #fefefe;
      color: #333;
      overflow-x: hidden; /* tránh scrollbar ngang làm “xô” header/footer */
    }

    /* ----- Layout chính ----- */
    main.page-intro {
      padding-top: var(--header-space);
      padding-bottom: 40px;
    }

    .intro-title {
      font-size: clamp(28px, 3.2vw, 48px);
      font-weight: 700;
      color: #4b5563;
      margin-bottom: 16px;
    }
    .intro-text {
      font-size: 1.05rem;
      line-height: 1.8;
      color: #6b7280;
      margin-bottom: 14px;
    }
    .intro-box .row {
      align-items: center; /* căn giữa dọc */
    }

    .intro-content {
      padding: 0 20px;       /* padding trái + phải đều 20px */
      text-align: justify;
      display: flex;
      flex-direction: column;
      justify-content: center;
      height: 100%;
    }


    .intro-content p {
      margin: 0 0 1rem 0;    /* khoảng cách dưới đoạn */
      padding-left: 50px;    /* cách viền trái 20px */
      padding-right: 10px;   /* cách viền phải 10px để text không sát */
      line-height: 1.8;
      font-size: 1.05rem;
    }


/**----------------------- */
    .title-box {
      background-color: #fff;
      border: 5px solid red;
      border-radius: 40px 0px 40px 0px;
      padding: 12px 24px;
      margin: 0 auto 40px;
      display: flex;
      justify-content: center; /* căn giữa chữ */
      align-items: center;
      max-width: 600px; /* khung vừa phải, không quá dài */
      background-color: black;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .title-box h1 {
      margin: 0;
      color: red;
      font-size: clamp(26px, 3vw, 40px);
      font-weight: 700;
      text-align: center;
      font-family: 'Script MT Bold', 'Georgia', serif;
    }
    .title-box:hover{
      transform: translateY(-4px);
      box-shadow: 0 16px 32px rgba(0,0,0,0.28)
    } 

    /* ----- Card thành viên (giống bản cũ) ----- */
    .teamcard {
      background-color: #1a1a1a;
      color: #fefefe;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      transition: transform .25s ease, box-shadow .25s ease;
      display: flex;
      align-items: center;
      padding: 1rem 1.25rem;
      width: 100%;
      max-width: 420px;
      margin: 0 auto 1rem;
      cursor: pointer;
      border: 0;
      text-align: left;
      margin-left: auto;
      margin-right: auto;
    }
    .teamcard:hover{
      transform: translateY(-4px);
      box-shadow: 0 16px 32px rgba(0,0,0,0.28);
    }
    .image-container{
      width: 64px;
      height: 64px;
      border-radius: 50%;
      overflow: hidden;
      margin-right: 1rem;
      flex-shrink: 0;
      animation: spin 15s linear infinite;
      border: 2px solid rgba(255,255,255,.25);
    }
    .image-container img{
      width: 100%; height: 100%; object-fit: cover;
    }
    @keyframes spin{
      from{ transform: rotate(0deg); }
      to{ transform: rotate(360deg); }
    }
    .team-info h2{
      font-size: 1rem;
      font-weight: 600;
      margin: 0 0 2px 0;
    }
    .team-info p{
      font-size: .9rem;
      color: #d1d5db;
      margin: 0;
    }

    /* ----- Overlay chi tiết (giống bản cũ) ----- */
    .modal-overlay{
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.8);
      justify-content: center;
      align-items: center;
      z-index: 1100; /* cao hơn header/footer */
      padding: 20px;
    }
    .expanded-card{
      background: #1a1a1a;
      color: #fefefe;
      border-radius: 1rem;
      box-shadow: 0 15px 40px rgba(0,0,0,0.5);
      padding: 2rem;
      width: 100%;
      max-width: 720px;
       overflow-y: auto;
      position: relative;
      transform: scale(.9);
      opacity: 0;
      animation: expand .3s forwards;
      overflow: hidden;
    }
    @keyframes expand{
      to{ transform: scale(1); opacity: 1; }
    }
    .expanded-card .close-btn{
      position: absolute;
      top: 12px; right: 14px;
      background: none; border: none;
      color: #fff; font-size: 28px; line-height: 1;
      cursor: pointer;
      padding: 6px 10px;
      border-radius: 8px;
      transition: background .2s;
    }
    .expanded-card .close-btn:hover{
      background: rgba(255,255,255,.1);
    }
    .expanded-image-container{
      width: 150px; height: 150px;
      border-radius: 50%;
      overflow: hidden;
      margin: 0 auto 1.25rem;
      border: 4px solid #fff;
    }
    .expanded-image-container img{
      width: 100%; height: 100%; object-fit: cover;
    }
    .expanded-card h2{
      font-size: 2rem; font-weight: 700; text-align: center; margin-bottom: .25rem;
    }
    .expanded-card h3{
      font-size: 1.15rem; color: #d1d5db; text-align: center; margin-bottom: 1.25rem;
    }
    .expanded-card p{
      font-size: 1rem; line-height: 1.75; text-align: justify;
    }

    @media (max-width: 991.98px){
      .teamcard{ max-width: 100%; }
    }

 /*----------Dịch vụ khách hàng----------------*/
.service-card {
  background-color: #1a1a1a;
  color: #fefefe;
  border-radius: 1rem;
  box-shadow: 0 12px 30px rgba(0,0,0,0.3);
  transition: transform .25s ease, box-shadow .25s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1.5rem 1rem;
  width: 100%;
  max-width: 280px;
  cursor: pointer;
  margin: 0 auto 1.5rem;
  border: 0;
  text-align: center;
}

.service-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.35);
}

.service-card .service-image {
  width: 120px;
  height: 120px;
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 1rem;
  border: 2px solid rgba(255,255,255,.2);
}

.service-card .service-image img{
  width: 100%; height: 100%; object-fit: cover;
}

.service-card h3 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.service-card h5 {
  font-size: 1rem;
  font-weight: 500;
  color: #d1d5db;
  margin-bottom: 0.5rem;
}

.service-card p {
  font-size: 0.95rem;
  color: #d1d5db;
  margin: 0;
  text-align: justify;
}

/* Thêm sau CSS cũ */
.expanded-card.service-layout {
  display: flex;
  gap: 20px;
  max-height: 80vh;
  overflow-y: auto; /* cho nội dung dài scroll */
}

.expanded-card.service-layout .expanded-image-container {
  width: 150px;
  height: 150px;
  border-radius: 12px; /* ảnh vuông bo góc */
  flex-shrink: 0;
  border: 2px solid #fff;
  margin: 0;
}

.expanded-card.service-layout .expanded-info {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.expanded-card.service-layout .expanded-info h2 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 700;
}

.expanded-card.service-layout .expanded-info h5 {
  margin: 0 0 10px 0;
  font-size: 1rem;
  color: #d1d5db;
}

.expanded-card.service-layout .expanded-info .expandedBio {
  font-size: 0.95rem;
  line-height: 1.6;
  text-align: justify;
}
.expandedBio {
  max-height: 400px;      /* để kéo khi nội dung dài */
  overflow-y: auto;       /* bật thanh cuộn dọc */
  padding: 0 12px;        /* cách viền trái và phải 12px */
  margin-top: 8px;        /* tạo khoảng cách trên với tiêu đề */
  line-height: 1.6;       /* dễ đọc hơn */
   text-align: justify;
}


 
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <main class="page-intro">
    <div class="intro-box text-center">
      <!-- Tiêu đề trong khung -->
      <div class="title-box" id="target1">
        <h1 class="intro-title">Về Beautiful Love & Team</h1>
      </div>

      <div class="row g-5 align-items-center">
        <!-- Bên trái: giới thiệu -->
        <div class="col-lg-6 intro-content">
          <p class="intro-text">
            <span style="color:red; font-family: 'Cursive', 'Georgia', serif;"><strong><span style="font-size:2em">B</span>eautiful Love</strong></span> là sản phẩm của bài tập nhóm trong học phần Công nghệ thông tin. Đây không chỉ là một trang web minh họa, mà còn là kết quả của sự hợp tác, sáng tạo và chia sẻ kiến thức giữa các thành viên.
          </p>
          <p class="intro-text">
            Trong quá trình thực hiện, nhóm đã cùng nhau xây dựng ý tưởng, phân chia công việc và phát triển nội dung. Mỗi thành viên đều đóng góp một phần quan trọng, từ thiết kế giao diện, xử lý dữ liệu đến hoàn thiện chức năng. Beautiful Love chính là nơi ghi dấu sự phối hợp ăn ý và tinh thần trách nhiệm của cả nhóm.
          </p>
          <p class="intro-text">
            Thông qua dự án này, chúng em không chỉ học hỏi thêm nhiều kỹ năng lập trình và thiết kế web, mà còn rèn luyện tinh thần làm việc nhóm, khả năng quản lý thời gian và giải quyết vấn đề. Beautiful Love là minh chứng cho nỗ lực chung và là niềm tự hào của cả nhóm trong suốt quá trình học tập.
          </p>
        </div>

        <!-- Bên phải: Team -->
        <div class="col-lg-6">

          <button class="teamcard" id="card-anh" type="button">
            <div class="image-container">
              <img src="../image/introduce/anh.jpg" alt="Trần Tuấn Anh">
            </div>
            <div class="team-info">
              <h2>Trần Tuấn Anh</h2>
              <p>2354800007</p>
            </div>
          </button>

          <button class="teamcard" id="card-thu" type="button">
            <div class="image-container">
              <img src="../image/introduce/thu.jpg" alt="Đỗ Đặng Anh Thư">
            </div>
            <div class="team-info">
              <h2>Đỗ Đặng Anh Thư</h2>
              <p>2354800234</p>
            </div>
          </button>

          <button class="teamcard" id="card-duc" type="button">
            <div class="image-container">
              <img src="../image/introduce/duc.jpg" alt="Lê Hồng Đức">
            </div>
            <div class="team-info">
              <h2>Lê Hồng Đức</h2>
              <p>2354800200</p>
            </div>
          </button>

          <button class="teamcard" id="card-khanh" type="button">
            <div class="image-container">
              <img src="../image/introduce/khanh.png" alt="Đỗ Quốc Khánh">
            </div>
            <div class="team-info">
              <h2>Đỗ Quốc Khánh</h2>
              <p>2354800217</p>
            </div>
          </button>

          <button class="teamcard" id="card-thanh" type="button">
            <div class="image-container">
              <img src="../image/introduce/thanh.png" alt="Văn Đình Thành">
            </div>
            <div class="team-info">
              <h2>Văn Đình Thành</h2>
              <p>2354800126</p>
            </div>
          </button>
        </div>
      </div>
    </div>
    <br><br>
    <div class="intro-box text-center">
  <div class="title-box" id="target2">
    <h1 class="intro-title">Dịch vụ khách hàng</h1>
  </div>

  <div class="container my-5">
    <div class="row justify-content-center g-4">

      <!-- Thẻ dịch vụ mẫu -->
      <div class="col-lg-4 col-md-6">
        <button class="service-card" id="service-1" type="button">
          <div class="service-image">
            <img src="../image/introduce/cs1.jpg" alt="Chính sách điều khoản">
          </div>
          <h3>Chính sách điều khoản</h3>
          <h5>Tổng quan & Chi tiết</h5>
          <p>Chi tiết xem sau.</p>
        </button>
      </div>

      <div class="col-lg-4 col-md-6">
        <button class="service-card" id="service-2" type="button">
          <div class="service-image">
            <img src="../image/introduce/cs2.jpg" alt="Hướng dẫn mua hàng">
          </div>
          <h3>Hướng dẫn mua hàng</h3>
          <h5>Bước & Quy định</h5>
          <p>Chi tiết xem sau.</p>
        </button>
      </div>

      <div class="col-lg-4 col-md-6">
        <button class="service-card" id="service-3" type="button">
          <div class="service-image">
            <img src="../image/introduce/cs3.jpg" alt="Chính sách đổi trả">
          </div>
          <h3>Chính sách đổi trả</h3>
          <h5>Quy trình & Lưu ý</h5>
          <p>Chi tiết xem sau.</p>
        </button>
      </div>

      <div class="col-lg-4 col-md-6">
        <button class="service-card" id="service-4" type="button">
          <div class="service-image">
            <img src="../image/introduce/cs4.jpg" alt="Chính sách bảo hành">
          </div>
          <h3>Chính sách bảo hành</h3>
          <h5>Quy trình & Lưu ý</h5>
          <p>Chi tiết xem sau.</p>
        </button>
      </div>

      <div class="col-lg-4 col-md-6">
        <button class="service-card" id="service-5" type="button">
          <div class="service-image">
            <img src="../image/introduce/cs5.jpg" alt="Chính sách giao nhận">
          </div>
          <h3>Chính sách giao nhận</h3>
          <h5>Quy trình & Lưu ý</h5>
          <p>Chi tiết xem sau.</p>
        </button>
      </div>

      <div class="col-lg-4 col-md-6">
        <button class="service-card" id="service-6" type="button">
          <div class="service-image">
            <img src="../image/introduce/cs6.jpg" alt="Chính sách thành viên">
          </div>
          <h3>Chính sách thành viên</h3>
          <h5>Quyền lợi và nghĩa vụ</h5>
          <p>Chi tiết xem sau.</p>
        </button>
      </div>

      <div class="col-lg-4 col-md-6">
        <button class="service-card" id="service-7" type="button">
          <div class="service-image">
            <img src="../image/introduce/cs7.jpg" alt="Q&A">
          </div>
          <h3>Q&A</h3>
          <h5>Câu hỏi thường gặp</h5>
          <p>Chi tiết xem sau.</p>
        </button>
      </div>

      <!-- Thêm các thẻ còn lại tương tự -->
    </div>
  </div>
</div>

  </main>

  <?php include 'footer.php'; ?>

  <!-- Modal cho TEAM (có ảnh) -->
<div id="modalOverlayTeam" class="modal-overlay" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="expanded-card">
    <button class="close-btn" type="button" aria-label="Đóng">&times;</button>
    <div class="expanded-image-container">
      <img id="expandedImageTeam" src="" alt="">
    </div>
    <div class="expanded-info">
      <h2 id="expandedNameTeam"></h2>
      <h5 id="expandedTitleTeam" style="text-align:center;"></h5>
      <div id="expandedBioTeam" class="expandedBio"></div>
    </div>
  </div>
</div>

<!-- Modal cho DỊCH VỤ (chỉ text) -->
<div id="modalOverlayService" class="modal-overlay" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="expanded-card">
    <button class="close-btn" type="button" aria-label="Đóng">&times;</button>
    <div class="expanded-info">
      <h2 id="expandedNameService"></h2>
      <h5 id="expandedTitleService"></h5>
      <div id="expandedBioService" class="expandedBio"></div>
    </div>
  </div>
</div>





  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Data tượng trưng cho 1 người (giữ đúng format cũ)
    const teamData = {
      'card-anh': {
        name: 'Trần Tuấn Anh',
        title: '2354800007',
        bio: 'Trần Tuấn Anh – người giữ vai trò nhóm trưởng, không chỉ là người dẫn dắt mà còn là nguồn cảm hứng cho tập thể. Với tinh thần trách nhiệm, sự kiên định và khả năng kết nối, anh luôn định hướng rõ ràng con đường phía trước, đồng thời khuyến khích từng thành viên phát huy thế mạnh riêng. Sự tận tâm và nhiệt huyết của anh đã tạo nên một tập thể đoàn kết, sáng tạo và bền bỉ vượt qua thử thách để chinh phục mục tiêu chung.',
        image: '../image/introduce/anh.jpg'
      },

      'card-thu': {
        name: 'Đỗ Đặng Anh Thư',
        title: '2354800234',
        bio: 'Đỗ Đặng Anh Thư là một thành viên nhiệt huyết, luôn mang đến sự sáng tạo và tinh thần trách nhiệm trong mỗi công việc được giao. Với sự cẩn trọng và tinh thần cầu tiến, Thư không chỉ hoàn thành tốt nhiệm vụ của mình mà còn hỗ trợ tích cực cho các thành viên khác. Chính sự tận tâm và tinh thần đồng đội ấy đã góp phần quan trọng vào thành công chung của cả nhóm.',
        image: '../image/introduce/thu.jpg'
      },

      'card-duc': {
        name: 'Lê Hồng Đức',
        title: '2354800200',
        bio: 'Lê Hồng Đức là một thành viên năng động và luôn tràn đầy tinh thần học hỏi. Với sự kiên nhẫn và khả năng phân tích cẩn thận, Đức thường đảm nhận tốt những công việc đòi hỏi tính logic và chi tiết cao. Bên cạnh đó, Đức còn là người tạo không khí vui vẻ, gắn kết tinh thần tập thể, giúp nhóm làm việc hiệu quả và thoải mái hơn.',
        image: '../image/introduce/duc.jpg'
      },

      'card-khanh': {
        name: 'Đỗ Quốc Khánh',
        title: '2354800217',
        bio: 'Đỗ Quốc Khánh là một thành viên trách nhiệm và đáng tin cậy của nhóm. Với sự tập trung và tinh thần cầu tiến, Khánh luôn hoàn thành tốt nhiệm vụ được giao, đồng thời chủ động hỗ trợ khi đồng đội gặp khó khăn. Sự chững chạc và tinh thần làm việc nghiêm túc của Khánh đã góp phần quan trọng trong việc duy trì sự ổn định và tiến độ chung của cả nhóm.',
        image: '../image/introduce/khanh.png'
      },

      'card-thanh': {
        name: 'Văn Đình Thành',
        title: '2354800126',
        bio: 'Văn Đình Thành là một thành viên năng động và nhiệt tình, luôn sẵn sàng đóng góp ý tưởng mới mẻ để làm phong phú thêm cho công việc nhóm. Với tinh thần trách nhiệm và sự sáng tạo, Thành không chỉ hoàn thành tốt nhiệm vụ được giao mà còn mang lại nguồn năng lượng tích cực cho cả tập thể. Chính sự nhiệt huyết và tinh thần đồng hành ấy đã góp phần giúp nhóm ngày càng gắn kết và hiệu quả hơn.',
        image: '../image/introduce/thanh.png'
      },
    };

    //Data từng thẻ dịch vụ khách hàng
    const serviceData = {
      'service-1': {
          title: 'Chính sách điều khoản',
          bio: `
            <h4>Tổng quan:</h4>
            <p>Chính sách điều khoản là tài liệu quan trọng nhằm bảo vệ quyền lợi và trách nhiệm của khách hàng khi sử dụng sản phẩm và dịch vụ của chúng tôi. Mỗi khách hàng khi truy cập website, thực hiện giao dịch hay sử dụng dịch vụ đều nên đọc kỹ các điều khoản này để đảm bảo hiểu rõ quyền lợi và nghĩa vụ của mình.</p>
            
            <ul>
              <li><strong>Quyền lợi khách hàng:</strong> Khách hàng có quyền được cung cấp sản phẩm đúng chất lượng, đúng mô tả và được hỗ trợ dịch vụ chăm sóc khách hàng khi gặp sự cố. Chúng tôi cam kết minh bạch trong mọi thông tin, đảm bảo quyền được hoàn tiền, đổi trả theo đúng quy định.</li>
              <li><strong>Trách nhiệm khách hàng:</strong> Khách hàng cần cung cấp thông tin chính xác, tuân thủ hướng dẫn sử dụng sản phẩm và dịch vụ, đồng thời báo cáo kịp thời các vấn đề phát sinh để được hỗ trợ hiệu quả. Việc hiểu và tuân thủ điều khoản giúp hạn chế rủi ro và tranh chấp không đáng có.</li>
              <li><strong>Điều kiện áp dụng:</strong> Tất cả sản phẩm và dịch vụ chỉ áp dụng khi khách hàng đồng ý và thực hiện đúng theo điều khoản. Điều này bao gồm các giao dịch trực tuyến, mua bán, đổi trả và bảo hành sản phẩm.</li>
            </ul>

            <h4>Chi tiết:</h4>
            <p>Để đảm bảo khách hàng có trải nghiệm tốt nhất, chúng tôi phân tách các chi tiết điều khoản thành các phần rõ ràng:</p>

            <ul>
              <li><strong>Thanh toán:</strong> Khách hàng có thể thanh toán bằng nhiều phương thức như chuyển khoản, thẻ tín dụng, ví điện tử. Mỗi phương thức đều có hướng dẫn chi tiết và biện pháp bảo mật thông tin để đảm bảo an toàn giao dịch.</li>
              <li><strong>Hoàn tiền và hủy đơn:</strong> Nếu khách hàng phát hiện sản phẩm lỗi, không đúng mô tả hoặc muốn hủy đơn, chúng tôi sẽ hướng dẫn chi tiết quy trình hoàn tiền hoặc hủy đơn trong thời gian ngắn nhất, đảm bảo khách hàng nhận lại số tiền đã thanh toán.</li>
              <li><strong>Bảo mật thông tin cá nhân:</strong> Mọi thông tin cá nhân của khách hàng, bao gồm tên, địa chỉ, số điện thoại và các thông tin giao dịch đều được bảo vệ tuyệt đối. Chúng tôi cam kết không chia sẻ thông tin với bên thứ ba nếu không có sự đồng ý của khách hàng.</li>
              <li><strong>Cam kết chất lượng sản phẩm và dịch vụ:</strong> Tất cả sản phẩm đều được kiểm tra kỹ trước khi gửi đến khách hàng. Dịch vụ chăm sóc khách hàng luôn sẵn sàng hỗ trợ, tư vấn và giải quyết các vấn đề phát sinh một cách nhanh chóng, tận tâm và minh bạch.</li>
            </ul>

            <p>Chúng tôi khuyến nghị khách hàng thường xuyên cập nhật các chính sách điều khoản trên website để nắm bắt các thay đổi hoặc bổ sung mới. Việc đọc kỹ điều khoản sẽ giúp khách hàng yên tâm hơn khi sử dụng dịch vụ, đồng thời nâng cao trải nghiệm mua sắm, giao dịch và sử dụng sản phẩm của Beautiful Love.</p>

            <p>Chính sách điều khoản không chỉ là quy định pháp lý, mà còn là cam kết của chúng tôi với khách hàng về chất lượng, minh bạch và uy tín. Chúng tôi luôn lắng nghe phản hồi để hoàn thiện hơn, mang đến sự hài lòng tối đa và xây dựng niềm tin lâu dài với mọi khách hàng.</p>
          `
        },


      'service-2': {
        title: 'Hướng dẫn mua hàng',
        bio: `
          <h4>Bước hướng dẫn:</h4>
          <p>Để mang đến trải nghiệm mua sắm trực tuyến thuận tiện và an toàn, chúng tôi cung cấp hướng dẫn chi tiết cho khách hàng từ lúc chọn sản phẩm cho đến khi hoàn tất đơn hàng. Việc làm theo hướng dẫn này giúp khách hàng tránh sai sót, tiết kiệm thời gian và đảm bảo nhận được sản phẩm đúng yêu cầu.</p>
          
          <ul>
            <li><strong>Chọn sản phẩm:</strong> Khách hàng truy cập danh sách sản phẩm, xem thông tin chi tiết, đánh giá, và chọn sản phẩm phù hợp với nhu cầu. Có thể so sánh nhiều sản phẩm để lựa chọn tối ưu.</li>
            <li><strong>Thêm vào giỏ hàng:</strong> Sau khi chọn sản phẩm, nhấn “Thêm vào giỏ” để lưu tạm thời. Khách hàng có thể tiếp tục mua sắm hoặc xem giỏ để kiểm tra các sản phẩm đã chọn.</li>
            <li><strong>Điền thông tin nhận hàng và thanh toán:</strong> Khách hàng cung cấp thông tin chính xác về địa chỉ, số điện thoại và phương thức thanh toán. Chúng tôi hỗ trợ nhiều phương thức như chuyển khoản, ví điện tử, thẻ tín dụng để linh hoạt.</li>
            <li><strong>Xác nhận đơn hàng:</strong> Kiểm tra lại toàn bộ thông tin, nhấn xác nhận đơn. Hệ thống sẽ gửi email hoặc thông báo để khách hàng theo dõi trạng thái xử lý.</li>
          </ul>

          <h4>Lưu ý:</h4>
          <ul>
            <li>Đảm bảo thông tin cá nhân và địa chỉ chính xác để tránh thất lạc hoặc chậm trễ khi giao hàng.</li>
            <li>Liên hệ bộ phận CSKH nếu có vấn đề phát sinh như thay đổi địa chỉ, hủy đơn hoặc yêu cầu hỗ trợ.</li>
            <li>Luôn kiểm tra số lượng, mẫu mã, và thông tin sản phẩm trước khi xác nhận đơn hàng để tránh nhầm lẫn.</li>
          </ul>

          <p>Tuân thủ hướng dẫn này, khách hàng sẽ nhận được sản phẩm đúng chất lượng, đúng thời gian và có trải nghiệm mua sắm trực tuyến an toàn, tiện lợi. Chúng tôi luôn nỗ lực để quá trình đặt hàng trở nên đơn giản, minh bạch và nhanh chóng.</p>
        `
      },

      'service-3': {
        title: 'Chính sách đổi trả',
        bio: `
          <h4>Quy trình đổi trả:</h4>
          <p>Beautiful Love cam kết mang đến sự hài lòng tối đa cho khách hàng. Trong trường hợp sản phẩm gặp sự cố, không đúng mô tả hoặc khách hàng muốn đổi/trả, chúng tôi cung cấp quy trình chi tiết để giải quyết nhanh chóng và minh bạch.</p>
          
          <ul>
            <li><strong>Liên hệ CSKH:</strong> Khách hàng thông báo về sản phẩm muốn đổi/trả qua email hoặc hotline. Bộ phận CSKH sẽ hướng dẫn các bước tiếp theo, xác nhận điều kiện đổi trả.</li>
            <li><strong>Đóng gói sản phẩm:</strong> Sản phẩm cần được đóng gói nguyên vẹn, kèm đầy đủ phụ kiện và hóa đơn mua hàng để đảm bảo quyền lợi.</li>
            <li><strong>Gửi về kho:</strong> Khách hàng gửi sản phẩm về kho theo hướng dẫn, sau đó bộ phận kho kiểm tra và xác nhận tình trạng sản phẩm.</li>
            <li><strong>Nhận xác nhận:</strong> Sau khi kiểm tra, khách hàng nhận thông báo chấp thuận đổi/trả và tiến hành hoàn tiền hoặc nhận sản phẩm thay thế.</li>
          </ul>

          <h4>Lưu ý:</h4>
          <ul>
            <li>Sản phẩm phải còn nguyên vẹn, chưa qua sử dụng và không bị hư hỏng do lỗi khách hàng.</li>
            <li>Đính kèm hóa đơn, phiếu bảo hành hoặc chứng từ liên quan để thuận tiện cho việc xử lý.</li>
            <li>Thời gian xử lý đổi/trả có thể khác nhau tùy thuộc vào loại sản phẩm, nhưng chúng tôi cam kết nhanh chóng và minh bạch.</li>
            <li>Liên hệ CSKH nếu có thắc mắc hoặc yêu cầu hỗ trợ để đảm bảo quyền lợi và trải nghiệm tốt nhất.</li>
          </ul>

          <p>Chính sách đổi trả của chúng tôi được xây dựng để bảo vệ quyền lợi khách hàng, đảm bảo sự minh bạch, công bằng và tiện lợi. Việc tuân thủ quy trình giúp quá trình đổi/trả diễn ra nhanh chóng, giảm rủi ro và tăng sự hài lòng khi sử dụng dịch vụ của Beautiful Love.</p>
        `
      },

      'service-4': {
        title: 'Chính sách bảo hành',
        bio: `
          <h4>Tổng quan:</h4>
          <p>Chính sách bảo hành của Beautiful Love được thiết kế nhằm bảo vệ quyền lợi khách hàng và đảm bảo chất lượng sản phẩm. Khi mua sản phẩm, khách hàng được hưởng các quyền lợi liên quan đến bảo hành nếu sản phẩm gặp lỗi kỹ thuật hoặc hư hỏng không do lỗi người dùng.</p>

          <h4>Phạm vi bảo hành:</h4>
          <ul>
            <li>Sản phẩm được bảo hành trong thời gian quy định từ ngày mua, tùy loại sản phẩm.</li>
            <li>Bảo hành áp dụng cho các lỗi do nhà sản xuất, không áp dụng cho hư hỏng do người dùng gây ra.</li>
            <li>Sản phẩm phải còn nguyên vẹn, không bị rơi vỡ, tháo lắp hoặc sửa chữa trái phép.</li>
          </ul>

          <h4>Quy trình bảo hành:</h4>
          <ul>
            <li><strong>Liên hệ CSKH:</strong> Khách hàng thông báo sự cố qua email hoặc hotline. Nhân viên sẽ hướng dẫn chi tiết các bước tiếp theo.</li>
            <li><strong>Kiểm tra sản phẩm:</strong> Sản phẩm được gửi về trung tâm bảo hành để kiểm tra tình trạng và xác nhận lỗi thuộc phạm vi bảo hành.</li>
            <li><strong>Tiến hành bảo hành:</strong> Nếu sản phẩm đủ điều kiện, chúng tôi sẽ sửa chữa, thay thế linh kiện hoặc đổi sản phẩm mới theo quy định.</li>
            <li><strong>Hoàn tất:</strong> Sau khi bảo hành xong, khách hàng sẽ nhận thông báo và được gửi sản phẩm trở lại hoặc nhận sản phẩm thay thế.</li>
          </ul>

          <h4>Lưu ý:</h4>
          <ul>
            <li>Khách hàng cần giữ phiếu mua hàng và chứng từ liên quan để chứng minh quyền lợi bảo hành.</li>
            <li>Không áp dụng bảo hành đối với các hư hỏng do sử dụng sai cách, va đập, tiếp xúc với môi trường khắc nghiệt hoặc tự ý sửa chữa.</li>
            <li>Thời gian bảo hành có thể thay đổi tùy loại sản phẩm, nhưng chúng tôi cam kết xử lý nhanh chóng và minh bạch.</li>
            <li>Luôn liên hệ CSKH để được hỗ trợ kịp thời và tránh sai sót trong quá trình gửi sản phẩm bảo hành.</li>
          </ul>

          <p>Chính sách bảo hành của Beautiful Love được xây dựng để đảm bảo khách hàng yên tâm khi sử dụng sản phẩm. Việc tuân thủ quy trình giúp xử lý nhanh chóng, minh bạch và mang lại trải nghiệm dịch vụ chuyên nghiệp. Chúng tôi luôn cam kết lắng nghe phản hồi từ khách hàng để cải thiện chất lượng dịch vụ và nâng cao uy tín lâu dài.</p>
        `
      },

      'service-5': {
        title: 'Chính sách giao nhận',
        bio: `
          <h4>Quy trình giao nhận:</h4>
          <p>Chúng tôi cam kết cung cấp dịch vụ giao nhận nhanh chóng, an toàn và minh bạch cho mọi khách hàng. Khi đơn hàng được xác nhận, hệ thống sẽ lập tức thông báo đến bộ phận kho và đối tác vận chuyển để xử lý. Mỗi đơn hàng được gán mã theo dõi riêng, giúp khách hàng dễ dàng kiểm tra trạng thái vận chuyển từ lúc xuất kho cho đến khi giao nhận thành công.</p>

          <p>Để đảm bảo việc giao hàng thuận lợi, khách hàng nên cung cấp chính xác các thông tin như địa chỉ, số điện thoại và thời gian nhận hàng mong muốn. Chúng tôi luôn ưu tiên giao hàng đúng hẹn và trong điều kiện tốt nhất.</p>

          <h4>Lưu ý quan trọng:</h4>
          <ul>
            <li>Kiểm tra kỹ thông tin trước khi xác nhận đơn hàng để tránh nhầm lẫn địa chỉ hoặc sản phẩm.</li>
            <li>Trong trường hợp không thể giao hàng do khách hàng vắng mặt, chúng tôi sẽ liên hệ để sắp xếp thời gian giao lại hoặc hoàn trả.</li>
            <li>Mọi vấn đề phát sinh trong quá trình vận chuyển, như mất mát hoặc hư hỏng sản phẩm, sẽ được xử lý theo quy định đổi trả và bảo hành đã nêu.</li>
            <li>Khách hàng nên giữ lại hóa đơn và phiếu giao hàng để thuận tiện cho việc đối chiếu và hỗ trợ nếu cần thiết.</li>
          </ul>

          <p>Chúng tôi khuyến nghị khách hàng thường xuyên kiểm tra trạng thái đơn hàng và liên hệ với bộ phận chăm sóc khách hàng nếu có bất kỳ thắc mắc hoặc yêu cầu hỗ trợ nào. Việc tuân thủ các quy định giao nhận sẽ giúp quá trình nhận hàng diễn ra suôn sẻ và nâng cao trải nghiệm dịch vụ.</p>

          <p>Beautiful Love cam kết mang đến dịch vụ giao nhận đáng tin cậy, nhanh chóng và an toàn, đảm bảo khách hàng nhận sản phẩm đúng chất lượng, đúng hẹn và hài lòng tối đa với trải nghiệm mua sắm.</p>
        `
      },

      'service-6': {
        title: 'Chính sách thành viên',
        bio: `
          <h4>Quyền lợi thành viên:</h4>
          <p>Mỗi khách hàng khi đăng ký trở thành thành viên của Beautiful Love sẽ được hưởng nhiều quyền lợi độc quyền nhằm nâng cao trải nghiệm mua sắm và sử dụng dịch vụ. Thành viên sẽ nhận được các ưu đãi, khuyến mãi đặc biệt, điểm thưởng khi mua hàng, thông báo sớm về các chương trình mới, cũng như quyền truy cập vào các nội dung và tính năng dành riêng.</p>

          <ul>
            <li><strong>Điểm thưởng:</strong> Thành viên sẽ tích lũy điểm khi thực hiện các giao dịch, mỗi điểm có thể quy đổi thành ưu đãi hoặc quà tặng.</li>
            <li><strong>Ưu đãi đặc biệt:</strong> Nhận giảm giá, voucher và các chương trình khuyến mãi chỉ dành cho thành viên.</li>
            <li><strong>Thông báo sớm:</strong> Nhận thông tin về các sản phẩm mới, chương trình sự kiện trước khi công bố rộng rãi.</li>
            <li><strong>Trải nghiệm cá nhân hóa:</strong> Gợi ý sản phẩm phù hợp dựa trên lịch sử mua sắm và sở thích của thành viên.</li>
          </ul>

          <h4>Nghĩa vụ thành viên:</h4>
          <p>Thành viên cần cung cấp thông tin chính xác khi đăng ký, bao gồm tên, địa chỉ email, số điện thoại và các thông tin cần thiết khác. Việc cung cấp thông tin trung thực giúp hệ thống đảm bảo quyền lợi và giao dịch thuận lợi.</p>

          <ul>
            <li>Bảo mật thông tin đăng nhập và không chia sẻ tài khoản cho người khác sử dụng.</li>
            <li>Tuân thủ các điều khoản và quy định khi sử dụng các quyền lợi thành viên.</li>
            <li>Liên hệ ngay với Beautiful Love nếu phát hiện bất kỳ hoạt động bất thường hoặc vi phạm nào liên quan đến tài khoản.</li>
          </ul>

          <h4>Quy định tham gia:</h4>
          <p>Mọi khách hàng đủ 18 tuổi hoặc đã được sự đồng ý của phụ huynh/tổ chức sẽ đủ điều kiện đăng ký thành viên. Beautiful Love có quyền từ chối hoặc hủy tư cách thành viên nếu phát hiện thông tin gian lận, hành vi vi phạm hoặc sử dụng dịch vụ sai mục đích.</p>

          <p>Chính sách thành viên được thiết lập nhằm bảo vệ quyền lợi và trải nghiệm của khách hàng, đồng thời tạo môi trường mua sắm minh bạch và tin cậy. Chúng tôi khuyến nghị thành viên đọc kỹ các quy định và thường xuyên cập nhật thông tin để đảm bảo không bỏ lỡ bất kỳ quyền lợi nào.</p>

          <p>Beautiful Love cam kết xây dựng cộng đồng thành viên thân thiện, năng động, nơi mỗi cá nhân đều được trân trọng, hỗ trợ và hưởng các quyền lợi xứng đáng với sự tin tưởng và tham gia tích cực của mình.</p>
        `
      },

      'service-7': {
        title: 'Hỏi & Đáp (Q&A)',
        bio: `
          <h4>Hỏi & Đáp về Beautiful Love:</h4>

          <p><strong>Q1: Beautiful Love là gì?</strong></p>
          <p>A1: Beautiful Love là sản phẩm dự án nhóm, minh họa ý tưởng và chức năng web, đồng thời thể hiện sự hợp tác, sáng tạo của các thành viên.</p>

          <p><strong>Q2: Tôi có thể đăng ký thành viên như thế nào?</strong></p>
          <p>A2: Khách hàng chỉ cần điền đầy đủ thông tin vào form đăng ký, xác nhận email và đồng ý với điều khoản sử dụng để trở thành thành viên.</p>

          <p><strong>Q3: Chính sách bảo hành áp dụng ra sao?</strong></p>
          <p>A3: Sản phẩm được bảo hành theo quy định của Beautiful Love. Khách hàng cần giữ hóa đơn và phiếu bảo hành để thực hiện các quyền lợi liên quan.</p>

          <p><strong>Q4: Tôi có thể liên hệ CSKH bằng cách nào?</strong></p>
          <p>A4: Khách hàng có thể liên hệ qua email, số điện thoại, hoặc chat trực tiếp trên website để được hỗ trợ nhanh chóng.</p>

          <p><strong>Q5: Thông tin của tôi có được bảo mật không?</strong></p>
          <p>A5: Mọi thông tin cá nhân của khách hàng được bảo mật tuyệt đối, không chia sẻ với bên thứ ba nếu không có sự đồng ý.</p>
        `
      }

    };



    // TEAM modal
  const modalOverlayTeamEl = document.getElementById('modalOverlayTeam');
  const expandedImageTeamEl = document.getElementById('expandedImageTeam');
  const expandedNameTeamEl = document.getElementById('expandedNameTeam');
  const expandedTitleTeamEl = document.getElementById('expandedTitleTeam');
  const expandedBioTeamEl = document.getElementById('expandedBioTeam');

  Object.keys(teamData).forEach(id => {
    const btn = document.getElementById(id);
    btn.addEventListener('click', () => {
      const data = teamData[id];
      expandedImageTeamEl.src = data.image;
      expandedImageTeamEl.alt = data.name;
      expandedNameTeamEl.textContent = data.name;
      expandedTitleTeamEl.textContent = data.title;
      expandedBioTeamEl.textContent = data.bio;
      modalOverlayTeamEl.style.display = 'flex';
      modalOverlayTeamEl.setAttribute('aria-hidden', 'false');
    });
  });

  // Đóng modal TEAM
  modalOverlayTeamEl.querySelector('.close-btn').addEventListener('click', () => {
    modalOverlayTeamEl.style.display = 'none';
    modalOverlayTeamEl.setAttribute('aria-hidden', 'true');
  });

  // Đóng modal khi click ngoài
  modalOverlayTeamEl.addEventListener('click', e => {
    if(e.target === modalOverlayTeamEl){
      modalOverlayTeamEl.style.display = 'none';
      modalOverlayTeamEl.setAttribute('aria-hidden', 'true');
    }
  });

  // SERVICE modal
  const modalOverlayServiceEl = document.getElementById('modalOverlayService');
  const expandedNameServiceEl = document.getElementById('expandedNameService');
  const expandedTitleServiceEl = document.getElementById('expandedTitleService');
  const expandedBioServiceEl = document.getElementById('expandedBioService');

  Object.keys(serviceData).forEach(id => {
    const btn = document.getElementById(id);
    btn.addEventListener('click', () => {
      const data = serviceData[id];
      expandedNameServiceEl.textContent = data.title;
      expandedTitleServiceEl.textContent = data.subtitle || '';
      expandedBioServiceEl.innerHTML = data.bio;
      modalOverlayServiceEl.style.display = 'flex';
      modalOverlayServiceEl.setAttribute('aria-hidden', 'false');
    });
  });

  // Đóng modal SERVICE
  modalOverlayServiceEl.querySelector('.close-btn').addEventListener('click', () => {
    modalOverlayServiceEl.style.display = 'none';
    modalOverlayServiceEl.setAttribute('aria-hidden', 'true');
  });

  // Đóng modal khi click ngoài
  modalOverlayServiceEl.addEventListener('click', e => {
    if(e.target === modalOverlayServiceEl){
      modalOverlayServiceEl.style.display = 'none';
      modalOverlayServiceEl.setAttribute('aria-hidden', 'true');
    }
  });
  </script>
</body>
</html>
