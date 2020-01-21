<?php

namespace Modules\Teacher\Entities;

use Illuminate\Database\Eloquent\Model;

class CriteriaRecord extends Model
{
    protected $fillable = ['criteria_id','date','total_score','class_record_id'];
}
