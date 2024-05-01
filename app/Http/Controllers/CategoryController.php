<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function store(Request $request)
{
    $category = category::query()->create([
        'name' => $request->get('name'),
  ]);

    return response()->json([
        'data' => new CategoryResource($category)
    ])->setStatusCode(201);
}



    public function update(category $category, Request $request)
    {
         $category->update([
            'name' => $request->get('name'),
         ]);

        return response()->json([
            'data' =>  new CategoryResource($category)
        ])->setStatusCode(200);

    }


    public function showadmin()
    {

        return response()->json([
            'data' =>DB::select('SELECT * FROM `categories`')
        ])->setStatusCode(200);

    }


    public function showclint()
    {

        return response()->json([
            'data' =>DB::select('SELECT `title` FROM `categories`')
        ])->setStatusCode(200);

    }


    public function destroy(category $category){
        $category->delete();
        return response()->json([
            'message' => 'deleted category'
        ])->setStatusCode(200);

    }

    public function showcatrgory(Category $category)
{
    $articles = $category->articles;
    return response()->json($articles);
}


}
