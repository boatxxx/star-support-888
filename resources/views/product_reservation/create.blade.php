@extends('layouts.app1')

@section('content')
<div class="container">
    <h2>จองสินค้า</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- ฟอร์มจองสินค้า -->
    <form action="{{ route('product_reservation.store') }}" method="POST">
        @csrf
        <div class="alert alert-info">
            @if ($shop ?? $workRecord)
                <!-- ถ้ามีข้อมูลร้านค้าแสดงชื่อร้าน -->
                ร้านค้าที่จองสินค้า: {{ $shop->name ?? $workRecord->shop->name }}
                <input type="hidden" name="shop_id" value="{{ $shop->shop_id ?? $workRecord->shop_id }}">
            @else
                <!-- ถ้าไม่มีข้อมูลร้านค้า -->
                <p>ไม่พบข้อมูลร้านค้า</p>
                <select name="shop_id" required>
                    <option value="">เลือกร้านค้า</option>
                    @foreach ($shops as $shopOption)
                        <option value="{{ $shopOption->id }}">{{ $shopOption->name }}</option>
                    @endforeach
                </select>
            @endif
        </div>






        </div>

        <!-- ส่วนเลือกสินค้า -->
        <div id="product-section" class="mb-4">
            <div class="form-group">
                <label for="product_id">เลือกสินค้า</label>
                <div id="products-container">
                    <div class="product-group d-flex">
                        <select name="product_ids[]" class="form-control" required>
                            <option value="" disabled selected>เลือกสินค้า</option>
                            @foreach($products as $product)
                                <option value="{{ $product->product_id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantities[]" class="form-control ml-2" placeholder="จำนวนที่จอง" required min="1">
                        <button type="button" class="btn btn-danger ml-2 remove-product">ลบ</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary mt-2" id="add-product">เพิ่มสินค้า</button>
            </div>
        </div>

        <!-- ปุ่มบันทึก -->
        <button type="submit" class="btn btn-warning">จองสินค้า</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productCount = 1;

        // เมื่อกดปุ่มเพิ่มสินค้า
        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const productGroup = document.createElement('div');
            productGroup.className = 'product-group d-flex mt-2';
            productGroup.innerHTML = `
                <select name="product_ids[]" class="form-control" required>
                    <option value="" disabled selected>เลือกสินค้า</option>
                    @foreach($products as $product)
                        <option value="{{ $product->product_id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="quantities[]" class="form-control ml-2" placeholder="จำนวนที่จอง" required min="1">
                <button type="button" class="btn btn-danger ml-2 remove-product">ลบ</button>
            `;
            container.appendChild(productGroup);
            productCount++;
        });

        // เมื่อกดปุ่มลบสินค้า
        document.getElementById('products-container').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-product')) {
                event.target.parentElement.remove();
            }
        });
    });
</script>
@endsection
