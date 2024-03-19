<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query();

        $type = $request->query('type');
        $categories->when($type, function ($query) use ($type) {
            return $query->where('type', '=', $type);
        });

        return response()->json([
            'status' => 'success',
            'data' => $categories->get()
        ]);
    }

    public function show($id)
    {
        $category = Category::with('sub_categories.products')->with('product_prepaids')->with('product_pasca')->find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'data kategori tidak ditemukkan!'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'category' => 'required|string',
            'type' => 'required|in:pasca,prabayar',
            'is_active' => 'required|boolean',
            'img' => 'required|string',
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

        $category = Category::create($data);

        return response()->json(['status' => 'success', 'data' => $category]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'category' => 'string',
            'type' => 'in:pasca,prabayar',
            'is_active' => 'boolean',
            'img' => 'string',
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

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'data kategori tidak ditemukkan!'
            ], 404);
        }

        $category->fill($data);

        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'data kategori tidak ditemukkan!'
            ], 404);
        }

        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'data kategori berhasil dihapus!'
        ]);
    }
}
