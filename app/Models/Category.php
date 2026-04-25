<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * RELASI: kategori punya banyak complaint
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}