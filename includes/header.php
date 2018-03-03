<?php
/**
 * This file adds the page header to the Summit Three Theme.
 *
 * @license   GPL-2.0+
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
 
add_action( 'genesis_header', 'summit_site_logo' );
function summit_site_logo() {
    
    // If the custom logo function and custom logo exist, set the logo image element inside the wrapping tags.
    if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {

        $custom_logo_id = get_theme_mod( 'custom_logo' );

        // We have a logo. Logo is go.
        if ( $custom_logo_id ) {
		  
            $custom_logo_attr = array(
                'class'    => 'site-logo-image',
                'itemprop' => 'logo',		   
            );
  
            $header_image = sprintf( '<a href="%1$s" rel="home" itemprop="url">%2$s</a>', esc_url( home_url( '/' ) ), wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr ) );

        }
    }

    if ( $header_image ) {

        printf( '<div class="site-logo" itemscope itemtype="http://schema.org/Organization">%s</div>', $header_image );
	}
}