
@include('base::inc.message')

<div class="containter-fluid">
	
	<div class="content white-bg m-padding  gray-border mv-margin">
		<div class="row tab-container">			
			<div class="col-sm-12">
				<h4 class="bold tab-title"><span class="fa fa-calendar"></span>Logs
				
				<div class="search-container">
					<span class="fa fa-search icon"></span>					
					<input type="date" name="search" class="search-field" value="<?php echo date('Y-m-d');?>" onchage="searchLog(this.value)">
				</div>
				
				</h4>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-hover userlist">
				<thead>				
					<tr>
	 					
						<th>Content</th>
						<th>Action</th>
						<th>Done By</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody class="search-result">
					
				</tbody>				
			</table>
		

		</div>	
		
	</div>
</div>

<script type="text/javascript">
	$('[name="search"]').change(function(){
		var date = $(this).val();
		$(".loglist tbody.search-result").html("<tr ><td align='center' colspan='123'> Loading... </td></td>");
		$.ajax({
			url: '/admin/log/search',
			type: 'POST',
			data: {
				date: 	date,
	 		}

			,success:function(data) {
				$('.search-result').html(data);
			}		
		});
	});

	$('[name="search"]').change();

</script>

<style type="text/css">
	.search-container .icon{
		position: absolute;
		left:10px;
	}

	.search-container{
		float:right;
		position: relative;
	}
	.search-field{
		min-width:200px;width:38%;
		border:1px solid #dcdcdc;
		outline:none;
		padding:3px;
		padding-left:32px;
		border-radius: 3px;
		font-weight:normal;
		margin-top:-12px;
	}
</style>
