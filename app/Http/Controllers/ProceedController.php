<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Proceed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProceedController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataProceed = Proceed::get();

        if($dataProceed)
        {
            return $this->traitResponse($dataProceed,'SUCCESS', 200);

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
            'date_id'=>'required',
            'branch_id'=>'required',
            'payment_id'=>'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataProceed = Proceed::create($request -> all());

        if($dataProceed)
        {

            return  $this ->traitResponse( $dataProceed ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proceed  $proceed
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataProceed = Proceed::find($id);

        if($dataProceed)
        {
            return $this->traitResponse($dataProceed , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);





    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proceed  $proceed
     * @return \Illuminate\Http\Response
     */
    public function edit(Proceed $proceed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proceed  $proceed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataProceed = Proceed::find($id);

        if(!$dataProceed)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'date_id'=>'required',
            'branch_id'=>'required',
            'payment_id'=>'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataProceed->update($request->all());
        if($dataProceed)
        {
            return $this->traitResponse($dataProceed , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);





    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proceed  $proceed
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataProceed = Proceed::find($id);

        if(!$dataProceed)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataProceed->delete($id);

        if($dataProceed)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);




    }
}
