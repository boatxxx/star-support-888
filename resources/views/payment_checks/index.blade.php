@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ประวัติยอดรับเงิน</h1>


    <h2 class="mt-5">รายการยอดรับเงิน</h2>
    <table class="table">
        <thead>
            <tr>
                <th>รหัสการขาย</th>
                <th>ผู้รับเงิน</th>
                <th>ยอดที่รับ</th>
                <th>วันที่รับเงิน</th>
                <th>ดำเนินการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentChecks as $paymentCheck)
                <tr>
                    <td>{{ $paymentCheck->sale_id }}</td>
                    <td>{{ $paymentCheck->user->name  }}</td> <!-- ชื่อคนรับเงิน -->
                    <td>{{ $paymentCheck->received_amount }}</td>
                    <td>{{ $paymentCheck->received_date }}</td>
                    <td>
                        <!-- ปุ่มสำหรับการดำเนินการต่างๆ ถ้าต้องการ -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
