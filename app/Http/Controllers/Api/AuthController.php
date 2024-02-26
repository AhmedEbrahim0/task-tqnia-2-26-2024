<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\VerficationMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ResponseTrait;
    public function login(Request $request){
        $validator=Validator::make($request->all(),[
            "email"=>"required|string",
            "password"=>"required|string",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);


        $user=User::where("email",$request->email)->first();

        if(! $user || ! Hash::check($request->password, $user->password)   )
            return  $this->Response(null,"Bad Creds",401);

        $token=$user->createToken('token-name', ['*'], now()->addWeek())->plainTextToken;
        $response=[
            'user'=>$user,
            "token"=>$token,
        ];
        return $this->Response($response,"Login Successfully",200);
    }
    public function register(Request $request){
        $validator=Validator::make($request->all(),[
            "name"=>"required|string",
            "phone"=>"required|numeric|digits:11|unique:users,phone",
            "email"=>"required|string|unique:users,email",
            "password"=>"required|string|confirmed",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);

        $user=User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>bcrypt($request->password),
            "phone"=>$request->phone,
        ]);
        $code=$user->id . rand(10000,99999);
        Mail::to($user->email)->send(new VerficationMail($code))  ;
        $user->update([
            "code"=>$code,
        ]);
        $token=$user->createToken('token-name', ['*'], now()->addWeek())->plainTextToken;
        $response=[
            'user'=>$user,
            "token"=>$token,
        ];
        return $this->Response($response,"Register Successfully",200);
    }
    public function logout(Request $request)  {
        $request->user()->tokens()->delete();
        return $this->Response(null,"Logout",200);

    }
}
