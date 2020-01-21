
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
					<th style="padding-right:15px">General Instrunction</th>
					<td>{!!$examination[0]->gen_instruction!!}</td>
				</tr>

				<tr>						
					<th>Duration</th>
					<td>{{$examination[0]->duration}}</td>
				</tr>

				<tr>						
					<th>Classes</th>
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
					<th>Number of Questions:</th>
					<td>{{$number_question_part}}</td>
				</tr>

			</table>
			<br>
			<div class="">
				<b>{{$type[$selected_part[0]->exam_type]}}.</b>
				<b> {{$selected_part[0]->exam_topic}}. </b>
				<span>{{$selected_part[0]->exam_instruction}}</span><br>
			</div>
			<br>
			<span class="btn btn-default add-option" style=""  onclick="addQuestion_tru()">
				<a class="fa fa-plus"></a>
				 Question				
			</span>

		<br>
		<div class="container-fluid">
			<form method="post">
					<input type="hidden" name="question_id" value="{{$question[0]->id}}">					
					<input type="hidden" name="examination_id" value="{{$examination[0]->id}}">
					<input type="hidden" name="part_id" value="{{$selected_part[0]->id}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">

				<div class="question-row" name="question-row">
					<div class="form-group row">
						<label>Question</label>
						<textarea  class="form-control" name="question[]" rows="5" required="true">{{$question[0]->question}}</textarea>
						
					</div>	

					<div class="form-group row">
						<label>Choices</label><br>
						    <input type="radio" name="choose[0][]"  @if($choice[0]->answer==1) {{'checked'}} @endif    onclick="chooseAnswer_tru(this)">
						    <input type="hidden" class="answer" name="answer[0][]" @if($choice[0]->answer==1) {{'value=1'}} @else {{'value=0'}} @endif>
						    <input type="hidden" class="choices_desc" value="true" name="choices_desc[0][]">
						    <input type="hidden" class="choice_id" name="choice_id[0][]" value="{{$choice[0]->id}}">
						  	True&nbsp;&nbsp;&nbsp;

						    <input type="radio" name="choose[0][]" @if($choice[1]->answer==1) {{'checked'}} @endif   onclick="chooseAnswer_tru(this)">
						    <input type="hidden" class="answer" name="answer[0][]" @if($choice[1]->answer==1) {{'value=1'}} @else {{'value=0'}} @endif>
						    <input type="hidden" class="choices_desc" value="false" name="choices_desc[0][]">
						    <input type="hidden" class="choice_id" name="choice_id[0][]" value="{{$choice[1]->id}}">
					  		False

					</div>
				</div>
				
				<a href="{{route('preview',$examination[0]->id)}}" class="btn btn-default  margin-right" style="color:gray">
				Cancel Update
				</a>
				<button class="btn btn-primary">Update Question</button>
			</form>
		</div>
		
	</div>
</div>


<div class="duplicate hidden padding">
	<div class="form-group row">
		<label>Question</label>
		<textarea  class="form-control" name="question[]" required=""></textarea>
		
	</div>	

	<div class="form-group row">
		<label>Choices</label><br>
	    <input type="radio" checked=""   onclick="chooseAnswer_tru(this)">
	    <input type="hidden" class="answer"  value="1">
	    <input type="hidden" class="choices_desc" value="true">
	  	True&nbsp;&nbsp;&nbsp;

	    <input type="radio"  onclick="chooseAnswer_tru(this)">
	    <input type="hidden" class="answer" value="0">
	    <input type="hidden" class="choices_desc" value="false">
	  	False

	</div>
</div>