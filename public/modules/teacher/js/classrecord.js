$(document).ready(function(){
   	$('[data-toggle="modal"]').click(function(event) {
		var link = $(this).attr('data-href');
		$('#deleteModal'+" .btn-ok-delete").attr('href', link);
	});
	
	$("input.visibility").bootstrapSwitch();

   Teacher.init();

   $('.term_percentage').keyup(function(event) {
   		var mid = parseInt($('.term_percentage:eq(0)').val());
   		var fin = parseInt($('.term_percentage:eq(1)').val());
   		
   		if((mid+fin)>100){
   			$(this).val(0);
   			
   			var mid = parseInt($('.term_percentage:eq(0)').val());
   			var fin = parseInt($('.term_percentage:eq(1)').val());
   			
   			$(this).val(100-(mid+fin));
   		}
   });

   $('#removescore').change(function(){
		var checked = $(this).is(":checked");
		
		if(!checked){
			$('input[id="custom_value"]').val("0").attr("readonly", false);
		}else{
			$('input[id="custom_value"]').val("0").attr("readonly", true);

		}

   });
});

$(function(){
	$('[data-toggle="tooltip"]').tooltip();
});

var Teacher = function () {
	"use strict";
return{
init:function(){
	this.set();
},
	set:function(){

		$('#addCriteria').click(function(event) {
			var cri=$('.criteria:last').clone();
			$('.criteria:last').after(cri);
			$('.criteria:last input').val("").attr('readonly', false);;
			$('.criteria:last .criteria-remove ').show();
			count_me();
			return false;
		});

		

		$('.class-nav').click(function(event) {
			$(this).children( '.arrow' ).toggle();
 			$('.sub-menu-class').slideToggle(200);
		});

		$('.cell-score').dblclick(function(event) {
			$(this).children('span,input').toggle();
			$(this).children('input').focus();


		});

		$('.update-score').keyup(function(event) {
			var a=$(this).val();
			var sc = a.replace(/\s+/g, '');
			$(this).val(sc);
			var span=$(this).parent().children('span');
			var max_score=parseInt($(this).attr('total-score'));
			var spaninput= $(this).parent().children('span,input');
			if(event.keyCode==13){

				if(parseInt(sc)>max_score){
					sc = max_score;	
				}	

				spaninput.toggle();
				span.html("<img src='/images/loader.gif' class='loader'>");
				$.ajax({
					url: '/teacher/classrecord/updatescore/jake',
					type: 'POST',
					data: {
						student_id: 	$(this).attr('student-id'),
						score_id: 				$(this).attr('score-id'),
						criteria_record_id: 	$(this).attr('criteria-record-id'),
						score: 					sc,
						actionType: 			$(this).attr("name")
					}

					,success:function(data) {
						span.html(sc);
					}
					,error:function() {
						span.html(sc);
						alert("Connection error. Please try again");
					}
				});
				
			}else if(event.keyCode==27){
				$(this).parent().children('span,input').toggle();
			}
		});

		

		$('.count-me  input[type="number"]').keyup(function(event) {
			var total=0;
			var con=$('.total-percentage');
			$('.count-me  input[type="number"]').each(function(index, el) {
				
				if($(el).val().length>0){
					total+=parseInt($(el).val());
					
				}
			});
			if(total>100){
				con.css('color', 'red');
				$(this).val("");
			}else{
				con.css('color', 'black');
				

			}
			if(total!=100){
				$('.submit-button').attr('disabled',true);
			}else{
				$('.submit-button').attr('disabled',false);

			}

			con.html(total);
			
		});

		



	}
};
}();

function count_me() {
	$('.count-me  input[type="number"]').keyup(function(event) {
			var total=0;
			var con=$('.total-percentage');
			$('.count-me  input[type="number"]').each(function(index, el) {
				
				if($(el).val().length>0){
					total+=parseInt($(el).val());
					
				}
			});
			if(total>100){
				con.css('color', 'red');
				$(this).val("");
			}else{
				con.css('color', 'black');
				

			}
			if(total!=100){
				$('.submit-button').attr('disabled',true);
			}else{
				$('.submit-button').attr('disabled',false);

			}

			con.html(total);
			
		});
}

