<?php

namespace Modules\Teacher\Entities;

use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    protected $fillable = ['student_id','class_record_id'];
}
