<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้</title>
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
        <h1>แก้ไขข้อมูลผู้ใช้</h1>

        <form action="{{ route('users.update', $employee->user_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">ชื่อ</label>
                <input type="text" id="name" name="name" value="{{ old('name', $employee->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">อีเมล</label>
                <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
            </div>
            <div class="form-group">
                <label for="user_id">รหัสพนักงาน</label>
                <input type="text" id="user_id" name="user_id" value="{{ old('name', $employee->user_id) }}" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสพนักงาน</label>
                <input type="text" id="password" name="password" value="{{ old('name', $employee->password) }}" required>
            </div>
            <div class="form-group">
                <label for="status">สถานะ</label>
                <select id="status" name="status" required>
                    <option value="ออนไลน์" {{ $employee->status == 'ออนไลน์' ? 'selected' : '' }}>ออนไลน์</option>
                    <option value="ออฟไลน์" {{ $employee->status == 'ออฟไลน์' ? 'selected' : '' }}>ออฟไลน์</option>
                </select>
            </div>

            <div class="form-group">
                <label for="role">บทบาท</label>
                <select id="role" name="role_id" required>
                    <option value="1" {{ $employee->role_id == 1 ? 'selected' : '' }}>ผู้ดูแลระบบ</option>
                    <option value="2" {{ $employee->role_id == 2 ? 'selected' : '' }}>พนักงานทั่วไป</option>
                </select>

            </div>
            <div class="form-group">
                <label for="img_user">รูปโปรไฟล์</label>
                <input type="file" name="img_user" class="form-control">



            <div class="form-group">
                <input type="submit" value="บันทึกการเปลี่ยนแปลง">
            </div>
        </form>

        <a href="{{ route('employees.index') }}" class="btn-secondary">กลับไปที่รายชื่อผู้ใช้</a>
    </div>
    @endsection
</body>
</html>
