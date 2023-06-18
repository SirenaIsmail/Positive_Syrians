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
    use apiResponse;
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

             $DebitTotal = StudentAccount::where('user_id', $request->user_id)
             ->where('payment_id', $request->payment_id)
              ->sum('Debit');
 
         $CreditTotal = StudentAccount::where('user_id', $request->user_id)
              ->where('payment_id', $request->payment_id)
              ->sum('Credit');
 
         $availableBalance = $DebitTotal - $CreditTotal;
         $requestedAmount = $request->amount;
         
 
         if ($requestedAmount > $availableBalance) {
             return response()->json(['error' => 'المبلغ المراد معالجته  يتجاوز المبلغ الواجب معالجته.'], 422);
         }
 
         if ($requestedAmount < $availableBalance) {
             return response()->json(['error' => 'المبلغ المراد معالجته  أقل من  المبلغ الواجب معالجته.'], 422);
         }


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
    
    public function update(Request $request, $id)
    {
       
        $dataProcessing = ProcessingFee::find($id);
        if (!$dataProcessing) {
            return $this->traitResponse(null,' Sorry Not Found',404);
        }
    
        DB::beginTransaction();
    
        try {
            
            $dataProcessing->user_id = $request->user_id;
            $dataProcessing->amount = $request->amount;
            $dataProcessing->description = $request->description;
            $dataProcessing->date = date('Y-m-d');
            $dataProcessing->save();
            
            $DebitTotal = StudentAccount::where('user_id', $request->user_id)
             ->where('payment_id', $request->payment_id)
              ->sum('Debit');
    

            $CreditTotal = StudentAccount::where('user_id', $request->user_id)
            ->where('payment_id', $request->payment_id)
            ->sum('Credit');

          $availableBalance = $DebitTotal - $CreditTotal;
          $requestedAmount = $request->amount;
        

       if ($requestedAmount > $availableBalance) {
           return response()->json(['error' => 'المبلغ المراد معالجته  يتجاوز المبلغ الواجب معالجته.'], 422);
       }

       if ($requestedAmount < $availableBalance) {
           return response()->json(['error' => 'المبلغ المراد معالجته  أقل من  المبلغ الواجب معالجته.'], 422);
       }


            $studentAccount = StudentAccount::where('receipt_id', $id)->first();
            if ($studentAccount) {
                $studentAccount->user_id = $request->user_id;
                $studentAccount->Credit = $request->amount;
                $studentAccount->date = date('Y-m-d');
                $studentAccount->save();
            }
       
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    
        return response()->json(['message' => 'تم تحديث البيانات بنجاح']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProcessingFee  $processingFee
     * @return \Illuminate\Http\Response
     */


    public function destroy($id)
    {
        $dataProcessing= ReceiptStudent::find($id);

        if(!$dataProcessing)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataProcessing->delete($id);

        if($dataProcessing)
        {
            return  $this->traitResponse(null , 'تم الحذف بنجاح ' , 200);

        }
        return  $this->traitResponse(null , 'فشل الحذف' , 404);

         }
    }

