<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return $this->showAll($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $newCategory = Category::create($request->all());

        return $this->showOne($newCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $category->fill($request->only(['name', 'description']));

        if (!$category->isDirty()) {
            return $this->errorResponse('You need to specify any diffent value to update', 422);
        }

        $category->save();

        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->showOne($category);
    }
}
