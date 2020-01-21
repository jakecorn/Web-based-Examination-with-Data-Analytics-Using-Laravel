<?php

namespace Modules\Teacher\Entities;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['score','criteria_record_id','student_id'];
}
