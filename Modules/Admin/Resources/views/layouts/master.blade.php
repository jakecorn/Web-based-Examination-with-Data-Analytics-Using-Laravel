@extends('base::layouts.master')

@section('content')

	@include("admin::pages.".$page_title)

@stop
@section("scripts")

<script type="text/javascript">
 $(document).ready(function() {
 	$('[data-toggle="modal"]').click(function(event) {
		var link = $(this).attr('data-href');
		$('#deleteModal'+" .btn-ok-delete").attr('href', link);
	});


	$(function(){
		$('[data-toggle="tooltip"]').tooltip();
	});
	

});	

$.ajaxSetup({
	headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

function searchUser(search){
	if(search.length>0){
		$(".userlist tbody.users").hide();
		$(".userlist tbody.search-result").html("<tr ><td align='center' colspan='123'> Loading... </td></td>");
		$.ajax({
			url: '/admin/user/search',
			type: 'POST',
			data: {
				search: 	search,
	 		}

			,success:function(data) {
				$(".userlist tbody.users").hide();
				$(".userlist tbody.search-result").html(data).show();
			}		
		});
	}else{
		$(".userlist tbody.users").show();
		$(".userlist tbody.search-result").hide();
	}
}
	function storeStatus(status,el,user_id, is_registered){
		if(status ==1 && is_registered == "Done manual registration but not found in the masterlist"){
			swal.fire({
				title: 'Are you sure?',
				text: "You want to activate this user? This user is not found in pre-registerd users.",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes, activate it!',
				cancelButtonText: 'No, cancel!',
				reverseButtons: true
				}).then((result) => {
				if (result.value) {
					swal.fire(
					'Activated!',
					'The user account has been activated',
					'success'
					)
					storeStatus_save(status,el,user_id);
				} else if (
					result.dismiss === Swal.DismissReason.cancel
				) {
					swal.fire(
					'Cancelled',
					'The user account was not activated',
					'error'
					)
				}
			});
		}else{
			storeStatus_save(status,el,user_id);
		}
		
	}

 	function storeStatus_save(status,el,user_id) {
				$(el).parent().children('span').hide();
				$(el).siblings('img').toggle();
				
				
 				$.ajax({
					url: '/admin/user/change-status',
					type: 'POST',
					data: {
						user_id: 	user_id,
						status: 	status
					}

					,success:function(data) {
						$(el).siblings('span').toggle();
						 $(el).siblings('img').hide();
						 swal.fire(
							'Updated!',
							'The status has been updated.',
							'success'
							)
 					}
					,error:function() {
						$(el).siblings('img').toggle();
						$(el).toggle();
						$('#passwordResetButton').click();
  						$('#passwordReset .modal-title-me').html("Connection Error");
  						$('#passwordReset .modal-body-content').html("There is an error with your connection. Please try again.");
					}
				});
	}

	function passwordReset(user_id,el) {
				$(el).hide();
				$(el).siblings('img').toggle();
 				$.ajax({
					url: '/admin/user/password-reset',
					type: 'POST',
					data: {
						user_id: 	user_id,
 					}

					,success:function(data) {
						$(el).show();
 						$(el).siblings('img').hide();
 						$('#passwordResetButton').click();
  						$('#passwordReset .modal-title-me').html("Password Reset");
  						$('#passwordReset .modal-body-content').html(data);
 					}
					,error:function() {
						$(el).show();
 						$(el).siblings('img').hide();
						$('#passwordResetButton').click();
  						$('#passwordReset .modal-title-me').html("Connection Error");
  						$('#passwordReset .modal-body-content').html("There is an error with your connection. Please try again.");
					}
				});
	}

 </script>
 
@stop