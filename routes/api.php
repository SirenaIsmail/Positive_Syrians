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
use App\Http\Controllers\PollController;
use App\Http\Controllers\ProceedController;
use App\Http\Controllers\QuestionBankController;
use App\Http\Controllers\ReferanceController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\SubjectTrainerController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaskAnswerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrainerProfileController;
use App\Http\Controllers\UserController;
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

define('PAGINATION_COUNT',10);

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});


//////////////////////////////////////////////////////////////////////////////////
//General Admin Role Start
Route::group(['prefix' => '/general_admin' , 'middleware' => ['auth']],function () {

    //BRANCH ROUTES  START
    Route::prefix('/branch')->group(function (){
        Route::controller(BranchController::class)->group(function () {
            Route::Post('/store','store');
            Route::get('/index','index');
            Route::get('/show/{id}','show');
            Route::Post('/update/{id}','update');
            Route::Post('/destroy/{id}','destroy');
        });
    })->middleware('general_admin');
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
    })->middleware('general_admin');
    //PROCEED END


    //Add admin user
    Route::controller(UserController::class)->group(function () {
        Route::post('/add_admin', 'addAdmin');
    })->middleware('general_admin');
    //End add admin user

});
//End General Admin Role




//////////////////////////////////////////////////////////////////////////////////////
//Scientific Affairs Role Start
Route::group(['prefix' => '/scientific_affairs' , 'middleware' => ['auth']],function () {

    //QUESTION BANK ROUTES
    Route::prefix('/qbank')->group(function (){
        Route::controller(QuestionBankController::class)->group(function () {
            Route::Post('/store', 'store');
            Route::get('/index', 'index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}', 'destroy');
        });
    })->middleware('scientific_affairs');

    //QUESTION BANK END
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
//    Route::get('/subject/download/{filename}','download');
        });
    })->middleware('branch_admin');

    //SUBJECT END


    //Add Receptionist or Trainer user
    Route::controller(UserController::class)->group(function () {
        Route::post('/add_employee', 'addEmployee');
        Route::post('/add_trainer', 'addTrainer');
    })->middleware('branch_admin');

    //End add Receptionist or Trainer user




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
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}', 'destroy');
            Route::Post('/approve/{id}', 'approve');
        });
    })->middleware('receptionist');
    //COURSE END

    //PAYMENT ROUTES
    Route::prefix('/payment')->group(function (){
        Route::controller(PaymentController::class)->group(function () {
            Route::Post('/store', 'store');
            Route::get('/index','index');
            Route::get('/show/{id}', 'show');
            Route::Post('/update/{id}', 'update');
            Route::Post('/destroy/{id}','destroy');
        });
    })->middleware('receptionist');
    //PAYMENT END

    //Student State
    Route::controller(SubscribeController::class)->group(function () {
        //الاعتماد أو سيحضر
        Route::Post('/attend/{id}', 'attend');
        //لن يحضر
        Route::Post('/notAttend/{id}', 'notAttend');
        //معلق الحضور
        Route::Post('/pending/{id}', 'pending');

    })->middleware('receptionist');
    //End Student State

    //لمسح الحضور
    Route::controller(AttendController::class)->group(function (){
       Route::Post('/scan_attend/{id}','scanAttend');
    })->middleware('receptionist');

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
Route::prefix('/poll')->group(function (){
    Route::controller(PollController::class)->group(function () {
        Route::Post('/store', 'store');
        Route::get('/index',  'index');
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
        Route::get('/show/{id}','show');
        Route::Post('/update/{id}','update');
        Route::Post('/destroy/{id}','destroy');
    });
});
//SUBJECT_TRAINER END




Route::prefix('/trainer')->group(function (){
    Route::controller(TrainerProfileController::class)->group(function () {
        Route::Post('/store','store');
        Route::get('/index','index');
        Route::get('/show/{id}','show');
        Route::Post('/update/{id}','update');
        Route::Post('/destroy/{id}','destroy');
    });

});


Route::controller(BarcodeController::class)->group(function () {
    Route::post('/scan', 'scanBarcode');
});


Route::controller(ExportController::class)->group(function () {
    Route::get('/export', 'exportToPDF');
});





