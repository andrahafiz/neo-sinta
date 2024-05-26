<?php

namespace App\Repositories\Interface;

use App\Models\Product;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;

interface ProductInterface
{
    /**
     * @param  \App\Http\Requests\ProductCreateRequest  $request
     * @return \App\Models\Product
     */
    public function create(ProductCreateRequest $request): \App\Models\Product;

    /**
     * @param  \App\Http\Requests\ProductUpdateRequest  $request
     * @param  \App\Models\Product  $product
     * @return \App\Models\Product
     */
    public function update(ProductUpdateRequest $request, Product $product): \App\Models\Product;

    /**
     * @param  \App\Models\Product  $product
     * @return  boolean
     */
    public function delete(Product $product): bool;
}
