<?php

use App\Http\Controllers\EducationalLevelController;
use App\Http\Controllers\QuestionController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\MCQController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Authentication
Route::post('/signup', [AuthController::class, 'signUp']);

Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum', 'sanctum'])->group(function () {
    Route::get('/teacher/educational-level/{educationalLevelId}', [TeacherController::class, 'getTeachersByEducationalLevel']);
    Route::get('/getsubjects/{educationalLevelId}', [SubjectController::class, 'getByEducationalLevel']);
    Route::get('/getteachers', [TeacherController::class, 'index']);
    Route::get('/videos/lesson/{lessonId}', [VideoController::class, 'getByLessonId']);
    Route::get('/getteacher/{id}', [TeacherController::class, 'show']);


});



Route::get('educational-levels/{id}', [EducationalLevelController::class, 'show']);

// Route::get('/videos/lesson/{lessonId}', [VideoController::class, 'getByLessonId']);



Route::post('/logout', [AuthController::class, 'logout'])->Middleware('auth:sanctum');
//subject

//  ->middleware('auth:sanctum');
Route::post('/addsubject', [SubjectController::class, 'store']);
Route::delete('/deletesubject/{id}', [SubjectController::class, 'destroy']);
//teacher
Route::post('/addteacher', [TeacherController::class, 'store']);
Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']);

// Route::get('/getteacher/{id}', [TeacherController::class, 'show']);
// Route::get('/teacher/educational-level/{educationalLevelId}', [TeacherController::class, 'getTeachersByEducationalLevel']);
//lesson
Route::post('/addlesson', [LessonController::class, 'store']);
Route::get('/getlessons/{teacherId}/{educationalLevel}', [LessonController::class, 'getLessonsByteacherId']);
Route::delete('/deletelesson/{id}', [LessonController::class, 'destroy']);
//code
Route::post('/addcode', [CodeController::class, 'store']);
Route::post('/codes/validate', [CodeController::class, 'validateCode']);
Route::get('/code/check/{userId}/{macaddress}/{lesson_id}', [CodeController::class, 'checkUserCodeStatus']);
Route::get('/code/users', [CodeController::class, 'getAllCodesWithUsers']);
Route::get('/code/{user_id}', [CodeController::class, 'getUserLessonsWithCode']);



// Video
Route::post('/videos', [VideoController::class, 'store']);

Route::delete('/videos/{id}', [VideoController::class, 'destroy']);

// PDF
Route::post('/pdfs', [PDFController::class, 'store']);
Route::get('/pdfs/lesson/{lessonId}', [PDFController::class, 'getByLessonId']);
Route::delete('/pdfs/{id}', [PDFController::class, 'destroy']);

//  MCQ
Route::post('/mcqs', [MCQController::class, 'store']);
Route::get('/mcqs/lesson/{lessonId}', [MCQController::class, 'getByLessonId']);
Route::delete('/mcqs/{id}', [MCQController::class, 'destroy']);





//questions
Route::post('/questions', [QuestionController::class, 'store']);
Route::get('/questions/lesson/{lessonId}', [QuestionController::class, 'getByLessonId']);


use Illuminate\Support\Facades\Artisan;


Route::get('/manage-cache', function () {
    // Clear existing caches
    Artisan::call('storage:link');
    // Artisan::call('cache:clear');
    // Artisan::call('view:clear');
    // Artisan::call('route:clear');

    // // Clear optimized files
    // Artisan::call('optimize:clear');

    // // Re-cache configuration
    // Artisan::call('config:cache');
    // Artisan::call('route:cache');
    // Artisan::call('view:cache');

    return 'Cache cleared and configuration cached!';
});

Route::get('/link', function () {
    try {

        Artisan::call('storage:link');


        return response()->json(['message' => 'Storage linked successfully.'], 200);
    } catch (\Exception $e) {

        return response()->json(['message' => 'Failed to link storage.', 'error' => $e->getMessage()], 500);
    }
});
