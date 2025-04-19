<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มผู้ใช้</title>
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
        .container form {
            display: flex;
            flex-direction: column;
        }
        .container input, .container select, .container button {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .container button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .container button:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="page-inner">
        <h1>เพิ่มผู้ใช้</h1>
        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="name" placeholder="ชื่อ" value="{{ old('name') }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror

            <input type="email" name="email" placeholder="อีเมล" value="{{ old('email') }}" required>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <select name="role_id" required>
                <option value="" disabled selected>เลือกบทบาท</option>
                <option value="1">ผู้ดูแลระบบ</option>
                <option value="2">พนักงานทั่วไป</option>
            </select>
            @error('role_id')
                <div class="error">{{ $message }}</div>
            @enderror

            <input type="text" name="status" placeholder="สถานะ" value="{{ old('status') }}" required>
            @error('status')
                <div class="error">{{ $message }}</div>
            @enderror

            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <input type="file" name="img_user">
            @error('img_user')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">เพิ่มผู้ใช้</button>
        </form>
    </div>

</div>

</body>
</html>
