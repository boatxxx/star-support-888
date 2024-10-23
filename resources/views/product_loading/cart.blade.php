@extends('layouts.app1')

@section('content')
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตระก้าสินค้าของฉัน</title>
    <style>
        body {
            font-family: 'TH Sarabun New', Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }
        .btn-load-back {
            background-color: #ff5722;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin: 10px 0;
        }
        .btn-load-back:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

    <h1>ตระก้าสินค้าของฉัน</h1>

    <table>
        <thead>
            <tr>
                <th>ชื่อสินค้า</th>
                <th>จำนวนรวมในตระก้า</th>
                <th>จำนวนที่ขายไปแล้ว</th>
                <th>จำนวนที่เหลือ</th>
                <th>การกระทำ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($finalItems as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['total_quantity'] }}</td>
                    <td>{{ $item['total_sold'] }}</td>
                    <td>{{ $item['remaining_quantity'] }}</td>
                    <td>
                        <form action="{{ route('loadBackToWarehouse', $item['product_id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-load-back">โหลดกลับคลัง</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>เข้าสู่ระบบโดย: {{ $user->name }}</p>
    </div>

</body>
</html>
@endsection
