<?php
/**
 * User Service
 */
declare(strict_types=1);

namespace app\api\service;

use app\api\exception\ApiServiceException;
use app\common\model\UserModel;

class UserService extends BaseService
{
    protected UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * 创建
     * @param $param
     * @return bool
     */
    public function create($param): bool
    {
        if (!array_key_exists('nickname', $param))
        {
            $param['nickname'] = '微信用户';
        }
        if (!array_key_exists('avatar', $param))
        {
            $param['avatar'] = request()->host() . '/static/common/images/defaultAvatar.png';
        }
        $result = $this->model::create($param);
        return (bool)$result;
    }

    /**
     * 修改
     * @param int $id
     * @param $param
     * @return bool
     * @throws ApiServiceException
     */
    public function update(int $id, $param): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new ApiServiceException('数据不存在');
        }
        $result = $data->save($param);
        if (!$result)
        {
            throw new ApiServiceException('修改失败');
        }
        return true;
    }

    /**
     * 查询
     * @param int $id
     * @return array
     * @throws ApiServiceException
     */
    public function read(int $id): array
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->findOrEmpty())
        {
            throw new ApiServiceException('数据不存在');
        }
        return $data->toArray();
    }

    /**
     * 删除
     * @param int $id
     * @return bool
     * @throws ApiServiceException
     */
    public function delete(int $id): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new ApiServiceException('数据不存在');
        }
        $data->delete();
        return true;
    }
}