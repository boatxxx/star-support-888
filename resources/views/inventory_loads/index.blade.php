@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <h1>ประวัติการโหลดสินค้ากลับคลัง</h1>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>รหัสการโหลด</th>
                <th>ชื่อสินค้า</th>
                <th>รหัสออเดอร์</th>
                <th>จำนวน</th>
                <th>ผู้โหลด</th>
                <th>เวลาที่โหลด</th>
                <th>จัดการ</th> <!-- เพิ่มคอลัมน์สำหรับการจัดการ -->
            </tr>
        </thead>
        <tbody>
            @if($inventoryLoads->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">ไม่พบข้อมูล</td>
                </tr>
            @else
                @foreach ($inventoryLoads as $load)
                    <tr>
                        <td>{{ $load->id }}</td>
                        <td>{{ $load->product->name ?? 'ไม่พบชื่อสินค้า' }}</td>
                        <td>{{ $load->workRecord->id ?? 'ไม่พบออเดอร์' }}</td>
                        <td>{{ $load->quantity }}</td>
                        <td>{{ $load->user->name ?? 'ไม่พบผู้โหลด' }}</td>
                        <td>{{ $load->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('inventory-loads.destroy', $load->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจว่าต้องการลบประวัติการโหลดนี้?')">ลบ</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection
