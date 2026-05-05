<?php
/**
 * Theme optimization functions
 */

// Enable lazy loading for images
function uenf_lazy_loading_setup() {
    if ( get_theme_mod( 'enable_lazy_loading', true ) ) {
        add_filter( 'wp_get_attachment_image_attributes', 'uenf_lazy_loading_attributes', 10, 3 );
    }
}
add_action( 'after_setup_theme', 'uenf_lazy_loading_setup' );

function uenf_lazy_loading_attributes( $attr, $attachment, $size ) {
    if ( is_admin() || is_feed() ) {
        return $attr;
    }

    $attr['loading'] = 'lazy';
    return $attr;
}

// Minify and combine CSS
function uenf_minify_css( $css ) {
    // Remove comments
    $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
    // Remove whitespace
    $css = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css );
    return $css;
}

// Minify and combine JavaScript
function uenf_minify_js( $js ) {
    require_once get_template_directory() . '/inc/class-js-minifier.php';
    return JSMin::minify( $js );
}

// Optimize images
function uenf_optimize_image_quality( $quality ) {
    return 85; // Balanced quality setting
}
add_filter( 'jpeg_quality', 'uenf_optimize_image_quality' );
add_filter( 'wp_editor_set_quality', 'uenf_optimize_image_quality' );

// Add defer to non-critical scripts
function uenf_defer_scripts( $tag, $handle, $src ) {
    $defer_scripts = array(
        'comment-reply',
        'wp-embed',
    );

    if ( in_array( $handle, $defer_scripts ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'uenf_defer_scripts', 10, 3 );

// Enable GZIP compression
function uenf_enable_gzip() {
    if ( extension_loaded( 'zlib' ) && !ini_get( 'zlib.output_compression' ) ) {
        ini_set( 'zlib.output_compression', 'On' );
    }
}
add_action( 'init', 'uenf_enable_gzip' );

// Add browser caching headers
// SECURITY FIX: WP-C03 — Cache-Control de 1 ano restrito apenas a feeds e attachments.
// Aplicar em páginas HTML dinâmicas (singular, archive, home) causaria cache de conteúdo privado
// em navegadores e CDNs, com risco de servir conteúdo desatualizado ou privado a outros usuários.
function uenf_add_browser_caching() {
    if ( !is_admin() && ( is_feed() || is_attachment() ) ) {
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT' );
        header( 'Cache-Control: public, max-age=31536000' );
    }
}
add_action( 'send_headers', 'uenf_add_browser_caching' );

// Remove query strings from static resources (desativado em dev para permitir cache-bust)
function uenf_remove_script_version( $src ) {
    if ( defined('WP_DEBUG') && WP_DEBUG ) {
        return $src; // Em dev, manter ?ver= para cache-bust
    }
    if ( strpos( $src, '?ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'script_loader_src', 'uenf_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'uenf_remove_script_version', 15, 1 );

// Disable emojis
function uenf_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'uenf_disable_emojis' );

// Optimize database
function uenf_optimize_database() {
    global $wpdb;
    
    // Clean post revisions
    $wpdb->query( "DELETE FROM $wpdb->posts WHERE post_type = 'revision'" );
    
    // Clean auto drafts
    $wpdb->query( "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'" );
    
    // Clean trash posts
    $wpdb->query( "DELETE FROM $wpdb->posts WHERE post_status = 'trash'" );
    
    // Clean post meta
    $wpdb->query( "DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL" );
    
    // Optimize tables
    $wpdb->query( "OPTIMIZE TABLE $wpdb->posts" );
    $wpdb->query( "OPTIMIZE TABLE $wpdb->postmeta" );
}
add_action( 'wp_scheduled_delete', 'uenf_optimize_database' ); 