<?php
/**
 * @author lin
 * @date 2023/8/9
 * @time 16:44
 */

use think\facade\Route;

Route::miss(function () {
    return api_not_found();
});

Route::get('/', 'Index');

Route::get('login', 'Login/index');
Route::post('login/auth', 'Login/auth');
Route::get('captcha/[:config]','\\think\\captcha\\CaptchaController@index');

Route::get('/img-upload', 'ImgUpload/index');
Route::post('/img-upload/upload', 'ImgUpload/upload');