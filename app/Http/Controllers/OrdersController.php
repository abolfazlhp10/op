<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // ذخیره فایل آپلود شده در مسیر مورد نظر
        $path = $request->pic->storeAs('public/order', $request->pic->getClientOriginalName());

        // دریافت پیام و اطلاعات کاربر
        $message = $request->get('description');
        $user = Auth::user();
        $name = $user->name;
        $email = $user->email;

        // ایجاد سفارش جدید
        $order = Order::create([
            'pic' => $path,
            'description' => $request->get('description'),
            'user_id' => $user->id,
        ]);

        // ارسال ایمیل
        Mail::raw($message, function ($message) use ($name, $email) {
            $message->to('alisaadatman27@gmail.com');
            $message->subject('پیام ارسالی از فرم تماس با ما');
            $message->from($email, $name);
        });

        // پاسخ JSON
        return response()->json([
            'data' => $order
        ])->setStatusCode(201);
    }


    public function getOrderCount(Request $request)
    {
        $user = $request->user();
        $orderCount = $user->orders()->count();

        return response()->json([
            'order_count' => $orderCount
        ]);
    }


    public function getAllOrders(Request $request)
    {
        $orders = Order::all();

        return response()->json([
            'orders' => $orders
        ]);
    }

}

