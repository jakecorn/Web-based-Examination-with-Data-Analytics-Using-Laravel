
<?php
use Modules\Teacher\Http\Controllers\TeacherController;

?>
	
<div class="containter-fluid">
	<div class="content white-bg m-padding  gray-border mv-margin">
		

		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Records
 				</h4>
			</div>
		</div>
		
		<div class="containter">
	   		<br>
	   		<table>
	   			<tr>
	   				<td width="80px"><label for="sub_code">Subject: </label></td>

	   				<td>{{$detail[0]->sub_code}} {{$detail[0]->sub_desc}} - {{$detail[0]->sub_sec}}</td>
	   			</tr>

	   			<tr>
	   				<td><label for="sub_code">Schedule: </label></td>
	   				<td>{{$detail[0]->day}} {{$detail[0]->time}}</td>
	   			</tr>

	   			<tr>
	   				<td><label for="sub_code">Formula: </label></td>
	   				<td>((Raw Score / Total Score) x {{$detail[0]->formula_times}}%) + {{$detail[0]->formula_plus}} = Final Grade</td>
	   			</tr>

	   		</table>
	   		 <br>
 	   		<div class="table-container table-responsive"> 			

	   			<center><label>Midterm  {{$detail[0]->midterm_percentage}} %</label></center>
		   		<table class="table-bordered table-hover" style="min-width:100%" >
		   			<thead>
			   			<tr>
			   				@if(count($criteria)>0)
				   				@foreach($criteria as $cri)
				   					<?php $total_score=0;?>
				   					<td align="center" class="criteria-name colored-col">
				   						<div style="white-space: nowrap;padding-left:5px;padding-right:5px;">{{$cri->criteria}} {{$cri->percent}}%
				   						
				   						</div>
				   						<table class="sub-table criteria-records">
					   						<tr>
					   							@if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
						   							@foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
						   								<td><?php echo substr($record->date,5); $total_score+=$record->total_score?></td> 
						   							@endforeach
					   							@endif
					   							<td colspan="2">Overall</td>
					   							
					   						</tr>
					   						
					   						<tr>
					   							@if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
						   							@foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
						   								<td>{{$record->total_score}}</td> 
						   							@endforeach
					   							@endif
					   							<td><b>{{$total_score}}</b></td>
					   							<td><b>%</b></td>
	 				   						</tr>
					   					</table>
				   					</td>	
				   				@endforeach
				   				<td align="center">Grade</td>
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
						   								<td class="cell-score">
						   									@if(count(TeacherController::score($record->id,$student->student_id))>0)
							   									@foreach(TeacherController::score($record->id,$student->student_id) as $score)
							   										
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
					   		
					   		<td align="center">{{$total1=number_format(($total_percent*($detail[0]->formula_times/100))+$detail[0]->formula_plus,1)}}%</td>
			   				
			   			</tr>
	   					@endforeach
	   				@endif
		   				
		   		</table>
		   		<br>



		   		<center><label>Final Term  {{$detail[0]->final_percentage}} %</label></center>

		   		<table class="table-bordered table-hover" style="min-width:100%">
		   			<thead>
			   			<tr>
			   				@if(count($criteria2)>0)
				   				@foreach($criteria2 as $cri)
				   					<?php $total_score=0;?>
				   					<td align="center" class="criteria-name colored-col">
				   						<div style="white-space: nowrap;padding-left:5px;padding-right:5px;">{{$cri->criteria}} {{$cri->percent}}%
				   						
				   						</div>
				   						<table class="sub-table criteria-records">
					   						<tr>
					   							@if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
						   							@foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
						   								<td><?php echo substr($record->date,5); $total_score+=$record->total_score?></td> 
						   							@endforeach
					   							@endif
					   							<td colspan="2">Overall</td>
					   							
					   						</tr>
					   						
					   						<tr>
					   							@if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
						   							@foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
						   								<td>{{$record->total_score}}</td> 
						   							@endforeach
					   							@endif
					   							<td><b>{{$total_score}}</b></td>
					   							<td><b>%</b></td>
	 				   						</tr>
					   					</table>
				   					</td>	
				   				@endforeach
				   				<td   align="center">Grade</td>
				   			@endif		   				
			   			</tr>
		   			</thead>
		   			
		   			<?php
		   			 	$no=1;
		   			 ?>
	   				@if(count($student2)>0)
	   					@foreach($student2 as $student)
	   					<?php $total_percent2=0;?>
	   					
	   					<tr class="stud-row">

			   				@if(count($criteria2)>0)
			   					@foreach($criteria2 as $cri)
			   							<?php $total_score=0; ?>
					   				<td class="colored-col" align="center">
					   					<table class="sub-table score">
					   						<tr>
					   						<?php $raw_score=0;?>
					   							@if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
						   							@foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
						   								<?php $total_score+=$record->total_score;?>
						   								<td class="cell-score">
						   									@if(count(TeacherController::score($record->id,$student->student_id))>0)
							   									@foreach(TeacherController::score($record->id,$student->student_id) as $score)
							   										
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
					   										$total_percent2+=($raw_score/$total_score)*($cri->percent/100)*100;
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
					   		
					   		<td align="center">{{$total2= number_format(($total_percent2*($detail[0]->formula_times/100))+$detail[0]->formula_plus,1)}}%</td>
			   				
			   			</tr>
	   					@endforeach
	   				@endif
		   				
		   		</table>
		   		<br>

		   		Computation : (Midterm Grade x {{$detail[0]->midterm_percentage}}%) + (Final Grade x {{$detail[0]->final_percentage}}%) = Semestral Grade<br>
		   		<b>Semestral Grade: </b>  {{ ($total1 *  ($detail[0]->midterm_percentage/100) ) + ($total2 * ($detail[0]->final_percentage/100)) }}%
		   		<br>
		   		<br>

		   	</div>

	  	</div>
	</div>
</div>