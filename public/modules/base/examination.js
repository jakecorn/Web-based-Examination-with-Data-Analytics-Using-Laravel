$(document).ready(function() {
	$("input[name=time_limit]").click(function(event) {
		if($(this).val()=="yes"){
			$("input[name=duration]").show();
		}else{

			$("input[name=duration]").hide();
		}
	});
});

function addPart(){
	var last = $('.part-group:last');
	last.after(last.clone());
	$('.part-group:last').before('<br><br>');


	$('.part-number').each(function(index, el) {
		$(el).html("Part "+ (index+1));		
	});
}


function removePart(a){

	$(a).parent().parent().remove();

	$('.part-number').each(function(index, el) {
		$(el).html("Part "+ (index+1));		
	});
}

function removeChoice(a){
	$(a).parents('tr').remove();
}

function addChoice(a){
	var b = $(a).siblings('table').find('.normal-choice:last').clone(); 
	$(a).siblings('table').find('.normal-choice:last').after(b); 
	$(a).siblings('table').find('.normal-choice:last input').removeAttr('readonly').val(""); 

}

function addChoice_ide(a){
	var b = $(a).siblings('table').find('.normal-choice:last').clone(); 
	$(a).siblings('table').find('.normal-choice:last').after(b); 
	$(a).siblings('table').find('.normal-choice:last .choices_desc,.normal-choice:last .choice_id').val(""); 

}


var ind=1;
function addQuestion(){ 
	var a = $('.question-row:last').clone();
	$('.question-row:last').after(a);
	$('.question-row:last').find("textarea,input").not("[readonly]").val("").text("");
	$('.question-row:last').find("input:checkbox").prop('checked',false);
	$('.question-row:last').find('.answer').attr("name","answer["+ind+"][]").val(0);
	$('.question-row:last').find('.choices_desc').attr("name","choices_desc["+ind+"][]");
	ind++;

}
var ind=1;
function addQuestion_mat(){ 
	var a = $('.question-row:last').clone();
	$('.question-row:last').after(a);
	$('.question-row:last').find("textarea,input").not("[readonly]").val("").text("");
	$('.question-row:last').find('.answer').attr("name","answer["+ind+"][]").val(1);
	$('.question-row:last').find('.choices_desc').attr("name","choices_desc["+ind+"][]");
	ind++;

}

var ind=1;
function addQuestion_tru(){ 
	var a = $('.duplicate:last').clone();
	$('.question-row:last').after(a);
	$('.duplicate:eq(0)').removeAttr('class').addClass('question-row');

	$('.question-row:last').find("textarea").val("");

	$('.question-row:last').find('.answer').attr("name","answer["+ind+"][]");
	$('.question-row:last').find('.choices_desc').attr("name","choices_desc["+ind+"][]");
	$('.question-row:last').find('input:radio').attr('name',"choose["+ind+"][]");
		
	ind++;
 
}

function chooseAnswer(a){
	if($(a).is(':checked')){
		 $(a).next('input').val(1);
	}else{
		 $(a).next('input').val(0);

	}
	
}

function chooseAnswer_tru(a){
		 $(a).siblings('.answer').val(0);
		 $(a).next('.answer').val(1);
}