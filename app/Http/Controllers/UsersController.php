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
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function signup(CreateUserRequest $request)
    {
        if ($request->password != $request->password_confirmation) {
            return response()->json(['message' => 'the passwords not match']);
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
        $data = [
            'name' => $user->name,
            'email' => $user->email
        ];

        $this->sendEmail($user->email, 'ثبت نام در سايت اوپي تيم', $data);

        return $user;
    }

    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        //if user with that email not found
        if (!$user) {
            return response()->json(['message' => 'email or password is incorrect']);
        }

        //user password in db
        $passwordHash = $user->password;

        //password that user entered
        $password = $request->password;

        //if password and passwordHash not match
        if (!password_verify($password, $passwordHash)) {
            return response()->json(['message' => 'email or password is incorrect']);
        }

        return response()->json(['message' => 'you are logged in successfully']);
    }

    public function logout(Request $request){

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function sendEmail($to, $subject, $data)
    {
        Mail::to($to)->queue(new Sendmail($data, $subject));
    }


    public function next()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();

        $siteUser = User::where('email', $user->email)->first();

        if ($siteUser) {
            Auth::loginUsingId($siteUser->id);
            return response()->json(['message' => 'login successfully', 'data' => new UserResource($siteUser)]);
        } else {

            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'image' => $user->avatar,
                'password' => Hash::make('password')

            ]);

            if ($newUser) {
                $newUser->email_verified_at = now();
                $newUser->save();
            }

            $data = [
                'name' => $newUser->name,
                'email' => $newUser->email,
            ];

            $this->sendEmail($newUser->email, 'ثبت نام در سايت op team', $data);

            Auth::loginUsingId($newUser->id);
            return response()->json(['message' => 'login successfully', 'data' => new UserResource($newUser)]);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['data' => new UserResource($user)]);
    }

    public function update(UpdateUserRequest $request,$id){

        //find someone that have this email and not this $id
        $checkEmailExists=User::where('email',$request->email)->where('id','!=',$id)->first();

        if($checkEmailExists){
            return response()->json(['message'=>'this email is alreadey taken']);
        }

        if ($request->password!=$request->password_confirmation) {
            return response()->json(['message'=>'passwords not match']);
        }

        $user=User::findOrFail($id);

        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        if($request->hasFile('image')){

            if ($user->image!=null) {
                Storage::delete($user->image);
            }

            $user->image=$request->image->store('users');
            $user->save();
        }

        return response()->json(['data'=>new UserResource($user)]);

    }
}
