@if(count($errors))
	<div class="alert alert-danger margin-top">
		<strong>Whoops!</strong> There were some problems detected.
		<br/>
		<ul>
				<?php $p_error="";?>
				@foreach($errors->all() as $error)
					@if($error!=$p_error)
						<li>
							{{$error}}
							<?php $p_error=$error;?>
						</li>
					@endif
				@endforeach
		</ul>
	</div>
@endif