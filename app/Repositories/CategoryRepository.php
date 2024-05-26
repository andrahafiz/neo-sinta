<?php

namespace App\Repositories;

use App\Models\Category;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Repositories\Interface\CategoryInterface;

class CategoryRepository implements CategoryInterface
{
    /**
     * @var \App\Models\Category
     */
    protected $categoryModel;

    /**
     * @param  \App\Models\Category  $categoryModel
     */
    public function __construct(
        Category $categoryModel,
    ) {
        $this->categoryModel = $categoryModel;
    }

    /**
     * @param  \App\Http\Requests\CategoryCreateRequest  $request
     * @return \App\Models\Category
     */
    public function create(CategoryCreateRequest $request): Category
    {
        $input = $request->safe([
            'name',
        ]);

        $category = $this->categoryModel->create([
            'category' => $input['name'],
            'slug'     => Str::slug($input['name']),
        ]);

        Logging::log("CREATE CATEGORY", $category);

        return $category;
    }

    /**
     * @param  \App\Http\Requests\CategoryUpdateRequest  $request
     * @param  \App\Models\Category  $category
     * @return \App\Models\Category
     */
    public function update(CategoryUpdateRequest $request, Category $category): Category
    {
        $input = $request->safe([
            'name',
        ]);

        $category->update([
            'category'    => $input['name'] ?? $category->category,
        ]);

        Logging::log("UPDATE CATEGORY", ["changes" => $category->getChanges(), "category" => $category]);

        return $category;
    }

    /**
     * @param  \App\Models\Category  $category
     * @return \App\Models\Category
     */
    public function delete(Category $category): bool
    {
        Logging::log("DELETE CATEGORY", $category);
        $category = $category->delete();
        if (!$category) {
            return $category;
        }

        return $category;
    }
}
