


<div class="containter-fluid">
	@include('teacher::classrecord.inc.buttons')
	@include('base::inc.message')
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Class Record</h4>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-hover">
				<thead>				
					<tr>
	 					<th>Course Code</th>
						<th>Course Description</th>
						<th>Section</th>
						<th>Day</th>
						<th>Time</th>
						<th>Term</th>
						<th colspan="3" align="center"><center>Actions</center></th>
					</tr>
				</thead>

				@if(count($class_record_list)>0)
					<?php $no=1;?>
					@foreach($class_record_list as $record)
						<tr>
							<td>{{$no++}}. {{$record->sub_code}}</td>
							<td>{{$record->sub_desc}}</td>
							<td>{{$record->sub_sec}}</td>
							<td>{{$record->day}}</td>
							<td>{{$record->time}}</td>
							<td>{{$record->type}}</td>
							<td align="center" class="list-action">
								<a data-href="{{route('deleteclassrecord',$record->id)}}" data-toggle="modal" data-target="#deleteModal" data-toggle="tooltip" title="Delete class record" class="fa fa-remove action btn-danger"></a>
							
								<a href="{{route('classrecordupdate',$record->id)}}" data-toggle="tooltip" title="Update class record" class="fa fa-pencil action btn-success"></a>
							
								<a href="{{route('classrecord',$record->id)}}" data-toggle="tooltip" data-placement="left" title="Open class record" class="fa fa-mail-forward btn-primary action"></a>
							</td>
						</tr>
					@endforeach

				@endif
				
			</table>
		</div>	
		
	</div>
</div>