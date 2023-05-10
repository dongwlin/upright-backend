<?php
/**
 * TrainModel Model
 */
declare(strict_types=1);

namespace app\common\model;

class TrainModel extends BaseModel
{
    protected $name = 'train';

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function hardware()
    {
        return $this->hasMany(HardwareModel::class, 'train_id');
    }
}