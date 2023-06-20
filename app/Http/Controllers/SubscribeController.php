<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\History;
use App\Models\Subscribe;
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

        if (auth()->check())
        {
            $branchId = Auth::user()->branch_id;

            $Result = DB::table('subscribes')
                ->join('subjects', 'subscribes.subject_id', '=', 'subjects.id')
                ->join('cards', 'subscribes.card_id', '=', 'cards.id')
                ->join('branches as card_branch', 'cards.branch_id', '=', 'card_branch.id')
                ->join('users', 'cards.user_id', '=', 'users.id')
                ->join('branches as user_branch', 'users.branch_id', '=', 'user_branch.id')
                ->join('dates', 'subscribes.date_id', '=', 'dates.id')
                ->select('subscribes.state', 'subjects.subjectName', 'subjects.content', 'subjects.price' ,'cards.barcode', 'users.first_name', 'users.last_name', 'users.phone_number','dates.date')
                ->where('user_branch.id', '=', $branchId)
                ->paginate(PAGINATION_COUNT);

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
       // return $this->traitResponse(null,'sorrrrrrryyyyyy',400);
       if (auth()->check()) {
        $branchId = Auth::user()->branch_id;

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


        $topCoursesReq= new Request(
            ['subscribe_id' => $dataSubscribe->id,
                'date_id' => $date_id,
                'branch_id' => $branchId,
            ]);
        $topCourses = ( new TopCourseController)->store($topCoursesReq);


        if($dataSubscribe)
        {

            return  $this ->traitResponse( $dataSubscribe ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);
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
       // return $this->traitResponse( null ,'Show Successfully', 200);

        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;

            $Result = DB::table('subscribes')
                ->join('courses', 'subscribes.course_id', '=', 'courses.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->join('cards', 'subscribes.card_id', '=', 'cards.id')
                ->join('branches as card_branch', 'cards.branch_id', '=', 'card_branch.id')
                ->join('users', 'cards.user_id', '=', 'users.id')
                ->join('branches as user_branch', 'users.branch_id', '=', 'user_branch.id')
                ->join('dates', 'subscribes.date_id', '=', 'dates.id')
                ->select('subscribes.state', 'subjects.subjectName', 'subjects.content', 'subjects.price' ,'cards.barcode', 'users.first_name', 'users.last_name', 'users.phone_number','dates.date')
                ->where('user_branch.id', '=', $branchId)
                ->where('subscribes.id', '=', $id)
                ->get();

            if ($Result->count() > 0) {
                return $this->traitResponse($Result, 'Show Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No  results found', 200);
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

        $dataSubscribe = Subscribe::find($id);

        if(!$dataSubscribe)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'state'=> 'required',

        ]);

        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataSubscribe->update($request->all());
        if($dataSubscribe)
        {
            return $this->traitResponse($dataSubscribe , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);
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

        if(!$dataSubscribe)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataSubscribe->delete($id);

        if($dataSubscribe)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);

    }


    public function search($filter)
    
    {
        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;
            if($filter != "null"){

            $filterResult = DB::table('subscribes')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->join('cards', 'subscribes.card_id', '=', 'cards.id')
            ->join('branches as card_branch', 'cards.branch_id', '=', 'card_branch.id')
            ->join('users', 'cards.user_id', '=', 'users.id')
            ->join('branches as user_branch', 'users.branch_id', '=', 'user_branch.id')
                // 'subjects.subjectName',
                ->select('subscribes.state','subjects.subjectName','subjects.content', 'subjects.price' ,'cards.barcode', 'users.first_name', 'users.last_name', 'users.phone_number')
                ->where('user_branch.id', '=', $branchId) // تحديد فقط الاشتراكات في فرع المستخدم
                ->where(function ($query) use ($filter) { // التحقق من وجود نتائج بعد تطبيق الفلتر
                    $query->where('subscribes.state', 'like', "%$filter%")
                           ->orWhere('users.first_name', 'like', "%$filter%");
                })
                   ->paginate(10);
            }
            else{

                $filterResult = DB::table('subscribes')
                ->join('courses', 'subscribes.course_id', '=', 'courses.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->join('cards', 'subscribes.card_id', '=', 'cards.id')
                ->join('branches as card_branch', 'cards.branch_id', '=', 'card_branch.id')
                ->join('users', 'cards.user_id', '=', 'users.id')
                ->join('branches as user_branch', 'users.branch_id', '=', 'user_branch.id')
                    // 'subjects.subjectName',
                    ->select('subscribes.id','subscribes.state','subjects.subjectName','subjects.price' ,'cards.barcode', 'users.first_name', 'users.last_name', 'users.phone_number')
                    ->where('user_branch.id', '=', $branchId) // تحديد فقط الاشتراكات في فرع المستخدم
                    
                    ->paginate(10);
            }
            if ($filterResult->count() > 0) {
                return $this->traitResponse($filterResult, 'Search Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No matching results found', 200);
            }
        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }

    }

    

    public function searchDate($filter)
    
    {
        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;
    
            $filterResult = DB::table('subscribes')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->join('cards', 'subscribes.card_id', '=', 'cards.id')
            ->join('branches as card_branch', 'cards.branch_id', '=', 'card_branch.id')
            ->join('users', 'cards.user_id', '=', 'users.id')
            ->join('branches as user_branch', 'users.branch_id', '=', 'user_branch.id') 
                ->select('subscribes.state','subjects.subjectName','subjects.content', 'subjects.price' ,'cards.barcode', 'users.first_name', 'users.last_name', 'users.phone_number')
                ->where('user_branch.id', '=', $branchId) // تحديد فقط الاشتراكات في فرع المستخدم
                ->where(function ($query) use ($filter) { // التحقق من وجود نتائج بعد تطبيق الفلتر
                    $query->where('subscribes.state', 'like', "%$filter%")
                           ->orWhere('users.first_name', 'like', "%$filter%");
                })
                   ->paginate(10);
            

            if ($filterResult->count() > 0) {
                return $this->traitResponse($filterResult, 'Search Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No matching results found', 200);
            }
         } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
        }
}
