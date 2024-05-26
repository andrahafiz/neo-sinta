<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Contracts\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use App\Http\Resources\CategoryCollection;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    /**
     * @var \App\Models\Category
     */
    protected $categoryModel;

    /**
     * @var \App\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @param  \App\Models\Category  $categoryModel
     * @param  \App\Repositories\CategoryRepository  $categoryRepository
     */
    public function __construct(
        Category $categoryModel,
        CategoryRepository $categoryRepository
    ) {
        $this->categoryModel = $categoryModel;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = Category::when($request->has('search'), function (Builder $query) use ($request) {
            return $query->where('category', 'like', '%' . $request->query('search') . '%');
        })->paginate($request->query('show'));

        return Response::json(new CategoryCollection($category));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCreateRequest $request)
    {
        $newCategory = DB::transaction(function () use ($request) {
            $newCategory = $this->categoryRepository
                ->create($request);
            return $newCategory;
        });

        return Response::json(
            new CategoryResource($newCategory),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load('products');
        return Response::json(new CategoryResource($category));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoryUpdateRequest  $request
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $updatedCategory = DB::transaction(function () use ($request, $category) {
            $updatedCategory = $this->categoryRepository
                ->update($request, $category);
            return $updatedCategory;
        });

        return Response::json(new CategoryResource($updatedCategory));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $deletedCategory = DB::transaction(function () use ($category) {
            $deletedCategory = $this->categoryRepository
                ->delete($category);
            return $deletedCategory;
        });

        if ($deletedCategory == false) {
            return Response::json(
                ['message' => "Data tidak bisa dihapus karena berkaitan dengan data lainnya"],
                Response::MESSAGE_UNPROCESSABLE_ENTITY,
                Response::STATUS_UNPROCESSABLE_ENTITY
            );
        }

        return Response::noContent();
    }
}
