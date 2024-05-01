<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewMenuRequest;
use App\Http\Resources\MenuResource;
use App\Models\menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{

    public function store(NewMenuRequest $request)
{
    $category = menu::query()->create([
        'title' => $request->get('title'),
  ]);

    return response()->json([
        'data' => new MenuResource($category)
    ])->setStatusCode(201);
}



    public function update(menu $menu, NewMenuRequest $request)
    {
         $menu->update([
            'title' => $request->get('title'),
         ]);

        return response()->json([
            'data' =>  new MenuResource($menu)
        ])->setStatusCode(200);

    }


    public function showadmin()
    {

        return response()->json([
            'data' =>DB::select('SELECT * FROM `menus`')
        ])->setStatusCode(200);

    }


    public function showclint()
    {

        return response()->json([
            'data' =>DB::select('SELECT `title` FROM `menus`')
        ])->setStatusCode(200);

    }


    public function destroy(menu $menu){
        $menu->delete();
        return response()->json([
            'message' => 'deleted menu'
        ])->setStatusCode(200);

    }



}
