
<?php
use Modules\Examination\Http\Controllers\ExaminationController;

?>
@include('examination::inc.examtab')
@include('base::inc.message')


<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Examination List</h4>
			</div>
		</div>

		<div class="table-responsive font-size margin-top">
			<table class="table table-hover" width="100%">
				<thead>				
					<tr>
	 					<th colspan="2">Classes</th>
						<th>Duration</th>
						<th>Term</th>
						<th>Topics</th>
						<th>Type</th>
						<th>Answer&nbsp;Visibility</th>
						<th style="width:100px;text-align:center" align="center">Actions</th>
					</tr>
				</thead>

				@if(count($examination_list)>0)
					<?php $no=1;?>
					@foreach($examination_list as $exam)
						<tr>
							<td>
								{{$no++}}.
							</td>
							<td>
								<?php $class = ExaminationController::classList($exam->examination_id);?>
								@if(count($class)>0)
									@foreach($class as $class)
										{{$class->sub_code}} - 
										{{$class->sub_sec}}
										{{$class->day}}
										{{$class->time}}
										<br>
 									@endforeach()
								@endif
							</td>					

							<td>{{$exam->duration}}</td>
							<td>{{$exam->type}}</td>

							<td>
								<?php $topic = ExaminationController::topic($exam->examination_id);?>
								@foreach($topic as $topic)
									{{$type[$topic->exam_type]}} - {{$topic->exam_topic}}<br>
								@endforeach
							</td>
							<td>{{$exam->exam_type==1?'Long Exam':'Quiz'	}}</td>
							<td align="center">
								<label style="font-weight:normal">
								  <input type="checkbox" {{$exam->answer_visibility==1?'checked=true':''}} name="visibitliy{{$exam->examination_id}}" class="visibility" onchange="return visibility(this,event)"  id="{{$exam->examination_id}}"  value="{{$exam->answer_visibility}}" data-size="mini" data-on="success"  data-on-label="Visible">
								</label>

								<div class="img-load" style="display:none">
									
										<img src='/images/loader.gif' class='loader ' style="width:20px">
								</div>


							</td>
							<td align="center">
								<a data-href="{{route('deleteexam',$exam->examination_id)}}" data-toggle="modal" data-target="#deleteModal"  title="Delete examination" class="fa fa-remove btn-danger action"></a>
							
								<a href="{{route('updateexam',$exam->examination_id)}}" data-toggle="tooltip" title="Update examination" class="fa fa-pencil btn-success action"></a>
								
								

								<a href="{{route('showexam',$exam->examination_id)}}" data-toggle="tooltip" data-placement="left" title="Show Examination" class="fa  fa-mail-forward btn-primary action"></a>
							</td>
						</tr>
					@endforeach

				@endif
				
			</table>
		</div>	
		
	</div>
</div>

<script type="text/javascript">

 $("input:checkbox").bootstrapSwitch();
function visibility(a,b){
		var id = $(a).attr("id");	
		var val = 0;
		$(a).parents('td').find('div').toggle();
		$(a).parents('td').find('label').toggle();

		if($(a).is(':checked')){
			val=1;
		}

	$.ajax({
		type: 'POST',
		url: "examination/"+id+"/visibility",
		data: {"visibility": val,"examination_id": id},

		success:function(data){
			$(a).parents('td').find('div').toggle();
			$(a).parents('td').find('label').toggle();
			$(a).attr("value",val);
 		},
		error:function(){				
				var c = $(a).attr("value");
				if(c==1){
      	  			$(a).prop("checked",true);

				}else{
      	  			$(a).prop("checked",false);
				}

	   	  		$(a).parents('td').find('div').toggle();
				$(a).parents('td').find('label').toggle();
		}
	});
}
</script>