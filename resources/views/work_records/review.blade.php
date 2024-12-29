@extends('layouts.app1')
<style>
   .card-header:nth-child(odd) {
        background-color: lightblue;
        color: white;
    }

    /* แถบสีเทาสำหรับแถวเลขคู่ */
    .card-header:nth-child(even) {
        background-color: lightgray;
        color: white;
    }

    .card-header {
        font-weight: bold;
        text-align: center;
    }
.
</style>
@section('content')
<div class="container">
    <h1 class="my-4">ตรวจสอบออเดอร์</h1>

    <div class="accordion" id="orderAccordion">
    <!-- Loop Through Work Records -->
    @foreach ($workRecords as $index => $workRecord)
        <div class="card {{ $workRecord->status === 'กำลังดำเนินการ' ? 'd-none' : '' }}">
            <div class="card-header" id="heading{{ $index }}">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapse{{ $index }}">
                        ออเดอร์ที่ {{ $index + 1 }} - {{ $workRecord->shop->name }} ({{ $workRecord->order_date }})
                    </button>
                </h5>
            </div>

            <div id="collapse{{ $index }}" class="collapse {{ $workRecord->status === 'กำลังดำเนินการ' ? 'd-none' : '' }}" aria-labelledby="heading{{ $index }}" data-parent="#orderAccordion">
                <div class="card-body">
                    <p><strong>วันที่ทำการออเดอร์:</strong> {{ $workRecord->order_date }}</p>
                    <p><strong>ร้านค้า:</strong> {{ $workRecord->shop->name }}</p>
                    <p><strong>ยูเซอร์:</strong> {{ $workRecord->user->name }}</p>
                    <p><strong>สถานะ:</strong>
                        @if($workRecord->status === 'pending')
                            รอดำเนินการ
                        @elseif($workRecord->status === 'completed')
                            เสร็จสิ้นแล้ว
                        @else
                            ไม่ทราบสถานะ
                        @endif
                    </p>

                    <h5 class="mt-4">สินค้าที่สั่งซื้อ</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>สินค้า</th>
                                <th>จำนวน</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workRecord->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Links to Related Menus for this Work Record -->
                    <div class="card mt-4">
                        <div class="card-header">
                            เมนูที่เกี่ยวข้องสำหรับออเดอร์นี้
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between">
                                <a href="{{ route('quotation.show', $workRecord->id) }}" class="btn btn-primary mb-2">ใบเสนอสินค้า</a>
                                @if($workRecord->shop->sta == 1 && $workRecord->shop->link_google)
                                    <!-- นำทางด้วยลิงก์ที่บันทึกไว้ -->
                                    <a href="{{ $workRecord->shop->link_google }}" class="btn btn-success mb-2" target="_blank">นำทางด้วยลิงก์ที่บันทึกไว้</a>
                                @elseif($workRecord->shop->sta == 0 && $workRecord->shop->latitude && $workRecord->shop->longitude)
                                    <!-- นำทางด้วยละติจูดและลองจิจูด -->
                                    <a href="https://www.google.com/maps?q={{ $workRecord->shop->latitude }},{{ $workRecord->shop->longitude }}" class="btn btn-success mb-2" target="_blank">นำทางด้วยพิกัด</a>
                                @else
                                    <p>ไม่พบข้อมูลนำทาง</p>
                                @endif

                                <a href="{{ route('product_loading.create', $workRecord->id) }}" class="btn btn-info mb-2">โหลดสินค้า</a>
                                <a href="{{ route('product_reservation.create', $workRecord->id) }}" class="btn btn-warning mb-2">จองสินค้า</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

</div>
@endsection
