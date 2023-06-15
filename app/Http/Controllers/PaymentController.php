<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Card;
use App\Models\Course;
use App\Models\User;
use App\Models\Subject;
use App\Models\Subscribe;
use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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

        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;
            $Result = DB::table('payments')
            ->join('subscribes', 'subscribes.id', '=', 'payments.subscribe_id')
            ->join('cards', 'cards.id', '=', 'subscribes.card_id')
            ->join('users', 'users.id', '=', 'cards.user_id')
            ->join('branches', 'branches.id', '=', 'users.branch_id')
            ->join('courses', 'courses.id', '=', 'subscribes.course_id')
            ->join('subjects', 'subjects.id', '=', 'courses.subject_id')
            ->join('student_accounts', 'student_accounts.payment_id', '=', 'payments.id')
            ->select('users.first_name','users.last_name'
            ,'subjects.subjectName','subjects.price','users.phone_number'
            ,'payments.date','branches.name','student_accounts.Debit','student_accounts.Credit')
                ->where('branches.id', '=', $branchId) 
                ->paginate(10);
    
            if ($Result->count() > 0) {
                return $this->traitResponse($Result, 'Index Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No  results found', 200);
            }
        } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
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
    public function store( $subscriptionId)
   
    {

                        // Get subscription information
              $subscription = Subscribe::find($subscriptionId);
              $card = Card::where('id', $subscription->card_id)->first();
              $course = Course::where('id', $subscription->course_id)->first();
              $subject = Subject::where('id', $course->subject_id)->first();
              $user = User::where('id', $card->user_id)->first();

           DB::beginTransaction();

           try{

                $payment = new Payment();
                $payment->branch_id = $subscription->branch_id;
                $payment->subscribe_id = $subscriptionId;
                $payment->date = date('Y-m-d');
                $payment->ammount = $subject->price;
                $payment->save();



               $studentAccount = new StudentAccount();
               $studentAccount->user_id = $card->user_id;
               $studentAccount->payment_id = $payment->id;
               $studentAccount->type = 'invoice';
               $studentAccount->Debit = $payment->ammount;
               $studentAccount->Credit = 0.00;
               $studentAccount->date = date('Y-m-d');
               $studentAccount->save();
           
           

           DB::commit();
           
              
        //    return [
        //     'student_name' => $user->first_name . ' ' . $user->last_name,
        //     'phone_number' => $user->phone_number,
        //     'subject_name' => $subject->subjectName,
        //     'amount' => $subject->price
        // ];

    }
        catch( \Exception $e){
           
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);

        }
        return response()->json(['message' => 'تم إضافة البيانات بنجاح']);
    } 


    


    
    public function show($id)
    {

        if (auth()->check()) {
            $branchId = Auth::user()->branch_id;
    
            
            $Result = DB::table('payments')
            ->join('subscribes', 'subscribes.id', '=', 'payments.subscribe_id')
            ->join('cards', 'cards.id', '=', 'subscribes.card_id')
            ->join('users', 'users.id', '=', 'cards.user_id')
            ->join('branches', 'branches.id', '=', 'users.branch_id')
            ->join('courses', 'courses.id', '=', 'subscribes.course_id')
            ->join('subjects', 'subjects.id', '=', 'courses.subject_id')
            ->join('student_accounts', 'student_accounts.payment_id', '=', 'payments.id')
            ->select('users.first_name','users.last_name'
            ,'subjects.subjectName','subjects.price','users.phone_number'
            ,'payments.date','branches.name')
                ->where('branches.id', '=', $branchId) 
                >where('payments.id', '=', $id) 
                ->get();
    
            if ($Result->count() > 0) {
                return $this->traitResponse($Result, 'Show Successfully', 200);
            } else {
                return $this->traitResponse(null, 'No matching results found', 200);
            }
               } else {
            return $this->traitResponse(null, 'User not authenticated', 401);
                   }



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


}
