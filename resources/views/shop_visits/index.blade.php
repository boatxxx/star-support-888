@extends('layouts.app')

@section('content')
<div class="container">
    <h1>รายการการเยี่ยมร้านค้า</h1>

    <a href="{{ route('shopVisit1.createuser') }}" class="btn btn-primary mb-3">เพิ่มการเยี่ยมร้านค้า</a>

    @if ($visits->isEmpty())
        <p>ไม่มีข้อมูลการเยี่ยมร้านค้า</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>รหัสการเยี่ยม</th>
                    <th>ร้านค้า</th>
                    <th>วันที่เยี่ยม</th>
                    <th>พนักงาน</th>
                    <th>หมายเหตุ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($visits as $visit)
                    <tr>
                        <td>{{ $visit->id }}</td>
                        <td>{{ $visit->shop ? $visit->shop->name : 'ไม่มีข้อมูลร้าน' }}</td>
                        <td>{{ $visit->visit_date }}</td>
                        <td>{{ $visit->employee->name }}</td>
                        <td>{{ $visit->notes }}</td>
                        <td>
                            <a href="{{ route('shop_visits.edit', $visit->id) }}" class="btn btn-warning">แก้ไข</a>
                            <form action="{{ route('shop_visits.destroy', $visit->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">ลบ</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
