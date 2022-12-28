<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    use ApiResponse;
    //
    public function getTags(Request $request){
        $query=$request->input('search');
        if($query==null||$query==''){
            $tags=Tag::all();
        }else{
            $tags=Tag::where('name','like','%'.$query.'%')->get();
        }
        return $this->sendSuccess($tags,'get tags success');
    }
}
