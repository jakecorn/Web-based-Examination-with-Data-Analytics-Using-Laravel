@include("announcement::inc.announcementtab")
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-comments-o"></span>Create Announcement</h4>
			</div>
		</div>

		<form class="mv-margin" method="post" enctype="multipart/form-data">
			{{csrf_field()}}
			<div class="form-group">
			   		<label for="announcement">Description | Instruction</label>
			   		<input type="hidden" name="file_id" value="{{$file[0]->id}}">
			    	<textarea class="form-control"  id="announcement" name="description" placeholder="Type your announcement here">{{$file[0]->description}}</textarea>
			</div>

			<div class="form-group">
				<label for="exam_instruction">Class</label> <small>(Choose a class where the file will be posted)</small>
				
				@if(count($class_list)>0)
					@foreach($class_list as $class)
						<div class="checkbox">
						    <label>
								<?php $selectedAttr="";?>
								@foreach($class_list_selected as $selected)
									<?php 
										if($selected->class_record_id==$class->id){
											$selectedAttr="checked=true";
										}
									?>
								@endforeach
						      	<input type="checkbox" name="class[]" {{$selectedAttr}} value="{{$class->id}}"> {{$class->sub_code}} - {{$class->sub_sec}} {{$class->day}} {{$class->time}}
						    </label>
						</div>
					@endforeach
				@endif

			</div>
			<div class="form-group ">
			<a href="{{route('announcementlist')}}" class="btn btn-default margin-right" style="color:gray">Cancel Update</a>
			   	<button type="submit" class="btn btn-primary">Save Update</button>
			</div>

		</form>
		
	</div>
</div>
