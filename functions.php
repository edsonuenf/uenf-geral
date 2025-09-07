<?php
/**
 * CCT Theme functions and definitions
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Inclui o componente de menu personalizado
require_once get_template_directory() . '/components/menu/uenf-menu.php';

// Definir constantes para cores padrão
define('CCT_PRIMARY_COLOR', '#1D3771');
define('CCT_PRIMARY_LIGHT', '#1D3770BF');
define('CCT_TEXT_COLOR', '#26557D');
define('CCT_LINK_COLOR', '#26557D');
define('CCT_LINK_HOVER_COLOR', '#1D3771');
define('CCT_WHITE', '#FFFFFF');
define('CCT_BLACK', '#000000');

// Definir constantes para fontes
define('CCT_PRIMARY_FONT', 'Ubuntu, system-ui, -apple-system, Roboto, Oxygen, sans-serif');
define('CCT_SECONDARY_FONT', 'system-ui, -apple-system, Roboto, Oxygen, sans-serif');

// Tamanhos de fonte
define('CCT_FONT_SIZE_BASE', '1rem');
define('CCT_FONT_SIZE_LG', '1.25rem');
define('CCT_FONT_SIZE_XL', '1.5rem');
define('CCT_FONT_SIZE_XXL', '2rem');

// Configurações padrão do customizer
define('CCT_DEFAULT_MENU_STYLE', 'modern');
define('CCT_DEFAULT_MENU_HIERARCHY_ICONS', true);
define('CCT_DEFAULT_PANEL_WIDTH', '300px');
define('CCT_DEFAULT_TRANSPARENT', 'transparent');

// Valores de configuração
define('CCT_DEFAULT_LAZY_LOADING', true);
define('CCT_DEFAULT_FOOTER_COLUMNS', 3);
define('CCT_DEFAULT_SOCIAL_ALIGNMENT', 'center');

// Constantes de tipografia
define('CCT_DEFAULT_HEADING_FONT', 'Roboto');
define('CCT_DEFAULT_BODY_FONT', 'Open Sans');
define('CCT_DEFAULT_HEADING_FONT_WEIGHT', '600');
define('CCT_DEFAULT_BODY_FONT_WEIGHT', '400');
define('CCT_DEFAULT_BASE_FONT_SIZE', '16');
define('CCT_DEFAULT_LINE_HEIGHT', '1.6');
define('CCT_DEFAULT_LETTER_SPACING', '0');
define('CCT_DEFAULT_TEXT_MAX_WIDTH', '65');
define('CCT_DEFAULT_TYPOGRAPHY_SCALE', 'major_second');
define('CCT_DEFAULT_FONT_PAIRING', 'modern_sans');

// Google Fonts API (opcional - pode ser configurada via customizer)
// define('CCT_GOOGLE_FONTS_API_KEY', 'sua_chave_aqui');

/**
 * Registra padrões de blocos para Aparência → Design → Padrões
 */
