<?php

namespace Modules\Teacher\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Teacher\Entities\Criteria;
use Modules\Teacher\Entities\ClassRecord;

class ClassRecord extends Model
{
    protected $fillable = [];

    public function setSubSecAttribute($value)
    {
    	return $this->attributes['sub_sec']=ucfirst($value);
    }

}
