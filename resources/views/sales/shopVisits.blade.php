@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ประวัติการเยี่ยมร้านค้า</h1>

    <!-- ฟอร์มค้นหา -->
    <form action="{{ route('shopVisits333') }}" method="GET">
        <div class="form-group">
            <label for="search">ค้นหาร้านค้า:</label>
            <input type="text" name="search" class="form-control" placeholder="ค้นหาตามชื่อร้านค้า">
        </div>
        <button type="submit" class="btn btn-primary">ค้นหา</button>
    </form>

    <!-- แสดงผลลัพธ์การเยี่ยมร้านค้า -->
    <table class="table mt-4">
        <thead>
            <tr>
                <th>ชื่อร้านค้า</th>
                <th>วันที่เยี่ยม</th>
                <th>พนักงาน</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shopVisits as $visit)
                <tr>
                    <td>{{ $visit->shop->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') }}</td>
                    <td>{{ $visit->employee->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $visit->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
