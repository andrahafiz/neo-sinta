<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CartUpdateRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'products_id'   => ['string', 'required', 'sometimes', 'exists:products,id'],
            'qty'           => ['sometimes', 'nullable', 'integer'],
        ];
    }
    public function messages()
    {
        return [
            'products_id.string'    => 'Produk harus karakter',
            'products_id.exists'    => 'Produk tidak terdaftar',
        ];
    }
}
