<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จองสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<style>
    /* app.css */

body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 16px;
    margin-bottom: 8px;
    color: #555;
}

select, input[type="number"], input[type="date"] {
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

button {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    color: #fff;
    background-color: #007bff;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}

    </style>
<body>
    <!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>จองสินค้า</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>

    @extends('layouts.app1')
    @section('content')
    <div class="container">
        <h1>จองสินค้า</h1>
        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf
            <div id="reservation-items">
                <div class="reservation-item">
                    <label for="product_id">เลือกสินค้า:</label>
                    <select name="product_id[]" required>
                        <option value="" disabled selected>เลือกสินค้า</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>

                    <label for="quantity">จำนวน:</label>
                    <input type="number" name="quantity[]" min="1" required>

                    <label for="reservation_date">วันที่จอง:</label>
                    <input type="date" name="reservation_date[]" required>
                </div>
            </div>

            <button type="button" id="add-item" class="btn btn-secondary">เพิ่มรายการ</button>
            <button type="submit" class="btn btn-warning">จองสินค้า</button>
        </form>
    </div>

    <script>
        document.getElementById('add-item').addEventListener('click', function() {
            var container = document.getElementById('reservation-items');
            var item = document.createElement('div');
            item.className = 'reservation-item';
            item.innerHTML = `
                <label for="product_id">เลือกสินค้า:</label>
                <select name="product_id[]" required>
                    <option value="" disabled selected>เลือกสินค้า</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>

                <label for="quantity">จำนวน:</label>
                <input type="number" name="quantity[]" min="1" required>

                <label for="reservation_date">วันที่จอง:</label>
                <input type="date" name="reservation_date[]" required>
                <button type="button" class="remove-item btn btn-danger">ลบ</button>
                <hr>
            `;
            container.appendChild(item);

            // Add event listener to remove button
            item.querySelector('.remove-item').addEventListener('click', function() {
                container.removeChild(item);
            });
        });
    </script>

    @endsection

    </body>
    </html>

