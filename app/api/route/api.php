<?php
/**
 * api route
 */

use think\facade\Route;

Route::post('login', 'WXLoginController/index');
Route::get('test', 'TestController/index');