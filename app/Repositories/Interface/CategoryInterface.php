<?php

namespace App\Repositories\Interface;

use App\Models\Category;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;

interface CategoryInterface
{
    /**
     * @param  \App\Http\Requests\CategoryCreateRequest  $request
     * @return \App\Models\Category
     */
    public function create(CategoryCreateRequest $request): \App\Models\Category;

    /**
     * @param  \App\Http\Requests\CategoryUpdateRequest  $request
     * @param  \App\Models\Category  $category
     * @return \App\Models\Category
     */
    public function update(CategoryUpdateRequest $request, Category $category): \App\Models\Category;

    /**
     * @param  \App\Models\Category  $category
     * @return  boolean
     */
    public function delete(Category $category): bool;
}
