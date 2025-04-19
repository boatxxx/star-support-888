@extends('layouts.app')

@section('content')
<div class="container">
    <h1>รายการโกดังสินค้า</h1>
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary">เพิ่มโกดังสินค้า</a>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table mt-2">
        <thead>
            <tr>
                <th>ชื่อ</th>
                <th>ที่ตั้ง</th>
                <th>ความจุ</th>
                <th>การดำเนินการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warehouses as $warehouse)
            <tr>
                <td>{{ $warehouse->name }}</td>
                <td>{{ $warehouse->location }}</td>
                <td>{{ $warehouse->capacity }}</td>
                <td>
                    <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                    <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
