<?php

use App\Http\Controllers\AttendController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DateController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptStudentController;
use App\Http\Controllers\ProcessingFeeController;
use App\Http\Controllers\TopCourseController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\ProceedController;
use App\Http\Controllers\QuestionBankController;
use App\Http\Controllers\ReferanceController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentAccountController;
use App\Http\Controllers\SubjectTrainerController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaskAnswerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrainerProfileController;
use App\Http\Controllers\UsersController;
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

//<<<<<<< Updated upstream
//define('PAGINATION_COUNT',10);
//=======
Route::controller(BranchController::class)->group(function () {
    Route::Post('/branch/store','store');
    Route::get('/branch/index','index');
    Route::get('/branch/show/{id}','show');
    Route::Post('/branch/update/{id}','update');
    Route::Post('/branch/destroy/{id}','destroy');
});
//>>>>>>> Stashed changes

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});


Route::controller(SubscribeController::class)->group(function () {
    Route::Post('/subscribe/store','store');
    Route::get('/subscribe/index','index');
    Route::get('/subscribe/show/{id}','show');
    Route::get('/subscribe/search/{filter}','search');
    Route::Post('/subscribe/update/{id}','update');
    Route::Post('/subscribe/destroy/{id}','destroy');

});

Route::controller(SubjectController::class)->group(function (){
    Route::Post('/subject/store','store');
    Route::get('/subject/index','index');
    Route::get('/subject/show/{id}','show');
    Route::get('/subject/view/','view');
    Route::Post('/subject/update/{id}','update');
    Route::Post('/subject/destroy/{id}','destroy');
    Route::get('/subject/search/{filter}','search');
    Route::get('/downloadFile','downloadFile');
});

Route::controller(BranchController::class)->group(function () {
    Route::Post('/branch/store','store');
    Route::get('/branch/index','index');
    Route::get('/branch/show/{id}','show');
    Route::get('/branch/search/{filter}','search');
    Route::Post('/branch/update/{id}','update');
    Route::Post('/branch/destroy/{id}','destroy');

});

Route::controller(TrainerProfileController::class)->group(function () {
    Route::Post('/trainer/store','store');
    Route::get('/trainer/index','index');
    Route::get('/trainer/show/{id}','show');
    Route::Post('/trainer/update/{id}','update');
    Route::Post('/trainer/destroy/{id}','destroy');

});

Route::controller(QuestionBankController::class)->group(function () {
    Route::Post('/qbank/store', 'store');
    Route::get('/qbank/index', 'index');
    Route::get('/qbank/show/{id}', 'show');
    Route::Post('/qbank/update/{id}', 'update');
    Route::Post('/qbank/destroy/{id}', 'destroy');
    Route::get('/qbank/search/{filter}','search');
});

Route::controller(UsersController::class)->group(function () {
    Route::post('/add_employee', 'addEmployee');
    Route::post('/add_trainer', 'addTrainer');
    Route::get('/user/search/{filter}','search');
    Route::get('/user/show/{id}','show');
});



//General Admin Role Start
// Route::group(['prefix' => '/general_admin' , 'middleware' => ['auth']],function () {

    //BRANCH ROUTES  START
//<<<<<<< Updated upstream

    Route::controller(BranchController::class)->group(function () {
        Route::Post('/branch/store','store');
        Route::get('/branch/index','index');
        Route::get('/branch/show/{id}','show');
        Route::Post('/branch/update/{id}','update');
        Route::Post('/branch/destroy/{id}','destroy');
        Route::get('/branch/search/{filter}','search');
    });



//=======
    // Route::controller(BranchController::class)->group(function () {
    //     Route::Post('/branch/store','store');
    //     Route::get('/branch/index','index');
    //     Route::get('/branch/show/{id}','show');
    //     Route::Post('/branch/update/{id}','update');
    //     Route::Post('/branch/destroy/{id}','destroy');
    // });
//>>>>>>> Stashed changes
    // BRANCH END

    //PROCEED ROUTES
    Route::prefix('/proceed')->group(function (){
        Route::controller(ProceedController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index', 'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}',  'update');
            Route::Post('/destroy/{id}', 'destroy');
        });
    // })->middleware('general_admin');
    //PROCEED END


    //Add admin user
    Route::controller(UsersController::class)->group(function () {
        Route::post('/add_admin', 'addAdmin');
        Route::get('/user/search/{filter}', 'searchForGenToBrn');
    })->middleware('general_admin');
    //End add admin user

});
//End General Admin Role




