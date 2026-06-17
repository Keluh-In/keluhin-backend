<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Response; // Import model Response
use App\Models\ComplaintAttachment; // Sesuaikan jika ini ada
use App\Models\User;
use App\Models\Category;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'description', 'location', 'is_anonymous', 'status', 'image',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relasi ke Response (Menggunakan model Response.php)
     */
    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function response()
    {
        return $this->hasOne(Response::class)->latestOfMany();
    }
    public function attachments()
{
    return $this->hasMany(ComplaintAttachment::class);
}
}
