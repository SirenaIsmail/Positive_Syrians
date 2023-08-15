<?php

namespace App\Http\Controllers;

use App\Models\SubjectTrainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SubjectTrainerController extends Controller
{
    use apiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dataSubjectTrainer = SubjectTrainer::paginate(PAGINATION_COUNT);

        if($dataSubjectTrainer)
        {
            return $this->traitResponse($dataSubjectTrainer,'SUCCESS', 200);

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
            'subject_id'=> 'required',
            'trainer_id'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataSubjectTrainer = SubjectTrainer::create($request -> all());

        if($dataSubjectTrainer)
        {

            return  $this ->traitResponse( $dataSubjectTrainer ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubjectTrainer  $subjectTrainer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataSubjectTrainer = SubjectTrainer::find($id);

        if($dataSubjectTrainer)
        {
            return $this->traitResponse($dataSubjectTrainer , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);
    }


    
    public function view($id)
    {
    $Result = DB::table('subject_trainers')
    ->join('subjects', 'subjects.id', '=', 'subject_trainers.subject_id')
    ->join('trainer_profiles', 'trainer_profiles.id', '=', 'subject_trainers.trainer_id')
    ->join('users', 'users.id', '=', 'trainer_profiles.user_id')
    ->select( 'subjects.houers','subjects.price','subjects.subjectName', 'subjects.content')
    ->where('trainer_profiles.id', '=', $id) 
    ->get();

if ($Result->count() > 0) {
    return $this->traitResponse($Result, 'Index Successfully', 200);
     } else {
    return $this->traitResponse(null, 'No  results found', 200);
         }
    }







    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubjectTrainer  $subjectTrainer
     * @return \Illuminate\Http\Response
     */
    public function edit(SubjectTrainer $subjectTrainer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubjectTrainer  $subjectTrainer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $dataSubjectTrainer = SubjectTrainer::find($id);

        if(!$dataSubjectTrainer)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'subject_id'=> 'required',
            'trainer_id'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataSubjectTrainer->update($request->all());
        if($dataSubjectTrainer)
        {
            return $this->traitResponse($dataSubjectTrainer , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubjectTrainer  $subjectTrainer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $dataSubjectTrainer = SubjectTrainer::find($id);

        if(!$dataSubjectTrainer)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataSubjectTrainer->delete($id);

        if($dataSubjectTrainer)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);





    }
}
