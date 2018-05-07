function translit(e){



	if($('[name^="name["]:first').length && ~$(this).attr('name').search('^name') && $('[name^="name["]:first')[0] !== this && $('[name^="name["]:first').val() != '') return;
	
	if($('[name^="title"]:first').length && ~$(this).attr('name').search('^title') && $('[name^="title"]:first')[0] !== this && $('[name^="title"]:first').val() != '') return;

	
	var space = '-'; 
	var text = $(this).val().toLowerCase();
	var transl = {
	'ş':'sh', 'ğ':'gh', 'ç':'ch', 'ö': 'o', 'ı':'i', 'ə':'e', 'ü':'u', 'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh', 
	'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
	'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
	'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': space, 'ы': 'y', 'ь': space, 'э': 'e', 'ю': 'yu', 'я': 'ya',
	' ': space, '_': space, '`': space, '~': space, '!': space, '@': space,
	'#': space, '$': space, '%': space, '^': space, '&': space, '*': space, 
	'(': space, ')': space,'-': space, '\=': space, '+': space, '[': space, 
	']': space, '\\': space, '|': space, '/': space,'.': space, ',': space,
	'{': space, '}': space, '\'': space, '"': space, ';': space, ':': space,
	'?': space, '<': space, '>': space, '№':space
	}
	                
	var result = '';
	var curent_sim = '';
	                
	for(i=0; i < text.length; i++) {
	    if(transl[text[i]] !== undefined) {
	        if(curent_sim != transl[text[i]] || curent_sim != space){
	             result += transl[text[i]];
	             curent_sim = transl[text[i]];
	        }                                                                             
	    }
	    else {
	        result += text[i];
	        curent_sim = text[i];
	    }                              
	}

	
	result = TrimStr(result); 

	if($('.nav-item').attr('class') !== undefined && $('.nav-item .btn-info').attr('class') === undefined && $('.jstree-clicked').data('item_id') !== 0)
		result = $('.jstree-clicked').attr('data-sef-url') + '-' + result;

	$('[name="sef"]').val(result); 
    
}

function setURL(url){
	//$('.cke_dialog:visible .cke_dialog_ui_hbox_first:first input.cke_dialog_ui_input_text').val(url);
	CKEDITOR.tools.callFunction(1, url);
}

function TrimStr(s) {
    s = s.replace(/^-/, '');
    return s.replace(/-$/, '');
}

function popitup(url) {
	var x = screen.width/2 - 780/2;
	var y = screen.height/2 - 480/2;
	newwindow=window.open(url,t['albums'],'height=480,width=780,left='+x+',top='+y);
	if (window.focus) newwindow.focus();
}

