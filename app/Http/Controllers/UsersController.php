<?php
/**
 * Created by PhpStorm.
 * User: fisher
 * Date: 2019-02-22
 * Time: 21:25
 */

namespace App\Http\Controllers;

use Validator;
use App\User;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Webpatser\Uuid\Uuid;

class UsersController extends Controller
{
    public function register(Request $request) {
        $check = User::where('email', $request['email'])->first();
        if($check === null){
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]);
            $new = new User;
            $new->name = $request['name'];
            $new->email = $request['email'];
            $new->password = app('hash')->make($request['password'], $options = []);
            $new->suffix = substr(Uuid::generate(4)->string, 0 , 6);
            $new->save();
            $payload = [
                'iss' => "lumen-jwt",
                'sub' => $new->id,
                'iat' => time(),
                'exp' => time() + 60 * 24 * 60 * 60,
                'suffix' => $new->suffix,
                'type' => 'free',
            ];

            return response()->json(['token' => JWT::encode($payload, env('JWT_SECRET')), 'message' => 'okay'], 200);
        }
        return response()->json(['error' => 'User exists'], 400);
    }



    public function getInfo(Request $request, User $user){
        $owner = $request->auth->id;
        if($owner !== $user->id){
            return response()->json(['error' => 'You aren\'t authorized to do that!']);
        }
//        $user->layouts = $user->layouts;
        return response($user, 200);
    }


}