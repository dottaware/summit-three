<?php
/**
 * Summit Three.
 *
 * This file adds functions to the Summit Three Theme.
 *
 * @package Summit Three
 * @author  Dottaware
 * @license GPL-2.0+
 * @link    https://www.dottaware.net/
 */

// Starts the Genesis engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'summit_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function summit_localization_setup() {

	load_child_theme_textdomain( 'summit', get_stylesheet_directory() . '/languages' );

}

// Defines the child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Summit Three' );
define( 'CHILD_THEME_URL', 'https://www.dottaware.net/' );
define( 'CHILD_THEME_VERSION', '0.0.1' );


// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Load page header.
include_once( get_stylesheet_directory() . '/includes/header.php' );


add_action( 'wp_enqueue_scripts', 'summit_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function summit_enqueue_scripts_styles() {

	// Add Google Fonts
    wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:400,400i,600,700', array(), CHILD_THEME_VERSION );

	// wp_enqueue_style( 'summit-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700', array(), CHILD_THEME_VERSION );
	
	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'summit-responsive-menu', get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js", array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script( 'summit-responsive-menu', 'genesis_responsive_menu', summit_responsive_menu_settings() );

}

/**
 * Defines responsive menu settings.
 *
 * @since 2.3.0
 */
function summit_responsive_menu_settings() {

	$settings = array(
		'mainMenu'         => __( 'Menu', 'summit' ),
		'menuIconClass'    => 'dashicons-before dashicons-menu',
		'subMenu'          => __( 'Submenu', 'summit' ),
		'subMenuIconClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'      => array(
			'combine' => array(
				'.nav-primary',
			),
			'others'  => array(),
		),
	);

	return $settings;

}

// Adds support for HTML5 markup structure.
add_theme_support( 'html5', array( 'caption', 'gallery', 'search-form', ) );

// Adds support for accessibility.
add_theme_support(
	'genesis-accessibility', array(
		'404-page',
		'drop-down-menu',
		'headings',
		'rems',
		'search-form',
		'skip-links',
	)
);

// Adds viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Adds custom logo in Customizer > Site Identity.
add_theme_support( 'custom-logo', array( 'height' => 64, 'width' => 64 ) );

// Renames primary and secondary navigation menus.
add_theme_support(
	'genesis-menus', array(
		'primary'   => __( 'Header Menu', 'summit' ),
		'secondary' => __( 'Footer Menu', 'summit' ),
	)
);

// Adds support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Adds support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Removes output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

add_action( 'genesis_theme_settings_metaboxes', 'summit_remove_metaboxes' );
/**
 * Removes output of unused admin settings metaboxes.
 *
 * @since 2.6.0
 *
 * @param string $_genesis_admin_settings The admin screen to remove meta boxes from.
 */
function summit_remove_metaboxes( $_genesis_admin_settings ) {

	remove_meta_box( 'genesis-theme-settings-header', $_genesis_admin_settings, 'main' );
	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_admin_settings, 'main' );

}

add_filter( 'genesis_customizer_theme_settings_config', 'summit_remove_customizer_settings' );
/**
 * Removes output of header settings in the Customizer.
 *
 * @since 2.6.0
 *
 * @param array $config Original Customizer items.
 * @return array Filtered Customizer items.
 */
function summit_remove_customizer_settings( $config ) {

	unset( $config['genesis']['sections']['genesis_header'] );
	return $config;

}

// Displays custom logo.
// add_action( 'genesis_site_title', 'the_custom_logo', 5 );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'summit_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function summit_secondary_menu_args( $args ) {

	if ( 'secondary' !== $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;
	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'summit_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function summit_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'summit_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function summit_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}
