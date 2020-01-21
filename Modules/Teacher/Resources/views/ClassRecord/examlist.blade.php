
<?php
use Modules\Teacher\Http\Controllers\TeacherController;
$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");
?>


@include('base::inc.message')

	
<div class="containter-fluid">

	@include('teacher::classrecord.inc.buttons')
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		

		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Examination
				</h4>
			</div>
		</div>
		
		<div class="containter">
	   		<br>
	   		<img src='/images/loader.gif' class='loader hidden'>
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
	   				<td>Raw Score x {{$detail[0]->formula_times}}% + {{$detail[0]->formula_plus}} = Final Grade</td>
	   			</tr>
	   		</table>
	   		 <br>

	   		<div class="table-container table-responsive"> 			

		   		<table class="table  table-hover" width="100%">
		   			<thead>
			   			<tr>
			   				<th align="center">Name</th>
			   				<th align="center">Topics</th>
			   				<th align="center">Duration</th>
			   				<th align="center"><center>Visibility</center></th>
			   				<th align="center">Actions</th>
			   			</tr>
			   		</thead>


			   		@foreach($examination  as $key => $exam)
			   			<tr>
			   				<td>

			   					@if($exam->exam_type==1)
	   								{{$duration[0]->type}} Exam
	   							@else
	   								{{$duration[0]->type}} Quiz
	   							@endif
			   				</td>
			   				<td>
			   					@foreach(TeacherController::examPart($exam->id)  as $exam_part)
			   						<div>
			   							
			   							{{$exam_part->exam_topic}} 
			   						</div>
			   					@endforeach
			   				</td>
			   				<td>{{$exam->duration}}</td>
			   				<td align="center">
			   					<label style="font-weight:normal">
								  <input type="checkbox" {{$exam_detail[$key]->visibility==1?'checked=true':''}} name="visibitliy{{$exam_detail[$key]->examination_id}}" class="visibility" onchange="return visibility(this,event,{{$exam_detail[$key]->class_record_id}})"  id="{{$exam_detail[$key]->examination_id}}" data-size="mini" value="{{$exam_detail[$key]->visibility}}" data-toggle="tooltip" title="Change the visibility of the examination. Once visible, student can start taking the exam. Must set to visible during the scheudle of examination">
								</label>

								<div class="img-load" style="display:none">
									
										<img src='/images/loader.gif' class='loader ' style="width:20px">
								</div>

			   				</td>
			   				<td class="list-action">
			   					<a href="{{route('checkexam',[$exam_detail[$key]->examination_id,'ide'])}}" class="fa fa-check action  btn-success" data-toggle="tooltip" data-placement="left" title="Check the identification or essay part"></a>
								   <?php 
										   $true="";
										   $false="";
										   $paused = "";
										   $play="";


								   ?>
			   					@if($exam_detail[$key]->lock_exam==true)
			   						<?php $false="display:none";?>
			   					@else
			   						<?php $true="display:none";?>
			   					@endif

								@if($exam_detail[$key]->pause_time!="" && $exam_detail[$key]->visibility==0)
									<!-- paused -->
			   						<?php $paused="display:none";?>
			   					@else
			   						<?php $play="display:none";?>
			   					@endif
			   						<a onclick="exam_lock({{$exam_detail[$key]->examination_id}},{{$exam_detail[$key]->class_record_id}},0,this)" style="<?php echo $true;?>" class="fa fa-lock locker lockedd action  btn-danger" data-toggle="tooltip" data-placement="left" title="Examination is locked. Click to unlock the examination. This allows the student to change their answer within the given duration. Will be applied to this section only."></a>
			   						<a onclick="exam_lock({{$exam_detail[$key]->examination_id}},{{$exam_detail[$key]->class_record_id}},1,this)" style="<?php echo $false;?>" class="fa fa-unlock locker unlockedd action  btn-primary" data-toggle="tooltip" data-placement="left" title="Examination is unlocked. Click to lock the examination. This prevents the student to change their answer. This must be executed at the end of the examination."></a>
			   						<a onclick="exam_pause({{$exam_detail[$key]->examination_id}},{{$exam_detail[$key]->class_record_id}},1,this)" style="<?php echo $paused;?>" class="fa fa-play pause-play play action  btn-info" data-toggle="tooltip" data-placement="left" title="Examination time is running. Click to pause time"></a>
			   						<a onclick="exam_pause({{$exam_detail[$key]->examination_id}},{{$exam_detail[$key]->class_record_id}},0,this)" style="<?php echo $play;?>" class="fa fa-pause pause-play pause action  btn-warning" data-toggle="tooltip" data-placement="left" title="Examination is time is paused. Click to resume time"></a>
			   						<img src='/images/loader.gif' class='loader ' style="width:18px;display:none">
			   				</td>

				   		</tr>
			   			
			   		@endforeach

			   	</table>			   				
		   	</div>

	  	</div>
	</div>
</div>
