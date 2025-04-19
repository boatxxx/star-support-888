@extends('layouts.app1')

@section('content')
    <div class="container">
        <h1>ข้อมูลการเยี่ยมร้านค้าของคุณ</h1>

        <a href="{{ route('shop_visits.create005') }}" class="btn btn-primary mb-3">เพิ่มการเยี่ยมร้านค้า</a>

        @if($shopVisits->isEmpty())
            <p>ยังไม่มีข้อมูลการเยี่ยมร้านค้า</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ชื่อร้าน</th>
                        <th>วันที่เยี่ยม</th>
                        <th>พนักงาน</th>
                        <th>หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shopVisits as $visit)
                        <tr>
                            <td>{{ $visit->shop->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') }}</td>
                            <td>{{ $visit->employee->name }}</td>
                            <td>{{ $visit->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
