<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Subscribe;
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
    
        try {
      
    
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
            $studentAccount->payment_id = $request->payment_id;
            $studentAccount->receipt_id = $receipt_student->id;
            $studentAccount->type = 'receipt';
            $studentAccount->Debit = 0.00;
            $studentAccount->Credit = $request->Debit;
            $studentAccount->date = date('Y-m-d');
            $studentAccount->save();

            $debitTotal = StudentAccount::where('user_id', $request->user_id)
            ->where('payment_id', $request->payment_id)
            ->sum('Debit');

        $creditTotal = StudentAccount::where('user_id', $request->user_id)
            ->where('payment_id', $request->payment_id)
            ->sum('Credit');

        $availableBalance = $debitTotal - $creditTotal;
        $requestedAmount = $request->Debit;

        if ($requestedAmount < 3000) {
            return response()->json(['error' => 'لا يمكن إدخال قيمة أقل من 3000.'], 422);
        }

        if ($requestedAmount > $availableBalance) {
            return response()->json(['error' => 'المبلغ الذي يريد الطالب دفعه يتجاوز المبلغ الواجب دفعه.'], 422);
        }
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب.'], 500);
        }
    
        return response()->json(['success' => 'تم إضافة الإيصال بنجاح.'], 200);
    }

     
  
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    public function show($payment_id,$user_id)
    {

        try {
            $user = User::findOrFail($user_id);
            $firstName = $user->first_name;
            $lastName = $user->last_name;
            $phoneNumber = $user->phone_number;
            $payment = Payment::findOrFail($payment_id);
            $subscribe = Subscribe::findOrFail($payment->subscribe_id);
            $course = Course::findOrFail($subscribe->course_id);
            $subject = Subject::findOrFail($course->subject_id);
            $subjectName = $subject->subjectName;
            $subjectPrice = $subject->price;
            $debitTotal = StudentAccount::where('user_id', $user_id)
                ->where('payment_id', $payment_id)
                ->sum('Debit');
            $creditTotal = StudentAccount::where('user_id', $request->user_id)
                 ->where('payment_id', $payment_id)
                ->sum('Credit');
            $currentBalance = $debitTotal - $creditTotal;
    
            return response()->json([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone_number' => $phoneNumber,
                'subject_name' => $subjectName,
                'subject_price' => $subjectPrice,
                'current_balance' => $currentBalance
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء استرداد البيانات.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    public function edit(ReceiptStudent $receiptStudent)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request,$id)
    {
        $dataReceipt = ReceiptStudent::find($id);
    
        if(!$dataReceipt)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);
    
        }
            
        DB::beginTransaction();
    
        try {
    
            $receipt_student = new ReceiptStudent();
            $receipt_student->user_id = $request->user_id;
            $receipt_student->Debit = $request->Debit;
            $receipt_student->description = $request->description;
            $receipt_student->date = date('Y-m-d');
            $receipt_student->save();
    
             $fund_account = new FundAccount();
             $fund_account->receipt_id = $dataReceipt->id;
             $fund_account->Debit = $request->Debit;
             $fund_account->Credit = 0.00;
             $fund_account->description = $request->description;
             $fund_account->date = date('Y-m-d');
             $fund_account->save();
    
             $studentAccount = new StudentAccount();
             $studentAccount->user_id = $request->user_id;
             $studentAccount->payment_id =  $request->payment_id;
             $studentAccount->receipt_id = $dataReceipt->id;
             $studentAccount->type = 'receipt';
             $studentAccount->Debit = 0.00;
             $studentAccount->Credit = $request->Debit;
             $studentAccount->date = date('Y-m-d');
             $studentAccount->save();
    
             $creditTotal = StudentAccount::where('user_id', $request->user_id)
             ->where('payment_id', $request->payment_id)
             ->sum('Credit');
 
         $availableBalance = $debitTotal - $creditTotal;
         $requestedAmount = $request->Debit;
 
         if ($requestedAmount < 3000) {
             return response()->json(['error' => 'لا يمكن إدخال قيمة أقل من 3000.'], 422);
         }
 
         if ($requestedAmount > $availableBalance) {
             return response()->json(['error' => 'المبلغ الذي يريد الطالب دفعه يتجاوز المبلغ الواجب دفعه.'], 422);
         }


             DB::commit();
         }
     
         catch( \Exception $e){
            
             DB::rollback();
             return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    
        if($dataReceipt)
        {
            return response()->json(['message' => 'تم تحديث البيانات بنجاح']);
        }
    }
    


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $dataReceipt= ReceiptStudent::find($id);

        if(!$dataReceipt)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataReceipt->delete($id);

        if($dataReceipt)
        {
            return  $this->traitResponse(null , 'تم الحذف بنجاح ' , 200);

        }
        return  $this->traitResponse(null , 'فشل الحذف' , 404);

    }

    }

