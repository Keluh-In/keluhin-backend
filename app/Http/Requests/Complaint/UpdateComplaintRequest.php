<?php

namespace App\Http\Requests\Complaint;

use App\Http\Requests\ApiFormRequest;

class UpdateComplaintRequest extends ApiFormRequest
{
    /**
     * hanya pemilik complaint boleh update
     * (logic tambahan bisa di controller/service)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * VALIDASI UPDATE COMPLAINT
     * (lebih fleksibel dari store)
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_anonymous' => 'nullable|boolean',
        ];
    }

    /**
     * ERROR MESSAGE
     */
    public function messages(): array
    {
        return [
            'category_id.exists' => 'Kategori tidak ditemukan',
            'image.image' => 'File harus gambar',
            'image.mimes' => 'Format harus jpg, jpeg, png',
            'image.max' => 'Maksimal 2MB',
        ];
    }
}
