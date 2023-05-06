<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    use apiResponse;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function addAdmin(Request $request){
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


        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }




    public function addEmployee(Request $request){
        $request->validate([
            'roll_number' => 'required|integer|min:3|max:4',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birth_day' => 'required',
            'branch_id' => 'required|integer',
            'phone_number' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

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


        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function search( $filter )
    {
        $branchId = Auth::user()->branch_id;
    
        $filterResult =  DB::table('users')
        ->join('branches','users.branch_id','=','branches.id')
        ->select('users.roll_unmber','users.first_name','users.first_name','users.birth_day'
        ,'users.phone_number','users.email','users.first_name','users.password','branches.No','branches.name',[$branchId])
        ->where("roll_unmber", "like", "%" . $filter . "%")
        ->orWhere("first_name", "like", "%" . $filter . "%")
            ->orWhere("last_name", "like", "%" . $filter . "%")->get();
        if ($filterResult) {

            return $this->traitResponse($filterResult, 'Search Successfully', 200);

        }


    }

}
