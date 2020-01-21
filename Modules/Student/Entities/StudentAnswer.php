<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
	public $timestamps = false;
    protected $fillable = ['answer','student_id','question_id'];


    

}
