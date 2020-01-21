
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
			<table class="mv-margin" style="font-size:14x">
				
				<tr>						
					<th valign="top" >General Instruction</th>
					<td>{!!$examination[0]->gen_instruction!!}</td>
				</tr>

				<tr>						
					<th>Duration</th>
					<td>{{$examination[0]->duration}}</td>
				</tr>

				<tr>						
					<th valign="top">Classes</th>
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
					<th style="padding-right:20px">Number of questions</th>
					<td>
						{{$number_question}}
					</td>
				</tr>


			</table>
			<br>
			<?php $no=1;?>
			<table>
				@foreach($part as $part)
					<tr class="">
						<td><b>PART&nbsp;{{$no++}}. </b></td> 
						<td><b>{{$type[$part->exam_type]}}</b></td>
					</tr>

					<tr>
						<td></td>
						<td>
							<b> {{$part->exam_topic}}. </b>
							<span>{{$part->exam_instruction}}</span><br>
						</td>
					</tr>

					<tr>
						<td></td>
						<td style="margin-left:56px;margin-top:10px">
							<a class="margin-right" style="width:95px;display:inline-block">							
								
								{{ExaminationController::numberQuestion_part_preview($part->id)}} Questions 			
								
							</a>

							<a href="{{route('addquestion',[$examination[0]->id,$part->id])}}">							
								<button class="btn btn-default btn-sm" data-toggle="tooltip" title="Add new question">
									<i class="fa fa-plus" ></i>&nbsp;Question			
								</button>
							</a>
							
							<a href="/template/{{$part->exam_type}}.xlsx" download="{{$type[$part->exam_type]}} Template"  data-toggle="tooltip" title="Download  {{$type[$part->exam_type]}} Template" class="btn btn-default btn-sm" style="color:gray"> <span class=" fa fa-download"></span> Template</a>
							<a  href="{{route('uploadAddQuestion',[$examination[0]->id,$part->id])}}"  class="btn btn-default btn-sm" data-toggle="tooltip" title="Upload  {{$type[$part->exam_type]}} Template" style="color:gray"> <span class=" fa fa-upload"></span> Questionnaire</a>
			
							
						</td>

					</tr>
				@endforeach
			</table>
			<a href="{{route('preview',$examination[0]->id)}}" style="color:white" class="btn btn-primary btn-default margin-top">							
					<span class="fa fa-mail-forward "></span> Preview Questionnaire			
				</span>
			</a>
		</div>
		
	</div>
</div>