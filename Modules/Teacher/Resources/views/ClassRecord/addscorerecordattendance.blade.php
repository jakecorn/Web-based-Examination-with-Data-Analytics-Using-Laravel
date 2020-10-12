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
   				<td width="80px"><label for="sub_code">Course </label></td>

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
	   			<label for="date">Date:</label>
	   			<input type="date" id="date" class="form-control" value="{{date("Y-m-d")}}" name="date" required="">
	   			<input type="hidden" name="criteria_name" value="{{$criteria_name}}">
	   			<input type="hidden" name="criteria_id" value="{{$criteria_id}}">
	   			<input type="hidden" name="class_record_id" value="{{$detail[0]->id}}">
	   			<input type="hidden" name="_token" value="{{ csrf_token() }}">

	   		</div>

	   		<div class="form-group hidden">	   			
		   		<label for="totalScore">Total Score: </label>
		   		<input type="number" class="form-control" value="1" name="totalScore" required="">
	   		</div>
	   		
	   		<br>
	   		<div class="table-responsive">
				<table class="table table-hover table-attendance">
					<thead>				
						<tr>
							<?php $no=1;?>
							<th>No.</th>
							<th class="not-important">ID</th>
							<th>Name</th>
							<th class="not-important">Program</th>
							<th class="not-important">Year Level</th>
							<th><center>Present</center></th>
						</tr>
					</thead>
					
					@if(count($student_list)>0)
						@foreach($student_list as $student)
							<tr>
								<td>{{$no++}}.</td>
								<td class="not-important">{{$student->stud_num}}</td>
								<td>
									<label for="score{{$student->student_id}}" style="font-weight:normal">
										{{$student->stud_lname}}, {{$student->stud_fname}}</td>
								
									</label>
								<td class="not-important"><label for="score{{$student->student_id}}" style="font-weight:normal">{{TeacherController::getCourseCode($student->course_id)}}</label></td>
								<td align="center" class="not-important"><label for="score{{$student->student_id}}" style="font-weight:normal;text-align:center">{{$student->year}}</label></td>
								<td align="center">
								<input type="hidden" id="student_id" name="student_id[]" value="{{$student->student_id}}">
								<input type="hidden" id="score" name="score[]" value="1">
								<input type="checkbox" checked="true"  onchange="attendance(this)" style="margin:5px" id="score{{$student->student_id}}">
								</td>
							</tr>
						@endforeach
					@endif

					<tr>
						<td colspan="6">
							<button class="btn btn-primary margin-top">Save Attendance</button>
						</td>
					</tr>
				</table>
			</div>
		</form>

</div>
