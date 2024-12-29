<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Notification extends Model
{
    protected $fillable = ['line_api_url', 'token'];

    public static function sendLineNotification($message)
    {
        // ดึงข้อมูลการตั้งค่าจากฐานข้อมูล (แถวแรก)
        $notification = self::first();

        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if (!$notification || !$notification->line_api_url || !$notification->token) {
            \Log::error('LINE Notify: ข้อมูลการแจ้งเตือนในตาราง notifications ไม่ครบถ้วน');
            return false;
        }

        // ส่งคำขอ POST ไปยัง LINE Notify API
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $notification->token,
            ])->asForm()->post($notification->line_api_url, [
                'message' => $message,
            ]);

            // ตรวจสอบผลลัพธ์จาก API
            if (!$response->successful()) {
                \Log::error('LINE Notify Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('LINE Notify Exception', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
