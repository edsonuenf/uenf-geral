<?php
/**
 * CCT Theme functions and definitions
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Corrigir erro de buffer zlib
if (!defined('ABSPATH')) {
    exit;
}

// Desabilitar compressão zlib para evitar erro ob_end_flush
if (function_exists('ini_set')) {
    ini_set('zlib.output_compression', 'Off');
    ini_set('output_buffering', 'Off');
}

// Limpar qualquer buffer existente
if (ob_get_level()) {
    ob_end_clean();
}

// Inclui o componente de menu personalizado
require_once get_template_directory() . '/components/menu/uenf-menu.php';

// Definir constantes para cores padrão
define('CCT_PRIMARY_COLOR', '#1D3771');
define('CCT_PRIMARY_LIGHT', '#1D3770BF');
define('CCT_TEXT_COLOR', '#333');
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
define('CCT_DEFAULT_MENU_HIERARCHY_ICONS', false);
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
 * 
 * NOTA: Os patterns FAQ e Pricing agora estão na pasta /patterns como arquivos nativos do WordPress.
 * Esta função mantém apenas os patterns básicos para compatibilidade.
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
// Carregar Gerenciador de Extensões primeiro
require_once CCT_THEME_DIR . '/inc/extensions/class-extension-manager.php';

// Carregar customizer
require_once CCT_THEME_DIR . '/inc/customizer.php';

// Carregar sistema de reset de configurações
require_once CCT_THEME_DIR . '/inc/class-theme-reset-manager.php';

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
    
    // Usar o Gerenciador de Extensões para carregar módulos
    $extension_manager = cct_extension_manager();
    
    // Carregar extensões através do gerenciador (incluindo tipografia)
     cct_init_customizer_extensions($wp_customize, $extension_manager);
     
     // Inicialização condicional das extensões (respeitando configurações do gerenciador)
     
     // Tipografia - só inicializa se estiver ativa
     if (class_exists('CCT_Typography_Customizer') && $extension_manager && $extension_manager->is_extension_active('typography')) {
         try {
             $typography_manager = new CCT_Typography_Customizer($wp_customize);
             
             if (defined('WP_DEBUG') && WP_DEBUG) {
                 error_log('CCT: Tipografia inicializada (extensão ativa)');
             }
         } catch (Exception $e) {
             if (defined('WP_DEBUG') && WP_DEBUG) {
                 error_log('CCT: Erro ao inicializar tipografia: ' . $e->getMessage());
             }
         }
     } elseif (defined('WP_DEBUG') && WP_DEBUG) {
         error_log('CCT: Tipografia não inicializada (extensão desativada)');
     }
     
     // Cores - só inicializa se estiver ativa
     if (class_exists('CCT_Color_Manager') && $extension_manager && $extension_manager->is_extension_active('colors')) {
         try {
             $color_manager = new CCT_Color_Manager($wp_customize);
             
             if (defined('WP_DEBUG') && WP_DEBUG) {
                 error_log('CCT: Gerenciador de cores inicializado (extensão ativa)');
             }
         } catch (Exception $e) {
             if (defined('WP_DEBUG') && WP_DEBUG) {
                 error_log('CCT: Erro ao inicializar cores: ' . $e->getMessage());
             }
         }
     } elseif (defined('WP_DEBUG') && WP_DEBUG) {
         error_log('CCT: Cores não inicializadas (extensão desativada)');
     }
     
     // Ícones - só inicializa se estiver ativa
     if (class_exists('CCT_Icon_Manager') && $extension_manager && $extension_manager->is_extension_active('icons')) {
         try {
             $icon_manager = new CCT_Icon_Manager($wp_customize);
             
             if (defined('WP_DEBUG') && WP_DEBUG) {
                 error_log('CCT: Gerenciador de ícones inicializado (extensão ativa)');
             }
         } catch (Exception $e) {
             if (defined('WP_DEBUG') && WP_DEBUG) {
                 error_log('CCT: Erro ao inicializar ícones: ' . $e->getMessage());
             }
         }
     } elseif (defined('WP_DEBUG') && WP_DEBUG) {
         error_log('CCT: Ícones não inicializados (extensão desativada)');
     }
}, 15); // Prioridade 15 para carregar após outros módulos

/**
 * Adiciona menu administrativo do Tema UENF
 */
function cct_add_admin_menu() {
    // Menu principal do Tema UENF
    add_menu_page(
        'Tema UENF',                    // Título da página
        'Tema UENF',                    // Título do menu (removido emoji)
        'manage_options',               // Capacidade necessária
        'tema-uenf',                    // Slug do menu
        'cct_admin_page_callback',      // Função callback
        'dashicons-admin-appearance',   // Ícone padrão do WordPress
        20                              // Posição padrão entre menus principais
    );
    
    // Submenu: Gerenciador de Extensões
    add_submenu_page(
        'tema-uenf',                    // Menu pai
        'Gerenciador de Extensões',     // Título da página
        'Extensões',                    // Título do submenu
        'manage_options',               // Capacidade necessária
        'tema-uenf-extensoes',          // Slug
        'cct_extensions_page_callback'  // Função callback
    );
    
    // Submenu: Reset de Configurações
    add_submenu_page(
        'tema-uenf',                    // Menu pai
        'Reset de Configurações',       // Título da página
        'Reset de Configurações',       // Título do submenu
        'manage_options',               // Capacidade necessária
        'tema-uenf-reset',              // Slug
        'cct_reset_page_callback'       // Função callback
    );
    
    // Submenu: Documentação de Personalização
    add_submenu_page(
        'tema-uenf',                    // Menu pai
        'Documentação de Personalização', // Título da página
        'Documentação Design',          // Título do submenu
        'manage_options',               // Capacidade necessária
        'tema-uenf-docs-design',        // Slug
        'cct_docs_design_page_callback' // Função callback
    );
    
    // Submenu: Customizer
    add_submenu_page(
        'tema-uenf',                    // Menu pai
        'Personalizar Tema',            // Título da página
        'Personalizar',                 // Título do submenu
        'manage_options',               // Capacidade necessária
        'customize.php'                 // Link direto para o customizer
    );
}
add_action('admin_menu', 'cct_add_admin_menu', 5);

