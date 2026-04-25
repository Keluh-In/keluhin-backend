<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * UPLOAD IMAGE COMPLAINT
     */
    public function upload($file, $folder = 'complaints')
    {
        if (!$file) {
            return null;
        }

        $path = $file->store("uploads/{$folder}", 'public');

        return $path;
    }

    /**
     * DELETE FILE
     */
    public function delete($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}