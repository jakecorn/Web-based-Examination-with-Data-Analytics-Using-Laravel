@include('admin::pages.download')
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-database"></span>Delete Records</h4>
			</div>
		</div>

		<form class="mv-margin" method="post" id="delete_database_form">
			{{csrf_field()}}
			<div class="form-group">
			    <div>Choose what records to be deleted based on school year. You can only delete records which are 2 years older. It is strongly recommended to backup the database before deleting the old records. Once the deletion has started, it cannot be stopped.</div>
		   		<label for="announcement">School Year</label>
		   		<select class="form-control" name="sy">
		   			<?php $current_year = date("Y"); $year_limit = 2;?>
		   			@if(count($sy)>0)
		   				@foreach($sy as $s)
		   				    <?php
		   				        $start_year = explode("-", $s->sy);
		   				        $disabled = "";
		   				        $disabled = "disabled=1";
		   				        if(($current_year-$start_year[0]) >2) {
		   				            $disabled = "";
		   				        }
		   				    ?>
		   					<option selected=""{{$disabled}}>{{$s->sy}}</option>
		   				@endforeach
		   			@endif
		   		</select>
			</div>
			<div class="form-group">
			   	<button type="submit" class="btn btn-primary" id="delete_database_form_button"><span class="glyphicon glyphicon-submit"></span>Delete Records</button>
			</div>

		</form>
		
	</div>
</div>
<script>
    $(function(){
        $('#delete_database_form_button').click(function(event){
            event.preventDefault();
            swal.fire({
              title: 'Are you sure?',
              text: "You want to delete the old database records? Make sure to backup the database before deleting the old records.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, proceed',
              cancelButtonText: 'No, cancel!',
              reverseButtons: true
              }).then((result) => {
              if (result.value) {
                $('#delete_database_form').submit();
              } else if (result.dismiss === Swal.DismissReason.cancel) {
                return false;
              }
            });
        });
    });

</script>