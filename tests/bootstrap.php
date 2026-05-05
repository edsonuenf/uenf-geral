<?php
/**
 * Bootstrap de testes — define stubs mínimos do WordPress para
 * permitir require/include de arquivos do tema sem um WP real.
 */

define( 'ABSPATH', dirname( __DIR__ ) . '/' );
define( 'WPINC', 'wp-includes' );
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );

// Stubs de funções WP usadas nos construtores e init_hooks
if ( ! function_exists( 'add_action' ) ) {
    function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {}
}
if ( ! function_exists( 'add_filter' ) ) {
    function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {}
}
if ( ! function_exists( 'remove_action' ) ) {
    function remove_action( $hook, $callback, $priority = 10 ) {}
}
if ( ! function_exists( 'apply_filters' ) ) {
    function apply_filters( $hook, $value, ...$args ) { return $value; }
}
if ( ! function_exists( 'do_action' ) ) {
    function do_action( $hook, ...$args ) {}
}
if ( ! function_exists( 'wp_enqueue_style' ) ) {
    function wp_enqueue_style( ...$args ) {}
}
if ( ! function_exists( 'wp_enqueue_script' ) ) {
    function wp_enqueue_script( ...$args ) {}
}
if ( ! function_exists( 'wp_localize_script' ) ) {
    function wp_localize_script( ...$args ) {}
}
if ( ! function_exists( 'wp_register_script' ) ) {
    function wp_register_script( ...$args ) {}
}
if ( ! function_exists( 'wp_register_style' ) ) {
    function wp_register_style( ...$args ) {}
}
if ( ! function_exists( 'get_template_directory_uri' ) ) {
    function get_template_directory_uri() { return 'http://localhost/wp-content/themes/uenf-geral-claude'; }
}
if ( ! function_exists( 'get_template_directory' ) ) {
    function get_template_directory() { return dirname( __DIR__ ); }
}
if ( ! function_exists( 'esc_url' ) ) {
    function esc_url( $url ) { return htmlspecialchars( $url, ENT_QUOTES ); }
}
if ( ! function_exists( 'esc_html' ) ) {
    function esc_html( $text ) { return htmlspecialchars( $text, ENT_QUOTES ); }
}
if ( ! function_exists( 'esc_attr' ) ) {
    function esc_attr( $text ) { return htmlspecialchars( $text, ENT_QUOTES ); }
}
if ( ! function_exists( 'sanitize_text_field' ) ) {
    function sanitize_text_field( $str ) { return strip_tags( trim( $str ) ); }
}
if ( ! function_exists( 'sanitize_hex_color' ) ) {
    function sanitize_hex_color( $color ) {
        if ( preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $color ) ) {
            return $color;
        }
        return '';
    }
}
if ( ! function_exists( 'absint' ) ) {
    function absint( $v ) { return abs( (int) $v ); }
}
if ( ! function_exists( 'wp_kses_post' ) ) {
    function wp_kses_post( $data ) { return $data; }
}
if ( ! function_exists( 'is_admin' ) ) {
    function is_admin() { return false; }
}
if ( ! function_exists( 'is_multisite' ) ) {
    function is_multisite() { return false; }
}
if ( ! function_exists( 'get_stylesheet' ) ) {
    function get_stylesheet() { return 'uenf-geral-claude'; }
}
if ( ! function_exists( 'wp_sprintf' ) ) {
    function wp_sprintf( $pattern, ...$args ) { return vsprintf( $pattern, $args ); }
}
if ( ! function_exists( 'wp_list_pluck' ) ) {
    function wp_list_pluck( $list, $field, $index_key = null ) {
        return array_column( $list, $field, $index_key );
    }
}
if ( ! function_exists( '__' ) ) {
    function __( $text, $domain = 'default' ) { return $text; }
}
if ( ! function_exists( '_e' ) ) {
    function _e( $text, $domain = 'default' ) { echo $text; }
}
if ( ! function_exists( 'plugin_dir_path' ) ) {
    function plugin_dir_path( $file ) { return trailingslashit( dirname( $file ) ); }
}
if ( ! function_exists( 'trailingslashit' ) ) {
    function trailingslashit( $str ) { return rtrim( $str, '/\\' ) . '/'; }
}
if ( ! function_exists( 'wp_parse_args' ) ) {
    function wp_parse_args( $args, $defaults = array() ) {
        if ( is_object( $args ) ) { $args = get_object_vars( $args ); }
        return array_merge( $defaults, (array) $args );
    }
}
if ( ! function_exists( 'get_option' ) ) {
    function get_option( $option, $default = false ) { return $default; }
}
if ( ! function_exists( 'update_option' ) ) {
    function update_option( $option, $value, $autoload = null ) { return true; }
}
if ( ! function_exists( 'delete_option' ) ) {
    function delete_option( $option ) { return true; }
}
if ( ! function_exists( 'get_theme_mod' ) ) {
    function get_theme_mod( $name, $default = false ) { return $default; }
}
if ( ! function_exists( 'set_theme_mod' ) ) {
    function set_theme_mod( $name, $value ) {}
}
if ( ! function_exists( 'wp_create_nonce' ) ) {
    function wp_create_nonce( $action = -1 ) { return 'test_nonce'; }
}
if ( ! function_exists( 'admin_url' ) ) {
    function admin_url( $path = '', $scheme = 'admin' ) { return 'http://localhost/wp-admin/' . ltrim( $path, '/' ); }
}
if ( ! function_exists( 'current_user_can' ) ) {
    function current_user_can( $capability, ...$args ) { return true; }
}
if ( ! function_exists( 'wp_die' ) ) {
    function wp_die( $message = '', $title = '', $args = array() ) { throw new \RuntimeException( (string) $message ); }
}
if ( ! function_exists( 'add_shortcode' ) ) {
    function add_shortcode( $tag, $callback ) {}
}
if ( ! function_exists( 'register_nav_menus' ) ) {
    function register_nav_menus( $locations = array() ) {}
}
if ( ! function_exists( 'add_theme_support' ) ) {
    function add_theme_support( $feature, ...$args ) {}
}
if ( ! function_exists( 'get_bloginfo' ) ) {
    function get_bloginfo( $show = '', $filter = 'raw' ) { return ''; }
}

