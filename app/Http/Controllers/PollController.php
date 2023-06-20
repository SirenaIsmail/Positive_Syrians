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
            'full_name_ar'=>'required',
            'poll_date'=>'required',
            'phone_numb'=>'required',
            'whatsapp_numb'=>'required',
            'first_subj'=>'required',
            'secound_subj' => 'required',
            'third_subj'=>'required',
            'first_time'=>'required',
            'secound_time'=>'required',
            'third_time'=>'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataPoll = Poll::create($request -> all());

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
            'first_subj'=>'required',

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



    public function search($filter)
    {
        if (auth()->check()) {
        $branchId = Auth::user()->branch_id;

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
               }) ->paginate(5);



               if ($filterResult->count() > 0) {
                return $this->traitResponse($filterResult, 'Show Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No matching results found', 200);
            }
        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
    }
}

