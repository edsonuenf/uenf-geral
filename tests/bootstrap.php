
<?php
/**
 * PHPUnit bootstrap file para ambiente WordPress
 */

error_reporting( E_ALL | E_STRICT );

// Caminho para o wp-tests do WordPress (ajuste conforme necessário)
if ( getenv( 'WP_TESTS_DIR' ) ) {
    $wp_tests_dir = getenv( 'WP_TESTS_DIR' );
} else {
    $wp_tests_dir = '/tmp/wordpress-tests-lib';
}

// Carrega o autoload do Composer se disponível
if ( file_exists( dirname( __DIR__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __DIR__ ) . '/vendor/autoload.php';
}

// Carrega o bootstrap do WordPress para testes
require_once $wp_tests_dir . '/includes/functions.php';

// Carrega as funções do tema após o WordPress
tests_add_filter( 'muplugins_loaded', function() {
    require dirname( __DIR__ ) . '/functions.php';
} );

require_once $wp_tests_dir . '/includes/bootstrap.php';

// Inclui funções de teste personalizadas
require_once __DIR__ . '/test-functions.php';