// Stubs de classes WP necessárias para heranças em customizer controls
if ( ! class_exists( 'Walker' ) ) {
    abstract class Walker {
        public $tree_type  = '';
        public $db_fields  = array();
        public $max_pages  = 1;
        public function walk( $elements, $max_depth, ...$args ) { return ''; }
        public function paged_walk( $elements, $max_depth, $page_num, $per_page, ...$args ) { return ''; }
        public function get_number_of_root_elements( $elements ) { return 0; }
        public function fill_children( $elements ) { return array(); }
        abstract public function start_el( &$output, $element, $depth = 0, $args = array(), $id = 0 );
        abstract public function end_el( &$output, $element, $depth = 0, $args = array() );
        public function start_lvl( &$output, $depth = 0, $args = array() ) {}
        public function end_lvl( &$output, $depth = 0, $args = array() ) {}
    }
}
if ( ! class_exists( 'Walker_Nav_Menu' ) ) {
    class Walker_Nav_Menu extends Walker {
        public $tree_type = array( 'post_type', 'taxonomy', 'custom' );
        public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
        public function start_lvl( &$output, $depth = 0, $args = array() ) {}
        public function end_lvl( &$output, $depth = 0, $args = array() ) {}
        public function start_el( &$output, $element, $depth = 0, $args = array(), $id = 0 ) {}
        public function end_el( &$output, $element, $depth = 0, $args = array() ) {}
    }
}
if ( ! class_exists( 'WP_Customize_Control' ) ) {
    class WP_Customize_Control {
        public $manager;
        public $id;
        public $settings = array();
        public $setting;
        public $priority = 10;
        public $section   = '';
        public $label     = '';
        public $description = '';
        public $type      = 'text';
        public function __construct( $manager = null, $id = null, $args = array() ) {}
        public function render() {}
        public function render_content() {}
        public function to_json() {}
        public function get_content() {}
        protected function content_template() {}
    }
}
if ( ! class_exists( 'WP_Customize_Upload_Control' ) ) {
    class WP_Customize_Upload_Control extends WP_Customize_Control {
        public $type = 'upload';
    }
}
if ( ! class_exists( 'WP_Widget' ) ) {
    abstract class WP_Widget {
        public $id_base;
        public $name;
        public function __construct( $id_base = '', $name = '', $widget_options = array(), $control_options = array() ) {}
        abstract public function widget( $args, $instance );
        abstract public function form( $instance );
        public function update( $new_instance, $old_instance ) { return $new_instance; }
    }
}

// Autoload do composer
require_once dirname( __DIR__ ) . '/vendor/autoload.php';
