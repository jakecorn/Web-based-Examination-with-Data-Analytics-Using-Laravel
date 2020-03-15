@include('admin::pages.usertab')
<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="fa fa-user"></span>User

				<div class="search-container">
					<span class="fa fa-search icon"></span>					
					<input type="search" name="search" class="search-field" onkeyup="searchUser(this.value)">
				</div>
				</h4>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-hover userlist">
				<thead>				
					<tr>
						<th>ID Number</th>
	 					<th>Name</th>
						<th>Username</th>
						<th>Role</th>
						<th><center>Status</center></th>
						<th>Created</th>
						@if($navigation!="Masterlist")
							<th align="center"><center>Password Reset</center></th>
						@endif
					</tr>
				</thead>
				<tbody class="search-result">
					
				</tbody>
				<tbody class="users">
					<?php $first_id=0;?>
					<?php $last_id=0;?>
					<?php $count_user=count($user);?>
					@if($count_user>0)

						<?php $first_id=$user[0]->id;?>
						@foreach($user as $user)
						<?php $last_id=$user->id;?>
						@if($navigation=="Masterlist")
							@if($user->is_registered=="Done manual registration but not found in the masterlist")
								@continue
							@endif
						@endif
							<tr>
								<td>{{$user->id_number}}</td>
								<td>{{$user->name}}</td>
								<td>{{$user->username}}</td>
								<td>{{$user->role}}</td>
								<td align="center">
									@if($navigation!="Masterlist")
										<span class="fa fa-toggle-on on" style="color:green;font-size:20px;{{$user->status==0? "display:none":""}}" onclick="storeStatus(0,this,{{$user->id}}, '{{$user->is_registered}}')" data-toggle="tooltip" title="Click to deacticvate account"></span>
	 									<span class="fa fa-toggle-off off" style="color:red;font-size:20px;{{$user->status==1? "display:none":""}}" onclick="storeStatus(1,this,{{$user->id}}, '{{$user->is_registered}}')" data-toggle="tooltip" title="Click to activate account"></span>
										<img src='/images/loader.gif' class='loader ' style="display:none;width:20px">
									@else
										@if($user->status==1)
											<span style="color:green">Activated</span>
										@else
											@if($user->is_registered=="Not registered")
											<span style="color:red">Not yet registered</span>
											@endif
											@if($user->is_registered=="Done manual registration and present in the masterlist")
											<span style="color:green">Ready for activation</span>
											@endif
										@endif
									@endif
								</td>
								<td>{{$user->created_at}}</td>
								@if($navigation!="Masterlist")
									<td align="center" class="list-action"> 							
										@if($user->status==1)
												<a data-toggle="tooltip" onclick="passwordReset({{$user->id}},this)" title="Reset password" class="fa fa-refresh action btn-success"></a>
												<img src='/images/loader.gif' class='loader ' style="display:none;width:20px">
										@endif
									</td>
								@endif
							</tr>
						@endforeach

					@endif
				</tbody>
				
			</table>

			@if($list!="first" && $navigation!="Masterlist")
				<a href="{{ url()->previous() }}" class="btn btn-default btn-sm width-n margin-right" style="color:gray">
					 <span class="fa fa-backward margin-right"></span> Previous
				</a>
			@endif

			@if($count_user==15) 
				<a href="/admin/{{$last_id}}" class="btn btn-default btn-sm width-n" style="color:gray">
					 Next <span class="fa fa-forward margin-left"></span>
				</a>
			@endif

		</div>	
		
	</div>
</div>

<style type="text/css">
	.search-container .icon{
		position: absolute;
		left:10px;
	}

	.search-container #upload_user_button{
        position: absolute;
        left: -115px;
        top: -11px;
	}

	.search-container{
		float:right;
		position: relative;
	}
	.search-field{
		min-width:200px;width:38%;
		border:1px solid #dcdcdc;
		outline:none;
		padding:8px;
		padding-left:32px;
		border-radius: 3px;
		font-weight:normal;
		margin-top:-10px;
	}
</style>
<div id="passwordReset" class="modal fade"  role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        	<h4 class="modal-title"><a class="fa fa-warning alert-warning margin-right"></a><a class="modal-title-me">Password Reset</a></h4>
		      </div>
		      <div class="modal-body">
		       		<p class="modal-body-content"></p>
		      </div>
		      <div class="modal-footer">
		        	{{-- <a class="btn btn-primary btn-ok-delete width-n">Yes</a> --}}
		        	<button type="button" class="btn btn-default width-n" data-dismiss="modal">OK</button>
		      </div>
	    </div>
	  </div>
</div>

<button data-toggle="modal" data-target="#passwordReset" id="passwordResetButton" style="display:none"></button>


