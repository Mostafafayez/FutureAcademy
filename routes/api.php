<?php

use App\Http\Controllers\authteacher;
use App\Http\Controllers\EducationalLevelController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UUIDController;
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
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ImageController;
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

//Authentication  pdf   eduction
Route::post('/signup', [AuthController::class, 'signUp']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/userinfo', [AuthController::class, 'userinfo']);

Route::post('/teacher/signup', [authteacher::class, 'signUp']);
Route::post('/teacher/login', [authteacher::class, 'login']);
Route::middleware(['auth:sanctum', 'sanctum'])->group(function () {
    Route::get('/teacher/educational-level/{educationalLevelId}', [TeacherController::class, 'getTeachersByEducationalLevel']);
    Route::get('/getsubjects/{educationalLevelId}', [SubjectController::class, 'getByEducationalLevel']);
    // Route::get('/getteachers', [TeacherController::class, 'index']);
    Route::get('/videos/lesson/{lessonId}', [VideoController::class, 'getByLessonId']);
    Route::get('/getteacher/{id}', [TeacherController::class, 'show']);
    Route::get('/lessons/package/{packageId}', [lessonController::class, 'getByPackageId']);
    Route::get('/getPackages/{teacherId}/{educationalLevel}', [PackagesController::class, 'getpackagesByteacherId']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/messages', [MessageController::class, 'store']);
});



Route::get('/getusermessages', [MessageController::class, 'getUserMessages']);
Route::get('/getmessages', [MessageController::class, 'getAllMessages']);

Route::get('/encryption_videos/lesson/{lessonId}', [VideoController::class, 'getEncryptionByLessonId']);

Route::get('/getsubjects', [SubjectController::class, 'getByEducationalLevels']);

Route::get('educational-levels/{id}', [EducationalLevelController::class, 'show']);

Route::get('/educational-levels', [EducationalLevelController::class, 'getall']);



Route::post('/logout', [AuthController::class, 'logout'])->Middleware('auth:sanctum');
//subject

//  ->middleware('auth:sanctum');
Route::post('/addsubject', [SubjectController::class, 'store']);
Route::delete('/deletesubject/{id}', [SubjectController::class, 'destroy']);
//teacher
Route::post('/addteacher', [TeacherController::class, 'store']);

Route::get('/getteachers', [TeacherController::class, 'index']);
Route::get('/mobile/teacher', [TeacherController::class, 'getall']);
Route::post('/search/{educationLevel}', [TeacherController::class, 'search']);

Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']);

//packages  signUp
Route::post('/addpackage', [PackagesController::class, 'store']);

Route::delete('/deletepackage/{id}', [PackagesController::class, 'destroy']);

// lessons
Route::post('/addlessons', [LessonController::class, 'store']); // For adding a lesson
Route::get('/lessons/package/{packageId}', [LessonController::class, 'getByPackageId']); // For getting lessons by package ID
Route::delete('/lessons/{id}', [LessonController::class, 'destroy']); // For deleting a lesson by ID
Route::get('/getlessons/{teacherId}', [LessonController::class, 'getLessonsByteacherIds']);


Route::get('/getlesson/assistant', [LessonController::class, 'getLessonsforassistant']);
//search
Route::post('/addcode', [CodeController::class, 'store']);
Route::get('/addcode/fixed', [CodeController::class, 'storefixed']);
Route::get('/addcode/mohamed_math', [CodeController::class, 'mohamed_math']);
Route::get('/getcode/mohamed_math', [CodeController::class, 'get_mohamed_math']);

Route::post('/codes/validate', [CodeController::class, 'validateCode']);
Route::get('/code/check/{userId}/{macaddress}/{lesson_id}', [CodeController::class, 'checkUserCodeStatus']);
Route::get('/code/users', [CodeController::class, 'getAllCodesWithUsers']);
Route::get('/code/{user_id}', [CodeController::class, 'getUserLessonsWithCode']);
//mobile
Route::post('/codes/validate/mobile', [CodeController::class, 'validateCodeForMacAddress2']);
Route::get('/code/check/mobile/{userId}/{macaddress2}/{lesson_id}', [CodeController::class, 'checkUserCodeStatus2']);
Route::get('/get/packages/{user_id}', [CodeController::class, 'getValidLessonsWithDetailsByUserId']);
// Video
Route::post('/videos', [VideoController::class, 'store']);

Route::delete('/videos/{id}', [VideoController::class, 'destroy']);

// PDF    code
Route::post('/pdfs', [PDFController::class, 'store']);
Route::get('/pdfs/lesson/{lessonId}', [PDFController::class, 'getByLessonId']);
Route::delete('/pdfs/{id}', [PDFController::class, 'destroy']);

//  MCQ
Route::post('/mcqs', [MCQController::class, 'store']);
Route::get('/mcqs/lesson/{lessonId}', [MCQController::class, 'getByLessonId']);
Route::delete('/mcqs/{id}', [MCQController::class, 'destroy']);
Route::get('mcqs/package/{id}', [MCQController::class, 'getByPackageId']);



Route::post('/submit-score', [ScoreController::class, 'store']);
Route::get('/scores/user/{user_id}', [ScoreController::class, 'getByUserId']);
Route::get('/scores', [ScoreController::class, 'getAllScores']);


//questions
Route::post('/questions', [QuestionController::class, 'store']);
Route::get('/questions/lesson/{lessonId}', [QuestionController::class, 'getByLessonId']);




//approval user
Route::get('/approve_user/{id}', [AuthController::class, 'isApproved']);

use Illuminate\Support\Facades\Artisan;


Route::get('/manage-cache', function ( ){
    // Clear existing caches
    Artisan::call('storage:link');
    // Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    // Clear optimized files
    Artisan::call('optimize:clear');

    // Re-cache configuration
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return 'cache linked!';
});

Route::get('/link', function () {
    try {

        Artisan::call('storage:link');


        return response()->json(['message' => 'Storage linked successfully.'], 200);
    } catch (\Exception $e) {

        return response()->json(['message' => 'Failed to link storage.', 'error' => $e->getMessage()], 500);
    }
});



// Route::get('/uuid', [UUIDController::class,'getUUID']);

Route::get('/clear', function () {


    Artisan::call('route:clear');

    return 'cache linked!';
});





Route::get('/images', [ImageController::class, 'index']); // Get all images
Route::post('/images', [ImageController::class, 'store']); // Upload image




//code fixed
Route::get('/addcode/fixed', [CodeController::class, 'storefixed']);
Route::get('/addcode/mohamed_math', [CodeController::class, 'mohamed_math']);
Route::get('/getcode/mohamed_math', [CodeController::class, 'get_mohamed_math']);
Route::get('/getcode/ashraf', [CodeController::class, 'get_ashraf_codes']);


Route::post('/update-password/{user_id}', [AuthController::class, 'updatePassword']);


