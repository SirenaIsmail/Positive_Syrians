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
        $threeMonthsAgo = \Carbon\Carbon::now()->subMonths(3)->format('Y-m-d');
        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;

            $Result = DB::table('courses')
                ->join('branches', 'courses.branch_id', '=', 'branches.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
                ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
                // 'subjects.subjectName',
                ->select('courses.*','subjects.subjectName','users.first_name','users.last_name')
                ->where('branches.id', '=', $branchId)
                ->whereDate('courses.start', '>=', $threeMonthsAgo)
                ->orderBy('courses.id', 'desc')
                ->paginate(10);
                

            if ($Result->count() > 0) {
                return $this->traitResponse($Result, 'تم العرض بنجاح', 200);
            } else {
                return $this->traitResponse(null, 'لا يوجد نتائج', 200);
            }
        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
    }

    public function indexa($id)
    {
        $threeMonthsAgo = \Carbon\Carbon::now()->subMonths(3)->format('Y-m-d');
         if (auth()->check()) {
        

            $Result = DB::table('courses')
               ->join('branches', 'courses.branch_id', '=', 'branches.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
                ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
                // 'subjects.subjectName',
                ->select('courses.*','subjects.subjectName','users.first_name','users.last_name')
                ->whereDate('courses.start', '>=', $threeMonthsAgo)
                ->where('branches.id', '=', $id)
                ->orderBy('courses.id', 'desc')
                ->paginate(10);
                

            if ($Result->count() > 0) {
                return $this->traitResponse($Result, 'تم العرض بنجاح', 200);
            } else {
                return $this->traitResponse(null, 'لا يوجد نتائج', 200);
            }
        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
    }


    public function indexAvailable()
    {
         if (auth()->check()) {
             $branchId = Auth::user()->branch_id;

              $Result = DB::table('courses') 
                ->leftJoin('branches', 'courses.branch_id', '=', 'branches.id')
                ->leftJoin('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->leftJoin('subscribes', 'courses.id', '=', 'subscribes.course_id')
                ->select('courses.*','subjects.subjectName','subjects.price' )
                // ->where('branches.id', '=', $branchId)
                ->where('courses.start', '>=', date('Y-m-d'))
                ->whereIn('courses.approved', [0, 1, 2])
                ->groupBy('courses.id')
                ->havingRaw('SUM(CASE WHEN subscribes.state IN (2 ,4) THEN 1 ELSE 0 END) <= courses.max_students')
               ->get();
                // ->paginate(10);

                

            if ($Result->count() > 0) {
                return $this->traitResponse($Result, 'Index Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No  results found', 200);
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
        if (date('N', strtotime($request->start)) === '5') {
            // يتم إلقاء استثناء بسبب تاريخ البدء يوم الجمعة
        }
        if (date('N', strtotime($request->end)) === '5') {
            // يتم إلقاء استثناء بسبب تاريخ الانتهاء يوم الجمعة
        }

        $validation = Validator::make($request->all(), [
            'min_students' => 'integer|min:10|max:80|lt:max_students',
            'max_students' => 'integer|min:11|max:82|gt:min_students',
            'start' => [
        'date',
        'after:now',
        function ($attribute, $value, $fail) {
            if (date('N', strtotime($value)) === '5') {
                $fail('The '.$attribute.' cannot fall on a Friday.');
            }
        },
    ],
    'end' => [
        'date',
        'after:start',
        'after:'.date('Y-m-d', strtotime('+5 days', strtotime($request->start))),
        'before:'.date('Y-m-d', strtotime('+4 months', strtotime($request->start))),
        function ($attribute, $value, $fail) use ($request) {
            if (date('N', strtotime($value)) === '5') {
                $fail('The '.$attribute.' cannot fall on a Friday.');
            }
        },
    ],
]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }
        
       $approve = 0 ;
        $branchId = Auth::user()->branch_id;
        $dataCourse = Course::create([
            'branch_id'=> $branchId,
            'subject_id'=> $request->subject_id,
            'trainer_id'=> $request->trainer_id,
            'min_students'=>$request->min_students,
            'max_students'=>$request->max_students,
            'approved'=> $approve,
            'start'=> $request->start,
            'end'=> $request->end,
        ]);

        if($dataCourse)
        {

            return  $this ->traitResponse( $dataCourse ,'تم الحفظ بنجاح' , 200 );
        }

        return  $this->traitResponse(null,'عذراً لم يتم حفظ البيانات' , 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;

            $Result = DB::table('courses')
                ->join('branches', 'courses.branch_id', '=', 'branches.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
                ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
                ->select('courses.*','subjects.subjectName','users.first_name','users.last_name')
                ->where('branches.id', '=', $branchId)
                ->where('courses.id', '=', $id)
                ->get();

            if ($Result->count() > 0) {
                return $this->traitResponse($Result, 'Show Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No matching results found', 200);
            }
        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
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
            return $this->traitResponse(null,' عذرا غير موجود',404);

        }

        if (date('N', strtotime($request->start)) === '5') {
            // يتم إلقاء استثناء بسبب تاريخ البدء يوم الجمعة
        }
        if (date('N', strtotime($request->end)) === '5') {
            // يتم إلقاء استثناء بسبب تاريخ الانتهاء يوم الجمعة
        }

        $validation = Validator::make($request->all(), [
            'min_students' => 'integer|min:10|max:80|lt:max_students',
            'max_students' => 'integer|min:11|max:82|gt:min_students',
            'start' => [
        'date',
        'after:now',
        function ($attribute, $value, $fail) {
            if (date('N', strtotime($value)) === '5') {
                $fail('The '.$attribute.' cannot fall on a Friday.');
            }
        },
    ],
    'end' => [
        'date',
        'after:start',
        'after:'.date('Y-m-d', strtotime('+5 days', strtotime($request->start))),
        'before:'.date('Y-m-d', strtotime('+4 months', strtotime($request->start))),
        function ($attribute, $value, $fail) use ($request) {
            if (date('N', strtotime($value)) === '5') {
                $fail('The '.$attribute.' cannot fall on a Friday.');
            }
        },
    ],
]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }


        if($request->approved == 0 ) {
            $approvedSubscriptions = $dataCourse->subscribes()->where('state', '=', 2)->orWhere('state', '=', 4)->count();
            if (($dataCourse->approved == 0 || $dataCourse->approved == 1) && (date('Y-m-d') <= $dataCourse->start) && ($approvedSubscriptions < $dataCourse->min_students)) {
                // Check if the current date is less than start date
                    $dataCourse->update([
                        'approved' => $request->approved,
                    ]);
                } 
                else {
                    return $this->traitResponse(null,'لا يمكن التعديل على حالة الكورس ',400);
                }
           
        }

        
         if($request->approved == 1 ) {
        // Check if the number of subscriptions is greater than or equal to min_students
        $approvedSubscriptions = $dataCourse->subscribes()->where('state', 1)->orWhere('state', '=', 2)->orWhere('state', '=', 4)->count();
        if($approvedSubscriptions >= $dataCourse->min_students && $dataCourse->approved == 0) {
            // Check if the current date is less than start date
            if(date('Y-m-d') <= $dataCourse->start) {
                $dataCourse->update([
                    'approved' => $request->approved,
                ]);
            } else {
                return $this->traitResponse(null,'لا يمكن الموافقة على الكورس، حيث أن التاريخ الحالي تجاوز تاريخ البدء',400);
            }
        } else {
            return $this->traitResponse(null,'لا يمكن الموافقة على الكورس، عدد الاشتراكات أقل من الحد الأدنى',400);
        }
    }

  
    elseif($request->approved == 2) {
        // Check if the current date is between start and end date
        if(date('Y-m-d') >= $dataCourse->start && date('Y-m-d') < $dataCourse->end && $dataCourse->approved == 1) {
            $dataCourse->update([
                'approved' => $request->approved,
            ]);
        } else {
            return $this->traitResponse(null,'لا يمكن تغيير حالة الكورس إلى قيد الاعطاء ',400);
        }
    }

    elseif($request->approved == 3 ) {
        // Check if the current date is between start and end date
        if(date('Y-m-d') >= $dataCourse->end && $dataCourse->approved == 2) {
            $dataCourse->update([
                'approved' => $request->approved,
            ]);
        } else {
            return $this->traitResponse(null,'لا يمكن تغيير حالة الكورس إلى منتهي ',400);
        }
    }
        elseif($request->approved == 4 ) {
            if (($dataCourse->approved == 0 || $dataCourse->approved == 1)  && date('Y-m-d') <= $dataCourse->start ) {
                $dataCourse->update([
                    'approved' => $request->approved,
                ]);
            } else {
                return $this->traitResponse(null,'لا يمكن تغيير حالة الكورس إلى مُلغى ',400);
            }
    }

    elseif($request->start) {
        // Check if the current date is between start and end date
        if( $dataCourse->approved == 0 ||  $dataCourse->approved == 1) {
            $dataCourse->update([
                'start' => $request->start,
            ]);
        } else {
            return $this->traitResponse(null,'لا يمكن تغيير تاريخ ابتداء الكورس  ',400);
        }
   }

        elseif($request->end) {
            // Check if the current date is between start and end date
            if( $dataCourse->approved == 0 ||  $dataCourse->approved == 1 ||  $dataCourse->approved == 2) {
                $dataCourse->update([
                    'end' => $request->end,
                ]);
            } else {
                return $this->traitResponse(null,'لا يمكن تغيير تاريخ انتهاء الكورس  ',400);
            }
        }
        

         $branchId = Auth::user()->branch_id;
        $dataCourse->update([
            'branch_id'=>$branchId,
            'subject_id'=>$request->subject_id,
            'trainer_id'=>$request->trainer_id,
            'min_students'=>$request->min_students,
            'max_students'=>$request->max_students,
            'approved'=>$request->approved,
            'start'=> $request->start,
            'end'=>$request->end,
        ]);

        if($dataCourse)
        {

            return  $this ->traitResponse( $dataCourse ,'تم التعديل بنجاح' , 200 );
        }

        return  $this->traitResponse(null,'عذراً فشل التعديل' , 400);

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
            return $this->traitResponse(null,'عذراً غير موجود' , 404);
        }

        $approvedSubscriptions = $dataCourse->subscribes()->whereIn('state', [1, 2])->count();
      if(  $dataCourse->approved == 0 && $dataCourse->start > date('Y-m-d') && $approvedSubscriptions == 0  ){

    

      $dataCourse->delete($id);

        if($dataCourse)
        {
            return  $this->traitResponse(null , 'تم الحذف بنجاح ' , 200);

        }
        return  $this->traitResponse(null , 'فشل الحذف' , 404);

    }

    return  $this->traitResponse(null , 'لا يمكن حذف هذا الكورس ' , 404);

    }


    public function search(Request $request,$filter =null)
{
    if (auth()->check()) {
        $branchId = Auth::user()->branch_id;

        $validation = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'approved' => 'nullable|integer',
        ]);

        if ($validation->fails()) {
            return $this->traitResponse(null, $validation->errors()->first(), 422);
        }

        $filterResult = DB::table('courses')
            ->join('branches', 'courses.branch_id', '=', 'branches.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
            ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
            ->select('courses.id','users.first_name', 'users.last_name','subjects.subjectName','subjects.price','courses.start'
            ,'courses.end','courses.min_students','courses.max_students')
            ->where('branches.id', '=', $branchId) // تحديد فقط الدورات في فرع المستخدم
            ->where(function ($query) use ($request) {
                $query->where('courses.start', '>=', $request->start_date)
                    ->where('courses.start', '<=', $request->end_date);
                if (isset($request->approved)) {
                    $query->where('courses.approved', '=', $request->approved);
                }
            })
            ->where(function ($query) use ($filter) { // التحقق من وجود نتائج بعد تطبيق الفلتر
                $query->where('subjects.subjectName', 'like', "%$filter%");
            })
            ->paginate(5);

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
        $approved = 1;
        $course->update([
           'approved'  => $approved,
        ]);

        return response()->json([
           'course' => $course,
        ]);

    }

    // public function search(){
    //     $filterResult = DB::table('courses')
    //     ->join('branches', 'courses.branch_id', '=', 'branches.id')
    //     ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
    //     ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
    //     ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
    //     ->select('subjects.subjectName','subjects.content','subjects.price','subjects.houers','subjects.number_of_lessons','courses.start','courses.end','branches.name','branches.No','users.first_name','users.last_name')
    //     // ->where('branches.id', '=', $branchId) // تحديد فقط الدورات في فرع المستخدم
    //     ->paginate(10);
        
    //     if ($filterResult->count() > 0) {
    //         return $this->traitResponse($filterResult, 'Search Successfully', 200);
    //     } else {
    //         return $this->traitResponse(null, 'No matching results found', 200);
    //     }
    // } 
    

    // public function searchbybranch($filter)
    // {   
    //     if (auth()->check()) {
    //         $branchId = Auth::user()->branch_id;
    //         if($filter != "null"){

    //         $filterResult = DB::table('courses')
    //             ->join('branches', 'courses.branch_id', '=', 'branches.id')
    //             ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
    //             ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
    //             ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
    //             ->select('subjects.subjectName','subjects.content','subjects.price','subjects.houers','subjects.number_of_lessons','courses.approved','courses.start','courses.end','branches.name','branches.No','users.first_name','users.last_name')
    //             ->where('branches.id', '=', $branchId) // تحديد فقط الدورات في فرع المستخدم
    //             ->where(function ($query) use ($filter) { // التحقق من وجود نتائج بعد تطبيق الفلتر
    //                 $query->where('courses.start', 'like', "%$filter%")
    //                     ->orWhere('courses.end', 'like', "%$filter%");
    //             })
    //             ->paginate(10);
    //         }
    //         else{

                
            // $filterResult = DB::table('courses')
            // ->join('branches', 'courses.branch_id', '=', 'branches.id')
            // ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            // ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
            // ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
            // ->select('subjects.subjectName','subjects.content','subjects.price','subjects.houers','subjects.number_of_lessons','courses.start','courses.end','branches.name','branches.No','users.first_name','users.last_name')
            // ->where('branches.id', '=', $branchId) // تحديد فقط الدورات في فرع المستخدم
            // ->paginate(10);
            // }
            // if ($filterResult->count() > 0) {
            //     return $this->traitResponse($filterResult, 'Search Successfully', 200);
            // } else {
            //     return $this->traitResponse(null, 'No matching results found', 200);
            // }
        // } else {
    //         return $this->traitResponse(null, 'User not authenticated', 401);
    //     }}
    // }
        
    public function searchBybranch(Request $request)
    {
    
            $validation = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'approved' => 'nullable|integer',
                'branchId' => 'required',
            ]);
    
            if ($validation->fails()) {
                return $this->traitResponse(null, $validation->errors()->first(), 422);
            }
    
            $filterResult = DB::table('courses')
                ->join('branches', 'courses.branch_id', '=', 'branches.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->select('courses.id','subjects.subjectName','subjects.price','courses.start','courses.end','courses.min_students','courses.max_students')
                ->where(function ($query) use ($request) {
                    $query->where('courses.start', '>=', $request->start_date)
                        ->where('courses.start', '<=', $request->end_date)
                        ->where('courses.branch_id', '=', $request->branchId);

                    if (isset($request->approved)) {
                        $query->where('courses.approved', '=', $request->approved);
                    }
                })
                ->paginate(5);
    
            if ($filterResult->count() > 0) {
                return $this->traitResponse($filterResult, 'Search Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No matching results found', 200);
            }

    }




    public function changeApproved()
    {

        $today = date('Y-m-d');
        $courses = DB::table('courses')->where('start', '>=', $today)->get();

        foreach ($courses as $course) {
            if ($course->start == $today && $course->approved == 1) {
                $course->approved = 2;
            } elseif ($course->approved == 2 && $course->end == $today) {
                $course->approved = 3;
            } elseif ($course->approved == 0 && $course->start < $today) {
                $course->approved = 4;
            }
            $course->save();
        }
    
    
   
}









// Add By Samar

public function GetCoursesByTrainerId()
{
    $UserId = Auth::user()->id;
    $threeMonthsAgo = \Carbon\Carbon::now()->subMonths(3)->format('Y-m-d');
     if (auth()->check()) {
    

        $Result = DB::table('courses')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->join('trainer_profiles', 'courses.trainer_id', '=', 'trainer_profiles.id')
            ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
            ->select('courses.*','subjects.subjectName','subjects.houers','subjects.price')
            ->whereDate('courses.start', '>=', $threeMonthsAgo)
            ->where('users.id','=',$UserId)
           // ->where('branches.id', '=', $id)
            ->orderBy('courses.id', 'desc')
            ->paginate(10);
            

        if ($Result->count() > 0) {
            return $this->traitResponse($Result, 'تم العرض بنجاح', 200);
        } else {
            return $this->traitResponse(null, 'لا يوجد نتائج', 200);
        }
    } else {
        return $this->traitResponse(null, 'User not authenticated', 401);
    }
}



}



