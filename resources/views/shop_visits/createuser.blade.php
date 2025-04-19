@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($shopVisit) ? 'แก้ไข' : 'เพิ่ม' }} การเยี่ยมร้านค้า</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($shopVisit) ? route('shop_visits.update', $shopVisit->id) : route('shop_visits.store') }}" method="POST">
        @csrf
        @if (isset($shopVisit))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="shop_id">ร้านค้า</label>
            <select class="form-control" id="shop_id" name="shop_id" required>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->shop_id }}" {{ (isset($shopVisit) && $shopVisit->shop_id == $shop->id) ? 'selected' : '' }}>
                        {{ $shop->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="visit_date">วันในสัปดาห์</label>
            <select class="form-control" id="visit_date" name="visit_date" required>
                <option value="" disabled selected>เลือกวันในสัปดาห์</option>
                <option value="Monday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Monday') ? 'selected' : '' }}>วันจันทร์</option>
                <option value="Tuesday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Tuesday') ? 'selected' : '' }}>วันอังคาร</option>
                <option value="Wednesday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Wednesday') ? 'selected' : '' }}>วันพุธ</option>
                <option value="Thursday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Thursday') ? 'selected' : '' }}>วันพฤหัสบดี</option>
                <option value="Friday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Friday') ? 'selected' : '' }}>วันศุกร์</option>
                <option value="Saturday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Saturday') ? 'selected' : '' }}>วันเสาร์</option>
                <option value="Sunday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Sunday') ? 'selected' : '' }}>วันอาทิตย์</option>
            </select>
        </div>
        <div class="form-group">
            <label for="employee_id">พนักงาน</label>
            <select class="form-control" id="employee_id" name="employee_id" required>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->user_id }}" {{ (isset($shopVisit) && $shopVisit->employee_id == $employee->id) ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="notes">หมายเหตุ</label>
            <textarea class="form-control" id="notes" name="notes" rows="3">{{ isset($shopVisit) ? $shopVisit->notes : old('notes') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">{{ isset($shopVisit) ? 'อัปเดต' : 'บันทึก' }}</button>
    </form>
</div>
@endsection
