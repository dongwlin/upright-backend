<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;

class UserValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'openid'        => 'require|unique:user,openid',
        'nickname'      => 'max:255',
        'gender'        => 'integer',
        'avatar'        => 'url',
        'description'   => 'max:255',
        'status'        => 'boolean'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'create' => ['openid'],
        'update' => ['nickname', 'gender', 'avatar', 'description'],
    ];
}
