<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนแก้ผลการเรียน</title>
</head>
<body>
    <h2>แบบฟอร์มลงทะเบียนแก้ผลการเรียน</h2>
    <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>ชื่อ-สกุล *</label>
        <input type="text" name="fullname" required>

        <label>ระดับชั้น *</label>
        <select name="level" required>
            <option value="ปวช.ปีที่ 1">ปวช.ปีที่ 1</option>
            <option value="ปวช.ปีที่ 2">ปวช.ปีที่ 2</option>
            <option value="ปวช.ปีที่ 3">ปวช.ปีที่ 3</option>
            <option value="ปวส.ปีที่ 1">ปวส.ปีที่ 1</option>
            <option value="ปวส.ปีที่ 2">ปวส.ปีที่ 2</option>
        </select>

        <label>ประเภทวิชา *</label>
        <select name="course_type" required>
            <option value="อุตสาหกรรม">อุตสาหกรรม</option>
            <option value="พาณิชยกรรม">พาณิชยกรรม</option>
            <option value="บริหารธุรกิจ">บริหารธุรกิจ</option>
        </select>

        <label>สาขาวิชา *</label>
        <select name="major" required>
            <option value="การบัญชี">การบัญชี</option>
            <option value="การตลาด">การตลาด</option>
            <option value="เทคโนโลยีสารสนเทศ">เทคโนโลยีสารสนเทศ</option>
            <option value="ช่างยนต์">ช่างยนต์</option>
        </select>

        <label>ปีการศึกษา *</label>
        <input type="number" name="academic_year" required>

        <label>วันที่ลงทะเบียนแก้ *</label>
        <input type="date" name="register_date" required>

        <label>ใบเสร็จรับเงิน *</label>
        <input type="file" name="receipt" accept="application/pdf, image/*">

        <button type="submit">ส่งข้อมูล</button>
    </form>
</body>
</html>
