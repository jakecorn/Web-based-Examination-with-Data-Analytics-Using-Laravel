<?php

use Modules\Teacher\Http\Controllers\TeacherController;

?>

<div class="containter-fluid">

	@include('teacher::classrecord.inc.buttons')
	@include('base::inc.message')
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Class Record / {{$current_criteria_name[0]->criteria}}</h4>
			</div>
		</div>

		<br>
		<table class="margin-bottom">
   			<tr>
   				<td width="80px"><label for="sub_code">Subject </label></td>

   				<td>: {{$detail[0]->sub_code}} {{$detail[0]->sub_desc}} - {{$detail[0]->sub_sec}}</td>
   			</tr>

   			<tr>
   				<td><label for="sub_code">Schedule</label></td>
   				<td>: {{$detail[0]->day}} {{$detail[0]->time}}</td>
   			</tr>

   			<tr>
   				<td><label for="sub_code">Formula</label></td>
   				<td>: ( (Raw Score / Total Score) x {{$detail[0]->formula_times}}% ) + {{$detail[0]->formula_plus}} = {{$detail[0]->type}} Grade</td>
   			</tr>

   			<tr>
   				<td><label for="sub_code">Term </label> </td>
   				<td>: {{$detail[0]->type}}</td>
   			</tr>
	   	</table>  		
   		<form method="post">
	   		<div class="form-group">
	   			<label for="date">Topic: <small>(Optional)</small></label>
	   			<input type="text" name="topic" class="form-control" placeholder="Topic">
	   		</div>	   			
	   		<div class="form-group">	   			
	   			<label for="date">Date: </label>
	   			<input type="date" class="form-control" name="date" value="{{date("Y-m-d")}}" required="">
	   			<input type="hidden" name="criteria_name" value="{{$criteria_name}}">
	   			<input type="hidden" name="criteria_id" value="{{$criteria_id}}">
	   			<input type="hidden" name="class_record_id" value="{{$detail[0]->id}}">
	   			<input type="hidden" name="_token" value="{{ csrf_token() }}">
	   		</div>

	   		<div class="form-group">	   			
		   		<label for="totalScore">Total Score: </label>
		   		<input type="number" class="form-control" name="totalScore" id="totalScore" required="">
	   		</div>
	   		
	   		<br>
			<table class="table table-hover">
				<thead>				
					<tr>
						<?php $no=1;?>
						<th>No.</th>
						<th>ID</th>
						<th>Name</th>
						<th>Course</th>
						<th>Year Level</th>
						<th>Score</th>
					</tr>
				</thead>
				
				@if(count($student_list)>0)
					@foreach($student_list as $student)
						<tr>
							<td>{{$no++}}.</td>
							<td>{{$student->stud_num}}</td>
							<td>{{$student->stud_lname}}, {{$student->stud_fname}}</td>
							<td>{{TeacherController::getCourseCode($student->course_id)}}</td>
							<td align="center">{{$student->year}}</td>
							<td>
							<input type="hidden" name="student_id[]" value="{{$student->student_id}}">
							<input type="number" maxlength="5" name="score[]" onkeyup="checkValue(this)" class="form-control text-center">
							</td>
						</tr>
					@endforeach
				@endif

				<tr>
					<td colspan="6">
						<button class="btn btn-primary margin-top">Save Score</button>
					</td>
				</tr>
			</table>
		</form>

</div>

<script type="text/javascript">
	function checkValue(a) {
		var totalScore = parseInt($('#totalScore').val());
		var val = parseInt($(a).val());
		
		if(totalScore<val){
			 $(a).val("")
		}
	}
</script>