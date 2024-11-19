<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'pickup_location',
        'destination',
        'images',
        'description_en',
        'description_ar',
        'price',
        'is_passport_required',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'integer',
        'is_passport_required' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
