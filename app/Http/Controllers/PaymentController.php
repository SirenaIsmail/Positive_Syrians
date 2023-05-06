<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dataPayment = Payment::paginate(PAGINATION_COUNT);

        if($dataPayment)
        {
            return $this->traitResponse($dataPayment,'SUCCESS', 200);

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
            'subscribe_id'=> 'required',
            'ammount'=> 'required',
            'subammount'=> 'required',


        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataPayment = Payment::create($request -> all());

        if($dataPayment)
        {

            return  $this ->traitResponse( $dataPayment ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);





    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataPayment = Payment::find($id);

        if($dataPayment)
        {
            return $this->traitResponse($dataPayment , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);




    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {


        $dataPayment = Payment::find($id);

        if(!$dataPayment)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'branch_id'=> 'required',
            'subscribe_id'=> 'required',
            'ammount'=> 'required',
            'subammount'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataPayment->update($request->all());
        if($dataPayment)
        {
            return $this->traitResponse($dataPayment , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        $dataPayment = Payment::find($id);

        if(!$dataPayment)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataPayment->delete($id);

        if($dataPayment)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);



    }


    public  function  createPayment($id)

    {
        $payment = Payment::findorfail($id);
        $subscribe = Subscribe::where('subscribe_id',$payment->id)->get('');

        return $this->traitResponse($subscribe , 'Updated Successfully',200);




    }


}
