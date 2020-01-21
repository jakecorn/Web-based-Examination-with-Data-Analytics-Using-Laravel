 <?php
use Modules\Student\Http\Controllers\StudentController;

$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");

?>
<form method="post">
{{csrf_field()}}
<input type="hidden" name="part_num" placeholder="here" value="{{$exam_part[0]->part_num}}">
<input type="hidden" name="time">
 <input type="hidden" name="examination_id" value="{{$examination[0]->id}}">
 <input type="hidden" name="exam_type" value="mat">
  <input type="hidden" name="class_record_id" value="{{$class_record_id}}">

     <div class="question-container">
     	<div class="row">     	
     		
	     	<div class="col-sm-8">
	     			<b>COLUMN A</b>
	     		  <?php $no=0;?>
	     		  <?php $q_index=0;?>
		        @foreach($question as $question)
		          <table class="question margin-bottom">
		              <tr>
		                <td valign="top">
		                  {{$question->position}}.&nbsp;
		                </td>

		                <td>
		                   {!!$question->question!!}
		                   <input type="hidden" name="question[]" value="{{$question->question_id}}">
		                   <input type="hidden" class="choice" name="choice[{{$q_index}}][]" value="{{StudentController::getAnswer($question->question_id)}}">
		                   <input type="hidden" name="position" value="{{$question->position}}">
 		                   <?php $letter = range('A','Z'); $index=0;?>
		                   <ul class="margin-top mat-choice-select" style="list-style:none;padding-left:20px;margin:0">
		                    @foreach(StudentController::getRandChoice_mat($examination[0]->id,$exam_part[0]->id) as $choice)
		                      <li>
		                        <label>
		                            <input type="radio"  {{StudentController::getAnswer($question->question_id)==$choice->choice_id? 'checked':''}}  onclick="chooseAnswer(this)" {{StudentController::getAnswer($question->question_id)==$choice->choice_id? 'checked':''}} name="choice_ss[{{$q_index}}][]"  value="{{$choice->choice_id}}">&nbsp;
  		                        	{{$letter[$index++]}}
  		                        </label>
		                      </li>
		                    @endforeach
		                </ul>                               
		                </td>
		              </tr>                  
		          </table>
		          <?php $no++;?>
		          <?php $q_index++;?>
		        @endforeach
	     	</div>

	     	<div class="col-sm-4">
     			<b>COLUMN B</b>


     			<ol type="A" class="margin-top" style="margin:0">
                    @foreach(StudentController::getRandChoice_mat($examination[0]->id,$exam_part[0]->id) as $choice)
                      <li>
                        <label>
                              {{$choice->choice_desc}}
                        </label>
                      </li>
                    @endforeach
                </ol>
     		</div>

     	</div>    	
     

     	<div class="row">
	        <center>
	          <?php
	            $position=$position-2;

	            if($position<0){
	              $part_num--;
	              $position=Session::get('position')-1;
	            }
	          ?>

	           <a class="btn btn-primary {{$part_num==0? ' hidden':''}}"  href="/student/exam/{{$class_record_id}}/{{$examination[0]->id}}/start/part/{{$part_num}}/position/{{$position}}" style="width:100px;margin-right:40px;color:white">Previous</a>

         	 <button class="btn btn-primary" style="width:100px">Next / Save</button>
	        </center>
        </div>

      </form>
    </div>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript">
 
    	function chooseAnswer(a){
    		$(a).parents('td').children('.choice').val($(a).val());
    	}
    </script>