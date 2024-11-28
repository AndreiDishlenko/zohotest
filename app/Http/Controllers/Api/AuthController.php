<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller {

    public function __construct() {
        // $this->middleware('<auth:a></auth:a>pi', ['except' => ['login']]);
        $this->middleware('api', ['except' => ['login']]);
    }

    public function login(Request $request) {               
        // Validation
        $validator = Validator::make($request->all(), [
            'email'     =>  ['required', 'email:rfc,dns'],
            'password'  =>  ['required', Password::min(6)],
        ]);
        
        if ($validator->fails())
            return response($validator->errors(), 422);
        
        try {
            if (! $token = auth()->attempt($request->only('email', 'password')) ) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public static function auth(Request $request) {

        $credentials = [
            'email' => 'test@gmail.com',
            'password' => 'userpassword'
        ];

        if ( !Auth::attempt( $credentials, false ) ) 
            return response()->json(['password' => 'User not found or password not correct'], 401);

        $request->session()->regenerate();
        //     dd("1");
        //     // $request->session()->regenerate(); 
        //     // return redirect()->intended('dashboard');
        // }

        return response()->json("Done", 200);
        // dd(auth()->user());
    }
}
