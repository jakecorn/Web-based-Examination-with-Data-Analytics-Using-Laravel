	
<?php
use Modules\Teacher\Http\Controllers\TeacherController;
?>
	
<div class="containter-fluid">

	@include('teacher::classrecord.inc.buttons')
	@include('base::inc.message')
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		

		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="glyphicon glyphicon-list-alt"></span>Class Record
					<a href="{{route('classrecordupdate',$detail[0]->id)}}" class="fa fa-gear option" title="Configure class record"></a>
				</h4>
			</div>
		</div>
		
		<div class="containter">
	   		<br>
	   		<img src='/images/loader.gif' class='loader hidden'>
	   		<table>
	   			<tr>
	   				<td width="80px"><label for="sub_code">Course </label></td>

	   				<td>: {{$detail[0]->sub_code}} {{$detail[0]->sub_desc}} - {{$detail[0]->sub_sec}}</td>
	   			</tr>

	   			<tr>
	   				<td><label for="sub_code">Schedule</label></td>
	   				<td>: {{$detail[0]->day}} {{$detail[0]->time}}</td>
	   			</tr>

	   			<tr>
	   				<td><label for="sub_code">Formula</label></td>
	   				<td>: ( (Raw Score / Total Score) x {{$detail[0]->formula_times}}% ) + {{$detail[0]->formula_plus}} = {{$detail[0]->type}} Grade</td>
	   			</tr>

	   			<tr>
	   				<td><label for="sub_code">Term </label> </td>
	   				<td>: {{$detail[0]->type}}</td>
	   			</tr>
	   		</table>
	   		 <br>

	   		<div class="table-container table-responsive"> 			

			   @include('teacher::Classrecord.class_record_body')				
		   		<a href="{{route('classrecordprint2',$detail[0]->id)}}" class="btn btn-primary margin-top" target="n">
		   			<span class="fa fa-print margin-right"></span>
		   			Print Class Record
	   			</a>
	   			<br>
	   			<br>	
		   	</div>

	  	</div>
	</div>
</div>

<div class="modal"  id="custom_total_score_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Custom Total Score</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	    <div class="form-group">
			<label for="custom_value">Score</label>
			<input type="number" class="form-control" id="custom_value" placeholder="Total Score">
		</div>
		<div class="form-check">
			<input type="checkbox" class="form-check-input" id="removescore">
			<label class="form-check-label" for="removescore">Remove manually added score</label>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="total_score_save({{$detail[0]->id}})">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>