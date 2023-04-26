<?php

namespace App\Http\Controllers;

use App\Models\Suject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SujectController extends Controller
{

    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataSubject = Suject::paginate(PAGINATION_COUNT);

        if($dataSubject)
        {
            return $this->traitResponse($dataSubject,'SUCCESS', 200);

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
            'name'=>'required',
            'content'=>'required',
            'houers'=>'required',
            'number_of_lessons'=>'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataSubject = Suject::create($request -> all());

        if($dataSubject)
        {

            return  $this ->traitResponse( $dataSubject ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);






    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Suject  $suject
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataSubject = Suject::find($id);

        if($dataSubject)
        {
            return $this->traitResponse($dataSubject , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);









    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Suject  $suject
     * @return \Illuminate\Http\Response
     */
    public function edit(Suject $suject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Suject  $suject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataSubject = Suject::find($id);

        if(!$dataSubject)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [

            'number_of_lessons'=>'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataSubject->update($request->all());
        if($dataSubject)
        {
            return $this->traitResponse($dataSubject , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);





    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Suject  $suject
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataSubject = Suject::find($id);

        if(!$dataSubject)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataSubject->delete($id);

        if($dataSubject)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);




    }
}
