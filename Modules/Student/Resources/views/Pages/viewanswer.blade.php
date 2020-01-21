<?php
use Modules\Student\Http\Controllers\StudentController;
use Modules\Utilitize\Util;

$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");

?>
<div class="sheet">
  
  <div class="sheet-header" style="text-align:center">
    <h4>Negros Oriental State Unviversity</h4>
    <h5>Bayawan-Sta Catalina Campus</h5>
    <br>

    <b>{{$subject[0]->sub_code}}
    {{$subject[0]->sub_desc}}</b>
    <br>
    {{$subject[0]->day}} - {{$subject[0]->time}}

    <br>

    {{$subject[0]->type}} Examination<br>

    <span style="text-transform:capitalize">{{$subject[0]->semester}} Semester, S.Y. {{$subject[0]->sy}}<br></span>

    {{-- {{$teacher[0]->t_fname}}, {{$teacher[0]->t_fname}}<br>Subject Instructor<br> --}}


  </div>
  <?php
    if(session('done')){?>
      @include('student::exam.examexitmessage')
      <?php return false;
    }

    $rawScore=0;
    $totalSCore=0;
  ?>
  <br>
  <br>
<br>
	<div class="row">
		<div class="col-sm-12">
			<?php $total=0 ;?>
			<?php $overall=0 ;?>
			@foreach($exam_part as $part)
				<table>
					<tr>
						<td><b>Part&nbsp;{{$part->part_num}}. </b><b>{{$type[$part->exam_type]}}</b></td>
					</tr>
					<tr>
						<td>{{$part->exam_instruction}}</td>
					</tr>
					<tr>
						<td>
							<label>
								Score:
							</label>
							@if($part->exam_type=="ide")
								{{$score=StudentController::getScore_ide($examination[0]->id,$part->id)}} /		
								{{$over=StudentController::getNumberItems($examination[0]->id,$part->id)}}		
 
								<?php $total+=$score;?>
								<?php $overall+=$over;?>
 							@elseif($part->exam_type=="ess")

								{{$score=StudentController::getScore_ess($examination[0]->id,$part->id)}} / 
								{{$over=StudentController::getNumberItems_ess($examination[0]->id,$part->id)}}		
								<?php $total+=$score;?>			
								<?php $overall+=$over;?>
 							@else
								{{$score=StudentController::getScore($examination[0]->id,$part->id)}} / 
								{{$over=StudentController::getNumberItems($examination[0]->id,$part->id)}}					
								<?php $total+=$score;?>			
								<?php $overall+=$over;?>
 							@endif

 							<?php $no=1;?>
 							@foreach(StudentController::viewQuestion($examination[0]->id,$part->id) as $question)
 								<div class="margin-bottom">
 									{!!$no++.". ".$question->question!!}
 									
 									@if($part->exam_type=="mul")
	 									<ol type="A">
		 									@foreach(StudentController::viewChoices($question->id) as $choice)
		 											@if($choice->answer==1)
		 												<li><b>{{$choice->choice_desc}}</b></li>

		 											@else
		 												<li>{{$choice->choice_desc}}</li>
		 											@endif

		 											

		 									@endforeach
	 									</ol>
 									@endif

 									@if($part->exam_type=="ide")
 										<?php $answer=StudentController::getAsnwerPerQuestion_ide($question->id);?>
 										<?php $check=0;?>
 										<div class="margin-top"><b>Possible Answers: </b><div class="margin-top">
	 									<ul type="A">
		 									@foreach(StudentController::viewChoices($question->id) as $choice)

 												<li>{{$choice->choice_desc}}</li>
 												<?php
 													if(count($answer)>0){
 														if(strcasecmp($answer[0]->answer, $choice->choice_desc)==0){ 															
 															$check++;
 														}

 													}
 												?>
		 									@endforeach
	 									</ul>

	 									
											<b>Your answer:</b> 
										@if(count($answer)==0)
											No answer <a class="fa fa-close" style="color:red"></a>
										@else
											 {{$answer[0]->answer}}
											@if($check==0)
												<a class="fa fa-close" style="color:red"></a>
											@else
												<a class="fa fa-check" style="color:#06e301"></a>
											@endif
										@endif
 									@endif

 									<?php $correct_id=0;?>

 									@if($part->exam_type=="mat")
 										<div class="margin-left" >
 											<b>Answer: </b><br>

 											{{StudentController::viewChoices($question->id)[0]->choice_desc}}
 											<?php $correct_id= StudentController::viewChoices($question->id)[0]->id;?>
 											
 										</div>
 									@endif
 									
 									<div class="margin-left margin-top">
 										
										@if($part->exam_type=="mul")
											<?php $answer=StudentController::getAsnwerPerQuestion($question->id);?>
											<b>Your answer:</b> 
												@if(count($answer)==0)
													No answer <a class="fa fa-close" style="color:red"></a>
												@else

													<?php echo $answer[0]->choice_desc;?>

													@if($answer[0]->correct==0)
														<a class="fa fa-close" style="color:red"></a>
													@else
														<a class="fa fa-check" style="color:#06e301"></a>
													@endif
												@endif
											


										@endif


										@if($part->exam_type=="mat")
											<?php $answer=StudentController::getAsnwerPerQuestion($question->id);?>
											<b>Your answer:</b> 
												@if(count($answer)==0)
													No answer <a class="fa fa-close" style="color:red"></a>
												@else

													<?php echo $answer[0]->choice_desc;?>

													@if($answer[0]->correct==0 or $correct_id!=$answer[0]->answer_id )
														<a class="fa fa-close" style="color:red"></a>
													@else
														<a class="fa fa-check" style="color:#06e301"></a>
													@endif
												@endif
										@endif

										@if($part->exam_type=="tru")
	 										
	 										<?php $correct_id=StudentController::viewChoices_true($question->id)[0]; ?>
											<?php $answer=StudentController::getAsnwerPerQuestion($question->id);?>
											<div>
	 											<b>Answer: </b>
	 											<a style="text-transform:capitalize">{{$correct_id->choice_desc}}</a> <br>
	 										</div>
											<b>Your answer:</b> 
												@if(count($answer)==0)
													No answer <a class="fa fa-close" style="color:red"></a>
												@else
													<a style="text-transform:capitalize">{{$answer[0]->choice_desc}}</a>

													@if($correct_id->id!=$answer[0]->answer_id )
														<a class="fa fa-close" style="color:red"></a>
													@else
														<a class="fa fa-check" style="color:#06e301"></a>
													@endif
												@endif
											


										@endif


										@if($part->exam_type=="ess")
	 										
											<?php $answer=StudentController::getAsnwerPerQuestion_ide($question->id);?>
		
											
												@if(count($answer)==0)
													No answer <a class="fa fa-close" style="color:red"></a>
												@else
													<b>Your answer: 
														<?php
															if($answer[0]->score==""){
																echo "To be checked";
															}else{
																echo "<b>".$answer[0]->score." Points</b>" ;
															}
														?>

													</b> 
													<br>
													<?php
														if(strlen($answer[0]->answer)==0){
															?>No answer <a class="fa fa-close" style="color:red"></a>
														<?php
														}else{?>
															{{$answer[0]->answer}}
															<?php
														}

													?>
 													
												@endif
										@endif

 									</div>


 								</div>
 							@endforeach
						</td>
					</tr>
				</table>
		 	@endforeach


		</div>
	
	</div>
	
	
		<br>
		<br>

		<div class="alert alert-success" style="padding:20px">
		 		<center style="font-size:18px">
					Your score is {{$total}} / {{$overall}}			
				</center>		
		 </div>

</div> <!-- end sheet -->
                