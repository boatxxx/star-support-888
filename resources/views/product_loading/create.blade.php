@extends('layouts.app1')

@section('content')
<div class="container">
    <h1>ขนสินค้าขึ้นรถ</h1>

    <form action="{{ route('product_loading.store', $workRecord->id) }}" method="POST">

        @csrf

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Product Selection -->
        <div id="product-section" class="mb-4">
            <div class="form-group">
                <label for="products">สินค้า</label>
                <div id="products-container">
                    <div class="product-group">
                        <input type="hidden" name="work_record_id" value={{  $workRecord->id }}>

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

        <!-- Vehicle Selection -->
        <div class="form-group mb-4">
            <label for="vehicle_id">เลือกยานพาหนะ</label>
            <select name="vehicle_id" id="vehicle_id" class="form-control" required>
                <option value="">เลือกยานพาหนะ</option>
                @foreach ($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                @endforeach
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
