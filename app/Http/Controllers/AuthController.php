<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\CareerProfile;
use App\Models\EmployerProfile;
use App\Models\Education;
use App\Models\Projects;
use App\Models\Experience;
use App\Models\role;
use App\Models\wallet;

class AuthController extends Controller
{
    //



    public function FindUsername($name)
    {
        //


      $user = User::all()->where('name',$name)->first();
      if( $user !==null){

       return response()->json([
        'success'=>true,
        'userexist'=>true,
        'callregister'=>false,
        'data'=>$user]
      , 200);

      }
     else{
        return response()->json([
        'success'=>true,
        'userexist'=>false,
        'callregister'=>true,
        'message'=> 'User Not Found']
       , 200);

     }

    }

    public function FindEmail($email)
    {
        //


      $user = User::all()->where('email',$email)->first();
      if( $user !==null){

       return response()->json([
        'success'=>true,
        'userexist'=>true,
        'callusername'=>false,
        'data'=>$user]
      , 200);

      }
     else{
        return response()->json([
        'success'=>true,
        'userexist'=>false,
        'callusername'=>true,
        'message'=> 'User Not Found']
       , 200);

     }

    }


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
 public function   login(Request $request){

    $credentials = $request->only('email', 'password');

    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string',
    ]);
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }
    try{

        if (!$jwt_token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 400);
        }


        $role =  auth()->user()->role;

     //   if (auth()->user()->role_id != 3) {
         //   return response()->json([
          //      'success' => false,
         //       'message' => 'Unathorize Access ',
          //  ], 401);
       // }



    }
    catch(JWTException $e)
    {
          return response()->json([
            'success' => false,
              'message' => 'could_not_create_token'
            ], 500);

    }

    return response()->json([
      //  'profile'=> auth()->user(),
        'success' => true,
        'message' => 'login Successful',
        'token' => $jwt_token,
        'role'=>$role->role,
    ], 200);

 }



    /**
     * register api
     *
     * @return \Illuminate\Http\Response
     */

     public function GetRoles(){

         $Roles = role::all();

         return $Roles;
     }
     public function createuser (Request $request){
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'message'=> $validator->errors()]
            , 400);
        }
      if( $role = role::all()->where('role','user')->first())
      {
       $role_id = $role->id;

               $user = User::create([
            'role_id'=>$role_id,
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        }
       if($user){ $token = JWTAuth::fromUser($user);

        $user_id  = $user->id;



            return response()->json([
            'success' => true,
            'message' => 'Registration Successful',
            'token' => $token,
            'role'=>$role->role,
            'data'=> $user
        ],201);


    }else{

        return response()->json([
            'success' => false,
            'message' => 'Internal Server Error ',

        ],500);
    }

    }
    public function Logout() {
        auth()->logout();
        return response()->json(
            ['message' => 'User successfully signed out'],200);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
///

///get auth user by token
    public function getAuthUser()
    {

        $user = JWTAuth::parseToken()->authenticate();

            return response()->json([
                'data' => $user,
                "error"=>false],200);
        }
}
