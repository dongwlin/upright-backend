<?php
/**
 * Login Controller
 */
declare(strict_types=1);

namespace app\admin\controller;

use app\admin\validate\AuthValidate;
use think\exception\ValidateException;
use think\response\Json;

class LoginController
{
    public function index()
    {
        return view();
    }

    public function auth(): Json
    {
        try {
            validate(AuthValidate::class)->check(request()->param());
        } catch (ValidateException $exception) {
            return api_unauthorized($exception->getError());
        }
        return api_ok();
    }


}