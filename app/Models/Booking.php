<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',       // thêm
        'movie_id',
        'showtime_id',
        'seats',
        'customer_name',
        'customer_email',
    ];

    // Nếu seats lưu JSON thì tự động cast thành mảng
    protected $casts = [
        'seats' => 'array',
    ];

    // Quan hệ: Booking thuộc về 1 Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // Quan hệ: Booking thuộc về 1 Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
}
