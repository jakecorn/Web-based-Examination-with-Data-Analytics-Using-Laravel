<?php
	use Modules\Utilitize\Util;
	use Modules\Teacher\Http\Controllers\TeacherController;
	$letter = range('A', 'Z');
	$sub_detail="";
?>

<!DOCTYPE html>
<html class="white-bg">
<head>
	<title>Item Analysis</title>
	<link rel="stylesheet" type="text/css" href="/css/app.css">
	<link rel="stylesheet" type="text/css" href="/css/mystyle.css">
	<script type="text/javascript" src="/js/app.js"></script>
</head>
<body class="white-bg">


	<div  style="font-size:14px">
		
	@foreach($class as $class)
		<?php
			if(in_array($class->id, $selected_class)){
				$sub_detail .= $class->sub_code." ".$class->sub_desc." - ".$class->sub_sec ." / ".$class->day." ".$class->time."  ";
			}
		?>
	@endforeach
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
						@foreach($answer as $key => $ans)
							
							@if($ans->question_answer==1)
								{{-- answer --}}
								<?php array_push($correctAnswerArray, $letter[$key]);?>
							@endif										
						@endforeach

					@elseif($exam_part[$index]->exam_type=='mat')
						@foreach(TeacherController::getChoices_mat($exam_part[$index]->id) as $key => $choices)
							
							@if($choices->question_id==$q->id)
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

	<table  class="table-analysis hidden ">
		<tr>
			<td><b>Name</b></td>
			@if(count($question)>0)
				<?php $no =1;?>
				@foreach($question as $q)
					<td>Item&nbsp;{{$no++}}</td>
				@endforeach
			@endif
		</tr>
		<?php $studentAnswerRow = array();?>
		@if(count($student1)>0)
			<?php $no =1;?>
			
			@foreach($student1 as $student)
			<?php $studentAnswer = array();?>
			<?php $studentNumber++;?>
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
										<?php array_push($studentAnswer, $letterDisplay);?>
									@endif
									
								</td>
							@else
								<td align="center">-</td>
								<?php array_push($studentAnswer, "-");?>
							@endif
						@endforeach
					@endif
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
		</tr>
		
		<tr>
			<td colspan="1000" style="font-size:5px">&nbsp;</td>
		</tr>

		<tr style="background:#dafdd6">
			<td>Correct Answer</td>
			@foreach($correctAnswerArray as $answer)
				<td align="center"><b>{{$answer}}</b></td>
			@endforeach
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
										<?php array_push($studentAnswer, $letterDisplay);?>
									@endif
									
								</td>
							@else
								<td align="center">-</td>
								<?php array_push($studentAnswer, "-");?>
							@endif
						@endforeach
					@endif
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
			@endif
		</tr>
	</table>

	{{-- //////////// --}}

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
	<br>
	<br>
		<div>
			<u><b>{{Auth::user()->name}}}, {{Auth::user()->degree}}}</b></u><br>
			Subject Instructor

		</div>
	</div>
</body>
</html>

<script type="text/javascript">
	window.print();
</script>