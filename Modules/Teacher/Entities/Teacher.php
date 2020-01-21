<?php

namespace Modules\Teacher\Entities;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
   public $timestamps = false;
    protected $fillable = ['teacher_id'];
}
