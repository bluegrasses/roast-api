<?php

namespace App\Http\Controllers;

use App\Models\BrewMethod;
use App\Traits\ApiResponse;

class BrewMethodsController extends Controller
{
    use ApiResponse;
    //
    public function getBrewMethods()
    {
        $collection = BrewMethod::withCount('cafes')->get();
        return $this->sendSuccess($collection,'brew methods list success');

    }
}
