<?php

namespace App\Repositories\Interface;

use App\Models\Transaction;
use App\Http\Requests\CheckoutStoreRequest;

interface CheckoutInterface
{
    /**
     * @param  \App\Http\Requests\CartCreateRequest  $request
     * @return \App\Models\Cart
     */
    public function checkout(CheckoutStoreRequest $request): \App\Models\Transaction;
}
