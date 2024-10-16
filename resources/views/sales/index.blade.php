@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ประวัติการขายสินค้า</h1>

    <!-- ฟอร์มค้นหาสินค้า -->
    <form method="GET" action="{{ route('sales.index') }}">
        <input type="text" name="search" placeholder="ค้นหาชื่อสินค้า/รหัสสินค้า" value="{{ request('search') }}">
        <button type="submit">ค้นหา</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคารวม</th>
                <th>วันที่ขาย</th>
                <th>ร้านค้า</th>
                <th>พนักงานขาย</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($query as $sale)
                <tr>
                    <td>{{ $sale->product->product_id ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $sale->product->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $sale->total_price }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                    <td>{{ $sale->shop->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $sale->user->name ?? 'ไม่พบข้อมูล' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">ไม่พบข้อมูลการขาย</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