/**
 * Página principal do Tema UENF
 */
function cct_admin_page_callback() {
    ?>
    <div class="wrap">
        <h1>🎓 Tema UENF</h1>
        <p>Bem-vindo ao painel de controle do Tema UENF. Gerencie todas as funcionalidades e configurações do seu tema.</p>
        
        <div class="card" style="max-width: 800px;">
            <h2>🚀 Acesso Rápido</h2>
            <p><a href="<?php echo admin_url('admin.php?page=tema-uenf-extensoes'); ?>" class="button button-primary">🔧 Gerenciar Extensões</a></p>
            <p><a href="<?php echo admin_url('customize.php'); ?>" class="button button-secondary">🎨 Personalizar Tema</a></p>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>📊 Status do Sistema</h2>
            <?php
            $extension_manager = cct_extension_manager();
            if ($extension_manager) {
                $active_count = 0;
                $total_count = 0;
                
                $extensions = $extension_manager->get_all_extensions();
                $total_count = count($extensions);
                
                foreach ($extensions as $id => $extension) {
                    if ($extension_manager->is_extension_active($id)) {
                        $active_count++;
                    }
                }
                
                echo '<p><strong>Extensões Ativas:</strong> ' . $active_count . ' de ' . $total_count . '</p>';
                
                $percentage = $total_count > 0 ? ($active_count / $total_count) * 100 : 0;
                if ($percentage <= 30) {
                    echo '<p><strong>Performance:</strong> 🟢 Excelente</p>';
                } elseif ($percentage <= 60) {
                    echo '<p><strong>Performance:</strong> 🟡 Boa</p>';
                } elseif ($percentage <= 80) {
                    echo '<p><strong>Performance:</strong> 🟠 Moderada</p>';
                } else {
                    echo '<p><strong>Performance:</strong> 🔴 Pesada</p>';
                }
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Página de Documentação de Personalização do Design
 */
function cct_docs_design_page_callback() {
    $docs_file = get_template_directory() . '/GUIA-CONFIGURACAO-DESIGN.md';
    $docs_content = '';
    
    if (file_exists($docs_file)) {
        $docs_content = file_get_contents($docs_file);
        // Converte Markdown avançado para HTML
        $docs_content = preg_replace('/^# (.+)$/m', '<h1 class="docs-h1">$1</h1>', $docs_content);
        $docs_content = preg_replace('/^## (.+)$/m', '<h2 class="docs-h2">$1</h2>', $docs_content);
        $docs_content = preg_replace('/^### (.+)$/m', '<h3 class="docs-h3">$1</h3>', $docs_content);
        $docs_content = preg_replace('/^#### (.+)$/m', '<h4 class="docs-h4">$1</h4>', $docs_content);
        $docs_content = preg_replace('/\*\*(.+?)\*\*/', '<strong class="docs-bold">$1</strong>', $docs_content);
        $docs_content = preg_replace('/\*(.+?)\*/', '<em class="docs-italic">$1</em>', $docs_content);
        $docs_content = preg_replace('/`(.+?)`/', '<code class="docs-code">$1</code>', $docs_content);
        $docs_content = preg_replace('/^- (.+)$/m', '<li class="docs-li">$1</li>', $docs_content);
        $docs_content = preg_replace('/(<li class="docs-li">.*<\/li>)/s', '<ul class="docs-ul">$1</ul>', $docs_content);
        $docs_content = preg_replace('/^> (.+)$/m', '<blockquote class="docs-quote">$1</blockquote>', $docs_content);
        $docs_content = nl2br($docs_content);
    } else {
        $docs_content = '<div class="docs-error"><p>📄 Arquivo de documentação não encontrado.</p></div>';
    }
    
    ?>    <style>    /* Força o modo claro para a página de documentação */    body.wp-admin,    #wpwrap,    #wpcontent,    .wrap {        background-color: #f1f1f1 !important;        color: #333 !important;    }        .docs-container {        max-width: 1200px;        margin: 0 auto;        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;        background-color: #f1f1f1 !important;        color: #333 !important;    }
    .docs-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    .docs-header h1 {
        margin: 0 0 10px 0;
        font-size: 2.5em;
        font-weight: 700;
    }
    .docs-header p {
        margin: 0;
        font-size: 1.1em;
        opacity: 0.9;
    }
    .docs-nav {
        background: white;
        border: 1px solid #e1e5e9;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .docs-nav h3 {
        margin: 0 0 15px 0;
        color: #2c3e50;
        font-size: 1.2em;
    }
    .docs-nav-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .docs-content {
        background: white;
        border: 1px solid #e1e5e9;
        border-radius: 8px;
        padding: 40px;
        line-height: 1.7;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    .docs-h1 {
        color: #2c3e50;
        border-bottom: 3px solid #3498db;
        padding-bottom: 10px;
        margin: 30px 0 20px 0;
        font-size: 2.2em;
    }
    .docs-h2 {
        color: #34495e;
        margin: 25px 0 15px 0;
        font-size: 1.8em;
        position: relative;
        padding-left: 20px;
    }
    .docs-h2:before {
        content: '▶';
        position: absolute;
        left: 0;
        color: #3498db;
        font-size: 0.8em;
        top: 50%;
        transform: translateY(-50%);
    }
    .docs-h3 {
        color: #2c3e50;
        margin: 20px 0 12px 0;
        font-size: 1.4em;
        border-left: 4px solid #3498db;
        padding-left: 15px;
    }
    .docs-h4 {
        color: #34495e;
        margin: 15px 0 10px 0;
        font-size: 1.2em;
    }
    .docs-bold {
        color: #2c3e50;
        font-weight: 600;
    }
    .docs-italic {
        color: #7f8c8d;
    }
    .docs-code {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 2px 6px;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.9em;
        color: #e74c3c;
    }
    .docs-ul {
        margin: 15px 0;
        padding-left: 0;
    }
    .docs-li {
        list-style: none;
        margin: 8px 0;
        padding-left: 25px;
        position: relative;
    }
    .docs-li:before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #27ae60;
        font-weight: bold;
    }
    .docs-quote {
        background: #f8f9fa;
        border-left: 4px solid #3498db;
        margin: 20px 0;
        padding: 15px 20px;
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: #2c3e50;
    }
    .docs-error {
        background: #fff5f5;
        border: 1px solid #fed7d7;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        color: #c53030;
    }
    .docs-footer {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    .docs-footer h3 {
        margin: 0 0 15px 0;
        font-size: 1.3em;
    }
    .docs-footer .button {
        margin: 5px;
        padding: 12px 24px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    .docs-footer .button-primary {
        background: white;
        color: #667eea;
        border: 2px solid white;
    }
    .docs-footer .button-primary:hover {
        background: transparent;
        color: white;
        transform: translateY(-2px);
    }
    .docs-footer .button-secondary {
        background: transparent;
        color: white;
        border: 2px solid white;
    }
    .docs-footer .button-secondary:hover {
        background: white;
        color: #f5576c;
        transform: translateY(-2px);
    }
    @media (max-width: 768px) {
        .docs-header, .docs-content, .docs-footer {
            padding: 20px;
        }
        .docs-header h1 {
            font-size: 2em;
        }
        .docs-nav-buttons {
            flex-direction: column;
        }
    }
    </style>
    
    <div class="wrap docs-container">
        <div class="docs-header">
            <h1>📖 Documentação de Personalização do Design</h1>
            <p>Guia completo e interativo para personalizar o visual do seu site usando o Tema UENF</p>
        </div>
        
        <div class="docs-nav">
            <h3>🚀 Acesso Rápido</h3>
            <div class="docs-nav-buttons">
                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">🎨 Abrir Customizer</a>
                <a href="<?php echo admin_url('admin.php?page=tema-uenf-extensoes'); ?>" class="button button-secondary">🔧 Gerenciar Extensões</a>
                <a href="<?php echo admin_url('admin.php?page=tema-uenf-reset'); ?>" class="button button-secondary">🔄 Reset Configurações</a>
            </div>
        </div>
        
        <div class="docs-content">
            <?php echo $docs_content; ?>
        </div>
        
        <div class="docs-footer">
            <h3>🎯 Próximos Passos</h3>
            <p style="margin-bottom: 20px;">Agora que você conhece as opções de personalização, comece a criar seu design único!</p>
            <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">🎨 Começar Personalização</a>
            <a href="<?php echo admin_url('admin.php?page=tema-uenf'); ?>" class="button button-secondary">← Voltar ao Painel</a>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Adiciona smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
        
        // Adiciona efeito de highlight ao passar o mouse sobre seções
        document.querySelectorAll('.docs-h2, .docs-h3').forEach(heading => {
            heading.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
                this.style.transition = 'transform 0.3s ease';
            });
            heading.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    });
    </script>
    <?php
}

/**
 * Página do Gerenciador de Extensões
 */
function cct_extensions_page_callback() {
    // Processar formulário se enviado
    if (isset($_POST['cct_update_extensions']) && wp_verify_nonce($_POST['cct_extensions_nonce'], 'cct_extensions_action')) {
        $extension_manager = cct_extension_manager();
        if ($extension_manager) {
            $extensions = $extension_manager->get_all_extensions();
            
            // Atualizar cada extensão
            foreach ($extensions as $id => $extension) {
                $is_enabled = isset($_POST['extension_' . $id]) ? true : false;
                $extension_manager->toggle_extension($id, $is_enabled);
            }
            
            echo '<div class="notice notice-success is-dismissible" style="border-left: 4px solid #46b450; background: #f7fcf0; padding: 12px 15px; margin: 20px 0; border-radius: 4px;"><p style="margin: 0; color: #155724; font-weight: 500;">🎉 Configurações de extensões atualizadas com sucesso!</p></div>';
        }
    }
    
    // CSS personalizado para melhor UX/UI
    echo '<style>
    .cct-extensions-page {
        background: #f1f1f1;
        margin: -20px -20px 0 -10px;
        padding: 20px;
        min-height: calc(100vh - 32px);
    }
    .cct-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .cct-header h1 {
        margin: 0 0 10px 0;
        font-size: 2.2em;
        font-weight: 600;
    }
    .cct-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 1.1em;
    }
    .cct-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e1e5e9;
        transition: all 0.3s ease;
    }
    .cct-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .cct-card h2 {
        margin: 0 0 15px 0;
        color: #2c3e50;
        font-size: 1.4em;
        font-weight: 600;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 10px;
    }
    .cct-controls {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .cct-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    .cct-btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    .cct-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    .cct-btn-success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(86, 171, 47, 0.3);
    }
    .cct-btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(86, 171, 47, 0.4);
        color: white;
    }
    .cct-btn-danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ffa8a8 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
    }
    .cct-btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        color: white;
    }
    .cct-btn-save {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
        box-shadow: 0 3px 12px rgba(76, 175, 80, 0.3);
    }
    .cct-btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(76, 175, 80, 0.4);
        color: white;
    }
    .cct-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .cct-table th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }
    .cct-table td {
        padding: 15px;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
    }
    .cct-table tr:hover {
        background: #f8f9fa;
    }
    .cct-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #667eea;
    }
    .cct-status-active {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .cct-status-inactive {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .cct-extension-title {
        font-weight: 600;
        color: #2c3e50;
        font-size: 15px;
    }
    .cct-description {
        color: #6c757d;
        font-size: 14px;
        line-height: 1.4;
    }
    .cct-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .cct-stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e1e5e9;
    }
    .cct-stat-number {
        font-size: 2.5em;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .cct-stat-label {
        color: #6c757d;
        font-size: 14px;
        font-weight: 500;
    }
    </style>';
    
    ?>
    <div class="cct-extensions-page">
        <div class="cct-header">
            <h1>🔧 Gerenciador de Extensões</h1>
            <p>Controle todas as funcionalidades avançadas do tema. Desative extensões não utilizadas para melhorar a performance.</p>
        </div>
        
        <?php
        // Estatísticas das extensões
        $extension_manager = cct_extension_manager();
        if ($extension_manager) {
            $extensions = $extension_manager->get_all_extensions();
            $total_count = count($extensions);
            $active_count = 0;
            
            foreach ($extensions as $id => $extension) {
                if ($extension_manager->is_extension_active($id)) {
                    $active_count++;
                }
            }
            
            $inactive_count = $total_count - $active_count;
            $percentage = $total_count > 0 ? round(($active_count / $total_count) * 100) : 0;
            ?>
            <div class="cct-stats">
                <div class="cct-stat-card">
                    <div class="cct-stat-number" style="color: #667eea;"><?php echo $total_count; ?></div>
                    <div class="cct-stat-label">Total de Extensões</div>
                </div>
                <div class="cct-stat-card">
                    <div class="cct-stat-number" style="color: #4CAF50;"><?php echo $active_count; ?></div>
                    <div class="cct-stat-label">Extensões Ativas</div>
                </div>
                <div class="cct-stat-card">
                    <div class="cct-stat-number" style="color: #ff6b6b;"><?php echo $inactive_count; ?></div>
                    <div class="cct-stat-label">Extensões Inativas</div>
                </div>
                <div class="cct-stat-card">
                    <div class="cct-stat-number" style="color: #ffa726;"><?php echo $percentage; ?>%</div>
                    <div class="cct-stat-label">Taxa de Utilização</div>
                </div>
            </div>
            <?php
        }
        ?>
        
        <div class="cct-card">
            <h2>⚡ Acesso Rápido</h2>
            <p style="margin-bottom: 20px; color: #6c757d;">Configure as extensões ativas diretamente no Customizer do WordPress:</p>
            <a href="<?php echo admin_url('customize.php'); ?>" class="cct-btn cct-btn-primary">🎨 Abrir Customizer</a>
            <p style="margin-top: 15px; font-size: 13px; color: #6c757d;">💡 <strong>Dica:</strong> No Customizer, procure por: <strong>🎓 Tema UENF → 🔧 Gerenciador de Extensões</strong></p>
        </div>
        
        <div class="cct-card">
            <h2>📋 Gerenciar Extensões</h2>
            <?php
            if ($extension_manager && !empty($extensions)) {
                ?>
                <form method="post" action="">
                    <?php wp_nonce_field('cct_extensions_action', 'cct_extensions_nonce'); ?>
                    
                    <div class="cct-controls">
                        <button type="button" id="select-all-extensions" class="cct-btn cct-btn-success">✅ Selecionar Todas</button>
                        <button type="button" id="deselect-all-extensions" class="cct-btn cct-btn-danger">❌ Desmarcar Todas</button>
                    </div>
                    
                    <table class="cct-table">
                        <thead>
                            <tr>
                                <th style="width: 60px; text-align: center;">Ativar</th>
                                <th>Extensão</th>
                                <th style="width: 120px; text-align: center;">Status</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($extensions as $id => $extension) {
                                $is_active = $extension_manager->is_extension_active($id);
                                $status_class = $is_active ? 'cct-status-active' : 'cct-status-inactive';
                                $status_text = $is_active ? '✅ Ativa' : '❌ Inativa';
                                $title = isset($extension['title']) ? $extension['title'] : ucfirst($id);
                                $description = isset($extension['description']) ? $extension['description'] : 'Sem descrição disponível';
                                
                                echo '<tr>';
                                echo '<td style="text-align: center;"><input type="checkbox" name="extension_' . esc_attr($id) . '" class="extension-checkbox cct-checkbox" ' . checked($is_active, true, false) . '></td>';
                                echo '<td><span class="cct-extension-title">' . esc_html($title) . '</span></td>';
                                echo '<td style="text-align: center;"><span class="' . $status_class . '">' . $status_text . '</span></td>';
                                echo '<td><span class="cct-description">' . esc_html($description) . '</span></td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    
                    <div style="margin-top: 25px; text-align: center;">
                        <input type="submit" name="cct_update_extensions" class="cct-btn cct-btn-save" value="💾 Salvar Configurações">
                    </div>
                </form>
                
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Selecionar todas as extensões
                    document.getElementById('select-all-extensions').addEventListener('click', function() {
                        var checkboxes = document.querySelectorAll('.extension-checkbox');
                        checkboxes.forEach(function(checkbox) {
                            checkbox.checked = true;
                        });
                        
                        // Feedback visual
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = 'scale(1)';
                        }, 150);
                    });
                    
                    // Desmarcar todas as extensões
                    document.getElementById('deselect-all-extensions').addEventListener('click', function() {
                        var checkboxes = document.querySelectorAll('.extension-checkbox');
                        checkboxes.forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                        
                        // Feedback visual
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = 'scale(1)';
                        }, 150);
                    });
                    
                    // Animação nos checkboxes
                    document.querySelectorAll('.extension-checkbox').forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                            var row = this.closest('tr');
                            if (this.checked) {
                                row.style.background = 'linear-gradient(135deg, #f0fff4 0%, #e6ffed 100%)';
                            } else {
                                row.style.background = '';
                            }
                        });
                    });
                });
                </script>
                <?php
            } else {
                echo '<div style="text-align: center; padding: 40px; color: #6c757d;">';
                echo '<div style="font-size: 48px; margin-bottom: 20px;">📦</div>';
                echo '<h3>Nenhuma extensão encontrada</h3>';
                echo '<p>O gerenciador de extensões não está disponível ou não há extensões registradas.</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Garante que o sistema global de extensões esteja ativo (sem forçar extensões específicas)
 */
function cct_ensure_global_system_activated() {
    // Garantir apenas que o sistema global esteja ativo
    if (get_theme_mod('cct_extensions_global_enabled') === false) {
        set_theme_mod('cct_extensions_global_enabled', true);
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('CCT: Sistema global de extensões ativado');
        }
    }
    
    // Não forçar mais a ativação de extensões específicas
    // Permitir que o usuário tenha controle total sobre quais extensões ativar
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('CCT: Sistema de extensões inicializado - controle total do usuário');
    }
}
add_action('after_setup_theme', 'cct_ensure_global_system_activated', 5);

