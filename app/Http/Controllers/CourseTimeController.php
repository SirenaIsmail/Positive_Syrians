<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTime;
use App\Models\SubjectTrainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use apiResponse;

    public function index()
    {
        //
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
            'course_id'=> 'required',
            'time_id'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $course = Course::find($request->course_id);
        if ($course->approved == 1){
            $dataCourseTime = CourseTime::create($request -> all());
        }

        if($dataCourseTime)
        {

            return  $this ->traitResponse( $dataCourseTime ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseTime  $courseTime
     * @return \Illuminate\Http\Response
     */
    public function show(CourseTime $courseTime)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseTime  $courseTime
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseTime $courseTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseTime  $courseTime
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseTime $courseTime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseTime  $courseTime
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseTime $courseTime)
    {
        //
    }
}
