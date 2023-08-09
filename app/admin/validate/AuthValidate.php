<?php
/**
 * @author lin
 * @date 2023/8/9
 * @time 22:01
 */

namespace app\admin\validate;

class AuthValidate extends \think\Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username'  => ['require', 'max' => 255],
        'password'  => ['require', 'max' => 255],
        'captcha'   => ['require', 'captcha', 'max' => 255]
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [];
}