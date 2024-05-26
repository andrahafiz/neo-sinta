<?php

namespace App\Repositories;

use Exception;
use App\Models\Cart;
use App\Models\Product;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CartCreateRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Repositories\Interface\CartInterface;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CartRepository implements CartInterface
{
    /**
     * @var \App\Models\Cart
     */
    protected $cartModel;

    /**
     * @var Product
     */
    protected $productModel;

    /**
     * @param  \App\Models\Cart  $cartModel
     */
    public function __construct(
        Cart $cartModel,
        Product $productModel,
    ) {
        $this->cartModel = $cartModel;
        $this->productModel = $productModel;
    }

    /**
     * @param  \App\Http\Requests\CartCreateRequest  $request
     * @return \App\Models\Cart
     */
    public function create(CartCreateRequest $request): \App\Models\Cart
    {
        $input = $request->safe([
            'products_id', 'qty'
        ]);

        $product = $this->productModel->find($input['products_id']);

        if ($product->stock < (int) $input['qty']) {
            throw new HttpException(500, 'Stok tidak cukup');
            // throw ValidationException::class;
        }
        $cart = $this->cartModel->select('qty')
            ->where('products_id', $input['products_id'])
            ->where('users_id', Auth::user()->id)
            ->first();

        $qty =  $request->has('qty') ? (int) $input['qty'] : 1;

        $cart = $this->cartModel->updateOrCreate([
            'products_id'   => $input['products_id'],
            'users_id'      => Auth::user()->id
        ], [
            'qty' => empty($cart) ? $qty : $cart->qty + $qty
        ]);

        Logging::log("DELETE CART", $cart);
        return $cart;
    }

    /**
     * @param  \App\Models\Cart  $cart
     * @return bool
     */
    public function delete(Cart $cart): bool
    {
        Logging::log("DELETE CART", $cart);
        $cart = $cart->delete();
        if (!$cart) {
            return $cart;
        }
        return $cart;
    }
}
