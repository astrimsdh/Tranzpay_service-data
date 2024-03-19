<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandContoller extends Controller
{

    public function index()
    {
        $brands = Brand::all();
        return response()->json([
            'status' => 'success',
            'data' => $brands
        ]);
    }

    public function show($id)
    {
        $brand = Brand::with('sub_categories.products')->with('product_prepaids')->find($id);
        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'data brand tidak ditemukkan!'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $brand
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'brand' => 'required|string',
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

        $brand = Brand::create($data);

        return response()->json(['status' => 'success', 'data' => $brand]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'brand' => 'string',
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

        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'data brand tidak ditemukkan!'
            ], 404);
        }

        $brand->fill($data);

        $brand->save();

        return response()->json([
            'status' => 'success',
            'message' => $brand
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'data brand tidak ditemukkan!'
            ], 404);
        }

        $brand->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'data brand berhasil dihapus!'
        ]);
    }
}
