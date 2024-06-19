<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use TaskManager\Http\Controllers\TagController;
use TaskManager\Http\Controllers\PostController;
use TaskManager\Http\Controllers\UserController;
use TaskManager\Http\Controllers\MediaController;
use TaskManager\Http\Controllers\AuthorController;
use TaskManager\Http\Controllers\CourseController;
use TaskManager\Http\Controllers\QrCodeController;
use TaskManager\Http\Controllers\SchoolController;
use TaskManager\Http\Controllers\FacultyController;
use TaskManager\Http\Controllers\MessageController;
use TaskManager\Http\Controllers\TaskManagerController;
use TaskManager\Http\Controllers\DashboardController;
use TaskManager\Http\Controllers\DepartmentController;
use TaskManager\Http\Controllers\SubscriberController;
use TaskManager\Http\Controllers\UniversityController;
use TaskManager\Http\Controllers\LibraryResourceController;
use TaskManager\Http\Controllers\LibraryResourceTypeController;

Route::get('/update', [TaskManagerController::class, 'update']);
Route::get('/optimize/clear', [TaskManagerController::class, 'artisanOptimizeClear']);
Route::get('/migrate', [PostController::class, 'migrate']);
Route::get('/seed', [PostController::class, 'seed']);
Route::get('/tags/seed', [TagController::class, 'seed']);
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return nl2br(Artisan::output());
});
Route::get('/dump-autoload', function () {
    Artisan::call('dump-autoload');
    return nl2br(Artisan::output());
});

Route::group(['middleware' => ['web']], function () {

    //app
    Route::get('/api/initialize', [TaskManagerController::class, 'initialize']);
    Route::get('/api/tags', [TaskManagerController::class, 'tags']);
    Route::get('/api/adverts', [TaskManagerController::class, 'adverts']);
    Route::get('/issues/{issueNumber}/download', [TaskManagerController::class, 'issuesDownload'])->middleware('auth'); //download the issue soft copy
    Route::get('/issues/{name}', [TaskManagerController::class, 'issue']);

    // QR codes
    Route::get('/qr-codes', [QrCodeController::class, 'website']);
    Route::get('/qr-codes/contact-us', [QrCodeController::class, 'websiteContactUs']);
    Route::get('/qr-codes/subscribe', [QrCodeController::class, 'websiteSubscribe']);
    Route::get('/qr-codes/posts/create', [QrCodeController::class, 'websitePostCreate']);
    Route::get('/qr-codes/instagram', [QrCodeController::class, 'instagram']);
    Route::get('/qr-codes/whatsapp/channel/invite', [QrCodeController::class, 'WhatsAppChannelInvite']);

    //post
    Route::get('/api/posts/user', [PostController::class, 'user']);
    Route::get('/api/posts/preview/{id}', [PostController::class, 'preview'])->middleware('auth');
    Route::get('/api/posts/review', [PostController::class, 'review']);
    Route::get('/api/posts/statistics/{token}', [PostController::class, 'updateStatistics']);
    Route::post('/api/posts/approve', [PostController::class, 'approve']);
    Route::post('/api/posts/decline', [PostController::class, 'decline']);
    Route::post('/api/posts/image-upload', [PostController::class, 'uploadImage']);
    Route::resource('/api/posts', PostController::class);

    //users
    Route::get('/api/profile', [UserController::class, 'profile'])->middleware('auth');

    //authors
    Route::resource('/api/authors', AuthorController::class);

    //tags
    Route::get('/api/tags/navbar', [TagController::class, 'navbar']);
    Route::patch('/api/tags/all', [TagController::class, 'updateAll']);
    Route::resource('/api/tags', TagController::class);

    //messages
    Route::post('/api/messages/call-back-request', [MessageController::class, 'storeCallBackRequest']);
    Route::resource('/api/messages', MessageController::class);

    //subscribers
    Route::resource('/api/subscribers', SubscriberController::class);

    //admin: media
    Route::resource('/api/media', MediaController::class);
});

Route::group(['middleware' => ['web', 'auth']], function () {

    //creators of content must have accounts
    Route::get('/posts/create', [TaskManagerController::class, 'index']);


    //dashboards
    Route::get('/api/dashboard/admin', [DashboardController::class, 'admin']);

    //Admin
    Route::get('/migrate-and-seed', [PostController::class, 'migrateAndSeed']);

    //admin: universities
    Route::post('/api/universities/import/courses', [UniversityController::class, 'importCourses']);
    Route::post('/api/universities/import', [UniversityController::class, 'import']);
    Route::get('/api/universities/import/template', [UniversityController::class, 'importTemplate']);
    Route::resource('/api/universities', UniversityController::class);

    //admin: schools
    Route::resource('/api/schools', SchoolController::class);

    //admin: faculties
    Route::resource('/api/faculties', FacultyController::class);

    //admin: departments
    Route::resource('/api/departments', DepartmentController::class);

    //admin: courses
    Route::post('/api/courses/import', [CourseController::class, 'import']);
    Route::get('/api/courses/import/template', [CourseController::class, 'importTemplate']);
    Route::resource('/api/courses', CourseController::class);

    //admin: library
    Route::resource('/api/library/resources/types', LibraryResourceTypeController::class);
    Route::resource('/api/library/resources', LibraryResourceController::class);
});
