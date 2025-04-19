<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดูข้อมูลผู้ใช้</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-color: #f4f4f4;
            align-items: center;

            margin: 0;
        }
        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            max-width: 100%;
        }
        .container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #333;
        }
        .container p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .container .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .container .btn:hover {
            background-color: #0056b3;
        }
        .container .profile-img {
            max-width: 100%;
            border-radius: 50%;
            margin-bottom: 10px;
        }
    </style>

</head>
<body>
    @extends('layouts.app')

    @section('content')
    <div class="container">
        <h1>ข้อมูลผู้ใช้</h1>

        <div class="profile-img">
            @if($employee->img_user)
                <img src="{{ asset('storage/' . $employee->img_user) }}" alt="Profile Image" class="profile-img">
            @else
                <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" class="profile-img">
            @endif
        </div>

        <p><strong>ชื่อ:</strong> {{ $employee->name }}</p>
        <p><strong>อีเมล:</strong> {{ $employee->email }}</p>
        <p><strong>สถานะ:</strong> {{ $employee->status }}</p>
        <p><strong>หรัสพนักงาน:</strong> {{ $employee->user_id }}</p>

        <p><strong>บทบาท:</strong>
            @if($employee->role_id == 1)
                ผู้ดูแลระบบ
            @elseif($employee->role_id == 2)
                พนักงานทั่วไป
            @else
                ไม่มีบทบาท
            @endif
        </p>

        <a href="{{ route('employees.index') }}" class="btn btn-secondary">กลับไปที่รายชื่อผู้ใช้</a>
    </div>
    @endsection

</body>
</html>
