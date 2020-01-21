<div class="row button-container" style="margin-top:20px">
		<div class="col-sm-12">
			<div>

				<a href="{{route('createclassrecord')}}" class="mybutton green-bg">
					<span class="fa fa-plus-circle margin-right"></span>
					Add Class Record
				</a>

				<a href="{{route('classrecordlist')}}" class="mybutton gray2-bg">
					<span class="fa fa-sign-in margin-right"></span>
					All Class Records
				</a>
				@if($page_title!="classRecordList")
					@if($page_title!="createClassRecord")
					<a href="{{route('getgrade',$detail[0]->id)}}" class="mybutton gray2-bg">
						<span class="fa fa-bar-chart margin-right"></span>
						Semestral Grade
					</a>
					@endif
				@endif
			</div>
		</div>
</div>