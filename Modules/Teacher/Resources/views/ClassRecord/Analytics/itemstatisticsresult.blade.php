<?php
	use Modules\Teacher\Http\Controllers\TeacherController;
	use Modules\Utilitize\Util;
?>

<div class="containter-fluid">
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-line-chart"></span>Data Statistics</h4>
			</div>
		</div>

		<div class="form-group">
			<label class="margin-top">Type</label>
			<select class="form-control"  onchange="return analyticsType(this)" name="type">
				<option value="analysis">Item Analysis</option>
				<option value="statistics" selected="">Item Statistics</option>
			</select>
		</div>

		<div class="form-group">
			<label>Subject</label>
			<select class="form-control"  name="class">
				@if(count($exam)>0)
					<?php $subject_list="";?>
					@foreach($exam as $exam)
						<option value="{{$exam->examination_id}}" {{$exam->examination_id==$selected_exam?' selected':''}}>
							<?php $sub_code=TeacherController::examClassRecord($exam->examination_id);?>
							{{$sub_code[0]->sub_code}}

							@foreach($sub_code as $class)
								@if($exam->examination_id==$selected_exam)
									<?php $subject_list.=$class->sub_code."-".$class->sub_sec." ". $class->day." ".$class->time.", ";?>
									{{$class->sub_sec." - ". $class->day." ".$class->time." | "}}
								@else
									{{$class->sub_sec." - ". $class->day." ".$class->time." | "}}
								@endif
							@endforeach
							
							@if($exam->exam_type == 1)
								{{$class->type." Exam"}}

							@else
								{{$class->type." Quiz"}}
							@endif
						</option>

						

					@endforeach
				@else
					<option value="0">No examination associated to any class record.</option>
				@endif
			</select>
		</div>
 			<button class="btn btn-primary width-n" onclick="showAnalysis()">Show</button>
 		<br>
 		<br>

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
							<h3>Item Statistics</h3>
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
									<?php $discrimination_level = "";?>
									<tr>
										<td style="width:45px" >{{$key+1}}</td>
										<td class="" style="background: #ff5252; margin-bottom: 1px !important; display: block;" title="Percentage of wrong answer ">
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

												<div class="bar btn-{{$class}}" style="width:{{$percent}}%" title="Percentage of correct answer.">
												    <?php
												        $correct_answer=number_format(($difficulty/$student_count)*100,1);
												    ?>
													{{$correct_answer}}%

													<?php $discrimination=TeacherController::discrimination($question->id,$student_count);?>
													
													
													@if($discrimination<=0)
														<!--<span class="fa fa-remove disc-indicator revise" style="padding: 1px 3px 1px 3px"></span>-->
														<?php $discrimination_level = "To be revised";?>
													@else
														<?php $discrimination_level = "To be retained";?>
														<!--<span class="fa fa-check disc-indicator retain"></span>	-->
													@endif
												</div>
												<div style="float:right;color:white; margin-right: 8px">{{100-$correct_answer}}%</div>
											@else
											    &nbsp;
 												<div style="float:right;color:white; margin-right: 8px">100% <span class="fa fa-remove--" style="color:white"></div>
											@endif
										</td>
									</tr>

									<?php array_push($questionArray, array($question->id,$difficulty_level,$discrimination_level,($difficulty/$student_count)*100)) ;?>
								@endforeach
							@endif
							

							<tr>
								<td></td>
								<td style="display: flex; justify-content: space-between;">
									<div class="graph-percent">20%</div>
									<div class="graph-percent">40%</div>
									<div class="graph-percent">60%</div>
									<div class="graph-percent">80%</div>
									<div class="graph-percent">100%</div>
								</td>
							</tr>
						</table>
					</div>
					<div class="col-sm-2">
						<div class="margin-bottom" style="margin-top:30%">
							<label>Legend</label>
							<div>
								<!--<span class="btn-warning legend-color"></span> Difficult<br>
								<span class="btn-success legend-color"></span> Ideal<br>
								<span class="btn-primary legend-color"></span> Easy<br>-->

								<span class="btn-success legend-color fa fa-check-- legend-circle"></span>Correct<br>
								<span class="btn-danger legend-color fa fa-remove-- legend-circle"></span>Wrong<br>
							</div>
						</div>
					</div>
				</div>

			</div>

			<a href="{{route('statisticsGraphPrint',$selected_exam)}}" target="-" data-toggle="tooltip" title="Print Graph" class="btn btn-primary margin-top white-text margin-bottom"><span class="fa fa-print"></span>&nbsp; Print Graph</a>&nbsp;&nbsp;
			<a href="{{route('statisticsTestPaperPrint',$selected_exam)}}" target="-" data-toggle="tooltip" title="Print test paper with suggestion" class="btn btn-primary margin-top white-text margin-bottom"><span class="fa fa-print"></span>&nbsp; Print Test Paper</a>
			<?php Util::set_session('questionArray',$questionArray) ;?>
	</div>
</div>

<script>
	$(function(){
		$('[data-toggle="tooltip"]').tooltip();
	})
	function analyticsType(a) {
		var link = $(a).val();
		window.location = "/teacher/data-analytics/"+link;
	}

	function showAnalysis() {
		var type = $('select[name=type]').val();
		var class_id = $('select[name=class]').val();
		window.location = "/teacher/data-analytics/statistics/id/"+class_id;
	}
</script>