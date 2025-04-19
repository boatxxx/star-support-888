<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขโปรโมชั่น</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles เหมือนกับที่ใช้ในหน้าเพิ่มโปรโมชั่น */
        body {
            background-color: #f8f9fa;
        }
        h1, h3 {
            text-align: center;
            margin-top: 20px;
            color: #343a40;
        }
        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            display: block;
            width: 100%;
            margin-top: 20px;
        }
        .condition-group, .discount-group, .product-group {
            background-color: #e9ecef;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        button[type="button"] {
            margin-top: 10px;
            margin-bottom: 30px;
            display: block;
            width: 100%;
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button[type="button"]:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <body>
        <div class="container">
            <h1>แก้ไขโปรโมชั่น</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('promotions.update', $promotion->promotion_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">ชื่อโปรโมชั่น</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $promotion->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="description">รายละเอียดโปรโมชั่น</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $promotion->description) }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="start_date">วันที่เริ่มต้น</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $promotion->start_date) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_date">วันที่สิ้นสุด</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $promotion->end_date) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="type">ประเภทโปรโมชั่น</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="percentage_discount" {{ $promotion->type == 'percentage_discount' ? 'selected' : '' }}>ส่วนลดเปอร์เซ็นต์</option>
                        <option value="product_specific_discount" {{ $promotion->type == 'product_specific_discount' ? 'selected' : '' }}>ส่วนลดสินค้าพิเศษ</option>
                        <option value="conditional_discount" {{ $promotion->type == 'conditional_discount' ? 'selected' : '' }}>ส่วนลดตามเงื่อนไข</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">สถานะ</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="active" {{ $promotion->status == 'active' ? 'selected' : '' }}>เปิดใช้งาน</option>
                        <option value="inactive" {{ $promotion->status == 'inactive' ? 'selected' : '' }}>ปิดใช้งาน</option>
                    </select>
                </div>

                <h4>เงื่อนไขโปรโมชั่น</h4>
                <div id="conditions">
                    @foreach ($conditions as $index => $condition)
                        <div class="condition-group">
                            <label for="condition_type">ประเภทเงื่อนไข</label>
                            <select name="conditions[{{ $index }}][condition_type]" class="form-control" required>
                                <option value="buy_x_get_y" {{ $condition->condition_type == 'buy_x_get_y' ? 'selected' : '' }}>ซื้อ X แถม Y</option>
                                <option value="quantity_based" {{ $condition->condition_type == 'quantity_based' ? 'selected' : '' }}>ตามจำนวน</option>
                            </select>
                            <label for="condition_value">ค่าเงื่อนไข</label>
                            <input type="text" name="conditions[{{ $index }}][condition_value]" class="form-control" value="{{ old('conditions.' . $index . '.condition_value', $condition->condition_value) }}" required>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addCondition()">เพิ่มเงื่อนไข</button>

                <h4>ส่วนลดโปรโมชั่น</h4>
                <div id="discounts">
                    @foreach ($discounts as $index => $discount)
                        <div class="discount-group">
                            <label for="discount_type">ประเภทส่วนลด</label>
                            <select name="discounts[{{ $index }}][discount_type]" class="form-control" required>
                                <option value="percentage" {{ $discount->discount_type == 'percentage' ? 'selected' : '' }}>เปอร์เซ็นต์</option>
                                <option value="fixed_amount" {{ $discount->discount_type == 'fixed_amount' ? 'selected' : '' }}>จำนวนคงที่</option>
                            </select>
                            <label for="discount_value">ค่าส่วนลด</label>
                            <input type="text" name="discounts[{{ $index }}][discount_value]" class="form-control" value="{{ old('discounts.' . $index . '.discount_value', $discount->discount_value) }}" required>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addDiscount()">เพิ่มส่วนลด</button>

                <h4>สินค้าที่ร่วมโปรโมชั่น</h4>
                <div id="products">
                    @foreach ($products as $index => $product)
                        <div class="product-group">
                            <label for="product_id">สินค้า</label>
                            <select name="products[{{ $index }}][product_id]" class="form-control" required>
                                @foreach ($productList as $prod)
                                    <option value="{{ $prod->id }}" {{ $prod->id == $product->product_id ? 'selected' : '' }}>{{ $prod->name }}</option>
                                @endforeach
                            </select>
                            <label for="applied_discount">ส่วนลดที่ใช้กับสินค้า</label>
                            <input type="text" name="products[{{ $index }}][applied_discount]" class="form-control" value="{{ old('products.' . $index . '.applied_discount', $product->applied_discount) }}" required>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addProduct()">เพิ่มสินค้า</button>

                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
            </form>
        </div>
    <script>
        function addCondition() {
            let conditionIndex = document.querySelectorAll('.condition-group').length;
            let conditionHtml = `
                <div class="condition-group">
                    <label for="condition_type">ประเภทเงื่อนไข</label>
                    <select name="conditions[${conditionIndex}][condition_type]" class="form-control">
                        <option value="buy_x_get_y">ซื้อ X แถม Y</option>
                        <option value="quantity_based">ตามจำนวน</option>
                    </select>
                    <label for="condition_value">ค่าเงื่อนไข</label>
                    <input type="text" name="conditions[${conditionIndex}][condition_value]" class="form-control" placeholder="ค่าเงื่อนไข">
                </div>`;
            document.getElementById('conditions').insertAdjacentHTML('beforeend', conditionHtml);
        }

        function addDiscount() {
            let discountIndex = document.querySelectorAll('.discount-group').length;
            let discountHtml = `
                <div class="discount-group">
                    <label for="discount_type">ประเภทส่วนลด</label>
                    <select name="discounts[${discountIndex}][discount_type]" class="form-control">
                        <option value="percentage">เปอร์เซ็นต์</option>
                        <option value="fixed_amount">จำนวนคงที่</option>
                    </select>
                    <label for="discount_value">ค่าส่วนลด</label>
                    <input type="text" name="discounts[${discountIndex}][discount_value]" class="form-control" placeholder="ค่าส่วนลด">
                </div>`;
            document.getElementById('discounts').insertAdjacentHTML('beforeend', discountHtml);
        }
        var products = @json($products1);

        function addProduct() {
    let productIndex = document.querySelectorAll('.product-group').length;

    // Create a dropdown for the products
    let productOptions = products.map(product => `<option value="${product.id}">${product.name}</option>`).join('');

    let productHtml = `
        <div class="product-group">
            <label for="products[${productIndex}][product_id]">สินค้า</label>
            <select name="products[${productIndex}][product_id]" class="form-control" required>
                <option value="">เลือกสินค้า</option>
                ${productOptions}
            </select>
            <label for="products[${productIndex}][applied_discount]">ส่วนลดที่ใช้กับสินค้า</label>
            <input type="text" name="products[${productIndex}][applied_discount]" class="form-control" placeholder="ค่าส่วนลด">
        </div>`;

    document.getElementById('products').insertAdjacentHTML('beforeend', productHtml);
}
    </script>
</body>
</html>
