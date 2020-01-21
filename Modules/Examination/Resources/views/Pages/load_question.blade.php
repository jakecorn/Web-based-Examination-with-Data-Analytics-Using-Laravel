
<?php

$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");
error_reporting(E_ALL ^ E_NOTICE);
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
			</div>
			<br>
			
		<div class="question-row">
			<div class="form-inline">
				<form method="get">
					<input type="hidden" name="examination_id" value="{{$examination[0]->id}}">
					<input type="hidden" name="part_id" value="{{$part[0]->id}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">

					<span>Select from which School year and term the questions will be loaded from.</span><br><br>
					<div class="form-group mr-3">
						<label>School Year: </label>
						<select class="form-control ml-3" name="sy">
							@foreach($sy as $year)
								<option {{$_REQUEST['sy'] == $year->sy ? 'selected':''}} >{{$year->sy}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group mr-3">
						<label>Semester: </label>
						<select class="form-control ml-3" name="semester">
							<option {{$_REQUEST['semester'] == "All"? 'selected':''}} >All</option>
							<option {{$_REQUEST['semester'] == "First"? 'selected':''}} >First</option>
							<option {{$_REQUEST['semester'] == "Second"? 'selected':''}} >Second</option>
						</select>		
					</div>

					<div class="form-group mr-3">
						<label>Term: </label>
						<select class="form-control ml-3" name="term">
							<option {{$_REQUEST['term'] == "All"? 'selected':''}} >All</option>
							<option {{$_REQUEST['term'] == "Midterm"? 'selected':''}} >Midterm</option>
							<option {{$_REQUEST['term'] == "Final"? 'selected':''}} value="Final">Final Term</option>
						</select>		
					</div>

					<div class="form-group" style="margin-left:15px">
						<button class="btn btn-primary" style="">Search Question</button>
					</div>
				</form>
			</div> 
		</div>
		
		<?php

		if(isset($_REQUEST['sy'])){
			?>
			<div class="padding">	
				<form method="post">
						<input type="hidden" name="examination_id" value="{{$examination[0]->id}}">
						<input type="hidden" name="part_id" value="{{$part[0]->id}}">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="hidden" class="form-check-input" name="exam_type" value="{{$questions[0]->exam_type}}">

						@foreach($questions as $question)
								<div class="form-group form-check">
								    <input type="hidden" class="form-check-input" name="question_id[]" value="{{$question->id}}">
								    <input type="checkbox" class="form-check-input" name="question[]"  id="question{{$question->id}}" value="{{$question->question}}">
								    <label class="form-check-label" for="question{{$question->id}}">{{$question->question}}</label>
								  </div>
						@endforeach

					<a href="{{route('showexam',$examination[0]->id)}}" style="color:gray;margin-left:-15px" class="btn btn-default width-n  margin-right">
						Cancel
					</a>
					<button class="btn btn-primary" style="">Save Question</button>
				</form>
			</div>
			<?php
		}
		?>
		
	</div>
</div>

 