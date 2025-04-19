<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บริษัท สตาร์ซัพพอร์ต 999 (ประเทศไทย) จำกัด</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #ffffff;
        }

        header {
            background-color: #2f8ce2;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .logo {
            width: 150px;
        }

        nav {
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #2f8ce2;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .content {
            padding: 20px;
            background-color: #f0f8ff;
        }

        .section {
            margin-bottom: 40px;
        }

        footer {
            background-color: #2f8ce2;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        @media (max-width: 600px) {
            .logo {
                width: 100px;
            }

            nav a {
                display: block;
                margin: 10px 0;
            }
        }

        .carousel {
            position: relative;
            max-width: 100%;
            margin: auto;
            overflow: hidden;
        }

        .carousel img {
            width: 100%;
            height: auto;
            display: block;
        }

        .carousel .prev, .carousel .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            color: #ffffff;
            font-weight: bold;
            font-size: 18px;
            transition: background-color 0.3s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        .carousel .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        .carousel .prev:hover, .carousel .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .btn-login {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header id="header">
        <img src="path-to-logo.jpg" alt="บริษัท สตาร์ซัพพอร์ต 999 (ประเทศไทย) จำกัด" class="logo">
    </header>
    <nav>
        <a href="#home">หน้าแรก</a>
        <a href="#services">บริการของเรา</a>
        <a href="https://www.facebook.com/yourfacebookpage" target="_blank">รับสมัครงาน</a>
        <a href="#location">ที่อยู่และติดต่อเรา</a>
        <a href="#contact">ติดต่อเรา</a>
    </nav>
    <div class="content">
        <div id="home" class="section">
            <div class="login-container">
                <a href="{{ route('login1') }}" class="btn-login">เข้าสู่ระบบ</a>
            </div>

            <h2>หน้าแรก</h2>
            <p>ยินดีต้อนรับสู่เว็บไซต์ของเรา!</p>
        </div>
        <div id="services" class="section">
            <h2>บริการของเรา</h2>
            <div class="carousel">
                <img class="mySlides" src="image1.jpg" alt="ภาพที่ 1">
                <img class="mySlides" src="image2.jpg" alt="ภาพที่ 2">
                <img class="mySlides" src="image3.jpg" alt="ภาพที่ 3">

                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>

            <p>เราให้บริการต่างๆ ตามความต้องการของลูกค้า</p>
        </div>
        <div id="location" class="section">
            <h2>ที่อยู่และติดต่อเรา</h2>
            <p>ที่อยู่: 1234 ถนนบางบอน กรุงเทพมหานคร 10150</p>
            <p>โทรศัพท์: 02-123-4567</p>
            <p>อีเมล: info@example.com</p>
        </div>
    </div>
    <footer>
        <p>&copy; 2023 บริษัท สตาร์ซัพพอร์ต 999 (ประเทศไทย) จำกัด</p>
    </footer>

    <script>
        document.querySelectorAll('nav a').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                if (this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) { slideIndex = 1; }
            slides[slideIndex - 1].style.display = "block";
            setTimeout(showSlides, 2000); // Change image every 2 seconds
        }

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }
    </script>
</body>
</html>
