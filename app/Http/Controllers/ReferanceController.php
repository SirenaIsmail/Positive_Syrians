<?php

namespace App\Http\Controllers;

use App\Models\Referance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReferanceController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataReferance = Referance::paginate(PAGINATION_COUNT);

        if($dataReferance)
        {
            return $this->traitResponse($dataReferance,'SUCCESS', 200);

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
            'lesson_number'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataReferance = Referance::create($request -> all());

        if($dataReferance)
        {

            return  $this ->traitResponse( $dataReferance ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);






    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Referance  $referance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataReferance = Referance::find($id);

        if($dataReferance)
        {
            return $this->traitResponse($dataReferance , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);




    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Referance  $referance
     * @return \Illuminate\Http\Response
     */
    public function edit(Referance $referance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Referance  $referance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

        $dataReferance = Referance::find($id);

        if(!$dataReferance)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'lesson_number'=> 'required ',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataReferance->update($request->all());
        if($dataReferance)
        {
            return $this->traitResponse($dataReferance , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Referance  $referance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $dataReferance = Referance::find($id);

        if(!$dataReferance)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataReferance->delete($id);

        if($dataReferance)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);





    }
}
