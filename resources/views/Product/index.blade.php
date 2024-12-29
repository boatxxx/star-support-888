<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* ปรับสไตล์ของปุ่มและตาราง */
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn:active {
            background-color: #003d7a;
            transform: scale(0.98);
        }

        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            th, td {
                display: block;
                text-align: right;
            }
            th::before, td::before {
                content: attr(data-label);
                display: inline-block;
                width: 100px;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>
@extends('layouts.app')
<body>
    @section('content')
    <div class="container">
        <h1>ข้อมูลสินค้า</h1>
        <a href="{{ route('products.create') }}" class="btn">เพิ่มสินค้า</a>
        <table>
            <thead>
                <tr>
                    <th>หมวดหมู่สินค้า</th>
                    <th>รหัสสินค้า</th>
                    <th>ชื่อสินค้า</th>
                    <th>คำอธิบายสินค้า</th>
                    <th>ราคา</th>
                    <th>จำนวนสินค้าคงเหลือ</th>
                    <th>สินค้าขายไปแล้ว</th> <!-- เพิ่มคอลัมน์สำหรับสินค้าที่ขาย -->
                    <th>วันหมดอายุของสินค้า</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @php
                        // ดึงจำนวนสินค้าที่ขายไปแล้ว
                        $totalSold = optional($sales->where('product_id', $product->product_id)->first())->total_sold ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $product->category->name ?? 'ไม่มีหมวดหมู่' }}</td>
                        <td>{{ $product->product_id1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ number_format($product->price, 2) }}</td> <!-- รูปแบบราคาที่ดีกว่า -->
                        <td>{{ $product->stock }}</td>
                        <td>{{ $totalSold }}</td> <!-- แสดงจำนวนที่ขาย -->
                        <td>{{ $product->expiration_date }}</td>
                        <td>
                            <a href="{{ route('products.show', $product->product_id) }}" class="btn">ดูรายละเอียด</a>
                            <a href="{{ route('products.edit', $product->product_id) }}" class="btn">แก้ไข</a>
                            <form action="{{ route('products.destroy', $product->product_id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?')">ลบ</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endsection
</body>
</html>
