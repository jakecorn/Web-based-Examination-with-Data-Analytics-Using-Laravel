<?php use Modules\Student\Http\Controllers\StudentController;


$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");
?>
<div class="containter-fluid">
	@include('base::inc.message')
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="fa fa-question-circle"></span>Courses
 				</h4>
			</div>
		</div>

		<div class="table-responsive margin-top">
			<table width="100%" class="table table-hover">
				<thead>
					<tr>
						<th>Course Code</th>
						<th>Course Description</th>
						<th>Section</th>
						<th>Schedule</th>
						<th>Term</th>
						<th>Topics</th>
						<th>Type</th>
						<th>Duration</th>
						<th style="text-align:center"><center>Action</center></th>
					</tr>
				</thead>
				<?php $countexam=0;?>

				@if(count($examination_list)>0)
					@foreach($examination_list as $exam)
						@if($exam->visibility==0)
							<?php continue;?>
						@endif
						<?php $countexam++; ?>
						<tr>
							<td>{{$exam->sub_code}}</td>
							<td>{{$exam->sub_desc}}</td>
							<td>{{$exam->sub_sec}}</td>
							<td>{{$exam->day}} - {{$exam->time}}</td>
							<td>{{$exam->type}}</td>
							<td>
								@foreach(StudentController::examPart($exam->examination_id) as $topic)
									{{$type[$topic->exam_type]}} - 
									{{$topic->exam_topic}}<br>
								@endforeach
							</td>
							<td>{{$exam->exam_type==1? 'Long Exam':'Quiz'}}</td>
							<td>{{$exam->duration}}</td>
							<td style="width:200px;" align="center">
								@if(StudentController::doneExam($exam->examination_id))

									<a href="{{route('viewanswer',$exam->examination_id)}}" style="color:gray" {!!StudentController::answerVisibility($exam->examination_id)==0? "onclick='return answer()'":""!!} class="btn btn-default add-option" data-toggle="tooltip"  title="View answer sheet">										
											<span class="fa fa-check-circle"></span>
											Answer
									</a>

									<a href="{{route('score',$exam->examination_id)}}" style="color:gray" class="margin-right btn btn-default add-option" data-toggle="tooltip" title="View score">										
											<span class="fa fa-bar-chart"></span>
											Score
									</a>
								@else

								<a  href="{{route('start',[$exam->class_record_id,$exam->examination_id])}}" style="color:gray" class="btn btn-default add-option" data-toggle="tooltip"   title="Take Examination">										
										<span class="fa fa-file-text-o"></span>
										Take Exam
								</a>
								@endif
							</td>
						</tr>
					@endforeach
				@endif

			</table>

			@if($countexam==0)
				<center class="margin-top">
					No examination available
				</center>
			@endif
		</div>			
	</div>
</div>

<script type="text/javascript">
	function answer() {
		swal.fire(
			'Not Available',
			'Answers are not yet available',
			'warning'
		)
		return false;
	}
</script>