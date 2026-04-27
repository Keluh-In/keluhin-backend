<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintAttachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'complaint_id',
        'uploaded_by',
        'validated_by',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'is_validated',
        'validated_at',
        'validation_note',
    ];

    protected $casts = [
        'is_validated' => 'boolean',
        'validated_at' => 'datetime',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by')->withTrashed();
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by')->withTrashed();
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}
