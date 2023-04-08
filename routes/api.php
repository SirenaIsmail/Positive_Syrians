<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CardController;
use \App\Http\Controllers\ClassRoomController;
use \App\Http\Controllers\CommentController;
use \App\Http\Controllers\CourseController;
use \App\Http\Controllers\DateController ;
use \App\Http\Controllers\HistoryController;
use \App\Http\Controllers\PaymentController;
 use \App\Http\Controllers\PollController;
 use \App\Http\Controllers\ProceedController;
 use \App\Http\Controllers\QuestionBankController;
 use \App\Http\Controllers\ReferanceController;
 use \App\Http\Controllers\StudentProfileController;
 use \App\Http\Controllers\SubjectTrainerController;
 use \App\Http\Controllers\SubscribeController;
 use \App\Http\Controllers\SujectController;
 use \App\Http\Controllers\TopCourseController;
 use \App\Http\Controllers\TrainerProfileController;
 use \App\Http\Controllers\TrainerRatingController;
 use \App\Http\Controllers\AttendController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//BRANCH ROUTES  START
  Route::Post('/branch/store',[BranchController::class,'store']);
  Route::get('/branch/index',[BranchController::class ,'index']);
  Route::get('/branch/show/{id}',[BranchController::class ,'show']);
  Route::Post('/branch/update/{id}',[BranchController::class,'update']);
  Route::Post('/branch/destroy/{id}',[BranchController::class,'destroy']);
// BRANCH END


//CARD ROUTES
   Route::Post('/card/store',[CardController::class,'store']);
   Route::get('/card/index',[CardController::class,'index']);
   Route::get('/card/show/{id}',[CardController::class,'show']);
   Route::Post('/card/update/{id}',[CardController::class,'update']);
   Route::Post('/card/destroy/{id}',[CardController::class,'destroy']);
//CARD END


//CLASSROOM ROUTES
    Route::Post('/class/store',[ClassRoomController::class,'store']);
    Route::get('/class/index',[ClassRoomController::class,'index']);
    Route::get('/class/show/{id}',[ClassRoomController::class,'show']);
    Route::Post('/class/update/{id}',[ClassRoomController::class,'update']);
    Route::Post('/class/destroy/{id}',[ClassRoomController::class,'destroy']);
//CLASSROOM END

//COMMENT ROUTES
    Route::Post('/comment/store',[CommentController::class,'store']);
    Route::get('/comment/index',[CommentController::class,'index']);
    Route::get('/comment/show/{id}',[CommentController::class,'show']);
    Route::Post('/comment/update/{id}',[CommentController::class,'update']);
    Route::Post('/comment/destroy/{id}',[CommentController::class,'destroy']);
//COMMENT END



//COURSE ROUTES
    Route::Post('/course/store',[CourseController::class,'store']);
    Route::get('/course/index',[CourseController::class,'index']);
    Route::get('/course/show/{id}',[CourseController::class,'show']);
    Route::Post('/course/update/{id}',[CourseController::class,'update']);
    Route::Post('/course/destroy/{id}',[CourseController::class,'destroy']);
//COURSE END


//DATE ROUTES
    Route::Post('/date/store',[DateController::class,'store']);
    Route::get('/date/index',[DateController::class,'index']);
    Route::get('/date/show/{id}',[DateController::class,'show']);
    Route::Post('/date/update/{id}',[DateController::class,'update']);
    Route::Post('/date/destroy/{id}',[DateController::class,'destroy']);
//DATE END




//HISTORY ROUTES
    Route::Post('/history/store',[HistoryController::class,'store']);
    Route::get('/history/index',[HistoryController::class,'index']);
    Route::get('/history/show/{id}',[HistoryController::class,'show']);
    Route::Post('/history/update/{id}',[HistoryController::class,'update']);
    Route::Post('/history/destroy/{id}',[HistoryController::class,'destroy']);
//HISTORY END

//PAYMENT ROUTES
    Route::Post('/payment/store',[PaymentController::class,'store']);
    Route::get('/payment/index',[PaymentController::class,'index']);
    Route::get('/payment/show/{id}',[PaymentController::class,'show']);
    Route::Post('/payment/update/{id}',[PaymentController::class,'update']);
Route::Post('/payment/destroy/{id}',[PaymentController::class,'destroy']);
//PAYMENT END



//POLL ROUTES
    Route::Post('/poll/store',[PollController::class,'store']);
    Route::get('/poll/index',[PollController::class,'index']);
    Route::get('/poll/show/{id}',[PollController::class,'show']);
    Route::Post('/poll/update/{id}',[PollController::class,'update']);
    Route::Post('/poll/destroy/{id}',[PollController::class,'destroy']);
//POLL END



//PROCEED ROUTES
    Route::Post('/proceed/store',[ProceedController::class,'store']);
    Route::get('/proceed/index',[ProceedController::class,'index']);
    Route::get('/proceed/show/{id}',[ProceedController::class,'show']);
    Route::Post('/proceed/update/{id}',[ProceedController::class,'update']);
    Route::Post('/proceed/destroy/{id}',[ProceedController::class,'destroy']);
