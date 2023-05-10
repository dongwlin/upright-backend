<?php
/**
 * Base Controller
 */

namespace app\api\controller;

use think\Request;

class BaseController
{
    protected string $openid;

    protected array $param;

    public function __construct()
    {
        $this->initData();
    }

    protected function initData(): void
    {
        $this->param = (array)request()->param();
        $this->uid = request()->uid ?? 0;
    }
}