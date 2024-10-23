@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ประวัติการขนสินค้าขึ้นตระก้า</h1>

    <form action="{{ route('product_loadings.search') }}" method="GET">
        <div class="form-group">
            <label for="search">ค้นหาสินค้า</label>
            <input type="text" class="form-control" id="search" name="search" placeholder="ค้นหาสินค้า...">
        </div>
        <button type="submit" class="btn btn-primary">ค้นหา</button>
    </form>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>รหัสการขนสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ออเดอร์</th>
                <th>จำนวน</th>
                <th>ผู้ขนย้าย</th>
                <th>เมนู</th>

            </tr>
        </thead>
        <tbody>
            @if($query->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">ไม่พบข้อมูล</td>
                </tr>
            @else
                @foreach ($query as $productLoading)
                    <tr>
                        <td>{{ $productLoading->id }}</td>
                        <td>{{ $productLoading->product->name ?? 'ไม่พบชื่อสินค้า' }}</td>
                        <td>{{ $productLoading->workRecord->id ?? 'ไม่พบออเดอร์' }}</td>
                        <td>{{ $productLoading->quantity }}</td>
                        <td>{{ $productLoading->user->name ?? 'ไม่พบผู้สร้าง' }}</td> <!-- แสดงชื่อผู้สร้าง -->
                        <td>
                            <form action="{{ route('product_loadings.destroy', $productLoading->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">ลบ</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection
