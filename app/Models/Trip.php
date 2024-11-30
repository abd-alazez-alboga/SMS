<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'pickup_location_ar',
        'destination_ar',
        'pickup_location_en',
        'destination_en',
        'images',
        'description_en',
        'description_ar',
        'price'
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'integer'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
