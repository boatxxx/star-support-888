<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Trip extends Model
{
    use HasFactory;
    protected $table = 'trips';

    protected $fillable = [
        'sales_rep_id',
        'latitude_date',
        'longitude_date',
        'time',
    ];
    public function salesRep()
{
    return $this->belongsTo(User::class, 'sales_rep_id');
}
}
