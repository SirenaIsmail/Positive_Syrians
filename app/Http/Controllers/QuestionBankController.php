<?php

namespace App\Http\Controllers;

use App\Models\QuestionBank;
use App\Models\Subscribe;
use App\Models\Coures;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;


class QuestionBankController extends Controller
{

    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataQb = QuestionBank::paginate(3);

        if($dataQb)
        {
            return $this->traitResponse($dataQb,'SUCCESS', 200);

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'course_id'=> 'required|integer',
            'model'=> 'required',
            'file'=> 'required',
            'branch_id'=> 'required|integer',


        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }
        $file = $request->file('file');
        if (!$file) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
        if (!$file->isValid()) {
            return response()->json(['error' => 'File upload failed.'], 400);
        }

        $path = Storage::disk('public')->put('uploads', $file);

        $dataQb = QuestionBank::create([
            'course_id'=> $request->course_id,
            'model'=> $request->model,
            'file'=> $path,
            'branch_id'=> $request->branch_id,
        ]);

        if($dataQb)
        {

            return  $this ->traitResponse( $dataQb ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataQb = QuestionBank::find($id);

        if($dataQb)
        {
            return $this->traitResponse($dataQb , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionBank $questionBank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataQb = QuestionBank::find($id);

        if(!$dataQb)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'model'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataQb->update($request->all());
        if($dataQb)
        {
            return $this->traitResponse($dataQb , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataQb = QuestionBank::find($id);

        if(!$dataQb)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataQb->delete($id);

        if($dataQb)
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
            $filterResult = DB::table('question_banks')
                ->join('branches', 'branches.id', '=', 'question_banks.branch_id')
                ->join('courses', 'courses.id', '=', 'question_banks.course_id')
                ->join('trainer_profiles', 'trainer_profiles.id', '=', 'courses.trainer_id')
                ->join('users', 'users.id', '=', 'trainer_profiles.user_id')
                ->join('subjects', 'subjects.id', '=', 'courses.subject_id')
                ->select('question_banks.model', 'question_banks.file', 'subjects.subjectName', 'subjects.content')
                ->where('branches.id', '=', $branchId) 
                ->where(function ($query) use ($filter) { // التحقق من وجود نتائج بعد تطبيق الفلتر
                    $query->where('question_banks.model', 'like', "%$filter%");
                })
                ->paginate(2);
                }

                else{
                    $branchId = Auth::user()->branch_id;
                    $filterResult = DB::table('question_banks')
                    //  ->join('branches', 'branches.id', '=', 'question_banks.branch_id')
                        ->join('courses', 'courses.id', '=', 'question_banks.course_id')
                        ->join('trainer_profiles', 'trainer_profiles.id', '=', 'courses.trainer_id')
                        ->join('users', 'users.id', '=', 'trainer_profiles.user_id')
                        ->join('subjects', 'subjects.id', '=', 'courses.subject_id')
                        ->select('question_banks.model', 'question_banks.file', 'subjects.subjectName', 'subjects.content')
                        //->where('branches.id', '=', $branchId) // تحديد فقط الاشتراكات في فرع المستخدم
                        ->paginate(1);

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

   public function search_by_branch($filter){
        $branchId = Auth::user()->branch_id;
        $filterResult = DB::table('question_banks')
        ->join('branches', 'branches.id', '=', 'question_banks.branch_id')
            ->join('courses', 'courses.id', '=', 'question_banks.course_id')
            ->join('trainer_profiles', 'trainer_profiles.id', '=', 'courses.trainer_id')
            ->join('users', 'users.id', '=', 'trainer_profiles.user_id')
            ->join('subjects', 'subjects.id', '=', 'courses.subject_id')
            ->select('question_banks.model', 'question_banks.file', 'subjects.subjectName', 'subjects.content')
            ->where('branches.id', '=', $branchId) // تحديد فقط الاشتراكات في فرع المستخدم
            ->paginate(1);

        
        if ($filterResult->count() > 0) {
        return $this->traitResponse($filterResult, 'Search Successfully', 200);
        }else{
            return $this->traitResponse(null, 'No matching results found', 200);
       
   }

}
}
