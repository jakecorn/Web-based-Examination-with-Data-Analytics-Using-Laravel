	
<?php
use Modules\Teacher\Http\Controllers\TeacherController;
?>

<table class="table-bordered table-hover" >
    <thead>
        <tr>
            <td align="center">Student</td>
            @if(count($criteria)>0)
                @foreach($criteria as $cri)
                    <?php $total_score=0;?>
                    <td align="center" class="criteria-name colored-col">
                        <div style="white-space: nowrap;padding-left:5px;padding-right:5px;">{{$cri->criteria}} {{$cri->percent}}%
                        
                        @if($cri->criteria!=Session::get('class_record_type')." Exam")
                            <a href="{{route('addscorerecord',[$detail[0]->id,$cri->id])}}"  title="Add {{$cri->criteria}} record" class="fa fa-plus btn-default criteria-record-add"></a>
                        @endif

                        </div>
                        <table class="sub-table criteria-records">
                            <tr>
                                @if(count(TeacherController::criteriaRecord($cri->id,$cri->class_record_id))>0)
                                    @foreach(TeacherController::criteriaRecord($cri->id,$cri->class_record_id) as $record)
                                        <td style="position:relative;cursor:pointer">
                                            <div  data-toggle="tooltip" data-placement="bottom" title="Topic: {{$record->topic}}">
                                                <?php echo substr($record->date,5); $total_score+=$record->total_score?>
                                            </div>
                                        </td> 
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
                                    @if($cri->criteria!=Session::get('class_record_type')." Exam")
                                        <td><b>{{$total_score}}</b></td>
                                    @else
                                        <td onclick="total_score_edit(this)"  title="Click to edit examination total score." style="font-weight:bold; cursor:pointer" >{{TeacherController::getTotalScoreExam($detail[0]->id, false, true)}}</td>
                                    @endif
                                <td><b>%</b></td>
                            </tr>
                        </table>
                    </td>	
                @endforeach
                <td>Grade</td>
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

            <td class="stud-name"><div>{{$no++}}. {{$student->stud_lname}}, {{$student->stud_fname}}</div></td>
            @if(count($criteria)>0)
                @foreach($criteria as $cri)
                        <?php $total_score=0; ?>
                    <td class="colored-col" align="center">
                        <table class="sub-table score">
                            <tr>
                            <?php $raw_score=0;?>
                            <?php $is_long_exam="_long_exam";?>
                            <?php $criteriaRecord=TeacherController::criteriaRecord($cri->id,$cri->class_record_id); ?>
                                @if(count($criteriaRecord)>0)
                                    @foreach($criteriaRecord as $record)
                                        @if($cri->criteria!=Session::get('class_record_type')." Exam")
                                            <?php $total_score+=$record->total_score;?>
                                            <?php $is_long_exam="";?>
                                        @else
                                            <?php $total_score+=TeacherController::getTotalScoreExam($detail[0]->id, false, true);?>
                                        @endif
                                        <?php $student_score= TeacherController::score($record->id,$student->student_id);?>
                                        <?php
                                            $title = '';
                                            $bg_color = '#d0ffd0';
                                            if($student_score[0]->is_manual_score == 1){
                                                $title = 'Manually inputted score';
                                                $bg_color = '#d0f6ff';
                                            }

                                        ?>
                                        <!-- <td class="cell-score" <!-- @if($record->exam_id=="") class="cell-score" title="Double click to edit score." @else title="Double click to edit score. Automated examination. {{$title}}." style="background:{{$bg_color}}" @endif> -->
                                        <td class="cell-score{{$is_long_exam}}">

                                            
                                            @if(count($student_score)>0)
                                                @foreach($student_score as $score)
                                                    
                                                    <?php is_numeric($score->score)? $raw_score+=$score->score : '';?>

                                                    <input type="text" class="update-score" title="11" exam-id="{{$record->exam_id}}"  total-score="{{$record->total_score}}" maxlength="5" name="update" score-id="{{$score->id}}" criteria-record-id="{{$score->criteria_record_id}}" student-id="{{$student->student_id}}" value="{{$score->score}}">
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
                                            
                                                $partial_total = ($raw_score/$total_score)*($cri->percent/100)*100;
                                                $total_percent_show =  $partial_total;

                                                if($cri->percent<$partial_total){
                                                    $total_percent+=$cri->percent;
                                                    $total_percent_show = $cri->percent;
                                                }else{
                                                    $total_percent+=$partial_total;
                                                }

                                                echo number_format($total_percent_show,1);
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
            
            <td align="center">{{number_format(($total_percent*($detail[0]->formula_times/100))+$detail[0]->formula_plus,1)}}%</td>
            
        </tr>
        @endforeach
    @endif
        
    </table>