<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{
    use apiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dataHistory = History::get();

        if($dataHistory)
        {
            return $this->traitResponse($dataHistory,'SUCCESS', 200);

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
            'student_id'=> 'required',
            'course_id'=>'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataHistory = History::create($request -> all());

        if($dataHistory)
        {

            return  $this ->traitResponse( $dataHistory ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);



    }

    public function addStudentsToCourse($id){
        $students = DB::table('subscribes')->where('course_id',$id)->where('state',1)->get();
        if ($students){
            foreach ($students as $student){
                $history = History::create([
                    'card_id' => $student->card_id,
                    'course_id' => $student->course_id,
                ]);
                return  $this ->traitResponse( $student ,'Saved Successfully' , 200 );
            }
        }
        return  $this->traitResponse(null,'Saved Failed ' , 400);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\History  $history
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {

        $dataHistory = History::find($id);

        if($dataHistory)
        {
            return $this->traitResponse($dataHistory , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\History  $history
     * @return \Illuminate\Http\Response
     */
    public function edit(History $history)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\History  $history
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {



        $dataHistory = History::find($id);

        if(!$dataHistory)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'student_id'=> 'required',
            'course_id'=>'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataHistory->update($request->all());
        if($dataHistory)
        {
            return $this->traitResponse($dataHistory , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\History  $history
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        $dataHistory = History::find($id);

        if(!$dataHistory)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataHistory->delete($id);

        if($dataHistory)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);


    }



}
