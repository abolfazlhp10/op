<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\Sendmail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Mail\forgetPassMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{

    public function signup(CreateUserRequest $request)
    {
        if ($request->password != $request->password_confirmation) {
            return response()->json(['message' => 'پسورد و تکرار آن یکسان نیستند'], 400);
        }

        $checkEmailExists = User::where('email', $request->email)->first();

        if ($checkEmailExists) {
            return response()->json(['message' => 'ایمیل تکراری است'], 400);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($request->hasFile('image')) {
            $user->image = $request->image->store('users');
            $user->save();
        }

        //data that will send with email
        // $data = [
        //     'name' => $user->name,
        //     'email' => $user->email
        // ];

        // $this->sendEmail($user->email, 'ثبت نام در سايت اوپي تيم', $data);

        return response()->json('ثبت نام موفق', 200);
    }

    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        //if user with that email not found
        if (!$user) {
            return response()->json(['message' => 'رمز عبور و یا آدرس ایمیل اشتباه است'], 401);
        }

        //user password in db
        $passwordHash = $user->password;

        //password that user entered
        $password = $request->password;

        //if password and passwordHash not match
        if (!Hash::check($password, $passwordHash)) {
            return response()->json(['message' => 'رمز عبور و یا آدرس ایمیل اشتباه است'], 401);
        } else {

            //create token 
            $token = $user->createToken('userToken')->plainTextToken;

            return response()->json(['message' => 'با موفقیت وارد شدید', 'token' => $token, 'user' => $user], 200);
        }
    }


    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    private function sendEmail($to, $subject, $data)
    {
        Mail::to($to)->queue(new Sendmail($data, $subject));
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['data' => new UserResource($user)]);
    }

    public function update(UpdateUserRequest $request, $id)
    {

        //find someone that have this email and not this $id
        $checkEmailExists = User::where('email', $request->email)->where('id', '!=', $id)->first();

        if ($checkEmailExists) {
            return response()->json(['message' => 'this email is alreadey taken']);
        }

        if ($request->password != $request->password_confirmation) {
            return response()->json(['message' => 'passwords not match']);
        }

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($request->hasFile('image')) {

            if ($user->image != null) {
                Storage::delete($user->image);
            }

            $user->image = $request->image->store('users');
            $user->save();
        }

        return response()->json(['data' => new UserResource($user)]);
    }

    public function sendForgetLink($email)
    {

        // email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'please insert valid email address'], 422);
        }

        //check email in db
        $user = User::where('email', $email)->first();

        if ($user) {

            $token = app('auth.password.broker')->createToken($user);

            Mail::to($user->email)->queue(new forgetPassMail($user, $token));

            return response()->json(['token' => $token]);
        } else {
            return response()->json(['message' => 'the user not found'], 404);
        }
    }

    public function forgetPassword($email, $token, Request $request)
    {
        $result = DB::select('SELECT * FROM password_reset_tokens WHERE email=?', [$email]);

        $tokenHash = $result[0]->token;

        if (!password_verify($token, $tokenHash)) {
            return response()->json(['message' => 'not found'], 404);
        }

        $this->validate($request, [
            'password' => ['required'],
            'newPassword' => ['required']
        ]);

        $user = User::where('email', $email)->first();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'password changed successfully']);
    }

    public function showAllUsers()
    {
        $users = User::all();
        return response()->json(['data' => $users]);
    }
}
