<?php
/**
 * User Model
 */
declare(strict_types=1);

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class User extends Model
{
    // 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    // 时间戳自动写入
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}