<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PollController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;

            $Result = DB::table('polls')
                ->join('branches', 'polls.branch_id', '=', 'branches.id')
                ->join('subjects AS subject1', 'polls.first_subj', '=', 'subject1.id')
                ->join('subjects AS subject2', 'polls.secound_subj', '=', 'subject2.id')
                ->join('subjects AS subject3', 'polls.third_subj', '=', 'subject3.id')
                ->select('polls.full_name_ar','polls.full_name_en', 'polls.mother_name', 'polls.address', 'polls.poll_date','polls.phone_numb','polls.whatsapp_numb', 'subject1.subjectName', 'polls.first_time', 'subject2.subjectName  As subjectName2'
                , 'polls.secound_time', 'subject3.subjectName  As subjectName3', 'polls.third_time','polls.notice')
                ->where('branches.id', '=', $branchId)
                 ->paginate(5);



                   if ($Result->count() > 0) {
                    return $this->traitResponse($Result, 'Index Successfully', 200);
                } else {
                    return $this->traitResponse(null, 'No results found', 200);
                }
            } else {
                return $this->traitResponse(null, 'User not authenticated', 401);
            }



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
            'full_name'=>'required',
//            'poll_date'=>'required|date',
            'phone_numb'=>'required',
            'first'=>'required|integer',
            'branch_id'=>'required|integer',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }
        $poll_date = date('d-m-Y');
        $dataPoll = Poll::create([
            'full_name'=> $request->full_name,
            'poll_date'=> $poll_date,
            'phone_numb'=> $request->phone_numb,
            'first'=> $request->first,
            'branch_id'=> $request->branch_id,

        ]);

        if($dataPoll)
        {

            return  $this ->traitResponse( $dataPoll ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);





    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;

            $Result = DB::table('polls')
                ->join('branches', 'polls.branch_id', '=', 'branches.id')
                ->join('subjects AS subject1', 'polls.first_subj', '=', 'subject1.id')
                ->join('subjects AS subject2', 'polls.secound_subj', '=', 'subject2.id')
                ->join('subjects AS subject3', 'polls.third_subj', '=', 'subject3.id')
                ->select('polls.full_name_ar','polls.full_name_en', 'polls.mother_name', 'polls.address','polls.phone_numb','polls.whatsapp_numb', 'polls.poll_date', 'subject1.subjectName', 'polls.first_time', 'subject2.subjectName  As subjectName2'
                , 'polls.secound_time', 'subject3.subjectName  As subjectName3', 'polls.third_time','polls.notice')
                ->where('branches.id', '=', $branchId)
                ->where('polls.id', '=', $id)
                 ->get();



                   if ($Result->count() > 0) {
                    return $this->traitResponse($Result, 'Index Successfully', 200);
                } else {
                    return $this->traitResponse(null, 'No results found', 200);
                }
            } else {
                return $this->traitResponse(null, 'User not authenticated', 401);
            }




    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function edit(Poll $poll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $dataPoll = Poll::find($id);

        if(!$dataPoll)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'full_name'=>'required',
            'first'=>'required|integer',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataPoll->update($request->all());
        if($dataPoll)
        {
            return $this->traitResponse($dataPoll , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataPoll = Poll::find($id);

        if(!$dataPoll)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataPoll->delete($id);

        if($dataPoll)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);
    }



    public function search_by_branch($filter)
    {
        $branchId = Auth::user()->branch_id;

        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;
            if($filter != "null"){
                $filterResult = DB::table('polls')
                ->join('branches', 'polls.branch_id', '=', 'branches.id')
                ->join('subjects AS subject1', 'polls.first_subj', '=', 'subject1.id')
                ->join('subjects AS subject2', 'polls.secound_subj', '=', 'subject2.id')
                ->join('subjects AS subject3', 'polls.third_subj', '=', 'subject3.id')
                ->select('polls.full_name_ar','polls.full_name_en', 'polls.mother_name', 'polls.address','polls.phone_numb','polls.whatsapp_numb', 'polls.poll_date', 'subject1.subjectName', 'polls.first_time', 'subject2.subjectName  As subjectName2'
                ,'polls.secound_time', 'subject3.subjectName  As subjectName3', 'polls.third_time','polls.notice')
                ->where('branches.id', '=', $branchId)
                ->where(function ($query) use ($filter) {
                    $query->where("subject1.subjectName", "like","%".$filter."%")
                    ->orWhere("subject2.subjectName", "like","%".$filter."%")
                    ->orWhere("subject3.subjectName", "like","%".$filter."%");
                   }) ->paginate(1);
            }
            else{
                //$branchId = Auth::user()->branch_id;
                $filterResult = DB::table('polls')
                ->join('branches', 'polls.branch_id', '=', 'branches.id')
                ->join('subjects AS subject1', 'polls.first_subj', '=', 'subject1.id')
                ->join('subjects AS subject2', 'polls.secound_subj', '=', 'subject2.id')
                ->join('subjects AS subject3', 'polls.third_subj', '=', 'subject3.id')
                ->select('polls.full_name_ar','polls.full_name_en', 'polls.mother_name', 'polls.address','polls.phone_numb','polls.whatsapp_numb', 'polls.poll_date', 'subject1.subjectName', 'polls.first_time', 'subject2.subjectName  As subjectName2'
                ,'polls.secound_time', 'subject3.subjectName  As subjectName3', 'polls.third_time','polls.notice')
                ->where('branches.id', '=', $branchId)
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






        // if (auth()->check()) {
        // $branchId = Auth::user()->branch_id;
        // if($filter != "null"){
        //     $filterResult = DB::table('polls')
        //     ->join('branches', 'polls.branch_id', '=', 'branches.id')
        //     ->join('subjects AS subject1', 'polls.first_subj', '=', 'subject1.id')
        //     ->join('subjects AS subject2', 'polls.secound_subj', '=', 'subject2.id')
        //     ->join('subjects AS subject3', 'polls.third_subj', '=', 'subject3.id')
        //     ->select('polls.full_name_ar','polls.full_name_en', 'polls.mother_name', 'polls.address','polls.phone_numb','polls.whatsapp_numb', 'polls.poll_date', 'subject1.subjectName', 'polls.first_time', 'subject2.subjectName  As subjectName2'
        //     ,'polls.secound_time', 'subject3.subjectName  As subjectName3', 'polls.third_time','polls.notice')
        //     ->where('branches.id', '=', $branchId)
        //     ->where(function ($query) use ($filter) {
        //         $query->where("subject1.subjectName", "like","%".$filter."%")
        //         ->orWhere("subject2.subjectName", "like","%".$filter."%")
        //         ->orWhere("subject3.subjectName", "like","%".$filter."%");
        //        }) ->paginate(5);
        // } else {
        //     return $this->traitResponse(null, 'User not authenticated', 401);
        // }

        // if ($filterResult->count() > 0) {
        //     return $this->traitResponse($filterResult, 'Show Successfully', 200);
        // } else {
        //     return $this->traitResponse(null, 'No matching results found', 200);
        // }


    public function pollsCounting(){
        $count = DB::table('polls')
            ->join('subjects', 'polls.first', '=', 'subjects.id')
            ->join('branches', 'polls.branch_id', '=', 'branches.id')
            ->select('subjects.subjectName', 'branches.name', DB::raw('DATE_FORMAT(poll_date, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('first', 'branch_id', 'month')
            ->get();

        if ($count->count() > 0) {
            return $this->traitResponse($count, 'Counting Successfully', 200);
        } else {
            return $this->traitResponse(null, 'No matching results found', 200);
        }

    }
    public function pollsCountingByBranch(Request $request){
        $count = DB::table('polls')
            ->join('subjects', 'polls.first', '=', 'subjects.id')
            ->join('branches', 'polls.branch_id', '=', 'branches.id')
            ->select('subjects.subjectName', 'branches.name', DB::raw('DATE_FORMAT(poll_date, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->where('polls.branch_id', '=', $request->branch)
            ->groupBy('first', 'branch_id', 'month')
            ->get();

        if ($count->count() > 0) {
            return $this->traitResponse($count, 'Counting Successfully', 200);
        } else {
            return $this->traitResponse(null, 'No matching results found', 200);
        }

    }
    public function pollsCountingByDate(Request $request){
        $count = DB::table('polls')
            ->join('subjects', 'polls.first', '=', 'subjects.id')
            ->join('branches', 'polls.branch_id', '=', 'branches.id')
            ->select('subjects.subjectName', 'branches.name', DB::raw('DATE_FORMAT(poll_date, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->where(DB::raw('DATE_FORMAT(poll_date, "%Y-%m")'), '=', $request->date)
            ->groupBy('subjects.subjectName', 'branches.name', 'month')
            ->get();

        if ($count->count() > 0) {
            return $this->traitResponse($count, 'Counting Successfully', 200);
        } else {
            return $this->traitResponse(null, 'No matching results found', 200);
        }

    }
    public function pollsCountingByBranchAndDate(Request $request){
        $branch = Auth::user()->branch_id;
        $count = DB::table('polls')
            ->join('subjects', 'polls.first', '=', 'subjects.id')
            ->join('branches', 'polls.branch_id', '=', 'branches.id')
            ->select('subjects.subjectName', 'branches.name', DB::raw('DATE_FORMAT(poll_date, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->where('polls.branch_id', '=', $branch)
            ->whereBetween('poll_date', [$request->startDate, $request->endDate])
           ->groupBy('subjects.subjectName', 'branches.name', 'month')
            ->get();

        if ($count->count() > 0) {
            return $this->traitResponse($count, 'Counting Successfully', 200);
        } else {
            return $this->traitResponse(null, 'No matching results found', 200);
        }
    }
}










