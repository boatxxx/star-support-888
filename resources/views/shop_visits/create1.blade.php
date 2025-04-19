@extends('layouts.app1')

@section('content')
<div class="container">
    <h1>เพิ่มข้อมูลการเยี่ยมร้านค้า</h1>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <form action="{{ route('shop_visits.store1') }}" method="POST">
        @csrf
        <!-- ซ่อนค่า shop_id -->
        <input type="hidden" name="shop_id" value="{{ $shopId }}">

        <!-- แสดงชื่อร้านค้า -->
        <div class="form-group">
            <label for="customer_name">ชื่อร้านค้า</label>
            <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ $shop->name }}" readonly>
        </div>

        <!-- ข้อมูลผู้ใช้งาน -->
        <div class="form-group">
            <label for="employee_id">ชื่อพนักงาน</label>
            <input type="text" name="employee_name" id="employee_name" class="form-control" value="{{ $user->name }}" readonly>
            <input type="hidden" name="employee_id" value="{{ $user->user_id }}">
        </div>

        <!-- หมายเหตุ -->
        <div class="form-group">
            <label for="notes">หมายเหตุ</label>
            <select name="notes" id="notes" class="form-control">
                <option value="สินค้าเพียงพอ">สินค้าเพียงพอ</option>
                <option value="ร้านปิด">ร้านปิด</option>
                <option value="สินค้าขาดตลาด">สินค้าขาดตลาด</option>
                <option value="ลูกค้าไม่ยอมซื้อสินค้าเพิ่ม">ลูกค้าไม่ยอมซื้อสินค้าเพิ่ม</option>
            </select>
        </div>

        <!-- ปุ่มบันทึก -->
        <button type="submit" class="btn btn-primary">บันทึกการเยี่ยม</button>
    </form>
</div>
@endsection
