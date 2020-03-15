$(function(){
    $('.left-toggler').click(function(e) {
        $('.leftcolumn').toggle(450);
        if($(this).attr('name')=='left'){
            $('.rightcolumn').css('width','100%');
            $('.left-toggler').show();
        }else{
            $('.header .left-toggler').slideUp();
            $('.rightcolumn').css('width','');				
        }
        
    });
});