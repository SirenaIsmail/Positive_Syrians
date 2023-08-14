<?php

use App\Http\Controllers\AttendController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DateController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\ProceedController;
use App\Http\Controllers\ProcessingFeeController;
use App\Http\Controllers\QuestionBankController;
use App\Http\Controllers\ReceiptStudentController;
use App\Http\Controllers\ReferanceController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectTrainerController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\TaskAnswerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TopCourseController;
use App\Http\Controllers\TrainerProfileController;
use App\Http\Controllers\TrainerRatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('resetPassword', 'resetPassword');

});



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//General Admin Role Start
Route::group(['prefix' => '/general_admin' , 'middleware' => ['auth','general_admin']],function () {

    //BRANCH ROUTES  START
    Route::prefix( '/branch' )->group( function (){
        Route::controller(BranchController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
            Route::get('/search/{filter}', 'search');
        });
    });
    // BRANCH END

    Route::prefix('/subject')->group(function () {
        Route::controller( SubjectController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::get('/view/','view');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
            Route::get('/search/{filter}','search');
            Route::get('/downloadFile','downloadFile');
        });
    });


    //PROCEED ROUTES
    Route::prefix('/proceed')->group(function () {
        Route::controller( ProceedController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index', 'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}',  'update');
            Route::Post('/destroy/{id}', 'destroy');
        });
    });
    //PROCEED END

    Route::controller(UsersController::class)->group(function () {
        Route::post('/add_admin', 'addAdmin');
        Route::get('/user/search/{filter}', 'searchForGenToBrn');
    });

});
//End General Admin Role

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//Scientific Affairs Role Start
Route::group(['prefix' => '/scientific_affairs' , 'middleware' => ['auth','scientific_affairs']],function () {
    //QUESTIONBANK ROUTES
    Route::prefix('/qbank')->group(function () {
        Route::controller( QuestionBankController::class)->group(function () {
            Route::Post('/store', 'store');
            Route::get('/index', 'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}', 'destroy');
            Route::get('/search/{filter}','search');
            Route::get('/search_by_branch/{filter}','search_by_branch');
        });
    });
    //QUESTIONBANK END

    //SUBJECT ROUTES
    Route::prefix('/subject')->group(function () {
        Route::controller( SubjectController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::get('/view/','view');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
            Route::get('/search/{filter}','search');
            Route::get('/downloadFile','downloadFile');
        });
    });

    //SUBJECT END

    Route::controller(TrainerProfileController::class)->group(function () {
        Route::get('/trainer/update_flag/{id}', 'update');
    });
});
//End Scientific Affairs Role



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Branch Admin Role Start
Route::group(['prefix' => '/branch_admin' , 'middleware' => ['auth','branch_admin']],function () {

    //CLASSROOM ROUTES
    Route::prefix('/class')->group(function (){
        Route::controller(ClassRoomController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::get('/search/{filter}','search');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
        });
    });
    //CLASSROOM END

    Route::controller(UsersController::class)->group(function () {
        Route::post('/add_employee', 'addEmployee');
        Route::post('/add_trainer', 'addTrainer');
        Route::get('/user/search/{filter}','search');
        Route::get('/user/show/{id}','show');



        Route::get('/user/searchByFilterWithBarcode/{filter?}','searchByFilterWithBarcode');
    });

    //SUBJECT ROUTES
    Route::prefix('/subject')->group(function () {
        Route::controller( SubjectController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::get('/view/','view');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
            Route::get('/search/{filter}','search');
            Route::get('/downloadFile','downloadFile');
        });
    });

    //SUBJECT END


    //COURSE ROUTES
    Route::prefix('/course')->group(function () {
        Route::controller(CourseController::class)->group(function () {
            Route::Post('/store',  'store');
            Route::get('/index',  'index');
            Route::get('/indexa/{id}',  'indexa');
            Route::get('/indexAvailable',  'indexAvailable');
            Route::get('/changeApproved',  'changeApproved');
            Route::get('/show/{id}',  'show');
            Route::get('/search','search');
            Route::get('/searchBybranch','searchBybranch');
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}', 'destroy');
            Route::Post('/approve/{id}', 'approve');
        });
    });
    //COURSE END

    Route::controller(UserController::class)->group(function (){
        Route::get('/search/{filter}/{barcode}','search');
        Route::get('/student_subscribes/{id}','studentSubscribes');
        Route::get('/search_without_paginate/{filter}','searchWithoutPaginate');
    });

