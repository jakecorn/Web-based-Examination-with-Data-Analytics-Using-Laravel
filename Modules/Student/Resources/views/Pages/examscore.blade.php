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
    

    <?php
    	if($examination[0]->exam_type == 0){
    		echo "Quiz";
    	}else{
    		echo $subject[0]->type." Examination";		
    	}
    ?>
    <br>

    <span style="text-transform:capitalize">{{$subject[0]->semester}} Semester, S.Y. {{$subject[0]->sy}}<br></span>

    {{$teacher[0]->name}}<br>Subject Instructor<br>


  </div>
  <?php
    if(session('done')){?>
      @include('student::exam.examexitmessage')
      <?php return false;
    }
  ?>
  <br>
  <br>
<br>
	<div class="row">
		<div class="col-sm-4">
			<?php $total=0 ;?>
			<?php $overall=0 ;?>
			@foreach($exam_part as $part)
				<table>
					<tr>
						<td><b>Part&nbsp;{{$part->part_num}}.</b></td>
						<td><b>{{$type[$part->exam_type]}}</b></td>
					</tr>
					<tr>
						<td></td>
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
								
								@if(Session::get("to_be_checked")==true)
									To be checked 
								@else
									<?php $total+=$score;?>											
								@endif

								<?php $overall+=$over;?>
 							@else
								{{$score=StudentController::getScore($examination[0]->id,$part->id)}} / 
								{{$over=StudentController::getNumberItems($examination[0]->id,$part->id)}}					
								<?php $total+=$score;?>			
								<?php $overall+=$over;?>
 							@endif
						</td>
					</tr>
				</table>
		 	@endforeach
		</div>
<!-- 		@if(count($errors))
			<div class="col-sm-8">
				<div style="margin-bottom:15px;margin-top:15px;color:red">
					<?php $p_error="";?>
					@foreach($errors->all() as $error)
						@if($error!=$p_error)
								{{$error}}
								<?php $p_error=$error;?>
						@endif
					@endforeach
				</div>
			</div>
		@endif -->
		<div class="col-sm-8">
			<div class="alert alert-success">
				<h3>Congratulations!!! 
				Your
				<?php
					if(Session::get('initial_score')){
						echo "initial";
					}
				?>
				score is {{$total}} / {{$overall}}
				</h3>

			</div>

			<center>
				<a href="{{route('subjectlist')}}"  style="color:white" class="btn btn-primary width-n" >Home Page</a>				
			</center>
		</div>
		
	</div>
	
	




</div> <!-- end sheet -->
                