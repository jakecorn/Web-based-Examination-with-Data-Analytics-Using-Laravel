<?php
	use Modules\Teacher\Http\Controllers\TeacherController;
	use Modules\Utilitize\Util;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Item Statistics</title>
	<link rel="stylesheet" type="text/css" href="/css/app.css">
	<link rel="stylesheet" type="text/css" href="/css/mystyle.css">
	<script type="text/javascript" src="/js/app.js"></script>
</head>
<body class="white-bg">
	<?php $subject_list="";?>
	@if(count($exam)>0)
		@foreach($exam as $exam)
			@foreach(TeacherController::examClassRecord($exam->examination_id) as $class)
				@if($exam->examination_id==$selected_exam)
					<?php $subject_list.=$class->sub_code."-".$class->sub_sec." ". $class->day." ".$class->time.", ";?>
				@endif
			@endforeach
		@endforeach
	@endif

						

	<table class="margin-bottom">
		<tr>
			<th width="80px">Subject</th>
			<td>: {{$subject_list}}</td>
		</tr>

		<tr>
			<th>Student </th>
			<td>: {{$student_count}} students</td>
		</tr>

		<tr>
			<th>S.Y.</th>
			<td>: {{Util::get_session('sy')}}</td>
		</tr>

		<tr>
			<th>Semester</th>
			<td>: {{Util::get_session('semester')}} Semester</td>
		</tr>
		<tr>
			<th>Term</th>
			<td>: {{Util::get_session('class_record_type')}}</td>
		</tr>


	</table>
	<div class="graph">
		<div class="row">
			<div class="col-sm-10">
				<center>
					<h3>Item Data Analytics</h3>

				</center>			
				<table width="100%">
					<tr>
						<th>
							No.<br>
						</th>
					</tr>
					<?php $questionArray = array();?>
					@if(count($question))
						@foreach($question as $key => $question)
							<tr>
								<td style="width:45px" >{{$key+1}}</td>
								<td>
									<?php $difficulty =TeacherController::difficulty($question->id); ?>
									<?php $percent=($difficulty/$student_count)*96;?>
									
									<?php $difficulty_level = "";?>
									@if(($difficulty/$student_count)<0.2)
										<?php $class="danger";?>
										<?php $difficulty_level = "Difficult";?>
									@elseif(($difficulty/$student_count)<=0.8)
										<?php $class="success";?>
										<?php $difficulty_level = "Ideal";?>
									@elseif(($difficulty/$student_count)>0.8)
										<?php $class="primary";?>
										<?php $difficulty_level = "Easy";?>
									@endif



									@if($difficulty>0)

										<div class="bar btn-{{$class}}" style="width:{{$percent}}%">
											{{number_format(($difficulty/$student_count)*100,1)}}%

											<?php $discrimination=TeacherController::discrimination($question->id,$student_count);?>
											
											<?php $discrimination_level = "";?>
											@if($discrimination<=0)
												<span class="fa fa-remove disc-indicator revise"></span>
												<?php $discrimination_level = "To be revised";?>
											@else
												<?php $discrimination_level = "To be retained";?>
												<span class="fa fa-check disc-indicator retain"></span>												
											@endif
										</div>
										
									@else
										
										<span style="color:red">0.0%</span> <span class="fa fa-remove" style="color:red"></span>
									@endif
								</td>
							</tr>

							<?php array_push($questionArray, array($question->id,$difficulty_level,$discrimination_level,($difficulty/$student_count)*100)) ;?>
						@endforeach
					@endif
					

					<tr>
						<td></td>
						<td>
							<div class="graph-percent"><span class="pull-left">0%</span> 20%</div>
							<div class="graph-percent">40%</div>
							<div class="graph-percent">60%</div>
							<div class="graph-percent">80%</div>
							<div class="graph-percent pull-right" style="padding-right:18px">100%</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-sm-2">
				<div class="margin-bottom" style="margin-top:30%">
					<label>Legend</label>
					<div>
						<span class="btn-warning legend-color"></span> Difficult<br>
						<span class="btn-success legend-color"></span> Ideal<br>
						<span class="btn-primary legend-color"></span> Easy<br>

						<span class="btn-success legend-color fa fa-check legend-circle"></span>Retain<br>
						<span class="btn-danger legend-color fa fa-remove legend-circle"></span>Revise<br>
					</div>
				</div>
			</div>
		</div>

	</div>
	<br>
	<br>
	<div>
		<u><b>{{Auth::user()->name}}, {{Auth::user()->degree}}</b></u><br>
		Subject Instructor

	</div>

</body>
</html>