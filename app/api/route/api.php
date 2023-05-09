<?php
/**
 * api route
 */

use think\facade\Route;

Route::get('test', 'TestController/index');

Route::post('login', 'WXLoginController/index');