function cct_registra_padroes_blocos() {
    // Padrão: Seção de Chamada (Hero)
    register_block_pattern(
        'cct-tema/secao-chamada',
        array(
            'title'       => __('Seção de Chamada', 'cct'),
            'description' => __('Uma seção hero com título, subtítulo e botão de ação', 'cct'),
            'categories'  => array('call-to-action'),
            'content'     => '
                <!-- wp:cover {"overlayColor":"primaria","minHeight":400,"contentPosition":"center center"} -->
                <div class="wp-block-cover" style="min-height:400px">
                    <span aria-hidden="true" class="wp-block-cover__background has-primaria-background-color has-background"></span>
                    <div class="wp-block-cover__inner-container">
                        <!-- wp:heading {"textAlign":"center","level":1,"fontSize":"gigante"} -->
                        <h1 class="has-text-align-center has-gigante-font-size">Bem-vindo ao Nosso Site</h1>
                        <!-- /wp:heading -->
                        
                        <!-- wp:paragraph {"align":"center","fontSize":"medio"} -->
                        <p class="has-text-align-center has-medio-font-size">Descubra soluções inovadoras para o seu negócio</p>
                        <!-- /wp:paragraph -->
                        
                        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                        <div class="wp-block-buttons">
                            <!-- wp:button {"backgroundColor":"destaque","style":{"spacing":{"padding":{"top":"var:preset|spacing|normal","right":"var:preset|spacing|medio","bottom":"var:preset|spacing|normal","left":"var:preset|spacing|medio"}}}} -->
                            <div class="wp-block-button">
                                <a class="wp-block-button__link has-destaque-background-color has-background">Saiba Mais</a>
                            </div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                </div>
                <!-- /wp:cover -->'
        )
    );
    
    // Padrão: Seção de Serviços
    register_block_pattern(
        'cct-tema/secao-servicos',
        array(
            'title'       => __('Seção de Serviços', 'cct'),
            'description' => __('Grid de 3 colunas com ícones e descrições de serviços', 'cct'),
            'categories'  => array('featured'),
            'content'     => '
                <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|muito-grande","bottom":"var:preset|spacing|muito-grande"}}}} -->
                <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--muito-grande);padding-bottom:var(--wp--preset--spacing--muito-grande)">
                    <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"muito-grande"} -->
                    <h2 class="has-text-align-center has-muito-grande-font-size">Nossos Serviços</h2>
                    <!-- /wp:heading -->
                    
                    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"var:preset|spacing|grande"}}}} -->
                    <div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--grande)">
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:heading {"textAlign":"center","level":3,"fontSize":"grande"} -->
                            <h3 class="has-text-align-center has-grande-font-size">🚀 Desenvolvimento</h3>
                            <!-- /wp:heading -->
                            
                            <!-- wp:paragraph {"align":"center"} -->
                            <p class="has-text-align-center">Criamos soluções web modernas e responsivas para o seu negócio.</p>
                            <!-- /wp:paragraph -->
                        </div>
                        <!-- /wp:column -->
                        
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:heading {"textAlign":"center","level":3,"fontSize":"grande"} -->
                            <h3 class="has-text-align-center has-grande-font-size">🎨 Design</h3>
                            <!-- /wp:heading -->
                            
                            <!-- wp:paragraph {"align":"center"} -->
                            <p class="has-text-align-center">Interfaces elegantes e funcionais que encantam seus usuários.</p>
                            <!-- /wp:paragraph -->
                        </div>
                        <!-- /wp:column -->
                        
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:heading {"textAlign":"center","level":3,"fontSize":"grande"} -->
                            <h3 class="has-text-align-center has-grande-font-size">⚡ Performance</h3>
                            <!-- /wp:heading -->
                            
                            <!-- wp:paragraph {"align":"center"} -->
                            <p class="has-text-align-center">Otimização avançada para máxima velocidade e eficiência.</p>
                            <!-- /wp:paragraph -->
                        </div>
                        <!-- /wp:column -->
                    </div>
                    <!-- /wp:columns -->
                </div>
                <!-- /wp:group -->'
        )
    );
    
    // Padrão: Seção de Depoimentos
    register_block_pattern(
        'cct-tema/secao-depoimentos',
        array(
            'title'       => __('Seção de Depoimentos', 'cct'),
            'description' => __('Área de depoimentos com citações destacadas', 'cct'),
            'categories'  => array('testimonials'),
            'content'     => '
                <!-- wp:group {"backgroundColor":"fundo-claro","style":{"spacing":{"padding":{"top":"var:preset|spacing|muito-grande","bottom":"var:preset|spacing|muito-grande","left":"var:preset|spacing|medio","right":"var:preset|spacing|medio"}}}} -->
                <div class="wp-block-group has-fundo-claro-background-color has-background" style="padding-top:var(--wp--preset--spacing--muito-grande);padding-right:var(--wp--preset--spacing--medio);padding-bottom:var(--wp--preset--spacing--muito-grande);padding-left:var(--wp--preset--spacing--medio)">
                    <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"muito-grande"} -->
                    <h2 class="has-text-align-center has-muito-grande-font-size">O que dizem nossos clientes</h2>
                    <!-- /wp:heading -->
                    
                    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"var:preset|spacing|grande"}}}} -->
                    <div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--grande)">
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:quote {"style":{"border":{"left":{"color":"var:preset|color|primaria","width":"4px"}}}} -->
                            <blockquote class="wp-block-quote" style="border-left-color:var(--wp--preset--color--primaria);border-left-width:4px">
                                <p>"Excelente trabalho! A equipe superou todas as nossas expectativas."</p>
                                <cite><strong>Maria Silva</strong><br>CEO, Empresa ABC</cite>
                            </blockquote>
                            <!-- /wp:quote -->
                        </div>
                        <!-- /wp:column -->
                        
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:quote {"style":{"border":{"left":{"color":"var:preset|color|primaria","width":"4px"}}}} -->
                            <blockquote class="wp-block-quote" style="border-left-color:var(--wp--preset--color--primaria);border-left-width:4px">
                                <p>"Profissionais competentes e resultado final impecável."</p>
                                <cite><strong>João Santos</strong><br>Diretor, Tech Solutions</cite>
                            </blockquote>
                            <!-- /wp:quote -->
                        </div>
                        <!-- /wp:column -->
                    </div>
                    <!-- /wp:columns -->
                </div>
                <!-- /wp:group -->'
        )
    );
    
    // Padrão: Seção de Contato
    register_block_pattern(
        'cct-tema/secao-contato',
        array(
            'title'       => __('Seção de Contato', 'cct'),
            'description' => __('Área de contato com informações e call-to-action', 'cct'),
            'categories'  => array('call-to-action'),
            'content'     => '
                <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|muito-grande","bottom":"var:preset|spacing|muito-grande"}}}} -->
                <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--muito-grande);padding-bottom:var(--wp--preset--spacing--muito-grande)">
                    <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"muito-grande"} -->
                    <h2 class="has-text-align-center has-muito-grande-font-size">Entre em Contato</h2>
                    <!-- /wp:heading -->
                    
                    <!-- wp:paragraph {"align":"center","fontSize":"medio"} -->
                    <p class="has-text-align-center has-medio-font-size">Pronto para começar seu projeto? Vamos conversar!</p>
                    <!-- /wp:paragraph -->
                    
                    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"var:preset|spacing|grande"}}}} -->
                    <div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--grande)">
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:heading {"textAlign":"center","level":3,"fontSize":"grande"} -->
                            <h3 class="has-text-align-center has-grande-font-size">📧 Email</h3>
                            <!-- /wp:heading -->
                            
                            <!-- wp:paragraph {"align":"center"} -->
                            <p class="has-text-align-center">contato@seusite.com</p>
                            <!-- /wp:paragraph -->
                        </div>
                        <!-- /wp:column -->
                        
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:heading {"textAlign":"center","level":3,"fontSize":"grande"} -->
                            <h3 class="has-text-align-center has-grande-font-size">📱 Telefone</h3>
                            <!-- /wp:heading -->
                            
                            <!-- wp:paragraph {"align":"center"} -->
                            <p class="has-text-align-center">(11) 99999-9999</p>
                            <!-- /wp:paragraph -->
                        </div>
                        <!-- /wp:column -->
                    </div>
                    <!-- /wp:columns -->
                    
                    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|grande"}}}} -->
                    <div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--grande)">
                        <!-- wp:button {"backgroundColor":"primaria"} -->
                        <div class="wp-block-button">
                            <a class="wp-block-button__link has-primaria-background-color has-background">Solicitar Orçamento</a>
                        </div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
                <!-- /wp:group -->'
        )
    );
}
add_action('init', 'cct_registra_padroes_blocos');

