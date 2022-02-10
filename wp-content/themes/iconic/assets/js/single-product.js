jQuery(document).ready(function(){	
if (jQuery("#slider-wrapper")[0]){	
	// jQuery('#carouselExampleControls').bcSwipe({ threshold: 50 });
	// jQuery('#similarPrppertycarousel').bcSwipe({ threshold: 50 });
	jQuery('.variable-width').slick({
		arrows: true,
		dots: false,
		infinite: true,
		//speed: 300,
		autoplay: true,
		autoplaySpeed: 2000,
		slidesToShow: 1,
		centerMode: true,
		variableWidth: true,
		nextArrow: '.next',
		prevArrow: '.previous',
		responsive: [{
		breakpoint: 1024,
		settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
				infinite: true,
				dots: false
			}
			}, {
		breakpoint: 600,
		settings: {
				slidesToShow: 2,
				slidesToScroll: 2
			}
			}, {
		breakpoint: 480,
		settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}]
	});
	}	
});