<div class="containter-fluid">
	@include('base::inc.message')
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="fa fa-book"></span>Courses

				</h4>
			</div>
		</div>

		<div class="table-responsive margin-top">
			<br>
			<table width="100%" class="table table-hover">
				<thead>
					<tr>
						<th>Course Code</th>
						<th>Course Description</th>
						<th>Section</th>
						<th>Schedule</th>
						<th style="text-align:center"><b>Actions</b></th>
  					</tr>
				</thead>
				@if(count($subject)>0)
					@foreach($subject as $subject)
						<tr>
							<td>{{$subject->sub_code}}</a></td>
							<td>{{$subject->sub_desc}}</td>
							<td>{{$subject->sub_sec}}</td>
							<td>{{$subject->day}} - {{$subject->time}}</td>
							<td align="center">
								<a href="{{route('viewrecord',$subject->class_record_id)}}" style="color:gray" class="margin-right btn btn-default add-option"  data-toggle="tooltip" title="View records">										
										<span class="fa fa-calendar"  style="margin-right:5px"></span>
										Records
								</a>

								<a href="{{route('examlist',$subject->class_record_id)}}" style="color:gray" class="btn btn-default add-option"  data-toggle="tooltip" title="View examinations">										
										<span class="fa  fa-question-circle" style="margin-right:5px"></span>
										Exams
								</a>

							</td>

						</tr>
					@endforeach
				@endif
			</table>
				
				@if(count($subject)==0)
					<center class="margin-top">No subjects available</center>
				@endif
		</div>			
	</div>
</div>