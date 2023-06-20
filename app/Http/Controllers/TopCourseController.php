<?php

namespace App\Http\Controllers;

use App\Models\TopCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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


        $dataTopCourse = TopCourse::paginate(PAGINATION_COUNT);

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




    public function getTopCoursesReport(){
        $top_courses = DB::table('top_courses')
            ->join('subscribes', 'top_courses.subscribe_id', '=', 'subscribes.id')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
            ->join('branches', 'top_courses.branch_id', '=', 'branches.id')
            ->join('dates', 'top_courses.date_id', '=', 'dates.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->select('branches.name as branch', 'subjects.subjectName as course',
                'dates.date', DB::raw('count(top_courses.id) as enrollments_count'))
            ->groupBy('branches.name', 'subjects.subjectName', 'dates.date')
            ->orderBy('enrollments_count', 'desc')
            ->get();

        if($top_courses)
        {
            return  $this->traitResponse($top_courses , 'Successful ' , 200);

        }
        return  $this->traitResponse(null , 'Failed ' , 404);
    }



    public function getBranchTopCoursesReport($branch){
        $top_courses = DB::table('top_courses')
            ->join('subscribes', 'top_courses.subscribe_id', '=', 'subscribes.id')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
            ->join('branches', 'top_courses.branch_id', '=', 'branches.id')
            ->join('dates', 'top_courses.date_id', '=', 'dates.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->select('branches.name as branch', 'subjects.subjectName as course',
                'dates.date', DB::raw('count(top_courses.id) as enrollments_count'))
            ->where('branches.id', '=', $branch) // اختيار الدورات التي تنتمي للفرع المحدد
            ->groupBy('branches.name', 'subjects.subjectName', 'dates.date')
            ->orderBy('enrollments_count', 'desc')
            ->get();

        if($top_courses)
        {
            return $this->traitResponse($top_courses, 'Successful', 200);
        }
        return $this->traitResponse(null, 'Failed', 404);
    }




    public function getMonthlyTopCoursesReport($month){
        $top_courses = DB::table('top_courses')
            ->join('subscribes', 'top_courses.subscribe_id', '=', 'subscribes.id')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
            ->join('branches', 'top_courses.branch_id', '=', 'branches.id')
            ->join('dates', 'top_courses.date_id', '=', 'dates.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->select('branches.name as branch', 'subjects.subjectName as course',
                'dates.date', DB::raw('count(top_courses.id) as enrollments_count'))
            ->where('dates.date', 'like', $month.'%') // اختيار الدورات التي تنطبق عليها الشهر المحدد
            ->groupBy('branches.name', 'subjects.subjectName', 'dates.date')
            ->orderBy('enrollments_count', 'desc')
            ->get();
        if($top_courses)
        {
            return $this->traitResponse($top_courses, 'Successful', 200);
        }
        return $this->traitResponse(null, 'Failed', 404);
    }

    public function getYearlyTopCoursesReport($year){
        $top_courses = DB::table('top_courses')
            ->join('subscribes', 'top_courses.subscribe_id', '=', 'subscribes.id')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
            ->join('branches', 'top_courses.branch_id', '=', 'branches.id')
            ->join('dates', 'top_courses.date_id', '=', 'dates.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->select('branches.name as branch', 'subjects.subjectName as course',
                DB::raw('YEAR(dates.date) as year'), DB::raw('count(top_courses.id) as enrollments_count'))
            ->where(DB::raw('YEAR(dates.date)'), '=', $year) // اختيار الدورات التي تنطبق عليها السنة المحددة
            ->groupBy('branches.name', 'subjects.subjectName', DB::raw('YEAR(dates.date)'))
            ->orderBy('enrollments_count', 'desc')
            ->get();

        if($top_courses)
        {
            return $this->traitResponse($top_courses, 'Successful', 200);
        }
        return $this->traitResponse(null, 'Failed', 404);
    }


    public function getMonth_Branch_TopCourse($month, $branch){
        $top_courses = DB::table('top_courses')
            ->join('subscribes', 'top_courses.subscribe_id', '=', 'subscribes.id')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
            ->join('branches', 'top_courses.branch_id', '=', 'branches.id')
            ->join('dates', 'top_courses.date_id', '=', 'dates.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->select('branches.name as branch', 'subjects.subjectName as course',
                'dates.date', DB::raw('count(top_courses.id) as enrollments_count'))
            ->where('branches.id', '=', $branch) // اختيار الدورات التي تنتمي للاسم المحدد للفرع
            ->where('dates.date', 'like', $month.'%') // اختيار الدورات التي تنطبق عليها الشهر المحدد
            ->groupBy('branches.name', 'subjects.subjectName', 'dates.date')
            ->orderBy('enrollments_count', 'desc')
            ->get();

        if($top_courses)
        {
            return $this->traitResponse($top_courses, 'Successful', 200);
        }
        return $this->traitResponse(null, 'Failed', 404);
    }


}
