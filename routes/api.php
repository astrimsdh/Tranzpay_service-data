<?php

use App\Http\Controllers\BrandContoller;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPascaController;
use App\Http\Controllers\ProductPrepaidController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('brands', [BrandContoller::class, 'index']);
Route::get('brands/{id}', [BrandContoller::class, 'show']);
Route::post('brands', [BrandContoller::class, 'create']);
Route::put('brands/{id}', [BrandContoller::class, 'update']);
Route::delete('brands/{id}', [BrandContoller::class, 'destroy']);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::post('categories', [CategoryController::class, 'create']);
Route::put('categories/{id}', [CategoryController::class, 'update']);
Route::delete('categories/{id}', [CategoryController::class, 'destroy']);


Route::get('sub_categories', [SubCategoryController::class, 'index']);
Route::get('sub_categories/{id}', [SubCategoryController::class, 'show']);
Route::post('sub_categories', [SubCategoryController::class, 'create']);
Route::put('sub_categories/{id}', [SubCategoryController::class, 'update']);
Route::delete('sub_categories/{id}', [SubCategoryController::class, 'destroy']);


Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::post('products', [ProductController::class, 'create']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);
Route::post('products/getByNumber', [ProductController::class, 'getProductByNumber']);

Route::get('product_prepaid', [ProductPrepaidController::class, 'index']);
Route::get('product_prepaid/{id}', [ProductPrepaidController::class, 'show']);
Route::post('product_prepaid', [ProductPrepaidController::class, 'create']);
Route::post('product_prepaid/sku', [ProductPrepaidController::class, 'getProductBySKU']);

Route::get('product_pasca', [ProductPascaController::class, 'index']);
Route::get('product_pasca/{id}', [ProductPascaController::class, 'show']);
Route::post('product_pasca', [ProductPascaController::class, 'create']);
Route::post('product_pasca/sku', [ProductPascaController::class, 'getProductBySKU']);


Route::post('customers', [CustomerController::class, 'create']);
Route::get('customers', [CustomerController::class, 'index']);
Route::get('customers/{id}', [CustomerController::class, 'show']);
Route::put('customers/{id}', [CustomerController::class, 'update']);
Route::delete('customers/{id}', [CustomerController::class, 'destroy']);
