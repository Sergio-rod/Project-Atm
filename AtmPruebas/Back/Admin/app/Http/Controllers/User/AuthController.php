<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Http\Requests\RegisterRequest;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
     public function Login(Request $request){

        try{

            if (Auth::attempt($request->only('email','password'))) {
                                /** @var \App\Models\User */

                $user = Auth::user();
                $token = $user->createToken('app')->accessToken;

                return response([
                    'message' => "Successfully Login",
                    'token' => $token,
                    'user' => $user
                ],200); // States Code
            }

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ],400);
        }
        return response([
            'message' => 'Invalid Email Or Password'
        ],401);

    } // end method


 public function Register(RegisterRequest $request){

        try{

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $token = $user->createToken('app')->accessToken;

            return response([
                'message' => "Registration Successfull",
                'token' => $token,
                'user' => $user
            ],200);

            }catch(Exception $exception){
                return response([
                    'message' => $exception->getMessage()
                ],400);
            }

    } // end mehtod






}



// public function Login(Request $request){

//     try{

//         if (Auth::attempt($request->only('email','password'))) {
//             $user = Auth::user();
//             $token =  $user->createToken('app')->accesToken;
//             // $token = $user->createToken('app')->accessToken;

//             return response([
//                 'message' => "Successfully Login",
//                 'token' => $token,
//                 'user' => $user
//             ],200); // States Code
//         }

//     }catch(Exception $exception){
//         return response([
//             'message' => $exception->getMessage()
//         ],400);
//     }
//     return response([
//         'message' => 'Invalid Email Or Password'
//     ],401);

// } // end method


// public function Register(RegisterRequest $request){

//     try{

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password)
//         ]);
//         $token = $user->createToken('app')->accessToken;

//         return response([
//             'message' => "Registration Successfull",
//             'token' => $token,
//             'user' => $user
//         ],200);

//         }catch(Exception $exception){
//             return response([
//                 'message' => $exception->getMessage()
//             ],400);
//         }

// } // end mehtod





//topicos


/*

    public function Register(Request $request){
        $this->validate($request,[
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('LaravelAuthApp')->accesToken;
        return response()->json(['token'=>$token],200);
    }

    public function Login(Request $request){
      $data = [
        'email' => $request->email,
        'password' => $request->password
      ];

      if(auth()->attempt($data)){
        $token = auth()->user()->createToken('LaravelAuthApp')->accesToken;
        return response()->json(['token'=>$token],200);
      }else{
        return response()->json(['error' => 'Unauthorized'],401);
      }
    }


*/
