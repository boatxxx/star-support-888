<!DOCTYPE html>
<html>
<head>
    <title>สรุปยอดขาย</title>
    <style>
        body { font-family: 'TH SarabunPSK', sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>สรุปยอดขาย</h1>
    <p>วันที่เริ่มต้น: {{ request('start_date') }} ถึง วันที่สิ้นสุด: {{ request('end_date') }}</p>

    <table>
        <thead>
            <tr>
                <th>รหัสร้านค้า</th>
                <th>พนักงาน</th>
                <th>รหัสสินค้า</th>
                <th>ยอดขายรวม</th>
                <th>วันที่ขาย</th>
                <th>ค่าคอมมิชชั่น ({{ $commission }}%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->shop->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $sale->user->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ $sale->product_id }}</td>
                    <td>{{ number_format($sale->total_price, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                    <td>{{ number_format($sale->total_price * ($commission / 100), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
