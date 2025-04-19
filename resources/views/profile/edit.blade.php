@extends('layouts.app1')

@section('content')
<div class="container">
    <h1>แก้ไขข้อมูลส่วนตัว</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">ชื่อ</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">รหัสผ่านใหม่ (ถ้าไม่เปลี่ยนเว้นว่างไว้)</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>
        <div class="mb-3">
            <label for="img_user" class="form-label">รูปโปรไฟล์</label>
            <input type="file" name="img_user" id="img_user" class="form-control">
            @error('img_user')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            @if($user->img_user)
                <img src="{{ asset('storage/' . $user->img_user) }}" alt="Profile Image" class="img-thumbnail mt-2" width="150">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>
</div>
@endsection
