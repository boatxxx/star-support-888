@extends('layouts.app1')

@section('content')
<div class="container">
    <h1>ขนสินค้าขึ้นรถ</h1>

    <form action="{{ route('product_loading.store', ['workRecordId' => $workRecord->id ?? null, 'shopId' => $shop->shop_id ?? null]) }}" method="POST">

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
                        <select name="items[0][category_id]" class="form-control mb-2 category-select" data-index="0" required>
                            <option value="">เลือกหมวดหมู่</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="work_record_id" value={{  $shop->shop_id ?? $workRecord->id }}>
                        <select name="product_ids[]" class="form-control mb-2 product-select" data-index="0" required>
                            <option value="" disabled selected>เลือกสินค้า</option>
                        </select>
                        <input type="number" name="quantities[]" class="form-control mb-2" placeholder="จำนวนสินค้า" required>
                        <button type="button" class="btn btn-danger remove-product">ลบ</button>
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
    document.addEventListener('DOMContentLoaded', function () {
        const categories = @json($categories); // ส่งข้อมูลหมวดหมู่และสินค้าไปยัง JavaScript
        const productsContainer = document.getElementById('products-container');

        // เมื่อเลือกหมวดหมู่ ให้เปลี่ยนรายการสินค้า
        productsContainer.addEventListener('change', function (event) {
            if (event.target.classList.contains('category-select')) {
                const index = event.target.getAttribute('data-index');
                const categoryId = event.target.value;
                const productSelect = document.querySelector(`.product-select[data-index="${index}"]`);
                productSelect.innerHTML = '<option value="" disabled selected>เลือกสินค้า</option>';

                // กรองสินค้าจากหมวดหมู่ที่เลือก
                if (categoryId) {
                    const selectedCategory = categories.find(category => category.id == categoryId);
                    if (selectedCategory) {
                        selectedCategory.products.forEach(product => {
                            const option = document.createElement('option');
                            option.value = product.product_id;
                            option.textContent = product.name;
                            productSelect.appendChild(option);
                        });
                    }
                }
            }
        });

        // เพิ่มรายการสินค้าใหม่
        let productIndex = 1;
        document.getElementById('add-product').addEventListener('click', function () {
            const productGroup = document.createElement('div');
            productGroup.className = 'product-group mb-4';
            productGroup.innerHTML = `
                <select name="items[${productIndex}][category_id]" class="form-control mb-2 category-select" data-index="${productIndex}" required>
                    <option value="">เลือกหมวดหมู่</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="items[${productIndex}][product_id]" class="form-control mb-2 product-select" data-index="${productIndex}" required>
                    <option value="" disabled selected>เลือกสินค้า</option>
                </select>
                <input type="number" name="items[${productIndex}][quantity]" class="form-control mb-2" placeholder="จำนวนสินค้า" required>
                <button type="button" class="btn btn-danger remove-product">ลบ</button>
            `;
            productsContainer.appendChild(productGroup);
            productIndex++;
        });

        // ลบรายการสินค้า
        productsContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-product')) {
                event.target.closest('.product-group').remove();
            }
        });
    });
    </script>
@endsection
