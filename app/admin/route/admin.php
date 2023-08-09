<?php
/**
 * @author lin
 * @date 2023/8/9
 * @time 16:44
 */

use think\facade\Route;

Route::get('captcha/[:config]','\\think\\captcha\\CaptchaController@index');