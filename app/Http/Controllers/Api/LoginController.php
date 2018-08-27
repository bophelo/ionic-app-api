<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;

class LoginController extends Controller
{
    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required',
        ]);

        if ($validator->fails()) 
        {
            return response()->json($validator->errors());
        }

        $credentials = $request->only('email', 'password');

        try{
            if(!$token = JWTAuth::attempt($credentials))
            {
                return response()->json(['error' => 'Invalid account details.'], 401);
            }
        }
        catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token. Please try logging in again.'], 500);
        }

        return response()->json([compact('token')], 200);
    }
}
