<?php
/**
 * Login Controller
 */
declare(strict_types=1);

namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\admin\service\AuthService;
use app\admin\validate\AuthValidate;
use think\exception\ValidateException;
use think\facade\Session;
use think\response\Json;

class LoginController
{
    public function index()
    {
        return view();
    }

    public function auth(AuthService $service): Json
    {
        $param = request()->only(['username', 'password', 'captcha']);
        try {
            validate(AuthValidate::class)->check($param);
        } catch (ValidateException $exception) {
            return api_unauthorized($exception->getError());
        }
        $result = $service->auth($param['username'], sha1($param['password']));
        if ($result['auth'] && $result['status'])
        {
            return api_ok();
        }
        else if ($result['auth'] && !$result['status'])
        {
            return api_forbidden();
        }
        return api_unauthorized();
    }
}