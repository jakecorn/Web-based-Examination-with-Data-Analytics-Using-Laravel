<?php
use Modules\Student\Http\Controllers\StudentController;
?>
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-gear"></span>My Account</h4>
			</div>
		</div>

		<form class="mv-margin" method="post" method="POST" role="form" enctype="multipart/form-data">
			{{csrf_field()}}
			<div class="form-group">
				<img src="{{Auth::user()->photo}}" onerror="this.src='/uploads/images/user.png'" style="border:1px solid gray;border-radius:20px;width:200px"/>	   
			</div>

			<div class="form-group">
		   		<label>Choose a Picture</label>
		   		<input type="file" name="photo" class="form-control">
			</div>

			<div class="form-group">
		   		<label>Student Number</label>
		   		<input type="text" name="stud_number" readonly="" data-toggle="tooltip" title="Only your instructor can change your student number." required="" value="{{StudentController::information()->stud_num}}" class="form-control">
			</div>

			<div class="form-group">
		   		<label>Name</label>
		   		<input type="text" name="name" readonly="" data-toggle="tooltip" title="Only your instructor can change your name." required="" value="{{Auth::user()->name}}" class="form-control">
			</div>

			<div class="form-group">
		   		<label>Student Address</label>
		   		<input type="text" name="stud_address" required="" value="{{StudentController::information()->stud_address}}" class="form-control">
			</div>

			<div class="form-group">
		   		<label>Contact Number</label>
		   		<input type="number" name="stud_contact_num" value="{{StudentController::information()->stud_contact_num}}" class="form-control">
			</div>

			<div class="form-group">
			   		<label for="course_id">Course</label>
			    	<select name="course_id" id="couse_id" class="form-control">
			    		@if(count($course)>0)
			    			@foreach($course as $course)
			    				<option value="{{$course->id}}" {{StudentController::information()->course_id==$course->id? 'selected':''}}>{{$course->course_desc}}</option>
			    			@endforeach
			    		@endif
			    	</select>
			</div>

			<div class="form-group">
			   		<label for="year">Year</label>
			   		<select name="year" id="year" class="form-control">
			    		<option {{StudentController::information()->year=="I"? 'selected':''}}>I</option>
			    		<option {{StudentController::information()->year=="II"? 'selected':''}}>II</option>
			    		<option {{StudentController::information()->year=="III"? 'selected':''}}>III</option>
			    		<option {{StudentController::information()->year=="IV"? 'selected':''}}>IV</option>
			    	</select>
			</div>

			<div class="form-group">
		   		<label>Username</label> <small>Must be at least 6 characters</small>
		   		<input type="text" name="username" required="" value="{{Auth::user()->username}}" class="form-control">
			</div>

			<div class="form-group">
				<span class="btn btn-default btn-sm"  onclick="changePassword()"><span class="fa fa-unlock-alt"></span>&nbsp; Change Password</span>
			</div>

			<div class="change-password" style="display:none">				
				<div class="form-group">
			   		<label>New Password</label> <small>Must be at least 6 characters</small>
			   		<input type="password" name="password" class="form-control">
				</div>

				<div class="form-group">
			   		<label>Password Confirmation</label>
			   		<input type="password" name="password_confirmation" class="form-control">
				</div>
			</div>

			<div class="form-group">
		   		<label>Current Password</label>
		   		<input type="password" name="current_password" required="" class="form-control">
			</div>

			<div class="form-group">
			   	<a href="/student" class="btn btn-default margin-right" style="color:gray">Cancel Update</a>
			   	<button type="submit" class="btn btn-primary">Update Account</button>
			</div>

		</form>
		
	</div>
</div>

<script type="text/javascript">
	var check=false
	function changePassword() {

		if(check==false){
			check=true;
			$('.change-password input').attr('required', true);
		}else{
			check=false;
			$('.change-password input').attr('required', false);
		}
		$('.change-password input').val("");
		$('.change-password').toggle(400);
	}
</script>
