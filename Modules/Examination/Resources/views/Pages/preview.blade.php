
<?php
use Modules\Examination\Http\Controllers\ExaminationController;

$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");

?>
@include('examination::inc.examtab')
	@include('base::inc.message')


<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="fa fa-question-circle"></span>Questions
				<a href="{{route('updateexam',$examination[0]->id)}}" class="fa fa-gear option" title="Configure examination"></a>
				</h4>
			</div>
		</div>

		<div>
			<table   class="mv-margin">
				
				<tr>						
					<th style="padding-right:20px">General Instruction</th>
					<td>{!!$examination[0]->gen_instruction!!}</td>
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
					<table style="max-width:100%">
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
					

					<table   style="margin-left:56px;margin-top:10px;min-width:90%">
						
						<?php $c_question = ExaminationController::getQuestion($part->id);
							$q_num= 1;
						?>
						
						@if(count($c_question)>0)
						
							@foreach($c_question as $question)
								
								<tr>
									<td valign="top" width="20px" >{{$q_num++}}.</td>
									<td style="position:relative;padding-right:35px">{!!$question->question!!}

									@if($part->exam_type=="ess")
										<?php $point=ExaminationController::getPoint($question->id);?>
										@if(count($point)>0)
											<b>({{$point[0]->point}}pts)</b>
										@endif
									@endif

									&nbsp;<a href="{{route('editquestion',[$examination[0]->id,$part->id,$question->id])}}"  data-toggle="tooltip" data-placement="left" class="fa fa-pencil edit action preview-action" style=" color:gray" title="Edit Question"></a>
									&nbsp;<a data-href="{{route('deletequestion',[$examination[0]->id,$part->id,$question->id])}}"  data-toggle="modal" data-target="#deleteModal" class="fa fa-remove action preview-action" style=" color:gray" title="Delete Question"></a>

									<!-- multiple choice -->

									@if($part->exam_type=="mul")

										<div class="margin-left" style="margin-top:5px">
										
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

									@if($part->exam_type=="mat")
										<div class="margin-left mat-margin-top">
											<?php $index=0;?>
											@foreach(ExaminationController::getChoices($question->id) as $choice)
												@if($loop->first)
													<div>
														<b>Answer:</b> {{$choice->choice_desc}}
													</div>
												@endif

												@if(strlen($choice->choice_desc)>0 and $choice->answer==0 )

													<div>
														<b>Destructor:</b> {{$choice->choice_desc}}
													</div>
												@endif
												

											@endforeach
										</div>
									@endif

									<!-- matching type -->

									@if($part->exam_type=="ide")
										<div class="margin-left" style="margin-top:5px">
											<?php $index=0;?>
											<b>Possible Answers:</b> 
											@foreach(ExaminationController::getChoices($question->id) as $choice)
												<div>
													{{$choice->choice_desc}}
												</div>

											@endforeach
										</div>
									@endif

									<!-- true or false -->

									@if($part->exam_type=="tru")
										<div class="margin-left" style="margin-top:5px">
											<?php $index=0;?>
											@foreach(ExaminationController::getChoices($question->id) as $choice)
												<div>
													@if($choice->answer==1)
														<b>Answer:</b> <a style="text-transform:uppercase">{{$choice->choice_desc}}</a>
													@endif

												</div>

											@endforeach
										</div>
									@endif
									<br>
									</td>
									</tr>

							@endforeach
						
						@endif
						
					</table>

				</div>
			@endforeach
			<a class="btn btn-primary" href="{{route('showexam',$examination[0]->id)}}">	
					 Back			
			</a>
		</div>
		
	</div>
</div>
