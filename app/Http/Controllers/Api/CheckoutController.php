<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Response;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\CheckoutRepository;
use App\Http\Requests\CheckoutStoreRequest;

class CheckoutController extends Controller
{
    /**
     * @var \App\Models\Transaction
     */
    protected $transactionModel;

    /**
     * @var \App\Repositories\CheckoutRepository
     */
    protected $checkoutRepository;

    /**
     * @param  \App\Models\Transaction  $transactionModel
     * @param  \App\Repositories\CheckoutRepository  $checkoutRepository
     */
    public function __construct(
        Transaction $transactionModel,
        CheckoutRepository $checkoutRepository
    ) {
        $this->transactionModel = $transactionModel;
        $this->checkoutRepository = $checkoutRepository;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\CheckoutStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CheckoutStoreRequest $request)
    {
        $newTransaction = DB::transaction(function () use ($request) {
            $newTransaction = $this->checkoutRepository
                ->checkout($request);
            return $newTransaction;
        });

        return Response::json("Checkout Sukses", Response::MESSAGE_CREATED, Response::STATUS_CREATED);
    }
}
