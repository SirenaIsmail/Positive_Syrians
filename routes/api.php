<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DateController;
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

Route::controller(CourseController::class)->group(function () {
    Route::Post('/course/store',  'store');
    Route::get('/course/index',  'index');
    Route::get('/course/show/{id}',  'show');
    Route::Post('/course/update/{id}', 'update');
    Route::Post('/course/destroy/{id}', 'destroy');
    Route::Post('/course/approve/{id}', 'approve');
});

Route::controller(SubscribeController::class)->group(function () {
    Route::Post('/subscribe/store','store');
    Route::get('/subscribe/index','index');
    Route::get('/subscribe/show/{id}','show');
    Route::Post('/subscribe/update/{id}','update');
    Route::Post('/subscribe/destroy/{id}','destroy');

});

Route::controller(SubjectController::class)->group(function (){
    Route::Post('/subject/store','store');
    Route::get('/subject/index','index');
    Route::get('/subject/show/{id}','show');
    Route::Post('/subject/update/{id}','update');
    Route::Post('/subject/destroy/{id}','destroy');
    Route::get('/subject/search/{filter}','search');

 Route::get('/subject/download/{filename}','download');
});

Route::controller(BranchController::class)->group(function () {
    Route::Post('/branch/store','store');
    Route::get('/branch/index','index');
    Route::get('/branch/show/{id}','show');
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
});

Route::controller(UserController::class)->group(function () {
    Route::post('/add_employee', 'addEmployee');
    Route::post('/add_trainer', 'addTrainer');
    Route::get('/user/search/{filter}','search');
});



//General Admin Role Start
//Route::group(['prefix' => '/general_admin' , 'middleware' => ['auth']],function () {
    //BRANCH ROUTES  START

    Route::controller(BranchController::class)->group(function () {
        Route::Post('/branch/store','store');
        Route::get('/branch/index','index');
        Route::get('/branch/show/{id}','show');
        Route::Post('/branch/update/{id}','update');
        Route::Post('/branch/destroy/{id}','destroy');
        Route::get('/branch/search/{filter}','search');
    });



    // BRANCH END

    //PROCEED ROUTES
    Route::controller(ProceedController::class)->group(function () {
        Route::Post('/proceed/store','store');
        Route::get('/proceed/index', 'index');
        Route::get('/proceed/show/{id}', 'show');
        Route::Post('/proceed/update/{id}',  'update');
        Route::Post('/proceed/destroy/{id}', 'destroy');
    });
    //PROCEED END


//});


    //Add admin user
    Route::controller(UserController::class)->group(function () {
        Route::post('/add_admin', 'addAdmin');
        

    });
    //End add admin user



//End General Admin Role





//Scientific Affairs Role Start
//Route::group(['prefix' => '/scientific_affairs' , 'middleware' => ['auth']],function () {
    //QUESTIONBANK ROUTES

    //QUESTIONBANK END
//});
//End Scientific Affairs Role





//Branch Admin Role Start
//Route::group(['prefix' => '/branch_admin/' , 'middleware' => ['auth']],function () {
    //CLASSROOM ROUTES
    Route::controller(ClassRoomController::class)->group(function () {
        Route::Post('/class/store','store');
        Route::get('/class/index','index');
        Route::get('/class/show/{id}','show');
        Route::Post('/class/update/{id}','update');
        Route::Post('/class/destroy/{id}','destroy');
        Route::get('/class/search/{filter}','search');
    });
    //CLASSROOM END


    //SUBJECT ROUTES

    //SUBJECT END


    //Add Receptionist or Trainer user

    Route::controller(UserController::class)->group(function () {
        Route::post('/add_employee', 'addEmployee');
     
    });


    //End add Receptionist or Trainer user




//});
//End Branch Admin Role






