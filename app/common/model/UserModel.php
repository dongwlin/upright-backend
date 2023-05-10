<?php
/**
 * UserModel Model
 */
declare(strict_types=1);

namespace app\common\model;

class UserModel extends BaseModel
{
    protected $name = 'user';

    public function train()
    {
        return $this->hasMany(TrainModel::class, 'user_id');
    }
}