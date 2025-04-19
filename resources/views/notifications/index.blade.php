@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ตั้งค่าระบบแจ้งเตือนผ่าน LINE</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('notifications.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="line_api_url" class="form-label">LINE API URL</label>
            <input type="url" name="line_api_url" id="line_api_url" class="form-control"
                value="{{ old('line_api_url', $notification->line_api_url ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="token" class="form-label">Token</label>
            <input type="text" name="token" id="token" class="form-control"
                value="{{ old('token', $notification->token ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>
</div>
@endsection
