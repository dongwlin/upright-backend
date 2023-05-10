<?php
/**
 * HardwareModel Model
 */
declare (strict_types = 1);

namespace app\common\model;

/**
 * @mixin \think\Model
 */
class HardwareModel extends BaseModel
{
    protected $name = 'hardware';

    public function train()
    {
        return $this->belongsTo(TrainModel::class, 'train_id');
    }
}
