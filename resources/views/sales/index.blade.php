@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">ประวัติการขายสินค้า</h1>
    <h1 class="text-center mb-4">ประวัติการขายสินค้า</h1>

    <!-- ฟอร์มค้นหาสินค้า -->
    <form method="GET" action="{{ route('sales.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อสินค้า/รหัสสินค้า" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </div>
    </form>

    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคารวม</th>
                <th>วันที่ขาย</th>
                <th>ร้านค้า</th>
                <th>พนักงานขาย</th>
                <th>การกระทำ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($query as $sale)
                <tr>
                    <td>{{ $sale->product->product_id ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $sale->product->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ number_format($sale->total_price, 2) }} บาท</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                    <td>{{ $sale->shop->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $sale->user->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>
                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบประวัติการขายนี้?')">ลบ</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">ไม่พบข้อมูลการขาย</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
