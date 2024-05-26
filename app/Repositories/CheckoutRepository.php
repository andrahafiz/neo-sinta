<?php

namespace App\Repositories;

use Exception;
use App\Models\Cart;
use App\Contracts\Logging;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CheckoutStoreRequest;
use App\Repositories\Interface\CheckoutInterface;

class CheckoutRepository implements CheckoutInterface
{
    /**
     * @var \App\Models\Cart
     */
    protected $cartModel;

    /**
     * @var \App\Models\Transaction
     */
    protected $transacionModel;

    /**
     * @var \App\Models\TransactionDetail
     */
    protected $transacionDetailModel;

    /**
     * @param  \App\Models\Cart  $cartModel
     * @param  \App\Models\Transaction  $transacionModel
     * @param  \App\Models\TransactionDetail  $transacionDetailModel
     */
    public function __construct(
        Cart $cartModel,
        Transaction $transacionModel,
        TransactionDetail $transacionDetailModel,
    ) {
        $this->cartModel = $cartModel;
        $this->transacionModel = $transacionModel;
        $this->transacionDetailModel = $transacionDetailModel;
    }

    /**
     * @param  \App\Http\Requests\CheckoutStoreRequest  $request
     * @return \App\Models\Transaction
     */
    public function checkout(CheckoutStoreRequest $request): Transaction
    {
        $input = $request->safe(['money']);

        $user = Auth::user()->id;

        $transaction = $this->transacionModel->create([
            'users_id' => $user
        ]);

        $totalCheckout = 0;
        foreach ($this->cart() as $cart) {
            $this->transacionDetailModel->create([
                'transactions_id'   => $transaction->id,
                'products_id'       => $cart->products_id,
                'qty'               => $cart->qty,
                'total'             => $cart->product->price * $cart->qty
            ]);

            $totalCheckout += $cart->product->price *  $cart->qty;

            if ($cart->product->stock - $cart->qty < 0) {
                throw new Exception('Stok tidak cukup');
            }

            $cart->product()->update([
                'stock' => $cart->product->stock - $cart->qty
            ]);
        }

        $kembalian = abs($totalCheckout - $input['money']);
        if ($totalCheckout > $input['money']) {
            throw new Exception('Uang Tidak Cukup');
        }

        $transaction->update([
            'total_price'   => $totalCheckout,
            'money'         => $input['money'],
            'change'        => $kembalian
        ]);

        $this->cartModel->with(['product', 'user'])
            ->where('users_id', $user)
            ->delete();

        Logging::log("CREATE TRANSACTION", $transaction);
        return $transaction;
    }

    private function cart()
    {
        $carts = $this->cartModel->with(['product', 'user'])
            ->where('users_id', Auth::user()->id)
            ->get();

        return $carts;
    }
}