$(document).ready(function() {
	
	if(	$('.article-select').attr('class') !== undefined){

		$('.article-select').prev().change(function(){
			if( !!~$.inArray($(this).val(), ['category', 'contact'])  ){
				$('.article-select').addClass('active');
			}else{
				$('.article-select').removeClass('active');
			}
		});

		if(!!~$.inArray($('.article-select').prev().val(), ['category', 'contact']) ) {
			$('.article-select').addClass('active');
		}
	}
	
	var _tmp = $('.tmp').html();
	
	$('body').on('click', '.add-pricetable', function(){
	    $(this).parents('.form-group').append(_tmp);
	});
	
	$('body').on('click', '.del-pricetable', function(){
	   $(this).parents('.group-price').remove(); 
	});

	$('.open-album').click(function(){
		popitup($(this).attr('href'));
		return false;
	});

	$('.link-tabs a').click(function(){
		$('.link-tabs a').removeClass('active');
		$(this).addClass('active');
		$('.div-tabs').removeClass('active');
		$('.div-tabs.'+$(this).attr('href')).addClass('active');
		return false;
	});

	if($('.link-tabs').attr('class') != undefined){
		if($('.tab-video input').val().trim() == '')
			$('.link-tabs a:first').trigger('click');
		else
			$('.link-tabs a:last').trigger('click');
	}
	
	$('.active [data-tab="true"]').each(function(){
		$(this).parents('ul').next().children().removeClass('active');
		$(this).parents('ul').next().find($(this).attr('href')).addClass('active');
	});
	
	$('[data-tab="true"]').click(function(){
		$(this).parents('ul').next().children().removeClass('active');
		$(this).parents('ul').next().find($(this).attr('href')).addClass('active');
		
		$(this).parent().siblings().removeClass('active');
		$(this).parent().addClass('active');
		
		return false;
	});
	

	// customized file inputs placeholder updating on change
	var file_api = ((window.File && window.FileReader && window.FileList && window.Blob)? true: false);
	$(document).on('change', '.customizedFileInputBox input[type="file"]', function() {
		var $el = $(this);
		var $context = $el.parent();
		var file_name = '';
		if (file_api && this.files[0]) {
			file_name = this.files[0].name;
		} else {
			file_name = $(this).val().replace("C:\\fakepath\\", '');
		}

		if (file_name) {
			$('.customizedFileInput', $context).text(file_name);
		} else {
			$('.customizedFileInput', $context).text($(this).attr('placeholder'));
		}
	});


	//Category
	if($('.all:checkbox').length){
	     $('.all:checkbox').click(function(){
	         $(this).parents('.form-group').find(':checkbox').prop('checked', $(this).prop('checked'));
	     });
	     if( $('body.edit').length && $('.menu-tree-item :checkbox[name]').length  ==  $('.menu-tree-item :checkbox[name]:checked').length)
	        $('.all:checkbox').trigger('click');
	        
	}

	// FOR MAGIC SUGGEST
	var checkExist = setInterval(function() {
	   if ($('.ms-ctn .ms-sel-ctn input').length) {
	    	$('.ms-ctn .ms-sel-ctn input').width('100%');
	    	$('.ms-ctn .ms-sel-ctn input').trigger('focus');
	    	$('body').trigger('click');
	    	$('[name="sef"]').trigger('focus');
	    	clearInterval(checkExist);
	   }
	}, 100);

	if($('[name="sef"]').attr('name') !== undefined){
		$('[name^="title"]').on('blur keyup', translit);
		$('[name^="name["]').on('blur keyup', translit);
	}

    if($('#datepicker').attr('id') !== undefined){
        $('#datepicker').datepicker({dateFormat: "dd.mm.yy"});
    }

	function getDeleteOrRestore(){
		if($('.see-deleted input').prop('checked'))
			return 'restore';

		return 'delete';
	}

	$('.deleted-items').change(function(){
		_elems = $('.deleted-items:checked');
		if(_elems.length >1)
			$('.send-to-'+getDeleteOrRestore()).show();
		else
			$('.send-to-'+getDeleteOrRestore()).hide();
	});


	$('.send-to-delete, .send-to-restore').click(function(){
		bootbox.confirm({
			message: window['t'][getDeleteOrRestore() + '_confirmation'],
			callback: function(ok) {
				if (ok) {
					$('#formDeleteItem [name="delete[]"]').remove();
					$('#formDeleteItem [name="restore[]"]').remove();

					$('.deleted-items:checked').each(function(){
						_id = $(this).val();
						$('#formDeleteItem').append('<input type="hidden" name="'+getDeleteOrRestore()+'[]" value="'+_id+'"/>');
					});

					$('#formDeleteItem').submit();
				}
			}
		});
		return false;

	});

	$('.delete-selectable').change(function(){
		$('.deleted-items').prop('checked', this.checked);
		if(this.checked)
			$('.send-to-'+getDeleteOrRestore()).show();
		else
			$('.send-to-'+getDeleteOrRestore()).hide();
	});

	$('.delete-item, .restore-item').click(function(e) {
		_id = $(this).data('item-id');
		bootbox.confirm({
			message: window['t'][getDeleteOrRestore() + '_confirmation'],
			callback: function(ok) {
				if (ok) {
					$('#formDeleteItem [name="delete[]"]').remove();
					$('#formDeleteItem [name="restore[]"]').remove();

					$('#formDeleteItem').append('<input type="hidden" name="'+getDeleteOrRestore()+'[]" value="'+_id+'"/>').submit();
				}
			}
		});
		return false;
	});

	$('.see-deleted input').change(function(){
		$('#'+$(this).attr('form')).submit();
	});

	
	_editInList = $('#main-content tbody tr .fa-pencil-square-o');
	if(_editInList.length){
		$('#main-content tbody tr').css('cursor', 'pointer');
		$('#main-content tbody tr').click(function(){
			location.href = $('.fa-pencil-square-o',this).parent().attr('href');
		});
	}
	
	
});