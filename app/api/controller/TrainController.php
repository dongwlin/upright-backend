<?php

declare(strict_types=1);

namespace app\api\controller;

use app\api\service\TrainService;

class TrainController
{
    function query(string $id, TrainService $service): \think\response\Json
    {
        return api_success($service->read($id));
    }

    function queryAll(TrainService $service): \think\response\Json
    {
        return api_success($service->queryAll());
    }

    function queryAllT(TrainService $service): \think\response\Json
    {
        $ret = $service->queryAll();
        $data = [];
        for ($i = 0; $i < sizeof($ret); $i++) {
            $data[$ret[$i]["startTime"]] = $ret[$i];
        }
        return api_success($data);
    }
}