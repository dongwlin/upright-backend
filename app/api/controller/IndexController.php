<?php
declare (strict_types = 1);

namespace app\api\controller;

use think\response\Json;

class IndexController
{
    public function index(): Json
    {
        return api_success();
    }
}