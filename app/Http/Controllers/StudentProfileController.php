<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentProfileController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataStudent = StudentProfile::paginate(PAGINATION_COUNT);

        if($dataStudent)
        {
            return $this->traitResponse($dataStudent,'SUCCESS', 200);

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
            'card_id'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataStudent = StudentProfile::create($request -> all());

        if($dataStudent)
        {

            return  $this ->traitResponse( $dataStudent ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentProfile  $studentProfile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataStudent = StudentProfile::find($id);

        if($dataStudent)
        {
            return $this->traitResponse($dataStudent , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentProfile  $studentProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentProfile $studentProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentProfile  $studentProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $dataStudent = StudentProfile::find($id);

        if(!$dataStudent)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'card_id'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataStudent->update($request->all());
        if($dataStudent)
        {
            return $this->traitResponse($dataStudent , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentProfile  $studentProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataStudent = StudentProfile::find($id);

        if(!$dataStudent)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataStudent->delete($id);

        if($dataStudent)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);
    }
}
