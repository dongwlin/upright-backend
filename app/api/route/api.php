<?php
/**
 * api route
 */

use think\facade\Route;

//api miss route
Route::miss(function() {
    return api_error_404();
});
// api index route
Route::rule('/', 'Index');

// api test
Route::get('selectLoginStatus', 'test/index');
Route::get('aggreateDay', 'train/index');

Route::get("trains", "train/queryAll")->middleware(\app\api\middleware\CheckAuth::class);
Route::get("test", "train/queryAllT")->middleware(\app\api\middleware\CheckAuth::class);

// api login route
Route::post('login', 'WXLogin/index');

// user route
Route::group('user', function () {
//   Route::get('create', 'user/create');
   Route::post('/', 'user/update');
   Route::get('/', 'user/read');
   Route::delete('/', 'user/delete');
})->middleware(\app\api\middleware\CheckAuth::class);

// solution route
Route::group('solution', function () {
    Route::get('/obtainSolution', 'solution/query');
})->middleware(\app\api\middleware\CheckAuth::class);

Route::get('/try', "solution/query");