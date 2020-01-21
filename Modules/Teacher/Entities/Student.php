<?php

namespace Modules\Teacher\Entities;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['stud_num','password','stud_fname','stud_lname','stud_address','stud_contact_num','course_id','year'];

     public function setStudFnameAttribute($value) {
    	// remove extra spaces
        $this->attributes['stud_fname'] = ucwords(strtolower($value));
    }
     public function setStudLnameAttribute($value) {
    	// remove extra spaces
        $this->attributes['stud_lname'] = ucwords(strtolower($value));
    }
}
