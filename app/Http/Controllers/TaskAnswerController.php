<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAnswer;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;

class TaskAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use ApiResponse;


    public function index()
    {
        $taskansdata = TaskAnswer::get();
        if ($taskansdata){
            return $this->traitResponse($taskansdata,'SUCCESS',200);

        }
        return $this->traitResponse(null, 'Sorry Not Found',404);
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
     * @return \Illuminate\Http\JsonResponse
     */


    public function store(Request $request)
    {

        $request->validate([
            'task_id'=>'required|integer',
            'answer'=>'required|integer',
//            'flag'=>'required|boolean',
//            'student_id'=>'required|integer',
        ]);
        $student_id = Auth::id();
        $task = Task::find($request->task_id);
        if ($request->answer == $task->answer){
            $flag = true;
        }else{
            $flag = false;
        }
        $taskans = TaskAnswer::create([
            'task_id'=>$request->task_id,
            'answer'=>$request->answer,
            'flag'=>$flag,
            'student_id'=>$student_id,
        ]);
        return response()->json([
            'Task Answer'=>$taskans,
        ]);
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskAnswer  $taskAnswer
     * @return \Illuminate\Http\Response
     */



    public function show($id)
    {
        $taskansdata = TaskAnswer::find($id);
        if($taskansdata){
            return $this->traitResponse($taskansdata,'SUCCESS',200);
        }
        return $this->traitResponse(null,'Sorry Not Found ',404);

    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskAnswer  $taskAnswer
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskAnswer $taskAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskAnswer  $taskAnswer
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {
        $taskansdata = TaskAnswer::find($id);
        if(!$taskansdata)
        {
            return $this->traitResponse(null,'Sorry Not Found ' ,404);

        }

        $request->validate([
            'task_id'=>'required|integer',
            'answer'=>'required|integer',
//            'flag'=>'required|boolean',
//            'student_id'=>'required|integer',
        ]);
        $student_id = Auth::id();
        $task = Task::find($request->task_id);
        if ($request->answer == $task->answer){
            $flag = true;
        }else{
            $flag = false;
        }
        $taskansdata->update([
            'task_id'=>$request->answer,
            'answer'=>$request->answer,
            'flag'=>$flag,
            'student_id'=>$student_id,
            ]);
        if($taskansdata)
        {
            return $this->traitResponse($taskansdata,'Updated Successfully ',200);

        }


        return $this->traitResponse(null,' Updated Failed',400);


    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskAnswer  $taskAnswer
     * @return \Illuminate\Http\Response
     */



    public function destroy($id)
    {
        $taskansdata = TaskAnswer::find($id);

        if(!$taskansdata)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $taskansdata->delete($id);

        if($taskansdata)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);
    }
}
