<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartCreateRequest extends FormRequest
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
            'products_id'   => ['string', 'required', 'exists:products,id'],
            'qty'           => ['sometimes', 'nullable', 'integer'],
        ];
    }
    public function messages()
    {
        return [
            'products_id.required'  => 'Produk wajib diisi',
            'products_id.string'    => 'Produk harus karakter',
            'products_id.exists'    => 'Produk tidak terdaftar',
        ];
    }
}
