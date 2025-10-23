<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Showtime extends Model
{
protected $fillable = ['room', 'time'];

    public function movie()
    {
        return $this->belongsTo(Movie::class); // movie_id phải tồn tại trong bảng showtimes
    }
}