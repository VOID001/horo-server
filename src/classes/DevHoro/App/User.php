<?php

namespace DevHoro\App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user_info';
    protected $primaryKey = 'machine_id';
}