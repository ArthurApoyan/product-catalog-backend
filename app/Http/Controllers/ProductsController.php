<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Product::query();

            if (isset($request['category'])) {
                $query->where('category_id', $request['category']);
            }

            if (isset($request['pattern'])) {
                $query->where('name', 'like', '%' . $request['pattern'] . '%');
            }

            $products = $query->with(['category'])->get();

            return response()->json(["products" => $products]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $product = Product::query()->find($id);

            if (!$product) {
                return response()->json(['error' => 'Product not found.']);
            }

            return response()->json(['product' => $product]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function getBagProductsByIds(Request $request)
    {
        try {
            $ids = isset($request['ids']) ? $request['ids'] : null;
            $products = null;

            if ($ids) {
                $products = Product::query()->whereIn('id', $ids)->get();
            }

            if ($products) {
                return response()->json(["products" => $products]);
            } else {
                return response()->json(["error" => 'Products not found']);
            }
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

}
