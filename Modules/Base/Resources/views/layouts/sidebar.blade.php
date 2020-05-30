<div class="profile">	
	<div class="dropdown" style="display:inline;cursor:pointer">
		<span class="dropdown-toggle" id="user-account" data-toggle="dropdown" aria-expanded="true">
			{{Auth::user()->name}} <span class="caret"></span>			
		</span>
		<ul class="dropdown-menu" style="padding:2px;margin:0px" role="menu" aria-labelledby="user-account">
			<li role="presentation"><a role="menu-item" href="/{{strtolower(Auth::user()->role)}}/myaccount"><span class="fa fa-dashboard" style="margin-right:10px"></span>Manage Account</a></li>
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
	<span class="glyphicon glyphicon-align-justify left-toggler" name="left" title="Hide left column"></span>
</div>
<ul class="nav">
	@if(Auth::user()->role=="Teacher")
		<li class="class-nav">
			<a href="/teacher/classrecord">
			<span class="fa fa-tasks"> </span>
		 	Class Record 				
			</a>
		 </li>
		<li>
			<a href="/teacher/file">
				<span class="fa fa-floppy-o"> </span>
			 	My Files 				
			</a>		
		</li>
		
		<li>
			<a href="/teacher/announcement">
				<span class="fa fa-commenting"> </span>
				Announcements
			</a>
		</li>
		
		<li>
			<a href="/teacher/examination">
				<span class="fa fa-file-text-o"> </span>		
				Examination
			</a>
		</li>

		<li>
			<a href="/teacher/data-analytics/analysis">
				<span class="fa fa-bar-chart-o"> </span>		
				Data Analytics
			</a>
		</li>


		<li>
			<a href="/teacher/settings">
				<span class="fa fa-gears"> </span>		
				Settings

			</a>
		</li>

	@endif

	@if(Auth::user()->role=="Admin")
		<li>
			<a href="/admin/user-list">
				<span class="fa fa-users"> </span>		
				User Management

			</a>
		</li>

		<li>
			<a href="/admin/course/list">
				<span class="fa fa-mortar-board"> </span>		
				Courses

			</a>
		</li>
		
		<li>
			<a href="{{route('logs')}}">
				<span class="fa fa-calendar"> </span>		
				History Logs
			</a>
		</li>

		<li>
			<a href="{{route('admin-settings')}}">
				<span class="fa fa-dashboard"> </span>		
				System Settings

			</a>
		</li>
	@endif
</ul>

<footer>
	Developed by: Jesus Sumadia<br>
	<span>09056194798<br>
	jesssumadia28@gmail.com</span>
</footer>
