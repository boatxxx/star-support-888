@section('title', 'ข้อมูลพนักงานทั้งหมด')@extends('layouts.app')



@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">ข้อมูลพนักงานทั้งหมด</h3>
                <h6 class="op-7 mb-2"> บริษัท สตาร์ซัพพอร์ต 999 (ประเทศไทย) จํากัด </h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-round">เพิ่มพนักงานใหม่</a>
            </div>
        </div>
        <div class="row">
            @foreach($employees as $employee)
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">{{ $employee->name }}</p>
                                    <h4 class="card-title">{{ $employee->email }}</h4>
                                    <p class="card-category">รหัสพนักงาน: {{ $employee->user_id }}</p>
                                    <p class="card-category">สถานะ: {{ $employee->status }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- ปุ่มสำหรับดูข้อมูล แก้ไข และลบ -->
                        <div class="row mt-3">
                            <div class="col text-end">
                                <a href="{{ route('users.show', $employee->user_id) }}" class="btn btn-info">ดูข้อมูล</a>
                                <a href="{{ route('users.edit', $employee->user_id) }}" class="btn btn-warning">แก้ไข</a>
                                <form action="{{ route('users.destroy', $employee->user_id) }}}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">ลบ</button>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>@endsection
</div>

