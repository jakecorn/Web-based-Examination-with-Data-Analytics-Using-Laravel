<?php

namespace Modules\Base\Http\Controllers;
error_reporting(0);
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Base\Entities\SMS;
use Illuminate\Support\Facades\DB;


class BaseController extends Controller
{
    public function testsms(){
    	$this::sendSms("09267629048","hello");
    }

    static public function sendSms($number,$text)
    {
    	if($number=="1"){
    		// return;
    	}


    	$text.=" This is an automated text. Please don't reply. Thank you.";
    	$text.=' | SMS\gnokii --sendsms '.$number;

    	$sms = new SMS();
        $sms->content = $text;
    	$sms->is_sent = 0;
    	$sms->save();

    	// return shell_exec('echo ');
    }

    static public function initiateSendSms()
    {

        return;
        $check_pending = DB::select("select * from sms where is_sent=-1 and created_at>=DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
    	$getsms = DB::select("select * from sms where is_sent=0 order by id asc limit 1");
    	
        var_dump($check_pending);

        if(count($check_pending)==0){

            if(count($getsms)>0){

                $sms = SMS::find($getsms[0]->id);
                $sms->is_sent=-1;
                $sms->update();

                $a =  shell_exec('echo '.$getsms[0]->content);

                $sms->is_sent=1;
                $sms->update();
            }
            
        }
    	    
    }
  
}
