<?php

namespace App\Http\Controllers;

use App\Models\TrainerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainerProfileController extends Controller
{
    use apiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataTrainerProfile = TrainerProfile::paginate(PAGINATION_COUNT);

        if($dataTrainerProfile)
        {
            return $this->traitResponse($dataTrainerProfile,'SUCCESS', 200);

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
            'rating'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataTrainerProfile = TrainerProfile::create($request -> all());

        if($dataTrainerProfile)
        {

            return  $this ->traitResponse( $dataTrainerProfile ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainerProfile  $trainerProfile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataTrainerProfile = TrainerProfile::find($id);

        if($dataTrainerProfile)
        {
            return $this->traitResponse($dataTrainerProfile , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainerProfile  $trainerProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainerProfile $trainerProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrainerProfile  $trainerProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $dataTrainerProfile = TrainerProfile::find($id);

        if(!$dataTrainerProfile)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'rating'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataTrainerProfile->update($request->all());
        if($dataTrainerProfile)
        {
            return $this->traitResponse($dataTrainerProfile , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);





    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainerProfile  $trainerProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataTrainerProfile = TrainerProfile::find($id);

        if(!$dataTrainerProfile)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataTrainerProfile->delete($id);

        if($dataTrainerProfile)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);


    }
}
