<?php
	use Modules\Utilitize\Util;
?>
@include('base::inc.message')

<div class="containter-fluid">
	@include('teacher::classrecord.inc.buttons')
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Class Record</h4>
			</div>
		</div>
		
		<form class="mv-margin" method="post">
			  <div class="form-group row">
			   	<div class="col-sm-6">
			   		<label for="sub_code">Subject Code <span style="color:red">*</span></label>
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
			    	<input type="text" required="" class="form-control" value="{{old('sub_code')}}" id="sub_code" name="sub_code" placeholder="Subject Code">
			   	</div>

			   	<div class="col-sm-6">
			   		<label for="sub_desc">Subject Description <span style="color:red">*</span></label>
			    	<input type="text" required="" class="form-control" id="sub_desc" value="{{old('sub_desc')}}" name="sub_desc" placeholder="Subject Description">
			   	</div>
			  </div>

			  <div class="form-group row">
			  	<div class="col-sm-4">
			  		<label for="day">Day <span style="color:red">*</span></label>
			    	<input type="text" required="" class="form-control" value="{{old('day')}}" id="day" list="day_list" name="day" placeholder="Day">
			    	<datalist id="day_list">
                        <option value="M-W-F">
                        <option value="T-TH">
                        <option value="SAT">
                        <option value="SUN">
                    </datalist>
			  	</div>
			  	<div class="col-sm-4">
			  		<label for="time">Time <span style="color:red">*</span></label>
			    	<input type="text" required="" class="form-control" value="{{old('time')}}" id="time" list="list_time" name="time" placeholder="Time">
			    	<datalist id="list_time">
			    	    <?php
			    	        $start = 7;
			    	        $starttime = "07:00";
			    	        $endtime = "07:00";
			    	        $starttime2 = "07:00";
			    	        $endtime2 = "07:00";
			    	        while ($start <24){
			    	            $endtime2 = date('H:i', strtotime('+60 minutes', strtotime($endtime2)));
			    	            echo "<option value='".$starttime2.'-'.$endtime2."'>";
			    	            $starttime2 = $endtime2;
			    	            $start++;
			    	        }
                            $start = 7;
			    	        while ($start <18){
			    	            $endtime = date('H:i', strtotime('+90 minutes', strtotime($endtime)));
			    	            echo "<option value='".$starttime.'-'.$endtime."'>";
			    	            $starttime = $endtime;
			    	            $start++;
			    	        }

			    	    ?>

                    </datalist>
			  	</div>
			  	<div class="col-sm-4">
			  		<label for="sub_sec">Section <span style="color:red">*</span></label>
			    	<input type="text" required="" maxlength="1" class="form-control" value="{{old('sub_sec')}}" id="sub_sec" list="list_section" name="sub_sec" placeholder="Section Letter">
                    <datalist id="list_section">
                        <?php
                            foreach (range('A', 'Z') as $char) {
                                echo "<option value='".$char."'>";
                            }
                        ?>
			  	    </datalist>
			  	</div>
			  </div>

			  <br>

			  <div class="form-group row">
			  	<div class="col-md-10">
			    	<label for="exampleInputEmail1">Criteria <span style="color:red">*</span></label> 
					<button onclick="return addCriteria()" id="addCriteria" class="btn btn-sm btn-default margin-left">
						<span class="glyphicon glyphicon-plus"></span>
						Criteria
					</button>								    	
			  	</div>
			  	<div class="col-md-2">
			    	<label for="exampleInputEmail1">Percentage <span style="color:red">*</span></label>
			  	</div>
			  </div>

			  <div class="form-group row criteria count-me">
			    <div class="col-md-10">
			    	<input type="text" name="criteria[]"  required=""  class="form-control"  readonly="true" value="Attendance" placeholder="Criteria">
			    </div>
			    
			    <div class="col-md-2">
			    	<input type="number" name="percent[]"  required=""  class="form-control"   placeholder="%">
			    </div>
			  </div>

			  <div class="form-group row criteria count-me">
			    <div class="col-md-10">
			    	<input type="text" name="criteria[]"  required=""  class="form-control"  value="Assignment" placeholder="Criteria">

			    	<a class="fa fa-close criteria-remove" onclick="criteriaRemove(this)" title="Remove criteria"></a>
			    </div>
			    
			    <div class="col-md-2">
			    	<input type="number" name="percent[]"  required=""  class="form-control"  placeholder="%">
			    </div>
			  </div>

			  <div class="form-group row criteria count-me">
			    <div class="col-md-10">
			    	<input type="text" name="criteria[]"  required=""  class="form-control"  value="Quiz" placeholder="Criteria">
			    	<a class="fa fa-close criteria-remove" onclick="criteriaRemove(this)" title="Remove criteria"></a>
			    </div>
			    
			    <div class="col-md-2">
			    	<input type="number" name="percent[]"  required=""  class="form-control"  placeholder="%">
			    </div>
			  </div>

			  <div class="form-group row criteria count-me">
			    <div class="col-md-10">
			    	<input type="text" name="criteria[]"  required=""  class="form-control"  value="Participation" placeholder="Criteria">
			    	<a class="fa fa-close criteria-remove" onclick="criteriaRemove(this)" title="Remove criteria"></a>
			    </div>
			    
			    <div class="col-md-2">
			    	<input type="number" name="percent[]"  required=""  class="form-control"  placeholder="%">
			    </div>
			  </div>

			  <div class="form-group row criteria count-me">
			    <div class="col-md-10">
			    	<input type="text" name="criteria[]" required=""  class="form-control"  value="Project" placeholder="Criteria">
			    	<a class="fa fa-close criteria-remove" onclick="criteriaRemove(this)" title="Remove criteria"></a>
			    </div>
			    
			    <div class="col-md-2">
			    	<input type="number" name="percent[]" required=""  class="form-control"  placeholder="%">
			    </div>
			  </div>

			  <div class="form-group row criteria count-me">
			    <div class="col-md-10">
			    	<input type="text" name="criteria[]"  required="" class="form-control"  placeholder="Criteria">
			    	<a class="fa fa-close criteria-remove" onclick="criteriaRemove(this)" title="Remove criteria"></a>
			    </div>
			    
			    <div class="col-md-2">
			    	<input type="number" name="percent[]" required=""  class="form-control"  placeholder="%">
			    </div>
			  </div>

			  <div class="form-group row count-me">
			    <div class="col-md-10">
			    	<input type="text" readonly="" name="criteria[]" required="" value="{{Util::get_session('class_record_type')}} Exam" class="form-control"  placeholder="Criteria">
			    </div>
			    
			    <div class="col-md-2">
			    	<input type="number" name="percent[]" required="" class="form-control"  placeholder="up to 40 % only" max="40">
			    </div>
			  </div>

			  <div class="form-group">
			    <div class="col-md-10">
			    	<label class="pull-right">Total Percentage</label>
			    </div>
			    
			    <div class="col-md-2 text-center total-percentage" style="font-weight:bold">
			    	0
			    </div>
			  </div>

		  	  <label for="formula">Formula: ((((Raw Score/Total Score) * fvalue)  + svalue ) * Criteria percentage)</label><br>
 			  	<label style="font-weight:normal"> 			  		
	 			  	<input type="checkbox" onclick="return formulaChange(this)">&nbsp; Zero-based computation 
				  	<small>( Raw Score x 100% ) + 0</small>
 			  	</label>

			  <div class="form-group row formula">
			  		<div class="col-md-6"> <span style="color:red">*</span>
			    		<input type="number" name="times" required="" value="{{$errors->has('times')? old('times'):""}}" class="form-control" id="formula" placeholder="Percentage to multiply">
			  		</div>

			  		<div class="col-md-6">
					  	<span style="color:red">*</span>
			     		<input type="number" name="plus" required="" value="{{$errors->has('plus')? old('plus'):""}}" class="form-control"  placeholder="Plus">
			  		</div>
			  </div>


			  <div class="form-group row">
			  		<div class="col-md-6">
			  			<label for="midterm_percentage"  style="font-weight:normal">Midterm Percentage % <span style="color:red">*</span></label> 
			    		<input type="number" name="midterm_percentage" required="" value="{{$errors->has('midterm_percentage')? old('midterm_percentage'):""}}" class="form-control term_percentage" id="midterm_percentage" placeholder="Midterm Percentage">
			  		</div>

			  		<div class="col-md-6">
			  			<label for="final_percentage"  style="font-weight:normal">Final Percentage % <span style="color:red">*</span>	</label> 
			     		<input type="number" name="final_percentage" required="" value="{{$errors->has('final_percentage')? old('final_percentage'):""}}" class="form-control term_percentage" id="final_percentage" placeholder="Final Percentage">
			  		</div>
			  </div>

			  <div class="form-group row">
			   	<div class="col-md-5">
			   		<button type="submit" class="btn btn-primary submit-button">Save Class Record</button>
			   	</div>
			  </div>



			</form>
	</div>
</div>