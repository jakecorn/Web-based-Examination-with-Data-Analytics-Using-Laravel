<?php

namespace Modules\Examination\Http\Controllers;
error_reporting(0);
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Modules\Examination\Entities\Examination;
use Modules\Examination\Entities\ClassRecordExam;
use Modules\Examination\Entities\ExamPart;
use Modules\Examination\Entities\Question;
use Modules\Examination\Entities\QuestionChoice;
use Modules\Examination\Entities\LongAnswer;
use Modules\Teacher\Entities\ClassRecord;
use Modules\Examination\Entities\Point;
use Modules\Utilitize\Util;
use Illuminate\Support\Facades\Input;
use Session;
class ExaminationController extends Controller
{
    use ValidatesRequests;


    public function __construct(){
        $this->data['main_page'] = "Examination";
        $this->data['term'] = "Examination";
        $this->data['message'] = "";
        $this->data['type'] = array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");
    }

    public function index()
    {   


        $exam = DB::select("select * from examinations,class_record_exams,class_records where examinations.id=class_record_exams.examination_id and class_record_exams.class_record_id=class_records.id and class_records.teacher_id='".Util::get_session('teacher_id')."' and sy='".Util::get_session('sy')."' and semester='".Util::get_session('semester')."' and type='".Util::get_session('class_record_type')."' group by examinations.id");
        
        $this->data['examination_list'] = $exam;
        $this->data['navigation'] = "List";
        $this->data['page_title'] = "examlist";
        return view('examination::layouts.master',$this->data);
    }

    public function createExam(){

        $class_list = DB::table('class_records')->where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->where('type',Util::get_session('class_record_type'))->orderBy('day')->orderBy('time')->get();
        $this->data['class_list'] = $class_list;
        $this->data['navigation'] = "Create";
        $this->data['page_title'] = "examcreate";
        return view('examination::layouts.master',$this->data);
    }

