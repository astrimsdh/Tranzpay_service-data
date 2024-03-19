<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductPasca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductPascaController extends Controller
{
    public function index(Request $request)
    {
        $productPasca = ProductPasca::query();

        $brandId = $request->query('brand_id');
        $categoryId = $request->query('category_id');

        $productPasca->when($brandId, function ($query) use ($brandId) {
            return $query->where('brand_id', '=', $brandId);
        });

        $productPasca->when($categoryId, function ($query) use ($categoryId) {
            return $query->where('category_id', '=', $categoryId);
        });

        return response()->json([
            'status' => 'success',
            'data' => $productPasca->get()
        ]);
    }

    public function show($id)
    {
        $productPasca = ProductPasca::find($id);
        if (!$productPasca) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product pasca not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $productPasca
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->input('data');

        $insertDataProduct = [];
        $insertDataCategory = [];
        $insertDataBrand = [];
        $dataCategory = [];
        $dataBrand = [];

        //Insert data category to database 
        foreach ($data as $result) {
            $dataCategory[] = $result['category'];
            $dataBrand[] = $result['brand'];
        }

        foreach (array_unique($dataCategory) as $result) {
            $insertDataCategory[] = [
                'category' => $result,
                'type' => 'pasca',
                'is_active' => true,
            ];
        }

        Category::upsert(
            $insertDataCategory,
            ['category'],
            [
                'type',
                'is_active'
            ]
        );

        foreach (array_unique($dataBrand) as $result) {
            $insertDataBrand[] = [
                'brand' => $result,
                'is_active' => true,
            ];
        }

        Brand::upsert(
            $insertDataBrand,
            ['brand'],
            [
                'is_active'
            ]
        );

        foreach ($data as $result) {
            $categoryId = Category::where('category', '=', $result['category'])->first()->id;
            $brandId = Brand::where('brand', '=', $result['brand'])->first()->id;
            $insertDataProduct[] = [
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'product_sku' => $result['buyer_sku_code'],
                'product_name' => $result['product_name'],
                'product_category' => $result['category'],
                'product_provider' => $result['brand'],
                'product_seller' => $result['seller_name'],
                'product_transaction_admin' => $result['admin'],
                'product_transaction_fee' => $result['commission'],
            ];
        }

        ProductPasca::upsert(
            $insertDataProduct,
            ['product_sku'],
            [
                'product_name',
                'product_category',
                'product_provider',
                'product_seller',
                'product_transaction_admin',
                'product_transaction_fee',
            ]
        );

        return response()->json(['status' => 'success']);
    }

    public function getProductBySKU(Request $request)
    {

        $rules = [
            'product_sku' => 'required|string',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $sku = $request->input('product_sku');
        $product = ProductPasca::FindProductBySKU($sku)->first();
        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }
}
