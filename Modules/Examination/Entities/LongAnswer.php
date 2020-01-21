<?php

namespace Modules\Examination\Entities;

use Illuminate\Database\Eloquent\Model;

class LongAnswer extends Model
{
	public $timestamps = false;
    protected $fillable = ['score','answer'];
}
