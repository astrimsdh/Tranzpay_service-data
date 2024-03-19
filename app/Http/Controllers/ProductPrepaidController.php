<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductPrepaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductPrepaidController extends Controller
{
    public function index(Request $request)
    {
        $productPrepaid = ProductPrepaid::query();

        $brandId = $request->query('brand_id');
        $categoryId = $request->query('category_id');

        $productPrepaid->when($brandId, function ($query) use ($brandId) {
            return $query->where('brand_id', '=', $brandId);
        });

        $productPrepaid->when($categoryId, function ($query) use ($categoryId) {
            return $query->where('category_id', '=', $categoryId);
        });

        return response()->json([
            'status' => 'success',
            'data' => $productPrepaid->get()
        ]);
    }

    public function show($id)
    {
        $productPrepaid = ProductPrepaid::find($id);
        if (!$productPrepaid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product Prepaid not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $productPrepaid
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
                'type' => 'prepaid',
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
                'product_desc' => $result['desc'],
                'product_category' => $result['category'],
                'product_provider' => $result['brand'],
                'product_type' => $result['type'],
                'product_seller' => $result['seller_name'],
                'product_seller_price' => $result['price'],
                'product_buyer_price' => ($result['price'] + 200),
                'product_unlimited_stock' => $result['unlimited_stock'],
                'product_stock' => $result['stock'],
                'product_multi' => $result['multi'],
            ];
        }

        ProductPrepaid::upsert(
            $insertDataProduct,
            ['product_sku'],
            [
                'category_id',
                'brand_id',
                'product_name',
                'product_desc',
                'product_category',
                'product_provider',
                'product_type',
                'product_seller',
                'product_seller_price',
                'product_buyer_price',
                'product_unlimited_stock',
                'product_stock',
                'product_multi',
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
        $product = ProductPrepaid::FindProductBySKU($sku)->first();
        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }
}
