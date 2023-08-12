<?php
/**
 * Auth Service
 */
declare(strict_types=1);

namespace app\admin\service;

use app\admin\model\AdminModel;

class AuthService extends BaseService
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
     * @param string $username
     * @param string $password
     * @return array|false[]
     */
    public function auth(string $username, string $password): array
    {
        $user = $this->model->where('username', '=', $username)->findOrEmpty();
        if (!$user->isEmpty() && password_verify($password, $user->password))
        {
            if ($user->status)
            {
                return [
                    'auth' => true,
                    'status' => true,
                    'uid' => $user->id,
                    'username' => $user->username,
                ];
            }
            return [
                'auth' => true,
                'status' => false,
            ];
        }
        return [
            'auth' => false,
            'status' => false,
        ];
    }
}