<?php

namespace App\Http\Requests\Complaint;

use App\Http\Requests\ApiFormRequest;

class StoreComplaintRequest extends ApiFormRequest
{
    /**
     * Semua user login boleh create complaint
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * VALIDASI CREATE COMPLAINT
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_anonymous' => 'nullable|boolean',
        ];
    }

    /**
     * MESSAGE ERROR (UI FRIENDLY)
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul wajib diisi',
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'description.required' => 'Deskripsi wajib diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