function showPassword(a){
		$(a).parent().children('span').toggle();
}

function criteriaRemove(a){
	$(a).parent().parent().remove();
}

function attendance(a){
	if($(a).is(':checked')==true){
		$(a).parent().children('#score').val(1);
	}else{
		$(a).parent().children('#score').val("A");
	}
}


$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
});


function formulaChange(a) {
	if($(a).is(':checked')){
		$('.formula input').attr('readonly', true);
		$('.formula input:eq(0)').val(100);
		$('.formula input:eq(1)').val(0);
	}else{
		$('.formula input').val("");
		$('.formula input').attr('readonly', false);
	}
}

$(function(){
	$('[data-toggle="tooltip"]').tooltip();
});


function visibility(a,b,c_id){

		var id = $(a).attr("id");	
		var val = 1;

		if($(a).attr('value') == 1){
			val=0;
		}

		$(a).parents('td').find('label').toggle();
		$(a).parents('td').find('div').toggle();
	$.ajax({
		type: 'POST',
		url: "/teacher/classrecord/exam/visibility",
		data: {"visibility": val,"examination_id": id,"class_record_id":c_id},

		success:function(data){
			$(a).parents('td').find('div').toggle();
			$(a).parents('td').find('label').toggle();
			$(a).attr("value",val);
 		},
		error:function(){
				var c = $(a).attr("value");
				if(c==1){
      	  			$(a).prop("checked",true);

				}else{
      	  			$(a).prop("checked",false);
				}			
      	  		$(a).parents('td').find('div').toggle();
				$(a).parents('td').find('label').toggle();
		}
	});
}


function exam_lock(exam_id,class_record_id,status,el){

		$(el).parent().children('.locker').hide();
		$(el).parent().children('img').show();
	$.ajax({
		type: 'POST',
		url: "/teacher/classrecord/exam/lock",
		data: {"examination_id": exam_id,"lock_exam": status,"class_record_id":class_record_id},

		success:function(data){
		
			if(status==1){
				$(el).parent().find('.lockedd').show();
			}else{
				$(el).parent().find('.unlockedd').show();
			}
			$(el).parent().find('img').hide();
 		},
		error:function(){
			if(status==0){
				$(el).parent().find('.lockedd').show();
			}else{
				$(el).parent().find('.unlockedd').show();

			}

			$(el).parent().find('img').hide();
		}
	});
	
}

var exam_score_element;
function total_score_edit(el){
	exam_score_element = el;
	var val = $(el).text();
	$('input[id="custom_value"]').val(val);
	$('#custom_total_score_modal').modal('show');

}

function total_score_save(class_record_id){
	Swal.fire({
		title: 'Are you sure?',
		text: "You wan't to manually add the total score of the examination.",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, add it!'
	  }).then((result) => {
		if (result.value) {
			var custom_value = $('input[id="custom_value"]').val();
			var removescore = $('input[id="removescore"]').is(":checked");
			var removescore_param = 0;

			if(removescore){
				removescore_param = 1;
			}
			
			$.ajax({
				url: '/teacher/classrecord/updatescore/long_exam',
				type: 'POST',
				data: {
					custom_value: 	custom_value,
					removescore: 	removescore_param,
					class_record_id: 	class_record_id,
				}

				,success:function(data) {
					$(exam_score_element).text(custom_value);
					$('#custom_total_score_modal').modal('hide');
					Swal.fire(
						'Added!',
						'Total score is successfully added. Please refresh the page to re-calculate the grades.',
						'success'
					);
				}
				,error:function() {
					Swal.fire(
						'Error',
						'A problem is encoutered when connecting to the server. Please try again.',
						'error'
					)
				}
			});
			
		}
	  })
}
