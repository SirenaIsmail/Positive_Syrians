<?php

namespace App\Http\Controllers;

use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

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
            'roll_number' => 'required|integer|min:3',
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



    public function addTrainer(Request $request){
        $request->validate([
//            'roll_number' => 'required|integer|min:4',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birth_day' => 'required',
            'branch_id' => 'required|integer',
            'phone_number' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $roll_number = 4;
        $user = User::create([
            'roll_number' => $roll_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_day' => $request->birth_day,
            'branch_id' => $request->branch_id,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $trainerReq = new Request([
            'user_id' => $user->id,
            'rating' => $user->rating,
        ]);

        $trainer = (new TrainerProfileController)->store($trainerReq);

        $STrainerReq  = new Request([
            'subject_id' => $request->subject_id,
            'trainer_id' => $trainer->user_id,
        ]);

        $STrainer = (new SubjectTrainerController)->store($STrainerReq);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'trainer' => $trainer,
            'trainer subject' => $STrainer,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }



}
