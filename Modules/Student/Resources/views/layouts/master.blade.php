<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
        <title>Student</title>
         <script type="text/javascript" src="/js/app.js"></script>
    </head>
    <body>
    	<div class="container-fluid">
            <div class="row">                
                @include('student::layouts.header')
                {{-- {{}} --}}
                <div class="col-sm-12">
                <div class="navigation">
                    <span style="font-size:25px;">{{$main_page}}</span> / {{$navigation}}
                </div>
                @include("student::pages.".$page_title)             
                </div>
            </div>
    	</div>
    <br>

    <div class="footer">
        
        Developed by: JDS/CSIT
        <div name="number">09056194798</div>
        <div name="email">jes.sumadia@gmail.com</div>
        
    </div>
    </body>
</html>

<script type="text/javascript">
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    
    $('.menu-toggler').click(function(event) {
         $('.student-header').slideToggle(300);
    });
    
</script>
