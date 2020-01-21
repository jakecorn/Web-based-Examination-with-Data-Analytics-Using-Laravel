
<div class="containter-fluid">

	@include('teacher::classrecord.inc.buttons')

	@include('base::inc.message')
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		@include('teacher::classrecord.inc.studenttab')
		<form class="mv-margin file-upload-form" method="post" enctype="multipart/form-data">
		    	<input type="hidden" name="class_record_id" value="{{$detail[0]->id}}">
		    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
		    	<input type="hidden" name="selected" value="false">
			
			<div class="form-group" style="position:relative">
		   		<label for="stud_num">Choose File <small>(File downloaded from the official list of the registrar in a form of web page (.html) )</small></label>
		    	<input type="file"  class="form-control" name="file" required="">
		    </div>

			<div class="form-group row">
			   	<div class="col-md-5">
			   		<button type="submit"  class="btn btn-primary upload-button">Upload Student List</button>
			   	</div>
			</div>



			</form>
	</div>
</div>

<script type="text/javascript">
	$('.file-upload-form').submit(function(event) {
		$('.upload-button').attr("disabled",true);
	});
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