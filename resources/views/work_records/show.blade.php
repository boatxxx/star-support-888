@extends('layouts.app')

@section('content')
<div class="container">
    <h1>รายละเอียดออเดอร์ #{{ $workRecord->id }}</h1>

    <div class="card">
        <div class="card-header">
            ข้อมูลออเดอร์
        </div>
        <div class="card-body">
            <p><strong>วันที่ทำการออเดอร์:</strong> {{ $workRecord->order_date }}</p>
            <p><strong>คำอธิบายเพิ่มเติม:</strong> {{ $workRecord->description }}</p>
            <p><strong>ร้านค้า:</strong> {{ $workRecord->shop->name }}</p>
            <p><strong>ยูเซอร์:</strong> {{ $workRecord->user->name }}</p>
            <p><strong>สถานะ:</strong> {{ ucfirst($workRecord->status) }}</p>

            <!-- Display Product Items -->
            <h4>รายการสินค้า:</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>สินค้า</th>
                        <th>จำนวน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($workRecord->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td> <!-- Use `name` instead of `product_id` to show the product name -->
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit and Delete Buttons -->
    <div class="mt-4">
        <a href="{{ route('work_records.edit', $workRecord->id) }}" class="btn btn-secondary">แก้ไขออเดอร์</a>

        <form action="{{ route('work_records.destroy', $workRecord->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">ลบออเดอร์</button>
        </form>

        <a href="{{ route('work_records.index') }}" class="btn btn-secondary">กลับไปที่รายการออเดอร์</a>
    </div>
</div>
@endsection
