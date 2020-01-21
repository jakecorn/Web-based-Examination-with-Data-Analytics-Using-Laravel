<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       	<link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
        <script type="text/javascript" src="/js/app.js"></script>
        <title>Laravel</title>
    </head>
    <body>
		<div class="container-fluid">
			<div class="row">
				Header
			</div>
			<div class="row gray-bg">
				<div name="leftcolumn" style="background:brown;" class="col-md-4">
					@include('teacher/layout/sidebar');					
				</div>

				<div name="rightcolumn" class="col-md-8">
					
					<br>
					
					<div class="row sub-nav">						
						<div class="col-sm-3">
							<div class="m-padding white-bg but">
								Class Record
							</div>
						</div>
						<div class="col-sm-3">
							<div class="m-padding white-bg but">
								Students
							</div>
						</div>
						<div class="col-sm-3">
							<div class="m-padding white-bg but">
								Exams
							</div>
						</div>
						<div class="col-sm-3">
							<div class="m-padding white-bg but">
								Files
							</div>
						</div>
					</div>

					<div class="containter-fluid">
						<div class="content white-bg m-padding  gray-border mv-margin">
							<h4 class="bottom-padding bold" style="border-bottom:1px solid #d0cece">Class Record</h4>
							<form class="mv-margin">
								  <div class="form-group row">
								  	<div class="col-md-10">
								    	<label for="exampleInputEmail1">Criteria</label> 
										<button class="btn btn-sm btn-default margin-left">
											<span class="glyphicon glyphicon-plus"></span>
											Add
										</button>								    	
								  	</div>
								  	<div class="col-md-2">
								    	<label for="exampleInputEmail1">Percentage</label>
								  	</div>
								  </div>

								  <div class="form-group row">
								    <div class="col-md-10">
								    	<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Criteria">
								    </div>
								    
								    <div class="col-md-2">
								    	<input type="email" class="form-control" id="exampleInputEmail1" placeholder="%">
								    </div>
								  </div>

								  <div class="form-group row">
								    <div class="col-md-10">
								    	<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Criteria">
								    </div>
								    
								    <div class="col-md-2">
								    	<input type="email" class="form-control" id="exampleInputEmail1" placeholder="%">
								    </div>
								  </div>

								  <div class="form-group row">
								    <div class="col-md-10">
								    	<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Criteria">
								    </div>
								    
								    <div class="col-md-2">
								    	<input type="email" class="form-control" id="exampleInputEmail1" placeholder="%">
								    </div>
								  </div>

								  <div class="form-group row">
								   	<div class="col-md-5">
								   		<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-duplicate"></span>Submit</button>
								   	</div>
								  </div>



								</form>
						</div>
					</div>

				</div>
			</div>
		</div>
    </body>
</html>
