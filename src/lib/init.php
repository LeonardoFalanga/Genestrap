<?php

add_action('genesis_setup', 'shq_gestrap_init');

/**
 * Initializes this child theme.
 */
function shq_gestrap_init() {
	// Require configuration from this child theme
	$config = require_once( get_stylesheet_directory() . '/config.php' );

	// Initialize the genesis framework calling the parent theme (Genesis)
	require_once( get_template_directory() . '/lib/init.php' );

	// Define this child theme information
	define( 'CHILD_THEME_NAME', $config['CHILD_THEME_NAME'] );
	define( 'CHILD_THEME_URL', $config['CHILD_THEME_URL'] );
	define( 'CHILD_THEME_VERSION', $config['CHILD_THEME_VERSION'] );

	// Unregister JQuery (only for frontend) and enqueue the compiled scripts
	if ( ! is_admin() ) {
		add_action( 'wp_enqueue_scripts', 'shqgb_enqueue_scripts', 11 );
		function shqgb_enqueue_scripts() {
			// Add the Bootstrap scripts
			wp_register_script( 'bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.bundle.js', ['jquery'], '4.0.0', true );
			wp_enqueue_script('bootstrap');
		}

		;
	}

	// Add HTML5 markup structure from Genesis
	add_theme_support( 'html5' );

	// Add HTML5 responsive recognition
	add_theme_support( 'genesis-responsive-viewport' );

	//* Unregister secondary navigation menu
	add_theme_support( 'genesis-menus', [ 'primary' => __( 'Header Navigation Menu', 'genesis' ) ] );

	// Load all the files required to customize Genesis
	foreach ( glob( get_stylesheet_directory() . '/lib/*.php' ) as $file ) {
		// Do not include this file
		if (false === strpos($file, 'init.php')) {
			require_once($file);
		};
	}
}