//PROCEED END



//QUESTIONBANK ROUTES
    Route::Post('/qbank/store',[QuestionBankController::class,'store']);
    Route::get('/qbank/index',[QuestionBankController::class,'index']);
    Route::get('/qbank/show/{id}',[QuestionBankController::class,'show']);
    Route::Post('/qbank/update/{id}',[QuestionBankController::class,'update']);
    Route::Post('/qbank/destroy/{id}',[QuestionBankController::class,'destroy']);
//QUESTIONBANK END



//REFERANCE ROUTES
    Route::Post('/referance/store',[ReferanceController::class,'store']);
    Route::get('/referance/index',[ReferanceController::class,'index']);
    Route::get('/referance/show/{id}',[ReferanceController::class,'show']);
    Route::Post('/referance/update/{id}',[ReferanceController::class,'update']);
    Route::Post('/referance/destroy/{id}',[ReferanceController::class,'destroy']);
//REFERANCE END



//STUDENT_PROFILE ROUTES
    Route::Post('/student/store',[StudentProfileController::class,'store']);
    Route::get('/student/index',[StudentProfileController::class,'index']);
    Route::get('/student/show/{id}',[StudentProfileController::class,'show']);
    Route::Post('/student/update/{id}',[StudentProfileController::class,'update']);
    Route::Post('/student/destroy/{id}',[StudentProfileController::class,'destroy']);
//STUDENT_PROFILE END



//SUBJECT_TRAINER ROUTES
    Route::Post('/strainer/store',[SubjectTrainerController::class,'store']);
    Route::get('/strainer/index',[SubjectTrainerController::class,'index']);
    Route::get('/strainer/show/{id}',[SubjectTrainerController::class,'show']);
    Route::Post('/strainer/update/{id}',[SubjectTrainerController::class,'update']);
    Route::Post('/strainer/destroy/{id}',[SubjectTrainerController::class,'destroy']);
//SUBJECT_TRAINER END



//SUBSCRIBE ROUTES
    Route::Post('/subscribe/store',[SubscribeController::class,'store']);
    Route::get('/subscribe/index',[SubscribeController::class,'index']);
    Route::get('/subscribe/show/{id}',[SubscribeController::class,'show']);
    Route::Post('/subscribe/update/{id}',[SubscribeController::class,'update']);
    Route::Post('/subscribe/destroy/{id}',[SubscribeController::class,'destroy']);
//SUBSCRIBE END

//SUBJECT ROUTES
    Route::Post('/subject/store',[SujectController::class,'store']);
    Route::get('/subject/index',[SujectController::class,'index']);
    Route::get('/subject/show/{id}',[SujectController::class,'show']);
    Route::Post('/subject/update/{id}',[SujectController::class,'update']);
    Route::Post('/subject/destroy/{id}',[SujectController::class,'destroy']);
//SUBJECT END




//TOP_COURSE ROUTES

    Route::Post('/topcourse/store',[TopCourseController::class,'store']);
    Route::get('/topcourse/index',[TopCourseController::class,'index']);
    Route::get('/topcourse/show/{id}',[TopCourseController::class,'show']);
    Route::Post('/topcourse/update/{id}',[TopCourseController::class,'update']);
    Route::Post('/topcourse/destroy/{id}',[TopCourseController::class,'destroy']);

//TOP_COURSE END


//TRAINER_PROFILE ROUTES

Route::Post('/trainerprofile/store',[TrainerProfileController::class,'store']);
Route::get('/trainerprofile/index',[TrainerProfileController::class,'index']);
Route::get('/trainerprofile/show/{id}',[TrainerProfileController::class,'show']);
Route::Post('/trainerprofile/update/{id}',[TrainerProfileController::class,'update']);
Route::Post('/trainerprofile/destroy/{id}',[TrainerProfileController::class,'destroy']);

//TRAINER_PROFILE END


//TRAINER_RATING ROUTES

    Route::Post('/trainerrating/store',[TrainerRatingController::class,'store']);
    Route::get('/trainerrating/index',[TrainerRatingController::class,'index']);
    Route::get('/trainerrating/show/{id}',[TrainerRatingController::class,'show']);
    Route::Post('/trainerrating/update/{id}',[TrainerRatingController::class,'update']);
    Route::Post('/trainerrating/destroy/{id}',[TrainerRatingController::class,'destroy']);

//TRAINER_RATING END



//ATTEND ROUTES

    Route::Post('/attend/store',[AttendController::class,'store']);
    Route::get('/attend/index',[AttendController::class,'index']);
    Route::get('/attend/show/{id}',[AttendController::class,'show']);
    Route::Post('/attend/update/{id}',[AttendController::class,'update']);
    Route::Post('/attend/destroy/{id}',[AttendController::class,'destroy']);

//ATTEND END

