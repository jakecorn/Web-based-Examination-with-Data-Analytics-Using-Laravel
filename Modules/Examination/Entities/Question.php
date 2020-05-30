<?php

namespace Modules\Examination\Entities;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [];

    public function choices()
    {
        //return QuestionChoice;
        return $this->hasMany('Modules\Examination\Entities\QuestionChoice', 'question_id');
    }

    public function studentanswer()
    {
        //returns the student answer of the selected question
        return $this->hasMany('Modules\Student\Entities\StudentAnswer', 'question_id');
    }

    public function getanswer()
    {
        $choices  = $this->choices;
        $answer = [];
        foreach($choices as $choice){
            if($choice->answer == 1){
                array_push($answer, $choice->id);
            }
        }
        //choices_id of a correct answer
        return $answer ;
    }

    public function getstudentanswer($student_id)
    {
        //returns the student answer of the selected question
        $studentanswer  = $this->studentanswer->where('student_id', $student_id);
        return $studentanswer ;
    }
}
