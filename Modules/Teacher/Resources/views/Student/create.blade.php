
<div class="containter-fluid">

	@include('teacher::classrecord.inc.buttons')

	@include('base::inc.message')
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		@include('teacher::classrecord.inc.studenttab')
		<form class="mv-margin" method="post">
		    	<input type="hidden" name="class_record_id" value="{{$detail[0]->id}}">
		    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
		    	<input type="hidden" name="selected" value="false">
			
			<div class="form-group" style="position:relative">
		   		<label for="stud_num">Student Number </label>
		    	<input type="number" autocomplete="false" class="form-control" id="stud_num" onkeyup="searchStudent(this)" name="stud_num" placeholder="This will uniquely identify each student. Must be unique." value="{{old('stud_num')}}">
	    		<table class="student-suggestion table-hover"></table>
		    </div>

 
		    <div class="form-group">
		   		<label for="password">Password</label>
		    	<input type="text" class="form-control"   id="password" name="password" value="{{$password}}" >
		    </div>


			<div class="form-group">
			   		<label for="stud_fname">First name</label>
			    	<input type="text" class="form-control" id="stud_fname" name="stud_fname" placeholder="First name" value="{{old('stud_fname')}}">
			</div>

			<div class="form-group">
			   		<label for="stud_lname">Last name</label>
			    	<input type="text" class="form-control" id="stud_lname" name="stud_lname" placeholder="Last name" value="{{old('stud_lname')}}">
			</div>

			<div class="form-group">
			   		<label for="stud_address">Address</label>
			    	<input type="text" class="form-control" id="stud_address" name="stud_address" placeholder="Address" value="{{old('stud_address')}}">
			</div>

			<div class="form-group">
			   		<label for="stud_contact_num">Contact Number</label>
			    	<input type="number" class="form-control" id="stud_contact_num" name="stud_contact_num" placeholder="Contact number	" value="{{old('stud_contact_num')}}">
			</div>

			<div class="form-group">
			   		<label for="course_id">Course</label>
			    	<select name="course_id" id="couse_id" class="form-control">
			    		@if(count($course)>0)
			    			@foreach($course as $course)
			    				<option value="{{$course->id}}" {{old('course_id')==$course->id? "selected":""}} >{{$course->course_desc}}</option>
			    			@endforeach
			    		@endif
			    	</select>
			</div>

			<div class="form-group">
			   		<label for="year">Year</label>
			   		<select name="year" id="year" class="form-control">
			    		<option {{old('year')=="I"? "selected":""}} value="I">I</option>
			    		<option {{old('year')=="II"? "selected":""}} value="II">II</option>
			    		<option {{old('year')=="III"? "selected":""}} value="III">III</option>
			    		<option {{old('year')=="IV"? "selected":""}} value="IV">IV</option>
			    	</select>
			</div>

			<div class="form-group row">
			   	<div class="col-md-5">
			   		<button type="submit" class="btn btn-primary">Add Student</button>
			   	</div>
			</div>



			</form>
	</div>
</div>

<script type="text/javascript">
	function searchStudent(a) {
		var search = $(a).val();
		var table = $('.student-suggestion');
		$(".form-group input,.form-group select").attr({
			'readonly': false,
			'disabled': false
		});
		$(".form-group input").val("");
		$(a).val(search);
		$('form [name="password"]').val($('form [name="password"]').attr("value")).attr("readonly",true);
		$('form [name="selected"]').val("false");
		table.show().html("<tr><td align='center'>Loading...</td></tr>");
		$.ajax({
			type: 'POST',
			url: "/teacher/classrecord/student/search",
			data: {"search": search},

			success:function(data){
				table.html(data);
	 		}
		});
	}

	function chooseStudent(stud_num,stud_fname,stud_lname,stud_address,stud_contact_num,course_id,year) {
		
		$('.form-group [name="stud_fname"]').val(stud_fname);
		$('.form-group [name="stud_lname"]').val(stud_lname);
		$('.form-group [name="stud_address"]').val(stud_address);
		$('.form-group [name="stud_contact_num"]').val(stud_contact_num);
		$('.form-group [name="password"]').val("*******");
		$(".form-group [name='course_id'] option[value="+course_id+"]").attr("selected",true);
		$(".form-group [name='year'] option[value="+year+"]").attr("selected",true);
		$(".form-group input").attr("readonly",true);
		$(".form-group select").attr("disabled",true);
		$('.form-group [name="stud_num"]').val(stud_num).attr('readonly',false);
		$('form [name="selected"]').val("true");
		$('.student-suggestion').hide();
	}
	$('.form-group input').click(function(event) {
		$('.student-suggestion').hide();
	});
 </script>