<?php

namespace Modules\Teacher\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Teacher\Entities\ClassRecord;
use Modules\Teacher\Entities\Criteria;
class Criteria extends Model
{
    protected $fillable = [];


    public function setCriteriaAttribute($value)
    {
    	return $this->attributes['criteria']=ucwords($value);
    }
}
