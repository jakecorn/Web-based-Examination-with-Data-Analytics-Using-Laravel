<?php

namespace Modules\Announcement\Entities;

use Illuminate\Database\Eloquent\Model;

class ClassRecordAnnouncement extends Model
{
    public $timestamps = false;
    protected $fillable = ['announcement'];
}
