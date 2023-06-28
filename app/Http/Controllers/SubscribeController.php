<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\History;
use App\Models\Subscribe;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscribeController extends Controller
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

        if (auth()->check())
        {
            $branchId = Auth::user()->branch_id;

            $Result = DB::table('subscribes')
                 ->join('courses', 'subscribes.course_id', '=', 'courses.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->join('cards', 'subscribes.card_id', '=', 'cards.id')
                ->join('branches', 'subscribes.branch_id', '=', 'branches.id')
                ->join('users', 'cards.user_id', '=', 'users.id')
                ->select('subjects.subjectName','subscribes.state', 'cards.barcode', 'users.first_name', 'users.last_name', 'users.phone_number','subscribes.date')
                ->where('branches.id', '=', $branchId)
                ->whereBetween('subscribes.date', [$threeMonthsAgo, date('Y-m-d')])
                ->orderBy('subscribes.date', 'desc')
                ->paginate(10);
               

            if ($Result->count() > 0) {
                return $this->traitResponse($Result, 'تم عرض البيانات بنجاخ', 200);
            } else {
                return $this->traitResponse(null, 'لا يوجد نتائج', 200);
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
        if (auth()->check()) {
        $branchId = Auth::user()->branch_id;
              
        $existingSubscribe = Subscribe::where('course_id', $request->course_id)
        ->where('card_id', $request->card_id)
        ->exists();
      if ($existingSubscribe) {
       return $this->traitResponse(null, 'الطالب مشترك بالفعل في هذا الكورس', 400);
        }
   
    

        $date = date('Y-m-d');
        $date_id = DB::table('dates')
        ->where('date', $date)
        ->value('id');
        $state = 1;
        $dataSubscribe = Subscribe::create([
        'course_id'=> $request->course_id,
        'card_id'=>  $request->card_id,
        'branch_id'=> $branchId,
        'date'=>$date,
        'state'=>  $state,
        ]);

            // جمع الاشتراكات التابعة لنفس الكورس
       $course = Course::findOrFail($request->course_id);
       $subscribesCount = $course->subscribes()
             ->whereIn('state', [1,2,4])
             ->where('course_id', $request->course_id)
            ->count();
            // مقارنة عدد الاشتراكات مع min_students
            if ($subscribesCount == $course->min_students) {
                // تحديث حالة الكورس إلى الموافقة
                $course->approved = 1;
                $course->save();
            }


        $topCoursesReq= new Request(
            ['subscribe_id' => $dataSubscribe->id,
                'date_id' => $date_id,
                'branch_id' => $branchId,
            ]);
        $topCourses = ( new TopCourseController)->store($topCoursesReq);


        if($dataSubscribe)
        {

            return  $this ->traitResponse( $dataSubscribe ,'تم حفظ البيانات بنجاح' , 200 );
        }

        return  $this->traitResponse(null,'عذراً فشل الحفظ' , 400);
    }  else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
    }







    public function attend($id){
        $Subscribe = Subscribe::find($id);
        if($Subscribe)
        {
            $Subscribe->state = 1;
            $Subscribe->save();

            $card_id = $Subscribe->card_id;  /// عند إضافة المعتمدين إلى سجل الحضور
            $course_id = $Subscribe->course_id;
            $historydata= new Request(
                ['card_id' =>$card_id,
                    'course_id' => $course_id,
                ]);
            $history = ( new HistoryController)->store($historydata);

            return response()->json([
                'Subscribe'=>$Subscribe,
                'history' => $history,
            ]);

        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);

    }




    public function notAttend($id){
        $Subscribe = Subscribe::find($id);
        if($Subscribe)
        {
            $Subscribe->state = 0;
            $Subscribe->save();

            $card_id = $Subscribe->card_id;  /// عند إضافة المعتمدين إلى سجل الحضور
            $course_id = $Subscribe->course_id;
            $historydata= History::with(['card', 'course'])
                ->whereHas('card', function ($query) use ($card_id) {
                $query->where('id', $card_id);
            })->whereHas('course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->first();
            $history = ( new HistoryController);
            $history->destroy($historydata);

            return response()->json([
                'Subscribe' => $Subscribe,
            ]);

        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);

    }

    public function pending($id){
        $Subscribe = Subscribe::find($id);
        if($Subscribe)
        {
            $Subscribe->state = 2;
            $Subscribe->save();

            //something in history

            return response()->json([
                'Subscribe' => $Subscribe,
            ]);

        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (auth()->check())
        {
            $branchId = Auth::user()->branch_id;

            $Result = DB::table('subscribes')
                 ->join('courses', 'subscribes.course_id', '=', 'courses.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->join('cards', 'subscribes.card_id', '=', 'cards.id')
                ->join('branches', 'subscribes.branch_id', '=', 'branches.id')
                ->join('users', 'cards.user_id', '=', 'users.id')
                ->select('subjects.subjectName','subscribes.state', 'cards.barcode', 'users.first_name', 'users.last_name', 'users.phone_number','subscribes.date')
                ->where('branches.id', '=', $branchId)
                ->where('subscribes.id', '=', $id)
                ->get(10);
               

            if ($Result->count() > 0) {
                return $this->traitResponse($Result, 'تم العرض  بنجاح', 200);
            } else {
                return $this->traitResponse(null, 'لا يوجد نتيحة', 200);
            }
        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscribe $subscribe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)

    {

     }

    


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */

     public function destroy($id)
     {
         $dataSubscribe = Subscribe::find($id);
     
         if (!$dataSubscribe) {
            return $this->traitResponse(null, 'غير موجود', 404);
        }
     
       
         $course = Course::find($dataSubscribe->course_id);
         
         if ($dataSubscribe->state != 1 || date('Y-m-d') > $course->start)
         {
             return $this->traitResponse(null, 'لا يمكن حذف الاشتراك', 400);
         }
     
        

         $dataSubscribe->delete($id);


         
         $subscribesCount = $course->subscribes()
              ->where('course_id', $dataSubscribe->course_id)
                ->count();
     
                if ($course->approved == 1 && $subscribesCount < $course->min_students) {
                    $course->approved = 0;
                    $course->save();
                }
     
         if ($dataSubscribe) {
             return $this->traitResponse(null, 'تم الحذف بنجاح', 200);
         }
     
         return $this->traitResponse(null, 'عذراً فشل الحذف', 404);
     
     }





    public function search($filter)

    {

        $fiveMonthsAgo = \Carbon\Carbon::now()->subMonths(5)->format('Y-m-d');
        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;

                $filterResult = DB::table('subscribes')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
           ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
           ->join('cards', 'subscribes.card_id', '=', 'cards.id')
           ->join('branches', 'subscribes.branch_id', '=', 'branches.id')
           ->join('users', 'cards.user_id', '=', 'users.id')
           ->select('subjects.subjectName','subscribes.state', 'cards.barcode', 'users.first_name', 'users.last_name', 'users.phone_number','subscribes.date')
           ->where('branches.id', '=', $branchId)
           ->whereBetween('subscribes.date', [$fiveMonthsAgo, date('Y-m-d')])
           ->orderBy('subscribes.date', 'desc')
           ->where(function ($query) use ($filter) { // التحقق من وجود نتائج بعد تطبيق الفلتر
            $query->where('users.first_name', 'like', "%$filter%");
                
                })
                    ->paginate(10);
            
            if ($filterResult->count() > 0) {
                return $this->traitResponse($filterResult, 'تم البحث بنجاح', 200);
            } else {
                return $this->traitResponse(null, 'عذراً لا يوجد نتيجة ', 200);
            }
        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }

    }

    public function searchDate(Request $request, $filter =null)
    {

         if (auth()->check()) {
           $branchId = Auth::user()->branch_id;
    
            $startDate =$request->input('start_date');
            $endDate = $request->input('end_date');
            
                if ($startDate <= $endDate) {
            
            $filterResult = DB::table('subscribes')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
           ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
           ->join('cards', 'subscribes.card_id', '=', 'cards.id')
           ->join('branches', 'subscribes.branch_id', '=', 'branches.id')
           ->join('users', 'cards.user_id', '=', 'users.id')
           ->select('subjects.subjectName','subscribes.state', 'cards.barcode', 'users.first_name', 'users.last_name', 'users.phone_number','subscribes.date')
           ->where('branches.id', '=', $branchId)
           ->whereBetween('subscribes.date', [$startDate, $endDate])
           ->where(function ($query) use ($filter) { // التحقق من وجود نتائج بعد تطبيق الفلتر
            $query->where('subscribes.state', 'like', "%$filter%")
                   ->orWhere('subjects.subjectName', 'like', "%$filter%");
        })
           ->paginate(10);
            } else {
                
                return "تاريخ البدء يجب أن يكون أصغر من تاريخ الانتهاء";
            
        
            }
        

        if ($filterResult->count() > 0) {
            return $this->traitResponse($filterResult, 'تم البحث بنجاح', 200);
        } else {
            return $this->traitResponse(null, 'عذراً لا يوجد نتيجة', 200);
        }
    } else {
        return $this->traitResponse(null, 'User not authenticated', 401);
    }



    }


}




