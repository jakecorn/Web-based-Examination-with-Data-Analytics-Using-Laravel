<?php

namespace Modules\Announcement\Entities;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{	
	public $timestamps = false;
    protected $fillable = ['announcement'];
}
