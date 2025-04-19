<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
    body {
            background-color: #f4f4f4;

            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            max-width: 500px;
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
        .container form label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .container form input, .container form textarea, .container form button {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .container form textarea {
            resize: vertical;
        }
        .container form button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .container form button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
        select {
    display: inline-block;
    width: 100%; /* ให้ dropdown กว้างเต็มที่ */
    padding: 10px; /* ระยะห่างภายใน dropdown */
    border: 1px solid #ddd; /* สีขอบของ dropdown */
    border-radius: 4px; /* มุมโค้งมนของ dropdown */
    background-color: #fff; /* สีพื้นหลังของ dropdown */
    font-size: 16px; /* ขนาดข้อความ */
    color: #333; /* สีข้อความ */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* เงาเบาๆ ให้กับ dropdown */
    transition: border-color 0.3s ease, box-shadow 0.3s ease; /* เอฟเฟกต์การเปลี่ยนสีขอบและเงา */
}

select:focus {
    border-color: #007bff; /* เปลี่ยนสีขอบเมื่อ dropdown ถูกเลือก */
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2); /* เปลี่ยนเงาเมื่อ dropdown ถูกเลือก */
    outline: none; /* ไม่มีกรอบรอบ dropdown เมื่อถูกเลือก */
}

option {
    padding: 10px; /* ระยะห่างภายในตัวเลือก */
    background-color: #fff; /* สีพื้นหลังของตัวเลือก */
    color: #333; /* สีข้อความของตัวเลือก */
}

option:hover {
    background-color: #f1f1f1; /* สีพื้นหลังของตัวเลือกเมื่อวางเมาส์ */
}
    </style>
</head>   @extends('layouts.app')
<body>


    @section('content')
    <div class="container">
        <h1>เพิ่มสินค้า</h1>
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <label for="Warehouse">คลังสินค้า:</label>
            <select id="Warehouse" name="Warehouse" required>
                <option value="" disabled selected>เลือกคลังสินค้า</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
            <div class="form-group">
                <label for="category_id">หมวดหมู่</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">-- เลือกหมวดหมู่ --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <label for="product_id1">รหัสสินค้า:</label>
            <input type="text" id="product_id1" name="product_id1" maxlength="20">

            <label for="name">ชื่อสินค้า:</label>
            <input type="text" id="name" name="name" maxlength="20" required>


            <label for="description">คำอธิบายสินค้า:</label>
            <textarea id="description" name="description" maxlength="255" required></textarea>

            <label for="price">ราคา:</label>
            <input type="text" id="price" name="price" required>

            <label for="stock">จำนวนสินค้าคงเหลือ:</label>
            <input type="number" id="stock" name="stock" required>

            <label for="expiration_date">วันหมดอายุของสินค้า:</label>
            <input type="date" id="expiration_date" name="expiration_date" required>

            <button type="submit" class="btn">บันทึก</button>
        </form>
    </div>
    @endsection

    @section('content')
    @endsection
</body>
</html>
