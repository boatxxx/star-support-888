@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>ตารางออเดอร์</h1>
        <div class="mb-3">
            <a href="{{ route('work_records.create') }}" class="btn btn-primary">สร้างออเดอร์</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>

                    <th>Order </th>
                    <th>หมายเหตุ</th>
                    <th>ร้านค้า</th>
                    <th>เซลล์ขายของ</th>
                    <th>สถานะ</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workRecords as $workRecord)
                    <tr>
                        <td>{{ $workRecord->id }}</td>

                        @php
                            $formattedDate = \Carbon\Carbon::parse($workRecord->order_date)->format('Y-m-d');
                        @endphp
                        <td>{{ $formattedDate }}</td>
                        <td>{{ $workRecord->description }}</td>
                        <td>{{ $workRecord->shop_id }}</td>
                        <td>{{ $workRecord->user_id }}</td>
                        <td>{{ $workRecord->status }}</td>
                        <td>
                            <a href="{{ route('work_records.show', $workRecord->id) }}" class="btn btn-info btn-sm">ดู</a>
                            <a href="{{ route('work_records.edit', $workRecord->id) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                            <form action="{{ route('work_records.destroy', $workRecord->id) }}" method="POST" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">ลบออเดอร์</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
