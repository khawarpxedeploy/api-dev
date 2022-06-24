<?php

namespace App\Http\Controllers\api\v1\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:customers',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Customer::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
   
        return $this->sendResponse($success, 'User registered successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } 

        $user = Customer::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password) && $user->status == 1) {
                $token = $user->createToken('MyApp')->plainTextToken;
                $success['token'] = $token;
                $user->makeHidden(['id','created_at','updated_at']);
                $success['user'] = $user;
                return $this->sendResponse($success, 'User login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error'=>'Invalid credentials!']);
            }
        } else {
            return $this->sendError('Unauthorised.', ['error'=>'User does not exist']);
        }
    }
}
