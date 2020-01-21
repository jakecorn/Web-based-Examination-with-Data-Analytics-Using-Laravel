
@include('announcement::inc.filetab')
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-floppy-o"></span>Upload File</h4>
			</div>
		</div>

		<form class="mv-margin" method="post" enctype="multipart/form-data">
			{{csrf_field()}}

			<div class="form-group">
			   		<label for="announcement">Choose File</label>
			    	<input type="file" name="file" required="" value="{{old('file')}}" class="form-control">
			</div>

			<div class="form-group">
			   		<label for="announcement">Description | Instruction</label>
			    	<textarea class="form-control"  id="announcement" name="description" placeholder="Type your announcement here">{{old('description')}}</textarea>
			</div>

			<div class="form-group">
				<label for="exam_instruction">Class</label> <small>(Choose a class where the file will be posted)</small>
				
				@if(count($class_list)>0)
					@foreach($class_list as $class)
						<div class="checkbox">
						    <label>
						      <input type="checkbox" name="class[]" value="{{$class->id}}"> {{$class->sub_code}} - {{$class->sub_sec}} {{$class->day}} {{$class->time}}
						    </label>
						</div>
					@endforeach
				@endif

			</div>
			<div class="form-group ">
			   	<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-submit"></span>Post File</button>
			</div>

		</form>
		
	</div>
</div>
