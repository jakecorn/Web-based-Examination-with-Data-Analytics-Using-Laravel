
<?php
use Modules\Teacher\Http\Controllers\TeacherController;

?>


@include('base::inc.message')

	
<div class="containter-fluid">

	@include('teacher::classrecord.inc.buttons')
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		

		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Class Record
				</h4>
			</div>
		</div>
		
		<div class="containter">
	   		<br>
	   		<img src='/images/loader.gif' class='loader hidden'>
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
	   		 <br>

	   		<div class="table-container table-responsive"> 			

		   		<table class="table-bordered table-hover" >
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
							   										
							   										<?php $score->score>=0 ? $raw_score+=$score->score : '';?>

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
		   		<br>
		   			<a href="{{route('classrecordprint',$class_record_id)}}" target="n">		   				
		   				<button class="btn btn-primary"><span class="fa fa-print margin-right"></span> Print Class Record</button>
		   			</a>
		   		<br>
		   		<br>
		   	</div>

	  	</div>
	</div>
</div>