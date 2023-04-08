<?php

namespace App\Http\Controllers;

use App\Models\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DateController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dataDate=Date::get();

        if($dataDate)
        {
            return $this->traitResponse($dataDate,'SUCCESS',200);

        }

        return $this->traitResponse(null,'Sorry  Not Found',404);




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

        $validation= Validator::make($request->all(),[

            'day'=>'required',
            'mounth'=>'required',
            'year'=>'required',


        ]);

        if($validation->fails())
        {

            return $this->traitResponse( null ,$validation->errors(),400);

        }

        $dataDate = Date::create($request->all());

        if($dataDate)
        {
            return $this->traitResponse( $dataDate ,'Saved Successfully',200);

        }


        return $this->traitResponse(null,'Saved Failed ',400);




    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataDate = Date::find($id);

        if($dataDate)
        {
            return $this->traitResponse($dataDate , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function edit(Date $date)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id )
    {

        $dataDate = Date::find($id);

        if(!$dataDate)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'day'=> 'required',
            'mounth'=> 'required',
            'year'=> 'required',
        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataDate->update($request->all());
        if($dataDate)
        {
            return $this->traitResponse($dataDate , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $dataDate = Date::find($id);

        if(!$dataDate)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataDate->delete($id);

        if($dataDate)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);



    }
}
