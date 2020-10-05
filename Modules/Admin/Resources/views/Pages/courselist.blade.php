
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

		<div class="table-responsive">
			<table class="table table-hover userlist">
				<thead>				
					<tr>
	 					<th>Course Code</th>
						<th>Course Description</th>

						<th align="center"><center>actions</center></th>
					</tr>
				</thead>
				<tbody class="search-result">
					
				</tbody>
				<tbody class="users">
					<?php $count_course=count($course);?>
					@if($count_course>0)

						@foreach($course as $course)
 							<tr>
								<td>{{$course->course_code}}</td>
								<td>{{$course->course_desc}}</td>
							
								<td align="center" class="list-action"> 							
									<a data-href="{{route('courseDelete',$course->id)}}" data-toggle="modal" data-target="#deleteModal"   title="Delete course" class="fa fa-remove action btn-danger"></a>
									<a href="{{route('courseEdit',$course->id)}}" data-toggle="tooltip"   title="Update Program" class="fa fa-pencil action btn-success"></a>
								</td>
							</tr>
						@endforeach

					@endif
				</tbody>
				
			</table>
		

		</div>	
		
	</div>
</div>


