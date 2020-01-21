
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
					<td>Number of Questions:</td>
					<td>{{$number_question_part}}</td>
				</tr>

			</table>
			<br>
			<div class="">
				<b>{{$type[$part[0]->exam_type]}}.</b>
				<b> {{$part[0]->exam_topic}}. </b>
				<span>{{$part[0]->exam_instruction}}</span><br>
			</div>

		<div class="margin-top">
			<form method="post" enctype="multipart/form-data">
					<input type="hidden" name="examination_id" value="{{$examination[0]->id}}">
					<input type="hidden" name="part_id" value="{{$part[0]->id}}">
					<input type="hidden" name="exam_type" value="{{$part[0]->exam_type}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">

					<div class="form-group">
						<label>{{$type[$part[0]->exam_type]}} Template</label> <small>(Note: A selected file must be a .CSV file extension download from this system)</small>				
						<input type="file" name="file" class="form-control" required="true">
					</div>
					<div class="form-group margin-left">
						<a href="{{route('showexam',$examination[0]->id)}}" style="color:gray;margin-left:-15px" class="btn btn-default margin-left width-n  margin-right">
							Cancel
						</a>
						<button class="btn btn-primary" style="">Upload Question</button>
					</div>
				
				
			</form>
		</div>
		
	</div>
</div>

 