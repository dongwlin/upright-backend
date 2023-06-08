<?php
/**
 * Test Controller
 */
declare(strict_types=1);

namespace app\api\controller;

use app\api\service\TokenService;
use think\response\Json;

class TestController extends BaseController
{
    public function index(): Json
    {
        return api_success(['uid' => $this->uid]);
    }
}