<div class="row tab-container">			
	<a href="{{route('studentList',$detail[0]->id)}}">		
		<div class="col-sm-3 {{{ $page_title=='studentList'? 'active' : ''}}}">
			<h4 class="bold tab-title student-option"><span class="glyphicon glyphicon-user"></span>Student List</h4>
		</div>
	</a>

	<a href="{{route('getAddStudent',$detail[0]->id)}}">
		<div class="col-sm-3  {{{ $page_title=='createStudent'? 'active' : ''}}}">
			<h4 class="bold tab-title student-option"><span class="glyphicon glyphicon-plus"></span>Add Student</h4>
		</div>
	</a>

	<a href="{{route('uploadStudent',$detail[0]->id)}}">
		<div class="col-sm-4  {{{ $page_title=='uploadStudent'? 'active' : ''}}}">
			<h4 class="bold tab-title student-option"><span class="fa fa-upload"></span>Upload Student List</h4>
		</div>
	</a>
</div>