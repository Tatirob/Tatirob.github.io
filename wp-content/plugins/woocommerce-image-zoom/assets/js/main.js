(function($) { 
  'use strict';

  	var image = $('.woocommerce-product-gallery__image').find('.wp-post-image');

	/**
	 * Remove srcset & size attr
	 */
	
	$("#wpb_wiz_gallery a").on("click", function(){ 
		$('.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a img').removeAttr('srcset');
		$('.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a img').removeAttr('sizes');
	});


	/**
	 * Init Zoom
	 */
	
	$(image).ezPlus({
        gallery: 'wpb_wiz_gallery',
        cursor: 'pointer',
        galleryActiveClass: "active",
        imageCrossfade: true,
        loadingIcon: wpb_wiz_free.loading_icon,
        zoomType: 'inner',
        responsive: true,
        scrollZoom: true,
        zoomWindowWidth: 400,
        zoomWindowHeight: 400,
        zoomWindowOffsetY: -8,
        zoomWindowOffsetX: 30,
        easing: true,
        zoomWindowFadeIn: true,
        zoomWindowFadeOut: true,
        borderSize: 0,
    });

    $(image).bind("click", function (e) {
        var ez = $(image).data('ezPlus');
        ez.closeAll(); //NEW: This function force hides the lens, tint and window
        $.fancybox.open(ez.getGalleryListFancyboxThree());
        return false;
    });


	/**
	 * Zoom image change with variation
	 */ 

	$(".variations select").live('change', function(){ 
		var currentValue 		= $(this).val();
		var currentImage 		= $(".woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a img").attr("src");
		var currentImageLarge 	= $(".woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a").attr("href");

		//console.log(currentValue);

		if( currentValue != '' ){
			var ez = image.data('ezPlus'); 
			ez.swaptheimage( currentImage, currentImageLarge );
		}
	});

	$('.attribute-swatch label').live('click', function() {
		var currentValue 		= $(this).data('option');
		var currentImage 		= $(".woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a img").attr("src");
		var currentImageLarge 	= $(".woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a").attr("href");

		//console.log(currentValue);

		if( currentValue != '' ){
			var ez = image.data('ezPlus'); 
			ez.swaptheimage( currentImage, currentImageLarge );
		}
	});

	// Reset variation on click the gallery
	$('#wpb_wiz_gallery a').live('click', function() {
		var ez 	= image.data('ezPlus');
		var featureImage 		= $(this).data('image');
		var featureImageLarge 	= $(this).data('large_image');

		ez.swaptheimage( featureImage, featureImageLarge );
	});

	// reset zoom on click clear variation
	$('.reset_variations').live('click', function() {
		var ez 					= image.data('ezPlus');
		var featureImage 		= $(".woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a img").attr("src");
		var featureImageLarge 	= $(".woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image > a").attr("href");

		ez.swaptheimage( featureImage, featureImageLarge );
	});
	
})(jQuery);