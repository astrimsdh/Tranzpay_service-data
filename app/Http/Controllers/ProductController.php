<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\PrefixNumber;
use App\Models\Product;
use App\Models\ProductPasca;
use App\Models\ProductPrepaid;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();

        $subCategoryId = $request->query('sub_category_id');
        $products->when($subCategoryId, function ($query) use ($subCategoryId) {
            return $query->where('sub_category_id', '=', $subCategoryId);
        });
        return response()->json([
            'status' => 'success',
            'data' => $products->get()
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'product not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'sub_category_id' => 'required|integer',
            'product_name' => 'required|string',
            'id_product' => 'required|string',
            'price' => 'required|integer',
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

        $subCategoryId = $request->input('sub_category_id');
        $subCategory = SubCategory::find($subCategoryId);
        if (!$subCategory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sub Category not found'
            ], 404);
        }


        $product = Product::create($data);

        return response()->json(['status' => 'success', 'data' => $product]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'sub_category_id' => 'integer',
            'product_name' => 'string',
            'id_product' => 'string',
            'price' => 'integer',
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

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }


        $subCategoryId = $request->input('sub_category_id');
        if ($subCategoryId) {
            $subCategory = SubCategory::find($subCategoryId);
            if (!$subCategory) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sub Category not found'
                ], 404);
            }
        }


        $product->fill($data);

        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
        $product->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Product has been deleted'
        ]);
    }


    public function insert_product_prepaid(Request $request)
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

    public function insert_product_pasca(Request $request)
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

    public function getProductByNumber(Request $request)
    {
        $rules = [
            'customer_no' => 'required|string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $nomor = substr($request->customer_no, 0, 4);
        $getPrefix = PrefixNumber::findProviderByNumber($nomor)->first();
        if (!$getPrefix) {
            return response()->json([
                'status' => 'error',
                'message' => 'invalid no customer!'
            ], 400);
        }
        $pulsa = ProductPrepaid::findProductByProvider($getPrefix->provider, 'pulsa')->get();
        $paket_data = ProductPrepaid::findProductByProvider($getPrefix->provider, 'data')->get();

        $data = [
            'pulsa' => $pulsa,
            'paket_data' => $paket_data
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
