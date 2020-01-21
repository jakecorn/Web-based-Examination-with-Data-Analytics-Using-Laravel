<?php

namespace Modules\Examination\Entities;

use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    protected $fillable = ['choice_desc','question_id','answer'];


    public function setChoiceDescAttribute($value) {
    	// remove extra spaces
        $this->attributes['choice_desc'] = trim(preg_replace('/\s+/', ' ', $value));
    }
}
