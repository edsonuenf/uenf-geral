<?php
/**
 * Componente de Menu UENF
 * 
 * @package UENF_Geral
 */

// Evita acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Inclui a classe do walker do menu
if (!class_exists('UENF_Menu_Walker')) {
    require_once dirname(__FILE__) . '/class-uenf-menu.php';
}

class UENF_Menu_Component {
    
    /**
     * Inicializa o componente
     */
    public static function init() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('after_setup_theme', [__CLASS__, 'register_menus']);
    }
    
    /**
     * Registra os menus do tema
     */
    public static function register_menus() {
        register_nav_menus([
            'primary' => __('Menu Principal', 'uenf-geral'),
            'footer'  => __('Menu do Rodapé', 'uenf-geral'),
        ]);
    }
    
    /**
     * Registra e enfileira os assets do menu
     */
    public static function enqueue_assets() {
        // Estilos
        wp_register_style(
            'uenf-menu-simple-css',
            get_template_directory_uri() . '/components/menu/assets/css/uenf-menu-simple.css',
            [],
            filemtime(get_template_directory() . '/components/menu/assets/css/uenf-menu-simple.css')
        );
        
        // Scripts
        // Garante que o jQuery seja carregado
        wp_enqueue_script('jquery');
        
        // Registra a versão simplificada do menu
        wp_register_script(
            'uenf-menu-simple-js',
            get_template_directory_uri() . '/components/menu/assets/js/uenf-menu-simple.js',
            ['jquery'],
            filemtime(get_template_directory() . '/components/menu/assets/js/uenf-menu-simple.js'),
            true
        );
        
        // Carrega os assets apenas se o menu estiver em uso
        if (has_nav_menu('primary')) {
            wp_enqueue_style('uenf-menu-simple-css');
            wp_enqueue_script('uenf-menu-simple-js');
        }
    }
    
    /**
     * Função auxiliar para exibir o menu
     * 
     * @param array $args Argumentos para o menu
     * @return void|string Retorna o HTML do menu se $echo for false
     */
    public static function display_menu($args = []) {
        $defaults = [
            'theme_location'  => 'primary',
            'container'       => 'nav',
            'container_id'    => 'site-navigation',
            'menu_class'      => 'new-menu',
            'menu_id'         => 'primary-menu',
            'container_class' => 'menu-container',
            'fallback_cb'     => [__CLASS__, 'fallback_menu'],
            'walker'          => new UENF_Menu_Walker(),
            'echo'            => true,
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Garante que os assets estão carregados
        if (!wp_style_is('uenf-menu-css', 'enqueued')) {
            wp_enqueue_style('uenf-menu-css');
        }
        
        // Carrega a nova versão do menu por padrão
        if (!wp_script_is('uenf-menu-new-js', 'enqueued') && !wp_script_is('uenf-menu-js', 'enqueued')) {
            wp_enqueue_script('uenf-menu-new-js');
            
            // Descomente a linha abaixo para voltar à versão antiga se necessário
            // wp_enqueue_script('uenf-menu-js');
        }
        
        // Retorna ou exibe o menu
        if (false === $args['echo']) {
            return wp_nav_menu($args);
        }
        
        wp_nav_menu($args);
    }
    
    /**
     * Fallback para quando não há menu definido
     */
    public static function fallback_menu() {
        ?>
        <div class="menu-fallback">
            <ul class="new-menu">
                <li class="menu-item">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'uenf-geral'); ?></a>
                </li>
                <?php
                // Lista as páginas principais
                wp_list_pages([
                    'title_li' => '',
                    'depth'    => 1,
                ]);
                ?>
            </ul>
        </div>
        <?php
    }
}

// Inicializa o componente
UENF_Menu_Component::init();

/**
 * Função template para facilitar o uso do menu
 * 
 * @param array $args Argumentos para o menu
 * @return void|string Retorna o HTML do menu se $echo for false
 */
function uenf_menu($args = []) {
    return UENF_Menu_Component::display_menu($args);
}
