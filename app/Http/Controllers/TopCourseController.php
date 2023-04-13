<?php

namespace App\Http\Controllers;

use App\Models\TopCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopCourseController extends Controller
{
    use apiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $dataTopCourse = TopCourse::get();

        if($dataTopCourse)
        {
            return $this->traitResponse($dataTopCourse,'SUCCESS', 200);

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
            'branch_id'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataTopCourse = TopCourse::create($request -> all());

        if($dataTopCourse)
        {

            return  $this ->traitResponse( $dataTopCourse ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);





    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TopCourse  $topCourse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataTopCourse = TopCourse::find($id);

        if($dataTopCourse)
        {
            return $this->traitResponse($dataTopCourse , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);










    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TopCourse  $topCourse
     * @return \Illuminate\Http\Response
     */
    public function edit(TopCourse $topCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TopCourse  $topCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataTopCourse = TopCourse::find($id);

        if(!$dataTopCourse)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'branch_id'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataTopCourse->update($request->all());
        if($dataTopCourse)
        {
            return $this->traitResponse($dataTopCourse , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);









    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TopCourse  $topCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataTopCourse = TopCourse::find($id);

        if(!$dataTopCourse)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataTopCourse->delete($id);

        if($dataTopCourse)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);











    }
}
