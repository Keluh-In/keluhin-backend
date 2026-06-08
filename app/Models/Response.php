<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Response extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'complaint_id',
        'admin_id',
        'message'
    ];

    /**
<<<<<<< HEAD
     * RELASI: Response milik Complaint
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }

    /**
     * RELASI: Response dibuat admin (User)
=======
     * RELASI: response milik complaint
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * RELASI: response dibuat admin (user)
>>>>>>> origin/main
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> origin/main
