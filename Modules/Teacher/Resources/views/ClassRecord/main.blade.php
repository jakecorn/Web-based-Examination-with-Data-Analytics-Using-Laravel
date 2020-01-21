@extends('base::layouts.master')

@if(isset($detail[0])>0)
	@section('topNav')
		@include('teacher::classrecord.inc.topnav')
	@stop
@endif

@section('content')

	@if($page_title=='createClassRecord')
	
		@include('teacher::classrecord.create')
	
	@elseif($page_title=='showClassRecord')
	
		@include('teacher::classrecord.showclassrecord')
	
	@elseif($page_title=='createStudent')
	
		@include('teacher::student.create')

	@elseif($page_title=='studentList')
	
		@include('teacher::student.studentlist')

	@elseif($page_title=='index')
 		@include('teacher::index')

	@elseif($page_title=='addScoreRecord')
 		@include('teacher::classrecord.addscorerecord')
	
	@elseif($page_title=='addScoreRecordAttendance')
 		@include('teacher::classrecord.addscorerecordattendance')
	
	@elseif($page_title=='classRecordUpdate')
 		@include('teacher::classrecord.classrecordupdate')

	@elseif($page_title=='classRecordList')
 		@include('teacher::classrecord.classrecordlist')
	
	@elseif($page_title=='grade')
 		@include('teacher::classrecord.grade')
	
	@elseif($page_title=='examlist')
 		@include('teacher::classrecord.examlist')

	@elseif($page_title=='dataanalytics')
 		@include('teacher::classrecord.analytics.dataanalytics')
	@elseif($page_title=='dataanalyticsindex')
 		@include('teacher::classrecord.analytics.dataanalyticsindex')
	@elseif($page_title=='itemstatisticsindex')
 		@include('teacher::classrecord.analytics.itemstatisticsindex')
	@elseif($page_title=='itemstatisticsresult')
 		@include('teacher::classrecord.analytics.itemstatisticsresult')

 	@elseif($page_title=='updatestudent')
 		@include('teacher::student.updatestudent')	

 	@elseif($page_title=='uploadStudent')
 		@include('teacher::student.uploadStudent')	

 	@elseif($page_title=='myaccount')
 		@include('teacher::myaccount')
	@endif

@stop

@section("scripts")
    <script type="text/javascript" src="{{Module::asset('teacher:js/classrecord.js')}}"/></script>

    
@stop