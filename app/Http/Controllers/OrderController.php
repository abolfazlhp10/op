<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::all();
        return response()->json(['data' => $orders]);
    }

    public function store(Request $request, $userID)
    {
        $this->validate($request, [
            'images' => ['image', 'mimes:jpg,png,jpeg', 'max:2000', 'required'],
            'description' => ['required']
        ]);

        // ایجاد سفارش جدید
        $order = Order::create([
            'user_id' => $userID,
            'description' => 'test desc',
        ]);

        //ثبت عكس ها در ديتابيس

        foreach ($request->images as $image) {
            Image::create([
                'order_id' => $order->id,
                'image' => $image->store('orders')
            ]);
        }

        // پاسخ JSON
        return response()->json('order created successfully', 200);
    }


    public function getOrderCount(Request $request, $userID)
    {
        $user = User::findOrFail($userID);
        $orderCount = $user->orders()->count();
        return response()->json([
            'order_count' => $orderCount
        ]);
    }


    public function getAllOrders(Request $request, $userID)
    {
        $orders = Order::where('user_id', $userID)->get();

        return response()->json([
            'orders' => $orders
        ]);
    }
}
