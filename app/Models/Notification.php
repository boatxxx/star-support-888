<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['line_api_url', 'token'];

    public static function sendLineNotification($message)
    {
        // 🛑 ปิดการแจ้งเตือนแบบสมบูรณ์

        // ส่งข้อความจำลองกลับไปเพื่อแจ้งว่าถูกปิดอยู่
        return 'LINE Notify ถูกปิดใช้งาน: ไม่มีเหตุการณ์อะไรเกิดขึ้น';
    }
}
