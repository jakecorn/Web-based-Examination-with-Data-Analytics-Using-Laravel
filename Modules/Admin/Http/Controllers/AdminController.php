<?php

namespace Modules\Admin\Http\Controllers;
error_reporting(0);
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Entities\Log;
use Session;
use Modules\Utilitize\Util;
use App\user;
use Modules\Teacher\Entities\Teacher;
use Modules\Base\Http\Controllers\BaseController;
class AdminController extends Controller
{
	use ValidatesRequests;

	public function __construct(){
		
	}

	public function index($last_id = 9999999999999, $all = false)
	{    
		$this->data['main_page'] = "User Management";  
		$this->data['list']='';

		if($last_id==9999999999999){
			$this->data['list']='first';
			
		}

		if($all  != false){
			$this->data['user']=User::where("role","=","Teacher")->limit(15)->orderBy("id","desc")->get();
			$this->data['navigation']='Masterlist';
		}else{
			$this->data['user']=User::where("id","<",$last_id)->where("role","=","Teacher")->where("is_registered","!=",1)->limit(15)->orderBy("id","desc")->get();
			$this->data['navigation']='Registered Users';
		}
		
		$this->data['page_title']='userlist';

		return view('admin::layouts.master',$this->data);
	 }


	 function storeStatus(Request $req){
		User::where('id',$req['user_id'])->update(['status'=>$req['status'], 'is_registered'=>2]);
		$user = User::where('id',$req['user_id'])->get(['name','cp_number']);
		$status = $req['status'] == 1? 'Activated':'Deactivated';

		BaseController::sendSms($user[0]->cp_number,'Admin has '.strtolower($status).' your account.');

		$this->saveLog($status.' the account of '.$user[0]['name'],'Activation',Auth::id(),$req['user_id']);

	 }

	 function passwordReset(Request $req){
		$password=Util::randPassword();
		User::where('id',$req['user_id'])->update(['password'=>bcrypt($password)]);
		$user = User::where('id',$req['user_id'])->get(['name','cp_number']);
		
		BaseController::sendSms($user[0]->cp_number,'Admin has reset your password to '.$password.'. Please change your new password immediately.');
		
		$this->saveLog('Reset password of '.$user[0]['name'],'Reset',Auth::id(),$req['user_id']);
		
		if($user[0]->cp_number == "1"){
			return "The new password is ".$password;			
		}else{
			return "New password is sent to <b>*******".substr($user[0]->cp_number,7,4)."<b>";
		}
	 }

	 function searchUser(Request $req){
		$user= User::where("name","like","%".$req['search']."%")->where("role","=","Teacher")->get();

		if(count($user)){
			foreach($user as $user){
			   $last_id=$user->id;?>
					<tr>
						<td><?php echo $user->name;?></td>
						<td><?php echo $user->username;?></td>
						<td><?php echo $user->role;?></td>
						<td align="center">
							 
							<span class="fa fa-toggle-on on" style="color:green;font-size:20px;<?php if($user->status==0){echo "display:none";}?>" onclick="storeStatus(0,this,<?php echo $user->id;?>)" data-toggle="tooltip" title="Click to deacticvate account"></span>

							<span class="fa fa-toggle-off off" style="color:red;font-size:20px;<?php if($user->status==1){echo "display:none";}?>" onclick="storeStatus(1,this,<?php echo $user->id;?>)" data-toggle="tooltip" title="Click to activate account"></span>

							<img src='/images/loader.gif' class='loader ' style="display:none;width:20px">
						</td>
						<td><?php echo $user->created_at;?></td>
						<td align="center" class="list-action">                             
							<a data-toggle="tooltip" onclick="passwordReset(<?php echo $user->id;?>,this)" title="Reset password" class="fa fa-refresh action btn-success"></a>
							<img src='/images/loader.gif' class='loader ' style="display:none;width:20px">
						</td>
					</tr>
			 <?php
			}
			
		}else{
			?>
			<tr>
				<td colspan="1342" align="center">No result found.</td>
			</tr>
			<?php
		}
	   
	 }

