<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบเสนอราคา</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">ใบเสนอราคา</h1>

        <!-- Display errors -->
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

        <!-- Shop Information -->
        <h3>ข้อมูลร้านค้า</h3>
        <p><strong>ชื่อร้านค้า:</strong> {{ $shopName }}</p>

        <!-- Product Selection Form -->
        <form action="{{ route('quotation.store') }}" method="POST">
            @csrf
            <div id="products-container">
                <h4>เลือกสินค้า</h4>
                <div class="product-group d-flex align-items-end mb-3">
                    <select name="items[0][product_id]" class="form-control me-2 product-select" required>
                        <option value="" disabled selected>เลือกสินค้า</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->product_id }}" data-price="{{ $product->price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="items[0][quantity]" class="form-control me-2" placeholder="จำนวนสินค้า" required min="1">
                    <input type="number" step="0.01" name="items[0][price]" class="form-control me-2 price-input" readonly>
                    <button type="button" class="btn btn-danger remove-product">ลบ</button>
                </div>
            </div>
            <button type="button" class="btn btn-primary mt-2" id="add-product">เพิ่มสินค้า</button>

            <!-- Promotions Section -->
            <h4 class="mt-4">โปรโมชั่นที่ใช้ได้</h4>
            <div class="promotion-section">
                @foreach ($promotions as $promotion)
                <div class="promotion-item mb-3">
                    <h5>
                        <input type="radio" name="selected_promotion" value="{{ $promotion->promotion_id }}" id="promotion-{{ $promotion->promotion_id }}" required>
                        <label for="promotion-{{ $promotion->promotion_id }}">{{ $promotion->name }}</label>
                    </h5>
                    <p>{{ $promotion->description }}</p>
                </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-success mt-4">ยืนยันใบเสนอราคา</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let productIndex = 1; // Start indexing products
            const container = document.getElementById('products-container');
            const addButton = document.getElementById('add-product');

            // Add a new product group
            addButton.addEventListener('click', function() {
                const productGroup = document.createElement('div');
                productGroup.className = 'product-group d-flex align-items-end mb-3';
                productGroup.innerHTML = `
                    <select name="items[${productIndex}][product_id]" class="form-control me-2 product-select" required>
                        <option value="" disabled selected>เลือกสินค้า</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->product_id }}" data-price="{{ $product->price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="items[${productIndex}][quantity]" class="form-control me-2" placeholder="จำนวนสินค้า" required min="1">
                    <input type="number" step="0.01" name="items[${productIndex}][price]" class="form-control me-2 price-input" readonly>
                    <button type="button" class="btn btn-danger remove-product">ลบ</button>
                `;
                container.appendChild(productGroup);
                productIndex++;
            });

            // Remove a product group
            container.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-product')) {
                    event.target.closest('.product-group').remove();
                }
            });

            // Update price input based on selected product
            container.addEventListener('change', function(event) {
                if (event.target.classList.contains('product-select')) {
                    const priceInput = event.target.closest('.product-group').querySelector('.price-input');
                    const selectedOption = event.target.options[event.target.selectedIndex];
                    priceInput.value = selectedOption.getAttribute('data-price');
                }
            });
        });
    </script>
</body>
</html>
