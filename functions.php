<?php
/**
 * CCT Theme functions and definitions
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Verificar se estamos no WordPress
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Segurança: Desabilita a edição de arquivos via painel administrativo
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Segurança: Desabilita o acesso direto a arquivos PHP
 */
if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

// Debug
error_log('Functions.php está sendo carregado');

// Define constants
if (!defined('CCT_THEME_VERSION')) {
    define('CCT_THEME_VERSION', '1.0.0');
}
if (!defined('CCT_THEME_DIR')) {
    define('CCT_THEME_DIR', get_template_directory());
}
if (!defined('CCT_THEME_URI')) {
    define('CCT_THEME_URI', get_template_directory_uri());
}

// Carregar arquivos de suporte
require_once CCT_THEME_DIR . '/inc/customizer.php';
require_once CCT_THEME_DIR . '/inc/template-tags.php';
require_once CCT_THEME_DIR . '/inc/template-functions.php';
require_once CCT_THEME_DIR . '/inc/optimization.php';
require_once CCT_THEME_DIR . '/inc/seo.php';
require_once CCT_THEME_DIR . '/inc/security.php'; // Arquivo de funções de segurança

// Carregar o sistema de atualização do GitHub
if (file_exists(CCT_THEME_DIR . '/updater.php')) {
    require_once CCT_THEME_DIR . '/updater.php';
}

// Verificar se as funções de template estão disponíveis
if (!function_exists('cct_posted_on') || !function_exists('cct_posted_by') || !function_exists('cct_post_thumbnail')) {
    error_log('Funções de template não estão disponíveis. Definindo funções de fallback.');
    
    // Definir funções de fallback se não estiverem disponíveis
    if (!function_exists('cct_posted_on')) {
        function cct_posted_on() {
            echo '<span class="posted-on">Publicado em ' . get_the_date() . '</span>';
        }
    }
    
    if (!function_exists('cct_posted_by')) {
        function cct_posted_by() {
            echo '<span class="byline">por ' . get_the_author() . '</span>';
        }
    }
    
    if (!function_exists('cct_post_thumbnail')) {
        function cct_post_thumbnail() {
            if (has_post_thumbnail()) {
                echo '<div class="post-thumbnail">';
                the_post_thumbnail('large');
                echo '</div>';
            }
        }
    }
}

// Verificar se o Customizer está carregado
if (!function_exists('cct_customize_register')) {
    error_log('Customizer não está carregado corretamente');
}

/**
 * Configurações de segurança adicionais
 */
add_action('after_setup_theme', 'cct_security_headers');
if (!function_exists('cct_security_headers')) {
    function cct_security_headers() {
        // Headers de segurança
        if (!is_admin()) {
            // X-Content-Type-Options: previne que navegadores façam MIME-type sniffing
            header('X-Content-Type-Options: nosniff');
            
            // X-XSS-Protection: ativa o filtro XSS do navegador
            header('X-XSS-Protection: 1; mode=block');
            
            // X-Frame-Options: previne clickjacking
            header('X-Frame-Options: SAMEORIGIN');
            
            // Referrer-Policy: controla quanto do referrer é enviado em requisições
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Permissions-Policy: controla quais recursos e APIs podem ser usados no navegador
            header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
        }
    }
}

// Setup theme
function cct_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');
    
    // Add support for image alignment
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_editor_style('css/editor-style.css');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for editor color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => esc_html__('Primary', 'cct-theme'),
            'slug'  => 'primary',
            'color' => '#1d3771',
        ),
        array(
            'name'  => esc_html__('Secondary', 'cct-theme'),
            'slug'  => 'secondary',
            'color' => '#222a3b',
        ),
        array(
            'name'  => esc_html__('Text', 'cct-theme'),
            'slug'  => 'text',
            'color' => '#333333',
        ),
    ));

    // Add support for editor font sizes
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => esc_html__('Small', 'cct-theme'),
            'size' => 14,
            'slug' => 'small'
        ),
        array(
            'name' => esc_html__('Medium', 'cct-theme'),
            'size' => 18,
            'slug' => 'medium'
        ),
        array(
            'name' => esc_html__('Large', 'cct-theme'),
            'size' => 32,
            'slug' => 'large'
        ),
        array(
            'name' => esc_html__('Huge', 'cct-theme'),
            'size' => 48,
            'slug' => 'huge'
        ),
    ));

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 100,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for custom image sizes
    add_image_size('uenf-large', 1200, 9999, false); // Large images
    add_image_size('uenf-medium', 768, 9999, false); // Medium images
    add_image_size('uenf-small', 320, 9999, false); // Small images
    add_image_size('uenf-square', 400, 400, true); // Square images

    // Enable lazy loading for images
    add_theme_support('html5', array('script', 'style', 'image'));
    add_filter('wp_lazy_loading_enabled', '__return_true', 10, 2);

    // Register nav menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'cct-theme'),
        'footer'  => esc_html__('Footer Menu', 'cct-theme'),
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');
}
add_action('after_setup_theme', 'cct_theme_setup');

