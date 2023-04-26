<?php

namespace App\Http\Controllers;

use App\Models\TrainerRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainerRatingController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dataTrainerRating = TrainerRating::paginate(PAGINATION_COUNT);

        if($dataTrainerRating)
        {
            return $this->traitResponse($dataTrainerRating,'SUCCESS', 200);

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

        $dataTrainerRating = TrainerRating::create($request -> all());

        if($dataTrainerRating)
        {

            return  $this ->traitResponse( $dataTrainerRating ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);









    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainerRating  $trainerRating
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataTrainerRating = TrainerRating::find($id);

        if($dataTrainerRating)
        {
            return $this->traitResponse($dataTrainerRating , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);






    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainerRating  $trainerRating
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainerRating $trainerRating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrainerRating  $trainerRating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataTrainerRating = TrainerRating::find($id);

        if(!$dataTrainerRating)
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

        $dataTrainerRating->update($request->all());
        if($dataTrainerRating)
        {
            return $this->traitResponse($dataTrainerRating , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);










    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainerRating  $trainerRating
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataTrainerRating = TrainerRating::find($id);

        if(!$dataTrainerRating)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataTrainerRating->delete($id);

        if($dataTrainerRating)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);







    }
}
