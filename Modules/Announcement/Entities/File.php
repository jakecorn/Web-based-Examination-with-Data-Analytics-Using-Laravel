<?php

namespace Modules\Announcement\Entities;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $timestamps = false;
    protected $fillable = [];

    public function setFileTypeAttribute($value)
    {
    	return $this->attributes['file_type']=strtoupper($value);
    }
}
