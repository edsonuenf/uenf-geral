<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function cct_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'cct_pingback_header' );

/**
 * Add custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function cct_body_classes( $classes ) {
    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }

    // Add a class if the site is being viewed in customizer preview.
    if ( is_customize_preview() ) {
        $classes[] = 'customizer-preview';
    }

    // Add class based on the header layout setting
    $header_layout = get_theme_mod( 'header_layout', 'default' );
    $classes[] = 'header-layout-' . $header_layout;

    return $classes;
}
add_filter( 'body_class', 'cct_body_classes' );

/**
 * Add custom image sizes
 */
function cct_custom_image_sizes() {
    add_image_size( 'cct-featured', 1200, 600, true );
    add_image_size( 'cct-thumbnail', 350, 250, true );
}
add_action( 'after_setup_theme', 'cct_custom_image_sizes' );

/**
 * Customize excerpt length
 */
function cct_custom_excerpt_length( $length ) {
    // Respeitar configuração do Customizer na página de busca
    if ( is_search() ) {
        $len = get_theme_mod( 'cct_search_results_excerpt_length', 20 );
        return max( 1, absint( $len ) );
    }
    return 20;
}
add_filter( 'excerpt_length', 'cct_custom_excerpt_length', 999 );

/**
 * Customize excerpt more string
 */
function cct_custom_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'cct_custom_excerpt_more' );

/**
 * Add custom classes to navigation menus
 */
function cct_nav_menu_css_class( $classes, $item ) {
    if ( in_array( 'current-menu-item', $classes ) ) {
        $classes[] = 'active';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'cct_nav_menu_css_class', 10, 2 );

/**
 * Add responsive container to embeds
 */
function cct_embed_wrapper( $html ) {
    return '<div class="video-container">' . $html . '</div>';
}
add_filter( 'embed_oembed_html', 'cct_embed_wrapper', 10 );
add_filter( 'video_embed_html', 'cct_embed_wrapper' );

/**
 * Remove WordPress version from RSS feeds
 */
add_filter( 'the_generator', '__return_false' );

/**
 * Add custom styles to TinyMCE editor
 */
function cct_add_editor_styles() {
    add_editor_style( 'css/editor-style.css' );
}
add_action( 'admin_init', 'cct_add_editor_styles' );

/**
 * Add support for custom logo with custom dimensions
 */
function cct_custom_logo_setup() {
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
}
add_action( 'after_setup_theme', 'cct_custom_logo_setup' );

/**
 * Register Google Fonts
 */
function cct_google_fonts_url() {
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin,latin-ext';

    /* translators: If there are characters in your language that are not supported by the font, translate this to 'off'. Do not translate into your own language. */
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'cct-theme' ) ) {
        $fonts[] = get_theme_mod( 'body_font_family', 'Open Sans:400,700' );
        // Adiciona Roboto com peso 600 para os itens do menu
        $fonts[] = 'Roboto:400,500,600,700';
    }

    if ( $fonts ) {
        $fonts_url = add_query_arg( array(
            'family' => urlencode( implode( '|', $fonts ) ),
            'subset' => urlencode( $subsets ),
        ), 'https://fonts.googleapis.com/css' );
    }

    return $fonts_url;
}

/**
 * Enqueue Google Fonts
 */
function cct_google_fonts() {
    wp_enqueue_style( 'cct-google-fonts', cct_google_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'cct_google_fonts' );