    public function storeCreateExam(Request $req)
    {
        $this->validate(request(),[
            'gen_instruction' => 'required',
            'time_limit' => 'required',
            'exam_type' => 'required',
            'class' => 'required',
            'is_long_exam' => 'required',
            'exam_topic' => 'required',
            'exam_instruction' => 'required'
            ]);


        $exam = new Examination;

        $exam->teacher_id = Util::get_session('teacher_id');
        $exam->gen_instruction=$req['gen_instruction'];
        $exam->duration=$req['time_limit']=="yes"? $req['duration'] : 'None';
        $exam->exam_type=$req['is_long_exam'];
        $exam->save();

        $part_num=1;
        foreach ($req['exam_type'] as $i => $value) {
            
            $exam_part = new ExamPart;
            $exam_part->exam_type=$req['exam_type'][$i];
            $exam_part->examination_id=$exam->id;
            $exam_part->exam_topic=$req['exam_topic'][$i];
            $exam_part->exam_instruction=$req['exam_instruction'][$i];
            $exam_part->part_num=$part_num;
            $exam_part->save();
            $part_num++;

        }
        $class_record_not_included = ". ";
        foreach ($req['class'] as $id => $class_id) {

            if($req['is_long_exam'] == 1 || $req['is_long_exam'] == "1"){
                    // check if class record has long examination created before
                $check_record=DB::select("select cr.examination_id from class_record_exams cr join examinations ex on ex.id=cr.examination_id
                                    where cr.class_record_id = ".$class_id." and ex.exam_type=1");
                if(count($check_record)>0){
                    $class_record_not_included=" There is a class record which is not added in the examination. Please check...";
                    continue;               //dont save the exam since the class record has already an existed examination. Only 1 Long examination per class record to avoid conflict in data anayltics
                }

            }

            $class = new ClassRecordExam;
            $class->class_record_id=$class_id;
            $class->examination_id=$exam->id;
            $class->save();

        }

        $this->data['navigation'] = "List";
        $this->data['page_title'] = "examlist";
        $this->data['message'] = "Examination has been created successfully".$class_record_not_included;
        return redirect()->route('showexam',$exam->id)->with("message","Examination has been created".$class_record_not_included);

    }

    public function deleteExam($exam_id){
       $check = DB::table('student_exams')->where('examination_id',$exam_id)->count();

        
       if($check>0){
            return redirect()->route('index')->withErrors(["errors"=>"Sorry, there is a student who already took this exam. Cannot be deleted."]);
       }

       $check = Examination::where('id',$exam_id)->where('teacher_id',Util::get_session('teacher_id'))->delete();
       exit;
       if($check==1){        
            ExamPart::where('examination_id',$exam_id)->delete();
            // DB::statement("delete from question_choices where question_choices.question_id in (select question_id from questions where examination_id='$exam_id')");

              DB::table('question_choices')
            ->join('questions', 'question_choices.question_id',"=",'questions.id')
            ->where("examination_id",$exam_id)
            ->delete();
            Question::where('examination_id',$exam_id)->delete();

            ClassRecordExam::where('examination_id',$exam_id)->delete();           
       }else{
            return redirect()->route('index')->withErrors(["Examination is not found."]);
            
       }
       
        return redirect()->route('index')->with("message","Examination has been deleted.");
    }

    function updateExam($id)
    {
        $exam = Examination::where("id",$id)->where('teacher_id',Util::get_session('teacher_id'))->get();

        $part = ExamPart::where('examination_id',$id)->get();

        $this->data['examination']=$exam;
        $this->data['part']=$part;
        $this->data['class_list']=$this->classList($id);
        $this->data['classes']=$this->classes();

        $this->data['navigation'] = "Update";
        $this->data['page_title'] = "updateexam";
        return view('examination::layouts.master',$this->data);

    }

    public function storeUpdateExam(Request $req)
    {
         $this->validate(request(),[
            'gen_instruction' => 'required',
            'time_limit' => 'required',
            'class' => 'required',
            'exam_topic' => 'required',
            'exam_instruction' => 'required'
            ]);

         $exam = Examination::where('teacher_id',Util::get_session('teacher_id'))->where('id',$req['examination_id'])->update([
            'gen_instruction' =>$req['gen_instruction'],
            'exam_type' =>$req['is_long_exam'],
            'duration' =>$req['time_limit']=="yes"? $req['duration'] : 'None'
            ]);

        $class_record_not_included = " .";
        foreach ($req['class'] as $i => $class_id) {
            $checkcout = ClassRecordExam::where('examination_id',$req['examination_id'])->where('class_record_id',$class_id)->count();
            
            if($checkcout==0){
                
                if($req['is_long_exam'] == 1 || $req['is_long_exam'] == "1"){
                    // check if class record has long examination created before
                    $check_record=DB::select("select cr.examination_id from class_record_exams cr join examinations ex on ex.id=cr.examination_id
                                        where cr.class_record_id = ".$class_id." and ex.exam_type=1");
                    if(count($check_record)>0){
                        $class_record_not_included=" There is a class record which is not added in the examination. Please check...";
                        continue;               //dont save the exam since the class record has already an existed examination. Only 1 Long examination per class record to avoid conflict in data anayltics
                    }

                }

                $c_exam = new ClassRecordExam;
                $c_exam->examination_id=$req['examination_id'];
                $c_exam->class_record_id=$class_id;
                $c_exam->save();
            }
        }

        $todelete = array();

        foreach ($req['class'] as $i => $class_id) {
            array_push($todelete, $req['class'][$i]);           
        }

        // check if someone already took the exam        
        // $check_took_exam =  DB::table("student_exams")->where('examination_id',$req['examination_id'])->count();
        
        ClassRecordExam::whereNotIn('class_record_id',$todelete)->where('examination_id',$req['examination_id'])->delete();


         if($exam==1){
            
             foreach ($req['part_id'] as $i => $part_id) {
                ExamPart::where('examination_id',$req['examination_id'])->where('id',$part_id)
                ->update([
                    
                    'exam_topic'=>$req['exam_topic'][$i],
                    'exam_instruction'=>$req['exam_instruction'][$i]
                    ]);
                if(strlen($part_id)==0){
 
                    $part = new ExamPart;
                    $part->exam_type=$req['exam_type'][$i];
                    $part->examination_id=$req['examination_id'];
                    $part->exam_topic=$req['exam_topic'][$i];
                    $part->exam_instruction=$req['exam_instruction'][$i];
                    $part->part_num=0;
                    $part->save();



                    echo "<br>";
                }
             }
               
         }

         $part_num = ExamPart::where('examination_id',$req['examination_id'])->get(['id']);

         // update the part number if ever new part will be added.
         foreach ($part_num as $key => $part) {
                $exam_part = ExamPart::find($part->id);
                $exam_part->part_num=$key+1;
                $exam_part->save();
         }

         return redirect()->route('index')->with("message","Examination has been updated successfully".$class_record_not_included);
    }

   
    //transfrered to teacher controller
    // public function storeStudentAnswer(Request $req)
    // {
        

    //     foreach ($req['answer']as $answer) {
    //         $choice =new QuestionChoice;
    //         $choice->question_id=$req['question_id'];
    //         $choice->choice_desc=$answer;
    //         $choice->answer=1;
    //         $choice->save();

    //     }
        
    //     return redirect()->back()->with('message','Answer has been added.');
    // }


   

    

    public function showExam($exam_id)
    {

        $this->examDetail($exam_id);
        $this->data['navigation'] = "Show";
        $this->data['page_title'] = "showexamination";
        return view('examination::layouts.master',$this->data);
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




    // questions

    public function addQuestionLoad(Request $req, $exam_id,$p_id)
    {
        $exam = Examination::where("id",$exam_id)->where('teacher_id',Util::get_session('teacher_id'))->get();

        $part = ExamPart::where('examination_id',$exam_id)->where('id',$p_id)->get();
        //$sy   = ClassRecord::where('teacher_id',Util::get_session('teacher_id'))->where(["sy","!=",Util::get_session('sy')], [["sy","!=",Util::get_session('sy')], []])->groupBy("sy")->get(['sy']);
        $sy   = "select sy from class_records where teacher_id=".Util::get_session('teacher_id')." and (sy!='".Util::get_session('sy')."' or (sy='".Util::get_session('sy')."' and semester!='".Util::get_session('semester')."') ) group by sy";
        $sy = DB::select($sy);
        if(count($sy)==0){
            return redirect()->route("showexam",[$exam_id])->withErrors("No questions yet created from previous class record");
        }

        $class_list = $this->classList($exam_id);
        $class_list_array = array();
        foreach ($class_list as $key => $value) {
            array_push($class_list_array, $value->sub_code);
        }

        if(isset($req['sy'])){
            $sql = "select * from class_records cr join class_record_exams cre on cre.class_record_id=cr.id join examinations ex on
                        cre.examination_id=ex.id join exam_parts exp on exp.examination_id=ex.id join questions q on q.exam_part_id=exp.id
                        where cr.sy='".$req['sy']."' and cr.teacher_id='".Util::get_session('teacher_id')."' and cr.sub_code in ('".implode("','",$class_list_array)."') and exp.exam_type='".$part[0]->exam_type."' ";
            if($req['semester']!="All"){
                $sql.=" and cr.semester='".$req['semester']."'";
            }
            
            if($req['term']!="All"){

                $sql.=" and cr.type='".$req['term']."'";

            }
            $sql.="  group by q.question";
            $questions = DB::select($sql);
  
            if(count($questions)==0){
                return redirect()->route("addQuestionLoad",[$exam_id,$p_id])->with(['warning'=>"No questions found in the selected School year, semester, and term. Try other combination."]);
            }
            $this->data['questions']=$questions;
        }

        $this::numberQuestion_part($p_id);
        $this->data['examination']=$exam;
        $this->data['part']=$part;
        $this->data['sy']=$sy;
        $this->data['class_list']=$class_list;

        $this->data['navigation'] = "Add Questions";
        $this->data['page_title'] = "load_question";
        return view('examination::layouts.master',$this->data); 
    }

    public function addQuestion($exam_id,$p_id)
    {
        $exam = Examination::where("id",$exam_id)->where('teacher_id',Util::get_session('teacher_id'))->get();

        $part = ExamPart::where('examination_id',$exam_id)->where('id',$p_id)->get();

        $this::numberQuestion_part($p_id);
        $this->data['examination']=$exam;
        $this->data['part']=$part;
        $this->data['class_list']=$this->classList($exam_id);

        $this->data['navigation'] = "Add Questions";
        $this->data['page_title'] = "addquestion_".$part[0]->exam_type;
        return view('examination::layouts.master',$this->data); 
    }

    public function  storeUpdateQuestion(Request $req){
        
         $validate= $this->validate(request(),
            [
            'question.*'=>'required',
            'answer'=>'required',
            'choices_desc.*'=>'required'
            ],

            [
                'question.*required'=>"The question field is required",
                'answer.required'=>"You must set an answer to every question by clicking the checkbox",
                'choices_desc.*required'=>"You should fill out all the choices"
            ]
            );

        $error = array();
        $q_check = "";
        $no=1;
        $witheror = false;
        foreach ($req['question'] as $i => $question) {
            if(strlen($question)==0 && $q_check==""){
                array_push($error, "Please fill out all the questions.");
                $q_check=1;
                $witheror = true;
            }

            $choice_error=null;
            
            if($req['exam_type']!="essay"){
                foreach ($req['choices_desc'][$i] as $c_id => $choice) {
                    if(strlen($choice)==0){
                        $choice_error = "Please fill out all choices";
                        $witheror = true;
                    }
                }

                $withAsnwer=false;
                foreach ($req['answer'][$i] as $answer) {

                    if($answer==1){
                        $withAsnwer=true;
                    }
                }

                if($withAsnwer==false){                        
                    array_push($error, "Please choose an answer");
                    $witheror = true;
                }
 
            }
            
            
            array_push($error, $choice_error);
                # code...
            $no++;
        }

        $this->data['errors']=$error;
        if($witheror==true){

            return redirect()->back()->withErrors($this->data['errors']);
        }

        $deleted = array();

        



        foreach ($req['question'] as $q_i => $ques) {
            
            
            $cout_edit_q=  Question::where('id',$req['question_id'])->where('examination_id',$req['examination_id'])->where('exam_part_id',$req['part_id'])
            ->update(['question'=>$ques]);

            if($req['exam_type']=="essay"){

                Point::where('question_id',$req['question_id'])->update([
                    'point'=>$req['point'][$q_i]
                    ]);
                

            }else{
                foreach ($req['choices_desc'][$q_i] as $c_i => $choice_desc) {
                    // remove all answer indicator
                    // QuestionChoice::where('question_id',$req['question_id'])->update(['answer' => 0]);
                    // $class=DB::select("update question_choices set answer=0 where question_id=".$req['question_id']);
                    // add or update with answer remark
                   $choice= QuestionChoice::updateOrCreate(['id' => $req['choice_id'][0][$c_i]],
                        ['choice_desc' => $choice_desc,
                        'question_id' => $req['question_id'],
                        'answer' => $req['answer'][$q_i][$c_i]==1? '1':'0'
                        ]);
                   array_push($deleted, $choice->id);
                }  
            }
            
           

        }

        QuestionChoice::whereNotIn('id',$deleted)->where('question_id',$req['question_id'])->delete();

        $message="Question has been updated successfully";

        return redirect()->route('preview',$req['examination_id'])->with('message',$message);
       
    }


    // upload
    public function uploadAddQuestion($exam_id,$p_id)
    {
        $exam = Examination::where("id",$exam_id)->where('teacher_id',Util::get_session('teacher_id'))->get();

        $part = ExamPart::where('examination_id',$exam_id)->where('id',$p_id)->get();

        $this::numberQuestion_part($p_id);
        $this->data['examination']=$exam;
        $this->data['part']=$part;
        $this->data['class_list']=$this->classList($exam_id);

        $this->data['navigation'] = "Add Questions";
        $this->data['page_title'] = "uploadquestion";
        return view('examination::layouts.master',$this->data); 
    }


    public function storeUploadAddQuestion(Request $req)
    {
        $type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");
        $myfile = fopen($req['file']->getRealPath(), "r") or die("Unable to open file!");        
        $detail = fgetcsv($myfile);
        fgetcsv($myfile);// SKIP THE LINE BEFORE THE FIRST QUESTION
        $line_number=1;
        $question_count=0;

        if($req['file']->getClientMimeType()!="application/vnd.ms-excel"){
            return redirect()->back()->withErrors("Invalid file. The file extension must be .CSV. Make sure that the upload file is downloaded from this system.");            
        }
        
        if($detail[0]!=$req['exam_type']){            
            return redirect()->back()->withErrors("The uploaded template is not a ".$type[$req['exam_type']]." template");
         }
        
        // matching type

        if($detail[0]=="mat" or $detail[0]=="tru" or $detail[0]=="ide"or $detail[0]=="ess"or $detail[0]=="mul"){
            while(($row = fgetcsv($myfile)) !== FALSE){
                 
                if($line_number<=15){
                    $question = $row[1];
                   
                    if(strlen($question)>0){
                    $question_count++;
                       // save question here
                        $ques= $row[1];
                        $question =new Question;
                        $question->question = $ques;
                        $question->examination_id = $req['examination_id'];
                        $question->exam_part_id = $req['part_id'];
                        $question->save();
                        echo $ques."<br>";

                        if($detail[0]!="ess"){                            
                            $first_answer = 0;

                             for ($i=2; $i <=11 ; $i++) {                        
                                $answer=0;
                                if($i%2==0){
                                    
                                $choice_desc = $row[$i];                                
                                $row[$i+1]=="TRUE"? $answer=1:$answer=0;

                                if($detail[0]=="mul" and $answer==1){

                                    if($first_answer==1){
                                        $answer=0;
                                    }
                                    $first_answer=1;
                                }
                                    if(strlen($choice_desc)>0){
                                                                                   
                                        $choice = new QuestionChoice;
                                        $choice->question_id=$question->id;
                                        $choice->choice_desc=$choice_desc;
                                        $choice->answer=$answer;
                                        $choice->save();
                                        echo $choice_desc."- $answer <br>";
                                        
                                    }
                                }


                             }
                        } 

                         if($detail[0]=="ess"){  


                            $point = new Point;
                            $point->question_id=$question->id;
                            $point->point=$row[2];
                            $point->save();
                         }

                         echo "<br><br><br>"; 
                    }///////////
                     

                }else{
                    break;
                }

                $line_number++;

            }
        }//end of matching type   
        fclose($myfile);

        return redirect()->route("showexam",$req['examination_id'])->with("message","Question template has been uploaded successfully. $question_count questions added.");

    }


    public function deleteQuestion($e_id,$p_id,$q_id){
       $check = Question::where('id',$q_id)->where('examination_id',$e_id)->where('exam_part_id',$p_id)->delete();
       
       if($check==1){        
            QuestionChoice::where('question_id',$q_id)->delete();
       }else{

        return redirect()->back()->withErrors(['errors'=>"Changing the URL manually doesn't work"]);
       }

        return redirect()->back()->with('message',"Question has been deleted successfully.");
    }


    public function  storeAddQuestionLoad(Request $req, $exam_id,$p_id){
        

        foreach ($req['question'] as $key => $q_description) {

            $exist =Question::where("question",$q_description)->where("examination_id",$exam_id)->count();
            
            if($exist==0){
                var_dump($req['question_id'][$key]);

                $choices = QuestionChoice::where("question_id",$req['question_id'][$key])->get();
                
                $question =new Question;
                $question->question = $q_description;
                $question->examination_id = $req['examination_id'];
                $question->exam_part_id = $req['part_id'];
                $question->save();

                if($req['exam_type']=="essay"){
                    $existing_point = Point::where("question_id",$req['question_id'][$key])->get();

                    $point = new Point;
                    $point->question_id=$question->id;
                    $point->point=$existing_point[0]->point;
                    $point->save();

                }else{
                    foreach ($choices as $c_i => $choice) {

                        $choices = new QuestionChoice;
                        $choices->question_id=$question->id;
                        $choices->choice_desc=$choice->choice_desc;
                        $choices->answer=$choice->answer;
                        $choices->save();
                    }  
                }

            }
        }
        return redirect()->back()->with('message',"Questions are successfully added.");
    }

    public function  storeAddQuestion(Request $req){

        
       $validate= $this->validate(request(),
            [
            'question.*'=>'required',
            'answer'=>'required',
            'choices_desc.*'=>'required'
            ],

            [
                'question.*required'=>"The question field is required",
                'answer.required'=>"You must set an answer to every question by clicking the checkbox",
                'choices_desc.*required'=>"You should fill out all the choices"
            ]
            );


        $error = array();
        $q_check = "";
        $no=1;
        $witheror = false;
        foreach ($req['question'] as $i => $question) {
            if(strlen($question)==0 && $q_check==""){
                array_push($error, "Please fill out all the questions.");
                $q_check=1;
                $witheror = true;

            }

            $choice_error=null;
            
            if($req['exam_type']!="essay"){

                foreach ($req['choices_desc'][$i] as $c_id => $choice) {
                    if(strlen($choice)==0 && $req['exam_type']!='mat'){
                        $choice_error = "Please fill out all choices in question number $no";
                        $witheror = true;

                    }
                }

                $withAsnwer=false;
                foreach ($req['answer'][$i] as $answer) {

                    if($answer==1){
                        $withAsnwer=true;
                    }
                }

                if($withAsnwer==false){                        
                    array_push($error, "Please choose an answer in question number $no");
                    $witheror = true;
                }
                
            }
            
            
            array_push($error, $choice_error);
                # code...
            $no++;
        }

        
        
        $this->data['errors']=$error;
        if($witheror==true){

            return redirect()->back()->withErrors($this->data['errors']);
        }


        foreach ($req['question'] as $q_i => $ques) {
            
            $question =new Question;
            $question->question = $ques;
            $question->examination_id = $req['examination_id'];
            $question->exam_part_id = $req['part_id'];
            $question->save();

            if($req['exam_type']=="essay"){

                $point = new Point;
                $point->question_id=$question->id;
                $point->point=$req['point'][$q_i];
                $point->save();

            }else{
                foreach ($req['choices_desc'][$q_i] as $c_i => $choice_desc) {

                    $choice = new QuestionChoice;
                    $choice->question_id=$question->id;
                    $choice->choice_desc=$choice_desc;
                    $choice->answer=$req['answer'][$q_i][$c_i]==1? '1':'0';
                    $choice->save();
                }  
            }
            
           

        }

        $message="Question has been created successfully";

        return redirect()->back()->with('message',$message);
       
    }

    public function preview($exam_id){
        

        $this->examDetail($exam_id);
        $this->data['navigation'] = "Preview Questionnaire";
        $this->data['page_title'] = "preview";


        return view('examination::layouts.master',$this->data);
    }



    public function editQuestion($e_id,$p_id,$q_id){

        $exam = Examination::where("id",$e_id)->where('teacher_id',Util::get_session('teacher_id'))->get();
        
        if(count($exam)==0){
             return redirect()->back()->withErrors(['errors'=>"Changing the URL manually doesn't work"]);
        }

        $part = ExamPart::where('examination_id',$exam[0]->id)->where('id',$p_id)->get();
        
        if(count($part)==0){
             return redirect()->back()->withErrors(['errors'=>"Changing the URL manually doesn't work"]);
        }


        $question = Question::where('examination_id',$exam[0]->id)->where('exam_part_id',$part[0]->id)->where('id',$q_id)->get();
       
        if(count($question)==0){
             return redirect()->back()->withErrors(['errors'=>"Changing the URL manually doesn't work"]);
        }

        $choice = QuestionChoice::where('question_id',$question[0]->id)->get();

        if($part[0]->exam_type=="ess"){
           $this->data['point'] =  Point::where('question_id',$question[0]->id)->get();
        }
        
        $this::numberQuestion_part($p_id);
        $this->data['examination']=$exam;
        $this->data['selected_part']=$part;
        $this->data['question']=$question;
        $this->data['choice']=$choice;

        $this->examDetail($e_id);
        $this->data['navigation'] = "Edit Question";
        $this->data['page_title'] = "editquestion_".$part[0]->exam_type;

        return view('examination::layouts.master',$this->data);
    }


    function updateAnswerVisibility(Request $req){
        Examination::where('id',$req['examination_id'])->where('teacher_id',Util::get_session('teacher_id'))->
        update([
            'answer_visibility'=>$req['visibility']
            ]);


    }

    static function getQuestion($part_id){
        return Question::where('exam_part_id',$part_id)->get();
    }

    static function getChoices($question_id){
        return QuestionChoice::where('question_id',$question_id)->get();
    }


    public function numberQuestion($id){
        $this->data['number_question']=Question::where('examination_id',$id)->count();
    }

    public function numberQuestion_part($id){
      $this->data['number_question_part']=Question::where('exam_part_id',$id)->count();
    }

    static public function numberQuestion_part_preview($id){
      return Question::where('exam_part_id',$id)->count();     
    }


    static public function getPoint($q_id){
      return Point::where('question_id',$q_id)->get();     
    }




    static public function classList($exam_id){
        $class=DB::select("select * from class_record_exams,class_records where class_record_exams.class_record_id=class_records.id and examination_id='$exam_id'");
        
        return $class;        
    }

    function classes()
    {
        return ClassRecord::where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->where('type',Util::get_session('class_record_type'))->orderBy('day')->orderBy('time')->get();
    }

    static public function topic($exam_id){
        $topic= ExamPart::where('examination_id',$exam_id)->get();
        
        return $topic;        
    }
}
