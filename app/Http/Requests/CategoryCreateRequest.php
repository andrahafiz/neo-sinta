<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['string', 'max:255', 'required', 'unique:' . Category::class . ',category'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama kategori wajib diisi',
            'name.string'   => 'Nama kategori harus karakter',
            'name.unique'   => 'Nama kategori sudah tercatat di dalam sistem',
            'name.max'      => 'Nama kategori hanya boleh :max karakter',
        ];
    }
}
