<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Email or Password does not exist'], 401);
        }
        if($credentials==true){
            $id  = Auth::id();
            User::where("id", $id)->update(["is_online" => 1]);
        }
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'email' => 'required | unique:users',
            'image' => 'required | image |mimes:jpeg,png,jpg',

        ]);

        if ($validator->fails()) {
            return Response::json(['error' => $validator->errors()->all()], 409);
        }

        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();
        //storing image
        $file = $request->file('image');
        $extentsion = $file->getClientOriginalExtension();
        $path = $request->file('image')->storeAs('user/proimages/', $user->id . '.' . $extentsion);
        $user->image = "http://127.0.0.1:8000/storage/" . $path;
        $user->imgname = $user->id . '.' . $extentsion;
        $user->imgpath = $path;
        $user->imgtype = $file->getClientMimeType();
        $user->save();
        return Response::json(['message' => "Account create Successfully"]);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return Response::json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $id = auth()->user()->id;
        User::where("id", $id)->update(["is_online" => 0]);
        Auth::logout();
        return Response::json(['message' => 'Successfully logged out',$id]);
    }
    

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => auth()->user() //or use the me() method
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
