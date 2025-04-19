<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
@extends('layouts.app')
<body>
    @section('content')
    <div class="container">
        <h1>รายละเอียดสินค้า</h1>
        <p><strong>รหัสสินค้า:</strong> {{ $product->product_id }}</p>
        <p><strong>ชื่อสินค้า:</strong> {{ $product->name }}</p>
        <p><strong>คำอธิบายสินค้า:</strong> {{ $product->description }}</p>
        <p><strong>ราคา:</strong> {{ $product->price }}</p>
        <p><strong>จำนวนสินค้าคงเหลือ:</strong> {{ $product->stock }}</p>
        <p><strong>วันหมดอายุของสินค้า:</strong> {{ $product->expiration_date }}</p>

        <a href="{{ route('products.index') }}" class="btn">กลับไปที่รายชื่อสินค้า</a>
    </div>
    @endsection
</body>
</html>
