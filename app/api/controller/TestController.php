<?php

namespace app\api\controller;

class TestController
{
    public function index()
    {
        dump(request()->host());
    }

}