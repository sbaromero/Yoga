
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
  $('.fnac').datepicker();
  $('.ingreso').datepicker();
  $('.reingreso').datepicker();
})



          
$(function() {
    $('a').bind('click',function(event){
        var $anchor = $(this);
                    
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1000);
                   
        event.preventDefault();
        });
});

