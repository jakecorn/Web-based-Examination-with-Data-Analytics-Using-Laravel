@extends('base::layouts.master')

@section('content')
	
	@include("announcement::pages.".$page_title)
@stop

<script type="text/javascript">

$(document).ready(function() {
	
$(function(){
	$('[data-toggle="tooltip"]').tooltip();
})
});
</script>