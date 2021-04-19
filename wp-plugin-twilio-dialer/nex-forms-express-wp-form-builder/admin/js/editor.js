'use strict';
var element = '';
var code = '';
var editor;		
				
jQuery(document).ready( function($) {
(function() {
	
	
	jQuery(document).on('click','.tiny_button_tags_placeholders .item', function() {
			element = '';
			code = '';
			element = $(this).attr('element');
			code = $(this).attr('code');
			editor.selection.setContent('{{' + code + '}}');
			code = '';
		});
	
    tinymce.create('tinymce.plugins.nf_tags_button', {
        init : function(ed, url) {
            ed.addButton('nf_tags_button', {
                title : 'Add an element',
				classes : 'flight_shortcodes btn nf_mce_button',
				text: '+ Add field tag',
                onclick : function(element) {
					
					
					editor = ed;
					
					var the_button = $('#'+element.control._id);
					var button = the_button.offset();
					var top = (button.top - 53) + "px";
					var left = (button.left) + "px";
					
					//the_button.removeClass('is_opened');
					$('.tags_opened').removeClass('tags_opened');
					
					
					var set_tags = $('.tiny_button_tags_placeholders').detach()
					
					var editor_container = the_button.closest('.wp-editor-wrap');
					editor_container.prepend(set_tags);
					element = '';
					code = '';	
					
						if(the_button.hasClass('is_opened'))
							{
							the_button.removeClass('is_opened');
							$('.tiny_button_tags_placeholders').hide();
							$('.tags_opened').removeClass('tags_opened');
							}
						else
							{
							the_button.addClass('is_opened');
							$('.tiny_button_tags_placeholders').slideDown('fast');
							editor_container.addClass('tags_opened');
							}
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('nf_tags_button', tinymce.plugins.nf_tags_button);
})();
});