/**
 * Inicializa extensões do customizer através do gerenciador
 */
function cct_init_customizer_extensions($wp_customize, $extension_manager) {
    // Verificar se o gerenciador está disponível
    if (!$extension_manager || !is_object($extension_manager)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('CCT: Gerenciador de extensões não disponível');
        }
        return;
    }
    
    // Garantir que o sistema esteja habilitado globalmente
     $global_enabled = get_theme_mod('cct_extensions_global_enabled', true);
     
     // Forçar ativação se não estiver definido
     if ($global_enabled === null || $global_enabled === false) {
         set_theme_mod('cct_extensions_global_enabled', true);
         $global_enabled = true;
         
         if (defined('WP_DEBUG') && WP_DEBUG) {
             error_log('CCT: Sistema de extensões foi reativado automaticamente');
         }
     }
     
     if (!$global_enabled) {
         if (defined('WP_DEBUG') && WP_DEBUG) {
             error_log('CCT: Sistema de extensões desabilitado globalmente - não carregando extensões');
         }
         return;
     }
    
    // Mapeamento de extensões para classes
     $extension_classes = array(
         'colors' => 'CCT_Color_Manager',
         'icons' => 'CCT_Icon_Manager', 
         'typography' => 'CCT_Typography_Customizer',
         'dark_mode' => 'CCT_Dark_Mode_Manager',
         'shadows' => 'CCT_Shadow_Manager',
         'breakpoints' => 'CCT_Responsive_Breakpoints_Manager',
         'design_tokens' => 'CCT_Design_Tokens_Manager',
         'patterns' => 'CCT_Pattern_Library_Manager',
         'gradients' => 'CCT_Gradient_Manager',
         'animations' => 'CCT_Animation_Manager'
     );
    
    // Classes que precisam de $wp_customize no construtor
      $constructor_classes = array('colors', 'icons', 'typography');
     
     // Carregar cada extensão se estiver ativa
     foreach ($extension_classes as $extension_id => $class_name) {
         if ($extension_manager->is_extension_active($extension_id) && class_exists($class_name)) {
             try {
                 // Verificar se a classe precisa de $wp_customize no construtor
                 if (in_array($extension_id, $constructor_classes)) {
                     // Classes que recebem $wp_customize no construtor
                     $manager = new $class_name($wp_customize);
                     
                     // Verificar se tem método init
                     if (method_exists($manager, 'init')) {
                         $manager->init();
                     }
                 } else {
                     // Classes que usam método register
                     $manager = new $class_name();
                     
                     if (method_exists($manager, 'register')) {
                         $manager->register($wp_customize);
                     }
                 }
                 
                 // Log para debug
                 if (defined('WP_DEBUG') && WP_DEBUG) {
                     error_log("CCT: Extensão {$extension_id} carregada com sucesso");
                 }
                 
             } catch (Exception $e) {
                 // Log de erro
                 if (defined('WP_DEBUG') && WP_DEBUG) {
                     error_log("CCT: Erro ao carregar extensão {$extension_id}: " . $e->getMessage());
                 }
             }
         } else {
             // Log quando extensão está desabilitada
             if (defined('WP_DEBUG') && WP_DEBUG && !$extension_manager->is_extension_active($extension_id)) {
                 error_log("CCT: Extensão {$extension_id} está desabilitada");
             }
         }
     }
}
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
    
    // Add support for block patterns
    add_theme_support('core-block-patterns');
    
    // Register block pattern categories
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category('uenf-patterns', array(
            'label' => __('UENF Patterns', 'cct-theme'),
        ));
        
        register_block_pattern_category('uenf-faq', array(
            'label' => __('FAQ', 'cct-theme'),
        ));
        
        register_block_pattern_category('uenf-pricing', array(
            'label' => __('Pricing', 'cct-theme'),
        ));
    }
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
    // Área de busca personalizada removida - substituída por solução nativa
    // Widget de redes sociais removido - usando configurações do customizer
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

