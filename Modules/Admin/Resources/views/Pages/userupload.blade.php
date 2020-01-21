@include('admin::pages.usertab')
@include('base::inc.message')
<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="fa fa-user"></span>User

				<div class="search-container">
                    <div id="upload_user_button">
                        <button class="btn btn-seconday">Upload User</button>
                    </div>
					<span class="fa fa-search icon"></span>					
					<input type="search" name="search" class="search-field" onkeyup="searchUser(this.value)">
				</div>
				</h4>
			</div>
		</div>
		<div>
		    <form method="post" enctype="multipart/form-data">
				{{csrf_field()}}
		        <div class="form-group margin-top">
                    <label>Choose CSV File <small>( <a href="" style="color:blue;text-decoration:underline">Download</a> User-list template and user it for uploading )</small></label>
                    <input type="file" name="file" class="form-control" required="">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Save Course</button>
                </div>
		    </form>
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


