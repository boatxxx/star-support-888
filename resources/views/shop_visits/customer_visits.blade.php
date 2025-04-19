@extends('layouts.app1')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- Navigation Menu -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <a class="navbar-brand" href="{{ route('user') }}">หน้าหลัก</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop_visits.index') }}">การเยี่ยมลูกค้า</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop_visits.create') }}">บันทึกการเยี่ยมลูกค้า</a>
                </li>
            </ul>
        </div>
    </nav>

    <h1 class="text-center">ตรวจเยี่ยมลูกค้า</h1>

    <div class="mt-4">
        <h2 class="text-center">การเยี่ยมลูกค้าในวัน {{ \Carbon\Carbon::now()->translatedFormat('l') }}</h2>
        @if($shopVisits->isEmpty())
        <p class="text-center">ไม่พบข้อมูลการเยี่ยมลูกค้าสำหรับวันนี้</p>
        @else
        <div class="row g-4">
            @foreach ($shopVisits as $visit)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">ร้าน: {{ $visit->shop->name }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">พนักงาน: {{ $visit->employee->name }}</h6>
                        <p class="card-text">
                            <strong>หมายเหตุ:</strong> {{ $visit->notes }}<br>
                            <strong>วันที่สร้าง:</strong> {{ $visit->created_at->format('d/m/Y H:i') }}
                        </p>
                        <div class="d-grid gap-2">
                            @if($visit->shop->sta == 1 && $visit->shop->link_google)
                            <a href="{{ $visit->shop->link_google }}" class="btn btn-success btn-sm" target="_blank">นำทางลิ้ง</a>
                            @elseif($visit->shop->sta == 0 && $visit->shop->latitude && $visit->shop->longitude)
                            <a href="https://www.google.com/maps?q={{ $visit->shop->latitude }},{{ $visit->shop->longitude }}" class="btn btn-secondary btn-sm" target="_blank">นำทางตำแหน่ง</a>
                            @else
                            <span class="text-muted">ไม่มีข้อมูลนำทาง</span>
                            @endif

                            <a href="{{ route('shop_visits.create1', ['shop_id' => $visit->shop_id]) }}" class="btn btn-primary btn-sm">บันทึกการเยี่ยม</a>
                            <a href="{{ route('product.load1', ['shop_id' => $visit->shop_id]) }}" class="btn btn-info btn-sm">โหลดสินค้า</a>
                            <a href="{{ route('product_reservation.create1', ['shopId' => $visit->shop_id]) }}" class="btn btn-warning btn-sm">จองสินค้า</a>
                            <a href="{{ route('quotation.create1', ['shopId' => $visit->shop_id]) }}" class="btn btn-dark btn-sm">ใบเสนอราคา</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .card-text {
        font-size: 0.9rem;
    }

    .btn-sm {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .card-title {
            font-size: 1rem;
        }

        .card-text {
            font-size: 0.8rem;
        }
    }
</style>
@endsection
