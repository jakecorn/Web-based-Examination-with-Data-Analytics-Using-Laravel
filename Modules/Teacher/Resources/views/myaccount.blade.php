
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-4">
				<h4 class="bold tab-title"><span class="fa fa-gear"></span>My Account</h4>
			</div>
		</div>

		<form class="mv-margin"  method="POST" role="form" enctype="multipart/form-data">
			{{csrf_field()}}
			<div class="form-group text-centesr">
				<img src="{{Auth::user()->photo}}" onerror="this.src='/uploads/images/user.png'" style="border:1px solid gray;border-radius:20px;width:200px"/>	   
			</div>
			
			<div class="form-group">
		   		<label>Choose a Picture</label>
		   		<input type="file" name="photo" class="form-control">
			</div>
			<div class="form-group">
		   		<label>Name</label>
		   		<input type="text" name="name" value="{{Auth::user()->name}}" class="form-control">
			</div>

			<div class="form-group">
		   		<label>Username</label> <small>Must be at least 6 characters</small>
		   		<input type="text" name="username" value="{{Auth::user()->username}}" class="form-control">
			</div>

			<div class="form-group">
		   		<label>Cellphone Number</label>
		   		<input type="text" name="cp_number" value="{{Auth::user()->cp_number}}" class="form-control">
			</div>

			<div class="form-group">
				<span class="btn btn-default btn-sm"  onclick="changePassword()"><span class="fa fa-unlock-alt"></span>&nbsp; Change Password</span>
			</div>

			<div class="change-password" style="display:none">				
				<div class="form-group">
			   		<label>New Password</label> <small>Must be at least 6 characters</small>
			   		<input type="password" name="password" class="form-control">
				</div>

				<div class="form-group">
			   		<label>Password Confirmation</label>
			   		<input type="password" name="password_confirmation" class="form-control">
				</div>
			</div>

			<div class="form-group">
		   		<label>Current Password</label>
		   		<input type="password" name="current_password" class="form-control">
			</div>

			<div class="form-group">
			   	<button type="submit" class="btn btn-primary">Update Account</button>
			</div>

		</form>
		
	</div>
</div>

<script type="text/javascript">
	var check=false
	function changePassword() {

		if(check==false){
			check=true;
			$('.change-password input').attr('required', true);
		}else{
			check=false;
			$('.change-password input').attr('required', false);
		}
		$('.change-password input').val("");
		$('.change-password').toggle(400);
	}
</script>
