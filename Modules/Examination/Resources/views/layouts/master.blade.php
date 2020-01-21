@extends('base::layouts.master')


@section('content')

	@include("examination::pages.".$page_title)

@stop

@section("scripts")
     {{-- <script type="text/javascript" src="{{Module::asset('base:textboxio/textboxio.js')}}"/></script> --}}
    <script type="text/javascript" src="{{Module::asset('examination:js/examination.js')}}"/></script>

    
    <script>


    $(document).ready(function() {
    	$('textarea').each(function(index, el) {
			var simpleEditor = textboxio.replace('textarea');
			});
    });
    	$(document).click(function() {
		 	var contents = $("div[contenteditable]").html();
			$("div[contenteditable]").blur(function() 
			{
    		bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
		 		$(this).parent().siblings('textarea').html($(this).html());
			});
		});
    </script>
@stop