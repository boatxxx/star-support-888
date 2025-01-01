@extends('layouts.app1')

@section('content')
<div class="container">
    <h1>ใบเสนอราคา</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>แจ้งเตือน:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('quotation.store', ['id' => $workRecord->id ]) }}" method="POST">
        @csrf
        <!-- Fields -->

        <h3>รายละเอียดออเดอร์</h3>

        @if($workRecord)
            <p><strong>วันที่ออเดอร์:</strong> {{ $workRecord->order_date }}</p>
            <h3>ออเดอร์: {{ $workRecord->id }}</h3>
            <h3>ร้านค้า: {{ $workRecord->shop->name ??  $shopName }}</h3>
        @else
            <p>ไม่พบข้อมูลออเดอร์</p>
        @endif

        <h4>สินค้าที่สั่งซื้อ</h4>
        <div id="products-container">
            @if($workRecord && $workRecord->items)
                @foreach ($workRecord->items as $index => $item)
                    <div class="product-group d-flex align-items-end mb-2">
                        <select name="items[{{ $index }}][category_id]" class="form-control me-2 category-select" data-index="{{ $index }}" required>
                            <option value="">เลือกหมวดหมู่</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if ($category->id == $item->product->category_id) selected @endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="items[{{ $index }}][product_id]" class="form-control me-2 product-select" data-index="{{ $index }}" required>
                            <option value="{{ $item->product->product_id }}" selected>{{ $item->product->name }}</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_id }}"
                                    @if ($product->product_id == $item->product->product_id) selected @endif
                                    data-price="{{ $product->price }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control me-2" value="{{ $item->quantity }}" min="1" step="1" placeholder="จำนวนสินค้า" required>
                        <input type="number" step="0.01" name="items[{{ $index }}][price]" class="form-control me-2 price-input" value="{{ $item->product->price }}" readonly>
                        <button type="button" class="btn btn-danger remove-product">ลบ</button>
                    </div>
                @endforeach
            @else
                <p>ยังไม่มีสินค้าสำหรับออเดอร์นี้</p>
            @endif
        </div>

        <button type="button" class="btn btn-primary mt-2" id="add-product">เพิ่มสินค้า</button>
        <button type="submit" class="btn btn-primary mt-4">สรุปใบเสนอราคา</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categories = @json($categories); // ส่งข้อมูลหมวดหมู่ไปยัง JS
        const productContainer = document.getElementById('products-container');

        // ฟังก์ชันกรองสินค้าเมื่อเลือกหมวดหมู่
        productContainer.addEventListener('change', function (event) {
            if (event.target.classList.contains('category-select')) {
                const categoryId = event.target.value;
                const productSelect = event.target.closest('.product-group').querySelector('.product-select');
                productSelect.innerHTML = '<option value="" disabled selected>เลือกสินค้า</option>';

                if (categoryId) {
                    const selectedCategory = categories.find(category => category.id == categoryId);
                    if (selectedCategory) {
                        selectedCategory.products.forEach(product => {
                            const option = document.createElement('option');
                            option.value = product.product_id;
                            option.textContent = product.name;
                            option.dataset.price = product.price;
                            productSelect.appendChild(option);
                        });
                    }
                }
            }
        });

        // เพิ่มสินค้าใหม่
        let productIndex = 1;
        document.getElementById('add-product').addEventListener('click', function () {
            const productGroup = document.createElement('div');
            productGroup.className = 'product-group d-flex align-items-end mb-2';
            productGroup.innerHTML = `
                <select name="items[${productIndex}][category_id]" class="form-control me-2 category-select" required>
                    <option value="">เลือกหมวดหมู่</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="items[${productIndex}][product_id]" class="form-control me-2 product-select" required>
                    <option value="" disabled selected>เลือกสินค้า</option>
                </select>
                <input type="number" name="items[${productIndex}][quantity]" class="form-control me-2" placeholder="จำนวนสินค้า" required min="1" step="1">
                <input type="number" step="0.01" name="items[${productIndex}][price]" class="form-control me-2 price-input" readonly>
                <button type="button" class="btn btn-danger remove-product">ลบ</button>`;
            productContainer.appendChild(productGroup);
            productIndex++;
        });

        // ลบสินค้า
        productContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-product')) {
                event.target.closest('.product-group').remove();
            }
        });

        // อัปเดตราคาเมื่อเลือกสินค้า
        productContainer.addEventListener('change', function (event) {
            if (event.target.classList.contains('product-select')) {
                const selectedOption = event.target.options[event.target.selectedIndex];
                const priceInput = event.target.closest('.product-group').querySelector('.price-input');
                priceInput.value = selectedOption.dataset.price || 0;
            }
        });
    });
</script>
@endsection