// Função para otimizar a exibição de imagens
function cct_optimize_image_display($html, $post_id, $post_image_id, $size, $attr) {
    $html = str_replace('<img', '<img loading="lazy" decoding="async"', $html);
    return $html;
}
add_filter('post_thumbnail_html', 'cct_optimize_image_display', 10, 5);
add_filter('get_image_tag', 'cct_optimize_image_display', 10, 5);

// Register widget areas
function cct_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'cct-theme'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'cct-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf(esc_html__('Footer %d', 'cct-theme'), $i),
            'id'            => 'footer-' . $i,
            'description'   => esc_html__('Add footer widgets here.', 'cct-theme'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
    // E-mail
    register_sidebar(array(
        'name'          => 'Email',
        'id'            => 'endereco-email-setor',
        'description'   => esc_html__('Add footer widgets here.', 'cct-theme'),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ));
    // telefone setor
    register_sidebar(array(
        'name'          => 'Telefone',
        'id'            => 'telefone-setor',
        'description'   => esc_html__('Add footer widgets here.', 'cct-theme'),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ));
    // Área de menu e busca
    register_sidebar(array(
        'name'          => 'Ivory Search UENF',
        'id'            => 'ivory-search-uenf',
        'description'   => esc_html__('Add header widgets here.', 'cct-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    // Área de idiomas
    register_sidebar(array(
        'name'          => 'idiomas UENF',
        'id'            => 'idiomas-uenf',
        'description'   => esc_html__('Add header widgets here.', 'cct-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'cct_widgets_init');

function formatarTelefoneBrasil($numero) {
    // Remove qualquer caractere que não seja número
    $numero = preg_replace('/\D/', '', $numero);

    // Verifica se começa com 55 e tem o total correto de dígitos
    if (preg_match('/^55(\d{2})(\d{4,5})(\d{4})$/', $numero, $matches)) {
        return "+55 ({$matches[1]}) {$matches[2]}-{$matches[3]}";
    }

    // Caso não seja válido, retorna o original
    return $numero;
}

add_filter('widget_text', 'formatarTelefoneBrasil');

// Enqueue scripts and styles
function cct_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('cct-fonts', 'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap', array(), CCT_THEME_VERSION);
    wp_enqueue_style('cct-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
    wp_enqueue_style('cct-bootstrap', CCT_THEME_URI . '/assets/bootstrap/bootstrap.min.css', array(), CCT_THEME_VERSION);
    
    // Enqueue back-to-top script
    wp_enqueue_script('cct-back-to-top', get_template_directory_uri() . '/js/back-to-top.js', array(), CCT_THEME_VERSION, true);
    wp_enqueue_script('cct-shortcuts', get_template_directory_uri() . '/js/shortcuts.js', array(), CCT_THEME_VERSION, true);

    // Enqueue main style
    wp_enqueue_style('cct-style', CCT_THEME_URI . '/css/style.css', array(), CCT_THEME_VERSION);
    
    // Estilos para as barras de rolagem personalizadas
    wp_enqueue_style('cct-scrollbars', CCT_THEME_URI . '/css/components/scrollbars.css', array('cct-style'), CCT_THEME_VERSION);
    
    // Estilos para o conteúdo da página inicial
    wp_enqueue_style('cct-page-content', CCT_THEME_URI . '/css/components/page-content.css', array('cct-style'), CCT_THEME_VERSION);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Enqueue JavaScript
    wp_enqueue_script('cct-main', CCT_THEME_URI . '/js/main.js', array('jquery'), CCT_THEME_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'cct_scripts');

/**
 * Personalizado breadcrumb com ícone de casa
 */
function cct_custom_breadcrumb() {
    if (!is_front_page()) {
        echo '<nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="' . esc_url(home_url('/')) . '">
                        <svg aria-hidden="true" class="e-font-icon-svg e-fas-home" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; color: var(--primary-color);">
                            <path d="M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z"></path>
                        </svg>
                    </a>
                </li>';

        // Para páginas normais
        if (is_page()) {
            $ancestors = get_post_ancestors(get_the_ID());
            if ($ancestors) {
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor) {
                    echo '<li class="breadcrumb-item"><a href="' . get_permalink($ancestor) . '" style="color: var(--medium-gray);">' . get_the_title($ancestor) . '</a></li>';
                }
            }
            echo '<li class="breadcrumb-item active"><span>' . get_the_title() . '</span></li>';
        }
        // Para posts
        elseif (is_single()) {
            $post = get_post();
            $parent = $post->post_parent;
            if ($parent) {
                echo '<li class="breadcrumb-item"><a href="' . get_permalink($parent) . '" style="color: var(--medium-gray);">' . get_the_title($parent) . '</a></li>';
            }
            echo '<li class="breadcrumb-item active"><span>' . get_the_title() . '</span></li>';
        }
        // Para categorias
        elseif (is_category()) {
            $category = get_queried_object();
            $parent = $category->parent;
            if ($parent) {
                $parent_category = get_category($parent);
                echo '<li class="breadcrumb-item"><a href="' . get_category_link($parent) . '" style="color: var(--medium-gray);">' . $parent_category->name . '</a></li>';
            }
            echo '<li class="breadcrumb-item active"><span>' . $category->name . '</span></li>';
        }
        // Para tags
        elseif (is_tag()) {
            $tag = get_queried_object();
            echo '<li class="breadcrumb-item active"><span>' . $tag->name . '</span></li>';
        }
        // Para autores
        elseif (is_author()) {
            $author = get_queried_object();
            echo '<li class="breadcrumb-item active"><span>' . $author->display_name . '</span></li>';
        }
        // Para datas
        elseif (is_date()) {
            echo '<li class="breadcrumb-item active"><span>' . get_the_date() . '</span></li>';
        }
        // Para busca
        elseif (is_search()) {
            echo '<li class="breadcrumb-item active"><span>Resultados da busca</span></li>';
        }
        // Para 404
        elseif (is_404()) {
            echo '<li class="breadcrumb-item active"><span>Página não encontrada</span></li>';
        }

        echo '</ol></nav>';
    }
}

// Include WP_Bootstrap_Navwalker
if (file_exists(get_template_directory() . '/class-wp-bootstrap-navwalker.php')) {
    require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
} else {
    error_log('WP_Bootstrap_Navwalker file is missing.');
}

/**
 * Função para registrar e carregar fontes localmente
 */
function theme_local_fonts() {
    // Defina um ID único para sua folha de estilo de fontes
    $font_style_id = 'theme-local-fonts';
    
    // Registre e enfileire sua folha de estilo de fontes
    wp_register_style(
        $font_style_id,
        get_template_directory_uri() . '/assets/fonts/fonts.css',
        array(),
        '1.0.0'
    );
    
    wp_enqueue_style($font_style_id);
}
add_action('wp_enqueue_scripts', 'theme_local_fonts');

/**
 * Carrega os addons do tema
 */
function uenf_load_addons() {
    static $loaded = false;
    
    if ($loaded) {
        return;
    }

    // Lista de addons para carregar
    $addons = [
        'page-visibility/page-visibility.php'
    ];

    foreach ($addons as $addon) {
        $file = get_template_directory() . '/addons/' . $addon;
        
        if (file_exists($file)) {
            require_once $file;
        }
    }

    $loaded = true;
}

// Carrega os addons após o tema estar pronto
add_action('after_setup_theme', 'uenf_load_addons', 10);

// Inicializa os addons apenas uma vez
add_action('init', function() {
    // Verifica se já inicializamos
    static $initialized = false;
    
    if ($initialized) {
        return;
    }

    // Carrega traduções do tema
    load_theme_textdomain('uenf-geral', get_template_directory() . '/languages');

    // Inicializa o addon page-visibility
    if (class_exists('UENF_Page_Visibility')) {
        UENF_Page_Visibility::init();
    }

    $initialized = true;
}, 10);

/**
 * Filtra os itens do menu para remover páginas ocultas diretamente na consulta
 */
function uenf_filter_hidden_pages_from_menu($sorted_menu_items, $args) {
    if (is_admin()) {
        return $sorted_menu_items;
    }

    // Obtém os IDs das páginas que devem ser ocultadas
    global $wpdb;
    $hidden_pages = $wpdb->get_col(
        "SELECT post_id FROM $wpdb->postmeta 
         WHERE meta_key = '_uenf_hide_from_menu' 
         AND meta_value = '1'"
    );

    if (empty($hidden_pages)) {
        return $sorted_menu_items;
    }

    // Filtra os itens do menu
    $filtered_items = [];
    foreach ($sorted_menu_items as $item) {
        if (!in_array($item->object_id, $hidden_pages)) {
            $filtered_items[] = $item;
        }
    }

    return $filtered_items;
}
add_filter('wp_get_nav_menu_items', 'uenf_filter_hidden_pages_from_menu', 999, 2);
