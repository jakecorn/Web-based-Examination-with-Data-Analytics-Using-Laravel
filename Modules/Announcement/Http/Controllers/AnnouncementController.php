<?php

namespace Modules\Announcement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

use Modules\Utilitize\Util;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Entities\ClassRecordAnnouncement;



class AnnouncementController extends Controller
{
    use ValidatesRequests;

    public function __construct(){
        $this->data['main_page'] = "Announcement";
        $this->data['term'] = "Examination";
        $this->data['message'] = "";
        $this->data['teacher_id'] = Util::get_session('teacher_id');
    }

    public function index()
    {
        $announcement = Announcement::where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->orderBy('id','desc')->get();
        $this->data['announcement'] = $announcement;
        $this->data['navigation'] = "List";
        $this->data['page_title'] = "announcementlist";
        return view('announcement::layouts.master',$this->data);
    }
    public function create()
    {
        
        $this->data['class_list'] = self::classList();
        $this->data['navigation'] = "Create Announcement";
        $this->data['page_title'] = "create";
        return view('announcement::layouts.master',$this->data);
    }

    public function storeCreate(Request $req)
    {
         $this->validate(request(),[
            'announcement' =>'required|min:10',
        ],
         [
         'announcement.required'=>"Announcement field should not be empty",
         'announcement.min'=>"Announcement should be at least 10 characters",
        ]); 

        
        if(count($req['class'])==0){
            return redirect()->back()->withErrors(['errors'=>'Please select at least one class'])->withInput();
        }

        // $tempDate =  date('Y-m-d');
        // $date = date('jS F Y',strtotime($tempDate))." ".date('h:m a');
        
        $ann = new Announcement;       
        $ann->announcement=$req['announcement'];
        $ann->date=date('Y-m-d');
        $ann->time=date('H:m a');
        $ann->sy=Util::get_session('sy');
        $ann->semester=Util::get_session('semester');
        $ann->teacher_id=Util::get_session('teacher_id');
        $ann->save();

        foreach ($req['class'] as $class_record_id) {
           $c_ann = new ClassRecordAnnouncement;
           $c_ann->class_record_id=$class_record_id;
           $c_ann->announcement_id=$ann->id;    
           $c_ann->save();
        }

        return redirect()->route('announcementlist')->with(['message'=>'Announcement has been posted.']);

    }


    public function deleteAnnouncement($id)
    {
       $del_a = Announcement::where('teacher_id',Util::get_session('teacher_id'))->where('id',$id)->delete();

       if($del_a==0){
           return redirect()->route('announcementlist')->withErrors(['errors'=>'Announcement ID does not exist']);
       }

       ClassRecordAnnouncement::where('announcement_id',$id)->delete();
       DB::table('student_announcements')->where('announcement_id',$id)->delete();
        return redirect()->route('announcementlist')->with(['message'=>'Announcement has been deleted']);

    }

    public function updateAnnouncement($id)
    {
        $announcement = Announcement::where('id',$id)->where('teacher_id',Util::get_session('teacher_id'))->get(['id','announcement']);
        
        if(count($announcement)==0){            
            return redirect()->route('announcementlist')->withErrors(['errors'=>'Announcement ID does not exist']);
        }

        $this->data['class_list_selected'] = ClassRecordAnnouncement::where('announcement_id',$id)->get();
        $this->data['class_list'] = self::classList();
        $this->data['announcement'] = $announcement;
        $this->data['navigation'] = "Update Announcement";
        $this->data['page_title'] = "updateannouncement";
        return view('announcement::layouts.master',$this->data);
    }

    public function storeupdateAnnouncement(Request $req)
    {
         $this->validate(request(),[
            'announcement' =>'required|min:10',
        ],
         [
         'announcement.required'=>"Announcement field should not be empty",
         'announcement.min'=>"Announcement should be at least 10 characters",
        ]); 

        
        if(count($req['class'])==0){
            return redirect()->back()->withErrors(['errors'=>'Please select at least one class'])->withInput();
        }
        
       $a_update=Announcement::where('id',$req['announcement_id'])->update([
        'announcement'=>$req['announcement']
        ]);
       

        ClassRecordAnnouncement::where('announcement_id',$req['announcement_id'])->delete();

        foreach ($req['class'] as $class_record_id) {
           $c_ann = new ClassRecordAnnouncement;
           $c_ann->class_record_id=$class_record_id;
           $c_ann->announcement_id=$req['announcement_id'];    
           $c_ann->save();
        }

        return redirect()->route('announcementlist')->with(['message'=>'Announcement has been updated.']);

    }












    static public function AnnouncementClass($announcement_id)
    {
        return DB::select("select * from class_records,class_record_announcements where class_records.id=class_record_announcements.class_record_id and announcement_id='$announcement_id'");
    }

    static public function classList()
    {
        return $class_list = DB::table('class_records')->where('teacher_id',Util::get_session('teacher_id'))->where('sy',Util::get_session('sy'))->where('semester',Util::get_session('semester'))->where('type',Util::get_session('class_record_type'))->orderBy('day')->orderBy('time')->get();
    }
}
