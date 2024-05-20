<?php

declare(strict_types=1);

namespace app\api\controller;

class TestController
{
    function index(): \think\response\Json
    {
        return api_success();
    }
}