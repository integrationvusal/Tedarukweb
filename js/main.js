function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}

$(document).ready(function() {
	$('li ul .active').removeClass('active').parents('li').addClass('active');
	// Header Scroll
	$(window).on('scroll', function() {
		var scroll = $(window).scrollTop();

		if (scroll >= 50) {
			$('#header').addClass('fixed');
		} else {
			$('#header').removeClass('fixed');
		}
	});

	
	$('.auto-contact').submit(function(e){

        if($(this).attr('accesskey') != 'success'){
            e.preventDefault();

            _elems = $('.auto-contact [name]');

            for(i=0;i< _elems.length ;i++){
            
                _name = $(_elems[i]).attr('name').trim();
                
                if(_name !== '' && ~_name.search('contact') ){
                    
                    _name = _name.match(/\[(.*?)\]/)[1];
                    
                    //_value = $(_elems[i]).attr('placeholder');
                    _value = $(_elems[i]).prev().text();
                
                
                    $('.auto-contact').append('<input type="hidden" name="fields['+_name+']" value="'+_value+'" />'); 
                }    
            
            }

            $(this).attr('accesskey','success');
            $(this).trigger('submit');
        }
    });
	
	

	// Page Scroll
	var sections = $('section')
		nav = $('.navbar-static-top');

	/*
	$(window).on('scroll', function () {
	  	var cur_pos = $(this).scrollTop();
	  	sections.each(function() {
	    	var top = $(this).offset().top - 75
	        	bottom = top + $(this).outerHeight();
	    	if (cur_pos >= top && cur_pos <= bottom) {
	      		nav.find('a').removeClass('active');
	      		nav.find('a[href="#'+$(this).attr('id')+'"]').addClass('active');
	    	}
	  	});
	});
	*/
	var currentSection = GetURLParameter('sec');

	nav.find('.dropdown-menu a').on('click', function () {
	  	var $el = $(this)
	    	id = $el.attr('href');
    	var offset = 110;
	    if(currentSection != 'undefined') offset = 300;
	    console.log(offset);
		$('html, body').animate({
			scrollTop: $(id).offset().top - offset
		}, 1000);
	  return false;
	});

	if(currentSection != 'undefined') {
		$("a[href='#" + currentSection + "']").trigger("click");
		currentSection = 'undefined';
	}


	// Mobile Navigation
	$('.nav-toggle').on('click', function() {
		$(this).toggleClass('close-nav');
		nav.toggleClass('open');
		return false;
	});	
	nav.find('.dropdown-menu a').on('click', function() {
		$('.nav-toggle').toggleClass('close-nav');
		nav.toggleClass('open');
	});
	
	$(".dropdown-menu a").click(function(){
		$(".dropdown").removeClass("open");
	});
	
	
});