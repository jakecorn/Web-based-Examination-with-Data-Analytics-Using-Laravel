 <?php
use Modules\Student\Http\Controllers\StudentController;

$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");

?>
<form method="post" class="question-form">
{{csrf_field()}}
<input type="hidden" name="part_num" value="{{$exam_part[0]->part_num}}">
<input type="hidden" name="time">
 <input type="hidden" name="examination_id" value="{{$examination[0]->id}}">
 <input type="hidden" name="exam_type" value="{{$exam_part[0]->exam_type}}">
 <input type="hidden" name="class_record_id" value="{{$class_record_id}}">
     <div class="question-container">
        <?php $no=0;?>
        @foreach($question as $question)
          <table class="question margin-bottom" width="100%">
              <tr>
                <td valign="top" style="width:20px">
                  {{$question->position}}.&nbsp;
                </td>

                <td>
                   {!!$question->question!!}                                
                </td>
              </tr>

              <tr>
                <td></td>

                <td>
                  <input type="hidden" name="question[]" value="{{$question->question_id}}">
                  <input type="hidden" name="position" value="{{$question->position}}">


                  @if($exam_part[0]->exam_type=="mul")
                    <ol type="A" class="margin-top" style="padding-left:20px;margin:0;margin-top:15px">
                     <?php $none=false;?>
                     <?php $choice_id="";?>
                    @foreach(StudentController::getRandChoice($question->question_id) as $choice)
                        @if(strtolower($choice->choice_desc)!="none of the above")
                          <li>
                            <label>

                                <input type="radio" {{StudentController::getAnswer($question->question_id)==$choice->choice_id? 'checked':''}} name="choice[{{$no}}][]" value="{{$choice->choice_id}}">&nbsp;
                               {{$choice->choice_desc}}                      
                                
                            </label>
                          </li>
                        @else
                     
                          <?php $none=true;?>
                          <?php $choice_id=$choice->choice_id;?>
                        @endif
                      

                    @endforeach

                    @if($none==true)
                         <li>
                          <label>
                              <input type="radio" {{StudentController::getAnswer($question->question_id)==$choice_id? 'checked':''}} name="choice[{{$no}}][]" value="{{$choice_id}}">&nbsp;
                              None of the above
                              
                          </label>
                      </li>
                    @endif
                  </ol>

                  {{-- true or false --}}
                  @elseif($exam_part[0]->exam_type=="tru")
                    <ul type="A"  class="margin-top true-false">
                      @foreach(StudentController::getRandChoice($question->question_id) as $choice)
                        <li>
                          <label>
                              <input type="radio" {{StudentController::getAnswer($question->question_id)==$choice->choice_id? 'checked':''}} name="choice[{{$no}}][]" value="{{$choice->choice_id}}">&nbsp;
                               {{$choice->choice_desc}}
                          </label>
                        </li>
                      @endforeach
                    </ul>

                    {{-- identification --}}
                  @elseif($exam_part[0]->exam_type=="ide")
                    <label><b>Answer</b></label>
                        <input type="text" class="form-control" name="choice[{{$no}}][]" value="{{StudentController::getAnswer_long($question->question_id)}}">

                   {{-- essay --}}
                  @elseif($exam_part[0]->exam_type=="ess")
                    <label><b>Answer</b></label>
                        <textarea class="form-control" name="choice[{{$no}}][]">{{StudentController::getAnswer_long($question->question_id)}}</textarea>
                  @endif
                  
                </td>
              </tr>
                  
          </table>
          <?php $no++;?>
        @endforeach

        <center>
          <?php
            $position=$position-2;

            if($position<0){
              $part_num--;
              $position=Session::get('position')-1;
            }
          ?>

          <a class="btn btn-primary {{$part_num==0? ' hidden':''}}"  href="/student/exam/{{$class_record_id}}/{{$examination[0]->id}}/start/part/{{$part_num}}/position/{{$position}}" style="width:100px;margin-right:40px;color:white">Previous</a>
          <button class="btn btn-primary" style="width:100px">Next</button>
          <span class="answered" style="display:none"><i> All questions are answered. <a href="#" style="color:blue">Submit now</a></i></span>
        </center>
      </form>
    </div>