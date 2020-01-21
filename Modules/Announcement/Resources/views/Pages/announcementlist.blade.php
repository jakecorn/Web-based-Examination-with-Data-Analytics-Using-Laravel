<?php
use Modules\Announcement\Http\Controllers\AnnouncementController;

?>
@include("announcement::inc.announcementtab")
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-comments-o"></span>Announcement List</h4>
			</div>
		</div>

		<div class="table-responsive font-size margin-top">
			<table class="table table-hover" width="100%">
				<thead>				
					<tr>
	 					<th colspan="2">Announcement</th>
						<th style="text-align:center" align="center">Actions</th>
					</tr>

					@if(count($announcement)>0)
						@foreach($announcement as $a)
							<tr>
								<td style="width:50px;">
									<div style="background:#5f5f5f;text-align:center;padding:8px 0 8px 0;width:42px;border-radius:5px;color:white;">
										<b>{{substr($a->date, 8)}}</b>
									</div>
								</td>
								<td>
									{{$a->announcement}}<br>
									<div style="color:#8d8c8b;line-height:14px;">
										<div class="margin-top margin-bottom">
											<small>
												{{date('jS F Y',strtotime($a->date))}}
												{{$a->time}}
											</small>
										</div>
											

										<small>
											Posted to:<br><br>
											<?php $class=AnnouncementController::AnnouncementClass($a->id);?>
											@if(count($class)>0)
												@foreach($class as $class)
													{{$class->sub_code}}
													{{$class->sub_sec}}
													{{$class->day}}
													{{$class->time}}<br>											
												@endforeach
											@endif
										</small>
									</div>

									

								</td>

								<td align="center">
									<a data-href="{{route('deleteAnnouncement',$a->id)}}"  data-toggle="modal" data-target="#deleteModal" title="Delete announcement" class="fa fa-remove btn-danger action"></a>
								
									<a href="{{route('updateAnnouncement',$a->id)}}" data-toggle="tooltip" title="Update announcement" data-placement="left" class="fa fa-pencil btn-success action"></a>
									
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
				</thead>				
			</table>
		</div>		
	</div>
</div>

<script type="text/javascript">
	$(function(){
	$('[data-toggle="tooltip"]').tooltip();
});

	$('[data-toggle="modal"]').click(function(event) {
		var link = $(this).attr('data-href');
		$('#deleteModal'+" .btn-ok-delete").attr('href', link);
	});
</script>