// Cores com transparência
define('CCT_PRIMARY_RGBA', 'rgba(29, 55, 113, 0.95)');
define('CCT_WHITE_RGBA_08', 'rgba(255, 255, 255, 0.8)');
define('CCT_WHITE_RGBA_01', 'rgba(255, 255, 255, 0.1)');
define('CCT_BLACK_RGBA_02', 'rgba(0, 0, 0, 0.2)');

// Espaçamentos
define('CCT_SPACING_UNIT', '1rem'); // @since 1.0.0

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

// Incluir Editor CSS Avançado
require get_template_directory() . '/inc/design-editor/css-editor-loader.php';

// Carregamento direto do Sistema de Tipografia (solução alternativa)
add_action('customize_register', function($wp_customize) {
    // Carregar arquivos do sistema de tipografia
    $typography_files = array(
        get_template_directory() . '/inc/customizer/class-customizer-base.php',
        get_template_directory() . '/inc/customizer/class-typography-customizer.php',
        get_template_directory() . '/inc/customizer/class-typography-controls.php'
    );
    
    foreach ($typography_files as $file) {
        if (file_exists($file)) {
            require_once $file;
        }
    }
    
    // Carregar arquivos do sistema de cores
     $color_files = array(
         get_template_directory() . '/inc/customizer/class-color-manager.php',
         get_template_directory() . '/inc/customizer/class-color-controls.php'
     );
     
     foreach ($color_files as $file) {
         if (file_exists($file)) {
             require_once $file;
         }
     }
     
     // Carregar arquivos do sistema de ícones
      $icon_files = array(
          get_template_directory() . '/inc/customizer/class-icon-manager.php',
          get_template_directory() . '/inc/customizer/class-icon-controls.php'
      );
      
      foreach ($icon_files as $file) {
          if (file_exists($file)) {
              require_once $file;
          }
      }
      
      // Carregar arquivos do sistema de layout
       $layout_files = array(
           get_template_directory() . '/inc/customizer/class-layout-manager.php',
           get_template_directory() . '/inc/customizer/class-layout-controls.php'
       );
       
       foreach ($layout_files as $file) {
           if (file_exists($file)) {
               require_once $file;
           }
       }
       
       // Carregar arquivos do sistema de animações
        $animation_files = array(
            get_template_directory() . '/inc/customizer/class-animation-manager.php',
            get_template_directory() . '/inc/customizer/class-animation-controls.php'
        );
        
        foreach ($animation_files as $file) {
            if (file_exists($file)) {
                require_once $file;
            }
        }
        
        // Carregar arquivos do sistema de gradientes
         $gradient_files = array(
             get_template_directory() . '/inc/customizer/class-gradient-manager.php',
             get_template_directory() . '/inc/customizer/class-gradient-controls.php'
         );
         
         foreach ($gradient_files as $file) {
             if (file_exists($file)) {
                 require_once $file;
             }
         }
         
         // Carregar arquivos do sistema de sombras
         $shadow_files = array(
             get_template_directory() . '/inc/customizer/class-shadow-manager.php',
             get_template_directory() . '/inc/customizer/class-shadow-controls.php'
         );
         
         foreach ($shadow_files as $file) {
             if (file_exists($file)) {
                 require_once $file;
             }
         }
         
         // Carregar arquivos da biblioteca de padrões
         $pattern_files = array(
             get_template_directory() . '/inc/customizer/class-pattern-library-manager.php',
             get_template_directory() . '/inc/customizer/class-pattern-library-controls.php'
         );
         
         foreach ($pattern_files as $file) {
             if (file_exists($file)) {
                 require_once $file;
             }
         }
         
         // Carregar arquivos do modo escuro/claro
         $dark_mode_files = array(
             get_template_directory() . '/inc/customizer/class-dark-mode-manager.php'
         );
         
         foreach ($dark_mode_files as $file) {
             if (file_exists($file)) {
                 require_once $file;
             }
         }
         
         // Carregar arquivos do sistema de breakpoints
         $breakpoints_files = array(
             get_template_directory() . '/inc/customizer/class-responsive-breakpoints-manager.php',
             get_template_directory() . '/inc/customizer/class-breakpoint-manager-control.php'
         );
         
         foreach ($breakpoints_files as $file) {
             if (file_exists($file)) {
                 require_once $file;
             }
         }
         
         // Carregar arquivos do sistema de design tokens
         $design_tokens_files = array(
             get_template_directory() . '/inc/customizer/class-design-tokens-manager.php',
             get_template_directory() . '/inc/customizer/class-design-tokens-control.php'
         );
         
         foreach ($design_tokens_files as $file) {
             if (file_exists($file)) {
                 require_once $file;
             }
         }
    
    // Instanciar módulo de tipografia se a classe existir
    if (class_exists('CCT_Typography_Customizer')) {
        new CCT_Typography_Customizer($wp_customize);
    }
    
    // Instanciar módulo de cores se a classe existir
     if (class_exists('CCT_Color_Manager')) {
         new CCT_Color_Manager($wp_customize);
     }
     
     // Instanciar módulo de ícones se a classe existir
      if (class_exists('CCT_Icon_Manager')) {
          new CCT_Icon_Manager($wp_customize);
      }
      
      // Instanciar módulo de layout se a classe existir
       if (class_exists('CCT_Layout_Manager')) {
           new CCT_Layout_Manager($wp_customize);
       }
       
       // Instanciar módulo de animações se a classe existir
        if (class_exists('CCT_Animation_Manager')) {
            $animation_manager = new CCT_Animation_Manager();
            $animation_manager->register($wp_customize);
        }
        
        // Instanciar módulo de gradientes se a classe existir
         if (class_exists('CCT_Gradient_Manager')) {
             $gradient_manager = new CCT_Gradient_Manager();
             $gradient_manager->register($wp_customize);
         }
         
         // Instanciar módulo de sombras se a classe existir
         if (class_exists('CCT_Shadow_Manager')) {
             $shadow_manager = new CCT_Shadow_Manager();
             $shadow_manager->register($wp_customize);
         }
         
         // Instanciar módulo de biblioteca de padrões se a classe existir
         if (class_exists('CCT_Pattern_Library_Manager')) {
             $pattern_manager = new CCT_Pattern_Library_Manager();
             $pattern_manager->register($wp_customize);
         }
         
         // Instanciar módulo de modo escuro se a classe existir
         if (class_exists('CCT_Dark_Mode_Manager')) {
             $dark_mode_manager = new CCT_Dark_Mode_Manager();
             $dark_mode_manager->register($wp_customize);
         }
         
         // Instanciar módulo de breakpoints se a classe existir
         if (class_exists('CCT_Responsive_Breakpoints_Manager')) {
             $breakpoints_manager = new CCT_Responsive_Breakpoints_Manager();
             $breakpoints_manager->register($wp_customize);
         }
         
         // Instanciar módulo de design tokens se a classe existir
         if (class_exists('CCT_Design_Tokens_Manager')) {
             $design_tokens_manager = new CCT_Design_Tokens_Manager();
             $design_tokens_manager->register($wp_customize);
         }
}, 15); // Prioridade 15 para carregar após outros módulos
require_once CCT_THEME_DIR . '/inc/template-tags.php';
require_once CCT_THEME_DIR . '/inc/template-functions.php';
require_once CCT_THEME_DIR . '/inc/optimization.php';
require_once CCT_THEME_DIR . '/inc/seo.php';
//require_once CCT_THEME_DIR . '/inc/security.php'; // Arquivo de funções de segurança

