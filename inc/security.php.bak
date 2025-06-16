<?php
/**
 * Funções de segurança para o tema UENF Geral
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove a versão do WordPress dos cabeçalhos e feeds
 */
add_filter('the_generator', '__return_empty_string');

/**
 * Remove a versão do WordPress dos scripts e estilos
 */
add_filter('script_loader_src', 'cct_remove_wp_version_strings');
add_filter('style_loader_src', 'cct_remove_wp_version_strings');

if (!function_exists('cct_remove_wp_version_strings')) {
    function cct_remove_wp_version_strings($src) {
        global $wp_version;
        
        // Verifica se é um arquivo do WordPress
        $parse_url = wp_parse_url($src);
        
        if (strpos($parse_url['path'], 'wp-includes/') !== false || 
            strpos($parse_url['path'], 'wp-admin/') !== false || 
            strpos($parse_url['path'], 'wp-content/') !== false) {
            
            // Remove a versão da URL
            if (strpos($src, 'ver=' . $wp_version) !== false) {
                $src = remove_query_arg('ver', $src);
            }
        }
        
        return $src;
    }
}

/**
 * Desabilita o envio de informações sobre o WordPress no cabeçalho HTTP
 */
add_action('after_setup_theme', 'cct_remove_wp_version_header');
if (!function_exists('cct_remove_wp_version_header')) {
    function cct_remove_wp_version_header() {
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }
}

/**
 * Previne enumeração de usuários
 */
if (!is_admin()) {
    // Redireciona tentativas de acesso a author=1 para a home
    if (isset($_GET['author'])) {
        wp_redirect(home_url(), 301);
        exit;
    }
    
    // Remove os links de feed para autores
    add_filter('author_rewrite_rules', '__return_empty_array');
}

/**
 * Desabilita o XML-RPC se não for necessário
 */
add_filter('xmlrpc_enabled', '__return_false');
add_filter('pings_open', '__return_false', 9999);
add_filter('wp_headers', 'cct_remove_x_pingback');

if (!function_exists('cct_remove_x_pingback')) {
    function cct_remove_x_pingback($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    }
}

/**
 * Segurança para uploads
 */
add_filter('upload_mimes', 'cct_restrict_mime_types');
if (!function_exists('cct_restrict_mime_types')) {
    function cct_restrict_mime_types($mime_types) {
        // Tipos de arquivo permitidos
        $allowed_mimes = array(
            // Imagens
            'jpg|jpeg|jpe' => 'image/jpeg',
            'gif'           => 'image/gif',
            'png'           => 'image/png',
            'webp'          => 'image/webp',
            // Documentos
            'pdf'           => 'application/pdf',
            'doc'           => 'application/msword',
            'docx'          => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'           => 'application/vnd.ms-excel',
            'xlsx'          => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt'           => 'application/vnd.ms-powerpoint',
            'pptx'          => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            // Arquivos de texto
            'txt'           => 'text/plain',
            'csv'           => 'text/csv',
            // Arquivos compactados
            'zip'           => 'application/zip',
        );
        
        return $allowed_mimes;
    }
}

/**
 * Previne acesso a arquivos sensíveis
 */
add_action('init', 'cct_prevent_sensitive_file_access');
if (!function_exists('cct_prevent_sensitive_file_access')) {
    function cct_prevent_sensitive_file_access() {
        if (!is_admin() && !wp_doing_ajax() && !wp_doing_cron()) {
            $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            
            // Lista de arquivos sensíveis para bloquear
            $sensitive_files = array(
                'wp-config.php',
                '.htaccess',
                'readme.html',
                'license.txt',
                'wp-includes',
                'wp-config-sample.php',
                'wp-activate.php',
                'wp-blog-header.php',
                'wp-comments-post.php',
                'wp-cron.php',
                'wp-links-opml.php',
                'wp-load.php',
                'wp-login.php',
                'wp-mail.php',
                'wp-settings.php',
                'wp-signup.php',
                'wp-trackback.php',
                'xmlrpc.php'
            );
            
            foreach ($sensitive_files as $file) {
                if (strpos($request_uri, $file) !== false) {
                    wp_die('Acesso negado.', 'Acesso Negado', array('response' => 403));
                }
            }
        }
    }
}

/**
 * Adiciona segurança extra ao cabeçalho
 */
add_action('send_headers', 'cct_additional_security_headers');
if (!function_exists('cct_additional_security_headers')) {
    function cct_additional_security_headers() {
        if (!is_admin()) {
            // Habilita o modo de segurança do navegador (HSTS)
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
            
            // Previne MIME-type sniffing
            header('X-Content-Type-Options: nosniff');
            
            // Configurações de segurança para iframes
            header('X-Frame-Options: SAMEORIGIN');
            
            // Política de segurança de conteúdo (CSP)
            // ATENÇÃO: Configure de acordo com seu site
            // header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; font-src 'self' https: data:;");
        }
    }
}
