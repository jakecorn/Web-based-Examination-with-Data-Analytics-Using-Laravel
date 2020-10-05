<?php
use Modules\Utilitize\Util;
use Modules\Student\Http\Controllers\StudentController;

?>
<div class="student-header-hidden padding visible-xs-block clearFix" style="height:53px">
 		<div class="dropdown pull-left  margin-right visible-sm-* " style="display:block;cursor:pointer;color:white;">
			<span class="dropdown-toggle" id="user-account" data-toggle="dropdown" aria-expanded="true">
				{{StudentController::information()->stud_num}} {{Auth::user()->name}} <span class="caret"></span>			
			</span>
			<ul class="dropdown-menu" style="padding:2px;margin:0px" role="menu" aria-labelledby="user-account">
				<li role="presentation"><a role="menu-item" href="{{route('account')}}"><span class="fa fa-dashboard" style="margin-right:10px"></span>Manage Account</a></li>
				<li role="presentation">
					<a role="menu-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><span class="fa fa-power-off" style="margin-right:10px"></span>
					Logout
	                </a>
	                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
	                    {{ csrf_field() }}
	                </form>
				</li>
			</ul>
		</div>

		<span class="glyphicon glyphicon-align-justify menu-toggler" name="left"></span>
</div>
<div class="student-header padding-lr">

	<ul class="link">
		<li><a href="/student"><i class="fa fa-newspaper-o"></i>Courses</a></li>
 		<li style="position:relative"><a href="/student/files"><i class="fa fa-floppy-o"></i> Files</a>

 			<?php $not = StudentController::fileNotification();?>
			
			@if($not>0 && Request::route()->getName()!="filesList")
				<div style="position:absolute;left:22px;top:10px;background:red;color:white;border-radius:15px;height:18px;width:18px;text-align:center;font-size:12px">
					{{$not}}
				</div>
 			@endif

 		</li>
		<li style="position:relative"><a href="/student/announcement"><i class="fa fa-bell-o" ></i> Announcements</a>
			<?php $not = StudentController::announcementNotification();?>
			
			@if($not>0 && Request::route()->getName()!="announcement")
				<div style="position:absolute;left:22px;top:10px;background:red;color:white;border-radius:15px;height:18px;width:18px;text-align:center;font-size:12px">
					{{$not}}
				</div>
 			@endif

		</li>
		<li><a href="/student/settings"><i class="fa fa-gear"></i>Settings</a></li>
	</ul>

	<div class="dropdown  pull-right margin-right visible-sm-*" style="display:inline;cursor:pointer;color:white;margin-top:15px">
		<span class="dropdown-toggle" id="user-account" data-toggle="dropdown" aria-expanded="true">
			{{Util::get_session('sy')}} {{Util::get_session('semester')}} Semester {{Util::get_session('class_record_type')}}, {{Auth::user()->name}} <span class="caret margin-right"></span>			
		</span>
		<ul class="dropdown-menu" style="padding:2px;margin:0px" role="menu" aria-labelledby="user-account">
			<li role="presentation"><a role="menu-item" href="{{route('account')}}"><span class="fa fa-dashboard" style="margin-right:10px"></span>Manage Account</a></li>
			<li role="presentation">
				<a role="menu-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><span class="fa fa-power-off" style="margin-right:10px"></span>
				Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			</li>
		</ul>
	</div>

</div>
<style type="text/css">
	@media (max-width: 767.98px) {
		.student-header{
			display:none;
		}
	}
</style>