/**
 * Gera CSS dinâmico para redes sociais
 */
function cct_get_social_media_css() {
    // Garantir que o valor padrão seja 36 se não estiver definido ou for inválido
    $icon_size = get_theme_mod('social_media_icon_size', 36);
    if (empty($icon_size) || $icon_size < 20 || $icon_size > 80) {
        $icon_size = 36;
    }
    
    $icon_color = get_theme_mod('social_media_icon_color', 'rgba(255, 255, 255, 0.6)');
    $bg_color = get_theme_mod('social_media_bg_color', '#1d3771');
    $border_width = get_theme_mod('social_media_border_width', 0);
    $border_color = get_theme_mod('social_media_border_color', '#ffffff');
    $border_radius = get_theme_mod('social_media_border_radius', 50);
    
    $font_size = round($icon_size * 0.45); // Proporção do ícone em relação ao container
    
    return sprintf(
        'width: %dpx; height: %dpx; font-size: %dpx; color: %s !important; background-color: %s; border: %dpx solid %s; border-radius: %d%%;',
        $icon_size,
        $icon_size,
        $font_size,
        esc_attr($icon_color),
        esc_attr($bg_color),
        $border_width,
        esc_attr($border_color),
        $border_radius
    );
}

/**
 * Exibe as redes sociais configuradas no customizer
 */
