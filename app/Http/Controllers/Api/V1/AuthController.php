<?php

namespace  App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

 use App\Http\Resources\UserResource;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password)
      ]);

      $token= $user->createToken('main')->plainTextToken;

      return response()->json([
        'message' => 'user registered successfully',
       'data' => UserResource::make($user),
            'token' =>$token
      ]);
        
    }


    public function login(LoginRequest $request)
    {
        $credentials= $request->only('email','password');
        if(Auth::attempt($credentials)){
            $user=Auth::user();

            $token= $user->createToken('main')->plainTextToken;

            return response()->json([
                'message' => 'user login has been successfully',
                'data' => UserResource::make($user),
                    'token' =>$token
              ]);

        }

        return response()->json([
            'message' => 'Invalid credentials'
            
          ],401);
    }


    public function logout(){

      $user=Auth::user();
      $user->currentAccessToken()->delete();

      return response()->json([
        'message' => 'logout is done'
      ]);
    }
}