//////////////////////////////////////////////////////////////////////////////////////
//Scientific Affairs Role Start
Route::group(['prefix' => '/scientific_affairs' , 'middleware' => ['auth']],function () {

    //QUESTION BANK ROUTES
    // Route::prefix('/qbank')->group(function (){
        // Route::controller(QuestionBankController::class)->group(function () {
        //     Route::Post('/store', 'store');
        //     Route::get('/index', 'index');
        //     Route::get('/show/{id}', 'show');
        //     Route::Post('/update/{id}', 'update');
        //     Route::Post('/destroy/{id}', 'destroy');

        // });
    // })->middleware('scientific_affairs');
    //QUESTION BANK END

    Route::controller(ExportController::class)->group(function () {
        Route::get('/encrypt_excel', 'encryptExcel');
        Route::get('/decrypt_excel', 'decryptExcel');
    })->middleware('scientific_affairs');

});
//End Scientific Affairs Role




////////////////////////////////////////////////////////////////////////////////////////
//Branch Admin Role Start
Route::group(['prefix' => '/branch_admin' , 'middleware' => ['auth']],function () {
    //CLASSROOM ROUTES
    Route::prefix('/class')->group(function (){
        Route::controller(ClassRoomController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::get('/search/{filter}','show');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
        });
    })->middleware('branch_admin');
    //CLASSROOM END


    //SUBJECT ROUTES
    Route::prefix('/subject')->group(function (){
        Route::controller(SubjectController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
        });
    })->middleware('branch_admin');

    //SUBJECT END


    //Add Receptionist or Trainer user
    Route::controller(UsersController::class)->group(function () {
        Route::post('/add_employee', 'addEmployee');
        Route::post('/add_trainer', 'addTrainer');
    })->middleware('branch_admin');

});
//End Branch Admin Role




//////////////////////////////////////////////////////////////////////////////////

//Receptionist Role Start
Route::group(['prefix' => '/receptionist' , 'middleware' => ['auth']],function () {
    //CARD ROUTES
    Route::prefix('/card')->group(function (){
        Route::controller(CardController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
        });
    })->middleware('receptionist');
    //CARD END

    //COURSE ROUTES
    Route::prefix('/course')->group(function (){
        Route::controller(CourseController::class)->group(function () {
            Route::Post('/store',  'store');
            Route::get('/index',  'index');
            Route::get('/show/{id}',  'show');
            Route::get('/search/{filter}','search');
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}', 'destroy');
            Route::Post('/approve/{id}', 'approve');
        });
    })->middleware('receptionist');
    //COURSE END

    //PAYMENT ROUTES
    Route::controller(PaymentController::class)->group(function () {
        Route::Post('/payment/store', 'store');
        Route::get('/payment/index','index');
        Route::get('/payment/show/{id}', 'show');
        Route::Post('/payment/update/{id}', 'update');
        Route::Post('/payment/destroy/{id}','destroy');
        Route::get('/payment/createPayment/{id}', 'createPayment');
    });
    //PAYMENT END


    Route::controller(ReceiptStudentController::class)->group(function () {
        Route::Post('/receipt/store', 'store');
        Route::get('/receipt/index','index');
        Route::get('/receipt/show/{id}','show');
        Route::get('/receipt/view/{payment_id}/{user_id}','view');
        Route::Post('/receipt/update/{id}', 'update');
        Route::Post('/receipt/destroy/{id}','destroy');

    });



    Route::controller(ProcessingFeeController::class)->group(function () {
        Route::Post('/processing/store', 'store');
        Route::get('/processing/index','index');
        Route::get('/processing/show/{id}', 'show');
        Route::Post('/processing/update/{id}', 'update');
        Route::Post('/processing/destroy/{id}','destroy');

    });



    Route::controller(WithdrawController::class)->group(function () {
        Route::Post('/withdraw/store', 'store');
        Route::get('/withdraw/index','index');
        Route::get('/withdraw/show/{id}', 'show');
        Route::Post('/withdraw/update/{id}', 'update');
        Route::Post('/withdraw/destroy/{id}','destroy');

    });




    //Student State
    Route::controller(SubscribeController::class)->group(function () {
        //الاعتماد أو سيحضر
        Route::Post('/attend/{id}', 'attend');
        //لن يحضر
        Route::Post('/notAttend/{id}', 'notAttend');
        //معلق الحضور
        Route::Post('/pending/{id}', 'pending');

        Route::get('/subscribe/search/{filter}', 'search');



    });
    //End Student State


