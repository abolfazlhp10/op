<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogoResource;
use App\Models\logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoController extends Controller
{
    public function store(Request $request){
        $path = $request->pic->storeAs('public/logos', $request->pic->getClientOriginalName());

        $logos = logo::query()->create([

          'pic' => $path,
      ]);


      return response()->json([
        'data' => new LogoResource($logos)
    ])->setStatusCode(201);

    }



    public function update(logo $logo,Request $request)
    {


        if ($request->hasFile('pic')) {
            $path = $request->pic->storeAs('public/logos', $request->pic->getClientOriginalName());
            Storage::delete($logo->pic);
        } else {
            $path = $logo->pic;
        }

        $logo->update([

            'pic' => $path,
        ]);


        return response()->json([
            'data' => new LogoResource($logo)
        ])->setStatusCode(200);;
    }


    public function show()
    {

        return response()->json([
            'data' => LogoResource::collection(logo::paginate(1)),


        ])->setStatusCode(200);

    }
}
