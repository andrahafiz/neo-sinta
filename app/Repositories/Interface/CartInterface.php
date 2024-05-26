<?php

namespace App\Repositories\Interface;

use App\Models\Cart;
use App\Http\Requests\CartCreateRequest;
use App\Http\Requests\CartUpdateRequest;

interface CartInterface
{
    /**
     * @param  \App\Http\Requests\CartCreateRequest  $request
     * @return \App\Models\Cart
     */
    public function create(CartCreateRequest $request): \App\Models\Cart;

    /**
     * @param  \App\Models\Cart  $category
     * @return  boolean
     */
    public function delete(Cart $category): bool;
}
