
<div class="containter-fluid">

	@include('teacher::classrecord.inc.buttons')
	@include('base::inc.message')

	<div class="content white-bg m-padding  gray-border mv-margin">
		@include('teacher::classrecord.inc.studenttab')
		<form class="mv-margin" method="post">
			
			<div class="form-group">
		   		<label for="stud_num">Student Number </label>
		    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
		    	<input type="hidden" name="student_id" value="{{$student[0]->id}}">
		    	<input type="hidden" name="class_record_id" value="{{$detail[0]->id}}">
		    	<input type="number" class="form-control" id="stud_num" name="stud_num" placeholder="Student Number" value="{{$student[0]->stud_num}}">
		    </div>

		    <div class="form-group">
		   		<label for="password">Password</label>
		    	<input type="text" class="form-control"  id="password" name="password" value="{{$student[0]->password}}">
		    </div>


			<div class="form-group">
			   		<label for="stud_fname">First name</label>
			    	<input type="text" class="form-control" id="stud_fname" name="stud_fname" placeholder="First name" value="{{$student[0]->stud_fname}}">
			</div>

			<div class="form-group">
			   		<label for="stud_lname">Last name</label>
			    	<input type="text" class="form-control" id="stud_lname" name="stud_lname" placeholder="Last name" value="{{$student[0]->stud_lname}}"> 
			</div>

			<div class="form-group">
			   		<label for="stud_address">Address</label>
			    	<input type="text" class="form-control" id="stud_address" name="stud_address" placeholder="Address" value="{{$student[0]->stud_address}}">
			</div>

			<div class="form-group">
			   		<label for="stud_contact_num">Contact Number</label>
			    	<input type="text" class="form-control" id="stud_contact_num" name="stud_contact_num" placeholder="Contact Number" value="{{$student[0]->stud_contact_num}}">
			</div>

			<div class="form-group">
			   		<label for="course_id">Program</label>
			    	<select name="course_id" id="couse_id" class="form-control">
			    		@if(count($course)>0)
			    			@foreach($course as $course)
			    				<option value="{{$course->id}}" {{$student[0]->course_id==$course->id? 'selected':''}}>{{$course->course_desc}}</option>
			    			@endforeach
			    		@endif
			    	</select>
			</div>

			<div class="form-group">
			   		<label for="year">Year</label>
			   		<select name="year" id="year" class="form-control">
			    		<option {{$student[0]->year=="I"? 'selected':''}}>I</option>
			    		<option {{$student[0]->year=="II"? 'selected':''}}>II</option>
			    		<option {{$student[0]->year=="III"? 'selected':''}}>III</option>
			    		<option {{$student[0]->year=="IV"? 'selected':''}}>IV</option>
			    	</select>
			</div>

  			<div class="form-group row">
			   	<div class="col-md-5">
			   		<a href="{{route('studentList',$detail[0]->id)}}" style="color:gray" class="btn btn-default margin-right">Cancel Update</a> 
			   		<button type="submit" class="btn btn-primary">Save Student</button>
			   	</div>
			</div>



			</form>
	</div>
</div>