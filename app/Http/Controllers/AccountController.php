<?php

namespace App\Http\Controllers;

use App\Models\location;
use App\Models\transrecord;
use App\Models\User;
use App\Models\wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
//use Excel;
use App\Imports\MoviesImport;
use Maatwebsite\Excel\Facades\Excel;
use JWTAuth;

class AccountController extends Controller
{
    //


    public function getallusers()
    {


        $Users = User::all();
        if (count($Users) > 0) {

            return response()->json(
                [
                    'success' => true,
                    'message' => 'successful',
                    'data' =>  $Users
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Job Not Found'
                ],
                404
            );
        }
    }


    public function getuser($id)
    {


        $User =  User::where('id', $id)->with('wallet')->with('transrecord')->get();

        if (count($User) > 0) {

            return response()->json(
                [
                    'success' => true,
                    'message' => 'successful',
                    'data' =>  $User
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User  Not Found'
                ],
                404
            );
        }
    }

    public function getallwallet()
    {

        $wallets = wallet::all();
        if (count($wallets) > 0) {

            return response()->json(
                [
                    'success' => true,
                    'message' => 'successful',
                    'data' =>  $wallets
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No  Wallet Found'
                ],
                404
            );
        }
    }

    public function getwallet($id)
    {
        $wallet =  wallet::where('id', $id)->with('wallettype')->with('transrecord')->with('user')->get();

        if (count($wallet) > 0) {

            return response()->json(
                [
                    'success' => true,
                    'message' => 'successful',
                    'data' =>  $wallet
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Wallet Not Found'
                ],
                404
            );
        }
    }
    public function count()
    {

        $Users = User::all();
        $wallets = wallet::all();
        $trans = transrecord::all();

        return response()->json(
            [
                'success' => true,
                'message' => 'successful',
                'data' =>  ["total users " => count($Users), "total wallet" => count($wallets), "volume of transaction" => count($trans)]
            ],
            200
        );
    }
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_wallet_id' => 'required',

            'amount' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $validator->errors()
                ],
                400
            );
        }

        $user_id  = JWTAuth::parseToken()->authenticate()->id;
        $r_wallet_id = $request->get('receiver_wallet_id');
        $amount = $request->get('amount');

        $recieverwallet = wallet::all()->where('id', $r_wallet_id)->first();

      if($recieverwallet == null){


        return response()->json([
            'success' => false,
            'message' => 'Reciever Wallet Not Found.',

        ], 400);
      }

      $senderwallet = wallet::where('user_id', $user_id)->first();



      if($senderwallet){

        $balance  = wallet::where('user_id', $user_id)->where('balance', '>', $amount)->get();

       // return $balance;


//return $senderwallet;
if( count($balance) > 0){


    //////////////Update Sender And Receiver Wallet After funding Wallet.



}
else{
    return response()->json([
        'success' => false,
        'message' => ' Transaction Failed Due to insuffient balalnce .',

    ], 400);
}



      } else{

      return response()->json([
        'success' => false,
        'message' => 'Wallet not found. Pls Create Wallet .',

    ], 400);
      }


    }

    public function upload(Request $request)
    {


        if (request()->hasFile('file')) {

            $save =  Excel::import(new MoviesImport, request()->file('file')->store('temp'));

            if ($save) {

                $Lagos =  location::where('state', 'Lagos')->select('lga')->pluck('lga');
                $Ogun =  location::where('state', 'Ogun')->select('lga')->pluck('lga');

                if (count($Lagos) > 0) {

                    return response()->json(
                        [
                            'success' => true,
                            'message' => 'successful',
                            'data' => ["Lagos" => $Lagos, "Ogun" => $Ogun]
                        ],
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'User  Not Found'
                        ],
                        404
                    );
                }
            }
        } else {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Pls Select a File to Upload',
                    'data' =>  null
                ],
                200
            );
        }
    }




    public function createwallet(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',

            'wallettype' => 'required|numeric|min:1|max:2'
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $validator->errors()
                ],
                400
            );
        }

        $user_id = $request->get('user_id');

        $user = User::all()->where('id', $user_id)->first();
        if ($user == null) {

            return response()->json([
                'success' => false,
                'message' => 'invalid User Id',

            ], 400);
        }
        $wallettype = $request->get('wallettype');
        $existingwallet = wallet::all()->where('user_id', $user_id)->where('wallettype_id', $wallettype)->first();
        if ($existingwallet) {

            return response()->json([
                'success' => false,
                'message' => 'Wallet Tpe Already Exist',

            ], 400);
        }

        if ($wallettype = 1) {
            $walletname = 'savings';
            $interes_rate = 0.09;
        }
        if ($wallettype = 2) {
            $walletname = 'current';
            $interes_rate = 0.06;
        }
        $wallet = wallet::create([

            'name' => $walletname,
            'mini_balance' => 0,
            'balance' => 0,
            'interest_rate' => $interes_rate,
            'wallettype_id' => $request->get('wallettype'),
            'user_id' => $user_id
        ]);

        if ($wallet) {

            return response()->json([
                'success' => true,
                'message' => 'Wallet Successfuly Successful',

            ], 201);
        }
    }
}
