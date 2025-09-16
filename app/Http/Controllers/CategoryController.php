<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        // // We only want to fetch the top-level categories (where parent_id is null).
        // // We also "eager load" the 'children' relationship for each parent category.
        // // This is much more efficient than running a separate query for each parent's subcategories.
        // $categories = Category::whereNull('parent_id')
        //                       ->with('children')
        //                       ->get();

        // // We will transform the data to match the JSON structure the frontend expects.
        // // The frontend 'job_category_form.html' was designed for a 'subcategories' key.
        // $formattedCategories = $categories->map(function ($category) {
        //     return [
        //         'id' => $category->id,
        //         'name' => $category->name,
        //         // We rename the 'children' relationship to 'subcategories' for frontend consistency.
        //         'subcategories' => $category->children->map(function ($child) {
        //             return [
        //                 'id' => $child->id,
        //                 'name' => $child->name,
        //             ];
        //         }),
        //     ];
        // });

        // return response()->json($formattedCategories);

        // New approach after separating SubCategory model
        $categories = \App\Models\Category::with('children')->get();
        return response()->json($categories);

    }

    public function getSubcategories(Request $request)
    {
        // \Log::info('Subcategory request received', [
        //     'mainCategory_id' => $request->input('mainCategory_id'),
        //     'all_input' => $request->all(),
        // ]);

        $mainCategoryId = $request->input('mainCategory_id');

        $query = SubCategory::query()->select('*');
        if ($mainCategoryId) {
            $query->where('parent_id', $mainCategoryId);
        }

        $subcategories = $query->get();

        // Return JSON instead of redirect
        return response()->json([
            'subcategories' => $subcategories
        ]);
    }
}
