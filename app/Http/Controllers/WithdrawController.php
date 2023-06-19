<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Withdraw;
use App\Models\FundAccount;
use App\Models\ReceiptStudent;
use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class WithdrawController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $Result = DB::table('withdraws')
        ->join('users', 'users.id', '=', 'withdraws.user_id')
        ->join('student_accounts', 'student_accounts.withdraw_id', '=', 'withdraws.id')
        ->join('payments', 'payments.id', '=', 'student_accounts.payment_id')
        ->select('users.first_name','users.last_name','users.phone_number',
            'payments.ammount','withdraws.date','withdraws.amount')
        ->paginate(10);
   
    
    if ($Result->count() > 0) {
        return $this->traitResponse($Result, 'تم الحصول على البيانات بنجاح', 200);
    } else {
        return $this->traitResponse(null, ' لا يوجد نتائج ', 200);
    }

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

             $withdraw = new Withdraw();
             $withdraw->user_id = $request->user_id;
             $withdraw->amount = $request->amount;
             $withdraw->description = $request->description;
             $withdraw->date = date('Y-m-d');
             $withdraw->save();


             $fund_account = new FundAccount();
             $fund_account->withdraw_id = $withdraw->id;
             $fund_account->Debit =  0.00;
             $fund_account->Credit =$request->amount;
             $fund_account->description = $request->description;
             $fund_account->date = date('Y-m-d');
             $fund_account->save();


            $studentAccount = new StudentAccount();
            $studentAccount->user_id = $request->user_id;
            $studentAccount->payment_id =  $request->payment_id;
            $studentAccount->withdraw_id = $withdraw->id;
            $studentAccount->type = 'withdraw';
            $studentAccount->Debit = 0.00;
            $studentAccount->Credit =0.00;
            $studentAccount->date = date('Y-m-d');
            $studentAccount->save();
        
            // $debitTotal = StudentAccount::where('user_id', $request->user_id)
            // ->where('payment_id', $request->payment_id)
            // ->sum('Debit');
        
            $creditTotal = StudentAccount::where('user_id', $request->user_id)
            ->where('payment_id', $request->payment_id)
            ->where('type', 'receipt')
            ->sum('Credit');
        
        $availableBalance =  $creditTotal;
       
        $requestedAmount = $request->amount;
  
        // if ($requestedAmount < 3000) {
        //     return response()->json(['error' => 'لا يمكن إدخال قيمة أقل من 3000.'], 422);
        // }
        
        if ($requestedAmount > $availableBalance) {
            return response()->json(['error' => 'المبلغ  المراد سحبه  يتجاوز المبلغ الذي قام الطالب بدفعه.'], 422);
        }
        

        DB::commit();

 }
 
     catch( \Exception $e){
        
         DB::rollback();
         return redirect()->back()->withErrors(['error' => $e->getMessage()]);

     }
     if($withdraw)
     {
        return response()->json(['message' => 'تم إضافة البيانات بنجاح']);

     }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Result = DB::table('withdraws')
        ->join('users', 'users.id', '=', 'withdraws.user_id')
        ->join('student_accounts', 'student_accounts.withdraw_id', '=', 'withdraws.id')
        ->join('payments', 'payments.id', '=', 'student_accounts.payment_id')
        ->select('users.first_name','users.last_name','users.phone_number',
            'payments.ammount','withdraws.date','withdraws.amount')
            ->where('withdraws.id', '=', $id) 
            ->get();
   
    
    if ($Result->count() > 0) {
        return $this->traitResponse($Result, 'تم الحصول على البيانات بنجاح', 200);
    } else {
        return $this->traitResponse(null, ' لا يوجد نتائج ', 200);
    }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdraw $withdraw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $datawithdraw = Withdraw::find($id);
        if (!$datawithdraw) {
            return $this->traitResponse(null,' Sorry Not Found',404);
        }


        DB::beginTransaction();
    
        try{
    
            $datawithdraw->user_id = $request->user_id;
            $datawithdraw->amount = $request->amount;
            $datawithdraw->description = $request->description;
            $datawithdraw->date = date('Y-m-d');
            $datawithdraw->save();
            
            $fund_account = FundAccount::where('withdraw_id', $id)->first();
            $fund_account->Credit = $request->amount;
            $fund_account->description = $request->description;
            $fund_account->save();
    
            $studentAccount = StudentAccount::where('withdraw_id', $id)->first();
            $studentAccount->Debit = 0.00;
            $studentAccount->Credit = 0.00;
            $studentAccount->save();


            $creditTotal = StudentAccount::where('user_id', $request->user_id)
            ->where('payment_id', $request->payment_id)
            ->where('type', 'receipt')
            ->sum('Credit');
        
        $availableBalance =  $creditTotal;
       
        $requestedAmount = $request->amount;

        if ($requestedAmount > $availableBalance) {
            return response()->json(['error' => 'المبلغ  المراد سحبه  يتجاوز المبلغ الذي قام الطالب بدفعه.'], 422);
        }
        
    
            DB::commit();
    
        }
        catch( \Exception $e){
    
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    
        }
    
        if($datawithdraw)
        {
            return response()->json(['message' => 'تم تحديث البيانات بنجاح']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $datawithdraw= Withdraw::find($id);

        if(!$datawithdraw)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $datawithdraw->delete($id);

        if($datawithdraw)
        {
            return  $this->traitResponse(null , 'تم الحذف بنجاح ' , 200);

        }
        return  $this->traitResponse(null , 'فشل الحذف' , 404);
    }
}
