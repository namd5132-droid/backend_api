<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'genre',
        'image',
        'description',
        'price',
        'new',
    ];

    // Khi trả JSON sẽ tự động thêm trường image_url
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        // Nếu có ảnh thì trả về link đầy đủ, không thì trả placeholder
       return $this->image 
    ? asset('uploads/' . $this->image) 
    : asset('images/no-image.png');
    }
}