//لمسح الحضور
    Route::controller(AttendController::class)->group(function (){
        Route::Post('/scan_attend/{barcode}','scanAttend');
    });

 });
//End Receptionist Role





////////////////////////////////////////////////////////////////////////////////
//Trainer Role Start
Route::group(['prefix' => '/trainer' , 'middleware' => ['auth']],function () {
    //REFERANCE ROUTES
    Route::prefix('/referance')->group(function (){
        Route::controller(ReferanceController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index',  'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}',  'update');
            Route::Post('/destroy/{id}',  'destroy');
        });
    })->middleware('trainer');
    //REFERANCE END

    //TASK ROUTES
    Route::prefix('/task')->group(function (){
        Route::controller(TaskController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index',  'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}',  'update');
            Route::Post('/destroy/{id}',  'destroy');
        });
    })->middleware('trainer');
    //TASK END

});
//End Trainer Role




////////////////////////////////////////////////////////////////////////////////////
//Student Role Start
Route::group(['prefix' => '/student' , 'middleware' => ['auth']],function () {
    //SUBSCRIBE ROUTES
    Route::prefix('/subscribe')->group(function (){
        Route::controller(SubscribeController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');

        });
    })->middleware('Student');
    //SUBSCRIBE END


    //TASK ANSWER ROUTES
    Route::prefix('/task_answer')->group(function (){
        Route::controller(TaskAnswerController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index',  'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}',  'update');
            Route::Post('/destroy/{id}',  'destroy');
        });
    })->middleware('Student');
    //TASK ANSWER END

});
//End Student Role
/////////////////////////////////////////////////////////////////////



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
//Route::controller(DateController::class)->group(function () {
//    Route::Post('/date/store', 'store');
//    Route::get('/date/index',  'index');
//    Route::get('/date/show/{id}','show');
//    Route::Post('/date/update/{id}', 'update');
//    Route::Post('/date/destroy/{id}',  'destroy');
//});
//DATE END




//HISTORY ROUTES
Route::controller(HistoryController::class)->group(function () {
    Route::Post('/history/store',  'store');
    Route::get('/history/index', 'index');
    Route::get('/history/show/{id}', 'show');
    Route::Post('/history/update/{id}',  'update');
    Route::Post('/history/destroy/{id}','destroy');
    Route::Post('/history/addStudentsToCourse/{id}','addStudentsToCourse');
});
//HISTORY END




//POLL ROUTES
// Route::prefix('/poll')->group(function (){
    Route::controller(PollController::class)->group(function () {
        Route::Post('/store', 'store');
        Route::get('/index',  'index');
        Route::get('poll/serach/{filter}',  'search');
        Route::get('/show/{id}',  'show');
        Route::Post('/update/{id}', 'update');
        Route::Post('/destroy/{id}',  'destroy');
    });
// });
//POLL END




//SUBJECT_TRAINER ROUTES
// Route::prefix('/strainer')->group(function (){
    Route::controller(SubjectTrainerController::class)->group(function () {
        Route::Post('/store','store');
        Route::get('/index','index');
        Route::get('/subjectTrainer/show/{id}','show');
        Route::get('/subjectTrainer/view/{id}','view');
        Route::Post('/update/{id}','update');
        Route::Post('/destroy/{id}','destroy');
    });
// });
//SUBJECT_TRAINER END


Route::controller(TrainerProfileController::class)->group(function () {
    Route::get('/trainerProfile/search/{filter}','search');
    Route::get('/trainerProfile/view','view');
});


Route::controller(ExportController::class)->group(function () {
    Route::get('/export', 'exportToPDF');
});


Route::controller(TopCourseController::class)->group(function () {
    Route::get('/top_courses', 'getTopCoursesReport');
    Route::get('/branch_topCourses/{branch}', 'getBranchTopCoursesReport');
    Route::get('/monthly_topCourses/{month}', 'getMonthlyTopCoursesReport');
    Route::get('/yearly_topCourses/{year}', 'getYearlyTopCoursesReport');
    Route::get('/branch_yearly_topCourses/{month}/{branch}', 'getMonth_Branch_TopCourse');
});


