<?php
return array(

	// Site name
	'site_name' => 'Prizzit', 
	// Default page title prefix
	'page_title_prefix' => '',
	// Default page title
	'page_title' => 'Prizzit', 
	// Default meta data
	'meta_data'	=> array(  
		'author'		=> 'Prizzit',
		'description'	=> 'Prizzit',
		'keywords'		=> 'Prizzit',
		'generator'		=> 'Prizzit',
		'robots'		=> 'index,follow'
	),
	// Default scripts to embed at page head or end
	'scripts' => array(
		'head'	=> array(
			'assets/vendor/jquery/dist/jquery.min.js',
			'assets/vendor/jquery-migrate/dist/jquery-migrate.min.js',
			'assets/vendor/popper.js/dist/umd/popper.min.js',
			'assets/vendor/bootstrap/bootstrap.min.js',
			'assets/vendor/hs-megamenu/src/hs.megamenu.js',
			'assets/vendor/svg-injector/dist/svg-injector.min.js',
			'assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
			'assets/vendor/fancybox/jquery.fancybox.min.js',
			'assets/vendor/jquery-validation/dist/jquery.validate.min.js',
			'assets/vendor/ion-rangeslider/js/ion.rangeSlider.min.js',
			'assets/vendor/slick-carousel/slick/slick.js',
			'assets/vendor/dzsparallaxer/dzsparallaxer.js',
			'assets/js/hs.core.js',
		),  
		'foot'	=> array(
			'assets/js/components/hs.header.js',
			'assets/js/components/hs.unfold.js', 
			'assets/js/components/hs.focus-state.js',
			'assets/js/components/hs.malihu-scrollbar.js', 
			//'assets/js/components/hs.validation.js', 
			'assets/js/components/hs.show-animation.js',
			'assets/js/components/hs.svg-injector.js',
			'assets/js/components/hs.fancybox.js',
			'assets/js/components/hs.range-slider.js',
			'assets/js/components/hs.slick-carousel.js',
			'assets/js/components/hs.go-to.js',
			'assets/vendor/sweetalert/dist/sweetalert2.min.js',
			'assets/js/main.js?v='.date("YmdHis"),   
		),
	),  
 
	// Default stylesheets to embed at page head
	'stylesheets' => array(
		'screen' => array(
			'assets/vendor/font-awesome/css/fontawesome-all.min.css',
			'assets/vendor/animate.css/animate.min.css',
			'assets/vendor/hs-megamenu/src/hs.megamenu.css',
			'assets/vendor/fancybox/jquery.fancybox.css',
			'assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css',
			'assets/vendor/ion-rangeslider/css/ion.rangeSlider.css',
			'assets/vendor/dzsparallaxer/dzsparallaxer.css',
			'assets/vendor/slick-carousel/slick/slick.css',
			'assets/vendor/sweetalert/dist/sweetalert2.min.css' 
		) 
	), 
	'stylesheets_custom' => array(
		'screen' => array(
			'assets/css/theme-custom.css',
			'assets/css/custom.css?v='.date("YmdHis"),
		) 
	)

);
