
@include('admin::pages.coursetab')
@include('base::inc.message')
<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="fa fa-graduation-cap"></span>Program
				</h4>
			</div>
		</div>

		<form method="post">
			{{csrf_field()}}
			<div class="form-group margin-top">
				<label>Program Code</label>
				<input type="text" name="course_code" class="form-control" required="">
			</div>

			<div class="form-group">
				<label>Program Description</label>
				<input type="text" name="course_desc" class="form-control" required="">
			</div>

			<div class="form-group">
 				<button class="btn btn-primary">Save Course</button>
			</div>
		</form>
		

		</div>	
		
	</div>
</div>


