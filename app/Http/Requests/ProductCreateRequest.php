<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'nama' => ['string', 'max:255', 'required', 'unique:' . Product::class . ',name_product'],
            'category' => ['required', 'exists:categories,id'],
            'stok' => ['numeric', 'required'],
            'deskripsi' => ['string', 'max:255', 'required'],
            'image' => ['sometimes', 'image', 'mimes:png,jpg,jpeg'],
            'harga' => ['numeric', 'required'],
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama produk wajib diisi',
            'nama.string'   => 'Nama produk harus karakter',
            'nama.max'      => 'Nama produk hanya boleh 255 karakter',
            'nama.uniqe'    => 'Nama produk sudah terdaftar di dalam sistem',

            'category.required' => 'Kategory produk wajib diisi',
            'category.exists'   => 'Kategory produk tidak terdaftar',

            'stok.required' => 'Stok produk wajib diisi',
            'stok.numeric'  => 'Stok produk harus angka',

            'deskripsi.required' => 'Deskripsi produk wajib diisi',
            'deskripsi.string'   => 'Deskripsi produk harus karakter',
            'deskripsi.max'      => 'Deskripsi produk hanya boleh 255 karakter',

            'image.image' => 'Harus gambar',
            'image.mimes' => 'Format gambar png, jpeg atau jpg',
            'image.size'  => 'Ukuran gambar maksimal 1 MB',

            'harga.required' => 'Harga produk wajib diisi',
            'harga.numeric'  => 'Harga produk harus angka',
        ];
    }
}
