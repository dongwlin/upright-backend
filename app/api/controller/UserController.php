<?php

namespace app\api\controller;

use app\api\exception\ApiServiceException;
use app\api\service\UserService;
use app\api\validate\UserValidate;
use think\exception\ValidateException;
use think\response\Json;

class UserController extends BaseController
{
    /**
     * 创建
     * @param UserService $service
     * @return Json
     */
    public function create(UserService $service): Json
    {
        $param = request()->only(['openid']);
        try {
            validate(UserValidate::class)->scene('create')->check($param);
            $service->create($param);
            return api_success();
        } catch (ValidateException $exception) {
            return api_error($exception->getMessage());
        }
    }

    /**
     * 修改
     * @param UserService $service
     * @return Json
     */
    public function update(UserService $service): Json
    {
        $param = request()->only(['nickname', 'gender', 'avatar', 'description']);
        try {
            validate(UserValidate::class)->scene('update')->check($param);
            $service->update($this->uid, $param);
            return api_success();
        } catch (ValidateException|ApiServiceException $exception) {
            return api_error($exception->getMessage());
        }
    }

    /**
     * 查询
     * @param UserService $service
     * @return Json
     */
    public function read(UserService $service): Json
    {
        try {
            return api_success($service->read($this->uid));
        } catch (ApiServiceException $exception) {
            return api_error($exception->getMessage());
        }
    }

    /**
     * 删除
     * @param UserService $service
     * @return Json
     */
    public function delete(UserService $service): Json
    {
        try {
            $service->delete($this->uid);
            return api_success();
        } catch (ApiServiceException $exception) {
            return api_error($exception->getMessage());
        }
    }
}