//    Route::controller(ExportController::class)->group(function () {
//        Route::get('/export', 'exportToPDF');
//   });

    Route::controller(TopCourseController::class)->group(function () {
        Route::get('/top_courses', 'getTopCoursesReport');
        Route::get('/branch_topCourses/{branch}', 'getBranchTopCoursesReport');
        Route::get('/monthly_topCourses/{month}', 'getMonthlyTopCoursesReport');
        Route::get('/yearly_topCourses/{year}', 'getYearlyTopCoursesReport');
        Route::get('/branch_yearly_topCourses/{month}/{branch}', 'getMonth_Branch_TopCourse');
    });

    Route::controller(ExportController::class)->group(function () {
        Route::get('/encrypt_excel', 'encryptExcel');
        Route::get('/decrypt_excel', 'decryptExcel');
        Route::get('/export_excel', 'exportExcel');
    });//->middleware('scientific_affairs');

    //Route::controller(WeeklyScheduleController::class)->group(function () {
    //    Route::get('/generate_schedule', 'generateWeeklySchedule');
    //});

    Route::controller(PollController::class)->group(function () {
        Route::get('/polls_counting', 'pollsCounting');
        Route::get('/polls_counting_byBranch', 'pollsCountingByBranch');
        Route::get('/polls_counting_byDate', 'pollsCountingByDate');
        Route::get('/polls_counting_byBranch&Date', 'pollsCountingByBranchAndDate');
    });

    Route::controller(TrainerRatingController::class)->group(function () {
        Route::get('/trainer_ratings/{startDate?}/{endDate?}/{subject?}', 'trainerRatings');
    });

    Route::controller(TrainerProfileController::class)->group(function () {
        Route::get('/trainerProfile/search/{filter?}','search');
        Route::get('/trainerProfile/view','view');
    });

});
//End Branch Admin Role



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Receptionist Role Start
 Route::group(['prefix' => '/receptionist' , 'middleware' => ['auth','receptionist']],function () {
    //CARD ROUTES
    Route::prefix('/card')->group(function (){
        Route::controller(CardController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
        });
    });
    //CARD END



    //PAYMENT ROUTES
    Route::prefix('/payment')->group(function () {
        Route::controller(PaymentController::class)->group(function () {
            Route::Post('/store/{subscriptionId}', 'store');
            Route::get('/index','index');
            Route::get('/show/{id}', 'show');
            Route::get('/search/{filter}', 'search');
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}','destroy');
            Route::get('/createPayment/{id}', 'createPayment');
        });
    });
    //PAYMENT END

    //COURSE ROUTES
    Route::prefix('/course')->group(function () {
        Route::controller(CourseController::class)->group(function () {
            Route::Post('/store',  'store');
            Route::get('/index',  'index');
            Route::get('/show/{id}',  'show');
            Route::get('/search/{filter?}','search');
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}', 'destroy');
            Route::Post('/approve/{id}', 'approve');
    //        Route::get('/search','search');
            Route::get('/searchbybranch/{filter}','searchbybranch');
        });
    });
    //COURSE END

    Route::prefix('/receipt')->group(function () {
        Route::controller(ReceiptStudentController::class)->group(function () {
            Route::Post('/store', 'store');
            Route::get('/index','index');
            Route::get('/search/{barcode?}','search');
            Route::get('/indexing/{id}','indexing');
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}','destroy');
            Route::get('/getImportByBranch','getImportByBranch');
            Route::get('/getImportDaily','getImportDaily');

        });

    });



    Route::prefix('/processing')->group(function () {
        Route::controller(ProcessingFeeController::class)->group(function () {
            Route::Post('/store', 'store');
            Route::get('/index','index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}','destroy');
        });
    });



    Route::prefix('/withdraw')->group(function () {
        Route::controller(WithdrawController::class)->group(function () {
            Route::Post('/store', 'store');
            Route::get('/index','index');
            Route::get('/indexing/{id}','indexing');
            Route::Post('/destroy/{id}','destroy');
        });
    });

    //Student State
    Route::controller(SubscribeController::class)->group(function () {
        //الاعتماد أو سيحضر
        Route::Post('/attend/{id}', 'attend');
        //لن يحضر
        Route::Post('/notAttend/{id}', 'notAttend');
        //معلق الحضور
        Route::Post('/pending/{id}', 'pending');

        Route::get('/index','index');
        Route::get('/searchDate/{filter?}', 'searchDate');
        Route::get('/search/{filter}','search');
        Route::Post('/store', 'store');
        Route::Post('/update/{id}', 'update');

    });
    //End Student State

    //لمسح الحضور
    Route::controller(AttendController::class)->group(function (){
        Route::Post('/scan_attend/{barcode}','scanAttend');
    });


     Route::controller(UserController::class)->group(function (){
         Route::get('/search/{filter?}/{barcode?}','search');
         Route::get('/student_subscribes/{id}','studentSubscribes');
         Route::get('/search_without_paginate/{filter}','searchWithoutPaginate');
     });

 });
