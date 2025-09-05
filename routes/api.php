<?php
use App\Models\SubCategory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

// Previous route that returned subcategories directly without a controller
Route::get('/categories/{category}/subcategories', function ($categoryId) {
    return SubCategory::where('parent_id', $categoryId)->get();
});

// --- MODIFIED ROUTE ---
Route::get('/categories', [CategoryController::class, 'index']);

// This route gets only the subcategories for a SPECIFIC parent category.
// The {category} is a wildcard that will capture the ID (like '1') from the URL.
Route::get('/categories/{category}/subcategories', [CategoryController::class, 'getSubcategories']);