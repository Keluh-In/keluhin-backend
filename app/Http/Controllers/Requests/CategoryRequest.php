<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * AUTHORIZE (MVP: hanya admin nanti bisa dikontrol di middleware)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * VALIDATION RULES
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name',
        ];
    }

    /**
     * CUSTOM MESSAGE (biar enak di UI admin)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi',
            'name.string' => 'Nama kategori harus berupa teks',
            'name.max' => 'Nama kategori maksimal 100 karakter',
            'name.unique' => 'Kategori sudah ada'
        ];
    }
}