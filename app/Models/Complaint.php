<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'location',
        'image',
        'is_anonymous',
        'status'
    ];

    /**
     * RELASI: complaint milik user
     */public function user()
{
    return $this->belongsTo(User::class);
}

    /**
     * RELASI: complaint punya kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * RELASI: complaint punya response admin
     */
    public function response()
    {
        return $this->hasOne(Response::class);
    }
}
