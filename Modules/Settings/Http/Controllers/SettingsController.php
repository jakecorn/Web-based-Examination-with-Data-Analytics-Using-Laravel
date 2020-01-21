<?php

namespace Modules\Settings\Http\Controllers;
error_reporting(0);
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Session;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->data['main_page']="Settings";
    }
    public function index()
    {
        $this->data['sy'] = DB::table('class_records')->groupBy('sy')->orderby('sy','asc')->pluck('sy');

        $this->data['navigation'] = "Settings";
        $this->data['page_title'] = "settings";
        return view('settings::layouts.master',$this->data);
    }


    public function saveSettings(Request $req)
    {
       Session::put('sy',$req['sy']);
       Session::put('semester',$req['semester']);
       Session::put('class_record_type',$req['term']);

       return redirect()->back()->with('message','Settings have been changed.');
    }
}
