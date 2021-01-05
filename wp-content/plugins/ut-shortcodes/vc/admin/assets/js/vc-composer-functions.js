/* <![CDATA[ */
(function($){
	
	"use strict";
	
    $(document).ready(function(){
        
        /* 
         * Overlay for Visual Composer
         */

        $(document).on('click', '#vc_no-content-add-element, #vc_add-new-element, .vc_add-element-not-empty-button, .vc_control.column_edit, .vc_control-btn.vc_control-btn-edit', function(event) {

            $('#ut-vc-overlay').addClass("show");            
            event.preventDefault(); 

        });

        $(document).on('click', '[data-vc-ui-element="button-save"], [data-vc-ui-element="button-close"], .vc_shortcode-link', function(event) {

            $('#ut-vc-overlay').removeClass("show");            
            event.preventDefault(); 

        });
        
        /* 
         * Force People to add Section in top level
         */

        $(document).on('click', '#vc_no-content-add-element, #vc_not-empty-add-element, #vc_add-new-element', function(event) {

            var $modal = $('#vc_ui-panel-add-element');

            $modal.find('ul.vc_ui-tabs-line').children('.vc_edit-form-tab-control').not('.ut-structual, .ut-all').each(function() {

                $(this).hide();

            });

            $modal.find('.wpb-layout-element-button').each(function() {

                if( $(this).data('element') !== 'vc_section' && $(this).data('element') !== 'ut_split_section' && $(this).data('element') !== 'ut_custom_section' && $(this).data('element') !== 'ut_content_block' ) {

                    $(this).addClass('vc_inappropriate');

                }                        

            });

            event.preventDefault();

        });
         
    });
        
})(jQuery);
 /* ]]> */	