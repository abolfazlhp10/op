<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConttactController extends Controller
{

    public function sendemail(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $message = $request->input('message');

        // ارسال ایمیل
          Mail::raw($message, function ($message) use ($name, $email) {
            $message->to('alisaadatman27@gmail.com');
            $message->subject('پیام ارسالی از فرم تماس با ما');
            $message->from($email, $name);
        });

        return response()->json(['message' => 'پیام شما با موفقیت ارسال شد.'], 201);
    }
}
