<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname', 'level', 'course_type', 'major',
        'academic_year', 'register_date', 'receipt_path', 'is_keyed'
    ];
}
