 <?php
$type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");
 ?>
@include('examination::inc.examtab')
	@include('base::inc.message')
<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Examination</h4>
			</div>
		</div>
		
		<div class="padding">
			
			<form class="mv-margin" method="post" id="exam_form">
				<div class="form-group row">
			   		<label for="gen_instruction">General Instruction</label>
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
			    	<input type="hidden" name="examination_id" value="{{$examination[0]->id}}">
			    	<textarea required="" class="form-control" id="gen_instruction" rows="3" name="gen_instruction" placeholder="General Instruction">{{$examination[0]->gen_instruction}}</textarea>
				</div>

				<div class="form-group row">
					<label for="exam_instruction">Time Limit</label><br>
					<span>Set time limit?</span>
					<div class="radio">
						  <label class="margin-right">
						    <input type="radio" required="" {{$examination[0]->duration=="None"? '':'checked'}} name="time_limit" value="yes">
						    Yes
						  </label>

						   <label>
						    <input type="radio" required="" {{$examination[0]->duration=="None"? 'checked':''}} name="time_limit" value="no">
						    No
						  </label>
					</div>
					<?php $display="";?>
					@if($examination[0]->duration=="None")
						<?php $display="style=display:none";?>
					@endif
					<input type="number"    <?php echo $display="style=display:none";?> class="form-control" value="{{$examination[0]->duration=="None"? '':$examination[0]->duration}}" name="duration" class="hidden" placeholder="Duration in minutes">
				</div>

				<div class="form-group row">
					<label for="exam_instruction">Type</label><br>
					<div class="radio">
						  <label class="margin-right">
						    <input type="radio" required="" {{$examination[0]->exam_type==1? 'checked':'checked'}} name="is_long_exam" value="1">
						    Long Exam 
						  </label>

						   <label>
						    <input type="radio" required="" {{$examination[0]->exam_type==0? 'checked':''}} name="is_long_exam" value="0">
						    Quiz
						  </label>
					</div>
				</div>

				<div class="form-group row">
					<label for="exam_instruction">Examination For</label>
					
					@if(count($classes)>0)
						@foreach($classes as $class)
							<div class="checkbox">
							    <label>
							      <input type="checkbox" name="class[]" class="classes" value="{{$class->id}}" @foreach($class_list as $c_list) {{$c_list->class_record_id==$class->id? 'checked': ''}} @endforeach> {{$class->sub_code}} - {{$class->sub_sec}} {{$class->day}} {{$class->time}}
							    </label>
							</div>
						@endforeach
					@endif

				</div>

				<div class="form-group row">
					<b>Parts of Examination</b>
					<a class="btn btn-default btn-xs action-button" data-toggle="tooltip" title="Add new part of examination" style="color:gray" onclick="addPart()"><span class="fa fa-plus"></span> Part</a>
				</div>

				@foreach($part as $part)
					<div class="part-group">					
						<div class="form-group row">
							<label class="part-number">Part {{$part->part_num}}</label>
							{{-- <a class="btn btn-default btn-xs action-button" data-toggle="tooltip" title="Remove this part of examination" style="color:gray" onclick="removePart(this)">
							<span class="fa fa-remove"></span> Part</a> --}}
							<br>

							<label>Examination Type</label> <span class="type-temp-name">{{$type[$part->exam_type]}}</span>
							<select class="form-control" name="exam_type[]" style="display:none">
								<option value="mul" {{$part->exam_type=="mul"? 'selected':''}} >Multiple Choice</option>
								<option value="mat" {{$part->exam_type=="mat"? 'selected':''}}>Matching Type</option>
								<option value="tru" {{$part->exam_type=="tru"? 'selected':''}}>True or False</option>
								<option value="ide" {{$part->exam_type=="ide"? 'selected':''}}>Identification</option>
								<option value="ess" {{$part->exam_type=="ess"? 'selected':''}} >Essay</option>
							</select>
						</div>

						<div class="form-group row">
							<label>Topic</label>				
							<input type="hidden" required="" name="part_id[]" value="{{$part->id}}">
							<input type="text" required="" class="form-control" name="exam_topic[]" value="{{$part->exam_topic}}">
						</div>

						<div class="form-group row">
							<label>Instruction</label>				
							<textarea required="" class="form-control" name="exam_instruction[]">{{$part->exam_instruction}}</textarea>
						</div>
					</div>
				@endforeach
				
				<a href="/teacher/examination" class="btn btn-default margin-right" style="color:gray;margin-left:-15px">Cancel Update</a>
				<button class="btn btn-primary">Save Update</button>
			</form>
		</div>

	</div>
</div>

<script type="text/javascript">
$('#exam_form').submit(function(){
	checked = $(".classes:checked").length;
    if(checked==0) {
        Swal.fire({
		  type: 'error',
		  title: 'Oops...',
		  text: 'Please select at least 1 class section.'
		});
        return false;
    }

});
</script>