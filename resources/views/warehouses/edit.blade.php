@extends('layouts.app')

@section('content')
<div class="container">
    <h1>แก้ไขโกดังสินค้า</h1>
    <form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">ชื่อโกดัง:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $warehouse->name }}" required>
        </div>

        <div class="form-group">
            <label for="location">ที่ตั้ง:</label>
            <input type="text" id="location" name="location" class="form-control" value="{{ $warehouse->location }}" required>
        </div>



        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>
</div>
@endsection
