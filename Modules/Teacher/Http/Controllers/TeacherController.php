<?php

namespace Modules\Teacher\Http\Controllers;
error_reporting(0);
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Modules\Teacher\Entities\ClassRecord;
use Modules\Teacher\Entities\ClassRecordPair;
use Modules\Teacher\Entities\CriteriaRecord;
use Modules\Teacher\Entities\Score;
use Modules\Teacher\Entities\Criteria;
use Modules\Teacher\Entities\Course;
use Modules\Teacher\Entities\Student;
use Modules\Teacher\Entities\StudentRecord;
use Modules\Teacher\Entities\Teacher;
use Illuminate\Support\Facades\Auth;
use Modules\Examination\Entities\ExamPart;
use Modules\Examination\Entities\QuestionChoice;
use Modules\Examination\Entities\Question;
use Modules\Examination\Entities\Examination;
use Modules\Examination\Http\Controllers\ExaminationController;
use Modules\Utilitize\Util;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Admin\Entities\Log;
use Session;
use App\User;
use Modules\Base\Http\Controllers\BaseController;
class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
     use ValidatesRequests;

     public function __construct(){
         $class_record = ClassRecord::where('teacher_id','=',Util::get_session('teacher_id'))->where('sy','=',Util::get_session('sy'))->where('semester','=',Util::get_session('semester'))->get();
        $this->data['class_record'] = $class_record;
        $this->data['main_page'] = "Class Record";
        $this->data['message'] = "";
        
                     
                
                
 
     }

     public function index(){
        
     }

     static public function countStudent($class_record_id){
        return  DB::select("select * from student_records where class_record_id='".$class_record_id."'");

    }
     

    public function getClassRecordList()
    {
        if(Session::get("register")=="TRUE"){

            Auth::logout();
            return redirect()->route("login")->with("successs","Account was created but not yet activated.");

        }

        if(Auth::user()->status==0){
             Auth::logout();
            return redirect()->route("login")->with("message","Account is not yet activated. Please contact the admin to activate your account.");
        }

        $record = ClassRecord::where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->where('type',Util::get_session('class_record_type'))->orderBy('day')->orderBy('time')->get();
        
        $this->data['class_record_list']=$record;
        $this->data['navigation']='List';
        $this->data['page_title']='classRecordList';

        return view('teacher::ClassRecord.main',$this->data);

    }
    
    public function getCreate()    {
        $this->data['page_title'] = "createClassRecord";
        $this->data['navigation']='Create Class Record';
        return view('teacher::ClassRecord.main',$this->data);
    }

    public function getClassRecordUpdate($id){
        $this->data['term'] = "midterm";

        $this->classRecordDetail($id);
        $this->data['navigation']='Class Record Update';
        $this->criteriaList($id);
        $this->data['page_title'] = "classRecordUpdate";
        return view('teacher::ClassRecord.main',$this->data);

    }

    public function storeClassRecordUpdate(Request $request)
    {
        $this->validate(request(),[
            'sub_code'=>'required',
            'sub_desc'=>'required',
            'sub_sec'=>'required',
            'day'=>'required',
            'time'=>'required',
            'plus'=>'required',
            'times'=>'required',            
            'midterm_percentage'=>'required',
            'final_percentage'=>'required',
            'criteria.*'=>'required|distinct',
            'percent'=>'required',
            'criteria_id'=>'required',
            'class_record_id'=>'required'
            ],
            [
                'criteria.*.distinct'=>"You have a duplicate cirteria name"
            ]);

        

        ClassRecord::where('id', $request['class_record_id'])->where('teacher_id',Util::get_session('teacher_id'))
        ->update([
            'sub_code' => $request['sub_code'],
            'sub_desc' => $request['sub_desc'],
            'sub_sec' => ucfirst($request['sub_sec']),
            'day' => $request['day'],
            'time' => $request['time'],
            'formula_plus' => $request['plus'],
            'formula_times' => $request['times']
        ]);

        //get midterm and final class record id to update simoultaneously
        $class_record_id = DB::select("select * from class_record_pairs where (class_record_id_mid='".$request['class_record_id']."' or class_record_id_final='".$request['class_record_id']."') and teacher_id='".Util::get_session('teacher_id')."' ");
        
        $check=ClassRecord::where('id',$class_record_id[0]->class_record_id_mid)->orwhere('id',$class_record_id[0]->class_record_id_final)->where('teacher_id',Util::get_session('teacher_id'))->update([
                'midterm_percentage' => $request['midterm_percentage'],
                'final_percentage' => $request['final_percentage']
                ]);



        $deleted = array();
        foreach ($request['criteria'] as $i=> $criteria) {
            
            $criteria=Criteria::where('id',$request['criteria_id'][$i])->where('class_record_id',$request['class_record_id'])
            ->update([
                'criteria'=>$request['criteria'][$i],
                'percent'=>$request['percent'][$i]

                ]);


            if($criteria==0){
                $criteria = new Criteria;
                $criteria->criteria=$request['criteria'][$i];
                $criteria->percent=$request['percent'][$i];
                $criteria->class_record_id=$request['class_record_id'];
                $criteria->save();
                array_push($deleted, $criteria->id);
            }else{
                
            array_push($deleted, $request['criteria_id'][$i]);
            }
        }
       
        // echo json_encode($deleted)."<br>"; 
        $check=CriteriaRecord::whereNotIn('criteria_id',$deleted)->where('class_record_id',$request['class_record_id'])->count();

        if($check>0){
            return redirect()->back()->withErrors(["errors"=>"This criteria has already a student record. Can not be deleted"]);
        }

        $d = Criteria::whereNotIn('id',$deleted)->where('class_record_id',$request['class_record_id'])->delete();
        $this->message('Class record has been updated successfully.');
       return redirect()->route('classrecord',$request['class_record_id'])->with('message','Class record has been updated.');
    }

    public function getClassRecord($id){
        Util::set_session('class_record_id', $id);

        $this->criteriaList($id);
        $this->data['student'] = $this->classStudentList($id);

        //get midterm exam id
       $criteria_id = Criteria::where('class_record_id',$id)->whereIn('criteria',['Midterm Exam','Final Exam'])->get(['id']);
        
        //get quiz critera id
       $quiz_criteria_id = Criteria::where('class_record_id',$id)->whereIn('criteria',['Quiz'])->get(['id','criteria']);

        //get examination id
       $examinations = DB::table('class_record_exams')->where('class_record_id',$id)->get(['examination_id']);
       
        foreach ($examinations as $key => $examination) {
            
            //get examination detail exam type
            $examination_detail = DB::table('examinations')->where('id',$examination->examination_id)->get(['exam_type','created_at']);

            if($examination_detail[0]->exam_type==0){
                // if examination is a quiz then use the critia id of a quiz;
                $criteria_id[0]->id = $quiz_criteria_id[0]->id;
            }

            // get parts of examination
            $exam_part = array();
            $exam_part=DB::table('exam_parts')->where('examination_id',$examination->examination_id)->get(['id','exam_type']);

            // update grade exam grade
            foreach ($this->data['student'] as $key => $student) {
                //get criteria record id
                $criteria_record = CriteriaRecord::where('criteria_id',$criteria_id[0]->id)->where('exam_id',$examination->examination_id)->get(['id']);

                // loop for exam parts
                $totalScore = 0;
                foreach ($exam_part as $key => $part) {

                        $score = 0;
                        
                        if($part->exam_type=="ess"){

                           $score+=$this->getScore_ess($examination->examination_id,$part->id,$student->student_id);
                        
                        }elseif($part->exam_type=="ide"){                
                         
                           $score+=$this->getScore_ide($examination->examination_id,$part->id,$student->student_id);
                       
                        }else{
                            
                           $score+=$this->getScore($examination->examination_id,$part->id,$student->student_id);
                        }

                        $totalScore+=$score;

                    }
                    // return $criteria_record[0]->id;

                    if(count($criteria_record)>0){

                        $existing_score = Score::where('student_id',$student->student_id)->where('criteria_record_id',$criteria_record[0]->id)->get();    
                        $update_score = false;

                        if(count($existing_score)>0){
                            if($existing_score[0]['is_manual_score'] != 1){
                                $update_score = true;
                            }
                        }else{
                            $update_score = true;
                        }

                        if($update_score){                            
                            Score::updateOrCreate(['student_id'=>$student->student_id,'criteria_record_id'=>$criteria_record[0]->id],
                                [
                                    'criteria_record_id'=>$criteria_record[0]->id,
                                    'student_id'=>$student->student_id,
                                    'score'=>$totalScore
                                ]);
                        }
                    }
            }
        }
       
 
        // exit;
        $this->classRecordDetail($id);
        $this->data['page_title'] = "showClassRecord";
        $this->data['navigation']='Records';
        return view('teacher::ClassRecord.main',$this->data);
    }

    // get score for midterme exam or final exam

        public function getScore($exam_id,$part_id,$stud_id){
            $question=$this->getQuestion($part_id,$exam_id); 

            $score=0;
            foreach ($question as $i => $question) {
                $choices = DB::select("select * from question_choices,student_answers where question_choices.question_id=student_answers.question_id and question_choices.id=student_answers.answer and question_choices.answer=1 and student_answers.question_id='$question->id' and  student_answers.student_id='$stud_id'");
                $score+=count($choices);
            }
            return $score;

        }

        public function getScore_ess($exam_id,$part_id,$stud_id){
                $question=$this->getQuestion($part_id,$exam_id); 

            $score=0;
            foreach ($question as $i => $question) {
                $choices = DB::select("select * from long_answers where question_id='$question->id' and  long_answers.student_id='$stud_id'");
                    if(count($choices)>0 and is_numeric($choices[0]->score)){                        
                        $score+=$choices[0]->score;
                    }
            }
            return $score;

        }

        public function getScore_ide($exam_id,$part_id,$stud_id){
                 $question=$this->getQuestion($part_id,$exam_id); 

                $score=0;
                foreach ($question as $i => $question) {
                    $choices = DB::select("select * from question_choices,long_answers where question_choices.question_id=long_answers.question_id and question_choices.choice_desc=long_answers.answer and question_choices.answer=1 and long_answers.question_id='$question->id' and  long_answers.student_id='$stud_id'");
                    $score+=count($choices);
                }
                return $score;

        }

        public function getQuestion($part_id,$exam_id)
        {
             return DB::table('questions')->where('exam_part_id',$part_id)->where('examination_id',$exam_id)->get();    
        }

    // end

    public function criteriaList($id){
        $criteria = Criteria::where('class_record_id', $id)->get();
        return $this->data['criteria'] = $criteria;
    }

    

    public function storeClassRecord(Request $request){

        $this->validate(request(),[
            'sub_code'=>'required',
            'sub_desc'=>'required',
            'day'=>'required',
            'sub_sec'=>'required',
            'time'=>'required',
            'percent.*'=>'required|integer',
            'criteria.*'=>'required|distinct',
            'plus'=>'required|integer',
            'times'=>'required|integer',
            'midterm_percentage'=>'required|integer',
            'final_percentage'=>'required|integer',
            ],[
                'criteria.*.distinct'=>"You have a duplicate cirtiera name"
            ]);
        
        $check=ClassRecord::where('sub_code',$request['sub_code'])->where('sub_sec',$request['sub_sec'])->where('teacher_id',Session::get('teacher_id'))->count();

        if($check>0){
            return redirect()->back()->withErrors(['errors'=>"Duplicate subject code and section"])->withInput();
        }

        $record = new ClassRecord;        
        $record->teacher_id=Session::get('teacher_id');
        $record->sub_desc=$request['sub_desc'];
        $record->sub_code=$request['sub_code'];
        $record->day=$request['day'];
        $record->time=$request['time'];
        $record->type="Midterm";
        $record->sy=Session::get('sy');
        $record->semester=Session::get('semester');
        $record->formula_times=$request['times'];
        $record->formula_plus=$request['plus'];
        $record->midterm_percentage=$request['midterm_percentage'];
        $record->final_percentage=$request['final_percentage'];
        $record->sub_sec=$request['sub_sec'];
        $record->save();

        $record2 = new ClassRecord;        
        $record2->teacher_id=Session::get('teacher_id');
        $record2->sub_desc=$request['sub_desc'];
        $record2->sub_code=$request['sub_code'];
        $record2->day=$request['day'];
        $record2->time=$request['time'];
        $record2->type="Final";
        $record2->sy=Session::get('sy');
        $record2->semester=Session::get('semester');
        $record2->formula_times=$request['times'];
        $record2->formula_plus=$request['plus'];
        $record2->midterm_percentage=$request['midterm_percentage'];
        $record2->final_percentage=$request['final_percentage'];
        $record2->sub_sec=$request['sub_sec'];
        $record2->save();
      
        $pair = new ClassRecordPair;
        $pair->class_record_id_mid = $record->id;
        $pair->class_record_id_final = $record2->id;
        $pair->teacher_id = Util::get_session('teacher_id');
        $pair->save();

        foreach ($request['criteria'] as $i => $c) {
            
            $criteria = new Criteria;
            $criteria->criteria=$c;
            $criteria->percent=$request['percent'][$i];
            $criteria->class_record_id=$record->id;
            $criteria->save();

            $criteria = new Criteria;
            $criteria->criteria= $c=="Midterm Exam"? 'Final Exam':$c;
            $criteria->percent=$request['percent'][$i];
            $criteria->class_record_id=$record2->id;
            $criteria->save();
        }

        return redirect()->route('classrecordlist')->with(['message'=>'Class record has been created successfully.']);
    }


    function deleteClassRecord($class_record_id){

        // check if has record before deleting

        $check = DB::select("select * from criterias, criteria_records where criterias.id=criteria_records.criteria_id and criterias.class_record_id='$class_record_id'");

        if(count($check)>0){
            return redirect()->route('classrecordlist')->withErrors(['errors'=>"You can not delete this class record because this already contains student records."]);
        }
        $record_id=DB::select("select * from class_record_pairs where class_record_id_mid='$class_record_id' or class_record_id_final='$class_record_id'");
        if(count($record_id)>0){
            $check=ClassRecord::where('id',$record_id[0]->class_record_id_mid)->where('teacher_id',Util::get_session('teacher_id'))->delete();
            
            if($check==0){
                return redirect()->back();
            }

            ClassRecord::where('id',$record_id[0]->class_record_id_final)->where('teacher_id',Util::get_session('teacher_id'))->delete();
            ClassRecordPair::where('class_record_id_mid',$record_id[0]->class_record_id_mid)->delete();
            StudentRecord::where('class_record_id',$record_id[0]->class_record_id_mid)->orWhere('class_record_id',$record_id[0]->class_record_id_final)->delete();
            criteria::where('class_record_id',$record_id[0]->class_record_id_mid)->orWhere('class_record_id',$record_id[0]->class_record_id_final)->delete();
            
        }
        return redirect()->back()->with(['message'=>'Class record has been deleted']);
    }

    function addScoreRecord($c_id,$id){
        $this->classRecordDetail($c_id);

        $this->data['current_criteria_name']=Criteria::where('id','=',$id)->get();

        if($this->data['current_criteria_name'][0]->criteria=="Attendance"){
            $this->data['page_title']='addScoreRecordAttendance';
        }else{
            $this->data['page_title']='addScoreRecord';
        }

        $this->data['criteria_id']=$id;
        $this->data['criteria_name']=$this->data['current_criteria_name'][0]->criteria;
        
        $this->data['navigation']=$this->data['current_criteria_name'][0]->criteria." / Add Record";
        $this->data['messsage']='';
        $this->data['student_list']=$this->classStudentList($c_id);

        return view('teacher::ClassRecord.main',$this->data);
    }

    function storeScoreRecord(Request $request){

        $this->validate(request(),[
                'class_record_id'=>'required',
                'date'=>'required',
                'totalScore'=>'required',
                'score'=>'required',
            ]);


        $criteriaRecord = new CriteriaRecord;
            
        $criteriaRecord->criteria_id=$request['criteria_id'];
        $criteriaRecord->topic=$request['topic'];
        $criteriaRecord->date=$request['date'];
        $criteriaRecord->total_score=$request['totalScore'];
        $criteriaRecord->class_record_id=$request['class_record_id'];
        $criteriaRecord->save();



        foreach ($request['score'] as $i => $score) {          

            $score = new Score;

            $score->criteria_record_id=$criteriaRecord->id;
            $score->student_id=$request['student_id'][$i];
            $score->score=strlen($request['score'][$i])==0 ? 'A' : $request['score'][$i];
            $score->save();

        }


        $this->message($request['criteria_name']." has been updated.");
        return redirect()->route("classrecord",$request['class_record_id'])->with("message","Record has been saved.");
    }


    public function getGrade($id){

        $get_id = DB::select("select * from class_record_pairs where (class_record_id_mid='$id' or class_record_id_final='$id') and teacher_id='".Util::get_session('teacher_id')."' ");

        $this->criteriaList($get_id[0]->class_record_id_mid);
        $this->criteriaList2($get_id[0]->class_record_id_final);
        
        $this->data['student'] = $this->classStudentList($id);
        $this->classRecordDetail($id);
        $this->data['class_record_id'] = $id;
        $this->data['page_title'] = "grade";
        $this->data['navigation']='Records / Semestral Grade';
        return view('teacher::ClassRecord.main',$this->data);
    }

    public function classRecordPrint($id){

        $get_id = DB::select("select * from class_record_pairs where (class_record_id_mid='$id' or class_record_id_final='$id') and teacher_id='".Util::get_session('teacher_id')."' ");

        $this->criteriaList($get_id[0]->class_record_id_mid);
        $this->criteriaList2($get_id[0]->class_record_id_final);
        
        $this->data['student'] = $this->classStudentList($id);
        $this->classRecordDetail($id);
        $this->data['page_title'] = "grade";
        $this->data['navigation']='Records / Semestral Grade';
        return view('teacher::ClassRecord.printclassrecord',$this->data);
    }

    public function classRecordPrint_term($id){
        $this->getClassRecord($id);
        return view("teacher::ClassRecord.printclassrecord_term",$this->data);
    }

    public function criteriaList2($id){
        $final_id = ClassRecordPair::where('class_record_id_final',$id)->where('teacher_id',Util::get_session('teacher_id'))->get();
        
        $criteria = Criteria::where('class_record_id', $final_id[0]->class_record_id_final)->get();
        $this->data['criteria2'] = $criteria;
    }

    static public function criteriaRecord($id,$c_id)
    {
        $record = CriteriaRecord::where('criteria_id',$id)->where('class_record_id',$c_id)->orderBy('date')->get();
        return $record;
    }

    static public function getTotalScoreExam($class_record_id,$exam_id=false)
    {
        
        //get examination that sets to this class record
        if(!$exam_id){
            $examination_id=DB::table("class_record_exams")->where("class_record_id",$class_record_id)->get(['examination_id']);            
            $examination_id = $examination_id[0]->examination_id;            
        }else{
            $examination_id = $exam_id;            
        }

        if(count($examination_id)>0 || $examination_id>0){
            
            $question_count =DB::select("select count(questions.id) as question_count from examinations,exam_parts,questions where examinations.id=exam_parts.examination_id and examinations.id='".$examination_id."' and questions.exam_part_id=exam_parts.id and exam_parts.exam_type!='ess'");
            
            // get total score of essay
            $cout_essay=DB::select("select sum(points.point) as count_points from examinations,exam_parts,questions,points where points.question_id=questions.id and  examinations.id=exam_parts.examination_id and examinations.id='".$examination_id."' and questions.exam_part_id=exam_parts.id and exam_parts.exam_type='ess'");
           
            return $question_count[0]->question_count+$cout_essay[0]->count_points;
        }else{
            return 0;
        }

    }

    static public function criteriaRecord2($id,$c_id)
    {
        $record = CriteriaRecord::where('criteria_id',$id)->where('class_record_id',$c_id)->orderBy('date')->get();
        return $record;
    }

    static function score($record_id,$student_id){
        $score = Score::where('criteria_record_id',$record_id)->where('student_id',$student_id)->get();
        return $score;
    }

    
    function classRecordDetail($id){
        $record = ClassRecord::where('id', $id)->where('teacher_id',Session::get('teacher_id'))->get();        
        $this->data['detail'] = $record;

    }

    function classStudentList($id){
       return $student = DB::select("select *, students.password as password from students,student_records,class_records,users where users.account_id=students.id and users.role='Student' and   students.id=student_records.student_id and student_records.class_record_id=class_records.id and class_records.id='$id' and class_records.teacher_id='".Util::get_session('teacher_id')."' order by stud_lname,stud_fname asc");
       
    }

    function storeUpdateScore(Request $request){
        if($request['actionType']=="update"){
            
            $type = Db::select("select criteria from criteria_records cr join criterias c on c.id=cr.criteria_id and cr.id=".$request['criteria_record_id']);
            $previous_score = Db::select("select  score from scores where criteria_record_id=".$request['criteria_record_id']);
            
            $student = Student::where('id',$request['student_id'])->get(['stud_fname','stud_lname']);
            $stud_name = $student[0]->stud_fname." ".$student[0]->stud_lname;

            $this->saveLog('Update '.$type[0]->criteria.' score from '.$previous_score[0]->score.' to '.$request['score'].' of '.$stud_name,'Update Score',Auth::id(),$request['student_id']);

            Score::where('id', $request['score_id'])->where('student_id',$request['student_id'])->where('criteria_record_id',$request['criteria_record_id'])->update(['score' => $request['score'],'is_manual_score'=>1]);


        }else{
            $score = new Score;
            $score->criteria_record_id=$request['criteria_record_id'];
            $score->student_id=$request['student_id'];
            $score->score=$request['score'];
            $score->is_manual_score=1;
            $score->save();
        }
 
    }


    // EXAMINATION FUNCTIONS

    static public function countExam($class_record_id)
    {
        $record_id=DB::select("select * from class_record_pairs where class_record_id_mid='$class_record_id' or class_record_id_final='$class_record_id'");
        $class_record_type = Util::get_session('class_record_type');
        $class_record_id = $record_id[0]->class_record_id_mid;
        if($class_record_type == 'Final'){
            $class_record_id = $record_id[0]->class_record_id_final;
        }

        return DB::table('class_record_exams')->where('class_record_id',$class_record_id)->count();        
    }

    public function classRecordExam($class_record_id)
    {
        
        $record_id = ClassRecordPair::where('class_record_id_mid',$class_record_id)->orWhere('class_record_id_final',$class_record_id)->get();

        $class_record_type = Util::get_session('class_record_type');
        $class_record_id = $record_id[0]->class_record_id_mid;

        if($class_record_type == 'Final'){
            $class_record_id = $record_id[0]->class_record_id_final;
        }

        $detail=DB::table('class_record_exams')->where('class_record_id',$class_record_id)->get();

        $exam_id = array();
        $record_id_list = array();
        foreach ($detail as $id) {
           array_push($exam_id, $id->examination_id);
           array_push($record_id_list, $id->class_record_id);
        }

        $exam = Examination::whereIn('id',$exam_id)->get();
 
        $duration = ClassRecord::whereIn('id',$record_id_list)->get();
        $this->data['examination']=$exam;
        $this->data['exam_detail']=$detail;
        $this->data['duration']=$duration;


        $this->classRecordDetail($class_record_id);
        $this->data['page_title'] = "examlist";
        $this->data['navigation']='Examination';

        return view('teacher::ClassRecord.main',$this->data);

    }

    function updateExaminationVisibility(Request $req){
        DB::table('class_record_exams')->where('examination_id',$req['examination_id'])->where('class_record_id',$req['class_record_id'])->
        update([
            'visibility'=>$req['visibility']
            ]);

        //get examination detail exam type
        $examination_detail = DB::table('examinations')->where('id',$req['examination_id'])->get(['exam_type']);
        if($examination_detail[0]->exam_type==0){
            //get quiz critera id
            $criteria_id = Criteria::where('class_record_id',$req['class_record_id'])->whereIn('criteria',['Quiz'])->get(['id']);
          
        }else{
            //get midterm/final exam id
            $criteria_id = Criteria::where('class_record_id',$req['class_record_id'])->whereIn('criteria',['Midterm Exam','Final Exam'])->get([ 'id']);
        }
        // var_dump($criteria_id[0]->id);exit;
        
        //get criteria record id
       $criteria_record = CriteriaRecord::where('criteria_id',$criteria_id[0]->id)->where('exam_id',$req['examination_id'])->get(['id']);

       //get examination id
       $exam_id = DB::table('class_record_exams')->where('class_record_id',$req['class_record_id'])->where('examination_id',$req['examination_id'])->get(['examination_id']);

       if(count($criteria_record)>0){
            if($req['visibility']==1){    
                $overall_score=0;               
               
                // get parts of examination
               $exam_part = DB::table('exam_parts')->where('examination_id',$exam_id[0]->examination_id)->get(['id','exam_type']);


                // loop for exam parts
                $totalScore = 0;
                foreach ($exam_part as $key => $part) {
                    if($part->exam_type=="ess"){
                        $overall_score+=$this->getNumberItems_ess($exam_id[0]->examination_id,$part->id);                       
                    }else{
                        $overall_score+=$this->getNumberItems($exam_id[0]->examination_id,$part->id);                       
                    }
                }    
               CriteriaRecord::where(['criteria_id'=> $criteria_id[0]->id])->where('exam_id',$req['examination_id'])->
                update([
                        'criteria_id'=> $criteria_id[0]->id,
                        'total_score'=> $overall_score,
                        'class_record_id'=> $req['class_record_id'],
                        'date'=> date('Y-m-d')
                    ]);
            }

       }else{
            $overall_score=0;

            // get parts of examination
           $exam_part = DB::table('exam_parts')->where('examination_id',$exam_id[0]->examination_id)->get(['id','exam_type']);


            // loop for exam parts
            $totalScore = 0;
            foreach ($exam_part as $key => $part) {
                if($part->exam_type=="ess"){
                    $overall_score+=$this->getNumberItems_ess($exam_id[0]->examination_id,$part->id);                       
                }else{
                    $overall_score+=$this->getNumberItems($exam_id[0]->examination_id,$part->id);                       
                }
            }

            // echo $overall_score;exit;
          
            $c_record = new CriteriaRecord;

            $c_record->criteria_id=$criteria_id[0]->id;
            $c_record->date=date('Y-m-d');
            $c_record->total_score=$overall_score;
            $c_record->exam_id=$req['examination_id'];
            $c_record->topic="Automated Quiz";
            $c_record->class_record_id=$req['class_record_id'];
            $c_record->save();

       }

    }



    public function lockExam(Request $req)
    {
        DB::table("class_record_exams")->where('class_record_id',$req['class_record_id'])->where('examination_id',$req['examination_id'])->
        update([
            'lock_exam'=>$req['lock_exam']
            ]);

        $student=StudentRecord::where('class_record_id',$req['class_record_id'])->pluck('student_id')->toArray();

        DB::table("student_exams")->where('examination_id',$req['examination_id'])->whereIn('student_id',$student)->
        update([
            'submitted'=>$req['lock_exam']==1? "true":""
            ]);
    }

    public function pause(Request $req)
    {
        if($req['pause']==0){
            return;     // do nothing
        }

        $class_record_exams = DB::table("class_record_exams")->where('class_record_id',$req['class_record_id'])->where('examination_id',$req['examination_id']);
        $record = $class_record_exams->get();
        
        $pause_time = date('Y-m-d H:i:s');
       
        $student=StudentRecord::where('class_record_id',$req['class_record_id'])->pluck('student_id')->toArray();
        $previous_pause_time = $record[0]->pause_time;

        if($previous_pause_time!=""){
            // exam was previsously paused
            
            //get all student exams which are not resumed prior to second pause of exam. e.g. student is absent during the resume of exam
            $unresume_exam = DB::table("student_exams")->where('examination_id',$req['examination_id'])->whereIn('student_id',$student)->where('is_resumed',0)->where("submitted","")->get();
            
            foreach ($unresume_exam as $key => $value) {
                // get the difference between previous paused tinme and  end time
                $time_remaining = strtotime($value->end_time) - strtotime($previous_pause_time);
                $new_end_time = date('Y-m-d H:i:s',strtotime('+ '.$time_remaining.' seconds',strtotime($pause_time)));
                DB::table("student_exams")->where("id",$value->id)->update([
                    "end_time" => $new_end_time
                ]);
            }
            
        }

        // set all resumed exam to unresumed to indicate that this exam was not resume for the pause time. Once the stud take the exam again exam will be marked as resume
        DB::table("student_exams")->where('examination_id',$req['examination_id'])->whereIn('student_id',$student)->where('is_resumed',1)->where("submitted","")->
        update([
            'is_resumed'=>0
            ]);

        $class_record_exams->update([
            'visibility'=>0,
            'pause_time'=>$pause_time
            ]);

    }

    public function savePoints(Request $req)
    {
        // return $req;
          return $choice= \Modules\Examination\Entities\LongAnswer::updateOrCreate(['student_id' => $req['student_id'],'question_id' => $req['question_id']],
        ['score' => $req['score']==''? '0':$req['score']
        ]);
    }

    public function getNumberItems($exam_id,$part_id){
         return DB::table('questions')->where('exam_part_id',$part_id)->where('examination_id',$exam_id)->count();    

    }

    public function getNumberItems_ess($exam_id,$part_id){

        $question = DB::table('questions')->where('exam_part_id',$part_id)->where('examination_id',$exam_id)->get();

        $question_id = "";
        foreach ($question as $question) {
            $question_id.=$question->id.",";
        }
        $question_id.="0";
         $sum = DB::select("select sum(point) as total from points where question_id in ($question_id)");    
         return $sum[0]->total;

    }
    // END EXAMINATION

    static public function examPart($exam_id)
    {
        return  DB::table('exam_parts')->where('examination_id',$exam_id)->get();
    }
    //student functions

    public function getCourse()
    {
        $course = Course::all();
        $this->data['course'] = $course;
    }

    function message($message){
        $this->data['message'] = $message;
    }

    function password(){
        $this->data['password'] = Util::randPassword();
    }



    function uploadStudent($id){
        $this->getCourse();
        $this->message(null);        
        $this->password();        
        $this->classRecordDetail($id);
        $this->data['page_title'] = "uploadStudent";
        $this->data['navigation']='Student / Add Student / Upload Student List';
        return view('teacher::ClassRecord.main',$this->data);
    }

    public function storeUploadStudent(Request $request)
    {

        if($request['file']->getClientMimeType()!='text/html'){
            return redirect()->back()->withErrors("Invalid file. The file updloaded is not .HTML extention.");
        }

        $line = 0;
        $myfile = fopen($request['file']->getRealPath(), "r") or die("Unable to open file!");
        
        $number_student = 0;
        $check_file=0;
        while(!feof($myfile)) {
            $line++;
            $data = fgets($myfile);
            
            if($line==115 or $line==233){
                $check_file=1;
                $datarow = explode("<tr>", $data);

                foreach ($datarow as $key => $tr) {
                    $celldata = explode("</td>", $tr);

                    if(count($celldata)==9){

                        // foreach ($celldata as $key => $cell) {
                        //     echo $line." ---- ". $key."-".htmlspecialchars($cell)."<BR>";
                        // }
                        // echo "<br><br>";
                            // echo "stud_num: ";
                            $stud_num=htmlspecialchars(substr($celldata[1], 4));
                            // echo "name: ";

                            $name = substr($celldata[2], 4);
                            $clean_name = str_ireplace("<td>", "", $name);
                            $clean_name = str_ireplace("<b>", "", $clean_name);
                            $clean_name = str_ireplace("</b>", "", $clean_name);

                            $name =$clean_name;
                            $split_name = explode(",", $name);

                            $stud_fname =$split_name[1];
                            $stud_lname =$split_name[0];

                            // echo "firstname : ".$first_name." last_name: ".$last_name."<br>";


                            // echo "course: ";
                            $course_code=htmlspecialchars(substr($celldata[4], 4));

                             // echo "year: ";
                            $year=htmlspecialchars(substr($celldata[5],19));


                             $course = Course::where("course_code",$course_code)->get();

                             $year_level = array("I","II","III","IV");

                             $course_id=0;
                             if(count($course)>0){
                                $course_id=$course[0]->id;
                             }
                            // initialize variables
                             $request['stud_num']=$stud_num;
                             $request['password']= Util::randPassword();
                             $request['stud_fname']=$stud_fname;
                             $request['stud_lname']=$stud_lname;
                             $request['course_id']=$course_id;
                             $request['stud_address']=" Not set ";
                             $request['stud_contact_num']=" Not set ";
                             $request['year']=$year_level[$year-1]; 

                             /////////////////////////////// add student
                            $check_studentnum= Student::where('stud_num',$request['stud_num'])->get();

                            $student_id=0;
                            $checkstudentExist=0;

                            if (count($check_studentnum)==0) {
                                
                                $student = new Student;
                                $student->stud_num=$request['stud_num'];
                                $student->password=$request['password'];
                                $student->stud_fname=$request['stud_fname'];
                                $student->stud_lname=$request['stud_lname'];
                                $student->stud_address=$request['stud_address'];
                                $student->stud_contact_num=$request['stud_contact_num'];
                                $student->course_id=$request['course_id'];
                                $student->year=$request['year'];
                                $student->save();

                                $user = new User;
                                $user->name=$request['stud_fname']." ".$request['stud_lname'];
                                $user->username=$request['stud_num'];
                                $user->password=bcrypt($request['password']);
                                $user->role='Student';
                                $user->account_id=$student->id;
                                $user->save();


                                $student_id=$student->id;

                            }else{
                                $student_id=$check_studentnum[0]->id;
                                $checkstudentExist=1;
                                
                            }

                            // check student number if already existed in class record
                            $check_studentnum2= StudentRecord::where('student_id',$student_id)->where('class_record_id',$request['class_record_id'])->count();
                          
                            $checkstudentExistClass=0;
                            
                            if($check_studentnum2==0){
                                StudentRecord::create(['student_id'=>$student_id,'class_record_id'=>$request['class_record_id']]);
                                $checktype = ClassRecordPair::where('class_record_id_mid',$request['class_record_id'])->orWhere('class_record_id_final',$request['class_record_id'])->where('teacher_id',Util::get_session('teacher_id'))->get();

                                if(Util::get_session('class_record_type')=="Midterm"){
                                    StudentRecord::create(['student_id'=>$student_id,'class_record_id'=>$checktype[0]->class_record_id_final]);
                                 }else{
                                    StudentRecord::create(['student_id'=>$student_id,'class_record_id'=>$checktype[0]->class_record_id_mid]);
                                 }    
                                $number_student++;
                            }else{
                                $checkstudentExistClass=1;            
                            }

                             //////end add student
                    } 

                }

             }

        }        
        fclose($myfile);

        if($check_file==0){
            return redirect()->back()->withErrors("Invalid file. Make sure the uploaded file is an official list download from the system of registrar.");
        }
            
        return redirect()->route('studentList',$request['class_record_id'])->with('message',"<b>$number_student</b> Students have been added.");
    }


    function getAddStudent($id){
        $this->getCourse();
        $this->message(null);        
        $this->password();        
        $this->classRecordDetail($id);
        $this->data['page_title'] = "createStudent";
        $this->data['navigation']='Student / Add Student';
        return view('teacher::ClassRecord.main',$this->data);
    }

    static public function getCourseCode($id)
    {
        $course= DB::table("courses")->where('id',$id)->pluck("course_code")->toArray();
       
        if(count($course)>0){
            return $course[0];            
        }
        return "Not set";
    }

    function studentList($id){
        $this->message(null);        
        $this->classRecordDetail($id);
        $this->data['student_list'] = $this->classStudentList($id);
        $this->data['navigation']='Student / List';
        if(count($this->data['student_list'])==0){
            return  redirect()->route('getAddStudent',$id);
        
        }
        $this->data['page_title'] = "studentList"; 
        return view('teacher::ClassRecord.main',$this->data);
    }


    function storeStudent(Request $request){

        $check=$this->validate(request(),[
             'stud_num' =>'required|integer',
            'stud_fname' =>'required',
            'stud_lname' =>'required',
            'stud_address' =>'required',    
            'password' =>'required|min:6',
            'class_record_id' =>'required',
            'stud_contact_num' =>'size:11|nullable'
        ],
         [
         'stud_num.required'=>"Student number is required",
         'stud_fname.required' =>'Student first name is required',
         'stud_lname.required' =>'Student last name is required',
         'stud_address.required' =>'Student Address is required',
         'stud_contact_num.size' =>'Student contact number must be 11 digits'
        ]);

        //check student if already exist in the same subject code

       $sub_code = ClassRecord::where("id",$request['class_record_id'])->get(['sub_code','sy','semester','type']);

       $checkRecord = DB::select("select name from students,student_records,class_records,users where users.account_id=class_records.teacher_id and  students.stud_num='$request[stud_num]' and  students.id=student_records.student_id and student_records.class_record_id=class_records.id and class_records.sub_code='".$sub_code[0]->sub_code."' and sy='".$sub_code[0]->sy."' and semester='".$sub_code[0]->semester."' and type='".$sub_code[0]->type."' ");

        if(count( $checkRecord)>0){
            return redirect()->back()->withErrors("You are trying to add a student who is already enrolled in the same subject in the class of ".$checkRecord[0]->name);
        }
        $check_studentnum= Student::where('stud_num',$request['stud_num'])->get();

        $student_id=0;
        $checkstudentExist=0;

        if (count($check_studentnum)==0) {
            
            $student = new Student;
            $student->stud_num=$request['stud_num'];
            $student->password=$request['password'];
            $student->stud_fname=$request['stud_fname'];
            $student->stud_lname=$request['stud_lname'];
            $student->stud_address=$request['stud_address'];
            $student->stud_contact_num=$request['stud_contact_num'];
            $student->course_id=$request['course_id'];
            $student->year=$request['year'];
            $student->save();

            \App\User::insert(['name'=>$request['stud_fname']." ".$request['stud_lname'],'username'=>$request['stud_num'],'password'=>bcrypt($request['password']),'role'=>'Student', 'account_id'=>$student->id]);
            $student_id=$student->id;
        }else{
            $student_id=$check_studentnum[0]->id;
            $checkstudentExist=1;
            
        }

        // check student number if already existed in class record
        $check_studentnum2= StudentRecord::where('student_id',$student_id)->where('class_record_id',$request['class_record_id'])->count();
      
        $checkstudentExistClass=0;
        
        if($check_studentnum2==0){
            StudentRecord::create(['student_id'=>$student_id,'class_record_id'=>$request['class_record_id']]);
            $checktype = ClassRecordPair::where('class_record_id_mid',$request['class_record_id'])->orWhere('class_record_id_final',$request['class_record_id'])->where('teacher_id',Util::get_session('teacher_id'))->get();

            if(Util::get_session('class_record_type')=="Midterm"){
                StudentRecord::create(['student_id'=>$student_id,'class_record_id'=>$checktype[0]->class_record_id_final]);
             }else{
                StudentRecord::create(['student_id'=>$student_id,'class_record_id'=>$checktype[0]->class_record_id_mid]);
             }    
        
        }else{
            $checkstudentExistClass=1;            

        }
        

        $this->data['page_title'] = "studentList";
        $this->classRecordDetail($request['class_record_id']);
        $this->message("Student has been added.");
        $this->data['student_list'] = $this->classStudentList($request['class_record_id']);
        $this->data['navigation']='Student / List';
        
        
 			if($checkstudentExist==1){

 				

	            if($checkstudentExistClass==1){
	                return redirect()->route('studentList',$request['class_record_id'])->with('warning',"Student number ".$request['stud_num']." is already assigned to <b>".$check_studentnum[0]->stud_lname.", ".$check_studentnum[0]->stud_fname."</b> who is already found in the class record.");
	            }else{

	            	if($request['selected']=="true"){
	 					return redirect()->route('studentList',$request['class_record_id'])->with('message',"Student has been added.");
	 				}

	                return redirect()->route('studentList',$request['class_record_id'])->with('warning',"Student number ".$request['stud_num']." is already assigned to an existing student, <b>".$check_studentnum[0]->stud_lname.", ".$check_studentnum[0]->stud_fname."</b>. No new student is created. ".$check_studentnum[0]->stud_lname.", ".$check_studentnum[0]->stud_fname." is added to the class record.");
	            }

	        }else{
	            return redirect()->back()->with('message',"Student has been added.");

	        }
        
     }

     function searchStudent(Request $req)
     {
     	$student =  Student::join("courses","students.course_id","=","courses.id")->where('stud_num',"like",$req['search']."%")->limit(5)->orderBy('stud_lname')->get();

     	if(count($student)>0){

	     	foreach ($student as $student) {
	     		?>
     			<tr onclick="chooseStudent('<?php echo $student->stud_num;?>','<?php echo $student->stud_fname;?>','<?php echo $student->stud_lname;?>','<?php echo $student->stud_address;?>','<?php echo $student->stud_contact_num;?>','<?php echo $student->course_id;?>','<?php echo $student->year;?>')">
	    			<td><?php echo $student->stud_num;?></td>
	    			<td><?php echo $student->stud_lname;?>, <?php echo $student->stud_fname;?></td>
 	    			<td><?php echo $student->course_code;?>-<?php echo $student->year;?></td>
	    		</tr>

	     		<?php
	     	}
	     }else{
	     	echo "
	     		<tr>
	    			<td align='center'>No result</td>
	    		</tr>
	     	";
	     }
     }

     public function updateStudent($class_record,$student_id)
     {
        $this->classRecordDetail($class_record);
        $this->getCourse();
        $this->message(null); 

        $this->data['student']=DB::table('students')->where('id',$student_id)->get();       
        $this->data['navigation'] = "Student / Update Information"; 
        $this->data['page_title'] = "updatestudent"; 
        return view('teacher::ClassRecord.main',$this->data);     
     }

     public function storeUpdateStudent(Request $req)
     {

         $check=$this->validate(request(),[
            'stud_num' =>'required|integer',
            'stud_fname' =>'required',
            'stud_lname' =>'required',
            'stud_address' =>'required',    
            'password' =>'required|min:6'
        ],
         [
         'stud_num.required'=>"Student number is required",
         'stud_fname.required' =>'Student first name is required',
         'stud_lname.required' =>'Student last name is required',
         'stud_address.required' =>'Student Address is required'
        ]);

        $check= Student::where('stud_num',$req['stud_num'])->where('id',"!=",$req['student_id'])->get();

        if(count($check)>0){
            return redirect()->back()->withErrors(['errors'=>"Student number is already assigned to <b>".$check[0]->stud_lname.", ".$check[0]->stud_fname."</b>"]);            
        }

        $student=Student::find($req['student_id']);
        $student->stud_num=$req['stud_num'];
        $student->stud_fname=$req['stud_fname'];
        $student->stud_lname=$req['stud_lname'];
        $student->password=$req['password'];
        $student->stud_address=$req['stud_address'];
        $student->stud_contact_num=$req['stud_contact_num'];
        $student->course_id=$req['course_id'];
        $student->year=$req['year'];
        $student->save();

        
        $student=User::where("account_id",$req['student_id'])->where('role',"Student")->update([
               'username'=>$req['stud_num'],
               'name'=>$req['stud_fname']." ".$req['stud_lname'],
               'password'=>bcrypt($req['password'])
           ]);
        

        return redirect()->route('studentList',$req['class_record_id'])->with('message','Student information has been updated.');


     }
    public function deleteStudent($student_id)
    {
        $check=DB::select("select count(student_id) as countstudent from scores,criteria_records where scores.criteria_record_id=criteria_records.id and class_record_id='".Util::get_session('class_record_id')."' and student_id='$student_id'");
        
        if($check[0]->countstudent>0){
            return redirect()->route('studentList',Util::get_session('class_record_id'))->withErrors(['errors'=>"Student has records already. Can not be deleted."]);
        }


        StudentRecord::where('student_id',$student_id)->where('class_record_id',Util::get_session('class_record_id'))->delete();
        return redirect()->route('studentList',Util::get_session('class_record_id'))->with(['message'=>"Student has been deleted from the class record."]);
    }

      public function checkExam($exam_id,$type)
    {
        $exam = Examination::where("id",$exam_id)->where('teacher_id',Util::get_session('teacher_id'))->get();

        $part = DB::table('exam_parts')->where('examination_id',$exam_id)->where('exam_type',$type)->get();
        
        if(count($part)==0){
            return redirect()->back()->with('warning',"No Essay or Identifation is found.");
        }

        $this->data['examination']=$exam;
        $this->data['part']=$part;
 
        $this->data['navigation'] = "Check Answer";
        $this->data['page_title'] = "checkexam";
        return view('examination::layouts.master',$this->data);
    }

      public function storeStudentAnswer(Request $req)
    {
        

        foreach ($req['answer']as $answer) {
            $choice =new QuestionChoice;
            $choice->question_id=$req['question_id'];
            $choice->choice_desc=$answer;
            $choice->answer=1;
            $choice->save();

        }
        
        return redirect()->back()->with('message','Answer has been added.');
    }

    static public function studentAnwers($question_id)
    {
    
        $answer= DB::table('question_choices')->where('question_id',$question_id)->pluck('choice_desc')->toArray();
        $student = DB::table('student_records')->where('class_record_id',Util::get_session('class_record_id'))->pluck('student_id')->toArray();
        return DB::table('long_answers')->whereNotIn('answer',$answer)->whereIn('student_id',$student)->where('question_id',$question_id)->get();

    }

    // item analysis

        function dataAnalytics(){
            $classes = ClassRecord::where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->where('type',Util::get_session('class_record_type'))->get();
            $this->data['class'] = $classes;

            // $exam = DB::select("select * from examinations,class_record_exams,class_records where class_record_exams.class_record_id=class_records.id and examinations.id=class_record_exams.examination_id and class_records.teacher_id='".Util::get_session('teacher_id')."' group by examinations.id");
            // $this->data['exam']=$exam;

           //get examination id
            $this->data['main_page'] = "Data Analytics";
            $this->data['navigation'] = "Item Analysis";
            $this->data['page_title'] = "dataanalyticsindex";
            return view('teacher::ClassRecord.main',$this->data);
        }

        public function itemStatisticsIndex()
        {
            $exam = DB::select("select * from examinations,class_record_exams,class_records where class_record_exams.class_record_id=class_records.id and examinations.id=class_record_exams.examination_id and class_records.teacher_id='".Util::get_session('teacher_id')."' group by examinations.id");
            $this->data['exam']=$exam;

            // $classes = ClassRecord::where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->where('type',Util::get_session('class_record_type'))->get();
            // $this->data['class'] = $classes;

           //get examination id
            $this->data['main_page'] = "Data Analytics";
            $this->data['navigation'] = "Data Statistics";
            $this->data['page_title'] = "itemstatisticsindex";
            return view('teacher::ClassRecord.main',$this->data);
        }


        static public function examClassRecord($examination_id)
        {
            return  DB::select("select * from class_record_exams,class_records where class_records.id=class_record_exams.class_record_id and class_record_exams.examination_id='$examination_id' order by day,time");
        }

        public function itemAnalysis($class , $is_all = false)
        {

            

           $classes = ClassRecord::where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->where('type',Util::get_session('class_record_type'))->get();
           $this->data['class'] = $classes;
           $all_classess = "";
           Util::set_session('All',$is_all);
           if($is_all=="All"){
                // get all class records that share the has same examinations with selected class record $class
                 $all_classess = DB::select("select cr.class_record_id,cr.examination_id from class_record_exams cr join (
                                    select cr.examination_id from class_record_exams cr join examinations ex on ex.id=cr.examination_id
                                    where cr.class_record_id = ".$class." and ex.exam_type=1 
                                ) subtable on cr.examination_id = subtable.examination_id");

                $class  = array();

                foreach ($all_classess as $classes) {
                    array_push($class, $classes->class_record_id);
                }

           }else{
                $class  = array($class);
           }

           $count_student = StudentRecord::whereIn('class_record_id',$class)->count();
           
           if($count_student==0){
            return redirect()->back()->withErrors("No Student found in the class record. Cannot generate  item analysis.");
           }

           $count_student = round($count_student/2);

            $student_list = DB::select("select * from students,student_records where students.id=student_records.student_id and class_record_id in (".implode(",",$class).") order by stud_lname,stud_fname asc limit ".$count_student);
            
            $student_id ="";
            foreach ($student_list as $key => $student) {
                $student_id.="$student->student_id,";
            }

            $student_id.="0";

            $student_list2 = DB::select("select * from students,student_records where students.id=student_records.student_id and class_record_id in (".implode(",",$class).")  and student_id not in ($student_id) order by stud_lname,stud_fname asc limit ".$count_student);
           
            //get questions

           //get examination id
           if($is_all=="All"){
                $exam_id = $all_classess;
           }else{
                $exam_id = DB::table('class_record_exams')->whereIn('class_record_id',$class)->get(['examination_id']);
           }

           if(count($exam_id)==0){
            return redirect()->back()->with("warning","No examination created in  the selected class record.");
           }
            // get parts of examination
           $exam_part = DB::table('exam_parts')->where('examination_id',$exam_id[0]->examination_id)->whereIn('exam_type',['mul','mat','tru'])->orderBy('id','asc')->pluck('id')->toArray();
          
           $question = DB::table('questions')->whereIn('exam_part_id',$exam_part)->where('examination_id',$exam_id[0]->examination_id)->orderBy('exam_part_id','asc')->orderBy('id','asc')->get(['id','exam_part_id']);
            $this->data['question'] = $question;
            
           $exam_part = DB::table('exam_parts')->where('examination_id',$exam_id[0]->examination_id)->whereIn('exam_type',['mul','mat','tru'])->orderBy('id','asc')->get(['id','exam_type']);
            $this->data['exam_part'] = $exam_part;

            $this->data['main_page'] = "Data Analytics";
            $this->data['student1'] = $student_list;
            $this->data['student2'] = $student_list2;
            $this->data['selected_class'] = $class;
            $this->data['navigation'] = "Item Analysis";
            $this->data['page_title'] = "dataanalytics";
            return view('teacher::ClassRecord.main',$this->data);
        }

       static public function studentAnswer($question_id,$student_id){
            return DB::select(" select student_answers.answer as student_answer,question_choices.answer as question_answer,question_choices.id as choice_id from student_answers,question_choices where student_answers.question_id=question_choices.question_id and student_answers.question_id='$question_id' and student_id='$student_id' order by question_choices.id asc");
        }

        static public function studentAnswer_mat($question_id,$student_id){
            return DB::select("select answer as student_answer from student_answers where question_id='$question_id' and student_id='$student_id'");
        }

        static public function getChoices_mat($part_id){
            return DB::select("select question_choices.id as choice_id, question_choices.question_id as question_id,answer from questions,question_choices where questions.id=question_choices.question_id and exam_part_id='$part_id' order by question_choices.id asc");
        }

        //statistics

        function itemStatistics($exam_id){

            $exam = DB::select("select * from examinations,class_record_exams,class_records where class_record_exams.class_record_id=class_records.id and examinations.id=class_record_exams.examination_id and class_records.teacher_id='".Util::get_session('teacher_id')."'  group by examinations.id");
            $this->data['exam']=$exam;

            $classes = ClassRecord::where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->where('type',Util::get_session('class_record_type'))->get();
            $this->data['class'] = $classes;

          
            // get parts of examination
           $exam_part = DB::table('exam_parts')->where('examination_id',$exam_id)->whereIn('exam_type',['mul','mat','tru'])->orderBy('id','asc')->pluck('id')->toArray();
           
           $question = DB::table('questions')->whereIn('exam_part_id',$exam_part)->where('examination_id',$exam_id)->orderBy('exam_part_id','asc')->orderBy('id','asc')->get(['id','exam_part_id','question']);
            $this->data['question'] = $question;
            $this->data['selected_exam'] = $exam_id;

           //count students

           $student_count = DB::select("select count(student_records.student_id) as countstudent from student_records,class_record_exams,student_exams where student_exams.student_id=student_records.student_id and class_record_exams.examination_id=student_exams.examination_id and student_records.class_record_id=class_record_exams.class_record_id and class_record_exams.examination_id='$exam_id'");
           if($student_count[0]->countstudent==0){
               return redirect()->back()->withErrors("No Student found in the class record. Cannot generate item analysis.");
           }
           
            $this->data['main_page'] = "Data Analytics";
            $this->data['student_count'] = $student_count[0]->countstudent;
            $this->data['navigation'] = "Data Statistics";
            $this->data['page_title'] = "itemstatisticsresult";
            return view('teacher::ClassRecord.main',$this->data);
        }

        public function statisticsGraphPrint($examination_id)
        {
            $this->itemStatistics($examination_id);
            $this->data['main_page'] = "Data Analytics";
            $this->data['page_title'] = "itemstatisticsresultprint";
            return view('teacher::ClassRecord.Analytics.itemstatisticsresultprint',$this->data);
        }

         public function statisticsTestPaperPrint($examination_id)
        {
            $questionArray = Util::get_session('questionArray');
            
            $this->data['questionArray']=$questionArray;
            $this->examDetail($examination_id);

            $this->itemStatistics($examination_id);
            $this->data['main_page'] = "Data Analytics";

            return view('teacher::ClassRecord.Analytics.itemstatisticsresulttestpaperprint',$this->data);
        }


        public function examDetail($exam_id){
            $exam = Examination::where("id",$exam_id)->where('teacher_id',Util::get_session('teacher_id'))->get();

            $part = ExamPart::where('examination_id',$exam_id)->get();

            $this->numberQuestion($exam_id);
            $this->numberQuestion_part($part[0]->id);
            $this->data['examination']=$exam;
            $this->data['part']=$part;
            $this->data['class_list']=$this->classList($exam_id);

            $this->data['navigation'] = "Show";
            $this->data['page_title'] = "showexamination";
        }

        public function numberQuestion($id){
            $this->data['number_question']=Question::where('examination_id',$id)->count();
        }

        public function numberQuestion_part($id){
          $this->data['number_question_part']=Question::where('exam_part_id',$id)->count();
        }

        static public function classList($exam_id){
            $class=DB::select("select * from class_record_exams,class_records where class_record_exams.class_record_id=class_records.id and examination_id='$exam_id'");
            
            return $class;        
        }

        static public function difficulty($question_id)
        {
            $dif=DB::select("select count(student_answers.student_id) as countanswer from student_answers,question_choices where student_answers.question_id=question_choices.question_id and question_choices.answer=1 and student_answers.answer=question_choices.id and question_choices.question_id='$question_id'");
            return $dif[0]->countanswer;
        }
        static public function discrimination($question_id,$count)
        {  
            $limit=round($count/2);
            $studentlist1=DB::select("select *  from student_answers,question_choices where student_answers.question_id=question_choices.question_id and question_choices.answer=1 and student_answers.answer=question_choices.id and question_choices.question_id='$question_id' order by student_answers.student_id asc limit ".$limit);

            $top_correct_answer=count($studentlist1);

            $student_id_list="";
            foreach ($studentlist1 as $student) {
                $student_id_list.=$student->student_id.",";
            }
            $student_id_list.="0";

            $studentlist2=DB::select("select *  from student_answers,question_choices where student_answers.question_id=question_choices.question_id and question_choices.answer=1 and student_answers.answer=question_choices.id and question_choices.question_id='$question_id' and student_id not in ($student_id_list) order by student_answers.student_id asc limit ".$limit);
            
            $botom_correct_answer=count($studentlist2);

            $student_id_list.=" / ";

            foreach ($studentlist2 as $student) {
                $student_id_list.=$student->student_id.",";
            }
            $student_id_list.="0";

            return $discrimination = ($top_correct_answer-$botom_correct_answer)/($count/2);

            // return $student_id_list." || ".$top_correct_answer."-".$botom_correct_answer."=".$discrimination;
        }

        //item analysis print

        function analysisResultPrint($class,$resultType){
            $classes = ClassRecord::where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->where('type',Util::get_session('class_record_type'))->get();
            $this->data['class'] = $classes;

            $is_all = Util::get_session('All');

            if($is_all=="All"){
                // get all class records that share the has same examinations with selected class record $class
                 $all_classess = DB::select("select cr.class_record_id,cr.examination_id from class_record_exams cr join (
                                    select cr.examination_id from class_record_exams cr join examinations ex on ex.id=cr.examination_id
                                    where cr.class_record_id = ".$class." and ex.exam_type=1
                                ) subtable on cr.examination_id = subtable.examination_id");

                $class  = array();

                foreach ($all_classess as $classes) {
                    array_push($class, $classes->class_record_id);
                }

           }else{
                $class  = array($class);
           }

           $count_student = StudentRecord::whereIn('class_record_id',$class)->count();
           

            $student_list = DB::select("select * from students,student_records where students.id=student_records.student_id and class_record_id in (".implode(",",$class).") order by stud_lname,stud_fname asc limit ".round($count_student/2));
            
            $student_id ="";
            foreach ($student_list as $key => $student) {
                $student_id.="$student->student_id,";
            }

            $student_id.="0";

            $student_list2 = DB::select("select * from students,student_records where students.id=student_records.student_id and class_record_id in (".implode(",",$class).") and student_id not in ($student_id) order by stud_lname,stud_fname asc limit ".round($count_student/2));
           
            //get questions

           //get examination id
           $exam_id = DB::table('class_record_exams')->whereIn('class_record_id',$class)->get(['examination_id']);
           
            // get parts of examination
           $exam_part = DB::table('exam_parts')->where('examination_id',$exam_id[0]->examination_id)->whereIn('exam_type',['mul','mat','tru'])->orderBy('id','asc')->pluck('id')->toArray();
           
           $question = DB::table('questions')->whereIn('exam_part_id',$exam_part)->where('examination_id',$exam_id[0]->examination_id)->orderBy('exam_part_id','asc')->orderBy('id','asc')->get(['id','exam_part_id']);
           $this->data['question'] = $question;

           $exam_part = DB::table('exam_parts')->where('examination_id',$exam_id[0]->examination_id)->whereIn('exam_type',['mul','mat','tru'])->orderBy('id','asc')->get(['id','exam_type']);
            $this->data['exam_part'] = $exam_part;
            $this->data['main_page'] = "Data Analytics";
            $this->data['student1'] = $student_list;
            $this->data['student2'] = $student_list2;
            $this->data['selected_class'] = $class;
            if($resultType=="analysis-data"){
                return view('teacher::ClassRecord.Analytics.analysisdataprint',$this->data);

            }else{
                return view('teacher::ClassRecord.Analytics.analysisdataresultprint',$this->data);
                
            }
        }

        // correct answeer

        static public function correcttAnswer_mul($question_id){
            return DB::select(" select question_choices.answer as question_answer,question_choices.id as choice_id from question_choices where question_id='$question_id' order by question_choices.id asc");
        }

        
    // end

    public function myAccount()
    {
        $exam = "DB::select()";
        $this->data['account']=$exam;

        $this->data['main_page'] = "My Account";
        $this->data['navigation'] = "Manage";
        $this->data['page_title'] = "myaccount";
        return view('teacher::ClassRecord.main',$this->data);
    }

     public function updateAccount(Request $req)
     {
        $this->validate(request(),[
            'name'=>'required',
            'current_password'=>'required',
            'username'=>'required|min:6',
            'cp_number'=>'required|digits:11',
        ]);

        $user = User::where('username',$req['username'])->where('id',"!=",Auth::user()->id)->count();
        if($user>0){
            return redirect()->back()->withErrors(['errors'=>"The username has already been taken."]);
           
        }
        if(!Hash::check($req['current_password'], Auth::user()->password)){
             return redirect()->back()->withErrors(["Current password is inccorrect."]);
        }

        $previous_detals = User::where('id',Auth::user()->id)->get(['username','name']);

        $update_detail = "";
        
        if($previous_detals[0]->username!=$req['username']){
            $update_detail.=" . Username is updated from ".$previous_detals[0]->username." to ".$req['username'];
        }

        if($previous_detals[0]->name!=$req['name']){
            $update_detail.=" . Name is updated from ".$previous_detals[0]->username." to ".$req['username'];
        }

        if(strlen($req['password'])>0 or strlen($req['password_confirmation'])>0){ 

            if($req['password']!=$req['password_confirmation']){
                return redirect()->back()->withErrors(['errors'=>"password confirmation does't match."]);
            }

            if(strlen($req['password'])<6){
                return redirect()->back()->withErrors(['errors'=>"New password must at least 8 characters."]);                
            }
            
            BaseController::sendSms(Auth::user()->cp_number,'You have updated your account. If you are not the one doing this, please contact admin immediatedly to reset your password.');

             User::where('id',Auth::user()->id)->update([
                'name'=>$req['name'],
                'username'=>$req['username'],
                'cp_number'=>$req['cp_number'],
                'password'=>bcrypt($req['password'])
            ]);

            

            $this->saveLog('Password is updated'.$update_detail,'Update Account',Auth::id(),Auth::id());


        }else{
             BaseController::sendSms(Auth::user()->cp_number,'You have updated your account. If you are not the one doing this, please contact admin immediatedly to reset your password.');
             User::where('id',Auth::user()->id)->update([
                'name'=>$req['name'],
                'username'=>$req['username'],
                'cp_number'=>$req['cp_number'],
            ]);
            

            $this->saveLog($update_detail,'Update Account',Auth::id(),Auth::id());
        }
        
        
        return redirect()->back()->with('message','Account has been updated.');
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
