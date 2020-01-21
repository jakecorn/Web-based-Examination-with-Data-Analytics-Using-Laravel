
<?php
use Modules\Examination\Http\Controllers\ExaminationController;
use Modules\Teacher\Http\Controllers\TeacherController;

$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");

?>
@include('base::inc.message')
@include('base::inc.error')


<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="fa fa-question-circle"></span>Questions
				</h4>
			</div>
		</div>

		<div>
			
			<label class="margin-top">Question Type</label>
			<select name="exam_type" class="form-control" onchange="switchQuestionType(this)">
				<option value="ide" {{$part[0]->exam_type=='ide'? 'selected':''}}>Identification</option>
				<option value="ess" {{$part[0]->exam_type=='ess'? 'selected':''}}>Essay</option>
			</select>
						
			<br>
			<?php $no=1;
			$letter = range('A','Z');
			?>
			@foreach($part as $part)
				<div class="margin-top">
					<table>
						<tr>
							<td><b>PART&nbsp;{{$no++}}. </b></td>
							<td><b> {{$type[$part->exam_type]}}.</b></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<b> {{$part->exam_topic}}. </b>
								<span>{{$part->exam_instruction}}</span>
							</td>
						</tr>
					</table>
					
					@if($part->exam_type=='ide')
					<table style="margin-left:56px;margin-top:10px" class="font-size">
						
						<?php $c_question = ExaminationController::getQuestion($part->id);
							$q_num= 1;
						?>
						
						@if($c_question)
						
							@foreach($c_question as $question)
								
								<tr>
									
									<td valign="top">
										<form method="post">
										{{$q_num++}}.
										<input type="hidden" name="question_id" value="{{$question->id}}">
										{{csrf_field()}}
									</td>
									<td style="position:relative;padding-right:35px">{!!$question->question!!}

										@if($part->exam_type=="ess")
											<?php $point=ExaminationController::getPoint($question->id);?>
											<b>({{$point[0]->point}}pts)</b>
										@endif
 
										<div class="margin-left margin-top">
										
										<b>Answers</b> <br>
										<?php $index=0;?>
										@foreach(ExaminationController::getChoices($question->id) as $choice)
											<ul>											
												<li>{{$choice->choice_desc}}</li>
												
											</ul>

										@endforeach

										<b>Student Answers</b> (Choose an anwer to be considered) <br>
										<?php $index=0 ;?>
											<div class="margin-left">
												
											@foreach(TeacherController::studentAnwers($question->id) as $choice)
												<label style="display:block;font-weight:normal">
													<input type="checkbox" name="answer[]" value="{{$choice->answer}}"> {{$choice->answer}} 
												</label style="display:block;font-weight:normal">


											@endforeach
											</div>
										</div>
 
										</td>
									</tr>
									<tr>
										<td></td>
 										<td class="right-padding">
											<button class="btn btn-default margin-left margin-top margin-bottom btn-sm">Add Answer</button>
											
											</form>

										</td>
									</tr>
									

							@endforeach
						
						@endif
						
					</table>
					@endif

					{{-- essay --}}

					@if($part->exam_type=='ess')
					<table style="margin-left:56px;margin-top:10px" class="font-size">
						
						<?php $c_question = ExaminationController::getQuestion($part->id);
							$q_num= 1;
						?>
						
						@if($c_question)
						
							@foreach($c_question as $question)
								
								<tr>
									
									<td valign="top">
										{{$q_num++}}. 
										<input type="hidden" name="question_id" value="{{$question->id}}">
										{{csrf_field()}}
									</td>
									<td style="position:relative;padding-right:35px">{!!$question->question!!}

										@if($part->exam_type=="ess")
											<?php $point=ExaminationController::getPoint($question->id);?>
											<b>({{$point[0]->point}}pts)</b>
										@endif
 
										<div class="margin-left margin-top">

										<b>Student Answers</b><br>
										<?php $index=0 ;?>
											<div>
											<?php $answer_no=1;?>	
											@foreach(TeacherController::studentAnwers($question->id) as $choice)
												
												<div class="margin-bottom">
													{!!$answer_no++.". ".$choice->answer!!}<br>
													<input type="number" placeholder="Points" value="{{$choice->score}}" id="{{$choice->student_id}}" question-id="{{$question->id}}" class="form-control" style="width:100px;display:inline-block;;height:31px">
													<button class="btn btn-default margin-left  margin-right margin-top margin-bottom btn-sm" onclick="return savePoints(this)">Save Points</button>
													<img src='/images/loader.gif' class='loader'  style="width:20px;display:none">
													<small style="color:blue;display:none" class="save-message">Saved</small>
												</div>


											@endforeach
											</div>
										</div>
 
										</td>
									</tr>					

							@endforeach
						
						@endif
						
					</table>
					@endif

				</div>
			@endforeach
			<a class="btn btn-default width-n" style="color:gray" href="{{route('exam',$examination[0]->id)}}" style="width:100px">	
					 back			
			</a>
		</div>
		
	</div>
</div>

<script type="text/javascript">
	function switchQuestionType(a) {
		var b = $(a).val();

		window.location="/teacher/{{$examination[0]->id}}/check/"+b;
	}

	function savePoints(a) {
		var score = $(a).siblings('input').val();
		var previous_score = $(a).siblings('input').attr("value");
		var student_id = $(a).siblings('input').attr("id");
		var question_id = $(a).siblings('input').attr("question-id");
		var loader = $(a).siblings('img');
		var message = $(a).siblings('small');
		loader.show();
		message.hide();

		$.ajax({
			url: '/teacher/examination/check/score/save',
			type: 'POST',
			data: {'score': score,'student_id':student_id,'question_id':question_id},		
			success:function(data){
				message.show();
				$(a).siblings('input').attr("value",score);
 				loader.hide();
			},
			error:function() {
				$(a).siblings('input').attr("value",previous_score);
				loader.hide();
				message.show();
			}
		});
		
	}


$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
});
</script>