// Verificar se as funções de template estão disponíveis
if (!function_exists('cct_posted_on') || !function_exists('cct_posted_by') || !function_exists('cct_post_thumbnail')) {
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
    // O Customizer não está carregado
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

/**
 * Enqueue scripts and styles
 * Ordem de carregamento otimizada para melhor performance e manutenção
 */
function cct_scripts() {
    // Versão baseada no timestamp do arquivo para evitar cache
    $theme_version = wp_get_theme()->get('Version');
    $style_path = get_template_directory() . '/css/style.min.css';
    $style_version = file_exists($style_path) ? filemtime($style_path) : $theme_version;
    
    // 1. Fontes externas (carregadas primeiro para evitar FOUT - Flash of Unstyled Text)
    wp_enqueue_style('cct-fonts', 'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap', array(), null);
    wp_enqueue_style('cct-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), '6.4.2');

    // 2. Frameworks e bibliotecas
    wp_enqueue_style('cct-bootstrap', CCT_THEME_URI . '/assets/bootstrap/bootstrap.min.css', array(), $theme_version);

    // 2.1. Variáveis CSS (deve ser carregado antes do style.min.css)
    $variables_path = get_template_directory() . '/css/variables.css';
    $variables_version = file_exists($variables_path) ? filemtime($variables_path) : $theme_version;
    wp_enqueue_style('cct-variables', CCT_THEME_URI . '/css/variables.css', array('cct-bootstrap'), $variables_version);

    // 3. Estilo principal (compilado com todos os estilos em um único arquivo)
    wp_enqueue_style('cct-style', 
        CCT_THEME_URI . '/css/style.min.css', 
        array(
            'cct-fonts',
            'cct-fontawesome',
            'cct-bootstrap',
            'cct-variables'
        ), 
        $style_version // Usa timestamp do arquivo para versionamento
    );
    
    // 3.1 Estilos de componentes (carregados separadamente para garantir que sejam sobrescritos)
    $components = array(
        'new-menu' => '/css/components/new-menu.css',
        'menu-enhancements' => '/css/components/menu-enhancements.css',
        'scrollbars' => '/css/components/scrollbars.css',
        'menu-styles' => '/css/components/menu-styles.css', // Estilos específicos do menu
        'shortcuts' => '/css/components/shortcuts.css' // Estilos do painel de atalhos
    );
    
    // Log de depuração detalhado para verificar os estilos
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('=== INÍCIO DO LOG DE ESTILOS ===');
        error_log('Diretório do tema: ' . get_template_directory());
        error_log('URI do tema: ' . get_template_directory_uri());
        error_log('Versão do tema: ' . $theme_version);
        error_log('Arquivo de estilo principal: ' . $style_path);
        error_log('Versão do estilo principal: ' . $style_version);
        
        foreach ($components as $handle => $path) {
            $file_path = get_template_directory() . $path;
            $file_uri = get_template_directory_uri() . $path;
            $file_exists = file_exists($file_path);
            $file_version = $file_exists ? filemtime($file_path) : 'N/A';
            
            error_log(sprintf(
                'Estilo: %s | Caminho: %s | URI: %s | Existe: %s | Versão: %s',
                $handle,
                $file_path,
                $file_uri,
                $file_exists ? 'Sim' : 'Não',
                $file_version
            ));
        }
        error_log('=== FIM DO LOG DE ESTILOS ===');
    }
    
    foreach ($components as $handle => $path) {
        $file_path = get_template_directory() . $path;
        if (file_exists($file_path)) {
            $file_version = filemtime($file_path);
            
            wp_enqueue_style(
                'cct-' . $handle . '-style',
                CCT_THEME_URI . $path,
                array('cct-style'), // Depende do estilo principal
                $file_version
            );
            
            // Adiciona um parâmetro de consulta para forçar o recarregamento
            wp_style_add_data('cct-' . $handle . '-style', 'ver', $file_version);
        }
    }
    
    // 3.2 Removido: não enfileirar custom-fixes.css; todas as alterações devem vir do SCSS compilado
    
    // 4. Scripts (carregados no final do documento para melhor performance)
    $js_files = array(
        // jQuery (garantir que está carregado primeiro)
        'jquery' => array(
            'path' => false, // Usar o jQuery do WordPress
            'deps' => array()
        ),
        // Gerenciador de eventos (deve ser carregado após o jQuery)
        'cct-event-manager' => array(
            'path' => '/js/event-manager.js', 
            'deps' => array('jquery'),
            'force' => true // Força o carregamento do event-manager.js em todas as páginas
        ),
        // Scripts do menu (dependem do jQuery e do event-manager)
        'cct-menu' => array(
            'path' => '/components/menu/assets/js/uenf-menu-new.js', // Atualizado para usar a nova versão do menu
            'deps' => array('jquery', 'cct-event-manager'),
            'force' => true // Forçar carregamento em todas as páginas
        ),
        // Script principal (carregado por último)
        'cct-main' => array(
            'path' => '/js/main.js', 
            'deps' => array('jquery', 'cct-event-manager')
        ),
        // Outros scripts (carregados condicionalmente)
        'cct-back-to-top' => array(
            'path' => '/js/back-to-top.js', 
            'deps' => array('jquery')
        )
    );
    
    // Garantir que o jQuery seja carregado corretamente
    wp_enqueue_script('jquery');
    
    // Registrar e enfileirar scripts
    foreach ($js_files as $handle => $file) {
        // Verificar se o script deve ser carregado
        if (isset($file['enqueue']) && $file['enqueue'] === false && !isset($file['force'])) {
            continue; // Pula scripts que não devem ser carregados
        }
        
        $file_path = get_template_directory() . $file['path'];
        $file_version = file_exists($file_path) ? filemtime($file_path) : $theme_version;
        
        // Registrar o script
        wp_register_script(
            $handle,
            get_template_directory_uri() . $file['path'],
            $file['deps'],
            $file_version,
            true
        );
        
        // Adicionar dados de localização se necessário
        if ($handle === 'cct-event-manager') {
            wp_localize_script($handle, 'uenfEventManagerVars', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'is_admin' => current_user_can('manage_options')
            ));
        }
        
        // Enfileirar o script
        wp_enqueue_script($handle);
    }
    
    // 5. Suporte a comentários (carregado apenas quando necessário)
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'cct_scripts');

