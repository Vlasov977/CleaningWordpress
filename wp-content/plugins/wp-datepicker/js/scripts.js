// JavaScript Document
jQuery(document).ready(function($){
	$('.wpdp .wpdp_settings > h3').click(function(){
		var target = '.wpdp .wpdp_settings > ul.menu-class.pages_'+$(this).attr('data-id');
		if(!$(target).is(':visible')){
			$('.wpdp .wpdp_settings > ul.menu-class').slideUp();
			$(target).slideDown();
		}
	});
	
	if ($('.wpdp div.banner_wrapper').length > 0) {
   	 if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
			$('.wpdp').on('click', 'div.banner_wrapper', function(e) {
				e.preventDefault();

				var id = $(this).find('.wpdp_vals');
				wp.media.editor.send.attachment = function(props, attachment) {
					id.val(attachment.id);
				};
				wp.media.editor.open($(this));
				return false;
			});
			
		}
		
	};
	
	if ($('.wpdp').length > 0) {
			setInterval(function(){
				wpdp_methods.update_hi();
				//console.clear();
			}, 1000);
	}
	
	$('.wpdp .head_area').on('click', 'a', function(){
		$('.wpdp .head_area code').fadeToggle();
	});
	
	if($('.wpdp_color').length>0)
	$('.wpdp_color').colorPicker();	
	
	$('select[name="wpdp_sel[]"]').on('change', function(){
		var obj = $(this).find(':selected');
		var obj_p = $(this).parent();
		var t = obj.data('type');
		var tag = obj.data('tag');
		//console.log(tag);
		var ds = obj_p.find('input[name^="wpdp_demo_str"]');
		var s = ds.val();
		
		var demo = '';
		switch(t){
			case "#":				
			case ".":
				demo += t+''+s;
				ds.attr('placeholder', 'Enter '+tag+' here');
				
			break;
			case "type":
				s = obj.val();
				ds.attr('placeholder', 'Enter name here');
				demo += tag+'['+t+"='"+s+"'  name='"+ds.val()+"']";
			break;			
		}
		obj_p.find('input[name^="wpdp_demo_output"]').val(demo);
		//console.log(t);
	});
	 $('input[name="wpdp_demo_str[]"]').on('change', function(){
		 $('select[name="wpdp_sel[]"]').trigger('change');
	 });
});		
	
						
var wpdp_methods = {

		update_hi: function(){
			jQuery.each(jQuery('.banner_wrapper .wpdp_vals'), function(){
				if(jQuery(this).val()>0){
					jQuery(this).parent().find('.dashicons').fadeIn();
				}else{
					jQuery(this).parent().find('.dashicons').fadeOut();
				}
			});
		}
}



