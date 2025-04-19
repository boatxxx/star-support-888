<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการลงทะเบียน</title>
</head>
<body>
    <h2>ข้อมูลการลงทะเบียน</h2>
    <table border="1">
        <tr>
            <th>ชื่อ</th>
            <th>ระดับชั้น</th>
            <th>สถานะ</th>
            <th>ใบเสร็จ</th>
            <th>คีย์ข้อมูล</th>
        </tr>
        @foreach ($registrations as $registration)
        <tr>
            <td>{{ $registration->fullname }}</td>
            <td>{{ $registration->level }}</td>
            <td>{{ $registration->is_keyed ? 'คีย์แล้ว' : 'ยังไม่คีย์' }}</td>
            <td>
                @if ($registration->receipt_path)
                    <a href="{{ asset('storage/' . $registration->receipt_path) }}" target="_blank">ดูไฟล์</a>
                @endif
            </td>
            <td>
                @if (!$registration->is_keyed)
                    <form action="{{ route('register.key', $registration->id) }}" method="POST">
                        @csrf
                        <button type="submit">คีย์ข้อมูล</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
