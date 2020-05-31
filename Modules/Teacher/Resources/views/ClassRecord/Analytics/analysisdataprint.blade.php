<?php
	use Modules\Utilitize\Util;
	use Modules\Teacher\Http\Controllers\TeacherController;
	$letter = range('A', 'Z');
	$sub_detail ="";
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
                        <b>{{number_format(($topCorrectAnswerTotalArray[$key]-$BottomCorrectAnswerTotalArray[$key])/($studentNumber/2),2)}}</b>
                    </td>
                @endforeach
                <td></td>
            @endif
        </tr>
    </table>
	<br>
	<br>
		<div>
			<u><b>{{Auth::user()->name}}, {{Auth::user()->degree}}</b></u><br>
			Subject Instructor

		</div>
	</div>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
		var row_element = [];
		var new_row_element = [];
		var upper_score = [];

		$('.upper-score').each(function(){
			upper_score.push($(this).html());
			row_element.push($(this).closest("tr").clone());
		});

		upper_score.sort();
		upper_score.reverse();
		for (i = 0; i < upper_score.length; i++) {
			$('.upper-score').filter(function() {
				if($(this).html() == upper_score[i]){
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
	});
	window.print();
</script>