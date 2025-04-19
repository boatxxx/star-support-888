@extends('layouts.app')

@section('content')
    <h1>ใบเสร็จ</h1>
    <p>ผู้ขาย: {{ $user->name }}</p>
    <p>วันที่ขาย: {{ now()->format('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>ชื่อสินค้า</th>
                <th>จำนวน</th>
                <th>ราคา</th>
                <th>ราคารวม</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $products[$item['product_id']]->name ?? 'ไม่พบชื่อสินค้า' }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price'], 2) }}</td>
                    <td>{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>ยอดรวม: {{ number_format($total, 2) }}</h3>
@endsection
