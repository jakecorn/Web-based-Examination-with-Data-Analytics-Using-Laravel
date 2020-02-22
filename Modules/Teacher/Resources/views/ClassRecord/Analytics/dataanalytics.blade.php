<?php
	use Modules\Utilitize\Util;
	use Modules\Teacher\Http\Controllers\TeacherController;
	$letter = range('A', 'Z');
	$sub_detail ="";
?>
<div class="containter-fluid">
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-line-chart"></span>Item Analysis</h4>
			</div>
		</div>

		<div class="form-group">
			<label class="margin-top">Type</label>
			<select class="form-control" onchange="return analyticsType(this)" name="type">
				<option value="analysis">Item Analysis</option>
				<option value="statistics">Item Statistics</option>
			</select>
		</div>

		<div class="form-group">
			<label>Class</label>
			<select class="form-control" name="class">
				@foreach($class as $class)
					<option value="{{$class->id}}" {{in_array($class->id, $selected_class)? 'selected=':''}}>{{$class->sub_code}} {{$class->sub_desc}} - {{$class->sub_sec}} {{$class->day}} {{$class->time}}</option>
					<option value="{{$class->id}}/All">{{$class->sub_code}} {{$class->sub_desc}} - All Sections</option>					

					<?php
						if(in_array($class->id, $selected_class)){
							$sub_detail .= $class->sub_code." ".$class->sub_desc." - ".$class->sub_sec ." / ".$class->day." ".$class->time."  ";
						}
					?>
				@endforeach
			</select>
		</div>
 		<div class="form-group">
			<button class="btn btn-primary width-n" onclick="showAnalysis()">Show</button>
		</div>
		<br>
		<div class="table-responsive">
			<table class="margin-bottom">
				<tr>
					<th width="80px">Subject</th>
					<td>: {{$sub_detail}}</td>
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

			{{-- correct answeer array --}}
			<?php $correctAnswerArray = array();?>
			<?php $studentNumber = 0;?>
			@if(count($question)>0)
				@foreach($question as $q)
					{{-- answer --}}
					<?php $answer=TeacherController::correcttAnswer_mul($q->id);?>
					
					@if(count($answer)>0)							
							{{-- get exam type using id --}}
							<?php $index=0;?>
							@foreach($exam_part as $key =>$part)
								@if($part->id==$q->exam_part_id)
									<?php $index=$key;?>
								@endif
							@endforeach

							@if($exam_part[$index]->exam_type=='mul')
								<?php $answer_added = false; ?>
								@foreach($answer as $key => $ans)
									
									@if($ans->question_answer==1 && $answer_added== false)
										<?php $answer_added = true; ?>
										{{-- answer --}}
										<?php array_push($correctAnswerArray, $letter[$key]);?>
									@endif										
								@endforeach

							@elseif($exam_part[$index]->exam_type=='mat')
								@foreach(TeacherController::getChoices_mat($exam_part[$index]->id) as $key => $choices)
									<?php $answer_added = false; ?>
									
									@if($choices->question_id==$q->id && $choices->answer==1 && $answer_added== false)
										<?php $answer_added = false; ?>
										{{-- answer --}}

										<?php array_push($correctAnswerArray, $letter[$key]);?>											
									@endif
								@endforeach
							@else
								<?php $truefalse = array("T","F");?>
								@foreach($answer as $key => $choices)											
									@if($choices->question_answer==1)
										{{-- answer --}}
										<?php array_push($correctAnswerArray, $truefalse[$key]);?>
									@endif
								@endforeach
							@endif
							
					@else
						<?php array_push($correctAnswerArray,"-");?>
					@endif
				@endforeach
			@endif

			<table  class="table-analysis ">
				<tr>
					<td><b>Name</b></td>
					@if(count($question)>0)
						<?php $no =1;?>
						@foreach($question as $q)
							<td>Item&nbsp;{{$no++}}</td>
						@endforeach
					@endif
					<td>Score</td>
				</tr>
				<tr  class="row-record"></tr>
				<?php $studentAnswerRow = array();?>
				@if(count($student1)>0)
					<?php $no =1;?>

					@foreach($student1 as $student)
					<?php $student_score =0;?>
					<?php $studentAnswer = array();?>
					<?php $studentNumber++;?>
						<tr>
							<td class="text-capitalize stud-name"><div>{{$no++.". ".$student->stud_lname.", ".$student->stud_fname}}</div></td>
							@if(count($question)>0)
								@foreach($question as $q_index => $q)
									{{-- answer --}}
									<?php $answer=TeacherController::studentAnswer($q->id,$student->student_id);?>
									
									@if(count($answer)>0)
										<td align="center">
											
											{{-- get exam type using id --}}
											<?php $index=0;?>
											@foreach($exam_part as $key =>$part)
												@if($part->id==$q->exam_part_id)
													<?php $index=$key;?>
												@endif
											@endforeach

											@if($exam_part[$index]->exam_type=='mul')
												<?php $letterDisplay=""; ?>
												@foreach($answer as $key => $ans)
													
													@if($ans->student_answer==$ans->choice_id)
														{{-- answer --}}
														<?php $letterDisplay=$letter[$key]; ?>
													@else
														@if($ans->student_answer=="")
															<?php $letterDisplay="-"; ?>
														@endif
													@endif
												@endforeach
												{{$letterDisplay}}-
												
												@if($correctAnswerArray[$q_index] == $letterDisplay)
													<?php $student_score++; ?>
												@endif
												<?php array_push($studentAnswer,$letterDisplay);?>

											@elseif($exam_part[$index]->exam_type=='mat')
												<?php $answer=TeacherController::studentAnswer_mat($q->id,$student->student_id);?>
												
												<?php $letterDisplay="-"; ?>
												@foreach(TeacherController::getChoices_mat($exam_part[$index]->id) as $key => $choices)
													@if(count($answer)>0)
														@if($choices->choice_id==$answer[0]->student_answer)
															{{-- answer --}}
															<?php $letterDisplay=$letter[$key]; ?>
														@endif
													@else
														@if($ans->student_answer=="")
															<?php $letterDisplay="-"; ?>
														@endif
													@endif
												@endforeach
												{{$letterDisplay}}
												@if($correctAnswerArray[$q_index] == $letterDisplay)
													<?php $student_score++; ?>
												@endif
												<?php array_push($studentAnswer, $letterDisplay);?>
												
											@else
												<?php $answer=TeacherController::studentAnswer($q->id,$student->student_id);?>
												<?php $truefalse = array("T","F");?>
												<?php $letterDisplay="-"; ?>
												@foreach($answer as $key => $choices)
													
													@if($choices->student_answer==$choices->choice_id)
														{{-- answer --}}
														<?php $letterDisplay=$truefalse[$key]; ?>
													@else
														@if($ans->student_answer=="")
															<?php $letterDisplay="-"; ?>
														@endif
													@endif
												@endforeach
												{{$letterDisplay}}
												@if($correctAnswerArray[$q_index] == $letterDisplay)
													<?php $student_score++; ?>
												@endif
												<?php array_push($studentAnswer, $letterDisplay);?>
											@endif
											
										</td>
									@else
										<td align="center">-</td>
										<?php array_push($studentAnswer, "-");?>
									@endif
								@endforeach
							@endif
							<td align="center" class="upper-score">{{$student_score}}</td>
						</tr>
						<?php array_push($studentAnswerRow, $studentAnswer);?>
					@endforeach
				@endif
				<tr class="total" style="background:#e4e5fc">
					<td>Total Correct Answer</td>
					@if(count($question)>0)
						<?php $topCorrectAnswerTotalArray = array();?>
						@foreach($question as $answerIndex => $q)
							<?php $total=0 ;?>
							<td class="sum" align="center">
								<?php $student_answerlist = "";?>
								@foreach($studentAnswerRow as $key => $studentAnswer)
										@if($correctAnswerArray[$answerIndex]==$studentAnswer[$answerIndex])
											<?php $total++;?>
										@endif
								@endforeach
								{{$total}}
								<?php array_push($topCorrectAnswerTotalArray, $total);?>										
							</td>
						@endforeach
					@endif
					<td></td>
				</tr>
				
				<tr>
					<td colspan="1000" style="font-size:5px">&nbsp;</td>
				</tr>

				<tr style="background:#dafdd6">
					<td>Correct Answer</td>
					@foreach($correctAnswerArray as $answer)
						<td align="center"><b>{{$answer}}</b></td>
					@endforeach
					<td></td>
				</tr>

				{{-- lower --}}
				<tr>
					<td colspan="1000" style="font-size:5px">&nbsp;</td>
				</tr>

				<?php $studentAnswerRow = array();?>
				@if(count($student2)>0)
					<?php $no =1;?>

					@foreach($student2 as $student)
					<?php $studentAnswer = array();?>
					<?php $studentNumber++;?>
					<?php $student_score=0; ?>
						<tr>
							<td class="text-capitalize stud-name"><div>{{$no++.". ".$student->stud_lname.", ".$student->stud_fname}}</div></td>
							@if(count($question)>0)
								@foreach($question as $q)
									{{-- answer --}}
									<?php $answer=TeacherController::studentAnswer($q->id,$student->student_id);?>
									
									@if(count($answer)>0)
										<td align="center">
											
											{{-- get exam type using id --}}
											<?php $index=0;?>
											@foreach($exam_part as $key =>$part)
												@if($part->id==$q->exam_part_id)
													<?php $index=$key;?>
												@endif
											@endforeach

											@if($exam_part[$index]->exam_type=='mul')
												<?php $letterDisplay=""; ?>
												@foreach($answer as $key => $ans)
													
													@if($ans->student_answer==$ans->choice_id)
														{{-- answer --}}
														<?php $letterDisplay=$letter[$key]; ?>
													@else
														@if($ans->student_answer=="")
															<?php $letterDisplay="-"; ?>
														@endif
													@endif
												@endforeach
												{{$letterDisplay}}
												@if($correctAnswerArray[$q_index] == $letterDisplay)
													<?php $student_score++; ?>
												@endif
												<?php array_push($studentAnswer,$letterDisplay);?>

											@elseif($exam_part[$index]->exam_type=='mat')
												<?php $answer=TeacherController::studentAnswer_mat($q->id,$student->student_id);?>
												
												<?php $letterDisplay="-"; ?>
												@foreach(TeacherController::getChoices_mat($exam_part[$index]->id) as $key => $choices)
													@if(count($answer)>0)
														@if($choices->choice_id==$answer[0]->student_answer)
															{{-- answer --}}
															<?php $letterDisplay=$letter[$key]; ?>
														@endif
													@else
														@if($ans->student_answer=="")
															<?php $letterDisplay="-"; ?>
														@endif
													@endif
												@endforeach
												{{$letterDisplay}}
												@if($correctAnswerArray[$q_index] == $letterDisplay)
													<?php $student_score++; ?>
												@endif
												<?php array_push($studentAnswer, $letterDisplay);?>
												
											@else
												<?php $answer=TeacherController::studentAnswer($q->id,$student->student_id);?>
												<?php $truefalse = array("T","F");?>
												<?php $letterDisplay="-"; ?>
												@foreach($answer as $key => $choices)
													
													@if($choices->student_answer==$choices->choice_id)
														{{-- answer --}}
														<?php $letterDisplay=$truefalse[$key]; ?>
													@else
														@if($ans->student_answer=="")
															<?php $letterDisplay="-"; ?>
														@endif
													@endif
												@endforeach
												{{$letterDisplay}}
												@if($correctAnswerArray[$q_index] == $letterDisplay)
													<?php $student_score++; ?>
												@endif
												<?php array_push($studentAnswer, $letterDisplay);?>
											@endif
											
										</td>
									@else
										<td align="center">-</td>
										<?php array_push($studentAnswer, "-");?>
									@endif
								@endforeach
							@endif
							<td align="center" class="upper-score">{{$student_score}}</td>
						</tr>

						<?php array_push($studentAnswerRow, $studentAnswer);?>
					@endforeach
				@endif
				<tr class="total" style="background:#e4e5fc">
					<td>Total Correct Answer</td>
					<?php $BottomCorrectAnswerTotalArray = array();?>
					@if(count($question)>0)
						@foreach($question as $answerIndex => $q)
							<?php $total=0 ;?>
							<td class="sum" align="center">
								<?php $student_answerlist = "";?>
								@foreach($studentAnswerRow as $key => $studentAnswer)
										@if($correctAnswerArray[$answerIndex]==$studentAnswer[$answerIndex])
											<?php $total++;?>
										@endif
								@endforeach
								{{$total}}
								<?php array_push($BottomCorrectAnswerTotalArray, $total);?>
							</td>
						@endforeach
						<td></td>
					@endif
				</tr>

				<tr class="difficulty" style="background:#fcdbdb">
					<td>Index of Difficulty</td>
					@if(count($question)>0)
						@foreach($question as $key => $q)
							<td class="index" align="center">
								<b>{{number_format(($topCorrectAnswerTotalArray[$key]+$BottomCorrectAnswerTotalArray[$key])/$studentNumber,2)}}</b>
							</td>
						@endforeach
						<td></td>
					@endif
				</tr>
				<tr style="background:#fcf3db">
					<td>Index of Discrimination</td>
					@if(count($question)>0)
						@foreach($question as $key => $q)
							<td align="center">
								<b>{{number_format(($topCorrectAnswerTotalArray[$key]-$BottomCorrectAnswerTotalArray[$key])/($studentNumber/2),2)}}</b>
							</td>
						@endforeach
						<td></td>
					@endif
				</tr>
			</table>
			
			<a href="{{route('analysisData',[$selected_class[0],'analysis-data'])}}" target="-" title="Print Item Analysis Data" class="btn btn-primary margin-top white-text margin-bottom width-n"><span class="fa fa-print"></span>&nbsp; Print</a>

			<br>
			<br>		

			<table style="width:700px" class="analysis-result">
				<tr>
					<th>Item No.</th>
					<th>Difficulty</th>
					<th>Remarks</th>
					<th>Descrimination</th>
					<th>Remarks</th>
				</tr>

				@if(count($question)>0)
					<?php $no=1;?>
					@foreach($question as $key => $q)
						<?php $difficulty=number_format(($topCorrectAnswerTotalArray[$key]+$BottomCorrectAnswerTotalArray[$key])/$studentNumber,2);?>
						<tr	@if($difficulty<0.2) {{'class=difficult'}} @elseif($difficulty<=0.8) {{'class=ideal'}} @elseif($difficulty>0.8) {{'class=easy'}} @endif >
							<td align="center">
								{{$no++}}
							</td>
							
							<td align="center">
								{{number_format(($topCorrectAnswerTotalArray[$key]+$BottomCorrectAnswerTotalArray[$key])/$studentNumber,2)}}						
							</td>
							<td align="center">
								@if($difficulty<0.2)
									{{"DIFFICULT"}}
								@elseif($difficulty<=0.8)
									{{"IDEAL"}}
								@elseif($difficulty>0.8)
									{{"EASY"}}
								@endif
							</td>

							<td align="center">
								{{number_format(($topCorrectAnswerTotalArray[$key]-$BottomCorrectAnswerTotalArray[$key])/($studentNumber/2),2)}}
								
							</td>

							<td align="center">

								@if(number_format(($topCorrectAnswerTotalArray[$key]-$BottomCorrectAnswerTotalArray[$key])/($studentNumber/2),2)<=0)
									{{"To be revised"}}
								@else
									{{"To be retained"}}
								@endif
							</td>
						</tr>
					@endforeach
					@endif
			</table>

			<div class="margin-top margin-bottom">
				<label>Legend</label>
				<div class="margin-left">
					<span class="difficult legend-color"></span> DIFFICULT<br>
					<span class="ideal legend-color"></span> IDEAL<br>
					<span class="easy legend-color"></span> EASY<br>
				</div>
			</div>

			<a href="{{route('analysisData',[$selected_class[0],'analysis-result'])}}" target="-" title="Print Item Analysis Result" class="btn btn-primary margin-top white-text margin-bottom width-n"><span class="fa fa-print"></span>&nbsp; Print</a>
			
		</div>

		

		
	</div>
	<div class="jake"></div>
</div>

<script type="text/javascript">
	var row_element = [];
	var new_row_element = [];
	var upper_score = [];
	
	$('.upper-score').each(function(){
		upper_score.push($(this).html());
		row_element.push($(this).closest("tr").clone());
	});
	
	upper_score.sort();
	upper_score.reverse();
	console.log(upper_score);
	alert(123)
	for (i = 0; i < upper_score.length; i++) {
		// $('.upper-score').filter(function() {
		// 	if($(this).html() == [i]){
		// 		new_row_element.push($(this).closest("tr").detach());
		// 	} 
		// });
		console.log(1)
	}
	
	$('.row-record').after(new_row_element);
	// console.log(upper_score);
	// console.log(parseInt(upper_score.length));

	function analyticsType(a) {
		var link = $(a).val();
		window.location = "/teacher/data-analytics/"+link;
	}

	function showAnalysis() {
		var type = $('select[name=type]').val();
		var class_id = $('select[name=class]').val();
		window.location = "/teacher/data-analytics/"+type+"/class/"+class_id;
	}
</script>