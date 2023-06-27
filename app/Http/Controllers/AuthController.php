<?php

namespace App\Http\Controllers;
use App\Models\Card;
use App\Models\TrainerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use apiResponse;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }

    public function register(Request $request){
        $request->validate([
            'roll_number' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birth_day' => 'required',
            'branch_id' => 'required|integer',
            'phone_number' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
//        $branchId = Auth::user()->branch_id;
        $user = User::create([
            'roll_number' => $request->roll_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_day' => $request->birth_day,
            'branch_id' => $request->branch_id,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        $barcode = mt_rand(000000000,999999999);
        if ($this->barcodeExist($barcode)){
            $barcode = mt_rand(000000000,999999999);
        }
        $cardReq= new Request(
            ['user_id' => $user->id,
            'barcode' => $barcode,
            'branch_id' => $request->branch_id,
        ]);
        $card = ( new CardController)->store($cardReq);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'card' => $card,
             'authorisation' => [
                 'token' => $token,
                 'type' => 'bearer',
             ]
        ]);
    }

    public function barcodeExist($barcode){
        return Card::whereBarcode($barcode)->exists();
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }



    public function resetPassword(Request $request){
        $id = Auth::user()->id;
        $myAccount = User::find($id);
        if(!$myAccount)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);
        }

        $validation = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);
        }

        $myAccount->update([
            'password' => Hash::make($request->password),
        ]);
        if($myAccount)
        {
            return $this->traitResponse($myAccount , 'Password is updated Successfully',200);

        }
        return $this->traitResponse(null,'Updated Failed',400);
    }



}
