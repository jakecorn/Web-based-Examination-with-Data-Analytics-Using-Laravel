<?php
	use Modules\Teacher\Http\Controllers\TeacherController;
	use Modules\Utilitize\Util;
?>
<!doctype html>
<html lang="{{ app()->getLocale() }}" style="background:white">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
       	<link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
        <script type="text/javascript" src="/js/app.js"></script>
        @section('scripts')
        
		@show
        <title>Class Record Print</title>
    </head>
    <body style="background:white">
	<div class="containter-fluid">

		<div class="content white-bg m-padding  gray-border mv-margin">
 			<div class="containter">
 		   		<table>
		   			<tr>
		   				<td width="80px"><label for="sub_code">Subject </label></td>

		   				<td>: {{$detail[0]->sub_code}} {{$detail[0]->sub_desc}} - {{$detail[0]->sub_sec}}</td>
		   			</tr>

		   			<tr>
		   				<td><label for="sub_code">Schedule</label></td>
		   				<td>: {{$detail[0]->day}} {{$detail[0]->time}}</td>
		   			</tr>

		   			<tr>
		   				<td><label for="sub_code">Formula</label></td>
		   				<td>: ( (Raw Score / Total Score) x {{$detail[0]->formula_times}}% ) + {{$detail[0]->formula_plus}} = {{$detail[0]->type}} Grade</td>
		   			</tr>

		   			<tr>
		   				<td><label for="sub_code">S.Y </label> </td>
		   				<td>: {{$detail[0]->sy}}</td>
		   			</tr>
		   			<tr>
		   				<td><label for="sub_code">Semester </label> </td>
		   				<td>: {{$detail[0]->semester}}</td>
		   			</tr>
		   			<tr>
		   				<td><label for="sub_code">Term </label> </td>
		   				<td>: {{$detail[0]->type}}</td>
		   			</tr>
		   		</table>
		   		 <br>

		   		<div class="table-containersssssssss"> 			

			   		<table class="table-bordered table-hover" >
			   			<thead>
				   			<tr>
				   				<td align="center">Student</td>
				   				@if(count($criteria)>0)
					   				@foreach($criteria as $cri)
					   					<?php $total_score=0;?>
					   					<td align="center" class="criteria-name colored-col">
					   						<div style="white-space: nowrap;padding-left:5px;padding-right:5px;">{{$cri->criteria}} {{$cri->percent}}%
					   						
					   						@if($cri->criteria!=Session::get('class_record_type')." Exam")
 					   						@endif

					   						</div>
					   						<table class="sub-table criteria-records">
						   						<tr>
						   							@if($cri->criteria!=Session::get('class_record_type')." Exam")
							   							@if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
								   							@foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
								   								<td style="position:relative;cursor:pointer;display:none">
								   									<div  data-toggle="tooltip" data-placement="bottom" title="Topic: {{$record->topic}}">
								   										<?php echo substr($record->date,5); $total_score+=$record->total_score?>
								   									</div>
	 							   								</td> 
								   							@endforeach
							   							@endif
							   						@else
								   						
								   						<td><?php $total_score+=TeacherController::getTotalScoreExam($detail[0]->id);?>-</td> 

						   							@endif
						   							<td colspan="2">Overall</td>
						   							
						   						</tr>
						   						
						   						<tr>
						   							@if($cri->criteria!=Session::get('class_record_type')." Exam")
							   							@if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
								   							@foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
								   								<!-- <td style="display:none">{{$record->total_score}}</td>  -->
								   							@endforeach
							   							@endif
							   						@else
								   						
								   						<!-- <td>{{TeacherController::getTotalScoreExam($detail[0]->id)}}</td>  -->

						   							@endif
						   							<td><b>{{$total_score}}</b></td>
						   							<td><b>%</b></td>
		 				   						</tr>
						   					</table>
					   					</td>	
					   				@endforeach
					   				<td>Grade</td>
					   			@endif		   				
				   			</tr>
			   			</thead>
			   			
			   			<?php
			   			 	$no=1;
			   			 ?>
		   				@if(count($student)>0)
		   					@foreach($student as $student)
		   					<?php $total_percent=0;?>
		   					
		   					<tr class="stud-row">

				   				<td class="stud-name"><div>{{$no++}}. {{$student->stud_lname}}, {{$student->stud_fname}}</div></td>
				   				@if(count($criteria)>0)
				   					@foreach($criteria as $cri)
				   							<?php $total_score=0; ?>
						   				<td class="colored-col" align="center">
						   					<table class="sub-table score">
						   						<tr>
						   						<?php $raw_score=0;?>
						   						<?php $criteriaRecord=TeacherController::criteriaRecord($cri->id,$cri->class_record_id); ?>
						   							@if(count($criteriaRecord)>0)
							   							@foreach($criteriaRecord as $record)
							   								
							   								@if($cri->criteria!=Session::get('class_record_type')." Exam")
							   									<?php $total_score+=$record->total_score;?>
							   								@else
							   									<?php $total_score+=TeacherController::getTotalScoreExam($detail[0]->id);?>
							   								@endif
							   								<td @if($cri->criteria!=Session::get('class_record_type')." Exam") class="cell-score" @endif style="display:none">
							   									
							   									<?php $student_score= TeacherController::score($record->id,$student->student_id);?>
							   									@if(count($student_score)>0)
								   									@foreach($student_score as $score)
								   										
								   										<?php is_numeric($score->score)? $raw_score+=$score->score : '';?>

								   										<input type="text" class="update-score"  maxlength="5" name="update" score-id="{{$score->id}}" criteria-record-id="{{$score->criteria_record_id}}" student-id="{{$student->student_id}}" value="{{$score->score}}">
								   										<span>{{$score->score}}</span>
								   									@endforeach
								   								@else
								   									<input type="text" class="update-score"  maxlength="5" name="add" score-id="0" criteria-record-id="{{$record->id}}" student-id="{{$student->student_id}}">
								   									<span>-</span>
							   									@endif
							   								</td> 
							   							@endforeach
						   							@endif


						   							<td><b>{{$raw_score}}</b></td>
						   							<td>
						   								<b>
						   								<?php
						   									if($raw_score>0){
						   										echo number_format(($raw_score/$total_score)*($cri->percent/100)*100,1);
						   										$total_percent+=($raw_score/$total_score)*($cri->percent/100)*100;
						   									}else{
						   										echo "0";
						   									}
						   								?>
						   								</b>
						   							</td>
					   							</tr>
						   					</table>
						   				</td>
				   					@endforeach
				   				@endif
						   		
						   		<td align="center">{{number_format(($total_percent*($detail[0]->formula_times/100))+$detail[0]->formula_plus,1)}}%</td>
				   				
				   			</tr>
		   					@endforeach
		   				@endif
			   				
			   		</table>
		   			<br>
					<br>
					<div style="margin-left:15px">
					<b style="text-transform:uppercase">{{Auth::user()->name}}, {{Auth::user()->degree}}</b><br>
					Subject Instructor</div>	
							   	</div>

		  	</div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	window.print();
</script>