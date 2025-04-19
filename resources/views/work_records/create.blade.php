@extends('layouts.app')

@section('content')
<div class="container">
    <h1>เพิ่มออเดอร์</h1>
    <form action="{{ route('work_records.store') }}" method="POST">
        @csrf
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Product Selection -->
        <div id="product-section">
            <div class="form-group">
                <label for="products">สินค้า</label>
                <div id="products-container">
                    <div class="product-group">
                        <select name="product_ids[]" class="form-control" required>
                            <option value="" disabled selected>เลือกสินค้า</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantities[]" class="form-control mt-2" placeholder="จำนวนสินค้า" required>
                        <button type="button" class="btn btn-danger mt-2 remove-product">ลบ</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary mt-2" id="add-product">เพิ่มสินค้า</button>
            </div>
        </div>

        <!-- Order Date -->
        <div class="form-group">
            <label for="order_date">วันที่ทำการออเดอร์</label>
            <input type="date" name="order_date" id="order_date" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">คำอธิบายเพิ่มเติม</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <!-- Shop -->
        <div class="form-group">
            <label for="shop_id">ร้านค้า</label>
            <select name="shop_id" id="shop_id" class="form-control" required>
                <option value="">เลือกร้านค้า</option>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->shop_id }}">{{ $shop->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- User -->
        <div class="form-group">
            <label for="user_id">ยูเซอร์</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">เลือกยูเซอร์</option>
                @foreach ($users as $user)
                    <option value="{{ $user->user_id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div class="form-group">
            <label for="status">สถานะออเดอร์</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending">รอดำเนินการ</option>
                <option value="completed">เสร็จสิ้น</option>
                <option value="cancelled">ยกเลิก</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productCount = 1;

        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const productGroup = document.createElement('div');
            productGroup.className = 'product-group';
            productGroup.innerHTML = `
                <select name="product_ids[]" class="form-control" required>
                    <option value="" disabled selected>เลือกสินค้า</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->product_id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="quantities[]" class="form-control mt-2" placeholder="จำนวนสินค้า" required>
                <button type="button" class="btn btn-danger mt-2 remove-product">ลบ</button>
            `;
            container.appendChild(productGroup);
            productCount++;
        });

        document.getElementById('products-container').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-product')) {
                event.target.parentElement.remove();
            }
        });
    });
</script>
@endsection
