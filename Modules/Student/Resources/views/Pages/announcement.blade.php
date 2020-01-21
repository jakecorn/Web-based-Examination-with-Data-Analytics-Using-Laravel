<?php
use Modules\Student\Http\Controllers\StudentController;
use Modules\Utilitize\Util;

?>
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-comments-o"></span>Announcement List</h4>
			</div>
		</div>

		<div class=" font-size margin-top">
			<table class="table table-hover" width="100%">

				<?php $announcementArray = array();?>
				@if(count($announcement)>0)
					@foreach($announcement as $a)
						<?php array_push($announcementArray, $a->announcement_id)?>
						<tr>
							<td style="width:50px">
								<div style="background:#5f5f5f;text-align:center;padding:8px 0 8px 0;width:42px;border-radius:5px;color:white;">
									<b>{{substr($a->date, 8)}}</b>
								</div>
							</td>
							<td>
								{{$a->announcement}}<br>

								<div class="margin-top">										
									<small>
										
										{{date('jS F Y',strtotime($a->date))}}
										{{$a->time_posted}}<br>

										by: {{$a->name}}, {{$a->sub_code}} - {{$a->sub_sec}} {{$a->day}} {{$a->time_schedule}}<br>
									</small>
								</div>
							</td>
						</tr>
					@endforeach
				@else
					<tr>
						<td colspan="123">
							<div class="alert alert-warning text-center">
								No announcement
							</div>
						</td>
					</tr>						
				@endif

			</table>
		</div>		
	</div>
</div>

<?php StudentController::saveNotification($announcementArray);?>
