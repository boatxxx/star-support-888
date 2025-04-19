<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $primaryKey = 'shop_id';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'district',
        'Link_google',
        'sta',
        'Latitude',
        'Longitude'
    ];
    public function shopVisits()
    {
        return $this->hasMany(ShopVisit::class, 'shop_id'); // ความสัมพันธ์แบบ One-to-Many
    }

}
