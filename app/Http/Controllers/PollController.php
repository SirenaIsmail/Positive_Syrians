<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $dataPoll = Poll::get();

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
}
