<?php

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



Route::group(['middleware' => ['auth:sanctum', 'course']], function() {
    Route::get('/teacher/educational-level/{educationalLevelId}', [TeacherController::class, 'getTeachersByEducationalLevel']);
    Route::get('/getsubjects/{educationalLevelId}', [SubjectController::class, 'getByEducationalLevel']);
    Route::get('/videos/lesson/{lessonId}', [VideoController::class, 'getByLessonId']);
});

Route::post('/logout', [AuthController::class, 'logout'])->Middleware('auth:sanctum');
//subject

//  ->middleware('auth:sanctum');
Route::post('/addsubject', [SubjectController::class, 'store']);
Route::delete('/deletesubject/{id}', [SubjectController::class, 'destroy']);
//teacher
Route::post('/addteacher', [TeacherController::class, 'store']);
Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']);
Route::get('/getteachers', [TeacherController::class, 'index']);
Route::get('/getteacher/{id}', [TeacherController::class, 'show']);
// Route::get('/teacher/educational-level/{educationalLevelId}', [TeacherController::class, 'getTeachersByEducationalLevel']);
//lesson
Route::post('/addlesson', [LessonController::class, 'store']);
Route::get('/getlessons/{teacherId}', [LessonController::class, 'getLessonsByteacherId']);
Route::delete('/deletelesson/{id}', [LessonController::class, 'destroy']);
//code
Route::post('/addcode', [CodeController::class, 'store']);
Route::post('/codes/validate', [CodeController::class, 'validateCode']);
Route::get('/code/check/{userId}/{macaddress}', [CodeController::class, 'checkUserCodeStatus']);
Route::get('/code/users', [CodeController::class, 'getAllCodesWithUsers']);

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



