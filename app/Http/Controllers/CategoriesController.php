<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoriesController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $categories = Category::all();

            return response()->json(["categories" => $categories]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

}

