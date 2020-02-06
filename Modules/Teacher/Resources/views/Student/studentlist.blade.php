<?php

use Modules\Teacher\Http\Controllers\TeacherController;

?>
<div class="containter-fluid">
	@include('teacher::classrecord.inc.buttons')

	@include('base::inc.message')
	<div class="content white-bg m-padding  gray-border mv-margin">
		@include('teacher::classrecord.inc.studenttab')
		
		<br>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>				
					<tr>
						<th>No.</th>
						<th>ID.</th>
						<th>Name</th>
						<th>Address</th>
						<th>Contact #</th>
						<th>Username</th>
						<th>Password</th>
						<th>Course</th>
						<th>Actions</th>
					</tr>
				</thead>
				<?php $no = 1; ?>

				@if(count($student_list)>0)
					@foreach($student_list as $key => $student)
						<tr>
							<td>{{$no++}}.</td>
							<td>{{$student->stud_num}}</td>
							<td class="stud-name"><div>{{$student->stud_lname}}, {{$student->stud_fname}}</div></td>
							<td>{{$student->stud_address}}</td>
							<td>{{$student->stud_contact_num}}</td>
							<td>{{$student->username}}</td>
							<td align="center" class="password-col">
								<span class="password" style="margin-right:8px">{{$student->password}}</span><span class="password asterisk">*****</span><span title="Show password" onclick="showPassword(this)" style="color:gray" class="fa fa-eye action"></span><span title="Hide passwordddd" onclick="showPassword(this)" style="color:gray" class="fa fa-eye-slash action"></span>
							</td>
							<td align="center">{{TeacherController::getCourseCode($student->course_id)}}-{{$student->year}}</td>
							<td align="center" class="list-action">
								<a data-href="{{route('deleteStudent',$student->student_id)}}" data-toggle="modal" data-target="#deleteModal" title="Remove student from class record" class="fa fa-remove action btn-danger"></a>
							
								<a href="{{route('updateStudent',[$detail[0]->id,$student->student_id])}}" data-toggle="tooltip" data-placement="left"  title="Edit student information" class="fa fa-pencil action btn-success" onclick="editStudent({{$student->id}})"></a>
								
							</td>
						</tr>
					@endforeach
				@endif


			</table>
		</div>
	</div>
</div>