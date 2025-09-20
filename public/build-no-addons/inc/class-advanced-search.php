<?php
/**
 * Sistema de Busca Avançada
 * 
 * Implementa funcionalidades avançadas de busca baseadas nas configurações do customizer
 * 
 * @package CCT_Theme
 * @subpackage Search
 * @since 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class CCT_Advanced_Search {
    
    /**
     * Inicializar sistema de busca avançada
     */
    public static function init() {
        // Hooks para modificar a busca (sempre ativos)
        add_action('pre_get_posts', array(__CLASS__, 'modify_search_query'));
        add_filter('posts_search', array(__CLASS__, 'modify_search_sql'), 10, 2);
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_search_scripts'));
        
        // Hooks para multisite
        if (is_multisite() && get_theme_mod('cct_search_multisite', false)) {
            add_action('init', array(__CLASS__, 'setup_multisite_search'));
        }
        
        // Hooks AJAX para busca avançada
        add_action('wp_ajax_cct_advanced_search', array(__CLASS__, 'ajax_search'));
        add_action('wp_ajax_nopriv_cct_advanced_search', array(__CLASS__, 'ajax_search'));
        add_action('wp_ajax_cct_load_more_search', array(__CLASS__, 'ajax_load_more'));
        add_action('wp_ajax_nopriv_cct_load_more_search', array(__CLASS__, 'ajax_load_more'));
    }
    
    /**
     * Modificar query de busca
     */
    public static function modify_search_query($query) {
        if (!is_admin() && $query->is_main_query() && $query->is_search()) {
            // Configurar tipos de post
            self::set_post_types($query);
            
            // Configurar número de resultados
            $results_per_page = get_theme_mod('cct_search_results_per_page', 10);
            $query->set('posts_per_page', $results_per_page);
            
            // Configurar ordenação
            self::set_orderby($query);
        }
    }
    
    /**
     * Configurar tipos de post para busca
     */
    private static function set_post_types($query) {
        $scope = get_theme_mod('cct_search_scope', 'all');
        
        switch ($scope) {
            case 'posts':
                $query->set('post_type', array('post'));
                break;
                
            case 'pages':
                $query->set('post_type', array('page'));
                break;
                
            case 'posts_pages':
                $query->set('post_type', array('post', 'page'));
                break;
                
            case 'custom':
                $custom_types = get_theme_mod('cct_search_post_types', '');
                if (!empty($custom_types)) {
                    $types = array_map('trim', explode(',', $custom_types));
                    $query->set('post_type', $types);
                }
                break;
                
            case 'all':
            default:
                // Buscar em todos os tipos públicos
                $post_types = get_post_types(array('public' => true));
                $query->set('post_type', array_values($post_types));
                break;
        }
    }
    
    /**
     * Configurar ordenação dos resultados
     */
    private static function set_orderby($query) {
        $orderby = get_theme_mod('cct_search_orderby', 'relevance');
        
        switch ($orderby) {
            case 'date':
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
                break;
                
            case 'date_asc':
                $query->set('orderby', 'date');
                $query->set('order', 'ASC');
                break;
                
            case 'title':
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
                break;
                
            case 'title_desc':
                $query->set('orderby', 'title');
                $query->set('order', 'DESC');
                break;
                
            case 'modified':
                $query->set('orderby', 'modified');
                $query->set('order', 'DESC');
                break;
                
            case 'relevance':
            default:
                // Manter ordenação padrão por relevância
                break;
        }
    }
    
    /**
     * Modificar SQL de busca para melhor relevância
     */
    public static function modify_search_sql($search, $query) {
        if (!is_admin() && $query->is_main_query() && $query->is_search()) {
            global $wpdb;
            
            $search_term = $query->get('s');
            if (empty($search_term)) {
                return $search;
            }
            
            // Busca mais inteligente
            $search_term = $wpdb->esc_like($search_term);
            
            $search = " AND (";
            $search .= "({$wpdb->posts}.post_title LIKE '%{$search_term}%') OR ";
            $search .= "({$wpdb->posts}.post_content LIKE '%{$search_term}%') OR ";
            $search .= "({$wpdb->posts}.post_excerpt LIKE '%{$search_term}%')";
            $search .= ")";
        }
        
        return $search;
    }
    
    /**
     * Configurar busca em multisite
     */
    public static function setup_multisite_search() {
        if (!is_multisite()) {
            return;
        }
        
        // Adicionar hook para busca em múltiplos sites
        add_filter('posts_results', array(__CLASS__, 'multisite_search_results'), 10, 2);
    }
    
    /**
     * Buscar em múltiplos sites
     */
    public static function multisite_search_results($posts, $query) {
        if (!$query->is_search() || is_admin()) {
            return $posts;
        }
        
        $search_term = $query->get('s');
        if (empty($search_term)) {
            return $posts;
        }
        
        $multisite_results = array();
        $sites_config = get_theme_mod('cct_search_sites', '');
        
        if (!empty($sites_config)) {
            // Buscar em sites específicos
            $site_ids = array_map('trim', explode(',', $sites_config));
        } else {
            // Buscar em todos os sites
            $site_ids = get_sites(array('fields' => 'ids'));
        }
        
        foreach ($site_ids as $site_id) {
            if ($site_id == get_current_blog_id()) {
                continue; // Pular site atual
            }
            
            switch_to_blog($site_id);
            
            $site_query = new WP_Query(array(
                's' => $search_term,
                'posts_per_page' => 5, // Limitar resultados por site
                'post_status' => 'publish'
            ));
            
            if ($site_query->have_posts()) {
                while ($site_query->have_posts()) {
                    $site_query->the_post();
                    $post = get_post();
                    $post->site_id = $site_id;
                    $post->site_name = get_bloginfo('name');
                    $post->site_url = get_site_url();
                    $multisite_results[] = $post;
                }
            }
            
            restore_current_blog();
        }
        
        // Combinar resultados
        $combined_results = array_merge($posts, $multisite_results);
        
        // Limitar total de resultados
        $max_results = get_theme_mod('cct_search_results_per_page', 10);
        return array_slice($combined_results, 0, $max_results);
    }
    
    /**
     * Carregar scripts de busca
     */
    public static function enqueue_search_scripts() {
        if (is_search()) {
            wp_enqueue_script(
                'cct-advanced-search',
                get_template_directory_uri() . '/js/advanced-search.js',
                array('jquery'),
                filemtime(get_template_directory() . '/js/advanced-search.js'),
                true
            );
            
            // Passar configurações para JavaScript
            wp_localize_script('cct-advanced-search', 'cctSearch', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('cct_search_nonce'),
                'multisite' => is_multisite() && get_theme_mod('cct_search_multisite', false),
                'scope' => get_theme_mod('cct_search_scope', 'all'),
                'orderby' => get_theme_mod('cct_search_orderby', 'relevance')
            ));
        }
    }
    
    /**
     * Obter estatísticas de busca
     */
    public static function get_search_stats($search_term) {
        global $wpdb;
        
        $scope = get_theme_mod('cct_search_scope', 'all');
        $post_types = self::get_search_post_types();
        
        $stats = array();
        
        foreach ($post_types as $post_type) {
            $count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->posts} 
                WHERE post_type = %s 
                AND post_status = 'publish' 
                AND (post_title LIKE %s OR post_content LIKE %s)",
                $post_type,
                '%' . $wpdb->esc_like($search_term) . '%',
                '%' . $wpdb->esc_like($search_term) . '%'
            ));
            
            $stats[$post_type] = $count;
        }
        
        return $stats;
    }
    
    /**
     * Obter tipos de post para busca
     */
    private static function get_search_post_types() {
        $scope = get_theme_mod('cct_search_scope', 'all');
        
        switch ($scope) {
            case 'posts':
                return array('post');
                
            case 'pages':
                return array('page');
                
            case 'posts_pages':
                return array('post', 'page');
                
            case 'custom':
                $custom_types = get_theme_mod('cct_search_post_types', '');
                if (!empty($custom_types)) {
                    return array_map('trim', explode(',', $custom_types));
                }
                return array('post');
                
            case 'all':
            default:
                return get_post_types(array('public' => true));
        }
    }
    
    /**
     * Destacar termos de busca no conteúdo
     */
    public static function highlight_search_terms($content, $search_term) {
        if (empty($search_term)) {
            return $content;
        }
        
        $highlighted = preg_replace(
            '/(' . preg_quote($search_term, '/') . ')/i',
            '<mark class="search-highlight">$1</mark>',
            $content
        );
        
        return $highlighted;
    }
    
    /**
     * Handler AJAX para busca avançada
     */
    public static function ajax_search() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'cct_search_nonce')) {
            wp_die('Erro de segurança');
        }
        
        $search_term = sanitize_text_field($_POST['s']);
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
        $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'relevance';
        
        // Configurar argumentos da query
        $args = array(
            's' => $search_term,
            'posts_per_page' => get_theme_mod('cct_search_results_per_page', 10),
            'post_status' => 'publish'
        );
        
        // Aplicar tipo de post
        if (!empty($post_type)) {
            $args['post_type'] = $post_type;
        } else {
            $args['post_type'] = self::get_search_post_types();
        }
        
        // Aplicar ordenação
        switch ($orderby) {
            case 'date':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
            case 'date_asc':
                $args['orderby'] = 'date';
                $args['order'] = 'ASC';
                break;
            case 'title':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            case 'title_desc':
                $args['orderby'] = 'title';
                $args['order'] = 'DESC';
                break;
            case 'modified':
                $args['orderby'] = 'modified';
                $args['order'] = 'DESC';
                break;
        }
        
        $query = new WP_Query($args);
        $results = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $results[] = array(
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'excerpt' => get_the_excerpt(),
                    'date' => get_the_date(),
                    'post_type' => get_post_type()
                );
            }
            wp_reset_postdata();
        }
        
        $stats = self::get_search_stats($search_term);
        
        wp_send_json_success(array(
            'results' => $results,
            'stats' => array('total' => $query->found_posts)
        ));
    }
    
    /**
     * Handler AJAX para carregar mais resultados
     */
    public static function ajax_load_more() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'cct_search_nonce')) {
            wp_die('Erro de segurança');
        }
        
        $search_term = sanitize_text_field($_POST['s']);
        $page = intval($_POST['page']);
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
        $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'relevance';
        
        $args = array(
            's' => $search_term,
            'posts_per_page' => get_theme_mod('cct_search_results_per_page', 10),
            'paged' => $page,
            'post_status' => 'publish'
        );
        
        if (!empty($post_type)) {
            $args['post_type'] = $post_type;
        } else {
            $args['post_type'] = self::get_search_post_types();
        }
        
        $query = new WP_Query($args);
        $results = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $results[] = array(
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'excerpt' => get_the_excerpt(),
                    'date' => get_the_date(),
                    'post_type' => get_post_type()
                );
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success(array(
            'results' => $results
        ));
    }
}

// Inicializar
CCT_Advanced_Search::init();