<?php
/**
 * api route
 */

use think\facade\Route;

Route::get('test', 'TestController/index')->middleware(\app\api\middleware\CheckAuth::class);

Route::post('login', 'WXLoginController/index');
