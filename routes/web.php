<?php

use App\Http\Controllers\Archieve\Mail\IncomingMailController;
use App\Http\Controllers\Archieve\Mail\OutgoingMailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\ClassificationController;
use App\Http\Controllers\Master\InstitutionController;
use App\Http\Controllers\Master\TypeMailContentController;
use App\Http\Controllers\User\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Home Route
 */
Route::get('/', function () {
    return view('home');
})->name('home');

/**
 * Auth Route
 */
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('forgot', [AuthController::class, 'forgot'])->name('forgot');
Route::post('forgot-authenticate', [AuthController::class, 'forgotAuthenticate'])->name('forgot-authenticate');

/**
 * Admin Route Access
 */
Route::group(['middleware' => ['role:admin']], function () {
    /**
     * Master Module
     */
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        /**
         * Route Institution Module
         */
        Route::group(['controller' => InstitutionController::class, 'prefix' => 'institution', 'as' => 'institution.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('institution', InstitutionController::class, ['except' => ['store']])->parameters(['institution' => 'id']);

        /**
         * Route Type Mail Content Module
         */
        Route::group(['controller' => TypeMailContentController::class, 'prefix' => 'type-mail-content', 'as' => 'type-mail-content.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('type-mail-content', TypeMailContentController::class)->parameters(['type-mail-content' => 'id']);

        /**
         * Route Classification Module
         */
        Route::group(['controller' => ClassificationController::class, 'prefix' => 'classification', 'as' => 'classification.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('classification', ClassificationController::class)->parameters(['classification' => 'id']);
    });

    /**
     * Route User Management Module
     */
    Route::group(['controller' => UserManagementController::class, 'prefix' => 'user-management', 'as' => 'user-management.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
    });
    Route::resource('user-management', UserManagementController::class)->parameters(['user-management' => 'id']);
});

/**
 * Admin and User Route Access
 */
Route::group(['middleware' => ['role:admin|user']], function () {
    /**
     * Master Module
     */
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        /**
         * Institution Module (Only Store Record)
         */
        Route::resource('institution', InstitutionController::class, ['except' => ['index', 'create', 'show', 'edit', 'update', 'destroy']])->parameters(['institution' => 'id']);
    });

    /**
     * Archieve Module
     */
    Route::group(['prefix' => 'archieve', 'as' => 'archieve.'], function () {
        /**
         * Mail Route
         */
        Route::group(['prefix' => 'mail', 'as' => 'mail.'], function () {
            /**
             * Route Incoming Mail Module
             */
            Route::group(['controller' => IncomingMailController::class, 'prefix' => 'incoming-mail', 'as' => 'incoming-mail.'], function () {
                Route::get('datatable', 'dataTable')->name('dataTable');
            });
            Route::resource('incoming-mail', IncomingMailController::class)->parameters(['incoming-mail' => 'id']);

            /**
             * Route Outgoing Mail Module
             */
            Route::group(['controller' => OutgoingMailController::class, 'prefix' => 'outgoing-mail', 'as' => 'outgoing-mail.'], function () {
                Route::get('datatable', 'dataTable')->name('dataTable');
            });
            Route::resource('outgoing-mail', OutgoingMailController::class)->parameters(['outgoing-mail' => 'id']);
        });
    });

    /**
     * Route Global Institution Module
     */
    Route::group(['controller' => Controller::class, 'prefix' => 'institution', 'as' => 'institution.'], function () {
        Route::get('get-institution/{level}/{global}', 'getInstitution')->name('getInstitution');
    });
});
