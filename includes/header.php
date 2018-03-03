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
 
add_action( 'genesis_header', 'summit_site_logo', 5 );
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

// Open markup for site banner after header
add_action('genesis_after_header', 'summit_site_banner_markup_open', 5 );
function summit_site_banner_markup_open() {
    printf( '<div %s>', genesis_attr( 'site-banner' ) );
}

// Close markup for site banner
add_action('genesis_after_header', 'summit_site_banner_markup_close', 15 );
function summit_site_banner_markup_close() {
	echo '</div>';
}

// Add an extra div class inside the header wrap.
add_filter( 'genesis_structural_wrap-header', 'summit_structural_wrap_header_flexbox', 10, 2);
function summit_structural_wrap_header_flexbox( $output, $original_output ) {

    if( 'open' == $original_output ) {
        $output = $output . '<div class="flexbox"><!-- begin flexbox -->';
    }
  
    if( 'close' == $original_output ) {
        $output = '</div><!-- end flexbox -->' . $output;
    }
    return $output;
}
