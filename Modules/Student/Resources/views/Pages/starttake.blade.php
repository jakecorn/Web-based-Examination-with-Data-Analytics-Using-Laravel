<?php
use Modules\Student\Http\Controllers\StudentController;
$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");

?>

@include("base::inc.message")
<div class="top-padding">
	<h4>Instruction</h4>
	{{$examination[0]->gen_instruction}}
	
	<h4>Duration </h4>
		<?php
 			$duration =  $examination[0]->duration;

 			if($duration!="None"){	 				
	 			 $duration=$duration/60;
	 			 $hour = floor($duration);
	 			$minute = $duration%60;

	 			if($hour<1){
	 				$minute = $duration*60;
	 			}

	 			?>
	 			{{$hour}} hour/s and {{$minute}} minute/s
	 			<?php
 			}else{
 				echo "None";
 			}




		?>

	<h4>Number of Questions</h4>
	{{StudentController::questionCount($examination[0]->id)}} Questions
 	<br>
 	<br>

	<?php $no=1;?>
	@foreach($topic as $topic)
		<div style="margin-bottom:5px"><b>PART {{$no++}}</b>
		<br>
		{{$type[$topic->exam_type]}} - {{$topic->exam_topic}}<br>

		 {{count(StudentController::getQuestion($topic->id,$examination[0]->id))}} Questions
		 </div>
	@endforeach

	<br>
	<center>
		<div class="alert alert-warning">
			<h3>You are about to take the exam. 
			@if($duration!="None")
				You should finish the exam in <b>{{date('H:i', mktime(0,$examination[0]->duration))}}:00 duration</b>. After the given time the exam will automatically be closed.
			@else
			@endif
				Click the button below to start the exam. Goodluck!
				</h3>
		</div>
		<a href="{{route('startexam',[$class_record_id,$examination[0]->id,1,1])}}" style="color:white" class="btn btn-primary">Start Exam</a>
	</center>
</div>
<br>

 <style type="text/css">
	html,body{
		background:white;
	}
</style> 