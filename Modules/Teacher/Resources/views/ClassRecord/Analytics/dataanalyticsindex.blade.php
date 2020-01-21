<div class="containter-fluid">
@include("base::inc.message")
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-line-chart"></span>Item Analysis</h4>
			</div>
		</div>

		<div class="form-group">
			<label class="margin-top">Type</label>
			<select class="form-control"  onchange="return analyticsType(this)" name="type">
				<option value="analysis">Item Analysis</option>
				<option value="statistics">Item Statistics</option>
			</select>
		</div>

		<div class="form-group">
			<label>Class</label>
			<select class="form-control"  name="class">
				@foreach($class as $class)
					<option value="{{$class->id}}">{{$class->sub_code}} {{$class->sub_desc}} - {{$class->sub_sec}} {{$class->day}} {{$class->time}}</option>					
					<option value="{{$class->id}}/All">{{$class->sub_code}} {{$class->sub_desc}} - All Sections</option>					
				@endforeach
			</select>
		</div>
 			<button class="btn btn-primary width-n" onclick="showAnalysis()">Show</button>
 	</div>
</div>

<script>
	function analyticsType(a) {
		var link = $(a).val();
		window.location = "/teacher/data-analytics/"+link;
	}

	function showAnalysis() {
		var type = $('select[name=type]').val();
		var class_id = $('select[name=class]').val();
		window.location = "/teacher/data-analytics/analysis/class/"+class_id;
	}
</script>