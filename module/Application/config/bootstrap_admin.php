<?php
return array( 

	// Site name
	'site_name' => 'Admin',
	// Default page title prefix
	'page_title_prefix' => '',
	// Default page title
	'page_title' => 'Admin',
	// Default meta data
	'meta_data'	=> array(
		'author'		=> 'Admin Zend Framework',
		'description'	=> 'Admin Zend Framework',
		'keywords'		=> 'Admin Zend Framework',
		'generator'		=> 'Admin Zend Framework',
		'robots'		=> 'index,follow'
	), 

	// Default scripts to embed at page head or end
	'scripts' => array(
		'head'	=> array(
			'backoffice/plugins/jQuery/jQuery-2.1.4.min.js',
			'backoffice/bower_components/jquery-ui/jquery-ui.min.js',
			'backoffice/bower_components/bootstrap/dist/js/bootstrap.min.js',
			'backoffice/bower_components/raphael/raphael.min.js',
			'backoffice/bower_components/morris.js/morris.min.js',
			'backoffice/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js',
			'backoffice/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
			'backoffice/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
			'backoffice/bower_components/jquery-knob/dist/jquery.knob.min.js',
			'backoffice/bower_components/moment/min/moment.min.js',
			'backoffice/bower_components/bootstrap-daterangepicker/daterangepicker.js',
			'backoffice/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
			'backoffice/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
			'backoffice/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
			'backoffice/bower_components/fastclick/lib/fastclick.js',
			'backoffice/plugins/bootstrap-toggle/js/bootstrap-toggle.min.js',
			'backoffice/plugins/bootstrap-filestyle/bootstrap-filestyle.min.js',
			'backoffice/plugins/datatables/media/js/jquery.dataTables.js',
			'backoffice/plugins/datatables/media/js/dataTables.rowReorder.min.js',
			'backoffice/plugins/datatables/media/js/dataTables.bootstrap.js',
			'backoffice/plugins/summernote/summernote.js',
		),
		'foot'	=> array(
			'backoffice/js/autosize.min.js',
			'backoffice/dist/js/adminlte.min.js',
			'backoffice/js/datatables.js',
			'backoffice/js/admin.js',
			'backoffice/dist/js/js.js',
		),
	),

	// Default stylesheets to embed at page head
	'stylesheets' => array(
		'screen' => array(
			'backoffice/bower_components/bootstrap/dist/css/bootstrap.min.css',
			'backoffice/bower_components/font-awesome/css/font-awesome.min.css',
			'backoffice/bower_components/Ionicons/css/ionicons.min.css',
			'backoffice/dist/css/AdminLTE.min.css',
			'backoffice/dist/css/skins/_all-skins.min.css',
			'backoffice/bower_components/morris.js/morris.css',
			'backoffice/bower_components/jvectormap/jquery-jvectormap.css',
			'backoffice/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
			'backoffice/bower_components/bootstrap-daterangepicker/daterangepicker.css',
			'backoffice/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
			'backoffice/plugins/bootstrap-toggle/css/bootstrap-toggle.min.css',
			'backoffice/plugins/datatables/media/css/rowReorder.dataTables.min.css',
			'backoffice/plugins/datatables/media/css/dataTables.bootstrap.css',
			'backoffice/plugins/summernote/summernote.css',
			'backoffice/dist/css/css.css',
		)
	)

);
