<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
            'name' => ['string', 'max:255', 'sometimes', 'unique:' . Category::class . ',category,' . $this->category->id],
        ];
    }

    public function messages()
    {
        return [
            'name.string'   => 'Nama produk harus karakter',
            'name.max'      => 'Nama produk hanya boleh :max karakter',
            'name.unique'   => 'Nama kategori sudah tercatat di dalam sistem',
        ];
    }
}
