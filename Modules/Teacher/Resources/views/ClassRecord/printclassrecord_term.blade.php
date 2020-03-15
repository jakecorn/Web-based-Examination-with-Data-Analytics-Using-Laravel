<?php
	use Modules\Teacher\Http\Controllers\TeacherController;
	use Modules\Utilitize\Util;
?>
<!doctype html>
<html lang="{{ app()->getLocale() }}" style="background:white">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
       	<link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
        <script type="text/javascript" src="/js/app.js"></script>
        @section('scripts')
        
		@show
        <title>Class Record Print</title>
    </head>
    <body style="background:white">
	<div class="containter-fluid">

		<div class="content white-bg m-padding  gray-border mv-margin">
 			<div class="containter">
 		   		<table>
		   			<tr>
		   				<td width="80px"><label for="sub_code">Subject </label></td>

		   				<td>: {{$detail[0]->sub_code}} {{$detail[0]->sub_desc}} - {{$detail[0]->sub_sec}}</td>
		   			</tr>

		   			<tr>
		   				<td><label for="sub_code">Schedule</label></td>
		   				<td>: {{$detail[0]->day}} {{$detail[0]->time}}</td>
		   			</tr>

		   			<tr>
		   				<td><label for="sub_code">Formula</label></td>
		   				<td>: ( (Raw Score / Total Score) x {{$detail[0]->formula_times}}% ) + {{$detail[0]->formula_plus}} = {{$detail[0]->type}} Grade</td>
		   			</tr>

		   			<tr>
		   				<td><label for="sub_code">S.Y </label> </td>
		   				<td>: {{$detail[0]->sy}}</td>
		   			</tr>
		   			<tr>
		   				<td><label for="sub_code">Semester </label> </td>
		   				<td>: {{$detail[0]->semester}}</td>
		   			</tr>
		   			<tr>
		   				<td><label for="sub_code">Term </label> </td>
		   				<td>: {{$detail[0]->type}}</td>
		   			</tr>
		   		</table>
		   		 <br>

		   		<div class="table-containersssssssss"> 			
				   	@include('teacher::Classrecord.class_record_body')
		   			<br>
					<br>
					<div style="margin-left:15px">
					<b style="text-transform:uppercase">{{Auth::user()->name}}, {{Auth::user()->degree}}</b><br>
					Subject Instructor</div>	
							   	</div>

		  	</div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	$(function(){
		$('.criteria-record-add').hide()
	});
	window.print();
</script>