@extends('layouts.app')

@section('content')
<div class="container">
    <h1>รายการคืนสินค้า</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('returns.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="ค้นหารหัสการคืนสินค้า หรือ ร้านค้า" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </div>
    </form>

    <a href="{{ route('returns.create') }}" class="btn btn-primary mb-3">เพิ่มการคืนสินค้า</a>

    <table class="table">
        <thead>
            <tr>
                <th>รหัสการคืนสินค้า</th>
                <th>รหัสการขาย</th>
                <th>วันที่ทำการคืนสินค้า</th>
                <th>ร้านค้า</th>
                <th>การกระทำ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returns as $return)
                <tr>
                    <td>{{ $return->id }}</td>
                    <td>{{ $return->sale_id }}</td>
                    <td>{{ $return->return_date }}</td>
                    <td>{{ $return->shop->name }}</td>
                    <td>
                        <a href="{{ route('returns.show', $return->id) }}" class="btn btn-info">ดู</a>
                        <a href="{{ route('returns.edit', $return->id) }}" class="btn btn-warning">แก้ไข</a>
                        <form action="{{ route('returns.destroy', $return->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบ?')">ลบ</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $returns->links() }} <!-- สำหรับการแสดงผล pagination -->
</div>
@endsection
