@extends('layouts.app1')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <!-- Navigation Menu -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <a class="navbar-brand" href="{{ route('user') }}">หน้าหลัก</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop_visits.index') }}">การเยี่ยมลูกค้า</a>
                </li>
                <li class="nav-item">
                    @foreach ($shopVisits as $visit)
                    <a class="nav-link" href="">บันทึกการเยี่ยมลูกค้า</a>
                @endforeach
                                </li>
            </ul>
        </div>
    </nav>

    <h1 class="text-center">ตรวจเยี่ยมลูกค้า</h1>

    @php
        $today = \Carbon\Carbon::now()->format('l'); // Get the current day in English (Monday, etc.)
    @endphp

    <div class="mt-4">
        <h2 class="text-center">การเยี่ยมลูกค้าในวัน {{ \Carbon\Carbon::now()->translatedFormat('l') }}</h2>
        @if($shopVisits->isEmpty())
            <p class="text-center">ไม่พบข้อมูลการเยี่ยมลูกค้าสำหรับวันนี้</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">รหัสการเยี่ยมลูกค้า</th>
                            <th scope="col">ชื่อลูกค้า</th>
                            <th scope="col">พนักงาน</th>
                            <th scope="col">หมายเหตุ</th>
                            <th scope="col">วันที่สร้าง</th>
                            <th scope="col">เมนูนำทาง</th>
                            <th scope="col">เมนูบันทึกข้อมูลการเยี่ยม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shopVisits as $visit)
                            <tr>
                                <td>{{ $visit->id }}</td>
                                <td>{{ $visit->shop->name }}</td>
                                <td>{{ $visit->employee->name }}</td>
                                <td>{{ $visit->notes }}</td>
                                <td>{{ $visit->created_at }}</td>
                                <td>
                                    <!-- เมนูการนำทาง -->
                                    @if($visit->shop->sta == 1 && $visit->shop->link_google)
                                        <!-- นำทางด้วยลิงก์ที่บันทึกไว้ -->
                                        <a href="{{ $visit->shop->link_google }}" class="btn btn-success" target="_blank">นำทางลิ้ง</a>
                                    @elseif($visit->shop->sta == 0 && $visit->shop->latitude && $visit->shop->longitude)
                                        <!-- นำทางด้วยพิกัด -->
                                        <a href="https://www.google.com/maps?q={{ $visit->shop->latitude }},{{ $visit->shop->longitude }}" class="btn btn-secondary" target="_blank">นำทางตำแหน่ง</a>
                                    @else
                                        <span>ไม่มีข้อมูลนำทาง</span>
                                    @endif
                                </td>

                                <td>
                                    <!-- ปุ่มสำหรับการบันทึกการเยี่ยมร้าน -->
                                    <a href="{{ route('shop_visits.create1', ['shop_id' => $visit->shop_id]) }}" class="btn btn-primary">
                                        บันทึกการเยี่ยม
                                    </a>
                                </td>

                                <td>
                                    <!-- ปุ่มสำหรับการโหลดสินค้า -->
                                    <a href="{{ route('product.load1', ['shop_id' => $visit->shop_id]) }}" class="btn btn-info mb-2">โหลดสินค้า</a>
                                    <!-- ปุ่มสำหรับการจองสินค้า -->
                                    <a href="{{ route('product_reservation.create1', ['shopId' => $visit->shop_id]) }}" class="btn btn-warning mb-2">จองสินค้า</a>
                                    <!-- ปุ่มสำหรับการสร้างใบเสนอราคา -->
                                  <!-- ปุ่มสำหรับการสร้างใบเสนอราคา -->
                                  <a href="{{ route('quotation.create1', ['shopId' => $visit->shop_id]) }}" class="btn btn-primary">ไปยังใบเสนอราคา</a>



                                                                    </td>



                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .table th, .table td {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            h1, h2 {
                font-size: 1.5rem;
            }

            .table th, .table td {
                font-size: 0.875rem;
            }

            .btn-lg {
                width: 100%;
                font-size: 1rem;
            }
        }
    </style>
</div>
@endsection
