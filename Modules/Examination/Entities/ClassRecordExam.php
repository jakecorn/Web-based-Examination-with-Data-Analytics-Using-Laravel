<?php

namespace Modules\Examination\Entities;

use Illuminate\Database\Eloquent\Model;

class ClassRecordExam extends Model
{
    protected $fillable = ['visibility','done_checking','lock'];
}
