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

        $cart = Cart::create([
            'name' => $request->get('name'),
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'linkinsageram' => $request->get('linkinsageram'),
            'linkedin' => $request->get('linkedin'),
        ]);

        if ($request->hasFile('pic')) {
            $cart->pic = $request->pic->store('carts');
            $cart->save();
        }

        return response()->json(["message" => "درخواست با موفقیت انجام شد"], 200);
    }



    public function update(Cart $cart, NewCartRequest $request)
    {
        $cart->update([
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'linkinsageram' => $request->linkinsageram,
            'linkedin' => $request->linkedin,
        ]);

        if ($request->hasFile('pic')) {
            Storage::delete($cart->pic);
            $cart->pic = $request->pic->store('carts');
            $cart->update();
        }

        return response()->json([
            'data' => new CartResource($cart)
        ])->setStatusCode(200);
    }


    public function show()
    {
        return response()->json(CartResource::collection(Cart::paginate(10)), 200);
    }


    public function index(Cart $cart)
    {
        return response()->json([
            'data' => new CartResource($cart),

        ])->setStatusCode(200);
    }

    public function destroy(Cart $cart)
    {
        if ($cart->pic) {
            Storage::delete($cart->pic);
        }
        $cart->delete();
        return response()->json(['message' => 'عملیات حذف با موفقیت انجام شد'], 200);
    }

    public function showOneCart($id){
        $cart=Cart::findOrFail($id);
        return $cart;
    }
}
