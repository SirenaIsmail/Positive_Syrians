<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use App\Models\TrainerRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TrainerRatingController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dataTrainerRating = TrainerRating::paginate(PAGINATION_COUNT);

        if($dataTrainerRating)
        {
            return $this->traitResponse($dataTrainerRating,'SUCCESS', 200);

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
            'rating'=> 'required|integer|min:1|max:5',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }
        $date = now()->format('Y-m-d');

        $date_id = DB::table('dates')
            ->whereDate('date', $date)
            ->value('id');
        $currentSubscribe = DB::table('subscribes')->find($request->subscribe_id);
        $trainer=DB::table('subscribes')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
            ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
            ->where('subscribes.id', '=', $request->subscribe_id)
            ->select('trainer_profiles.id')
            ->first();
        $dataTrainerRating = TrainerRating::create([
            'date_id'=> $date_id,
            'subscribe_id' => $request->subscribe_id,
            'trainer_id' => $trainer->id,
            'rating' => $request->rating,
            'note' => $request->note,
        ]);

        if($dataTrainerRating)
        {

            return  $this ->traitResponse( $dataTrainerRating ,'شكرا لك على هذا التقييم' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainerRating  $trainerRating
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataTrainerRating = DB::table('trainer_ratings')
                ->join('subscribes', 'trainer_ratings.subscribe_id', '=', 'subscribes.id')
                ->join('courses', 'subscribes.course_id', '=', 'courses.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
                ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
                ->where('trainer_ratings.id', '=', $id)
                ->select('users.first_name','users.last_name', 'subjects.subjectName', 'trainer_ratings.rating', 'trainer_ratings.note')
                ->first();

        if($dataTrainerRating)
        {
            return $this->traitResponse($dataTrainerRating , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);






    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainerRating  $trainerRating
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainerRating $trainerRating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrainerRating  $trainerRating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataTrainerRating = TrainerRating::find($id);

        if(!$dataTrainerRating)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'rating'=> 'required|integer|min:1|max:5',
        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }
        $date = now()->format('Y-m-d');

        $date_id = DB::table('dates')
            ->whereDate('date', $date)
            ->value('id');
        $dataTrainerRating->update([
            'date_id'=>$date_id,
            'rating'=>$request->rating,]);
        if($dataTrainerRating)
        {
            return $this->traitResponse($dataTrainerRating , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainerRating  $trainerRating
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataTrainerRating = TrainerRating::find($id);

        if(!$dataTrainerRating)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataTrainerRating->delete($id);

        if($dataTrainerRating)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);

    }

    public function rate(Request $request,$id){
        if (auth()->check()) {
            $subscribe_id = Subscribe::find($id);
            if ($subscribe_id->exists()){
                $rateReq= new Request([
                    'rating' => $request->rating,
                    'subscribe_id' => $subscribe_id,
                    ]);
                $rate = ( new TrainerRatingController())->store($rateReq);
                if($rate)
                    return $this->traitResponse($rate, 'rated Successfully', 200);
                }else{
                    return $this->traitResponse(null,'The rate is Not Saved',400);
                }
            }else {
                return $this->traitResponse(null, 'No subscribe found', 200);
            }
       
    }


    public function trainerRatings($startDate = null , $endDate = null , $subject =null){
        if (auth()->check()){
            $branch = Auth::user()->branch_id;
            if(isset($date)){
                $topTrainers =  DB::table('trainer_ratings')
                    ->join('dates', 'trainer_ratings.date_id', '=', 'dates.id')
                    ->join('branches', 'users.branch_id', '=', 'branches.id')
                    ->join('trainer_profiles', 'trainer_ratings.trainer_id', '=', 'trainer_profiles.id')
                    ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
                    ->select('users.first_name', 'users.last_name', DB::raw('AVG(trainer_ratings.rating) as avg_rating'))
                    ->where('branches.id', '=', $branch)
                    ->whereBetween('dates.date', [$startDate, $endDate])
                    ->groupBy('trainer_id')
                    ->orderBy('avg_rating', 'desc')
                    ->get();
            }elseif (isset($date) && isset($subject)){
                $topTrainers =  DB::table('trainer_ratings')
                    ->join('dates', 'trainer_ratings.date_id', '=', 'dates.id')
                    ->join('branches', 'users.branch_id', '=', 'branches.id')
                    ->join('trainer_profiles', 'trainer_ratings.trainer_id', '=', 'trainer_profiles.id')
                    ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
                    ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                    ->select('users.first_name', 'users.last_name','subjects.subjectName', DB::raw('AVG(trainer_ratings.rating) as avg_rating'))
                    ->where('branches.id', '=', $branch)
                    ->whereBetween('dates.date', [$startDate, $endDate])
                    ->where('subjects.subjectName', '=', $subject)
                    ->groupBy('trainer_id')
                    ->orderBy('avg_rating', 'desc')
                    ->get();
            }
            if($topTrainers->count() > 0){
                return $this->traitResponse($topTrainers, 'Successful', 200);
            }else{
                return $this->traitResponse(null, 'No matching results', 200);
            }
        }else{
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
    }
}
