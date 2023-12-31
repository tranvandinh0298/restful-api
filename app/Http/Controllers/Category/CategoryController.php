<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']);
        $this->middleware('auth:api')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return $this->showCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->allowedAdminAction();

        $newCategory = Category::create($request->all());

        return $this->showCategory($newCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->showCategory($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->allowedAdminAction();

        $category->fill($request->only(['name', 'description']));

        if (!$category->isDirty()) {
            return $this->errorResponse('You need to specify any diffent value to update', 422);
        }

        $category->save();

        return $this->showCategory($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->allowedAdminAction();
        
        $category->delete();

        return $this->showCategory($category);
    }
}
