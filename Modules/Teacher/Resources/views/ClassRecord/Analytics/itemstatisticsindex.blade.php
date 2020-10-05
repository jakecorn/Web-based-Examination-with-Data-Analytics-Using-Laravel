<?php
	use Modules\Teacher\Http\Controllers\TeacherController;
?>

<div class="containter-fluid">
@include("base::inc.message")
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-line-chart"></span>Data Statistics</h4>
			</div>
		</div>

		<div class="form-group">
			<label class="margin-top">Type</label>
			<select class="form-control"  onchange="return analyticsType(this)" name="type">
				<option value="analysis">Item Analysis</option>
				<option value="statistics" selected="">Item Statistics</option>
			</select>
		</div>

		<div class="form-group">
			<label>Course</label>
			<select class="form-control"  name="class">
				@if(count($exam)>0)
					@foreach($exam as $exam)
						<option value="{{$exam->examination_id}}">
							<?php  $sub_code=TeacherController::examClassRecord($exam->examination_id);?>
								
							{{$sub_code[0]->sub_code}}

							@foreach($sub_code as $class)
								{{$class->sub_sec." - ". $class->day." ".$class->time." | "}}
							@endforeach
							
							@if($exam->exam_type == 1)
								{{$class->type." Exam"}}

							@else
								{{$class->type." Quiz"}}
							@endif
						</option>				
					@endforeach
				@else
					<option value="0">No examination associated to any class record.</option>
				@endif
			</select>
		</div>
		<div class="form-group">
			
				<button class="btn btn-primary width-n" @if(count($exam)==0) {{"disabled"}} @endif onclick="showAnalysis()">Show</button>			

			

		</div>
	</div>
</div>

<script>
	function analyticsType(a) {
		var link = $(a).val();
		window.location = "/teacher/data-analytics/"+link;
	}

	function showAnalysis() {
		var type = $('select[name=type]').val();
		var class_id = $('select[name=class]').val();
		window.location = "/teacher/data-analytics/statistics/id/"+class_id;
	}
</script>