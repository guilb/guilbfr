<?php
return array(
	array(
		'name'     				=> 'Apollo13 Photon Post Types', // The plugin name
		'slug'     				=> 'a13_photon_cpt', // The plugin slug (typically the folder name)
		'source'   				=> A13_TPL_PLUGINS.'/a13_photon_cpt.zip', // The plugin source
		'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
		'version' 				=> '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
		'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
		'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
	),

    array(
        'name'     				=> 'Envato WordPress Toolkit', // The plugin name
        'slug'     				=> 'envato-wordpress-toolkit', // The plugin slug (typically the folder name)
        'source'   				=> A13_TPL_PLUGINS.'/envato-wordpress-toolkit.zip', // The plugin source
        'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
        'version' 				=> '1.7.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
    ),

    //from the WordPress Plugin Repository
    array(
        'name' 	    => 'Contact Form 7',
        'slug' 		=> 'contact-form-7',
        'required'  => false,
        'version' 	=> '4.1.2'
    ),

);