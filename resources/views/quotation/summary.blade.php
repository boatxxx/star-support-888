@extends('layouts.app1')

@section('content')
<div class="container">
    <h1 class="text-center">สรุปใบเสนอราคา</h1>
    <h4 class="text-center">ร้านค้า: <strong>{{ $workRecord->shop->name }}</strong></h4>

    <h3 class="mt-4">รายละเอียดสินค้า</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>สินค้า</th>
                <th>จำนวน</th>
                <th>ราคาต่อหน่วย</th>
                <th>รวม</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp <!-- ตัวแปรสำหรับเก็บยอดรวม -->
            @foreach ($items as $item)
            @php
                $itemTotal = $item['quantity'] * $item['price']; // คำนวณยอดรวมของรายการ
                $totalAmount += $itemTotal; // เพิ่มยอดรวมไปยังยอดรวมทั้งหมด
            @endphp
            <tr>
                <td>{{ $products[$item['product_id']]->name ?? 'ไม่พบชื่อสินค้า' }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ number_format($item['price'], 2) }} บาท</td>
                <td>{{ number_format($itemTotal, 2) }} บาท</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="mt-4">ยอดรวมทั้งหมด: <strong>{{ number_format($totalAmount, 2) }} บาท</strong></h4>

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <input type="hidden" name="work_record_id" value="{{ $workRecord->id }}"> <!-- ส่ง ID ของ WorkRecord -->
        <input type="hidden" name="shop_id" value="{{ $workRecord->shop->shop_id }}"> <!-- เพิ่ม shop_id -->
        <input type="hidden" name="items" value="{{ json_encode($items) }}">
        <div class="mt-4">
            <button type="submit" class="btn btn-success">บันทึกข้อมูลขายสินค้า</button>
        </div>
    </form>

    <div class="mt-4">
        <button class="btn btn-primary" onclick="window.print();">พิมพ์ใบเสนอราคา</button>
    </div>
</div>
@endsection
