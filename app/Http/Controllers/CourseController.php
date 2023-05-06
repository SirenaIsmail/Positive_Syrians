<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CourseController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataCourse = Course::paginate(PAGINATION_COUNT);

        if($dataCourse)
        {
            return $this->traitResponse($dataCourse,'SUCCESS', 200);

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
            'branch_id'=> 'required|integer',
            'subject_id'=> 'required|integer',
            'trainer_id'=> 'required|integer',
//            'approved'=> 'required',
            'start'=> 'required',
            'end'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }
        $approve = false;

        $dataCourse = Course::create([
            'branch_id'=> $request->branch_id,
            'subject_id'=> $request->subject_id,
            'trainer_id'=> $request->trainer_id,
            'approved'=> $approve,
            'start'=> $request->start,
            'end'=> $request->end,
        ]);

        if($dataCourse)
        {

            return  $this ->traitResponse( $dataCourse ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'The Branch Not Saved ' , 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        $dataCourse = Course::find($id);

        if($dataCourse)
        {
            return $this->traitResponse($dataCourse , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $dataCourse = Course::find($id);

        if(!$dataCourse)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'branch_id'=> 'required|integer',
            'subject_id'=> 'required|integer',
            'trainer_id'=> 'required|integer',
//            'approved'=> 'required',
            'start'=> 'required',
            'end'=> 'required',

        ]);

        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }
        $dataCourse->update([
            'branch_id'=> $request->branch_id,
            'subject_id'=> $request->subject_id,
            'trainer_id'=> $request->trainer_id,
            'start'=> $request->start,
            'end'=> $request->end,
        ]);
//        $dataCourse->save();
        if($dataCourse)
        {
            return $this->traitResponse($dataCourse , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataCourse = Course::find($id);

        if(!$dataCourse)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataCourse->delete($id);

        if($dataCourse)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);

    }

    public function search($filter)
    {
        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;
    
            $filterResult = DB::table('courses')
                ->join('branches', 'courses.branch_id', '=', 'branches.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
                ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
                ->select('subjects.subjectName','subjects.content','subjects.price','subjects.houers','subjects.number_of_lessons','courses.start','courses.end','branches.name','branches.No','users.first_name','users.last_name')
                ->where('branches.id', '=', $branchId) // تحديد فقط الدورات في فرع المستخدم
                ->where(function ($query) use ($filter) { // التحقق من وجود نتائج بعد تطبيق الفلتر
                    $query->where('courses.start', 'like', "%$filter%")
                        ->orWhere('courses.end', 'like', "%$filter%");
                })
                ->get();
    
            if ($filterResult->count() > 0) {
                return $this->traitResponse($filterResult, 'Search Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No matching results found', 200);
            }
        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
    }


    public function approve($id){
        $course = Course::find($id);
        if(!$course)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }
        $approved = true;
        $course->update([
           'approved'  => $approved,
        ]);

        return response()->json([
           'course' => $course,
        ]);

    }
}



