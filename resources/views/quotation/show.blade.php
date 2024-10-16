@extends('layouts.app1')

@section('content')
<div class="container">
    <h1>ใบเสนอราคา</h1>

    <form action="{{ route('quotation.store', $workRecord->id) }}" method="POST">
        @csrf

        <h3>รายละเอียดออเดอร์</h3>
        <p><strong>วันที่ออเดอร์:</strong> {{ $workRecord->order_date }}</p>
        <p><strong>ร้านค้า:</strong> {{ $workRecord->shop->name }}</p>

        <h4>สินค้าที่สั่งซื้อ</h4>
        <div id="products-container">
            @foreach ($workRecord->items as $index => $item)
            <div class="product-group d-flex align-items-end mb-2">
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
        </div>


        <!-- แสดงโปรโมชั่นที่สามารถใช้ได้ -->
        <h4>โปรโมชั่นที่ใช้ได้</h4>
<div class="promotion-section">
    @foreach ($promotions as $promotion)
    <div class="promotion-item mb-3">
        <h5>
            <input type="radio" name="selected_promotion" value="{{ $promotion->promotion_id }}" id="promotion-{{ $promotion->promotion_id }}" required>
            <label for="promotion-{{ $promotion->promotion_id }}">{{ $promotion->name }}</label> <!-- ชื่อโปรโมชั่น -->
        </h5>
        <p>{{ $promotion->description }}</p> <!-- คำอธิบาย -->

        <strong>ชนิดโปรโมชั่น:</strong>
        <span>
            @if($promotion->type === 'percentage_discount')
                ลดเป็นเปอร์เซ็นต์
            @elseif($promotion->type === 'fixed_amount')
                ลดจำนวนคงที่
            @elseif($promotion->type === 'product_specific_discount')
                ลดเฉพาะสินค้าบางรายการ
            @else
                ชนิดโปรโมชั่นอื่น ๆ
            @endif
        </span>

        @if ($promotion->conditions)
            <strong>เงื่อนไข:</strong>
            <ul>
                @foreach ($promotion->conditions as $condition)
                    <li>เงื่อนไข: {{ $condition->condition_type }} - {{ $condition->condition_value }}</li>
                @endforeach
            </ul>
        @endif

        @if ($promotion->discounts)
            <strong>ส่วนลด:</strong>
            <ul>
                @foreach ($promotion->discounts as $discount)
                    <li>ส่วนลด:
                        {{ $discount->discount_type === 'percentage_discount' ? 'ลดเป็นเปอร์เซ็นต์' : 'ลดจำนวนคงที่' }} -
                        {{ $discount->discount_value }}{{ $discount->discount_type === 'percentage_discount' ? '%' : ' บาท' }}
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($promotion->type === 'product_specific_discount' && $promotion->products)
            <strong>สินค้าที่มีส่วนลด:</strong>
            <ul>
                @foreach ($promotion->products as $product)
                    <li>{{ $product->name }} - ลด {{ $product->discount_value }}%</li>
                @endforeach
            </ul>
        @endif
    </div>
    @endforeach
</div>



        <button type="button" class="btn btn-primary mt-2" id="add-product">เพิ่มสินค้า</button>
        <button type="submit" class="btn btn-primary mt-4">สรุปใบเสนอราคา</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update price when a product is selected
        const productSelects = document.querySelectorAll('.product-select');
        productSelects.forEach(select => {
            select.addEventListener('change', function() {
                const priceInput = this.closest('.product-group').querySelector('.price-input');
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');

                priceInput.value = price; // Update the price
            });
        });

        // Add new product group
        let productIndex = {{ count($workRecord->items) }};
        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const productGroup = document.createElement('div');
            productGroup.className = 'product-group d-flex align-items-end mb-2';
            productGroup.innerHTML =
                `<select name="items[${productIndex}][product_id]" class="form-control me-2 product-select" required>
                    <option value="" disabled selected>เลือกสินค้า</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->product_id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="items[${productIndex}][quantity]" class="form-control me-2" placeholder="จำนวนสินค้า" required min="1" step="1">
                <input type="number" step="0.01" name="items[${productIndex}][price]" class="form-control me-2 price-input" readonly>
                <button type="button" class="btn btn-danger remove-product">ลบ</button>`;
            container.appendChild(productGroup);
            productIndex++;

            // Add event listener for new select dropdown
            const newSelect = productGroup.querySelector('.product-select');
            newSelect.addEventListener('change', function() {
                const priceInput = productGroup.querySelector('.price-input');
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');

                priceInput.value = price; // Update the price
            });
        });

        // Remove product group
        document.getElementById('products-container').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-product')) {
                event.target.parentElement.remove();
            }
        });
    });
</script>
@endsection
