<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Header */
        .header {
            position: sticky;
            top: 0;
            background-color: #053a72;
            color: white;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-wrap: wrap;
        }

        /* User Info */
        .header .user-info {
            display: flex;
            align-items: center;
        }

        .header .user-info img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 10px;
        }

        .header .user-info .user-details {
            display: flex;
            flex-direction: column;
        }

        .header .user-info .user-details span {
            font-size: 14px;
        }

        /* Menu */
        .header .menu {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .menu a {
            text-decoration: none;
            color: #063669;
            background-color: #fff;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        .menu a:hover {
            background-color: #e9ecef;
        }

        .menu a i {
            margin-right: 8px;
            font-size: 18px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header .menu {
                flex-direction: row; /* เปลี่ยนทิศทางเป็นแนวนอน */
                justify-content: space-between;
                width: 100%;
                margin-top: 10px;
            }

            .menu a {
                padding: 10px;
                font-size: 14px;
                flex: 1;
                text-align: center; /* จัดเมนูให้เป็นแนวกึ่งกลาง */
                margin: 0; /* ลบ margin-bottom เพื่อป้องกันบัง */
                border-radius: 0;
            }

            .menu a i {
                margin-right: 0; /* เอา margin-right ออกเพื่อให้ไอคอนอยู่กึ่งกลาง */
            }
        }

        @media (max-width: 576px) {
            .header .user-info img {
                width: 60px; /* ปรับขนาดภาพให้เล็กลง */
                height: 60px;
            }

            .menu a {
                padding: 8px;
                font-size: 12px;
            }

            .menu a i {
                font-size: 14px;
            }
        }
    </style>
    @yield('head')
</head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<body>
    <div class="header">
        <div class="user-info">
            <img src="{{ asset('storage/' . $user->img_user) }}" alt="User Image">
            <div class="user-details">
                <span>ชื่อผู้ใช้: {{ $user->name }}</span>
                <span>อีเมลผู้ใช้งาน: {{ $user->email }}</span>
            </div>
        </div>
        <div class="menu">
            <a href="{{ url('/user') }}"><i class="fas fa-home"></i> ย้อนกลับหน้าแรก</a>
            <a href=""><i class="fas fa-user-edit"></i> แก้ไขข้อมูลส่วนตัว</a>
            <a href=""><i class="fas fa-history"></i> ดูประวัติการขายสินค้า</a>
        </div>
    </div>
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>
