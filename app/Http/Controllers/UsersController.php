<?php

namespace App\Http\Controllers;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class UsersController extends Controller
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
            //'branch_id' => 'required|integer',
            'phone_number' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $branchId = Auth::user()->branch_id;
        $user = User::create([
            'roll_number' => $request->roll_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_day' => $request->birth_day,
            'branch_id' => $branchId,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        //$token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            // 'authorisation' => [
            //     'token' => $token,
            //     'type' => 'bearer',
            // ]
        ]);
    }




    public function addEmployee(Request $request){
        $request->validate([
            'roll_number' => 'required|integer|min:3',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birth_day' => 'required',
            //'branch_id' => 'required|integer',
            'phone_number' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $branchId = Auth::user()->branch_id;
        $user = User::create([
            'roll_number' => $request->roll_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_day' => $request->birth_day,
            'branch_id' => $branchId,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            // 'authorisation' => [
            //     'token' => $token,
            //     'type' => 'bearer',
            // ]
        ]);
    }


    public function addTrainer(Request $request){
        $request->validate([
           // 'roll_number' => 'required|integer|min:4',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birth_day' => 'required',
            //'branch_id' => 'required|integer',
            'phone_number' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $roll_number = 4;
        $branchId = Auth::user()->branch_id;
            
        $user = User::create([
            'roll_number' => $roll_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_day' => $request->birth_day,
            'branch_id' => $branchId,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $trainerReq = new Request([
            'user_id' => $user->id,
            'rating' => $request->rating,
        ]);

        $trainer = (new TrainerProfileController)->store($trainerReq);

        $STrainerReq  = new Request([
            'subject_id' => $request->subject_id,
            'trainer_id' => $trainer->user_id,
        ]);

        $STrainer = (new SubjectTrainerController)->store($STrainerReq);

        //$token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'trainer' => $trainer,
            'trainer subject' => $STrainer,
            // 'authorisation' => [
            //     'token' => $token,
            //     'type' => 'bearer',
            // ]
        ]);
    }


    public function search(Request $request, $filter)
    {
        if (auth()->check() ) {
            $branchId = Auth::user()->branch_id;
            if($filter != "null"){
                $filterResult = DB::table('users')
                ->join('branches', 'users.branch_id', '=', 'branches.id')
                ->select('users.first_name', 'users.last_name', 'users.birth_day', 'users.phone_number', 'users.email', 'users.password', 'branches.No', 'branches.name')
                ->where('users.roll_number', '=', $request->query('roll_number')) // تحديد فقط المستخدمين في فرع المستخدم
                ->where('users.branch_id', '=', $branchId) // تحديد فقط المستخدمين في فرع المستخدم
                ->select('users.roll_number', 'users.first_name', 'users.last_name', 'users.birth_day'
                , 'users.phone_number', 'users.email', 'users.password', 'branches.No', 'branches.name')
                ->where('branches.id', '=', $branchId) // تحديد فقط المستخدمين في فرع المستخدم
                ->where(function ($query) use ($filter) { // التحقق من وجود نتائج بعد تطبيق الفلتر
                    $query->where('users.roll_number', 'like', "%$filter%")
                        ->orWhere('users.first_name', 'like', "%$filter%")
                        ->orWhere('users.last_name', 'like', "%$filter%");
                })
                ->paginate(1);
    
            }
            else{
                $filterResult = DB::table('users')
                ->join('branches', 'users.branch_id', '=', 'branches.id')
                ->select('users.first_name', 'users.last_name', 'users.birth_day', 'users.phone_number', 'users.email', 'users.password', 'branches.No', 'branches.name')
                ->where('users.roll_number', '=', $request->query('roll_number')) // تحديد فقط المستخدمين في فرع المستخدم
                ->where('users.branch_id', '=', $branchId) // تحديد فقط المستخدمين في فرع المستخدم
                ->paginate(1);

            }
            
            if ($filterResult->count() > 0) {
                return $this->traitResponse($filterResult, 'Search Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No matching results found', 200);
            }

        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
    }

}
