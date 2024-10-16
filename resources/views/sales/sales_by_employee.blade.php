@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ยอดขายตามพนักงาน</h1>

    <form method="GET" action="{{ route('sales.by_employee') }}">
        <div class="form-group">
            <label for="start_date">วันที่เริ่มต้น</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end_date">วันที่สิ้นสุด</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">ค้นหา</button>
    </form>

    <h2>ยอดขายทั้งหมด</h2>
    <table class="table">
        <thead>
            <tr>
                <th>พนักงานขาย</th>
                <th>ราคารวม</th>
                <th>วันที่ขายล่าสุด</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->user->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ number_format($sale->total_price, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
