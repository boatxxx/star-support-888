@extends('layouts.app')

@section('content')
<div class="container">
    <h1>สรุปยอดขาย</h1>

    <!-- Form สำหรับกรองข้อมูล -->
    <form method="GET" action="{{ route('sales.summary') }}">
        <div class="row">
            <!-- เลือกวันที่เริ่มต้น -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="start_date">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
            </div>

            <!-- เลือกวันที่สิ้นสุด -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="end_date">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>

            <!-- เลือกร้านค้า -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="shop_id">ร้านค้า</label>
                    <select name="shop_id" class="form-control">
                        <option value="">เลือกทุกสาขา</option>
                        @foreach ($shops as $shop)
                            <option value="{{ $shop->shop_id }}">{{ $shop->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- เลือกพนักงานขาย -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="employee_id">พนักงานขาย</label>
                    <select name="employee_id" class="form-control">
                        <option value="">เลือกพนักงานทุกคน</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->user_id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- ระบุเปอร์เซ็นต์คอมมิชชั่น -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="commission">เปอร์เซ็นต์คอมมิชชั่น (%)</label>
                    <input type="number" name="commission" class="form-control" min="0" max="100" value="0" required>
                </div>
            </div>
        </div>

        <!-- ปุ่มค้นหา -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">ดูสรุปยอดขาย</button>
            <button type="button" class="btn btn-success" onclick="window.print()">พิมพ์เอกสาร</button>
        </div>
    </form>

    <!-- ตารางแสดงผลสรุปยอดขาย -->
    @if (isset($sales) && count($sales) > 0)
    <h2>สรุปยอดขาย</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>รหัสร้านค้า</th>
                <th>พนักงานขาย</th>
                <th>ยอดขายรวม</th>
                <th>คอมมิชชั่น (%)</th>
                <th>ยอดคอมมิชชั่น (บาท)</th>
                <th>ยอดสุทธิ (บาท)</th>
                <th>วันที่ขาย</th>
                <th>การดำเนินการ</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSales = 0;
                $totalCommission = 0;
            @endphp
            @foreach ($sales as $sale)
            @php
                $commissionAmount = $sale->total_price * ($commission / 100); // คำนวณคอมมิชชั่น
                $netTotal = $sale->total_price - $commissionAmount; // คำนวณยอดสุทธิ
                $totalSales += $sale->total_price; // สะสมยอดขายรวม
                $totalCommission += $commissionAmount; // สะสมค่าคอมมิชชั่นรวม
            @endphp
            <tr>
                <td>{{ $sale->shop->name ?? 'ไม่พบข้อมูล' }}</td>
                <td>{{ $sale->user->name ?? 'ไม่พบข้อมูล' }}</td>
                <td>{{ number_format($sale->total_price, 2) }}</td>
                <td>{{ $commission }}%</td>
                <td>{{ number_format($commissionAmount, 2) }}</td>
                <td>{{ number_format($netTotal, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                <td>
                    @if (!$sale->paymentCheck) <!-- ตรวจสอบว่ายังไม่มีการเช็ครับยอดเงิน -->
                        <form action="{{ route('sales.confirmPayment', $sale->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">เช็ครับเงิน</button>
                        </form>
                    @else
                        <span class="text-success">เช็ครับแล้ว</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">ยอดรวมทั้งหมด</th>
                <th>{{ number_format($totalSales, 2) }} บาท</th>
                <th></th>
                <th>{{ number_format($totalCommission, 2) }} บาท</th>
                <th>{{ number_format($totalSales - $totalCommission, 2) }} บาท</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    @else
    <p>ไม่พบข้อมูลยอดขายตามเงื่อนไขที่เลือก</p>
    @endif
</div>
@endsection