/**
 * Personalizado breadcrumb com ícone de casa
 */
function cct_custom_breadcrumb() {
    echo '<nav aria-label="breadcrumb"><div class="custom-breadcrumb">';
    // Ícone da casa sempre no início
    echo '<a href="' . esc_url(home_url('/')) . '" class="cb-home" style="display:inline-flex; align-items:center; vertical-align:middle;">
        <svg aria-hidden="true" class="e-font-icon-svg e-fas-home" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; margin-right: 2px; display:inline-block; vertical-align:middle;">
            <path fill="rgb(38,85,125)" d="M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z"></path>
        </svg>
    </a><span style="display:inline-block; width:12px;"></span>';

    $items = array();

    if (is_front_page()) {
        // Só a casa, destacada
        echo '<span style="color: #1a3365; font-weight: bold; margin-left: 4px;">Início</span>';
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $items[] = '<a href="' . get_permalink($ancestor) . '" style="color: rgb(38,85,125);">' . get_the_title($ancestor) . '</a>';
            }
        }
        $items[] = '<span style="color: #1a3365; font-weight: bold;">' . get_the_title() . '</span>';
    } elseif (is_single()) {
        $post = get_post();
        $parent = $post->post_parent;
        if ($parent) {
            $items[] = '<a href="' . get_permalink($parent) . '" style="color: rgb(38,85,125);">' . get_the_title($parent) . '</a>';
        }
        $items[] = '<span style="color: #1a3365; font-weight: bold;">' . get_the_title() . '</span>';
    } elseif (is_category()) {
        $category = get_queried_object();
        $parent = $category->parent;
        if ($parent) {
            $parent_category = get_category($parent);
            $items[] = '<a href="' . get_category_link($parent) . '" style="color: rgb(38,85,125);">' . $parent_category->name . '</a>';
        }
        $items[] = '<span style="color: #1a3365; font-weight: bold;">' . $category->name . '</span>';
    } elseif (is_tag()) {
        $tag = get_queried_object();
        $items[] = '<span style="color: #1a3365; font-weight: bold;">' . $tag->name . '</span>';
    } elseif (is_author()) {
        $author = get_queried_object();
        $items[] = '<span style="color: #1a3365; font-weight: bold;">' . $author->display_name . '</span>';
    } elseif (is_date()) {
        $items[] = '<span style="color: #1a3365; font-weight: bold;">' . get_the_date() . '</span>';
    } elseif (is_search()) {
        $items[] = '<span style="color: #1a3365; font-weight: bold;">Resultados da busca</span>';
    } elseif (is_404()) {
        $items[] = '<span style="color: #1a3365; font-weight: bold;">Página não encontrada</span>';
    }

    // Renderiza os itens com separador apenas entre eles
    if (!is_front_page() && count($items)) {
        foreach ($items as $i => $item) {
            if ($i > 0) {
                echo '<span class="cb-sep" style="color: #888; margin: 0 8px;">»</span>';
            }
            echo $item;
        }
    }

    echo '</div></nav>';
}

