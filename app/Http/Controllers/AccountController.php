<?php

namespace App\Http\Controllers;

use App\Models\location;
use App\Models\transrecord;
use App\Models\User;
use App\Models\wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
//use Excel;
use App\Imports\MoviesImport;
use Maatwebsite\Excel\Facades\Excel;
class AccountController extends Controller
{
    //


    public function getallusers (){


        $Users = User::all();
        if( count( $Users) > 0 ){

         return response()->json([
          'success'=>true,
          'message'=>'successful',
          'data'=>  $Users]
        , 200);

        }
       else{
          return response()->json([
          'success'=>false,
          'message'=> 'Job Not Found']
         , 404);

       }

    }


    public function getuser ($id){


        $User =  User::where('id',$id)->with('wallet')->with('transrecord')->get();

        if( count( $User) >0){

         return response()->json([
          'success'=>true,
          'message'=>'successful',
          'data'=>  $User]
        , 200);

        }
       else{
          return response()->json([
          'success'=>false,
          'message'=> 'User  Not Found']
         , 404);

       }

    }

    public function getallwallet (){

        $wallets = wallet::all();
        if( count( $wallets) > 0 ){

         return response()->json([
          'success'=>true,
          'message'=>'successful',
          'data'=>  $wallets]
        , 200);

        }
       else{
          return response()->json([
          'success'=>false,
          'message'=> 'No  Wallet Found']
         , 404);

       }

    }

    public function getwallet($id){
        $wallet =  wallet::where('id',$id)->with('wallettype')->with('transrecord')->with('user')->get();

        if( count( $wallet) >0){

         return response()->json([
          'success'=>true,
          'message'=>'successful',
          'data'=>  $wallet]
        , 200);

        }
       else{
          return response()->json([
          'success'=>false,
          'message'=> 'Wallet Not Found']
         , 404);

       }
    }
    public function count(){

        $Users = User::all();
        $wallets = wallet::all();
        $trans = transrecord::all();

        return response()->json([
            'success'=>true,
            'message'=>'successful',
            'data'=>  ["total users "=>count($Users), "total wallet"=>count($wallets), "volume of transaction"=>count($trans)]
        ],
           200);



    }
    public function send(Request $request){



    }

public function upload(Request $request){


    if(request()->hasFile('file')) {

      $save =  Excel::import(new MoviesImport, request()->file('file')->store('temp'));

      if($save){

        $Lagos =  location::where('state','Lagos')->select('lga')->pluck('lga');
        $Ogun =  location::where('state','Ogun')->select('lga')->pluck('lga');

        if( count( $Lagos) >0){

         return response()->json([
          'success'=>true,
          'message'=>'successful',
          'data'=> ["Lagos"=>$Lagos, "Ogun"=>$Ogun]]
        , 200);

        }
       else{
          return response()->json([
          'success'=>false,
          'message'=> 'User  Not Found']
         , 404);

       }

      }
    }
    else{

    return response()->json([
        'success'=>false,
        'message'=>'Pls Select a File to Upload',
        'data'=>  null]
      , 200);
}
    }




public function checkUploadedFileProperties($extension, $fileSize)
{
$valid_extension = array("csv", "xlsx"); //Only want csv and excel files
$maxFileSize = 2097152; // Uploaded file size limit is 2mb
if (in_array(strtolower($extension), $valid_extension)) {
if ($fileSize <= $maxFileSize) {
} else {
throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
}
} else {
throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
}
}

        public function createwallet(Request $request){

        }

}
