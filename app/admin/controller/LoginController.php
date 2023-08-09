<?php
/**
 * Login Controller
 */
declare(strict_types=1);

namespace app\admin\controller;

use app\admin\validate\AuthValidate;
use think\exception\ValidateException;

class LoginController
{
    public function index()
    {
        return view();
    }

    public function auth()
    {
        try {
            validate(AuthValidate::class)->check(request()->param());
        } catch (ValidateException $exception) {
            return json([
               'code'   => 500,
               'msg'    => $exception->getError(),
               'data'   => []
            ]);
        }
        return json([
            'code'  => 200,
            'msg'   => 'success',
            'data'  => []
        ]);
    }


}