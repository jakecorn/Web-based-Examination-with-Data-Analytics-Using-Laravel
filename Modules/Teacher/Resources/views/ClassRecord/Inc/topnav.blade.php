
<?php
use Modules\Teacher\Http\Controllers\TeacherController;

?>


<div class="row sub-nav">						
	<div class="col-sm-3 col">
		<a href="{{route('classrecord',$detail[0]->id)}}">
			<div class="m-padding white-bg but">
				<span class="fa fa-tasks" id="icon"></span>
				<span class="name">Records</span>
			</div>
		</a>
	</div>
	<div class="col-sm-3 col">
		<a href="{{route('studentList',$detail[0]->id)}}">			
			<div class="m-padding white-bg but">
				<span class="fa fa-group" id="icon"></span>
				<span class="name">Students</span>
				<span class="number">{{count(TeacherController::countStudent($detail[0]->id))}}</span>
			</div>
		</a>
	</div>
	<div class="col-sm-3 col">
		<a href="{{route('exam',$detail[0]->id)}}">	
			<div class="m-padding white-bg but">
				<span class="fa fa-files-o" id="icon"></span><span class="name">Examination</span>
				<span class="number">{{TeacherController::countExam($detail[0]->id)}}</span>
			</div>
		</a>
	</div>
</div>