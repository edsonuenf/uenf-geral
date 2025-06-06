<?php
/**
 * Page Visibility Addon
 * 
 * @package UenfGeral
 * @subpackage Addons
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class UENF_Page_Visibility {
    private static $instance = null;

    /**
     * Inicializa o addon
     */
    public static function init() {
        if (self::$instance !== null) {
            return;
        }

        self::$instance = new self();
        self::$instance->setup_hooks();
    }

    private function setup_hooks() {
        // Hooks
        add_action('add_meta_boxes', [__CLASS__, 'add_meta_box']);
        add_action('save_post', [__CLASS__, 'save_meta']);
        
        // Filtro principal para remover itens de menu (prioridade alta para rodar depois do Polylang)
        add_filter('wp_get_nav_menu_items', [__CLASS__, 'filter_menu_items'], 999, 3);
        
        // Filtro adicional para garantir que as páginas não apareçam nos menus mesmo com cache
        add_filter('wp_get_nav_menu_objects', [__CLASS__, 'filter_menu_objects'], 999, 2);
        
        // Filtro para modificar a consulta do menu (prioridade máxima)
        add_filter('wp_get_nav_menu_items', [__CLASS__, 'modify_menu_query'], 9999, 3);
        
        // Assets
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
    }
    
    /**
     * Modifica a consulta do menu para excluir páginas ocultas
     */
    public static function modify_menu_query($items, $menu, $args) {
        if (is_admin()) {
            return $items;
        }
        
        if (empty($items)) {
            return $items;
        }
        
        // Obtém os IDs das páginas que devem ser ocultadas
        global $wpdb;
        $hidden_pages = $wpdb->get_col(
            "SELECT post_id FROM $wpdb->postmeta 
             WHERE meta_key = '_uenf_hide_from_menu' 
             AND meta_value = '1'"
        );
        
        if (empty($hidden_pages)) {
            return $items;
        }
        
        // Filtra os itens do menu
        $filtered_items = [];
        foreach ($items as $item) {
            if (!in_array($item->object_id, $hidden_pages)) {
                $filtered_items[] = $item;
            }
        }
        
        return $filtered_items;
    }
    
    /**
     * Filtra os objetos de menu para remover páginas ocultas
     */
    public static function filter_menu_objects($sorted_menu_items, $args) {
        if (empty($sorted_menu_items) || is_admin()) {
            return $sorted_menu_items;
        }
        
        $filtered_items = [];
        
        foreach ($sorted_menu_items as $item) {
            $should_keep = true;
            
            // Verifica se é uma página e se deve ser ocultada
            if ('post_type' === $item->type && 'page' === $item->object) {
                $hidden = get_post_meta($item->object_id, '_uenf_hide_from_menu', true);
                
                if ('1' === $hidden) {
                    $should_keep = false;
                }
            }
            
            if ($should_keep) {
                $filtered_items[] = $item;
            }
        }
        
        return $filtered_items;
    }

    /**
     * Adiciona o meta box
     */
    public static function add_meta_box() {
        error_log('UENF_Page_Visibility: Tentando adicionar meta box...');
        
        // Verifica se estamos na tela de edição de página
        $screen = get_current_screen();
        error_log('UENF_Page_Visibility: Screen atual: ' . $screen->id);
        
        add_meta_box(
            'uenf_page_visibility',
            'Visibilidade no Menu',
            [__CLASS__, 'render_meta_box'],
            'page',
            'side',
            'default'
        );
        
        error_log('UENF_Page_Visibility: Meta box registrada');
    }

    /**
     * Renderiza o meta box
     */
    public static function render_meta_box($post) {
        wp_nonce_field('uenf_page_visibility_nonce', 'uenf_page_visibility_nonce');
        $hidden = get_post_meta($post->ID, '_uenf_hide_from_menu', true);
        ?>
        <p>
            <label>
                <input type="checkbox" name="uenf_hide_from_menu" <?php checked($hidden, '1'); ?> value="1">
                Ocultar esta página dos menus de navegação
            </label>
        </p>
        <p class="description">Quando marcado, esta página não aparecerá em nenhum menu do site.</p>
        <?php
    }

    /**
     * Salva os dados do meta box
     */
    /**
     * Salva os metadados de visibilidade da página
     * 
     * @param int $post_id ID do post que está sendo salvo
     * @return void
     */
    public static function save_meta($post_id) {
        // Verifica se é um autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verifica o nonce - segurança contra CSRF
        if (!isset($_POST['uenf_page_visibility_nonce']) || 
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['uenf_page_visibility_nonce'])), 'uenf_page_visibility_nonce')) {
            return;
        }

        // Verifica permissões do usuário
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Verifica se o post_type é 'page'
        if (!isset($_POST['post_type']) || 'page' !== $_POST['post_type']) {
            return;
        }

        // Sanitiza o ID do post
        $post_id = absint($post_id);
        if ($post_id <= 0) {
            return;
        }

        // Obtém o valor anterior (para verificar se houve mudança)
        $was_hidden = get_post_meta($post_id, '_uenf_hide_from_menu', true);
        
        // Sanitiza a entrada do usuário
        $is_hidden = isset($_POST['uenf_hide_from_menu']) ? '1' : '';
        
        // Atualiza ou remove o meta conforme necessário
        if ($is_hidden) {
            update_post_meta($post_id, '_uenf_hide_from_menu', '1');
        } else {
            delete_post_meta($post_id, '_uenf_hide_from_menu');
        }
        
        // Limpa o cache do menu se o status de visibilidade mudou
        if ($was_hidden !== $is_hidden) {
            self::clear_menu_cache();
        }
    }
    
    /**
     * Limpa o cache dos menus
     */
    /**
     * Limpa o cache de menus
     * 
     * @return void
     */
    private static function clear_menu_cache() {
        // Verifica se as funções necessárias estão disponíveis
        if (!function_exists('wp_cache_delete') || !function_exists('delete_transient')) {
            return;
        }

        // Limpa o cache de menus
        wp_cache_delete('alloptions', 'options');
        wp_cache_delete('page_children', 'pages');
        
        // Tenta limpar o cache de forma segura
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
        
        // Limpa transientes
        delete_transient('wp_page_menu');
        
        // Limpa o cache de menus do WordPress
        if (function_exists('wp_update_nav_menu_object') && function_exists('wp_get_nav_menus')) {
            $menus = wp_get_nav_menus();
            
            if (!empty($menus) && is_array($menus)) {
                foreach ($menus as $menu) {
                    if (is_object($menu) && isset($menu->term_id)) {
                        $menu_id = absint($menu->term_id);
                        if ($menu_id > 0) {
                            wp_update_nav_menu_object($menu_id, (array) $menu);
                        }
                    }
                }
            }
        }
        
        // Limpa o cache do navegador
        if (!headers_sent()) {
            header('Cache-Control: no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');
        }
        
        // Limpa o cache de menu do navegador
        add_action('wp_footer', function() {
            echo '<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">';
            echo '<meta http-equiv="Pragma" content="no-cache">';
            echo '<meta http-equiv="Expires" content="0">';
        }, 999);
    }

    /**
     * Filtra os itens do menu para remover páginas ocultas
     */
    /**
     * Filtra os itens do menu para remover páginas ocultas
     * 
     * @param array $items Itens do menu
     * @param object $args Argumentos do menu
     * @return array Itens do menu filtrados
     */
    public static function filter_menu_items($items, $args) {
        // Se não houver itens ou estiver no admin, retorna vazio
        if (empty($items) || is_admin()) {
            return $items;
        }

        // Verificação de segurança adicional
        if (!is_array($items)) {
            return [];
        }

        // Array para armazenar os itens que devem permanecer no menu
        $filtered_items = [];
        $removed_count = 0;

        foreach ($items as $item) {
            // Verificação de tipo do objeto
            if (!is_object($item)) {
                continue;
            }

            $should_keep = true;
            
            // Verifica se é uma página e se deve ser ocultada
            if (isset($item->type, $item->object, $item->object_id) && 
                'post_type' === $item->type && 
                'page' === $item->object) {
                
                // Sanitiza o ID do post
                $post_id = absint($item->object_id);
                
                if ($post_id > 0) {
                    // Verifica se o post existe e está publicado
                    $post_status = get_post_status($post_id);
                    
                    if ('publish' !== $post_status) {
                        $should_keep = false;
                    } else {
                        // Verifica se a página deve ser ocultada
                        $hidden = get_post_meta($post_id, '_uenf_hide_from_menu', true);
                        
                        if ('1' === $hidden) {
                            $should_keep = false;
                            $removed_count++;
                            continue;
                        }
                    }
                }
            }
            
            if ($should_keep) {
                $filtered_items[] = $item;
            }
        }
        
        // Se itens foram removidos, atualiza os contadores
        if ($removed_count > 0) {
            // Atualiza os contadores de itens de menu
            add_filter('wp_get_nav_menu_items', function($items, $menu, $args) {
                $menu_order = 1;
                $menu_items_with_children = [];
                
                // Primeiro, define a ordem dos itens
                foreach ($items as $item) {
                    $item->menu_order = $menu_order++;
                    
                    // Se o item tem um pai, adiciona à lista de itens com filhos
                    if ($item->menu_item_parent != 0) {
                        $menu_items_with_children[] = $item->menu_item_parent;
                    }
                }
                
                // Atualiza os contadores de itens com filhos
                $menu_items_with_children = array_unique($menu_items_with_children);
                foreach ($items as $item) {
                    if (in_array($item->ID, $menu_items_with_children)) {
                        $item->classes[] = 'menu-item-has-children';
                    }
                }
                
                return $items;
            }, 10, 3);
        }
        
        return $filtered_items;
    }

    /**
     * Carrega os assets
     */
    public static function enqueue_assets($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }

        $screen = get_current_screen();
        if ('page' !== $screen->post_type) {
            return;
        }

        // CSS
        wp_enqueue_style(
            'uenf-page-visibility-admin',
            get_template_directory_uri() . '/addons/page-visibility/assets/css/admin.css',
            [],
            '1.0.0'
        );

        // JS
        wp_enqueue_script(
            'uenf-page-visibility-admin',
            get_template_directory_uri() . '/addons/page-visibility/assets/js/admin.js',
            ['jquery'],
            '1.0.0',
            true
        );
    }
}

// Inicializa o addon
add_action('init', function() {
    // Verifica se a classe existe antes de chamar o init
    if (class_exists('UENF_Page_Visibility')) {
        UENF_Page_Visibility::init();
        error_log('UENF_Page_Visibility inicializado com sucesso!');
    } else {
        error_log('ERRO: A classe UENF_Page_Visibility não foi encontrada');
    }
}, 20);

// Adiciona um hook de ativação para limpar o cache de menu
add_action('after_switch_theme', function() {
    // Limpa o cache de menus
    add_action('activated_plugin', function() {
        wp_cache_delete('page_children', 'pages');
        delete_transient('wp_page_menu');
        if (function_exists('wp_update_nav_menu_item')) {
            $menus = wp_get_nav_menus();
            if (!empty($menus)) {
                foreach ($menus as $menu) {
                    wp_update_nav_menu_object($menu->term_id, (array) $menu);
                }
            }
        }
    });
});
