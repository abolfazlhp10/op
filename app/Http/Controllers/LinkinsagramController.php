<?php

namespace App\Http\Controllers;

use App\Http\Resources\LinkinsagramResource;
use App\Models\linkinsagram;
use Illuminate\Http\Request;

class LinkinsagramController extends Controller
{
    public function store(Request $request)
    {

        $link = linkinsagram::query()->create([
            'link' => $request->get('link'),
        ]);

        return response()->json([
            'data' => new LinkinsagramResource($link)
        ])->setStatusCode(201);
        }



    public function update(linkinsagram $linkinsagram,Request $request)
    {



        $linkinsagram->update([
            'link' => $request->get('link'),
         ]);


        return response()->json([
            'data' => new LinkinsagramResource($linkinsagram)
        ])->setStatusCode(200);;
    }


    public function show()
    {

        return response()->json([
            'data' => LinkinsagramResource::collection(linkinsagram::paginate(1)),


        ])->setStatusCode(200);

    }
}
