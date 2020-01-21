<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;

class LongAnswer extends Model
{
    public $timestamps = false;
    protected $fillable = ['answer','student_id','question_id','score'];

    public function setAnswerAttribute($value) {
    	// remove extra spaces
        $this->attributes['answer'] = trim(preg_replace('/\s+/', ' ', $value));
    }
}
