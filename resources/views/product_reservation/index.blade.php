@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ประวัติการจองสินค้า</h1>

    <!-- ฟอร์มค้นหา -->
    <form action="{{ route('reservations.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="ค้นหาจากชื่อสินค้า หรือชื่อร้านค้า" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">ค้นหา</button>
            </div>
        </div>
    </form>

    <!-- ตารางแสดงประวัติการจองสินค้า -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ชื่อร้านค้า</th>
                <th>จำนวน</th>
                <th>วันที่จอง</th>
                <th>ผู้จอง</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->product->product_id ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $reservation->product->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $reservation->shop->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $reservation->quantity }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</td>
                    <td>{{ $reservation->user->name ?? 'ไม่พบข้อมูล' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