    public function uploadUser()
    {
        $this->data['main_page'] = "User Management";
        $this->data['navigation']='Upload User';
        $this->data['page_title']='userupload';
        return view('admin::layouts.master',$this->data);
	}
	
    public function saveUploadUser(Request $req)
    {
		
		$type= array("mul"=>"user");
        $myfile = fopen($req['file']->getRealPath(), "r") or die("Unable to open file!");
        $detail = fgetcsv($myfile);
        fgetcsv($myfile);// SKIP THE LINE BEFORE THE FIRST QUESTION
        $line_number=1;
		$question_count=0;
		
		if($req['file']->getClientMimeType()!="application/vnd.ms-excel"){
			// echo 12;
            return redirect()->back()->withErrors("Invalid file. The file extension must be .CSV. Make sure that the uploaded file is downloaded from this system.");            
		}

		if($detail[0]!="user"){
			// echo 3;            
            return redirect()->back()->withErrors("The uploaded template is not a user template. Make sure that the uploaded file is downloaded from this system.");
        }

        while(($row = fgetcsv($myfile)) !== FALSE){
			
			if($row[0] == "END"){
				break;
			}

			$id_number =  $row[1];
			$last_name =  $row[2];
			$first_name =  $row[3];
			$degree =  $row[4];
			$cellphone_number =  $row[5];
			echo $id_number." - ";
			echo $last_name." - ";
			echo $first_name." - ";
			echo $degree." - ";
			echo $cellphone_number." -";

			$check_user = User::where('id_number',$id_number)->count();

			if($check_user == 0){
				$user =  User::create([
					'name' => $last_name.", ".$first_name,
					'id_number' => $id_number,
				    'username' => $id_number,
				    'degree' => "",
				    'role' => "Teacher",
				    'password' => bcrypt("12312311RGFF"),
				]);

                $user->account_id = $user->id;
                $user->save();

				$teacher = new Teacher;
				$teacher->id=$user->id;
				$teacher->save();
			}else{
				$user =  User::where('id_number', $id_number)->where('is_registered', 'Done manual registration but not found in the masterlist')->update(['is_registered'=>2]);

			}
			echo $check_user." - <br>";
			
			
	
		}
		
		fclose($myfile);
		return redirect()->route('uploaduser')->with('message','Users have been uploaded.');
    }
	// course

	 public function courseCreate(Request $req)
	 {
		$this->data['main_page'] = "Program";
		$this->data['navigation']='Create Program';
		$this->data['page_title']='coursecreate';
		return view('admin::layouts.master',$this->data);
	 }

	 public function storeCourse(Request $req)
	 {
		$this->validate(request(),[
			'course_code'=>'required',
			'course_desc'=>'required'
			],[
				'course_code.required'=>'The program code field is required',
				 'course_desc.required'=>'The program description is required'
			]);

		DB::table('courses')->insert(['course_code'=>$req['course_code'], 'course_desc'=>$req['course_desc'] ]);
		return redirect()->route('courseList')->with('message','Course has been created.');
	  }

	 public function courseList()
	{    
		$this->data['main_page'] = "Program";
		$this->data['course']=DB::table('courses')->get();
		$this->data['navigation']='List';
		$this->data['page_title']='courselist';
		return view('admin::layouts.master',$this->data);
	 }

	 public function courseDelete($id)
	{  
		$check = DB::table('students')->where('course_id',$id)->count();
		if($check>0){
			return redirect()->route('courseList')->withErrors(['errors'=>"This course is already assigned to students. Can not be deleted."]);
		}

		DB::table('courses')->where('id',$id)->delete();
		return redirect()->route('courseList')->with('message','Course has been deleted.');
	 }

	  public function courseEdit($id)
	 {
		 $this->data['course']=DB::table('courses')->where('id',$id)->get();
		$this->data['main_page'] = "Program";
		$this->data['navigation']='Edit Program';
		$this->data['page_title']='courseedit';

		return view('admin::layouts.master',$this->data);
	 }

