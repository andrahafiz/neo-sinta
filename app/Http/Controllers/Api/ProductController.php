<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Logging;
use App\Models\Product;
use App\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    /**
     * @var \App\Models\Product
     */
    protected $productModel;

    /**
     * @var \App\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * @param  \App\Models\Product  $productModel
     * @param  \App\Repositories\ProductRepository  $productRepository
     */
    public function __construct(
        Product $productModel,
        ProductRepository $productRepository
    ) {
        $this->productModel = $productModel;
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $product = Product::paginate($request->query('show'));
        return Response::json(new ProductCollection($product));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateRequest $request)
    {
        $newProduct = DB::transaction(function () use ($request) {
            $newProduct = $this->productRepository
                ->create($request);
            return $newProduct;
        });

        $newProduct->load('categories');

        return Response::json(
            new ProductResource($newProduct),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load('categories');
        return Response::json(new ProductResource($product));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductUpdateRequest  $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $updatedProduct = DB::transaction(function () use ($request, $product) {
            $updatedProduct = $this->productRepository
                ->update($request, $product);
            return $updatedProduct;
        });

        return Response::json(new ProductResource($updatedProduct));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $deletedProduct = DB::transaction(function () use ($product) {
            $deletedProduct = $this->productRepository
                ->delete($product);
            return $deletedProduct;
        });

        if ($deletedProduct == false) {
            return Response::json(
                ['message' => "Data tidak bisa dihapus karena berkaitan dengan data lainnya"],
                Response::MESSAGE_UNPROCESSABLE_ENTITY,
                Response::STATUS_UNPROCESSABLE_ENTITY
            );
        }

        return Response::noContent();
    }
}