function cct_display_social_media() {
    $social_networks = array('facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'telegram', 'whatsapp');
    $alignment = get_theme_mod('social_media_alignment', 'right');
    $icon_gap = get_theme_mod('social_media_icon_gap', 6);
    $social_css = cct_get_social_media_css();
    
    $output = '<div class="social-media-links" style="text-align: ' . esc_attr($alignment) . '; gap: ' . esc_attr($icon_gap) . 'px;">';
    $has_links = false;
    
    foreach ($social_networks as $network) {
        $link = get_theme_mod($network . '_link', '');
        $icon = get_theme_mod($network . '_icon', 'fab fa-' . $network);
        
        if (!empty($link)) {
            $has_links = true;
            $output .= '<a href="' . esc_url($link) . '" target="_blank" rel="noopener noreferrer" class="social-link social-' . esc_attr($network) . '" style="' . $social_css . '" title="' . esc_attr(ucfirst($network)) . '">';
            $output .= '<i class="' . esc_attr($icon) . '" aria-hidden="true"></i>';
            $output .= '<span class="screen-reader-text">' . esc_html(ucfirst($network)) . '</span>';
            $output .= '</a>';
        }
    }
    
    $output .= '</div>';
    
    // Só exibe se houver pelo menos uma rede social configurada
    if ($has_links) {
        echo $output;
    }
}

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

    // 2. Frameworks e bibliotecas (Bootstrap CDN completo para garantir funcionalidade do offcanvas)
    wp_enqueue_style('cct-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2');
    wp_style_add_data('cct-bootstrap', 'integrity', 'sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN');
    wp_style_add_data('cct-bootstrap', 'crossorigin', 'anonymous');

    // 2.1. Variáveis CSS (deve ser carregado antes do style.min.css)
    $variables_path = get_template_directory() . '/css/variables.css';
    $variables_version = file_exists($variables_path) ? filemtime($variables_path) : $theme_version;
    wp_enqueue_style('cct-variables', CCT_THEME_URI . '/css/variables.css', array('cct-bootstrap'), $variables_version);

    // 2.5 Reset CSS (deve ser carregado antes de todos os outros estilos)
    $reset_css_path = get_template_directory() . '/css/reset.css';
    if (file_exists($reset_css_path)) {
        $reset_css_version = filemtime($reset_css_path);
        wp_enqueue_style('cct-reset', CCT_THEME_URI . '/css/reset.css', array(), $reset_css_version);
    }
    
    // 2.6 Correção específica para hero e header (carregado após reset)
    $hero_header_fix_path = get_template_directory() . '/css/hero-header-fix.css';
    if (file_exists($hero_header_fix_path)) {
        $hero_header_fix_version = filemtime($hero_header_fix_path);
        wp_enqueue_style('cct-hero-header-fix', CCT_THEME_URI . '/css/hero-header-fix.css', array('cct-reset'), $hero_header_fix_version);
    }
    
    // 3. Estilo principal (compilado com todos os estilos em um único arquivo)
    wp_enqueue_style('cct-style', 
        CCT_THEME_URI . '/css/style.min.css', 
        array(
            'cct-fonts',
            'cct-fontawesome',
            'cct-bootstrap',
            'cct-variables',
            'cct-reset',
            'cct-hero-header-fix'
        ), 
        $style_version // Usa timestamp do arquivo para versionamento
     );
    
    // 3.1 Estilos adicionais (removidos do header.php para melhor performance)
    wp_enqueue_style('cct-styles-additional', CCT_THEME_URI . '/css/styles.css', array('cct-style'), $style_version);
    wp_enqueue_style('cct-custom-fixes', CCT_THEME_URI . '/css/custom-fixes.css', array('cct-styles-additional'), $style_version);
    
    // 3.1.1 Estilos dos Block Patterns
    $patterns_css_path = get_template_directory() . '/css/patterns.css';
    if (file_exists($patterns_css_path)) {
        $patterns_css_version = filemtime($patterns_css_path);
        wp_enqueue_style('cct-patterns', CCT_THEME_URI . '/css/patterns.css', array('cct-style'), $patterns_css_version);
    }
    
    // 3.1.2 Correções específicas para o WordPress Customizer
    if (is_customize_preview()) {
        $customizer_fix_path = get_template_directory() . '/css/customizer-fix.css';
        if (file_exists($customizer_fix_path)) {
            $customizer_fix_version = filemtime($customizer_fix_path);
            wp_enqueue_style('cct-customizer-fix', CCT_THEME_URI . '/css/customizer-fix.css', array('cct-style'), $customizer_fix_version);
        }
    }
    
    // 3.2 Estilos de componentes (carregados separadamente para garantir que sejam sobrescritos)
    $components = array(
        'new-menu' => '/css/components/new-menu.css',
        'menu-enhancements' => '/css/components/menu-enhancements.css',
        'scrollbars' => '/css/components/scrollbars.css',
        'menu-styles' => '/css/components/menu-styles.css', // Estilos específicos do menu
        'shortcuts' => '/css/components/shortcuts.css', // Estilos do painel de atalhos
        'search-modern' => '/css/search-modern.css', // Estilos modernos da página de busca
        'search-retractable' => '/css/components/search-retractable.css' // Estilos da busca retrátil
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
    
    // 3.2 Correções de espaçamento (carregado por último para garantir precedência)
    $spacing_fixes_path = get_template_directory() . '/css/spacing-fixes.css';
    if (file_exists($spacing_fixes_path)) {
        $spacing_fixes_version = filemtime($spacing_fixes_path);
        wp_enqueue_style(
            'cct-spacing-fixes',
            CCT_THEME_URI . '/css/spacing-fixes.css',
            array('cct-style', 'cct-new-menu-style'), // Depende de todos os outros estilos
            $spacing_fixes_version
        );
    }
    
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
        // Bootstrap JS (removido do header.php para melhor performance)
        'cct-bootstrap-js' => array(
            'path' => false, // Usar CDN para Bootstrap JS
            'deps' => array('jquery'),
            'force' => true,
            'cdn' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'
        ),
        // Script principal (carregado por último)
        'cct-main' => array(
            'path' => '/js/main.js', 
            'deps' => array('jquery', 'cct-event-manager', 'cct-bootstrap-js')
        ),
        // Outros scripts (carregados condicionalmente)
        'cct-back-to-top' => array(
            'path' => '/js/back-to-top.js', 
            'deps' => array('jquery')
        ),
        // Script da busca retrátil
        'cct-search-retractable' => array(
            'path' => '/js/search-retractable.js',
            'deps' => array('jquery', 'cct-event-manager')
        ),
        // Script dos Block Patterns
        'cct-patterns' => array(
            'path' => '/js/patterns.js',
            'deps' => array('jquery'),
            'force' => true
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
        
        // Lidar com scripts CDN
        if (isset($file['cdn']) && $file['cdn']) {
            wp_register_script(
                $handle,
                $file['cdn'],
                $file['deps'],
                null, // Sem versão para CDN
                true
            );
        } else {
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
        }
        
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
 * Links apenas para páginas sem filhos
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
                // Verificar se a página ancestral tem filhos usando WP_Query
                $child_query = new WP_Query(array(
                    'post_type' => 'page',
                    'post_parent' => $ancestor,
                    'post_status' => 'publish',
                    'posts_per_page' => 1,
                    'fields' => 'ids'
                ));
                
                $has_children = $child_query->have_posts();
                wp_reset_postdata();
                
                if (!$has_children) {
                    // Página sem filhos - criar link
                    $items[] = '<a href="' . get_permalink($ancestor) . '" style="color: rgb(38,85,125);" title="' . esc_attr(get_the_title($ancestor)) . '">' . get_the_title($ancestor) . '</a>';
                } else {
                    // Página com filhos - apenas texto
                    $items[] = '<span style="color: #666; font-weight: normal;" title="Esta página possui subpáginas">' . get_the_title($ancestor) . '</span>';
                }
            }
        }
        // Página atual sempre como texto (sem link)
        $items[] = '<span style="color: #1a3365; font-weight: bold;">' . get_the_title() . '</span>';
    } elseif (is_single()) {
        $post = get_post();
        $parent = $post->post_parent;
        if ($parent) {
            // Verificar se a página pai tem filhos usando WP_Query
            $child_query = new WP_Query(array(
                'post_type' => 'page',
                'post_parent' => $parent,
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'fields' => 'ids'
            ));
            
            $has_children = $child_query->have_posts();
            wp_reset_postdata();
            
            if (!$has_children) {
                // Página pai sem filhos - criar link
                $items[] = '<a href="' . get_permalink($parent) . '" style="color: rgb(38,85,125);">' . get_the_title($parent) . '</a>';
            } else {
                // Página pai com filhos - apenas texto
                $items[] = '<span style="color: #666; font-weight: normal;">' . get_the_title($parent) . '</span>';
            }
        }
        $items[] = '<span style="color: #1a3365; font-weight: bold;">' . get_the_title() . '</span>';
    } elseif (is_category()) {
        $category = get_queried_object();
        $parent = $category->parent;
        if ($parent) {
            $parent_category = get_category($parent);
            // Para categorias, verificar se tem subcategorias
            $subcategories = get_categories(array(
                'parent' => $parent,
                'hide_empty' => false,
                'number' => 1
            ));
            
            if (empty($subcategories)) {
                // Categoria pai sem filhos - criar link
                $items[] = '<a href="' . get_category_link($parent) . '" style="color: rgb(38,85,125);">' . $parent_category->name . '</a>';
            } else {
                // Categoria pai com filhos - apenas texto
                $items[] = '<span style="color: #666; font-weight: normal;">' . $parent_category->name . '</span>';
            }
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

// Verifica a ordem de carregamento dos scripts (apenas se WP_DEBUG ativo)
function cct_debug_scripts_footer() {
    // Só executa se WP_DEBUG estiver ativo
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }
    
    global $wp_scripts;
    
    // Usar error_log em vez de echo para evitar problemas de output buffering
    if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
        $debug_info = "Scripts enfileirados: ";
        foreach( $wp_scripts->queue as $handle ) {
            $src = $wp_scripts->registered[$handle]->src;
            $deps = implode(', ', $wp_scripts->registered[$handle]->deps);
            $debug_info .= "Script: {$handle} | Src: {$src} | Dependencies: {$deps}; ";
        }
        error_log($debug_info);
    }
    
    // JavaScript debug apenas no console
    echo '<script>
        if (typeof console !== "undefined") {
            console.log("[CCT DEBUG] jQuery version:", typeof jQuery !== "undefined" ? jQuery.fn.jquery : "jQuery não carregado");
            console.log("[CCT DEBUG] uenfEventRouter:", typeof uenfEventRouter !== "undefined" ? "Carregado" : "Não carregado");
            console.log("[CCT DEBUG] UENFMenu:", typeof UENFMenu !== "undefined" ? "Definido" : "Não definido");
        }
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

/**
 * Página de Reset de Configurações
 */
function cct_reset_page_callback() {
    // Verificar se o usuário tem permissão
    if (!current_user_can('manage_options')) {
        wp_die(__('Você não tem permissão para acessar esta página.'));
    }
    
    // Processar ações de reset se enviadas
    if (isset($_POST['action']) && wp_verify_nonce($_POST['reset_nonce'], 'cct_reset_action')) {
        $reset_manager = UENF_Theme_Reset_Manager::get_instance();
        $message = '';
        $message_type = 'success';
        
        switch ($_POST['action']) {
            case 'reset_theme':
                $result = $reset_manager->reset_theme_settings();
                $message = $result ? 'Configurações do tema resetadas com sucesso!' : 'Erro ao resetar configurações do tema.';
                $message_type = $result ? 'success' : 'error';
                break;
                
            case 'reset_extensions':
                $extension_manager = cct_extension_manager();
                if ($extension_manager) {
                    $result = $extension_manager->reset_all_extensions();
                    $message = $result ? 'Configurações de extensões resetadas com sucesso!' : 'Erro ao resetar configurações de extensões.';
                    $message_type = $result ? 'success' : 'error';
                }
                break;
                
            case 'reset_all':
                $result = $reset_manager->reset_all_settings();
                $message = $result ? 'Todas as configurações resetadas com sucesso!' : 'Erro ao resetar todas as configurações.';
                $message_type = $result ? 'success' : 'error';
                break;
        }
        
        if ($message) {
            echo '<div class="notice notice-' . $message_type . ' is-dismissible"><p>' . esc_html($message) . '</p></div>';
        }
    }
    ?>
    <div class="wrap">
        <h1>🔄 Reset de Configurações</h1>
        <p>Use esta página para resetar configurações do tema e extensões para os valores padrão.</p>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>⚠️ Atenção</h2>
            <p><strong>Esta ação não pode ser desfeita!</strong> Certifique-se de fazer um backup das suas configurações antes de prosseguir.</p>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>🔄 Opções de Reset</h2>
            
            <form method="post" style="margin-bottom: 20px;">
                <?php wp_nonce_field('cct_reset_action', 'reset_nonce'); ?>
                <input type="hidden" name="action" value="reset_theme">
                <h3>Reset do Tema</h3>
                <p>Reseta apenas as configurações do tema (cores, tipografia, layout, etc.)</p>
                <button type="submit" class="button button-secondary" onclick="return confirm('Tem certeza que deseja resetar as configurações do tema? Esta ação não pode ser desfeita.');">🎨 Resetar Tema</button>
            </form>
            
            <form method="post" style="margin-bottom: 20px;">
                <?php wp_nonce_field('cct_reset_action', 'reset_nonce'); ?>
                <input type="hidden" name="action" value="reset_extensions">
                <h3>Reset de Extensões</h3>
                <p>Reseta apenas as configurações das extensões ativas</p>
                <button type="submit" class="button button-secondary" onclick="return confirm('Tem certeza que deseja resetar as configurações das extensões? Esta ação não pode ser desfeita.');">🔧 Resetar Extensões</button>
            </form>
            
            <form method="post">
                <?php wp_nonce_field('cct_reset_action', 'reset_nonce'); ?>
                <input type="hidden" name="action" value="reset_all">
                <h3>Reset Completo</h3>
                <p><strong>Reseta TODAS as configurações</strong> (tema + extensões)</p>
                <button type="submit" class="button button-primary" style="background-color: #dc3545; border-color: #dc3545;" onclick="return confirm('ATENÇÃO: Esta ação irá resetar TODAS as configurações do tema e extensões. Esta ação não pode ser desfeita. Tem certeza que deseja continuar?');">🗑️ Reset Completo</button>
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>📋 Acesso Rápido</h2>
            <p><a href="<?php echo admin_url('admin.php?page=tema-uenf'); ?>" class="button button-secondary">← Voltar ao Tema UENF</a></p>
            <p><a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">🎨 Abrir Customizer</a></p>
        </div>
    </div>
    <?php
}
