<?php
/**
 * Base Model
 */
declare(strict_types=1);

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    // 软删除
    use SoftDelete;
    // 时间戳自动写入
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';
}