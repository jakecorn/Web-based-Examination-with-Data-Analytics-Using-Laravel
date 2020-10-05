

<div class="containter-fluid">
	
	@include('teacher::classrecord.inc.buttons')
	@include('base::inc.message')

	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Class Record</h4>
			</div>
		</div>
		
		<form class="mv-margin" method="post">
			  <div class="form-group row">
			   	<div class="col-sm-6">
			   		<label for="sub_code">Course Code </label>
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
			    	<input type="hidden" name="class_record_id" value="{{$detail[0]->id}}">
			    	<input type="text" class="form-control" required id="sub_code" name="sub_code" placeholder="Course Code" value="{{$detail[0]->sub_code}}">
			   	</div>

			   	<div class="col-sm-6">
			   		<label for="sub_desc">Course Description </label>
			    	<input type="text" class="form-control" required id="sub_desc" name="sub_desc" placeholder="Course Description" value="{{$detail[0]->sub_desc}}">
			   	</div>
			  </div>

			  <div class="form-group row">
			  	<div class="col-sm-4">
			  		<label for="day">Day </label>
			    	<input type="text" class="form-control"  required id="day" name="day" placeholder="Day" list="day_list" value="{{$detail[0]->day}}">
			  	    <datalist id="day_list">
                        <option value="M-W-F">
                        <option value="T-TH">
                        <option value="SAT">
                        <option value="SUN">
                    </datalist>
			  	</div>
			  	<div class="col-sm-4">
			  		<label for="time">Time </label>
			    	<input type="text" class="form-control" required id="time" name="time" placeholder="Time" value="{{$detail[0]->time}}" list="list_time">
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
			  		<label for="sub_sec">Sesction </label>
			    	<input type="text" maxlength="1" class="form-control" required id="sub_sec" name="sub_sec" placeholder="Section Letter" value="{{$detail[0]->sub_sec}}" list="list_section">
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
			    	<label for="exampleInputEmail1">Criteria</label> 
					<a onclick="return addCriteria()" style="color:gray" id="addCriteria"  class="btn btn-sm btn-default margin-left">
						<span class="glyphicon glyphicon-plus"></span>
						Criteria
					</a>								    	
			  	</div>
			  	<div class="col-md-2">
			    	<label for="exampleInputEmail1">Percentage</label>
			  	</div>
			  </div>


			  @if(count($criteria)>0)
			  	@foreach($criteria as $criteria)
			  		 <div class="form-group row criteria count-me">
					    <div class="col-md-10">
					    	<input type="hidden" name="criteria_id[]" value="{{$criteria->id}}">
					    	<input type="text" @if($criteria->criteria=="Attendance" or $criteria->criteria=="Midterm Exam" or $criteria->criteria=="Final Exam") {{'readonly'}}  @endif  name="criteria[]"  required value="{{$criteria->criteria}}"  class="form-control" id="exampleInputEmail1" placeholder="Criteria">
					    	
					    	<?php $hidden="";?>
					    	@if($criteria->criteria=="Attendance" or $criteria->criteria=="Midterm Exam" or $criteria->criteria=="Final Exam")
 					    		<?php $hidden="display:none";?>
					    		
					    	@endif
					    	<a class="fa fa-close criteria-remove" data-toggle="tooltip" style="{{$hidden}}" onclick="criteriaRemove(this)" title="Remove criteria"></a>
					    </div>
					    
					    <div class="col-md-2">
					    	<input type="number" name="percent[]"  required value="{{$criteria->percent}}" class="form-control" id="exampleInputEmail1" placeholder="%">
					    </div>
					</div>
			  	@endforeach
			  @endif
			 
			 <div class="form-group">
			    <div class="col-md-10">
			    	<label class="pull-right">Total Percentage</label>
			    </div>
			    
			    <div class="col-md-2 text-center total-percentage" style="font-weight:bold">
			    	100
			  </div>

			  <label for="formula">Formula: ( Raw Score x 50% ) + 50</label><br>
 			  	<label style="font-weight:normal"> 			  		
	 			  	<input type="checkbox" onclick="return formulaChange(this)">&nbsp; Zero-based computation 
				  	<small>( Raw Score x 100% ) + 0</small>
 			  	</label>

 			  
			  <div class="form-group row formula">
			  		<div class="col-md-6">
			    		<input type="number" value="{{$detail[0]->formula_times}}" name="times" class="form-control" id="formula" placeholder="Percentage to multiply" required="">
			  		</div>

			  		<div class="col-md-6">
			     		<input type="number" name="plus" value="{{$detail[0]->formula_plus}}" class="form-control" id="exampleInputEmail1" placeholder="Plus" required="">
			  		</div>
			  </div>

			   <div class="form-group row">
			  		<div class="col-md-6">
			  			<label for="midterm_percentage"  style="font-weight:normal">Midterm Percentage %</label> 
			    		<input type="number" name="midterm_percentage" required="" value="{{$detail[0]->midterm_percentage}}" class="form-control term_percentage" id="midterm_percentage" placeholder="Midterm Percentage">
			  		</div>

			  		<div class="col-md-6">
			  			<label for="final_percentage"  style="font-weight:normal">Final Percentage %</label> 
			     		<input type="number" name="final_percentage" required="" value="{{$detail[0]->final_percentage}}" class="form-control term_percentage" id="final_percentage" placeholder="Final Percentage">
			  		</div>
			  </div>

			  <div class="form-group row">
			   	<div class="col-md-12">
			   		<a href="{{route('classrecord',Session::get('class_record_id'))}}" class="btn btn-default margin-right" style="color:gray" >Cancel Update</a>
			   		<button type="submit" class="btn btn-primary submit-button">Update Class Record</button>
			   		</form>
			   	</div>
			  </div>
			
	</div>
</div>