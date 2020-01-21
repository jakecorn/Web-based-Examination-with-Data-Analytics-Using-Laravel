<?php
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
        <title>Laravel</title>
    </head>
    <body style="background:white">
<?php
use Modules\Teacher\Http\Controllers\TeacherController;

?>


@if(count($message)>0 and strlen($message)>0)
	<div class="alert alert-success">
		{{$message}}
	</div>
@endif

	
<div class="containter-fluid-removethis">

	
	<div class="content white-bg m-padding">

		
		<div class="containter">
	   		<br>
	   		<table class="margin-bottom">
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
	   				<td><label for="sub_code">S.Y. </label> </td>
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

	   		<div class="table-container-removethis"> 			

		   		<table class="table-bordered" >
		   			<thead>
		   				<tr>
		   					<td  align="center" colspan="{{count($criteria)+2}}"><b>Midterm {{$detail[0]->midterm_percentage}} %</b></td>
		   					<td></td>
		   					<td align="center"  colspan="{{count($criteria2)+2}}"><b>Final {{$detail[0]->final_percentage}} %</b></td>
		   				</tr>
			   			<tr>
			   				<td align="center">Student</td>
			   				@if(count($criteria)>0)
				   				@foreach($criteria as $cri)
				   					<?php $total_score=0;?>
				   					<td align="center" class="criteria-name colored-col">
				   						<div style="white-space: nowrap;padding-left:5px;padding-right:5px;">{{$cri->criteria}}
				   						</div>

				   						<table class="sub-table criteria-records">
					   						<tr>
					   							@if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
						   							@foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
						   								<?php $total_score+=$record->total_score?>
						   							@endforeach
					   							@endif
					   							<td>{{$total_score}}</td>
					   							<td><b>{{$cri->percent}}%</b></td>
					   						</tr>
					   					</table>
				   					</td>	
				   				@endforeach
				   				<td>Midterm Grade</td>
				   				<td width="40px"></td>
				   			@endif

				   			<!-- Final criteria -->
				   			@if(count($criteria2)>0)
				   				@foreach($criteria2 as $cri)
				   					<?php $total_score=0;?>
				   					<td align="center" class="criteria-name colored-col">
				   						<div style="white-space: nowrap;padding-left:5px;padding-right:5px;">{{$cri->criteria}}
				   						</div>

				   						<table class="sub-table criteria-records">
					   						<tr>
					   							@if(count(TeacherController::criteriaRecord2($cri->id,$cri->class_record_id))>0)
						   							@foreach(TeacherController::criteriaRecord2($cri->id,$cri->class_record_id) as $record)
						   								<?php $total_score+=$record->total_score?>
						   							@endforeach
					   							@endif
					   							<td>{{$total_score}}</td>
					   							<td><b>{{$cri->percent}}%</b></td>
					   						</tr>
					   					</table>
				   					</td>	
				   				@endforeach
				   				<td>Final Grade</td>
				   				<td>Semestral Grade</td>
				   			@endif			   				
			   			</tr>
		   			</thead>
		   			
		   			<?php
		   			 	$no=1;
		   			 ?>
	   				@if(count($student)>0)
	   					@foreach($student as $student)
	   					<?php $total_percent=0;?>
	   					<?php $mid_grade=0;?>
	   					<?php $final_grade=0;?>
	   					<tr class="stud-row">

			   				<td class="stud-name"><div>{{$no++}}. {{$student->stud_lname}}, {{$student->stud_fname}}</div></td>
			   				@if(count($criteria)>0)
			   					@foreach($criteria as $cri)
			   							<?php $total_score=0; ?>
					   				<td class="colored-col" align="center">
					   					<table class="sub-table score">
					   						<tr>
					   						<?php $raw_score=0;?>
					   							@if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
						   							@foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
						   								<?php $total_score+=$record->total_score;?>
						   									@if(count(TeacherController::score($record->id,$student->student_id))>0)
							   									@foreach(TeacherController::score($record->id,$student->student_id) as $score)
							   										
 							   										<?php is_numeric($score->score)? $raw_score+=$score->score : '';?>
							   									@endforeach
						   									@endif
						   								
						   							@endforeach
						   							<td><b>{{$raw_score}}</b></td> 
					   							@endif
					   							
					   							<td><b>
					   								<?php
					   									if($raw_score>0){
					   										echo number_format(($raw_score/$total_score)*($cri->percent/100)*100,1);
					   										$total_percent+=($raw_score/$total_score)*($cri->percent/100)*100;
					   									}else{
					   										echo "0.0";
					   									}
					   								?>

					   							</td></b>
				   							</tr>
					   					</table>
					   				</td>
			   					@endforeach
			   				@endif

			   				<!-- Midterm Grade -->
					   		<td align="center">
					   			<b>	
					   				<?php $mid_grade = ($total_percent*($detail[0]->formula_times/100))+$detail[0]->formula_plus;?>
					   				
					   				{{number_format($mid_grade,1)}}%
					   				
					   			</b> 			
					   		</td>
					   		<td></td>

					   		<!-- Final scores -->
					   		<?php $total_percent=0;?>
					   		@if(count($criteria2)>0)
			   					@foreach($criteria2 as $cri)
			   							<?php $total_score=0; ?>
					   				<td class="colored-col" align="center">
					   					<table class="sub-table score">
					   						<tr>
					   						<?php $raw_score=0;?>
					   							@if(count(TeacherController::criteriaRecord2($cri->id,$cri->class_record_id))>0)
						   							@foreach(TeacherController::criteriaRecord2($cri->id,$cri->class_record_id) as $record)
						   								<?php $total_score+=$record->total_score;?>
						   									@if(count(TeacherController::score($record->id,$student->student_id))>0)
							   									@foreach(TeacherController::score($record->id,$student->student_id) as $score)
							   										
 							   										<?php is_numeric($score->score)? $raw_score+=$score->score : '';?>
							   									@endforeach
						   									@endif
						   								
						   							@endforeach
						   							<td><b>{{$raw_score}}</b></td> 
					   							@endif
					   							
					   							<td><b>
					   								<?php
					   									if($raw_score>0){
					   										echo number_format(($raw_score/$total_score)*($cri->percent/100)*100,1);
					   										$total_percent+=($raw_score/$total_score)*($cri->percent/100)*100;
					   									}else{
					   										echo "0.0";
					   									}
					   								?>

					   							</td></b>
				   							</tr>
					   					</table>
					   				</td>
			   					@endforeach
			   				@endif
					   		<td align="center">
					   			<b>
					   			<?php $final_grade = ($total_percent*($detail[0]->formula_times/100))+$detail[0]->formula_plus;?>
					   				
					   				{{number_format($final_grade,1)}}%
					   			</b> 			
					   		</td>
					   		<td align="center">
					   			<b>
					   				{{-- {{number_format(($mid_grade+$final_grade)/2,1)}}% --}}
					   				{{number_format(( ($mid_grade * ($detail[0]->midterm_percentage/100)) + ($final_grade * ($detail[0]->final_percentage/100))),1)}}%
					   			</b>
					   		</td>
			   				
			   			</tr>
	   					@endforeach
	   				@endif
		   				
		   		</table>
		   		
		   	</div>

	  	</div>
	</div>

	<br>
	<br>
	<div style="margin-left:15px">
	<b style="text-transform:uppercase">{{Auth::user()->name}}, {{Auth::user()->degree}}</b><br>
	Subject Instructor</div>

</div>



<script type="text/javascript">
	window.print();
</script>