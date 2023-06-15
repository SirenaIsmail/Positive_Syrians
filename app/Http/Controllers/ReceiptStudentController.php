<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\FundAccount;
use App\Models\ReceiptStudent;
use App\Models\StudentAccount;
use App\Models\ProcessingFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ReceiptStudentController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        DB::beginTransaction();

        try{

             $receipt_student = new ReceiptStudent();
             $receipt_student->user_id = $request->user_id;
             $receipt_student->Debit = $request->Debit;
             $receipt_student->description = $request->description;
             $receipt_student->date = date('Y-m-d');
             $receipt_student->save();


             $fund_account = new FundAccount();
             $fund_account->receipt_id = $receipt_student->id;
             $fund_account->Debit = $request->Debit;
             $fund_account->Credit = 0.00;
             $fund_account->description = $request->description;
             $fund_account->date = date('Y-m-d');
             $fund_account->save();


            $studentAccount = new StudentAccount();
            $studentAccount->user_id = $request->user_id;
            $studentAccount->payment_id =  $request->payment_id;
            $studentAccount->receipt_id = $receipt_student->id;
            $studentAccount->type = 'receipt';
            $studentAccount->Debit = 0.00;
            $studentAccount->Credit = $request->Debit;
            $studentAccount->date = date('Y-m-d');
            $studentAccount->save();
        
        

        DB::commit();

 }
 
     catch( \Exception $e){
        
         DB::rollback();
         return redirect()->back()->withErrors(['error' => $e->getMessage()]);

     }

     if($receipt_student)
     {
        return response()->json(['message' => 'تم إضافة البيانات بنجاح']);

     }
    }

     
  
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    public function show(ReceiptStudent $receiptStudent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    public function edit(ReceiptStudent $receiptStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReceiptStudent $receiptStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReceiptStudent $receiptStudent)
    {
        //
    }
}
