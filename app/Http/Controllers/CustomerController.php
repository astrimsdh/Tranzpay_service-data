<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customer = Customer::query();

        $userId = $request->query('user_id');
        $customer->when($userId, function ($query) use ($userId) {
            return $query->where('user_id', '=', $userId);
        });
        return response()->json([
            'status' => 'success',
            'data' => $customer->get()
        ]);
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'customer not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $customer
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer',
            'category_id' => 'required|integer',
            'customer_name' => 'required|string',
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

        $categoryId = $request->input('category_id');
        $category = Category::find($categoryId);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        $userId = $request->input('user_id');
        $user = getUser($userId);
        if ($user['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']);
        }

        $customer = Customer::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $customer
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'user_id' => 'integer',
            'category_id' => 'integer',
            'customer_name' => 'string',
            'customer_no' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found'
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


        $userId = $request->input('user_id');
        if ($userId) {
            $user = getUser($userId);
            if ($user['status'] === 'error') {
                return response()->json([
                    'status' => $user['status'],
                    'message' => $user['message']
                ], $user['http_code']);
            }
        }

        $customer->fill($data);

        $customer->save();

        return response()->json([
            'status' => 'success',
            'message' => $customer
        ]);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found'
            ], 404);
        }
        $customer->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Customer has been deleted'
        ]);
    }
}
