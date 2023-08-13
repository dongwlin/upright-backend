<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class ImgUploadValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'image' => ['file', 'image', 'fileSize' => 10*1024*1024, 'fileExt' => 'jpg,png']
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];
}
