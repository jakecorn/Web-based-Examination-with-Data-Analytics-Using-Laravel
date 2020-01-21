<?php

namespace Modules\Announcement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

use Modules\Announcement\Entities\File;
use Modules\Announcement\Entities\ClassRecordFile;
use Modules\Utilitize\Util;
use Modules\Announcement\Http\Controllers\AnnouncementController;

class FileController extends Controller
{
    use ValidatesRequests;

    public function __construct(){
        $this->data['main_page'] = "My Files";
        
    }

    public function fileList()
    {
        $file = File::join("class_record_files","class_record_files.file_id","=","files.id")->join("class_records","class_record_files.class_record_id","=","class_records.id")->where('class_records.teacher_id',Util::get_session('teacher_id'))->groupBy("files.id")->orderBy('files.id','desc')->get();
        $this->data['file'] = $file;
        $this->data['navigation'] = "List";
        $this->data['page_title'] = "file.filelist";
        return view('announcement::layouts.master',$this->data);
    }

    public function createFile()
    {
        
        $this->data['class_list'] = AnnouncementController::classList();
        $this->data['navigation'] = "Upload File";
        $this->data['page_title'] = "file.create";
        return view('announcement::layouts.master',$this->data);
    }
    public function storeCreateFile(Request $req)
    {
        $this->validate($req,[
            'file' =>'required|file',
            'description' =>'required',
            'class' =>'required'
            ]);
        
        
            $file_name = str_random(12).".".$req['file']->guessClientExtension();
         $req['file']->move(public_path()."/file",$file_name);

         $file  = new File;
         $file->file_name=$req['file']->getClientOriginalName();
         $file->rand_name=$file_name;
         $file->file_type=$req['file']->getClientMimeType();
         $file->description=$req['description'];
         $file->date=date('Y-m-d');
         $file->time=date('H:m a');
         $file->save();


         foreach ($req['class'] as $class_record_id) {
               $c_file = new ClassRecordFile;
               $c_file->class_record_id=$class_record_id;
               $c_file->file_id=$file->id;    
               $c_file->save();
            }
        return redirect()->route('fileList')->with("message","File has been uploaded and posted.");
    }

    public function deleteFile($file_id)
    {
        $file_name = File::where('files.id',$file_id)->join("class_record_files","class_record_files.file_id","=","files.id")->join("class_records","class_records.id","=","class_record_files.class_record_id")->get();
        if(count($file_name)==0){
           return redirect()->route("fileList")->withErrors("File ID is invalid");
        }
         unlink(public_path()."/file/".$file_name[0]->rand_name);
        $count = File::join("class_record_files","class_record_files.file_id","=","files.id")->join("class_records","class_record_files.class_record_id","=","class_records.id")->where('class_records.teacher_id',Util::get_session('teacher_id'))->where("class_record_files.file_id",$file_id)->count();
        if($count==0){
         return redirect()->route("fileList")->withErrors("File ID is invalid");
        }

        File::where("id",$file_id)->delete();
        DB::table('student_files')->where("file_id",$file_id)->delete();
        ClassRecordFile::where("file_id",$file_id)->delete();

        return redirect()->route('fileList')->with("message","File has been deleted.");
    }
    public function updateFile($file_id)
    {
        $file = File::where('id',$file_id)->get();
        
        if(count($file)==0){            
            return redirect()->route('fileList')->withErrors(['errors'=>'File ID does not exist']);
        }

        $this->data['class_list_selected'] = ClassRecordFile::where('file_id',$file_id)->get();
        $this->data['class_list'] =  AnnouncementController::classList();
        $this->data['file'] = $file;
        $this->data['navigation'] = "Update File";
        $this->data['page_title'] = "file.updatefile";
        return view('announcement::layouts.master',$this->data);
    }  
     


    public function storeUpdateFile(Request $req)
    {
        $this->validate($req,[
            'description' =>'required',
            'class' =>'required'
            ]);

        
        if(count($req['class'])==0){
            return redirect()->back()->withErrors(['errors'=>'Please select at least one class'])->withInput();
        }
        
       $a_update=File::where('id',$req['file_id'])->update([
        'description'=>$req['description']
        ]);
       

        ClassRecordFile::where('file_id',$req['file_id'])->delete();

        foreach ($req['class'] as $class_record_id) {
           $file = new ClassRecordFile;
           $file->class_record_id=$class_record_id;
           $file->file_id=$req['file_id'];    
           $file->save();
        }

        return redirect()->route('fileList')->with(['message'=>'File has been updated.']);

    }




    static public function fileClass($file_id)
    {
        return DB::select("select * from class_records,class_record_files where class_records.id=class_record_files.class_record_id and file_id='$file_id'");
    }


}
