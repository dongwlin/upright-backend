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
ROute::rule('/', 'Index');
// api login route
Route::post('login', 'WXLogin/index');
// api test route
Route::group('test',function () {
    Route::get('', 'Test/index');
})->middleware(\app\api\middleware\CheckAuth::class);
// api test1 route
Route::get('test1', 'Test/test1');

// user route
Route::group('user', function () {
   Route::get('create', 'user/create');
   Route::post('update', 'user/update');
   Route::get('read', 'user/read');
   Route::delete('delete', 'user/delete');
})->middleware(\app\api\middleware\CheckAuth::class);