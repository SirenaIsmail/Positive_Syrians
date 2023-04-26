<?php

namespace App\Http\Controllers;

use App\Models\Attend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataAttend = Attend::paginate(PAGINATION_COUNT);

        if($dataAttend)
        {
            return $this->traitResponse($dataAttend,'SUCCESS', 200);

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

        $dataAttend = Attend::create($request -> all());

        if($dataAttend)
        {

            return  $this ->traitResponse( $dataAttend ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataAttend = Attend::find($id);

        if($dataAttend)
        {
            return $this->traitResponse($dataAttend , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);






    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function edit(Attend $attend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $dataAttend = Attend::find($id);

        if(!$dataAttend)
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

        $dataAttend->update($request->all());
        if($dataAttend)
        {
            return $this->traitResponse($dataAttend , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataAttend = Attend::find($id);

        if(!$dataAttend)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataAttend->delete($id);

        if($dataAttend)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);









    }
}
