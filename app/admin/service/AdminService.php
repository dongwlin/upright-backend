<?php
/**
 * Admin User Service
 */
declare(strict_types=1);

namespace app\admin\service;

use app\admin\exception\AdminServiceException;
use app\admin\model\AdminModel;

class AdminService extends BaseService
{
    /**
     * @var AdminModel
     */
    protected AdminModel $model;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new AdminModel();
    }

    /**
     * 创建
     * @param array $param
     * @return bool
     */
    public function create(array $param): bool
    {
        if (array_key_exists('password', $param))
        {
            $param['password'] = password_hash($param['password'], PASSWORD_BCRYPT);
            $this->model::create($param);
            return true;
        }
        return false;
    }

    /**
     * 查找
     * @param int $id
     * @return array
     * @throws AdminServiceException
     */
    public function read(int $id): array
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new AdminServiceException("数据不存在");
        }
        return $data->toArray();
    }

    /**
     * 修改
     * @param int $id
     * @param array $param
     * @return bool
     * @throws AdminServiceException
     */
    public function update(int $id, array $param): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new AdminServiceException("数据不存在");
        }
        if (array_key_exists('password', $param))
        {
            $param['password'] = password_hash($param['password'], PASSWORD_BCRYPT);
        }
        $result = $data->save($param);
        if (!$result)
        {
            throw new AdminServiceException("修改失败");
        }
        return true;
    }

    /**
     * 删除
     * @param int $id
     * @return bool
     * @throws AdminServiceException
     */
    public function delete(int $id): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new AdminServiceException("数据不存在");
        }
        $result = $data->delete();
        if (!$result)
        {
            throw new AdminServiceException("删除失败");
        }
        return true;
    }

    /**
     * 禁用
     * @param int $id
     * @return bool
     * @throws AdminServiceException
     */
    public function disable(int $id): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new AdminServiceException("数据不存在");
        }
        $result = $data->save(['status' => false]);
        if (!$result)
        {
            throw new AdminServiceException("禁用失败");
        }
        return true;
    }

    /**
     * 启用
     * @param int $id
     * @return bool
     * @throws AdminServiceException
     */
    public function enable(int $id): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new AdminServiceException("数据不存在");
        }
        $result = $data->save(['status' => true]);
        if (!$result)
        {
            throw new AdminServiceException("启用失败");
        }
        return true;
    }
}