<?php

namespace App\Http\Controllers;

use App\Models\ProcessingFee;
use Illuminate\Http\Request;
use App\Models\StudentAccount;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProcessingFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function store(Request $request)
    {

        DB::beginTransaction();

        try{

             $processing_fee = new ProcessingFee();
             $processing_fee->user_id = $request->user_id;
             $processing_fee->amount = $request->amount;
             $processing_fee->description = $request->description;
             $processing_fee->date = date('Y-m-d');
             $processing_fee->save();



            $studentAccount = new StudentAccount();
            $studentAccount->user_id = $request->user_id;
            $studentAccount->payment_id = $request->payment_id;
            $studentAccount->processing_id = $processing_fee->id;
            $studentAccount->type = 'processing';
            $studentAccount->Debit = 0.00;
            $studentAccount->Credit =  $request->amount;
            $studentAccount->date = date('Y-m-d');
            $studentAccount->save();
        
        

        DB::commit();


 }

     catch( \Exception $e){
        
         DB::rollback();
         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
     }



      if($processing_fee)
     {
        return response()->json(['message' => 'تم إضافة البيانات بنجاح']);

     }


    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProcessingFee  $processingFee
     * @return \Illuminate\Http\Response
     */


    public function show(ProcessingFee $processingFee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProcessingFee  $processingFee
     * @return \Illuminate\Http\Response
     */
    public function edit(ProcessingFee $processingFee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProcessingFee  $processingFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProcessingFee $processingFee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProcessingFee  $processingFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProcessingFee $processingFee)
    {
        //
    }
}
