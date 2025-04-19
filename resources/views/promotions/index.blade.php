
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรโมชั่น</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container my-4">
        <h1 class="mb-4">โปรโมชั่น</h1>
        <a href="{{ route('promotions.create') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> เพิ่มโปรโมชั่น</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>รหัสโปรโมชั่น</th>
                        <th>ชื่อโปรโมชั่น</th>
                        <th>รายละเอียด</th>
                        <th>วันที่เริ่ม</th>
                        <th>วันที่สิ้นสุด</th>
                        <th>ประเภท</th>
                        <th>สถานะ</th>
                        <th>การกระทำ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promotions as $promotion)
                        <tr>
                            <td>{{ $promotion->promotion_id }}</td>
                            <td>{{ $promotion->name }}</td>
                            <td>{{ $promotion->description }}</td>
                            <td>{{ $promotion->start_date->format('d/m/Y') }}</td>
                            <td>{{ $promotion->end_date->format('d/m/Y') }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $promotion->type)) }}</td>
                            <td>{{ ucfirst($promotion->status) }}</td>
                            <td>
                                <a href="{{ route('promotions.edit', $promotion->promotion_id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> แก้ไข</a>
                                <form action="{{ route('promotions.destroy', $promotion->promotion_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('ต้องการลบโปรโมชั่นนี้ใช่หรือไม่?')"><i class="fas fa-trash"></i> ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endsection
</body>
</html>
