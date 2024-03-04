<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{


    public function store(NewCartRequest $request)
    {
        $path = $request->pic->storeAs('public/carts', $request->pic->getClientOriginalName());
        $carts = Cart::query()->create([
            'name' => $request->get('name'),
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'linkinsageram' => $request->get('linkinsageram'),
            'linkedin' => $request->get('linkedin'),
            'pic' => $path,
      ]);

        return response()->json([
            'data' => new CartResource($carts)
        ])->setStatusCode(201);
    }


    public function update(Cart $cart,Request $request)
    {


        if ($request->hasFile('pic')) {
            $path = $request->pic->storeAs('public/carts', $request->pic->getClientOriginalName());
            Storage::delete($cart->pic);
        } else {
            $path = $cart->pic;
        }

        $cart->update([
            'name' => $request->get('name', $cart->name),
            'title' => $request->get('title', $cart->title),
            'description' => $request->get('description', $cart->description),
            'linkinsageram' => $request->get('linkinsageram', $cart->linkinsageram),
            'linkedin' => $request->get('linkedin', $cart->linkedin),
            'pic' => $path,
        ]);


        return response()->json([
            'data' => new CartResource($cart)
        ])->setStatusCode(200);;
    }


    public function show()
    {

        return response()->json([
            'data' => CartResource::collection(Cart::paginate(10)),


        ])->setStatusCode(200);

    }


    public function index(Cart $cart)
    {
        return response()->json([
            'data' => new CartResource($cart),

        ])->setStatusCode(200);
    }




    public function destroy(Cart $cart)
    {
        Storage::delete($cart->pic);
        $cart->delete();
        return response()->json([
            'data' => [
                'message' => 'Cart is delete'
            ]
        ])->setStatusCode(200);

    }
}
