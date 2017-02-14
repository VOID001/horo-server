<?php

namespace DevHoro\App;

use Illuminate\Database\Eloquent\Model;

class Horo extends Model
{
    protected $table = 'horo_info';

    public function Status() {
        return [
            "饥饿度" => $this->hunger,
            "生命值" => $this->hp,
            "等级" => $this->level,
            "智力" => $this->knowlege
        ];
    }
}