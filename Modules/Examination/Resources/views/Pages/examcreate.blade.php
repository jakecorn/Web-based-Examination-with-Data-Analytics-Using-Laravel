
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
			    	<textarea type="text" class="form-control" placeholder="General instruction for this examination" rows="3" id="gen_instruction" name="gen_instruction" required="true"></textarea>
				</div>

				<div class="form-group row">
					<label for="exam_instruction">Time Limit</label><br>
					<span>Set time limit?</span>
					<div class="radio">
						  <label class="margin-right">
						    <input type="radio" required="" name="time_limit" value="yes">
						    Yes
						  </label>

						   <label>
						    <input type="radio" checked="" required="" name="time_limit" value="no">
						    No
						  </label>
					</div>

					<input type="number"  class="form-control" name="duration" placeholder="Duration in minutes" style="display:none">
				</div>

				<div class="form-group row">
					<label for="exam_instruction">Type</label><br>
					<div class="radio">
						  <label class="margin-right">
						    <input type="radio" required="" name="is_long_exam" value="1" checked>
						    Long Exam 
						  </label>

						   <label>
						    <input type="radio" required="" name="is_long_exam" value="0">
						    Quiz
						  </label>
					</div>
				</div>

				<div class="form-group row">
					<label for="exam_instruction">Examination For</label>
					
					@if(count($class_list)>0)
						@foreach($class_list as $class)
							<div class="checkbox">
							    <label>
							      <input type="checkbox" name="class[]"  class="classes" value="{{$class->id}}"> {{$class->sub_code}} - {{$class->sub_sec}} {{$class->day}} {{$class->time}}
							    </label>
							</div>
						@endforeach
					@endif

				</div>

				<div class="form-group row">
					<b>Type of Examination</b>
					<a class="btn btn-default btn-xs action-button" data-toggle="tooltip" title="Add new type of examination" style="color:gray" onclick="addPart()"><span class="fa fa-plus"></span> Part</a>
				</div>

				<div class="part-group">					
					<div class="form-group row">
						<label class="part-number">Part 1</label>
						<a class="btn btn-default btn-xs action-button remove-part" data-toggle="tooltip" title="Remove this part of examination" style="color:gray;display:none" onclick="removePart(this)">
							<span class="fa fa-remove"></span> Part</a>
						<br>

						<label>Examination Type</label>
						<select class="form-control" name="exam_type[]">
							<option value="mul">Multiple Choice</option>
							<option value="mat">Matching Type</option>
							<option value="tru">True or False</option>
							<option value="ide">Identification</option>
							<option value="ess">Essay</option>
						</select>
					</div>

					<div class="form-group row">
						<label>Topic</label>				
						<input type="text" placeholder="Topic for each part of the examination" required="" class="form-control" name="exam_topic[]">
					</div>

					<div class="form-group row">
						<label>Instruction</label>				
						<textarea type="text" placeholder="Instuction for each part of the examination" required="" class="form-control" name="exam_instruction[]"></textarea>
					</div>
				</div>

				<button class="btn btn-primary" id="saveExamButton" style="margin-left:-15px">Save Examination</button>
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