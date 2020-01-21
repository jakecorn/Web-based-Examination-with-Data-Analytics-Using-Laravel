<?php
	use Modules\Utilitize\Util;
?>
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
       	<link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
        <script type="text/javascript" src="/js/app.js"></script>
        <script type="text/javascript" src="/js/sweetalert2@8.js"></script>
        <script type="text/javascript" src="/js/bootstrap-switch.min.js"></script>
    	<link rel="stylesheet" type="text/css" href="/css/bootstrap-switch.min.css">
        @section('scripts')
        
		@show
        <title>{{ config('app.name', 'Laravel')}}</title>
    </head>
    <body>
		<div class="container-fluid"zz>
			<!-- <div class="row">
				Header
			</div> -->
			<div class="row">
				<div name="leftcolumn" class="col-md-3 leftcolumn">
					@include('base::layouts.sidebar')&nbsp;				
				</div>

				

				<div name="rightcolumn" class="col-md-9 rightcolumn">
					<div class="header">
						<div class="row" style="text-align:center">
							<div class="col-sm-6">
								<span name="right" class="glyphicon glyphicon-align-justify left-toggler" title="Hide left column" style="float:left;display:none;margin-right:15px"></span>
									<img src="/images/norsu-logo.png" height="40px" style="float:left;margin-top:-7px;margin-bottom:-12px;margin-right:-20px"> {{ config('app.name', 'Laravel')}}
							</div>
							<div class="col-sm-6">
								<div>School Year: {{Util::get_session('sy')}}, {{Util::get_session('semester')}} Semester {{Util::get_session('class_record_type')}}</div>							
							</div>
						</div>						
							
					</div>
					<div class="navigation">
						<span style="font-size:25px;">{{$main_page}}</span> / {{$navigation}}
					</div>
					@yield('topNav')

					@yield('content')					

				</div>
			</div>
		</div>

		{{-- MODAL --}}

		<div id="deleteModal" class="modal fade" role="dialog">
			  <div class="modal-dialog">
			    <!-- Modal content-->
			    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        	<h4 class="modal-title"><a class="fa fa-warning alert-warning margin-right"></a>Delete Record</h4>
				      </div>
				      <div class="modal-body">
				       		<p>Are you sure you want to delete this record?</p>
				      </div>
				      <div class="modal-footer">
				        	<a class="btn btn-primary btn-ok-delete width-n" onclick="yes()">Yes</a>
				        	<button type="button" class="btn btn-default width-n" data-dismiss="modal">No</button>
				      </div>
			    </div>
			  </div>
		</div>
    </body>
</html>
<script type="text/javascript">
	function yes() {
		$('#deleteModal').hide();
	}

	function smssend(){

		$.ajax({
			type:"get",
			url:"<?php echo route("smssend");?>",
			complete(){
				setTimeout(function(){
					smssend();
				},5000);
			}
		});
	}
	
	smssend();
</script>


<style type="text/css">
	@media (max-width: 575.98px) {
		.leftcolumn{
			display:none;
		}
		.header{
			text-align: center
		}

		.left-toggler{
			display: inline-block;
		}
	}
</style>
 