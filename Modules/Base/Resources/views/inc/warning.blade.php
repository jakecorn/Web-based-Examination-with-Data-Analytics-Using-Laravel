@if(count($warning))
	<div class="alert alert-warning margin-top">
		<strong>Warning!</strong>
		<br/>
		<ul>
				<?php $p_error="";?>
				@foreach($warning->all() as $error)
					@if($error!=$p_error)
						<li>
							{!!$error!!}
							<?php $p_error=$error;?>
						</li>
					@endif
				@endforeach
		</ul>
	</div>
@endif