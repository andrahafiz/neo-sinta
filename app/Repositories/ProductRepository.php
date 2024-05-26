<?php

namespace App\Repositories;

use App\Models\Product;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\Interface\ProductInterface;


class ProductRepository implements ProductInterface
{
    /**
     * @var \App\Models\Product
     */
    protected $productModel;

    /**
     * @param  \App\Models\Product  $productModel
     */
    public function __construct(
        Product $productModel,
    ) {
        $this->productModel = $productModel;
    }

    /**
     * @param  \App\Http\Requests\ProductCreateRequest  $request
     * @return \App\Models\Product
     */
    public function create(ProductCreateRequest $request): Product
    {
        $input = $request->safe([
            'nama', 'category', 'deskripsi', 'stok', 'harga', 'image'
        ]);

        $photo = $request->file('image');
        if ($photo instanceof UploadedFile) {
            $rawPath = $photo->store('public/photos/product');
            $path = str_replace('public/', '', $rawPath);
        }

        $product = $this->productModel->create([
            'name_product' => $input['nama'],
            'slug' => Str::slug($input['nama']),
            'categories_id' => $input['category'],
            'description' => $input['deskripsi'],
            'stock' => $input['stok'],
            'price' => $input['harga'],
            'image' => $path ?? 'no-image.jpg'
        ]);

        Logging::log("CREATE PRODUCT", $product);

        return $product;
    }

    /**
     * @param  \App\Http\Requests\ProductUpdateRequest  $request
     * @param  \App\Models\Product  $product
     * @return \App\Models\Product
     */
    public function update(ProductUpdateRequest $request, Product $product): Product
    {

        $input = $request->safe([
            'nama', 'category', 'deskripsi', 'stok', 'harga', 'image'
        ]);

        $photo = $request->file('image');
        if ($photo instanceof UploadedFile) {
            $file_path = storage_path() . '/app/' . $product->image;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filename = $photo->store('public/photos/product');
        } else {
            $filename = $product->image;
        };

        $product->update([
            'name_product' => $input['nama'] ?? $product->name_product,
            'slug' => Str::slug($input['nama']) ?? $product->slug,
            'categories_id' => $input['category'] ?? $product->categories_id,
            'description' => $input['deskripsi'] ?? $product->description,
            'stock' => $input['stok'] ?? $product->stock,
            'price' => $input['harga'] ?? $product->price,
            'image' => $filename
        ]);

        Logging::log("UPDATE PRODUCT", ["changes" => $product->getChanges(), "product" => $product]);

        $product->load('categories');
        return $product;
    }


    /**
     * @param  \App\Models\Product  $product
     * @return \App\Models\Product
     */
    public function delete(Product $product): bool
    {
        Logging::log("DELETE PRODUCT", $product);
        $product = $product->delete();
        return $product;
    }
}