//End Receptionist Role




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Trainer Role Start
Route::group(['prefix' => '/trainer' , 'middleware' => ['auth','trainer']],function () {

    Route::prefix('/trainer_profile')->group(function () {
        Route::controller( TrainerProfileController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
        });
    });


    //REFERANCE ROUTES
    Route::prefix('/referance')->group(function (){
        Route::controller(ReferanceController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index',  'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}',  'update');
            Route::Post('/destroy/{id}',  'destroy');
        });
    });
    //REFERANCE END

    Route::prefix('/task')->group(function (){
        Route::controller(TaskController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index',  'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}',  'update');
            Route::Post('/destroy/{id}',  'destroy');
        });
    });

    Route::controller(TrainerProfileController::class)->group(function () {
        Route::get('/trainerProfile/search/{filter?}','search');
        Route::get('/trainerProfile/view','view');
    });


    Route::controller(CourseController::class)->group(function () {
        Route::get('/Course/GetCoursesByTrainerId','GetCoursesByTrainerId');
    });


    Route::controller(QuestionBankController::class)->group(function () {
        Route::Post('/store', 'store');
        Route::get('/index', 'index');
        Route::get('/show/{id}', 'show');
        Route::Post('/update/{id}', 'update');
        Route::Post('/destroy/{id}', 'destroy');
        Route::get('/search/{filter}','search');
        Route::get('/search_by_branch/{filter}','search_by_branch');
    });
});
//End Trainer Role






////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Student Role Start
Route::group(['prefix' => '/student' , 'middleware' => ['auth','Student']],function () {

    //SUBSCRIBE ROUTES
    Route::prefix('/subscribe')->group(function () {
        Route::controller( SubscribeController::class)->group(function () {
            Route::get('/show/{id}','show');

        });
    });
    //SUBSCRIBE END
    Route::prefix('/task_answer')->group(function (){
        Route::controller(TaskAnswerController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index',  'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}',  'update');
            Route::Post('/destroy/{id}',  'destroy');
        });
    });

    Route::prefix('/rating')->group(function (){
        Route::controller(TrainerRatingController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index',  'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}',  'update');
            Route::Post('/destroy/{id}',  'destroy');
            Route::Post('/rate/{id}',  'rate');

        });
    });
});


//End Student Role




//COMMENT ROUTES
Route::prefix('/comment')->group(function (){
    Route::controller(CommentController::class)->group(function () {
        Route::Post('/store', 'store');
        Route::get('/index','index');
        Route::get('/show/{id}', 'show');
        Route::Post('/update/{id}', 'update');
        Route::Post('/destroy/{id}','destroy');
    });
});
//COMMENT END





//DATE ROUTES
Route::prefix('/date',)->group(function () {
    Route::controller(DateController::class)->group(function () {
        Route::Post('/store', 'store');
        Route::get('/index',  'index');
        Route::get('/show/{id}','show');
        Route::Post('/update/{id}', 'update');
        Route::Post('/destroy/{id}',  'destroy');
    });
});
//DATE END




//HISTORY ROUTES
//Route::controller(HistoryController::class)->group(function () {
//    Route::Post('/history/store',  'store');
//    Route::get('/history/index', 'index');
//    Route::get('/history/show/{id}', 'show');
//    Route::Post('/history/update/{id}',  'update');
//    Route::Post('/history/destroy/{id}','destroy');
//});
//HISTORY END




//POLL ROUTE
Route::prefix('/poll')->group(function () {
    Route::controller(PollController::class)->group(function () {
        Route::Post('/store', 'store');
        Route::get('/index',  'index');
        Route::get('/serach/{filter}',  'search');
        Route::get('/search_by_branch/{filter}','search_by_branch');
        Route::get('/show/{id}',  'show');
        Route::Post('/update/{id}', 'update');
        Route::Post('/destroy/{id}',  'destroy');
    });
});
//POLL END




//SUBJECT_TRAINER ROUTES

Route::prefix('/strainer')->group(function (){
    Route::controller(SubjectTrainerController::class)->group(function () {
        Route::Post('/store','store');
        Route::get('/index','index');
        Route::get('/subjectTrainer/show/{id}','show');
        Route::get('/subjectTrainer/view/{id}','view');
        Route::Post('/update/{id}','update');
        Route::Post('/destroy/{id}','destroy');
    });
});

Route::prefix( '/branch' )->group( function (){
    Route::controller(BranchController::class)->group(function () {
        Route::Post('/store','store');
        Route::get('/index','index');
        Route::get('/show/{id}','show');
        Route::Post('/update/{id}','update');
        Route::Post('/destroy/{id}','destroy');
        Route::get('/search/{filter}', 'search');
    });
});
// BRANCH END

Route::prefix('/subject')->group(function () {
    Route::controller( SubjectController::class)->group(function () {
        Route::Post('/store','store');
        Route::get('/index','index');
        Route::get('/show/{id}','show');
        Route::get('/view/','view');
        Route::Post('/update/{id}','update');
        Route::Post('/destroy/{id}','destroy');
        Route::get('/search/{filter}','search');
        Route::get('/downloadFile','downloadFile');
    });
});


//SUBJECT_TRAINER END

















