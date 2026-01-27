<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\UserPermissionController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\WebsiteSettingController;
use App\Http\Controllers\Api\HomeCarouselController;
use App\Http\Controllers\Api\NavigationMenuController;
use App\Http\Controllers\Api\UserManagementController;
use App\Http\Controllers\Api\NewsPostController;
use App\Http\Controllers\Api\ServiceItemController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\EditorUploadController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\ReportController;

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

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/permissions/me', [PermissionController::class, 'me']);
    Route::get('/sections', [SectionController::class, 'index']);
    Route::patch('/sections/{slug}/visibility', [SectionController::class, 'toggleVisibility']);
    Route::get('/users', [UserPermissionController::class, 'users']);
    Route::get('/users/{user}/permissions', [UserPermissionController::class, 'show']);
    Route::put('/users/{user}/permissions', [UserPermissionController::class, 'update']);
    Route::get('/contents', [ContentController::class, 'index']);
    Route::post('/contents', [ContentController::class, 'store']);
    Route::put('/contents/{content}', [ContentController::class, 'update']);
    Route::delete('/contents/{content}', [ContentController::class, 'destroy']);
    Route::get('/settings', [WebsiteSettingController::class, 'show']);
    Route::post('/settings', [WebsiteSettingController::class, 'update']);
    Route::get('/home/carousels', [HomeCarouselController::class, 'index']);
    Route::post('/home/carousels', [HomeCarouselController::class, 'store']);
    Route::put('/home/carousels/{slide}', [HomeCarouselController::class, 'update']);
    Route::delete('/home/carousels/{slide}', [HomeCarouselController::class, 'destroy']);
    Route::get('/menus', [NavigationMenuController::class, 'index']);
    Route::post('/menus', [NavigationMenuController::class, 'store']);
    Route::put('/menus/{menu}', [NavigationMenuController::class, 'update']);
    Route::post('/menus/{menu}/header', [NavigationMenuController::class, 'updateHeader']);
    Route::delete('/menus/{menu}', [NavigationMenuController::class, 'destroy']);
    Route::get('/admin/users', [UserManagementController::class, 'index']);
    Route::post('/admin/users', [UserManagementController::class, 'store']);
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update']);
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy']);
    Route::get('/news', [NewsPostController::class, 'index']);
    Route::post('/news', [NewsPostController::class, 'store']);
    Route::put('/news/{post}', [NewsPostController::class, 'update']);
    Route::delete('/news/{post}', [NewsPostController::class, 'destroy']);
    Route::get('/services', [ServiceItemController::class, 'index']);
    Route::post('/services', [ServiceItemController::class, 'store']);
    Route::put('/services/{item}', [ServiceItemController::class, 'update']);
    Route::delete('/services/{item}', [ServiceItemController::class, 'destroy']);
    Route::get('/team', [TeamMemberController::class, 'index']);
    Route::post('/team', [TeamMemberController::class, 'store']);
    Route::put('/team/{member}', [TeamMemberController::class, 'update']);
    Route::delete('/team/{member}', [TeamMemberController::class, 'destroy']);
    Route::get('/contacts', [ContactMessageController::class, 'index']);
    Route::delete('/contacts/{message}', [ContactMessageController::class, 'destroy']);
    Route::get('/reports/monthly', [ReportController::class, 'monthly']);
    Route::post('/uploads/editor', [EditorUploadController::class, 'store']);
});