// Menu personalizado - usando o walker padrão do WordPress

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

// Verifica a ordem de carregamento dos scripts
function cct_debug_scripts_footer() {
    global $wp_scripts;
    
    echo '<!-- Scripts enfileirados: -->' . "\n";
    foreach( $wp_scripts->queue as $handle ) {
        $src = $wp_scripts->registered[$handle]->src;
        $deps = implode(', ', $wp_scripts->registered[$handle]->deps);
        echo "<!-- Script: {$handle} | Src: {$src} | Dependencies: {$deps} -->\n";
    }
    
    // Verifica se o jQuery está carregado corretamente
    echo '<script>
        console.log("[DEBUG] jQuery version:", typeof jQuery !== "undefined" ? jQuery.fn.jquery : "jQuery não carregado");
        console.log("[DEBUG] uenfEventRouter:", typeof uenfEventRouter !== "undefined" ? "Carregado" : "Não carregado");
        console.log("[DEBUG] UENFMenu:", typeof UENFMenu !== "undefined" ? "Definido" : "Não definido");
    </script>';
}
add_action('wp_print_footer_scripts', 'cct_debug_scripts_footer', 9999);

// Inicializa os addons apenas uma vez
if (!function_exists('cct_init_addons')) {
    function cct_init_addons() {
        // Verifica se os addons já foram inicializados
        static $initialized = false;
        if ($initialized) {
            return;
        }
        $initialized = true;
        
        // Inicializa os addons aqui
        do_action('cct_addons_init');
    }
    
    // Garante que os addons sejam inicializados após o tema estar pronto
    add_action('after_setup_theme', 'cct_init_addons', 20);
}

/**
 * Função vazia para manter compatibilidade
 */
function cct_customizer_live_preview() {
    // Função mantida para compatibilidade, mas sem funcionalidade
}
add_action('customize_preview_init', 'cct_customizer_live_preview');

/**
 * Inicializa os addons apenas uma vez
 */
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
