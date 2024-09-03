@extends('layouts.app')

@section('content')
<div class="container">
    <h1>แก้ไขการคืนสินค้า</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('returns.update', $return->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="sale_id">รหัสการขาย</label>
            <input type="text" class="form-control" id="sale_id" name="sale_id" value="{{ old('sale_id', $return->sale_id) }}" required>
        </div>

        <div id="product-section">
            <div class="form-group">
                <label for="products">สินค้า</label>
                <div id="products-container">
                    @foreach ($return->items as $item)
                    <div class="product-group">
                        <select name="product_ids[]" class="form-control" required>
                            <option value="" disabled>เลือกสินค้า</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_id }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantities[]" class="form-control mt-2" value="{{ $item->quantity }}" placeholder="จำนวนสินค้า" required>
                        <button type="button" class="btn btn-danger mt-2 remove-product">ลบ</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-primary mt-2" id="add-product">เพิ่มสินค้า</button>
            </div>
        </div>

        <div class="form-group">
            <label for="return_date">วันที่ทำการคืนสินค้า</label>
            <input type="date" class="form-control" id="return_date" name="return_date" value="{{ old('return_date', $return->return_date) }}" required>
        </div>

        <div class="form-group">
            <label for="reason">สาเหตุการคืนสินค้า</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ old('reason', $return->reason) }}</textarea>
        </div>

        <div class="form-group">
            <label for="shop_id">ร้านค้า</label>
            <select class="form-control" id="shop_id" name="shop_id" required>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->shop_id }}" {{ $shop->id == $return->shop_id ? 'selected' : '' }}>{{ $shop->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="user_id">คนที่คืนสินค้า</label>
            <select class="form-control" id="user_id" name="user_id" required>
                @foreach ($users as $user)
                    <option value="{{ $user->user_id }}" {{ $user->id == $return->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">บันทึกการเปลี่ยนแปลง</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        });

        document.getElementById('products-container').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-product')) {
                event.target.parentElement.remove();
            }
        });
    });
</script>
@endsection
