<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = SubCategory::query()->with('products');

        $brandId = $request->query('brand_id');
        $categoryId = $request->query('category_id');

        $categories->when($brandId, function ($query) use ($brandId) {
            return $query->where('brand_id', '=', $brandId);
        });

        $categories->when($categoryId, function ($query) use ($categoryId) {
            return $query->where('category_id', '=', $categoryId);
        });


        return response()->json([
            'status' => 'success',
            'data' => $categories->get()
        ]);
    }

    public function show($id)
    {
        $subCategory = SubCategory::with('products')->find($id);
        if (!$subCategory) {
            return response()->json([
                'status' => 'error',
                'message' => 'sub category not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $subCategory
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'sub_category' => 'required|string',
            'is_active' => 'required|boolean',
            'desc' => 'required|string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $categoryId = $request->input('category_id');
        $category = Category::find($categoryId);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        $brandId = $request->input('brand_id');
        $brand = Brand::find($brandId);
        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brand not found'
            ]);
        }

        $subCateogry = SubCategory::create($data);

        return response()->json(['status' => 'success', 'data' => $subCateogry]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'category_id' => 'integer',
            'brand_id' => 'integer',
            'sub_category' => 'string',
            'is_active' => 'boolean',
            'desc' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $subCateogry = SubCategory::find($id);
        if (!$subCateogry) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sub Category not found'
            ], 404);
        }


        $categoryId = $request->input('category_id');
        if ($categoryId) {
            $category = Category::find($categoryId);
            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found'
                ], 404);
            }
        }

        $brandId = $request->input('brand_id');
        if ($brandId) {
            $brand = Brand::find($brandId);
            if (!$brand) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Brand not found'
                ]);
            }
        }

        $subCateogry->fill($data);

        $subCateogry->save();

        return response()->json([
            'status' => 'success',
            'message' => $subCateogry
        ]);
    }

    public function destroy($id)
    {
        $subCateogry = SubCategory::find($id);

        if (!$subCateogry) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sub Category not found'
            ], 404);
        }
        $subCateogry->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Sub Category deleted'
        ]);
    }
}
