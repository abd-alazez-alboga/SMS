<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsfeed extends Model
{
    use HasFactory;
    protected $fillable = [
        'images',
        'title_en',
        'description_en',
        'title_ar',
        'description_ar',
    ];
    protected $casts = [
        'images' => 'array',
    ];
}
