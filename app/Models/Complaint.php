<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Gunakan ini jika Anda mengaktifkan Soft Deletes

class Complaint extends Model
{
    use HasFactory, SoftDeletes; // Pastikan SoftDeletes dipasang jika controller merekam aksi 'complaint.soft_deleted'

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'location',
        'is_anonymous',
        'status',
    ];

    /**
     * Kast tipe data atribut database agar terkonversi dengan benar di PHP.
     */
    protected $casts = [
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Hubungan Relasi ke Model User (Pengaju Pengaduan).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Hubungan Relasi ke Model Category (Kategori Pengaduan).
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Hubungan Relasi tambahan yang dimuat di fungsi show() (jika ada).
     */
    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class); // Sesuaikan dengan nama model Response Anda jika ada
    }

    public function response()
    {
        return $this->hasOne(ComplaintResponse::class)->latestOfMany(); // Contoh untuk mengambil respon terakhir
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class); // Sesuaikan jika ada relasi lampiran berkas
    }
}
