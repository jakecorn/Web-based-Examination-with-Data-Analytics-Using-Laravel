
<?php

$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");

?>


<div class="containter-fluid">
	@include('examination::inc.examtab')
	@include('base::inc.message')
 	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="fa fa-question-circle"></span>Questions
				<a href="{{route('updateexam',$examination[0]->id)}}" class="fa fa-gear option" title="Configure examination"></a>
				</h4>
			</div>
		</div>

			<table class="mv-margin">
				
				<tr>						
					<th style="padding-right:15px" valign="top">General Instrunction</th>
					<td>{!!$examination[0]->gen_instruction!!}</td>
				</tr>

				<tr>						
					<th>Duration</th>
					<td>{{$examination[0]->duration}}</td>
				</tr>

				<tr>						
					<th valign="top">Classes</th>
					<td>
						@foreach($class_list as $class)
							{{$class->sub_code}} - 
							{{$class->sub_sec}}
							{{$class->day}}
							{{$class->time}}
							<br>
						@endforeach
					</td>
				</tr>

				<tr>
					<th>Number of Questions</th>
					<td>{{$number_question_part}}</td>
				</tr>

			</table>
			<br>
			<div class="">
				<b>{{$type[$part[0]->exam_type]}}.</b>
				<b> {{$part[0]->exam_topic}}. </b>
				<span>{{$part[0]->exam_instruction}}</span>
				<a href="{{route('addQuestionLoad',[$examination[0]->id,$part[0]->id])}}" style="float:right;color:gray">
					<button class="btn btn-default add-option" >
						<span class="fa fa-plus"></span>
						Load Existing Question
					</button>
				</a>
			</div>
			<br>
			
		<div class="padding">
			<form method="post">
					<input type="hidden" name="examination_id" value="{{$examination[0]->id}}">
					<input type="hidden" name="part_id" value="{{$part[0]->id}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="question-row">
					<div class="form-group row">
						<label>Question</label>				
						<textarea  class="form-control" name="question[]" rows="5" required="true">{{old('question')}}</textarea>
						
					</div>	

					<div class="form-group row">
						<label>Choices</label> (Check a checkbox that serves as the answer) &nbsp;
					<span class="btn btn-default add-option btn-sm" data-toggle="tooltip" title="Add new choice"  onclick="addChoice(this)">
						<a class="fa fa-plus"></a>
						 Choice
					</span>			
						
						<table width="100%" class="choice-table">
							<tr class="choice  normal-choice">
								<td>
									<input type="RADIO" name="choose_answer_radio" onclick="chooseAnswer(this)">
									<input type="hidden" class="answer" name="answer[0][]" value="0">
								</td>
								<td>
									
									<input type="text" required="" class="form-control choices_desc" name="choices_desc[0][]" value="">
									<a class="fa fa-close criteria-remove" data-toggle="tooltip" data-placement="left" onclick="removeChoice(this)" title="Remove this choice"></a>
								</td>
							</tr>

							<tr class="choice  normal-choice">
								<td>
									<input type="RADIO" name="choose_answer_radio" onclick="chooseAnswer(this)">
									<input type="hidden" class="answer" name="answer[0][]" value="0">
								</td>
								<td>
									
									<input type="text" required="" class="form-control choices_desc" name="choices_desc[0][]" value="">
									<a class="fa fa-close criteria-remove" data-toggle="tooltip" data-placement="left" onclick="removeChoice(this)" title="Remove this choice"></a>

								</td>
							</tr>
							
							<tr class="choice  normal-choice">
								<td>
									<input type="RADIO" name="choose_answer_radio" onclick="chooseAnswer(this)">
									<input type="hidden" class="answer" name="answer[0][]" value="0">
								</td>
								<td>
									
									<input type="text" required="" class="form-control choices_desc" name="choices_desc[0][]" value="">
									<a class="fa fa-close criteria-remove" data-toggle="tooltip" data-placement="left" onclick="removeChoice(this)" title="Remove this choice"></a>

								</td>
							</tr>

							<tr class="choice">
								<td>
									<input type="RADIO" name="choose_answer_radio"  onclick="chooseAnswer(this)">
									<input type="hidden" class="answer" name="answer[0][]" value="0">
								</td>
								<td>
									<input type="text" readonly="" value="None of the above" class="form-control choices_desc" name="choices_desc[0][]">

									<a class="fa fa-close criteria-remove" data-toggle="tooltip" data-placement="left" onclick="removeChoice(this)" title="Remove this choice"></a>
								</td>
							</tr>
						</table>

		 
					</div>
				</div>
				
				<a href="{{route('showexam',$examination[0]->id)}}" style="color:gray;margin-left:-15px" class="btn btn-default width-n  margin-right">
					Cancel
				</a>
				<button class="btn btn-primary" style="">Save Question</button>
			</form>
		</div>
		
	</div>
</div>

 