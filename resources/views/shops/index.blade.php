@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">ร้านค้าทั้งหมด</h1>

    <!-- ระบบค้นหา -->
    <form action="{{ route('shops.index') }}" method="GET" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อร้านค้า หรือที่อยู่" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- สถานะ --</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>แผนที่ลิ้ง</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>ละติจูด/ลองจิจูด</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="employee" class="form-select">
                    <option value="">-- ผู้รับผิดชอบ --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->user_id }}" {{ request('employee') == $employee->user_id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">ค้นหา</button>
            </div>
        </div>
    </form>

    <!-- แสดงผลการค้นหา -->
    @if(request('search') || request('status') || request('employee'))
        <div class="alert alert-info">
            ผลการค้นหาสำหรับ: "{{ request('search') }}"
            สถานะ: "{{ request('status') == '1' ? 'แผนที่ลิ้ง' : 'ละติจูด/ลองจิจูด' }}"
            ผู้รับผิดชอบ: "{{ $employees->firstWhere('user_id', request('employee'))->name ?? 'ทั้งหมด' }}"
        </div>
    @endif

    <!-- ปุ่มเพิ่มร้านค้า -->
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('shops.create') }}" class="btn btn-success">เพิ่มร้านค้าใหม่</a>
    </div>

    <!-- ตารางแสดงข้อมูล -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>รหัสร้านค้า</th>
                    <th>ชื่อร้านค้า</th>
                    <th>ที่อยู่</th>
                    <th>อำเภอ</th>
                    <th>แผนที่ Google</th>
                    <th>สถานะ</th>
                    <th>ละติจูด</th>
                    <th>ลองจิจูด</th>
                    <th>ผู้รับผิดชอบ</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shops as $shop)
                    <tr>
                        <td>{{ $shop->shop_id }}</td>
                        <td>{{ $shop->name }}</td>
                        <td>{{ $shop->address }}</td>
                        <td>{{ $shop->district }}</td>
                        <td>
                            @if($shop->link_google)
                                <a href="{{ $shop->link_google }}" target="_blank">ดูแผนที่</a>
                            @else
                                ไม่มีแผนที่
                            @endif
                        </td>
                        <td>
                            @if($shop->sta == 1)
                                <span class="badge bg-success">แผนที่ลิ้ง</span>
                            @else
                                <span class="badge bg-secondary">ละติจูด/ลองจิจูด</span>
                            @endif
                        </td>
                        <td>{{ $shop->latitude }}</td>
                        <td>{{ $shop->longitude }}</td>
                        <td>{{ $shop->employee_name }}</td>
                        <td>
                            <a href="{{ route('shops.edit', $shop->shop_id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('shops.destroy', $shop->shop_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">ไม่พบข้อมูล</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $shops->links('pagination::bootstrap-5') }} <!-- Bootstrap 4 -->
    </div>
</div>

<!-- JavaScript -->
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('คุณต้องการลบร้านค้านี้ใช่หรือไม่?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
