<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    // Handler to qyery data from user table
    public function getData(){

        $data = DB::table('user')
                    ->select('user_id','first_name', 'last_name', 'email', 'phone', 'position')
                    ->get();

        return response()->json($data);

    }

    
        //handler to insert data in to user table
    public function store(Request $request){
         
        //validation of user form data
        $validator = Validator::make($request->all(),[
            'firstName'=>'required|alpha',
            'lastName'=> 'required|alpha',
            'email'=>'required|email|max:30',
            'phoneNumber'=>'required|alpha_dash|min:10',
            'position'=>'required',
            'password'=>'required'
        ]); 

        if($validator->fails()){
            return response()->json([
                'validate_err'=>$validator->messages()
            ]);
        }
        else{

        $data = $request->json()->all();//obtain the json array
        //retrieving the contents of the array
        $firstname = $data['firstName'];
        $lastname = $data['lastName'];
        $email = $data['email'];
        $phone = $data['phoneNumber'];
        $position = $data['position'];
        $password = $data['password'];
        
        $hashed_password = Hash::make($password);
            
        //inserting into the user table        
        $result = DB::table('user')->insert(
            ['first_name'=> $firstname,'last_name'=>$lastname,'phone'=>$phone,'email'=>$email, 'position'=>$position,'user_password'=>$hashed_password] 
        );
         if(!$result){
             return response()->json([
                 'status'=>400,
                'message'=>'something went wrong'
             ]);
       
         }
         else{
         return response()->json([
                 'status'=>200,
                 'message'=>'record inserted successfully',
             ]);
         }
        }
      
    }

    public function remove($id){
        $deleted = DB::table('user')
                        ->where('user_id',$id)
                        ->delete();
       return response()->json([
        'message'=> 'record deleted',
        'affected rows'=>$deleted
       ]);
    }

    //login function
    public function authenticate (Request $request){
        
      $userData = $request->json()->all();
      $email = $userData['email'];
      $userpassword = $userData['password'];
      $result = DB::table('user')
                        -> select('email','user_password')
                        ->where('email','=',$email)
                        ->get();
        $queryResults = json_decode($result);
        $userPassword = $queryResults[0]->user_password;
        if($result->isEmpty()){
            return response()->json('user not registered');
        }
        else if (Hash::check($userpassword, $userPassword)){
            return response()->json([
            'message'=>"success"
            ]);
        }else{
            return response()->json('invalid username or password');
        }
      
    }

   

}
