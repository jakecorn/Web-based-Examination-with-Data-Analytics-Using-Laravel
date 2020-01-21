<?php
use Modules\Student\Http\Controllers\StudentController;
use Modules\Utilitize\Util;
$class_name = array(strtoupper("audio/mp3")=>"fa-file-audio-o",strtoupper("application/vnd.openxmlformats-officedocument.wordprocessingml.document")=>"fa-file-word-o",strtoupper("application/vnd.ms-powerpoint")=>"fa-file-powerpoint-o",strtoupper("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")=>"fa-file-excel-o",strtoupper("application/octet-stream")=>"fa-file-zip-o","IMAGE"=>"fa-file-image-o",strtoupper("application/pdf")=>"fa-file-pdf-o",strtoupper("application/x-msdownload")=>"fa-archive");
?>
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-files-o"></span>File List</h4>
			</div>
		</div>

		<div class=" font-size margin-top">
			<table class="table table-hover" width="100%">

				<?php $fileArray = array();?>
				@if(count($file)>0)
					@foreach($file as $a)
						<?php array_push($fileArray, $a->file_id)?>
						<tr>
							<td style="width:50px">
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
								<b>{{$a->file_name}}</b> <a href="/file/{{$a->rand_name}}" download="{{$a->file_name}}" style="color:blue"><small>Download</small></a>
								<br>
								{{$a->description}}<br>

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
								No file posted
							</div>
						</td>
					</tr>						
				@endif

			</table>
		</div>		
	</div>
</div>

<?php StudentController::saveFileNotification($fileArray);?>
