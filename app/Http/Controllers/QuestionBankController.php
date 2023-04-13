<?php

namespace App\Http\Controllers;

use App\Models\QuestionBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $dataQb = QuestionBank::get();

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'model'=> 'required',


        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataQb = QuestionBank::create($request -> all());

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
    public function update(Request $request,$id)
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
}
