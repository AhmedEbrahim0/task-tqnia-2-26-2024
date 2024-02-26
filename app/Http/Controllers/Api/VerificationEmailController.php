<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseTrait;

class VerificationEmailController extends Controller
{
    use ResponseTrait;
    /**
     * Handle the incoming request.
     */
    public function verify(Request $request)
    {
        $user=$request->user();
        if($user->code == $request->code){
            User::find($user->id)->update([
                "is_verify"=>true,
            ]);
            return $this->Response(null,"Verified successfully",200);
        }else{
            return $this->Response(null,"The code is wrong",200);
        }
    }
}
