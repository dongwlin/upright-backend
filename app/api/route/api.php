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
Route::post('login', 'WXLoginController/index');
// api test route
Route::group('test',function () {
    Route::get('', 'TestController/index');
})->middleware(\app\api\middleware\CheckAuth::class);
// api test1 route
Route::get('test1', 'TestController/test1');