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

        $dataPoll = Poll::paginate(PAGINATION_COUNT);

        if($dataPoll)
        {
            return $this->traitResponse($dataPoll,'SUCCESS', 200);

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
            'full_name'=>'required',
            'first_subj'=>'required',
            'secound_subj' => 'required',
            'third_subj'=>'required',
            'first_time'=>'required',
            'secound_time'=>'required',
            'third_time'=>'required',
            'poll_date'=> 'required',

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


        $dataPoll = Poll::find($id);

        if($dataPoll)
        {
            return $this->traitResponse($dataPoll , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);



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
        $branchId = Auth::user()->branch_id;
        $filterResult = DB::table('polls')
            ->join('subjects AS subject1', 'polls.first_subj', '=', 'subject1.id')
            ->join('subjects AS subject2', 'polls.secound_subj', '=', 'subject2.id')
            ->join('subjects AS subject3', 'polls.third_subj', '=', 'subject3.id')
            ->select('polls.full_name', 'polls.mother_name', 'polls.address', 'polls.poll_date', 'subject1.subjectName', 'polls.first_time', 'subject2.subjectName  As subjectName2', 'polls.secound_time', 'subject3.subjectName  As subjectName3', 'polls.third_time',[$branchId])
            ->where("subject1.subjectName", "like","%".$filter."%")
            ->orWhere("subject2.subjectName", "like","%".$filter."%")
            ->orWhere("subject3.subjectName", "like","%".$filter."%")
            ->paginate(PAGINATION_COUNT);
    
        if ($filterResult) {
            return $this->traitResponse($filterResult, 'Search Successfully', 200);
        }
    }

}
