<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    use ApiResponse;
    //
    public function index()
    {
        $collection = Product::all();
        return $this->sendSuccess($collection, 'Products retrieved successfully.');

    }
}
