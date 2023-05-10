<?php
/**
 * api route
 */

use think\facade\Route;

Route::miss(function() {
    return api_error_404();
});

ROute::rule('/', 'Index');

Route::post('login', 'WXLoginController/index');

Route::group('test',function () {
    Route::get('', 'TestController/index');
})->middleware(\app\api\middleware\CheckAuth::class);

Route::get('test1', 'TestController/test1');