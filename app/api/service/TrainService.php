<?php
/**
 * Train Server
 */

declare(strict_types=1);

namespace app\api\service;

use app\api\exception\ApiServiceException;
use app\common\model\TrainModel;

class TrainService extends BaseService
{
    protected TrainModel $model;

    public function __construct()
    {
        $this->model = new TrainModel();
    }

    /**
     * 创建
     * @param $param
     * @return int train的id
     */
    public function create($param): int
    {
        $result = $this->model::create($param);
        return $result->id;
    }

    /**
     * 更新
     * @param $id
     * @param $param
     * @return bool
     * @throws ApiServiceException
     */
    public function update($id, $param): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new ApiServiceException("数据不存在");
        }
        $result = $data->save($param);
        if (!$result)
        {
            throw new ApiServiceException("修改失败");
        }
        return true;
    }

    /**
     * 读取
     * @param $id
     * @return array
     * @throws ApiServiceException
     */
    public function read($id): array
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new ApiServiceException("数据不存在");
        }
        return $data->toArray();
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws ApiServiceException
     */
    public function delete($id): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new ApiServiceException("数据不存在");
        }
        $result = $data->delete();
        if (!$result)
        {
            throw new ApiServiceException("删除失败");
        }
        return true;
    }

    /**
     * 禁用
     * @param $id
     * @return bool
     * @throws ApiServiceException
     */
    public function disable($id): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new ApiServiceException("数据不存在");
        }
        $result = $data->save(['status' => false]);
        if (!$result)
        {
            throw new ApiServiceException("禁用失败");
        }
        return true;
    }

    /**
     * 启用
     * @param $id
     * @return bool
     * @throws ApiServiceException
     */
    public function enable($id): bool
    {
        $data = $this->model->findOrEmpty($id);
        if ($data->isEmpty())
        {
            throw new ApiServiceException("数据不存在");
        }
        $result = $data->save(['status' => true]);
        if (!$result)
        {
            throw new ApiServiceException("启用失败");
        }
        return true;
    }
}