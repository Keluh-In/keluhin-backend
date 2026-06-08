<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
<<<<<<< HEAD
use App\Models\Response; // Import model Response
use App\Models\ComplaintAttachment; // Sesuaikan jika ini ada
use App\Models\User;
use App\Models\Category;
=======
>>>>>>> origin/main

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
<<<<<<< HEAD
        'user_id', 'category_id', 'title', 'description', 'location', 'is_anonymous', 'status',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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

=======
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
     */
    public function user()
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
>>>>>>> origin/main
    public function response()
    {
        return $this->hasOne(Response::class)->latestOfMany();
    }
<<<<<<< HEAD
    public function attachments()
{
    return $this->hasMany(ComplaintAttachment::class);
}
=======

    /**
     * RELASI: complaint punya banyak tanggapan admin
     */
    public function responses()
    {
        return $this->hasMany(Response::class)->latest('id');
    }

    /**
     * RELASI: complaint punya banyak lampiran bukti
     */
    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class)->latest('id');
    }
>>>>>>> origin/main
}
