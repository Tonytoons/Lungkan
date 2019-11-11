<?php
return array(

	// Site name
	'site_name' => 'Zend Framework',
	// Default page title prefix
	'page_title_prefix' => '',
	// Default page title
	'page_title' => 'Zend Framework',
	// Default meta data
	'meta_data'	=> array( 
		'author'		=> 'Zend Framework',
		'description'	=> 'Zend Framework',
		'keywords'		=> 'Zend Framework',
		'generator'		=> 'Zend Framework',
		'robots'		=> 'index,follow'
	),

	// Default scripts to embed at page head or end
	'scripts' => array(
		'head'	=> array(
			'assets/vendor/jquery/jquery.min.js',
		),
		'foot'	=> array(
			'assets/js/script.js',
		),
	),

	// Default stylesheets to embed at page head
	'stylesheets' => array(
		'screen' => array(
			'assets/vendor/bootstrap/css/bootstrap.min.css',
			'assets/css/font-awesome.min.css',
			//'assets/vendor/simple-line-icons/css/simple-line-icons.css',
			'assets/css/style.css?v='.date("YmdHis"),
			//'css/custom.css'

		)
	)

);
