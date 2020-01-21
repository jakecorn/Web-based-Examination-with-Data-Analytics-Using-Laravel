<?php
use Modules\Announcement\Http\Controllers\FileController;

?>
@include('announcement::inc.filetab')
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-floppy-o"></span>File List</h4>
			</div>
		</div>
<?php $class_name = array(strtoupper("audio/mp3")=>"fa-file-audio-o",strtoupper("application/vnd.openxmlformats-officedocument.wordprocessingml.document")=>"fa-file-word-o",strtoupper("application/vnd.ms-powerpoint")=>"fa-file-powerpoint-o",strtoupper("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")=>"fa-file-excel-o",strtoupper("application/octet-stream")=>"fa-file-zip-o","IMAGE"=>"fa-file-image-o",strtoupper("application/pdf")=>"fa-file-pdf-o",strtoupper("application/x-msdownload")=>"fa-archive");?>
		<div class="table-responsive font-size margin-top">
			<table class="table table-hover" width="100%">
				<thead>				
					<tr>
	 					<th colspan="2">File</th>
						<th style="text-align:center;width:100px" align="center">Actions</th>
					</tr>

					@if(count($file)>0)
						@foreach($file as $a)
							<tr>
								<td style="width:50px;">
								<?php
									$fa_class="fa-files-o";
									if(array_key_exists($a->file_type, $class_name)){
										$fa_class=$class_name["$a->file_type"];

									}
								?>
									<div class="fa {{$fa_class}}"  style="background:#5f5f5f;width:42px;text-align:center;padding:8px;font-size:25px;border-radius:5px;color:white;">
										
									</div>
								</td>
								<td>
									<b>{{$a->file_name}}</b><br>
									{{$a->description}}
									
									<div style="color:#8d8c8b;line-height:14px;">
										<div class="margin-top margin-bottom">
											<small>
												{{date('jS F Y',strtotime($a->date))}}
												{{$a->time}}
											</small>
										</div>
											

										<small>
											Posted to:<br><br>
											<?php $class=FileController::fileClass($a->file_id);?>
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
									<a data-href="{{route('deleteFile',$a->file_id)}}"  data-toggle="modal" data-target="#deleteModal" title="Delete file" class="fa fa-remove btn-danger action"></a>
								
									<a href="{{route('updateFile',$a->file_id)}}" data-toggle="tooltip" title="Update File" data-placement="left" class="fa fa-pencil btn-success action"></a>
									<a  download="{{$a->file_name}}" href="/file/{{$a->rand_name}}" data-toggle="tooltip" title="Download File" data-placement="left" class="fa fa-cloud-download btn-primary action"></a>
									
								</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="123">
								<div class="alert alert-warning text-center">
									No File
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