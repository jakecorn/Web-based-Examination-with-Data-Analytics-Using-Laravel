
<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-gear"></span>Settings</h4>
			</div>
		</div>

		<form class="mv-margin" method="post">
			{{csrf_field()}}
			<div class="form-group">
		   		<label for="announcement">School Year</label>
		   		<select class="form-control" name="sy">
		   			@if(count($sy)>0)
		   				@foreach($sy as $sy)
		   					<option {{$sy==Session::get('sy')? "selected":""}}>{{$sy}}</option>
		   				@endforeach
		   			@endif
		   		</select>
			</div>

			<div class="form-group">
		   		<label for="announcement">Semester</label>
		   		<select class="form-control" name="semester">
		   				<option value="First" {{"First"==Session::get('semester')? "selected":""}}>First Semester</option>
		   				<option value="Second" {{"Second"==Session::get('semester')? "selected":""}}>Second Semester</option>
		   		</select>
			</div>

			<div class="form-group">
		   		<label for="announcement" name="class_record_type">Term</label>
		   		<select class="form-control" name="term">
		   				<option value="Midterm" {{"Midterm"==Session::get('class_record_type')? "selected":""}}>Midterm</option>
		   				<option value="Final" {{"Final"==Session::get('class_record_type')? "selected":""}}>Final Term</option>
		   		</select>
			</div>
			<div class="form-group">
			   	<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-submit"></span>Save Settings</button>
			</div>

		</form>
		
	</div>
</div>
