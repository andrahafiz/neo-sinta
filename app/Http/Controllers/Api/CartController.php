<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Contracts\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CartCollection;
use App\Http\Requests\CartCreateRequest;

class CartController extends Controller
{
    /**
     * @var \App\Models\Cart
     */
    protected $cartModel;

    /**
     * @var \App\Repositories\CartRepository
     */
    protected $cartRepository;

    /**
     * @param  \App\Models\Cart  $cartModel
     * @param  \App\Repositories\CartRepository  $cartRepository
     */
    public function __construct(
        Cart $cartModel,
        CartRepository $cartRepository
    ) {
        $this->cartModel = $cartModel;
        $this->cartRepository = $cartRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = Cart::with(['product', 'user'])->where('users_id',  Auth::user()->id)->get();
        return Response::json(new CartCollection($cart));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CartCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CartCreateRequest $request)
    {
        $newCart = DB::transaction(function () use ($request) {
            $newCart = $this->cartRepository
                ->create($request);
            return $newCart;
        });
        $newCart->load(['product', 'user']);

        return Response::json(
            new CartResource($newCart),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $deletedCart = DB::transaction(function () use ($cart) {
            $deletedCart = $this->cartRepository
                ->delete($cart);
            return $deletedCart;
        });

        return Response::noContent();
    }
}
