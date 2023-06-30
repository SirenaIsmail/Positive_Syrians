<?php

namespace App\Http\Controllers;

use App\Models\TrainerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TrainerProfileController extends Controller
{
    use apiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataTrainerProfile = TrainerProfile::join('users', 'users.id', '=', 'trainer_profiles.user_id')
            ->join('branches', 'branches.id' , '=','users.branch_id')
            ->select('users.first_name', 'users.last_name','branches.name', 'users.email', 'trainer_profiles.rating')
            ->get();

        if ($dataTrainerProfile) {
            return $this->traitResponse($dataTrainerProfile, 'SUCCESS', 200);
        }


        return $this->traitResponse(null, 'Sorry Failed Not Found', 404);

    }


    public function view(Request $request)
    {
        //$branchId = Auth::user()->branch_id;
        $dataTrainerProfile = DB::table('trainer_profiles')
            ->join('users','users.id','=','trainer_profiles.user_id')
            ->select('users.first_name','trainer_profiles.id')
           // ->where('users.roll_number', '=', $request->query('roll_number')) // تحديد فقط المستخدمين في فرع المستخدم
           // ->where('users.branch_id', '=', $branchId)
            ->get();

        if($dataTrainerProfile)
        {
            return $this->traitResponse($dataTrainerProfile,'SUCCESS', 200);

        }


        return $this->traitResponse(null, 'Sorry Failed Not Found', 404);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id'=> 'required|integer',
            'rating'=> 'required|max:10',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }
            $dataTrainerProfile = TrainerProfile::create([
                'user_id' => $request->user_id,
                'rating' => $request->rating,
            ]);

            if ($dataTrainerProfile) {

                return $this->traitResponse($dataTrainerProfile, 'Saved Successfully', 200);
            }

            return $this->traitResponse(null, 'Saved Failed ', 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainerProfile  $trainerProfile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataTrainerProfile = TrainerProfile::find($id);

        if($dataTrainerProfile)
        {
            return $this->traitResponse($dataTrainerProfile , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainerProfile  $trainerProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainerProfile $trainerProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrainerProfile  $trainerProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $dataTrainerProfile = TrainerProfile::find($id);

        if(!$dataTrainerProfile)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'rating'=> 'required',


        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataTrainerProfile->update([
            'user_id'=> $request->user_id,
            'rating'=> $request->rating,
        ]);
        if($dataTrainerProfile)
        {
            return $this->traitResponse($dataTrainerProfile , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);





    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainerProfile  $trainerProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataTrainerProfile = TrainerProfile::find($id);

        if(!$dataTrainerProfile)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataTrainerProfile->delete($id);

        if($dataTrainerProfile)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);



    } public function search($filter)
    {
        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;
            $filterResult = DB::table('trainer_profiles')
                ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
                ->join('branches', 'users.branch_id', '=', 'branches.id')
                ->select('users.roll_number', 'users.first_name', 'users.last_name', 'trainer_profiles.rating', 'users.birth_day', 'users.phone_number', 'users.email', 'users.password', 'branches.No', 'branches.name')
                ->where('branches.id', '=', $branchId) // تحديد فقط المدربين في فرع المستخدم
                ->where('users.first_name', 'like', "%$filter%") // تحديد النتائج المطابقة للتقييم المدخل
                ->paginate(PAGINATION_COUNT);

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
