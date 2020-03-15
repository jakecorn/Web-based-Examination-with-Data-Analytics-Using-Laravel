<?php

namespace Modules\Student\Http\Controllers;
error_reporting(0);
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Modules\Teacher\Entities\Criteria;
use Modules\Teacher\Entities\ClassRecord;
use Modules\Teacher\Entities\StudentRecord;
use Modules\Examination\Entities\ExamPart;
use Modules\Examination\Entities\Examination;
use Modules\Teacher\Entities\ClassRecordPair;
use Modules\Student\Entities\LongAnswer;
use Modules\Student\Entities\StudentAnnouncement;
use Modules\Student\Entities\StudentAnswer;
use Modules\Utilitize\Util;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Admin\Entities\Log;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DateTime;
date_default_timezone_set('Asia/Manila'); 

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    use ValidatesRequests;

    var $student_id;
    var $sy;
    var $semester;
    var $term;
    public function __construct(){
            $this->data['main_page']="Subject";
    }

    public function index()
    {
    //    echo $this->student_id." aaaaa";
       echo Util::get_session('student_id')." <br> 1";
       echo Util::get_session('sy')." <br> 1";
       echo Util::get_session('semester')." <br> 1";
       echo Util::get_session('class_record_type')." <br> 1";
         
    }

     public function subjectList()
    {
        $this->student_id=  Util::get_session('student_id');    
        $this->sy=  Util::get_session('sy');    
        $this->semester=  Util::get_session('semester');   
        $this->term=  Util::get_session('class_record_type');  
        $subject = DB::select("select * from student_records, class_records where student_records.class_record_id=class_records.id and student_id='".Util::get_session('student_id')."' and sy='".Util::get_session('sy')."' and semester='".Util::get_session('semester')."' and type='".Util::get_session('class_record_type')."' order by day,time");
        
        $this->data['subject'] = $subject;
        $this->data['navigation'] = "List";
        $this->data['page_title'] = "subjectlist";
        return view('student::layouts.master',$this->data);
    }


    function viewRecord($id)
    {
        Util::set_session('class_record_id', $id);

        $mid_final_id = ClassRecordPair::where('class_record_id_mid', $id)->orWhere('class_record_id_final', $id)->get();

        if(count($mid_final_id)==0){
            return redirect()->back()->with('warning','Subject records are not found.');
        }

        $criteria = Criteria::where('class_record_id', $mid_final_id[0]->class_record_id_mid)->get();
        $this->data['criteria'] = $criteria;
        
        $criteria2 = Criteria::where('class_record_id', $mid_final_id[0]->class_record_id_final)->get();
        $this->data['criteria2'] = $criteria2; 

        $student = DB::select("select *,id as student_id from students where id='".Util::get_session('student_id')."'");

        $this->data['student'] = $student;

        $student = DB::select("select *,id as student_id from students where id='".Util::get_session('student_id')."'");

        $this->data['student2'] = $student;


        $record = ClassRecord::where('id', $id)->get();        
        $this->data['detail'] = $record;

        $this->data['navigation'] = "Records";
        $this->data['page_title'] = "subjectrecord";
        return view('student::layouts.master',$this->data);
    }

    public function examList($class_record_id)
    {
        $exam = DB::select("select * from class_records,student_records,class_record_exams,examinations where class_records.id=student_records.class_record_id and class_records.id=class_record_exams.class_record_id and class_record_exams.examination_id=examinations.id  and class_records.id='$class_record_id' and student_id='".Util::get_session('student_id')."'");

        $class_record_type = Util::get_session('class_record_type');
        if($class_record_type == 'Final'){
            $class_record_id_column = 'class_record_id_final';
        } 

        if(count($exam)==0){
            return redirect()->back()->with(["warning"=>"Sorry, you don't have any examinations yet."]);
        }

        $this->data['examination_list'] = $exam;
        $this->data['navigation'] = "Examination";
        $this->data['page_title'] = "examlist";
        return view('student::layouts.master',$this->data);
    }

    public function startTake($class_record_id,$exam_id)
    { 
        date_default_timezone_set('Asia/Manila'); 

        $checkDone = DB::table('student_exams')->where('examination_id',$exam_id)->where('student_id',Util::get_session('student_id'))->where("is_resumed",1)->get(['submitted','end_time']);
        
        if(count($checkDone)>0){
            $end_time = $checkDone[0]->end_time;
            $now      =  date('Y-m-d H:i:s');

            if($end_time<$now && $end_time !=''){
                
                $checkifsubmitted= DB::table('student_exams')->where('examination_id',$exam_id)->where('student_id',Util::get_session('student_id'))->get(['submitted']);
               
                DB::table('student_exams')->where('examination_id',$exam_id)->where('student_id',Util::get_session('student_id'))->update(['submitted'=>'true']);
                return redirect()->route('score',$exam_id)->withErrors("You are done taking this examination.");
            }

            if($checkDone[0]->submitted=="true"){
               return redirect()->route('score',$exam_id)->withErrors("You are done taking this exam.");
            }
        }
        // check if valid examination id that belongs to a valid user student
        $check=DB::select("select * from class_record_exams,student_records where student_records.class_record_id=class_record_exams.class_record_id and student_id='".Util::get_session('student_id')."' and class_record_exams.examination_id='".$exam_id."'");

        if(count($check)==0){
            return redirect()->route('examlist')->withErrors(['errors'=>"Changing the URL manually doesn't work"]);
        }

        // check if done ramdomizing questions and choices

        $checkRand=DB::select("select * from student_exams where student_id='".Util::get_session('student_id')."' and examination_id='$exam_id'");

        if(count($checkRand)==0){
            $this->deleteQuestion($exam_id);
        }

        $this->data['examination'] = Examination::where('id',$exam_id)->get();
        $this->data['class_record_id'] = $class_record_id;
        $this->data['topic']=self::examPart($exam_id);

        if(count($checkRand)==0){

                foreach ($this->data['topic'] as $i => $topic) {           

                    $question=self::getQuestion($topic->id,$exam_id);             

                    $position = 1;
                    foreach ($question as $no => $question) {
                        $this->saveRandQuestion($question->id,$position++); 
                       
                       // RAND MULTIPLE CHOICE
                       if($topic->exam_type=="mul" or $topic->exam_type=="tru"){
                                 $this->randMul($question->id);
                       }
                   }
                   
                   if($topic->exam_type=="mat"){
                         $this->randMat($topic->id,$exam_id);
                   }

                }

            // store exam to indicate the questions have ramdomized
            DB::table('student_exams')->insert([
                'student_id'=>Util::get_session('student_id'),
                'examination_id'=>$exam_id,
            ]);
        }

        $this->data['navigation'] = "Examination";
        $this->data['page_title'] = "starttake";
        return view('student::layouts.master',$this->data);
    }

    
    //delete questions and choices if not done randomizing all the quesions or choicse due to power lose
    
    static function questionCount($exam_id){
        return DB::table('questions')->where('examination_id',$exam_id)->count();
    }

    public function deleteQuestion($exam_id)
    {

        foreach (self::examPart($exam_id) as $i => $part) {
            foreach (self::getQuestion($part->id,$exam_id) as $key => $question) {
                DB::table('rand_questions')->where('student_id',Util::get_session('student_id'))->where('question_id',$question->id)->delete();
                DB::table('rand_choices')->where('student_id',Util::get_session('student_id'))->where('question_id',$question->id)->delete();
            }
        }

    }

    static public function getQuestion($part_id,$exam_id)
    {
         return DB::table('questions')->where('exam_part_id',$part_id)->where('examination_id',$exam_id)->inRandomOrder()->get();    
    }


    public function saveRandQuestion($question_id,$position)
    {
         DB::table('rand_questions')->insert([
            'student_id'=>Util::get_session('student_id'),
            'question_id'=>$question_id,
            'position'=>$position
        ]);
    }

    public function randMul($question_id)
    {        
       $choices = DB::table("question_choices")->where('question_id',$question_id)->inRandomOrder()->get();

       foreach ($choices as $i => $choice) {
            DB::table('rand_choices')->insert([
                'student_id'=>Util::get_session('student_id'),
                'question_id'=>$question_id,
                'choice_id'=>$choice->id
            ]);
       }
    }

    public function randMat($part_id,$exam_id)
    {
        $question=self::getQuestion($part_id,$exam_id);         

           $position = 1;
           foreach ($question as $no => $question) {

                $answer = DB::table('question_choices')->where('question_id',$question->id)->get();      

                foreach ($answer as $answer) {
                    DB::table('rand_choices')->insert([
                        'student_id'=>Util::get_session('student_id'),
                        'question_id'=>$question->id,
                        'choice_id'=>$answer->id
                    ]);
                }
           }
    }

    function calculateTransactionDuration($startDate, $endDate)
    {
        $startDateFormat = new DateTime($startDate);
        $EndDateFormat = new DateTime($endDate);
        // the difference through one million to get micro seconds
        $uDiff = ($startDateFormat->format('u') - $EndDateFormat->format('u')) / (1000 * 1000);
        $diff = $startDateFormat->diff($EndDateFormat);
        $s = (int) $diff->format('%s') - $uDiff;
        $i = (int) ($diff->format('%i')) * 60; // convert minutes into seconds
        $h = (int) ($diff->format('%h')) * 60 * 60; // convert hours into seconds

        return sprintf('%.6f', abs($h + $i + $s)); // return total duration in seconds
    }

    public function examinationStart($class_record_id,$exam_id,$part_num,$position)
    {
        $this->data['part_num']=$part_num;
        $this->data['position']=$position;
        $this->data['previous']="";
        $this->data['done']=0; 
        $this->data['examination'] = Examination::where('id',$exam_id)->get();
        $this->data['end_time'] = $this->setTimeLimit($exam_id,$class_record_id);
        $currenttime = date('Y-m-d H:i:s');
        $this->data['end_time_millisecond'] =  strtotime($this->data['end_time']) - strtotime($currenttime);

        $check_exam = DB::table("class_record_exams")->where('class_record_id',$class_record_id)->where('examination_id',$exam_id)->get(['visibility']);
        
        if($check_exam[0]->visibility==0){
            return redirect()->route("subjectlist")->withErrors("Examination is paused or set not to be visible by Instructor.");
        }

        $checkDone = DB::table('student_exams')->where('examination_id',$exam_id)->where('student_id',Util::get_session('student_id'))->get(['submitted','end_time']);

        if(count($checkDone)>0){
            $end_time = $checkDone[0]->end_time;
            $now      =  date('Y-m-d H:i:s');

            if($end_time<$now && $end_time !=''){
                
                $checkifsubmitted= DB::table('student_exams')->where('examination_id',$exam_id)->where('student_id',Util::get_session('student_id'))->get(['submitted']);
               
                DB::table('student_exams')->where('examination_id',$exam_id)->where('student_id',Util::get_session('student_id'))->update(['submitted'=>'true']);
                
                if($checkifsubmitted[0]->submitted!='true'){
                  
                    // if not submited meaning the has expired and not intentionally submitted by student
                   return redirect()->route('score',$exam_id)->withErrors("Time duration has ended. Answers are submitted automatically.");//d_time."--". $now );
                }else{

                    // student tries to access any of the examination page
                    // return redirect()->route('score',$exam_id)->withErrors("You are done taking this examination.");
                }
            }
            if($checkDone[0]->submitted=="true"){
               return redirect()->route('score',$exam_id)->withErrors("You are done taking this exam.1223");
            }
        }
         
        if(count($this->data['examination'])==0){
             return redirect()->back()->withErrors("Changing the URL manually doesn't work.");
        }

        $this->data['exam_part'] = examPart::where('examination_id',$exam_id)->where('part_num',$part_num)->get();
        
        if(count($this->data['exam_part'])==0){

            if(Util::get_session('submitted')==0){
                $this->data['errors']="Changing the URL manually doesn't work.";
                return redirect()->back()->withErrors($this->data['errors']);
            }else{
                $this->data['done']=1; 
                return redirect()->back()->with($this->data);
            }

        }else{            
            
            $this->data['subject'] = DB::select("select * from class_records,student_records,class_record_exams where class_records.id=student_records.class_record_id and class_records.id=class_record_exams.class_record_id and examination_id='$exam_id' and  class_record_exams.class_record_id='$class_record_id' and student_id='".Util::get_session('student_id')."'");
                if(count($this->data['subject'])>0){
                    if($this->data['subject'][0]->visibility==0){
                        return redirect()->back()->withErrors("Changing the URL manually doesn't work.");
                    }
                }else{
                    return redirect()->back()->withErrors("Changing the URL manually doesn't work.");
                }


            if($this->data['exam_part'][0]->exam_type=="mat"){                
                $this->data['question'] = DB::select("select * from questions,rand_questions where questions.id=rand_questions.question_id and examination_id='$exam_id' and exam_part_id='".$this->data['exam_part'][0]->id."' and student_id='".Util::get_session('student_id')."' order by position");            
            }else{
                $this->data['question'] = DB::select("select * from questions,rand_questions where questions.id=rand_questions.question_id and examination_id='$exam_id' and exam_part_id='".$this->data['exam_part'][0]->id."' and student_id='".Util::get_session('student_id')."' and position>=$position order by position asc limit 2");            
                
            }
         
            if(count($this->data['question'])==0){
                
                if(Util::get_session('submitted')==1){
                        $this->data['check']=3;
                }else{
                    return redirect()->back();
                }
                return redirect()->route('startexam',[$class_record_id,$exam_id,$part_num+1,1]);
            }

            // $this->data['teacher']=DB::table('teachers')->where('id',$this->data['subject'][0]->teacher_id)->get();
            $this->data['subject'][0]->teacher_id;
            $this->data['teacher']=DB::select("select * from teachers,users where teachers.id=users.account_id and teachers.id='".$this->data['subject'][0]->teacher_id."'");
        } 
        $question_list = DB::select("select rq.*,exp.part_num,sa.answer,la.answer as long_answer from students st
                        join student_exams ste on st.id = ste.student_id
                        join examinations ex on ex.id=ste.examination_id
                        join questions q on q.examination_id=ex.id
                        join rand_questions rq on rq.question_id=q.id and rq.student_id=ste.student_id
                        join exam_parts exp on exp.id=exam_part_id
                        left join student_answers sa on sa.question_id=rq.question_id and sa.student_id=ste.student_id
                        left outer join long_answers la on la.student_id=st.id and la.question_id=rq.question_id
                        where ste.student_id=".Util::get_session('student_id')." and ste.examination_id=".$exam_id." order by rq.id asc");
        
        $this->data['class_record_id'] = $class_record_id;
        $this->data['question_list'] = $question_list;
        Util::set_session('submitted',0);
        
        return view('student::examination',$this->data);
    }

    static public function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    static public function doneExam($exam_id)
    {
        return DB::table('student_exams')->where('student_id',Util::get_session('student_id'))->where('examination_id',$exam_id)->where('submitted','true')->count();
 
    }

    function examNext(Request $req){
         Util::set_session('position',$req['position']);
         foreach ($req['question'] as $i => $question) {

                if($req['exam_type']=="ide" or $req['exam_type']=="ess"){

                    if(strlen($req['choice'][$i][0])>0){                            
                        $choice= LongAnswer::updateOrCreate(['student_id' => Util::get_session('student_id'),'question_id'=>$question],
                        [
                            'student_id' => Util::get_session('student_id'),
                            'question_id' => $question,
                            'answer' => $req['choice'][$i][0],
                            'score' => ''

                        ]);
                    }
                }else{
                    
                    if(isset($req['choice'][$i][0])){
                        $choice= StudentAnswer::updateOrCreate(['student_id' => Util::get_session('student_id'),'question_id'=>$question],
                            [
                                'student_id' => Util::get_session('student_id'),
                                'question_id' => $question,
                                'answer' => $req['choice'][$i][0]

                            ]);
                     }
                    
                }

        }

        Util::set_session('submitted',1);

        if($req['exam_type']=="mat"){
            return redirect()->route('startexam',[$req['class_record_id'],$req['examination_id'],$req['part_num']+1,1]);
        }

        return redirect()->route('startexam',[$req['class_record_id'],$req['examination_id'],$req['part_num'],$req['position']+1]);

     }

    function examScore($exam_id){    

        $checkDone = DB::table('student_exams')->where('examination_id',$exam_id)->where('student_id',Util::get_session('student_id'))->where('submitted','true')->count();

        if($checkDone==0){
            return redirect()->back()->with('warning','Examination is not yet submitted or not found.');
        }
        
        $this->data['examination'] = Examination::where('id',$exam_id)->get();

        $this->data['exam_part'] = examPart::where('examination_id',$exam_id)->get();

        $subject= DB::select("select * from class_records,student_records,class_record_exams where class_records.id=student_records.class_record_id and class_records.id=class_record_exams.class_record_id and examination_id='$exam_id' and student_id='".Util::get_session('student_id')."'");
        $this->data['subject'] =$subject;

        $this->data['teacher']=DB::select("select * from teachers,users where teachers.id=users.account_id and teachers.id='".$subject[0]->teacher_id."'");

        $this->data['navigation'] = "Score";
        $this->data['page_title'] = "examscore";
        return view('student::layouts.master',$this->data);
    }

    public function setTimeLimit($examination_id,$class_record_id)
    {
        date_default_timezone_set('Asia/Manila'); 
        
        $check_stored = DB::table('student_exams')->where('examination_id',$examination_id)->where('student_id',Util::get_session('student_id'))->get();
        
        $get_time = DB::table("examinations")->where('id',$examination_id)->get(['duration']);
       
        if(count($get_time)==0){
            return redirect()->back()->withErrors(['errors'=>"Changing the URL manually doesn't work"]);
        }

        $duration=$get_time[0]->duration;
        if($duration=="None"){
            return 1230;
        }
        
        
        $now        =  date('Y-m-d H:i:s');
        // var_dump($class_record_id);
        // var_dump($examination_id);

        $exam_pause_time = DB::table("class_record_exams")->where('class_record_id',$class_record_id)->where('examination_id',$examination_id)->get(['pause_time','visibility']);
        // exit;
        if($exam_pause_time[0]->visibility==0){
            return;
        }

        // var_dump($exam_pause_time[0]->pause_time);
        
        if($exam_pause_time[0]->pause_time!="" && $check_stored[0]->end_time!=""){
            $duration = strtotime($check_stored[0]->end_time) - strtotime($exam_pause_time[0]->pause_time);
            $check_stored[0]->end_time."-".$exam_pause_time[0]->pause_time."=";
            $end_time = date('Y-m-d H:i:s',strtotime('+ '.$duration.' seconds',strtotime($now)));
        }else{
            $end_time   = date('Y-m-d H:i:s',strtotime('+'.$duration.' minutes',strtotime($now)));
        }
        // var_dump($end_time);
        // echo "duration: ".$duration;
        // echo "NOW: ".$now;
        // exit;
        if($check_stored[0]->is_resumed==0){  
            DB::table('student_exams')->where('examination_id',$examination_id)->where('student_id',Util::get_session('student_id'))->where("is_resumed",0)->update(['end_time'=>$end_time,'is_resumed'=>1]);

        }
        
        $get_endtime= DB::table('student_exams')->where('examination_id',$examination_id)->where('student_id',Util::get_session('student_id'))->get(['end_time']);
        
        // var_dump($get_endtime[0]->end_time);
        return  $get_endtime[0]->end_time;

 
    }

    
    function storeExamScore($exam_id){
          DB::table('student_exams')->where('examination_id',$exam_id)->where('student_id',Util::get_session('student_id'))->
        update([
            'submitted' => 'true'
            ]);

        return $this->examScore($exam_id);
    }

    function viewAnswer($exam_id){

        $this->data['examination'] = Examination::where('id',$exam_id)->get();

        if($this->data['examination'][0]->answer_visibility==0){
            //return redirect()->route('subjectlist');
            return redirect()->back()->with('warning','Answer sheet is not yet available.');
        }

        $this->data['exam_part'] = examPart::where('examination_id',$exam_id)->get();

        $this->data['subject'] = DB::select("select * from class_records,student_records,class_record_exams where class_records.id=student_records.class_record_id and class_records.id=class_record_exams.class_record_id and examination_id='$exam_id' and student_id='".Util::get_session('student_id')."'");

        $this->data['teacher']=DB::table('teachers')->where('id',$this->data['subject'][0]->teacher_id)->get();

        $this->data['navigation'] = "Examination /Answer Sheet";
        $this->data['page_title'] = "viewanswer";
        return view('student::layouts.master',$this->data);

    }

    static function viewQuestion($exam_id,$part_id){
        return DB::table('questions')->where('examination_id',$exam_id)->where('exam_part_id',$part_id)->get();
    }

    static function viewChoices($question_id){
        return DB::table('question_choices')->where('question_id',$question_id)->get();
    }

    static function viewChoices_true($question_id){
        return DB::table('question_choices')->where('question_id',$question_id)->where('answer',1)->get();
    }


    static function getAsnwerPerQuestion($question_id){
        return DB::select("select question_choices.answer as correct,student_answers.answer as answer_id,choice_desc, student_answers.answer as answer from student_answers,question_choices where student_answers.answer=question_choices.id and student_answers.question_id='$question_id' and  student_id='".Util::get_session('student_id')."'");
    }

    static function getAsnwerPerQuestion_ide($question_id){
        return DB::select("select * from long_answers where long_answers.question_id='$question_id' and  student_id='".Util::get_session('student_id')."'");
    }
    
 
    static public function answerVisibility($exam_id)
    {
       return Examination::where('id',$exam_id)->where('answer_visibility','1')->count();

    }


    static public function getNumberItems($exam_id,$part_id){
         return DB::table('questions')->where('exam_part_id',$part_id)->where('examination_id',$exam_id)->count();    

    }

    static public function getNumberItems_ess($exam_id,$part_id){

        $question = DB::table('questions')->where('exam_part_id',$part_id)->where('examination_id',$exam_id)->get();

        $question_id = "";
        foreach ($question as $question) {
            $question_id.=$question->id.",";
        }
        $question_id.="0";
         $sum = DB::select("select sum(point) as total from points where question_id in ($question_id)");    
         return $sum[0]->total;

    }

    static public function getScore($exam_id,$part_id){
        $question=self::getQuestion($part_id,$exam_id); 

        $score=0;
        foreach ($question as $i => $question) {
            $choices = DB::select("select * from question_choices,student_answers where question_choices.question_id=student_answers.question_id and question_choices.id=student_answers.answer and question_choices.answer=1 and student_answers.question_id='$question->id' and  student_answers.student_id='".Util::get_session('student_id')."'");
            $score+=count($choices);
        }
        return $score;

    }

    public function announcementList()
    {
        $announcement= DB::select("select announcement,announcements.date,announcements.time as time_posted,announcement_id,users.name as name,sub_code,sub_sec,day,class_records.time as time_schedule  from student_records,class_record_announcements,announcements,users,class_records where class_records.id=class_record_announcements.class_record_id and class_records.teacher_id=users.account_id and users.role='Teacher' and  announcements.id=class_record_announcements.announcement_id and student_records.class_record_id=class_record_announcements.class_record_id and student_id='".Util::get_session('student_id')."' order by announcement_id desc");
        
        $this->data['main_page'] ="Announcements";
        $this->data['announcement'] =$announcement;
        $this->data['navigation'] = "Announcement";
        $this->data['page_title'] = "announcement";
        return view('student::layouts.master',$this->data);
    }

    static public function announcementNotification()
    {
        $not = DB::select("select count(student_records.student_id) as numnotification from student_records,class_record_announcements,class_records where class_records.id=student_records.class_record_id and student_records.class_record_id=class_record_announcements.class_record_id and announcement_id not in (select announcement_id from student_announcements where student_id='".Util::get_session('student_id')."') and student_id='".Util::get_session('student_id')."' and type='".Util::get_session('class_record_type')."' and  semester='".Util::get_session('semester')."' and sy='".Util::get_session('sy')."' order by class_record_announcements.announcement_id desc ");
        return $not[0]->numnotification;
    }

    public function filesList()
    {
        $file= DB::select("select *,class_records.time as time_schedule ,files.time as time_posted  from student_records,class_record_files,files,class_records,users where users.role='Teacher' and  users.account_id=class_records.teacher_id and class_records.id=student_records.class_record_id and  files.id=class_record_files.file_id and student_records.class_record_id=class_record_files.class_record_id and student_id='".Util::get_session('student_id')."' and type='".Util::get_session('class_record_type')."' and  semester='".Util::get_session('semester')."' and sy='".Util::get_session('sy')."' order by file_id desc");
        
        $this->data['main_page'] ="Files";
        $this->data['file'] =$file;
        $this->data['navigation'] = "List";
        $this->data['page_title'] = "file";
        return view('student::layouts.master',$this->data);
    }
    static public function fileNotification()
    {
        $not = DB::select("select count(student_records.student_id) as numnotification from student_records,class_record_files where student_records.class_record_id=class_record_files.class_record_id and file_id not in (select file_id from student_files where student_id='".Util::get_session('student_id')."') and student_id='".Util::get_session('student_id')."'");
        return $not[0]->numnotification;
    }

    static public function saveNotification($not){
        foreach ($not as $announcement_id) {
            $c=StudentAnnouncement::where('announcement_id',$announcement_id)->where('student_id',Util::get_session('student_id'))->count();
            if($c==0){
                
                $ann = new StudentAnnouncement;
                $ann->student_id=Util::get_session('student_id');
                $ann->announcement_id=$announcement_id;
                $ann->save();
            }
        }
    }
    static public function saveFileNotification($not){
        foreach ($not as $file_id) {
            $c=DB::table("student_files")->where('file_id',$file_id)->where('student_id',Util::get_session('student_id'))->count();
            if($c==0){
                
                DB::table("student_files")->insert([
                    "student_id"=>Util::get_session('student_id'),
                    "file_id"=>$file_id
                    ]);
                
            }
        }
    }


    static public function getScore_ide($exam_id,$part_id){
        $question=self::getQuestion($part_id,$exam_id); 

        $score=0;
        foreach ($question as $i => $question) {
            $choices = DB::select("select * from question_choices,long_answers where question_choices.question_id=long_answers.question_id and question_choices.choice_desc=long_answers.answer and question_choices.answer=1 and long_answers.question_id='$question->id' and  long_answers.student_id='".Util::get_session('student_id')."'");
            $score+=count($choices);
        }
        return $score;

    }

    static public function getScore_ess($exam_id,$part_id){
        $question=self::getQuestion($part_id,$exam_id); 

        $score=0;
        $finish=true;
        $startedChecking=false;
         Util::set_session("to_be_checked",false); 
        foreach ($question as $i => $question) {
            $choices = DB::select("select * from long_answers where question_id='$question->id' and  long_answers.student_id='".Util::get_session('student_id')."'");
            
            if(count($choices)>0){
                if($choices[0]->score==""){
                    $finish=false;
                }

                if($choices[0]->score!=""){
                    $startedChecking=true;
                }

                if(is_numeric($choices[0]->score)){
                    $score+=$choices[0]->score;
                }
            }
        }
        
        if($finish==false){
            if($startedChecking==false){
                Util::set_session("to_be_checked",true);    
                return 0;
            }else{
                Util::set_session("initial_score",true);            
                return $score;
                // return $score." initial score";
            }   
                

        }

        Util::set_session("initial_score",false);  
        return $score;

    }


   static function getAnswer($question_id){
        $a =  DB::table('student_answers')->where('question_id',$question_id)->where('student_id',Util::get_session('student_id'))->get();
        
        if(count($a)>0){
            return $a[0]->answer;   
        }
        return '';

    }

    static function getAnswer_long($question_id){
         $a =  DB::table('long_answers')->where('question_id',$question_id)->where('student_id',Util::get_session('student_id'))->get();
        
        if(count($a)>0){
            return $a[0]->answer;   
        }
        return '';

    }

    static public function getRandChoice($question_id){
        return DB::select("select * from question_choices, rand_choices where question_choices.id=rand_choices.choice_id and rand_choices.question_id='$question_id' and student_id='".Util::get_session('student_id')."' order by rand_choices.id asc");
    }

    static public function getRandChoice_mat($exam_id,$part_id){
        $question_id = "";
        
        $question = DB::table('questions')->select('id')->where('exam_part_id',$part_id)->where('examination_id',$exam_id)->get();    

        foreach ($question as $question) {
            $question_id.=$question->id.",";
        }

        $question_id.="0";
        return DB::select("select * from question_choices, rand_choices where question_choices.id=rand_choices.choice_id and rand_choices.question_id in ($question_id) and student_id='".Util::get_session('student_id')."' order by rand_choices.id asc");
    }

    static public function examPart($id){
        $topic = ExamPart::where('examination_id',$id)->get();        
        return $topic;
    }



   public function settings()
    {
        $this->data['sy'] = DB::table('class_records')->groupBy('sy')->orderby('sy','asc')->pluck('sy');

        $this->data['main_page'] = "Settings";
        $this->data['navigation'] = "Update Settings";
        $this->data['page_title'] = "settings";
        return view('student::layouts.master',$this->data);
    }


    public function saveSettings(Request $req)
    {
       Session::put('sy',$req['sy']);
       Session::put('semester',$req['semester']);
       Session::put('class_record_type',$req['term']);

       return redirect()->back()->with('message','Settings have been changed.');
    }

    static function information(){
        $information= DB::table('students')->where('id',Auth::user()->account_id)->get();
        return $information[0];
    }

    // account

     public function account()
     {
        $this->data['course'] = $this->getCourse();
        $this->data['main_page'] = "My Account";
        $this->data['navigation']='Update';
        $this->data['page_title']="myaccount";

        return view('student::layouts.master',$this->data);
     }


     public function storeAccount(Request $req)
     {
        $this->validate(request(),[
            'current_password'=>'required',
            'name'=>'required',
            'stud_address'=>'required',
            'photo'     =>  'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = \App\User::where('username',$req['username'])->where('id',"!=",Auth::user()->id)->count();
        
        if($user>0){
            return redirect()->back()->withErrors(["The username has already been taken."]);
           
        }

        if(!Hash::check($req['current_password'], Auth::user()->password)){
             return redirect()->back()->withErrors(["Current password is inccorrect."]);
        }

        $previous_detals = \App\User::where('account_id',Auth::user()->account_id)->get(['username','name','id']);

        $update_detail = "";
        
        if($previous_detals[0]->username!=$req['username']){
            $update_detail.=" . Username is updated from ".$previous_detals[0]->username." to ".$req['username'];
        }

        if(strtolower($previous_detals[0]->name)!=strtolower($req['name'])){
            $update_detail.=" . Name is updated from ".$previous_detals[0]->username." to ".$req['username'];
        }

        if(strlen($req['password'])>0 or strlen($req['password_confirmation'])>0){
 

            if($req['password']!=$req['password_confirmation']){
                return redirect()->back()->withErrors(['errors'=>"password confirmation does't match."]);
            }

            if(strlen($req['password'])<6){
                return redirect()->back()->withErrors(['errors'=>"New password must at least 6 characters."]);
                
            }

             \App\User::where('id',Auth::user()->id)->update([
                'username'=>$req['username'],
                'password'=>bcrypt($req['password']),
                'photo'=>$this->initiatePhotoUPload($req, Auth::user()->photo)
            ]);


             DB::table("students")->where('id',Auth::user()->account_id)->update([
                'password'=>$req['password']
            ]);

            $this->saveLog('Password is updated'.$update_detail,'Update Account',$previous_detals[0]->id,$previous_detals[0]->id);

        }else{
             \App\User::where('id',Auth::user()->id)->update([
                 'photo'=>$this->initiatePhotoUPload($req, Auth::user()->photo),
                 'username'=>$req['username'],
            ]);

            $this->saveLog($update_detail,'Update Account',$previous_detals[0]->id,$previous_detals[0]->id);
        }
        
        DB::table("students")->where('id',Auth::user()->account_id)->update([                
                'stud_address'=>$req['stud_address'],
                'stud_contact_num'=>$req['stud_contact_num'],
                'course_id'=>$req['course_id'],
                'year'=>$req['year'],
                
            ]);


        return redirect()->back()->with('message','Account has been updated.');
    }

    public function initiatePhotoUPload($request, $previous_photo){

        if (is_null($request->file('photo')) ) {
            return null;
        }
 
        // Get image file
        $image = $request->file('photo');
        $name = $request->input('name').time();
        $name = str_replace(' ', '', strtolower($name));
        $folder = '/uploads/images/';
        $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
        Util::uploadPhoto($image, $folder, 'public', $name);

        if(!is_null($previous_photo)){
            Util::deletePhoto($previous_photo); 
        }
        
        return $filePath;
    }


    public function getCourse()
    {
        return DB::table("courses")->get();
        
    }

    public function saveLog($content,$action_type,$created_by,$owned_by){
        $date_time = date('Y-m-d H:i:s');
        $logs = new log;
        $logs->content=$content;
        $logs->action_type=$action_type;
        $logs->created_by=$created_by;
        $logs->owned_by=$owned_by;
        $logs->created_at=$date_time;
        $logs->save();
    }

}
