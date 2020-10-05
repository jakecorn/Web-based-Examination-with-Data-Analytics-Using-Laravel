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
			    <?php
			        $sub_code = "";
			        $is_zero_based = false;
			    ?>

				@foreach($class as $class)
					<?php
					    if($class->formula_times == 100) {
					        $is_zero_based = true;
					    }
					    if($sub_code!=$class->sub_code){ ?>
				            <option value="{{$class->id}}/All">{{$class->sub_code}} {{$class->sub_desc}} - All Sections</option>
				        <?php }
				        $sub_code=$class->sub_code;
					?>

                    <option value="{{$class->id}}" {{in_array($class->id, $selected_class)? 'selected=':''}}>{{$class->sub_code}} {{$class->sub_desc}} - {{$class->sub_sec}} {{$class->day}} {{$class->time}} {{$is_zero_based}}</option>
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
					<th width="80px">Course</th>
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
			<div id="" style="margin: auto;text-align:center; max-width: 400px;margin-bottom:30px">
				<h3>Passing Percentage</h3>
				<canvas id="passing-percentage-graph" width="100px" height="100px"></canvas>
				<input type="hidden" id="is_zero_based" value="{{$is_zero_based}}">
			</div>
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
					    <?php
					        $student_took_the_exam = 0;
                            $student_score =0;
                            $studentAnswer = array();
                            $studentNumber++;
                        ?>
						<tr class="row-record">
							<td class="text-capitalize stud-name"><div><span>{{$no++}}</span>. {{$student->stud_lname.", ".$student->stud_fname}}</div></td>
							@if(count($question)>0)
								@foreach($question as $q_index => $q)
									{{-- answer --}}
									<?php $answer=TeacherController::studentAnswer($q->id,$student->student_id);?>
									
									@if(count($answer)>0)
									    <?php $student_took_the_exam = true; ?>
										<td align="center" class="letter">
											
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
										<td align="center"  class="letter">-</td>
										<?php array_push($studentAnswer, "-");?>
									@endif
								@endforeach
							@endif
							<td align="center" class="upper-score" has-took-exam="{{$student_took_the_exam}}">{{$student_score}}</td>
						</tr>
						<?php array_push($studentAnswerRow, $studentAnswer);?>
					@endforeach
				@endif
				<tr class="total  total-correct-answer-1" style="background:#e4e5fc">
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
						<td align="center" class="row-answer"><b>{{$answer}}</b></td>
					@endforeach
					<td></td>
				</tr>

				{{-- lower --}}
				<tr>
					<td colspan="1000" style="font-size:5px">&nbsp;</td>
				</tr>
				<tr class="row-record-2"></tr>
				<?php $studentAnswerRow = array();?>
				@if(count($student2)>0)
					<?php $no =1;?>

					@foreach($student2 as $student)
                        <?php
                            $studentAnswer = array();
                            $student_took_the_exam = 0;
                            $studentNumber++;
                            $student_score=0;
                            ?>
						<tr class="row-record">
							<td class="text-capitalize stud-name"><div><span>{{$no++}}</span>. {{$student->stud_lname.", ".$student->stud_fname}}</div></td>
							@if(count($question)>0)
								@foreach($question as $q)
									{{-- answer --}}
									<?php $answer=TeacherController::studentAnswer($q->id,$student->student_id);?>
									
									@if(count($answer)>0)
									    <?php $student_took_the_exam = true; ?>
										<td align="center" class="letter">
											
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
															<?php $letterDisplay="A"; ?>
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
										<td align="center"  class="letter">-</td>
										<?php array_push($studentAnswer, "-");?>
									@endif
								@endforeach
							@endif
							<td align="center" class="upper-score" has-took-exam="{{$student_took_the_exam}}">{{$student_score}}</td>
						</tr>

						<?php array_push($studentAnswerRow, $studentAnswer);?>
					@endforeach
				@endif
				<tr class="total total-correct-answer-2" style="background:#e4e5fc">
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
							    <?php
							        $discrimination_value = number_format(($topCorrectAnswerTotalArray[$key]-$BottomCorrectAnswerTotalArray[$key])/($studentNumber/2),2);
                                    if(substr($_SERVER['REQUEST_URI'], -3) == "All"){
                                        TeacherController::setDescrimination($q->id, $discrimination_value);
                                    }
							    ?>
								<b>{{$discrimination_value}}</b>
							</td>
						@endforeach
						<td></td>
					@endif
				</tr>
			</table>
			
			<a href="{{route('analysisData',[$selected_class[0],'analysis-data'])}}" target="-" title="Print Item Analysis Data" class="btn btn-primary margin-top white-text margin-bottom width-n"><span class="fa fa-print"></span>&nbsp; Print</a>

			<br>
			<br>		
			
			<div  style="overflow-y: scroll;text-align:center;margin-bottom:30px;height:300px;width:100%;position:relative;display: none">
				<div>
					<h3>Error Percentage</h3>
					<canvas id="error-percentage-graph"></canvas>
				</div>
			</div>
			<br>
			<br>
			<div style="margin: auto;text-align:center; max-width: 400px;margin-bottom:30px;">
				<h3>Index of Difficulty</h3>
				<canvas id="index-of-difficulty-graph" width="100px" height="100px"></canvas>
			</div>

			<table style="width:100%" class="analysis-result">
				<tr>
					<th>Item No.</th>
					<th>Difficulty</th>
					<th>Remarks</th>
					<th>Descrimination</th>
					<th>Remarks</th>
				</tr>

				<?php $difficult=0; $ideal=0; $easy=0?>
				@if(count($question)>0)
					<?php $no=1;?>
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
									<?php $difficult++;?>
								@elseif($difficulty<=0.8)
									<?php $ideal++;?>
									{{"IDEAL"}}
								@elseif($difficulty>0.8)
									<?php $easy++;?>
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
			
			<input type="hidden" id="difficulty_counter" value="<?=$difficult;?>"/>
			<input type="hidden" id="ideal_counter" value="<?=$ideal;?>"/>
			<input type="hidden" id="easy_counter" value="<?=$easy;?>"/>

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
	$(document).ready(function(){
		var row_element = [];
		var new_row_element = [];
		var upper_score = [];

		$('.upper-score').each(function(){
			upper_score.push(parseInt($(this).html()));
			row_element.push($(this).closest("tr").clone());
		});

		upper_score.sort(function (a,b) {
            return a-b;
        });
		upper_score.reverse();
		for (i = 0; i < upper_score.length; i++) {
			$('.upper-score').filter(function() {
				if(parseInt($(this).text()) == upper_score[i]){
					new_row_element.push($(this).closest("tr").detach());
				}
			});
		}

		var row_record;
		var is_upper = true;
		var order = 1;
		var middle_record = (parseInt(upper_score.length/2)+1);

		for (i = 0; i < new_row_element.length; i++) {
			if(middle_record == parseInt(i)){
				row_record = $('.row-record-2');
				order =1;
				is_upper = false;
			}else{
				row_record= $('.row-record:last');
			}
			row_record.after(new_row_element[i]);
			new_row_element[i].find("td div span").text(order);

			if(!is_upper){
				new_row_element[i].addClass("row-record-2");
			}
			order++;
		}
		examResultGraph()
	});
	
	$('footer').click(function(){
		$('table').click(function(){
			$(this).find('tr').each(function(){
				$(this).find('.letter:eq(13)').css("background", "red")
			});
		});
	});

	var total_correct_answer_counter_array = [];
	var total_item_with_answer = [];
	getTotalScore(true);
	getTotalScore(false);
	itemErrorPercentageGraph(total_correct_answer_counter_array, total_item_with_answer);
	
	// console.log(total_correct_answer_counter_array);
	// console.log(total_item_with_answer);

	function getTotalScore(is_upper){
		var total_correct_answer_row = $('.total-correct-answer-1');
		var answer_letter;
		var correct_answer_letter;
		var correct_answer_counter=0;
		var row_record = $('.row-record:not(.row-record-2)');

		if(!is_upper){
			total_correct_answer_row  =  $('.total-correct-answer-2');
			row_record = $('.row-record-2');
		}


		$('.row-answer').each(function(index, el){
			correct_answer_letter = $(this).text();
			var answer_count = 0;
			var has_answer=0;

			row_record.each(function(index2, el2){
				var answer_letter = $(this).children("td.letter:eq("+index+")").text();
				if(answer_letter.trim() == correct_answer_letter.trim()){
					correct_answer_counter++;
				}

				if(answer_letter.trim().toUpperCase() !=answer_letter.trim().toLowerCase()){
					has_answer++;
				}
				
			});

			total_correct_answer_row.children(".sum:eq("+index+")").text(correct_answer_counter);
			if(!is_upper){
				total_item_with_answer[index] = total_item_with_answer[index]+has_answer;
				total_correct_answer_counter_array[index] = total_correct_answer_counter_array[index]+correct_answer_counter;
			}else{
				total_item_with_answer[index] = has_answer;
				total_correct_answer_counter_array[index] = correct_answer_counter;
			}
			correct_answer_counter = 0;
		});
	}


	function examResultGraph(){
		var number_of_items = $('.row-answer').length;
		var is_zero_based = $('#is_zero_based').val();
		var pass_percentage = 0.75;

	    if(is_zero_based == '1'){
	        pass_percentage = 0.70
	    }
	    console.log(pass_percentage)
		var passing_score = number_of_items*pass_percentage;
		var passed = 0;
		var failed = 0;
		var number_of_takers = 0;
		
		$('.upper-score[has-took-exam=1]').each(function(){
			var score = parseInt($(this).text());
			if(score>0){
				if(passing_score<=score){
					passed++;
				}else{
					failed++;
				}
			}else{
			    failed++;
			}
		});

		number_of_takers = passed+failed;
		var passed_percentage = ((passed/number_of_takers)*100).toFixed(2);
		var failed_percentage = ((failed/number_of_takers)*100).toFixed(2);
		passed_percentage = parseInt(passed_percentage);
		failed_percentage = parseInt(failed_percentage);
		var ctx = document.getElementById('passing-percentage-graph');
		var myChart = new Chart(ctx, {
			type: 'pie',
			data: {
				labels: ['Passed ('+passed+') '+passed_percentage+'%' , 'Failed ('+failed+') '+failed_percentage+'%'],
				datasets: [{

					label: '# of Votes',
					data: [passed_percentage,failed_percentage],
					backgroundColor: [
					    'rgba(54, 162, 235, 0.2)',
						'rgba(255, 99, 132, 0.2)'
					],
					borderColor: [
					    'rgba(54, 162, 235, 1)',
						'rgba(255, 99, 132, 1)'
					],
					borderWidth: 1
				}]
			},
			options: { 
				gridLines : {
					display: false
				},
				label: {
					display: true
				},
				scaleLabel: {
					display: true,
					labelString: 'test'
				},
				tooltips: false,
				legend: {
					display: true,
					position: 'bottom'
				}
			}
		});
	}

	function indexOfDifficulty(){
		var difficult = parseInt($('#difficulty_counter').val());
		var ideal = parseInt($('#ideal_counter').val());
		var easy = parseInt($('#easy_counter').val());
		var total = difficult+ideal+easy;

		var difficult_percentage = parseInt((difficult/total)*100);
		var ideal_percentage = parseInt((ideal/total)*100);
		var easy_percentage = parseInt((easy/total)*100);

		var ctx = document.getElementById('index-of-difficulty-graph');
		var myChart = new Chart(ctx, {
			type: 'pie',
			data: {
				labels: ['Difficult ('+difficult+') '+difficult_percentage+'%' , 'Ideal ('+ideal+') '+ideal_percentage+'%' , 'Easy ('+easy+') '+easy_percentage+'%'],
				datasets: [{
					label: '# of Votes',
					data: [difficult_percentage,ideal_percentage,easy_percentage],
					backgroundColor: [
						'#ffe1d1',
						'#d4ffd1',
						'#d1deff'
					],
					borderWidth: 1
				}]
			},
			options: { 
				gridLines : {
					display: false
				},
				label: {
					display: true
				},
				scaleLabel: {
					display: true,
					labelString: 'test'
				},
				legend: {
					display: true,
					position: 'bottom'
				}
			}
		});
	}

	indexOfDifficulty()

	function itemErrorPercentageGraph(total_correct_answer_counter_array, total_item_with_answer){

		var labels = [];
		var data = [];
		total_correct_answer_counter_array.forEach(function(el, index){
			labels[index] = (index+1);
			var percentage = parseInt(((total_item_with_answer[index]-total_correct_answer_counter_array[index])/total_item_with_answer[index])*100)
			data[index] = percentage;

		});

		var ctx = document.getElementById('error-percentage-graph');
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: labels,
				datasets: [{
					label: 'Items',
					backgroundColor: '#3097D1',
					borderColor: '#4dace2',
					data: data,
					fill: false
				}]
			},
			options: {
				responsive: true,
    			maintainAspectRatio: false,
				ticks: {
					callback: function(value) {
						return value + '%';
					}
				},
				scales: {
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Percentage'
						}
					}]
				},
				gridLines : {
					display: false
				},
				label: {
					display: true
				},
				scaleLabel: {
					display: true,
					labelString: 'test'
				},
				legend: {
					display: true,
					position: 'bottom'
				}
			}
		});
	}

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