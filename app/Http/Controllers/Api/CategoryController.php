<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Helpers\ResponseHelper;

class CategoryController extends Controller
{
    public function index()
    {
        return ResponseHelper::success(
            Category::all()
        );
    }
}