	 public function storeCourseEdit(Request $req)
	 {
		$this->validate(request(),[
			'course_code'=>'required',
			'course_desc'=>'required'
			],[
				'course_code.required'=>'The course code field is required',
				 'course_desc.required'=>'The course description is required'
			]);

		DB::table('courses')->where('id',$req['course_id'])->update(['course_code'=>$req['course_code'], 'course_desc'=>$req['course_desc'] ]);
		return redirect()->route('courseList')->with('message','Course has been updated.');
	  }

	public function databaseManagement()
	 {
	    $this->data['sy'] = DB::table('class_records')->groupBy('sy')->orderBy('sy','asc')->get(['sy']);
		$this->data['main_page'] = "Database Management";
		$this->data['navigation']='Database';
		$this->data['page_title']='databasemanagement';
		return view('admin::layouts.master',$this->data);
	 }

    public function dabataseBackup(){
        \Artisan::call('db:custombackup');
        return redirect()->back()->with('message',"Dabatase backup has been created successfully. Please check for the latest created backup at the <i>/lms/storage/backups/</i> directory.");
    }
	 public function storeDatabaseManagement(Request $req)
	{
	    $current_year = date("Y");
	    $year_limit = 2;
	    $sy = $req['sy'];
	    $deleted_records = 0;
	    $start_year = explode("-", $sy);
	    $start_year = $start_year[0];
	    if(($current_year - $sy) > $year_limit && isset($sy)){
	        echo DB::transaction(function () use($sy, &$deleted_records, $start_year) {
                $class_records = DB::select("select id from class_records where sy='".$sy."'");
                foreach($class_records as $class_record){
                    $class_delete = DB::delete("delete class_records, student_records, class_record_announcements, announcements, student_announcements,
                                                class_record_files, files, student_files, class_record_pairs, criterias, criteria_records, scores
                                                from class_records
                                                    left join student_records on class_records.id=student_records.class_record_id
                                                    left join class_record_announcements on class_records.id=class_record_announcements.class_record_id
                                                        left join announcements on class_record_announcements.announcement_id=announcements.id
                                                            left join student_announcements on announcements.id=student_announcements.announcement_id
                                                    left join class_record_files on class_records.id=class_record_files.class_record_id
                                                        left join files on class_record_files.file_id=files.id
                                                            left join student_files on files.id=student_files.file_id
                                                    left join class_record_pairs on class_records.id=class_record_pairs.class_record_id_mid or class_records.id=class_record_pairs.class_record_id_final
                                                    left join criterias on class_records.id=criterias.class_record_id
                                                        left join criteria_records on criterias.id=criteria_records.criteria_id
                                                            left join scores on criteria_records.id=scores.criteria_record_id
                                                where class_records.id=".$class_record->id);
                    if($class_delete){
                        $deleted_records+=$class_delete;
                        $questions = DB::select("select questions.id as id from class_record_exams
                                                    join examinations on class_record_exams.examination_id=examinations.id
                                                    join questions on examinations.id=questions.examination_id
                                                    where class_record_exams.class_record_id=".$class_record->id);

                        $deleted_records+=DB::delete("delete class_record_exams, examinations, student_exams, exam_parts, questions, student_answers
                                                        from
                                                        class_record_exams
                                                            left join examinations on class_record_exams.examination_id=examinations.id
                                                                left join student_exams on examinations.id=student_exams.examination_id
                                                                left join exam_parts on examinations.id=exam_parts.examination_id
                                                                left join questions on examinations.id=questions.examination_id
                                                                    left join student_answers on questions.id=student_answers.question_id
                                                        where class_record_exams.class_record_id=".$class_record->id);

                        foreach($questions as $question){
                            $deleted_records+=DB::delete("delete from points where question_id=".$question->id);
                            $deleted_records+=DB::delete("delete from long_answers where question_id=".$question->id);
                            $deleted_records+=DB::delete("delete from rand_questions where question_id=".$question->id);
                            $deleted_records+=DB::delete("delete question_choices, rand_choices from question_choices left join rand_choices on question_choices.id=rand_choices.choice_id where question_choices.question_id=".$question->id);
                        }
                    }

                    $deleted_records+=DB::delete("delete from sms where year(created_at)<='".$start_year."'");
                    $deleted_records+=DB::delete("delete from sms where year(created_at)<='".$start_year."'");
                    $deleted_records+=DB::delete("delete from class_records where id=".$class_record->id);
                }
	        });
	        return redirect()->back()->with('message','Deleted '.$deleted_records. " records  of S.Y. ".$sy);
	    }else{
	        return redirect()->back()->withErrors(['errors' => "The selected S.Y. is not ".$year_limit." years older."]);
	    }

	}
	  public function settings()
	 {
		$this->data['sy'] = DB::table('class_records')->groupBy('sy')->orderBy('sy','asc')->get();
		$this->data['main_page'] = "System Settings";
		$this->data['navigation']='Update Settings';
		$this->data['page_title']='settings';

		return view('admin::layouts.master',$this->data);
	 }

	public function storeSettings(Request $req)
	{
		

		DB::table('settings')->update(['sy'=>$req['sy'], 'semester'=>$req['semester'], 'term'=>$req['term'] ]);
		Session::put('sy',$req['sy']);
		Session::put('semester',$req['semester']);
		Session::put('term',$req['term']);
		return redirect()->back()->with('message','System settings has been updated.');  
	}

	 public function account()
	 {
		$this->data['main_page'] = "My Account";
		$this->data['navigation']='Update';
		$this->data['page_title']="myaccount";

		return view('admin::layouts.master',$this->data);
	 }


	 public function storeAccount(Request $req)
	 {
		$this->validate(request(),[
			'name'=>'required',
			'current_password'=>'required',
			'username'=>'required|min:6',
		]);

		$user = User::where('username',$req['username'])->where('id',"!=",Auth::user()->id)->count();
		if($user>0){
			return redirect()->back()->withErrors(['errors'=>"The username has already been taken."]);
		   
		}
		if(!Hash::check($req['current_password'], Auth::user()->password)){
			 return redirect()->back()->withErrors(["Current password is inccorrect."]);
		}


		if(strlen($req['password'])>0 or strlen($req['password_confirmation'])>0){
 

			if($req['password']!=$req['password_confirmation']){
				return redirect()->back()->withErrors(['errors'=>"password confirmation does't match."]);
			}

			if(strlen($req['password'])<6){
				return redirect()->back()->withErrors(['errors'=>"New password must at least 8 characters."]);
				
			}

			 User::where('id',Auth::user()->id)->update([
				'name'=>$req['name'],
				'username'=>$req['username'],
				'password'=>bcrypt($req['password'])
			]);

		}else{
			 User::where('id',Auth::user()->id)->update([
				'name'=>$req['name'],
				'username'=>$req['username'],
			]);
		}
	  
		return redirect()->back()->with('message','Account has been updated.');
	 }

	 public function settngs()
	 {
		
		$this->data['sy'] = DB::table('class_records')->groupBy('sy')->orderby('sy','asc')->get();
		$this->data['main_page'] = "Admin";
		$this->data['navigation']='settings';
		$this->data['page_title']="Settings";

		return view('admin::layouts.master',$this->data);
	 }

	 public function save_settngs(Request $req)
	 {
	   DB::table("settings")->update([
			'sy'=>$req['sy'],
			'semester'=>$req['semester'],
			'term'=>$req['term']
			]);

		Session::put('sy',$req['sy']);
		   Session::put('semester',$req['semester']);
		   Session::put('class_record_type',$req['term']);

		   return redirect()->back()->with('message','Settings have been changed.');

	}
	
	public function logList()
	{
		$logs = DB::select("select * from logs");
        $this->data['logs'] = $logs;        
        $this->data['main_page'] = "Admin";
        $this->data['navigation']='Logs';
        $this->data['page_title']="loglists";

        return view('admin::layouts.master',$this->data);
    }	
	
	public function searchLog(Request $date)
	{
		$logs = DB::select("select lg.*,us.name from logs lg left join users us on us.id=lg.created_by where date(lg.created_at)='".$date['date']."' order by id desc");
		foreach ($logs as $log) {
			?>
				<tr>
					<td><?php echo $log->content;?></td>
					<td><?php echo $log->action_type;?></td>
					<td><?php echo $log->name;?></td>
					<td><?php echo $log->created_at;?></td>
				</tr>
			<?php
		}		
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
