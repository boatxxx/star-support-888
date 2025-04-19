@extends('layouts.app')

@section('content')
<div class="container">
    <h2>เลือกพนักงานและวันที่</h2>
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <form action="{{ route('employee-visit.show') }}" method="GET">
        <div class="form-group">
            <label for="sales_rep_id">เลือกพนักงาน</label>
            <select id="sales_rep_id" name="sales_rep_id" class="form-control">
                @foreach($employees as $employee)
                    <option value="{{ $employee->user_id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="visit_date">เลือกวันที่</label>
            <input type="date" id="visit_date" name="visit_date" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">ค้นหา</button>
    </form>
</div>
@endsection
