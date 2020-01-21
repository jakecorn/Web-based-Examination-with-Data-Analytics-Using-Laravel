
<?php
use Modules\Examination\Http\Controllers\ExaminationController;

$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");

?>	

<link rel="stylesheet" type="text/css" href="/css/app.css">
<link rel="stylesheet" type="text/css" href="/css/mystyle.css">
<script type="text/javascript" src="/js/app.js"></script>
<title>Test Paper</title>
<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		

		<div>
			
			<table   class="mv-margin">
				
				<tr>						
					<th style="padding-right:20px">General Instruction</th>
					<td>{{$examination[0]->gen_instruction}}</td>
				</tr>

				<tr>						
					<th>Duration</th>
					<td>{{$examination[0]->duration}}</td>
				</tr>

				<tr>						
					<th>Classes</th>
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
					<th>Number of questions</th>
					<td>
						{{$number_question}}
					</td>
				</tr>


			</table>
			<br>
			<?php $no=1;
			$letter = range('A','Z');
			?>
			@foreach($part as $part)
				<div class="margin-top">
					<table>
						<tr>
							<td><b>PART&nbsp;{{$no++}}. </b></td>
							<td><b> {{$type[$part->exam_type]}}.</b></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<b> {{$part->exam_topic}}. </b>
								<span>{{$part->exam_instruction}}</span>
							</td>
						</tr>
					</table>
					

					<table style="margin-left:56px;margin-top:10px">
						
						<?php $c_question = ExaminationController::getQuestion($part->id);
							$q_num= 1;
						?>
						
						@if($c_question)
						
							@foreach($c_question as $question)
								
								<tr>
									<td valign="top">{{$q_num++}}.</td>
									<td style="position:relative;padding-right:35px">{!!$question->question!!}
									<br>
									
									@if(count($questionArray))
										@foreach ($questionArray as $key => $value)
											@if($value[0]==$question->id)	
												<div style="margin-top:8px">
													Difficulty: 
													<b>
													@if($value[1]=="Ideal")
														<span style="color:green">{{$value[1]}}</span>
													@elseif($value[1]=="Easy")
														<span style="color:blue">{{$value[1]}}</span>
													@else
														<span style="color:red">{{$value[1]}}</span>

													@endif
													</b>

													Remarks: <b>{!!$value[2]=="To be retained"? "<span style='color:green'>$value[2]</span>":"<span style='color:red'>$value[2]</span>"!!}</b>
													Correct Answer:<b> <span style="color:blue">{{number_format($value[3],1)}} %</span></b>
												</div>
											@endif
										@endforeach
									@endif

									@if($part->exam_type=="ess")
										<?php $point=ExaminationController::getPoint($question->id);?>
										<b>({{$point[0]->point}}pts)</b>
									@endif


									<!-- multiple choice -->

									@if($part->exam_type=="mul")

										<div class="margin-left margin-top">
										
										<?php $index=0;?>
										@foreach(ExaminationController::getChoices($question->id) as $choice)
											<div>
												@if($choice->answer==1)
													<b>{{$letter[$index++]}}. {{$choice->choice_desc}}</b>
												@else
													{{$letter[$index++]}}. {{$choice->choice_desc}}
												@endif
											</div>

										@endforeach
										</div>
									@endif

									<!-- matching type -->

									<div class="margin-left margin-top">
									@if($part->exam_type=="mat" or $part->exam_type=="ess" )
										<?php $index=0;?>
										@foreach(ExaminationController::getChoices($question->id) as $choice)
											<div>
												<b>Answer:</b> {{$choice->choice_desc}}
											</div>

										@endforeach
									@endif
									</div>

									<!-- matching type -->

									<div class="margin-left margin-top">
									@if($part->exam_type=="ide")
										<?php $index=0;?>
										<b>Possible Answers:</b> 
										@foreach(ExaminationController::getChoices($question->id) as $choice)
											<div>
												{{$choice->choice_desc}}
											</div>

										@endforeach
									@endif
									</div>

									<!-- true or false -->

									<div class="margin-left margin-top">
									@if($part->exam_type=="tru")
										<?php $index=0;?>
										@foreach(ExaminationController::getChoices($question->id) as $choice)
											<div>
												@if($choice->answer==1)
													<b>Answer:</b> <a style="text-transform:uppercase">{{$choice->choice_desc}}</a>
												@endif

											</div>

										@endforeach
									@endif
									</div>

									</td>
									</tr>

							@endforeach
						
						@endif
						
					</table>

				</div>
			@endforeach
		</div>
		
	</div>
</div>