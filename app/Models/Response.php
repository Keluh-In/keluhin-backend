<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'complaint_id',
        'admin_id',
        'message'
    ];

    /**
     * RELASI: response milik complaint
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * RELASI: response dibuat admin (user)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}