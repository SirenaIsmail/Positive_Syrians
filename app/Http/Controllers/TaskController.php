<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Task;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    use ApiResponse;


    public function index()
    {
        $tasksdata = Task::paginate(PAGINATION_COUNT);

        $tasksdata = Task::get();
        $tasksdata->options = json_decode($tasksdata->options);
        if ($tasksdata){
            return $this->traitResponse($tasksdata,'SUCCESS',200);

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
  //          'trainer_id'=>'required|integer',
            'course_id'=>'required|integer',
            'lesson_number'=>'required|integer',
            'the_question'=>'required',
            'options'=>'required|json',
            'answer'=>'required|integer',
        ]);
        $trainer_id = Auth::id();

        $task = Task::create([
            'trainer_id'=>$trainer_id,
            'course_id'=>$request->course_id,
            'lesson_number'=>$request->lesson_number,
            'the_question'=>$request->the_question,
            'options'=>$options = json_encode($request->options),
            'answer'=>$request->answer,
        ]);
        return response()->json([
            'Task'=>$task,
        ]);
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */



    public function show($id)
    {
        $taskdata = Task::find($id);
        if($taskdata){
            return $this->traitResponse($taskdata,'SUCCESS',200);
        }
       return $this->traitResponse(null,'Sorry Not Found ',404);


    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */



    public function update(Request $request, $id)
    {
        $taskdata = Task::find($id);
        if(!$taskdata)
        {
            return $this->traitResponse(null,'Sorry Not Found ' ,404);

        }
        $request->validate([
//            'trainer_id'=>'required|integer',
            'course_id'=>'required|integer',
            'lesson_number'=>'required|integer',
            'the_question'=>'required',
            'options'=>'required|json',
            'answer'=>'required|integer',
        ]);
        $trainer_id = Auth::id();
        $taskdata->update([
            'trainer_id'=>$trainer_id,
            'course_id'=>$request->course_id,
            'lesson_number'=>$request->lesson_number,
            'the_question'=>$request->the_question,
            'options'=>$request->options,
            'answer'=>$request->answer,
        ]);
        if($taskdata)
        {
            return $this->traitResponse($taskdata,'Updated Successfully ',200);

        }


        return $this->traitResponse(null,' Updated Failed',400);


    }


    public function destroy($id)
    {
        $taskdata = Task::find($id);

        if(!$taskdata)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $taskdata->delete($id);

        if($taskdata)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);
    }
}