//Receptionist Role Start
//Route::group(['prefix' => '/receptionist/' , 'middleware' => ['auth']],function () {
    //CARD ROUTES
    Route::controller(CardController::class)->group(function () {
        Route::Post('/card/store','store');
        Route::get('/card/index','index');
        Route::get('/card/show/{id}','show');
        Route::Post('/card/update/{id}','update');
        Route::Post('/card/destroy/{id}','destroy');
    });
    //CARD END

    //COURSE ROUTES

    Route::controller(CourseController::class)->group(function () {
        Route::Post('/course/store',  'store');
        Route::get('/course/index',  'index');
        Route::get('/course/show/{id}',  'show');
        Route::Post('/course/update/{id}', 'update');
        Route::Post('/course/destroy/{id}', 'destroy');
        Route::get('/course/search/{filter}',  'search');
    });



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

    //Student State
    Route::controller(SubscribeController::class)->group(function () {
        //الاعتماد أو سيحضر
        Route::Post('/attend/{id}', 'attend');
        //لن يحضر
        Route::Post('/notAttend/{id}', 'notAttend');
        //معلق الحضور
        Route::Post('/pending/{id}', 'pending') ;

        Route::get('/subscribe/search/{filter}', 'search');




    });
    //End Student State

   // });
//End Receptionist Role






//Trainer Role Start
Route::group(['prefix' => 'trainer/' , 'middleware' => ['auth']],function () {
    //REFERANCE ROUTES
    Route::controller(ReferanceController::class)->group(function () {
        Route::Post('/referance/store','store');
        Route::get('/referance/index',  'index');
        Route::get('/referance/show/{id}', 'show');
        Route::Post('/referance/update/{id}',  'update');
        Route::Post('/referance/destroy/{id}',  'destroy');
    });
    //REFERANCE END

    //TASK ROUTES

    //TASK END



});
//End Trainer Role






//Student Role Start

  //  Route::group(['prefix' => 'student/' , 'middleware' => ['auth']],function () {
        //SUBSCRIBE ROUTES

        //SUBSCRIBE END


        //TASK ANSWER ROUTES
        Route::controller(TaskAnswerController::class)->group(function () {
            Route::Post('/task_answer/store','store');
            Route::get('/task_answer/index',  'index');
            Route::get('/task_answer/show/{id}', 'show');
            Route::Post('/task_answer/update/{id}',  'update');
            Route::Post('/task_answer/destroy/{id}',  'destroy');
        });
        //TASK ANSWER END


 //   });
//End Student Role




//COMMENT ROUTES
Route::controller(CommentController::class)->group(function () {
    Route::Post('/comment/store', 'store');
    Route::get('/comment/index','index');
    Route::get('/comment/show/{id}', 'show');
    Route::Post('/comment/update/{id}', 'update');
    Route::Post('/comment/destroy/{id}','destroy');
});
//COMMENT END





//DATE ROUTES
Route::controller(DateController::class)->group(function () {
    Route::Post('/date/store', 'store');
    Route::get('/date/index',  'index');
    Route::get('/date/show/{id}','show');
    Route::Post('/date/update/{id}', 'update');
    Route::Post('/date/destroy/{id}',  'destroy');
});
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
Route::controller(PollController::class)->group(function () {
    Route::Post('/poll/store', 'store');
    Route::get('/poll/index',  'index');
    Route::get('/poll/show/{id}',  'show');
    Route::Post('/poll/update/{id}', 'update');
    Route::Post('/poll/destroy/{id}',  'destroy');
    Route::get('/poll/search/{filter}','search');
});
//POLL END




//SUBJECT_TRAINER ROUTES
Route::controller(SubjectTrainerController::class)->group(function () {
    Route::Post('/strainer/store','store');
    Route::get('/strainer/index','index');
    Route::get('/strainer/show/{id}','show');
    Route::Post('/strainer/update/{id}','update');
    Route::Post('/strainer/destroy/{id}','destroy');
});

//SUBJECT_TRAINER END


Route::controller(TrainerProfileController::class)->group(function () {
    Route::get('/trainerProfile/search/{filter}','search');
});

Route::controller(TaskController::class)->group(function () {
    Route::Post('/task/store','store');
    Route::get('/task/index',  'index');
    Route::get('/task/show/{id}', 'show');
    Route::Post('/task/update/{id}',  'update');
    Route::Post('/task/destroy/{id}',  'destroy');
});












