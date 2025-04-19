@extends('layouts.app')

@section('content')
<div class="container">
    <h1>รายละเอียดการคืนสินค้า</h1>

    <div class="card">
        <div class="card-header">
            รายละเอียดการคืนสินค้า
        </div>
        <div class="card-body">
            <p><strong>รหัสการคืนสินค้า:</strong> {{ $return->id }}</p>
            <p><strong>รหัสการขาย:</strong> {{ $return->sale_id }}</p>
            <p><strong>วันที่ทำการคืนสินค้า:</strong> {{ $return->return_date }}</p>
            <p><strong>สาเหตุการคืนสินค้า:</strong> {{ $return->reason }}</p>
            <p><strong>ร้านค้า:</strong> {{ $return->shop->name }}</p>
            <p><strong>คนที่คืนสินค้า:</strong> {{ $return->user->name }}</p>
            <p><strong>วันที่สร้าง:</strong> {{ $return->created_at }}</p>

            <!-- แสดงรายการสินค้าที่คืน -->
            <h5>รายการสินค้าที่คืน</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>สินค้า</th>
                        <th>จำนวน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($return->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('returns.index') }}" class="btn btn-primary mt-3">กลับไปที่รายการคืนสินค้า</a>
</div>
@endsection
