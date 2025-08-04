<?php
/**
 * Funções auxiliares para testes
 */

/**
 * Cria um post de teste
 *
 * @param array $args Argumentos para criar o post
 * @return int|\WP_Error O ID do post criado ou um objeto de erro em caso de falha
 */
function create_test_post($args = []) {
    $defaults = [
        'post_title'   => 'Test Post',
        'post_content' => 'This is a test post',
        'post_status'  => 'publish',
        'post_type'    => 'post',
    ];

    return wp_insert_post(wp_parse_args($args, $defaults));
}

/**
 * Remove um post de teste
 *
 * @param int $post_id ID do post a ser removido
 * @return bool|\WP_Post O post removido ou falso em caso de falha
 */
function delete_test_post($post_id) {
    return wp_delete_post($post_id, true);
}

/**
 * Configura o ambiente para testes
 */
function setup_test_environment() {
    // Configurações iniciais para testes
    if (!defined('WP_TESTS_DOMAIN')) {
        define('WP_TESTS_DOMAIN', 'example.org');
    }
    
    if (!defined('WP_TESTS_EMAIL')) {
        define('WP_TESTS_EMAIL', 'admin@example.org');
    }
    
    if (!defined('WP_TESTS_TITLE')) {
        define('WP_TESTS_TITLE', 'Test Blog');
    }
    
    // Configura o ambiente WordPress
    if (!function_exists('_delete_all_posts')) {
        require_once ABSPATH . 'wp-admin/includes/post.php';
    }
    
    // Limpa dados de teste anteriores
    _delete_all_posts();
}
