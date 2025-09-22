<?php
/**
 * CCT Theme Customizer
 * 
 * Este arquivo contém todas as configurações do WordPress Customizer para o tema CCT.
 * Inclui seções, configurações e controles para personalização visual do tema.
 * 
 * Funcionalidades principais:
 * - Configurações de menu (estilo, ícones, cores)
 * - Painel de atalhos (cores, largura, comportamento)
 * - Sistema de cores personalizáveis
 * - Configurações de cabeçalho e rodapé
 * - Campos de formulário personalizáveis
 * - Sistema de reset para valores padrão
 * - Backup e restauração de configurações
 * - Preview em tempo real com postMessage
 * 
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 * @author Equipe de Desenvolvimento CCT
 * 
 * @see https://developer.wordpress.org/themes/customize-api/
 */

// ============================================================================
// VERIFICAÇÕES DE SEGURANÇA E COMPATIBILIDADE
// ============================================================================

/**
 * Verificação de segurança: Impede acesso direto ao arquivo
 * 
 * ABSPATH é uma constante definida pelo WordPress que contém o caminho
 * absoluto para o diretório raiz da instalação. Se não estiver definida,
 * significa que o arquivo está sendo acessado diretamente, o que não é permitido.
 */
if (!defined('ABSPATH')) {
    exit; // Sair se acessado diretamente
}

/**
 * Verificação de compatibilidade: Garantir que o WordPress está carregado
 * 
 * add_action é uma função fundamental do WordPress. Se não existir,
 * significa que o WordPress não foi carregado corretamente.
 */
if (!function_exists('add_action')) {
    return; // Retornar silenciosamente se o WordPress não estiver carregado
}

/**
 * Funções de fallback para compatibilidade
 * 
 * Estas funções garantem que o customizer funcione mesmo em ambientes
 * onde algumas funções do WordPress podem não estar disponíveis durante
 * o carregamento inicial.
 */

/**
 * Fallback para função de tradução
 * 
 * @param string $text Texto a ser traduzido
 * @param string $domain Domínio de tradução
 * @return string Texto original (sem tradução)
 */
if (!function_exists('__')) {
    function __($text, $domain) {
        return $text;
    }
}

/**
 * Fallback para função de escape de atributos HTML
 * 
 * @param string $text Texto a ser escapado
 * @return string Texto escapado para uso seguro em atributos HTML
 */
if ( ! function_exists( 'esc_attr' ) ) {
    function esc_attr( $text ) {
        return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
    }
}

if ( ! function_exists( 'get_theme_mod' ) ) {
    function get_theme_mod( $name, $default = false ) {
        // Placeholder for get_theme_mod
        return $default;
    }
}

// ============================================================================
// CLASSES PERSONALIZADAS DO CUSTOMIZER
// ============================================================================

/**
 * Nota: Classes WP nativas devem estar disponíveis no ambiente WordPress
 * 
 * As classes como WP_Customize_Control, WP_Customize_Color_Control, etc.
 * são fornecidas nativamente pelo WordPress e não precisam de fallbacks.
 */

/**
 * Controle personalizado para seleção de ícones
 * 
 * Esta classe estende WP_Customize_Control para criar um seletor dropdown
 * personalizado que permite aos usuários escolher ícones de uma lista predefinida.
 * 
 * Funcionalidades:
 * - Dropdown com opções de ícones
 * - Integração completa com a API do Customizer
 * - Suporte a descrições e labels
 * - Sanitização automática de valores
 * 
 * @since 1.0.0
 * @see WP_Customize_Control
 */
if (class_exists('WP_Customize_Control')) {
    class Customize_Icon_Select_Control extends WP_Customize_Control {
        /**
         * Tipo do controle personalizado
         * 
         * @var string
         */
        public $type = 'icon-select';
        
        /**
         * Array de ícones disponíveis para seleção
         * 
         * @var array Formato: array('valor' => 'Label do Ícone')
         */
        public $icons = array();

        /**
         * Construtor da classe
         * 
         * Inicializa o controle e configura os ícones disponíveis.
         * 
         * @param WP_Customize_Manager $manager Instância do gerenciador do customizer
         * @param string $id ID único do controle
         * @param array $args Argumentos de configuração
         */
        public function __construct($manager, $id, $args = array()) {
            parent::__construct($manager, $id, $args);
            if (isset($args['icons'])) {
                $this->icons = $args['icons'];
            }
        }

        /**
         * Renderiza o conteúdo do controle
         * 
         * Gera o HTML do dropdown de seleção de ícones.
         * Inclui label, descrição e opções de seleção.
         */
        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
                <select <?php $this->link(); ?>>
                    <?php foreach ($this->icons as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($this->value(), $value); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php
        }
    }
}

// Controle personalizado para cores com suporte a RGBA
if (class_exists('WP_Customize_Control')) {
    class Customize_Alpha_Color_Control extends WP_Customize_Control {
        public $type = 'alpha-color';
        public $palette = true;
        public $default = '#FFFFFF';

        public function enqueue() {
            // Apenas garante que o wp-color-picker está enfileirado
            wp_enqueue_script('wp-color-picker');
            // Adiciona estilos inline
            $css = '
            .wp-picker-container .wp-picker-holder {
                position: absolute;
                z-index: 100;
            }
            .wp-picker-container .wp-picker-holder .wp-picker-alpha {
                padding: 10px 0;
            }
            .wp-picker-container .wp-picker-holder .wp-picker-alpha-slider {
                margin: 10px 0;
                height: 20px;
                position: relative;
                background: linear-gradient(to right, 
                    rgba(0,0,0,0) 0%, 
                    rgba(0,0,0,1) 100%
                );
                border-radius: 3px;
            }
            .wp-picker-container .wp-picker-holder .wp-picker-alpha-slider .ui-slider-handle {
                position: absolute;
                top: -3px;
                bottom: -3px;
                width: 6px;
                background: #fff;
                border: 1px solid #aaa;
                border-radius: 3px;
                opacity: 0.8;
                cursor: ew-resize;
            }
            .wp-picker-container .wp-picker-holder .wp-picker-alpha-slider .ui-slider-handle:focus {
                border-color: #5b9dd9;
                box-shadow: 0 0 3px rgba(0, 115, 170, 0.8);
            }';
            
            wp_add_inline_style('wp-color-picker', $css);
        }

        public function render_content() {
            ?>
            <label>
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif;
                if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
                <input 
                    type="text" 
                    class="alpha-color-control" 
                    value="<?php echo esc_attr($this->value()); ?>" 
                    data-default-color="<?php echo esc_attr($this->default); ?>"
                    data-palette="<?php echo $this->palette; ?>"
                    <?php $this->link(); ?>
                />
            </label>
            <?php
        }
    }
}

if ( ! class_exists( 'WP_Customize_Image_Control' ) ) {
    class WP_Customize_Image_Control {
        // Placeholder for WP_Customize_Image_Control
    }
}

if ( ! function_exists( 'esc_url' ) ) {
    function esc_url( $url ) {
        return filter_var( $url, FILTER_SANITIZE_URL );
    }
}

// ============================================================================
// FUNÇÃO PRINCIPAL DO CUSTOMIZER
// ============================================================================

/**
 * Registra todas as configurações, seções e controles do Customizer
 * 
 * Esta é a função principal que configura todo o sistema de personalização
 * do tema CCT. Ela é executada quando o WordPress inicializa o Customizer
 * e é responsável por:
 * 
 * PAINÉIS CRIADOS:
 * - Cores do Tema: Agrupa todas as configurações de cores
 * 
 * SEÇÕES PRINCIPAIS:
 * - Menu de Navegação: Estilo, ícones e comportamento do menu
 * - Painel de Atalhos: Configurações do painel lateral
 * - Cores Personalizadas: Sistema completo de cores
 * - Cabeçalho: Layout e configurações do header
 * - Rodapé: Colunas e configurações do footer
 * - Formulários: Estilização de campos de entrada
 * - Reset: Sistema de redefinição para padrões
 * - Backup/Restore: Exportar e importar configurações
 * 
 * FUNCIONALIDADES AVANÇADAS:
 * - Preview em tempo real (postMessage)
 * - Sanitização robusta de dados
 * - Valores padrão centralizados via constantes
 * - Sistema de backup/restore completo
 * - Reset seguro para configurações padrão
 * 
 * @since 1.0.0
 * @param WP_Customize_Manager $wp_customize Instância do gerenciador do customizer
 * 
 * @see add_action('customize_register', 'cct_customize_register')
 * @see https://developer.wordpress.org/themes/customize-api/customizer-objects/
 */
function cct_customize_register( $wp_customize ) {
    // Painel de Cores - Comentado temporariamente para teste
    // $wp_customize->add_panel('cct_colors_panel', array(
    //     'title' => __('Cores do Tema', 'cct'),
    //     'priority' => 30,
    // ));
    
    // ====================================
    // Seção: Menu de Navegação
    // ====================================
    $wp_customize->add_section('cct_menu_settings', array(
        'title' => __('Menu de Navegação', 'cct'),
        'priority' => 35,
        'description' => __('Configure a aparência do menu de navegação.', 'cct'),
    ));
    
    // Estilo do Menu
    $wp_customize->add_setting('menu_style', array(
        'default' => CCT_DEFAULT_MENU_STYLE,
        'sanitize_callback' => 'cct_sanitize_select',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('menu_style', array(
        'label' => __('Estilo do Menu', 'cct'),
        'section' => 'cct_menu_settings',
        'type' => 'select',
        'choices' => array(
            'modern' => __('Moderno (com gradientes)', 'cct'),
            'classic' => __('Clássico (cores sólidas)', 'cct'),
            'minimal' => __('Minimalista', 'cct'),
        ),
        'description' => __('Escolha o estilo visual do menu.', 'cct'),
    ));
    
    // Mostrar ícones de hierarquia
    $wp_customize->add_setting('menu_show_hierarchy_icons', array(
        'default' => CCT_DEFAULT_MENU_HIERARCHY_ICONS,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('menu_show_hierarchy_icons', array(
        'label' => __('Mostrar Ícones de Hierarquia', 'cct'),
        'section' => 'cct_menu_settings',
        'type' => 'checkbox',
        'description' => __('Exibe setas e símbolos para indicar a hierarquia dos submenus.', 'cct'),
    ));
    
    // ====================================
    // Painel: Painel de Atalhos
    // ====================================
    $wp_customize->add_panel('cct_shortcut_panel', array(
        'title' => __('Painel de Atalhos', 'cct'),
        'priority' => 40,
        'capability' => 'edit_theme_options',
    ));
    
    // ====================================
    // Seção: Botão Abrir
    // ====================================
    $wp_customize->add_section('cct_shortcut_button_open', array(
        'title' => __('Botão Abrir', 'cct'),
        'panel' => 'cct_shortcut_panel',
        'priority' => 10,
    ));
    
    // Cor de Fundo do Botão
    $wp_customize->add_setting('shortcut_button_bg', array(
        'default' => CCT_PRIMARY_COLOR,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_button_bg', array(
        'label' => __('Cor de Fundo do Botão', 'cct'),
        'section' => 'cct_shortcut_button_open',
        'settings' => 'shortcut_button_bg',
    )));
    
    // Cor do Ícone do Botão
    $wp_customize->add_setting('shortcut_button_icon_color', array(
        'default' => defined('CCT_WHITE') ? CCT_WHITE : '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_button_icon_color', array(
        'label' => __('Cor do Ícone do Botão', 'cct'),
        'section' => 'cct_shortcut_button_open',
        'settings' => 'shortcut_button_icon_color',
    )));
    
    // ====================================
    // Seção: Painel
    // ====================================
    $wp_customize->add_section('cct_shortcut_panel_settings', array(
        'title' => __('Painel', 'cct'),
        'panel' => 'cct_shortcut_panel',
        'priority' => 20,
    ));
    
    // Cor de Fundo do Painel com suporte a RGBA
    $wp_customize->add_setting('shortcut_panel_bg', array(
        'default' => CCT_PRIMARY_COLOR,
        'sanitize_callback' => 'sanitize_hex_color_rgba',
    ));
    
    $wp_customize->add_control(new Customize_Alpha_Color_Control($wp_customize, 'shortcut_panel_bg', array(
        'label' => __('Cor de Fundo do Painel', 'cct'),
        'section' => 'cct_shortcut_panel_settings',
        'settings' => 'shortcut_panel_bg',
        'description' => __('Use o controle deslizante para ajustar a opacidade', 'cct'),
    )));
    
    // Largura do Painel (aceita px, vw, %)
    $wp_customize->add_setting('shortcut_panel_width', array(
        'default' => CCT_DEFAULT_PANEL_WIDTH,
        'sanitize_callback' => 'cct_sanitize_css_unit',
    ));
    
    $wp_customize->add_control('shortcut_panel_width', array(
        'label' => __('Largura do Painel', 'cct'),
        'description' => __('Use px, % ou vw como unidade de medida (ex: 300px, 80%, 50vw)', 'cct'),
        'section' => 'cct_shortcut_panel_settings',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '300px',
        ),
    ));
    
    // Cor de Fundo do Cabeçalho
    $wp_customize->add_setting('shortcut_header_bg', array(
        'default' => defined('CCT_PRIMARY_COLOR') ? CCT_PRIMARY_COLOR : '#1D3771',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_header_bg', array(
        'label' => __('Cor de Fundo do Cabeçalho', 'cct'),
        'section' => 'cct_shortcut_panel_settings',
        'settings' => 'shortcut_header_bg',
    )));
    
    // Cor do Texto do Cabeçalho
    $wp_customize->add_setting('shortcut_header_text_color', array(
        'default' => defined('CCT_WHITE') ? CCT_WHITE : '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_header_text_color', array(
        'label' => __('Cor do Texto do Cabeçalho', 'cct'),
        'section' => 'cct_shortcut_panel_settings',
        'settings' => 'shortcut_header_text_color',
    )));
    
    // Cor de Fundo do Botão Fechar
    $wp_customize->add_setting('shortcut_close_button_bg', array(
        'default' => CCT_DEFAULT_TRANSPARENT,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_close_button_bg', array(
        'label' => __('Cor de Fundo do Botão Fechar', 'cct'),
        'section' => 'cct_shortcut_panel_settings',
        'settings' => 'shortcut_close_button_bg',
    )));
    
    // Cor do Texto do Botão Fechar
    $wp_customize->add_setting('shortcut_close_button_color', array(
        'default' => defined('CCT_WHITE') ? CCT_WHITE : '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_close_button_color', array(
        'label' => __('Cor do Texto do Botão Fechar', 'cct'),
        'section' => 'cct_shortcut_panel_settings',
        'settings' => 'shortcut_close_button_color',
    )));
    
    // ====================================
    // Seção: Menu
    // ====================================
    $wp_customize->add_section('cct_shortcut_menu', array(
        'title' => __('Menu', 'cct'),
        'panel' => 'cct_shortcut_panel',
        'priority' => 30,
    ));

    // Seção: Configurações Gerais
    $wp_customize->add_section('cct_general_settings', array(
        'title' => __('Configurações Gerais', 'cct'),
        'priority' => 5,
    ));

    // Opção para desativar sidebar do footer
    $wp_customize->add_setting('disable_footer_sidebar', array(
        'default' => false,
        'sanitize_callback' => 'sanitize_checkbox',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('disable_footer_sidebar', array(
        'label' => __('Desativar Sidebar do Footer', 'cct'),
        'section' => 'cct_general_settings',
        'type' => 'checkbox',
        'description' => __('Marque esta opção para desativar a sidebar do rodapé', 'cct'),
    ));

    $wp_customize->add_control('disable_sidebar', array(
        'label' => __('Desativar Sidebar', 'cct'),
        'section' => 'cct_general_settings',
        'type' => 'checkbox',
        'description' => __('Marque esta opção para desativar a sidebar por padrão', 'cct'),
    ));
    
    // Tamanho da Fonte dos Itens de Menu
    $wp_customize->add_setting('shortcut_menu_font_size', array(
        'default' => '16px',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('shortcut_menu_font_size', array(
        'label' => __('Tamanho da Fonte dos Itens de Menu', 'cct'),
        'description' => __('Ex: 16px, 1rem, 1.2em', 'cct'),
        'section' => 'cct_shortcut_menu',
        'type' => 'text',
    ));
    
    // Cor de Fundo dos Itens de Menu
    $wp_customize->add_setting('shortcut_item_bg', array(
        'default' => '#1d3771',
        'sanitize_callback' => function($color) {
            if (empty($color) || $color === 'transparent' || $color === 'default') {
                return 'transparent';
            }
            // Remove o # para permitir cores hexadecimais sem o símbolo
            $color = ltrim($color, '#');
            
            // Se for uma cor hexadecimal válida, retorna com #
            if (preg_match('/^([A-Fa-f0-9]{3}){1,2}$/', $color)) {
                return '#' . $color;
            }
            
            // Se for rgba, retorna como está
            if (strpos($color, 'rgba') === 0) {
                return $color;
            }
            
            // Se não for nenhum dos formatos acima, retorna a cor padrão #1d3771
            return '#1d3771';
        },
    ));
    
    // Cor de Fundo dos Itens de Menu
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_item_bg', array(
        'label' => __('Cor de Fundo dos Itens de Menu', 'cct'),
        'section' => 'cct_shortcut_menu',
        'settings' => 'shortcut_item_bg',
    )));
    
    // Cor de Fundo ao Passar o Mouse
    $wp_customize->add_setting('shortcut_item_hover_bg', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_item_hover_bg', array(
        'label' => __('Cor de Fundo ao Passar o Mouse', 'cct'),
        'section' => 'cct_shortcut_menu',
        'settings' => 'shortcut_item_hover_bg',
    )));
    
    // Cor da Borda ao Passar o Mouse
    $wp_customize->add_setting('shortcut_item_hover_border_color', array(
        'default' => 'rgba(255, 255, 255, 0.1)',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_item_hover_border_color', array(
        'label' => __('Cor da Borda ao Passar o Mouse', 'cct'),
        'section' => 'cct_shortcut_menu',
        'settings' => 'shortcut_item_hover_border_color',
        'description' => __('Define a cor da borda que aparece ao passar o mouse sobre os itens do menu', 'cct'),
    )));
    
    // Cor do Texto ao Passar o Mouse
    $wp_customize->add_setting('shortcut_item_hover_text_color', array(
        'default' => '#1d3771',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'shortcut_item_hover_text_color', array(
        'label' => __('Cor do Texto ao Passar o Mouse', 'cct'),
        'section' => 'cct_shortcut_menu',
        'settings' => 'shortcut_item_hover_text_color',
        'description' => __('Define a cor do texto que aparece ao passar o mouse sobre os itens do menu', 'cct'),
    )));

    // Seção de Cores Principais
    $wp_customize->add_section('cct_main_colors', array(
        'title' => __('Cores Principais', 'cct'),
        'panel' => 'cct_colors_panel',
        'priority' => 10,
    ));

    // Seção duplicada removida - mantendo apenas a primeira definição

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', array(
        'label' => __('Cor do Texto', 'cct'),
        'section' => 'cct_text_colors',
        'settings' => 'text_color',
    )));

    // Cor dos Links
    $wp_customize->add_setting('link_color', array(
        'default' => CCT_LINK_COLOR,
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_color', array(
        'label' => __('Cor dos Links', 'cct'),
        'section' => 'cct_text_colors',
        'settings' => 'link_color',
    )));

// Cor dos Links (Hover)
$wp_customize->add_setting('link_hover_color', array(
    'default' => CCT_LINK_HOVER_COLOR,
    'sanitize_callback' => 'sanitize_hex_color',
));

$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_hover_color', array(
    'label' => __('Cor dos Links (Hover)', 'cct'),
    'section' => 'cct_text_colors',
    'settings' => 'link_hover_color',
)));

    // Seção de Cores de Texto
    $wp_customize->add_section('cct_text_colors', array(
        'title' => __('Cores de Texto', 'cct'),
        'panel' => 'cct_colors_panel',
        'priority' => 20,
    ));

    // Cor do Texto
    $wp_customize->add_setting('text_color', array(
        'default' => CCT_TEXT_COLOR,
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', array(
        'label' => __('Cor do Texto', 'cct'),
        'section' => 'cct_text_colors',
        'settings' => 'text_color',
    )));

    // Removida a seção de cores de botões conforme solicitado

    // Seção de Formulários (nível superior, mesmo nível de Cores do Tema)
    $wp_customize->add_panel('cct_forms_panel', array(
        'title' => __('Formulários', 'cct'),
        'priority' => 35, // Posicionado após Cores do Tema
    ));

    // Subseção para Estilos de Campos
    $wp_customize->add_section('cct_form_fields', array(
        'title' => __('Campos de Formulário', 'cct'),
        'panel' => 'cct_forms_panel',
        'priority' => 10,
    ));

    // Subseção para Botões
    $wp_customize->add_section('cct_form_buttons', array(
        'title' => __('Botões de Formulário', 'cct'),
        'panel' => 'cct_forms_panel',
        'priority' => 20,
    ));

    // ====================================
    // Campos de Formulário
    // ====================================
    
    // Cor do Texto dos Campos
    $wp_customize->add_setting('form_input_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_text_color', array(
        'label' => __('Cor do Texto dos Campos', 'cct'),
        'section' => 'cct_form_fields',
        'settings' => 'form_input_text_color',
    )));

    // Cor de Fundo dos Campos
    $wp_customize->add_setting('form_input_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_bg_color', array(
        'label' => __('Cor de Fundo dos Campos', 'cct'),
        'section' => 'cct_form_fields',
        'settings' => 'form_input_bg_color',
    )));

    // Cor da Borda dos Campos
    $wp_customize->add_setting('form_input_border_color', array(
        'default' => '#cccccc',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_border_color', array(
        'label' => __('Cor da Borda dos Campos', 'cct'),
        'section' => 'cct_form_fields',
        'settings' => 'form_input_border_color',
    )));

    // Cor da Borda ao Passar o Mouse (Hover)
    $wp_customize->add_setting('form_input_border_hover_color', array(
        'default' => '#999999',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_border_hover_color', array(
        'label' => __('Cor da Borda ao Passar o Mouse', 'cct'),
        'section' => 'cct_form_fields',
        'settings' => 'form_input_border_hover_color',
    )));

    // Cor de Fundo ao Passar o Mouse (Hover)
    $wp_customize->add_setting('form_input_bg_hover_color', array(
        'default' => '#f9f9f9',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_bg_hover_color', array(
        'label' => __('Cor de Fundo ao Passar o Mouse', 'cct'),
        'section' => 'cct_form_fields',
        'settings' => 'form_input_bg_hover_color',
    )));

    // ====================================
    // Botões de Formulário
    // ====================================
    
    // Cor de Fundo do Botão
    $wp_customize->add_setting('form_button_bg_color', array(
        'default' => CCT_PRIMARY_COLOR,
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_button_bg_color', array(
        'label' => __('Cor de Fundo do Botão', 'cct'),
        'section' => 'cct_form_buttons',
        'settings' => 'form_button_bg_color',
        'description' => __('Esta cor também será aplicada aos botões do WordPress no editor.', 'cct'),
    )));

    // Cor de Fundo do Botão ao Passar o Mouse (Hover)
    $wp_customize->add_setting('form_button_bg_hover_color', array(
        'default' => defined('CCT_PRIMARY_COLOR') ? CCT_PRIMARY_COLOR : '#1D3771',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_button_bg_hover_color', array(
        'label' => __('Cor de Fundo ao Passar o Mouse', 'cct'),
        'section' => 'cct_form_buttons',
        'settings' => 'form_button_bg_hover_color',
    )));

    // Cor do Texto do Botão
    $wp_customize->add_setting('form_button_text_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_button_text_color', array(
        'label' => __('Cor do Texto do Botão', 'cct'),
        'section' => 'cct_form_buttons',
        'settings' => 'form_button_text_color',
        'description' => __('Esta cor também será aplicada ao texto dos botões do WordPress no editor.', 'cct'),
    )));

    // Cor do Texto do Botão ao Passar o Mouse (Hover)
    $wp_customize->add_setting('form_button_text_hover_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_button_text_hover_color', array(
        'label' => __('Cor do Texto ao Passar o Mouse', 'cct'),
        'section' => 'cct_form_buttons',
        'settings' => 'form_button_text_hover_color',
    )));

    // Raio da Borda do Botão (aceita px, %, em, rem)
    $wp_customize->add_setting('form_button_border_radius', array(
        'default' => '12px',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('form_button_border_radius', array(
        'label' => __('Raio da Borda do Botão (ex: 4px, 5%, 0.5em)', 'cct'),
        'description' => __('Use valores como 4px, 5%, 0.5em, etc.', 'cct'),
        'section' => 'cct_form_buttons',
        'type' => 'text',
    ));

    // Espaçamento Interno do Botão (top, right, bottom, left)
    $wp_customize->add_setting('form_button_padding', array(
        'default' => '15px 15px 15px 15px',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('form_button_padding', array(
        'label' => __('Espaçamento Interno (top right bottom left)', 'cct'),
        'description' => __('Ex: 10px 20px 10px 20px (top, right, bottom, left)', 'cct'),
        'section' => 'cct_form_buttons',
        'type' => 'text',
    ));
    
    // Borda do Botão
    $wp_customize->add_setting('form_button_border_width', array(
        'default' => '0',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('form_button_border_width', array(
        'label' => __('Largura da Borda (ex: 1px, 2px, etc.)', 'cct'),
        'section' => 'cct_form_buttons',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('form_button_border_color', array(
        'default' => defined('CCT_PRIMARY_COLOR') ? CCT_PRIMARY_COLOR : '#1D3771',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_button_border_color', array(
        'label' => __('Cor da Borda', 'cct'),
        'section' => 'cct_form_buttons',
        'settings' => 'form_button_border_color',
    )));
    
    $wp_customize->add_setting('form_button_border_hover_color', array(
        'default' => defined('CCT_PRIMARY_COLOR') ? CCT_PRIMARY_COLOR : '#1D3771',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_button_border_hover_color', array(
        'label' => __('Cor da Borda ao Passar o Mouse', 'cct'),
        'section' => 'cct_form_buttons',
        'settings' => 'form_button_border_hover_color',
    )));

    // Seção de Cores do Menu
    $wp_customize->add_section('cct_menu_colors', array(
        'title' => __('Cores do Menu', 'cct'),
        'panel' => 'cct_colors_panel',
        'priority' => 40,
    ));

    $menu_states = array(
        'link' => __('Link Padrão', 'cct'),
        'active' => __('Link Ativo', 'cct'),
        'hover' => __('Link Hover', 'cct'),
        'selected' => __('Link Selecionado', 'cct'),
    );

    foreach ($menu_states as $state => $label) {
        $wp_customize->add_setting("menu_{$state}_color", array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
     
     // === SEÇÃO DE BACKUP/RESTORE ===
     $wp_customize->add_section('cct_backup_section', array(
         'title' => __('Backup e Restauração', 'cct'),
         'priority' => 998,
         'description' => __('Faça backup das suas configurações ou restaure configurações salvas anteriormente.', 'cct'),
     ));
     
     // Exportar configurações
     $wp_customize->add_setting('cct_export_settings', array(
         'default' => '',
         'sanitize_callback' => 'sanitize_text_field',
     ));
     
     $wp_customize->add_control('cct_export_settings', array(
         'label' => __('Exportar Configurações', 'cct'),
         'section' => 'cct_backup_section',
         'type' => 'button',
         'description' => __('Baixe um arquivo com todas as suas configurações atuais.', 'cct'),
         'input_attrs' => array(
             'value' => __('Exportar Agora', 'cct'),
             'class' => 'button button-primary cct-export-button',
             'onclick' => 'cctExportSettings()',
         ),
     ));
     
     // Importar configurações
     $wp_customize->add_setting('cct_import_settings', array(
         'default' => '',
         'sanitize_callback' => 'sanitize_text_field',
     ));
     
     $wp_customize->add_control('cct_import_settings', array(
         'label' => __('Importar Configurações', 'cct'),
         'section' => 'cct_backup_section',
         'type' => 'textarea',
         'description' => __('Cole aqui o conteúdo do arquivo de backup e clique em "Restaurar" para aplicar as configurações.', 'cct'),
         'input_attrs' => array(
             'placeholder' => __('Cole o conteúdo do backup aqui...', 'cct'),
             'rows' => 5,
         ),
     ));
     
     // Botão de restaurar
     $wp_customize->add_setting('cct_restore_settings', array(
         'default' => '',
         'sanitize_callback' => 'sanitize_text_field',
     ));
     
     $wp_customize->add_control('cct_restore_settings', array(
         'label' => __('Restaurar Configurações', 'cct'),
         'section' => 'cct_backup_section',
         'type' => 'button',
         'description' => __('⚠️ ATENÇÃO: Isto irá sobrescrever todas as configurações atuais.', 'cct'),
         'input_attrs' => array(
             'value' => __('Restaurar Agora', 'cct'),
             'class' => 'button button-secondary cct-restore-button',
             'onclick' => 'cctRestoreSettings()',
         ),
     ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "menu_{$state}_color", array(
            'label' => sprintf(__('Cor do Menu (%s)', 'cct'), $label),
            'section' => 'cct_menu_colors',
            'settings' => "menu_{$state}_color",
        )));
    }




/**
 * Adiciona uma seção de Tipografia com controle duplo para tamanho de fonte
 */
function theme_typography_customizer_settings($wp_customize) {
    // Adiciona seção de tipografia
    $wp_customize->add_section('typography_section', array(
        'title'    => __('Tipografia', 'cct'),
        'priority' => 30,
    ));
    
    // Adiciona configuração para seleção de fonte principal
    $wp_customize->add_setting('typography_primary_font', array(
        'default'           => CCT_PRIMARY_FONT,
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_primary_font', array(
        'label'    => __('Fonte Principal', 'cct'),
        'section'  => 'typography_section',
        'type'     => 'text',
        'description' => __('Exemplo: "Ubuntu", sans-serif', 'cct'),
    ));
    
    // Adiciona configuração para tamanho de fonte base
    $wp_customize->add_setting('typography_font_size_base', array(
        'default'           => CCT_FONT_SIZE_BASE,
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_font_size_base', array(
        'label'    => __('Tamanho da Fonte Base', 'cct'),
        'section'  => 'typography_section',
        'type'     => 'text',
        'description' => __('Exemplo: 1rem ou 16px', 'cct'),
    ));
    
    // Adiciona configuração para tamanho de fonte grande
    $wp_customize->add_setting('typography_font_size_lg', array(
        'default'           => CCT_FONT_SIZE_LG,
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_font_size_lg', array(
        'label'    => __('Tamanho de Fonte Grande', 'cct'),
        'section'  => 'typography_section',
        'type'     => 'text',
        'description' => __('Exemplo: 1.25rem ou 20px', 'cct'),
    ));
    
    // Adiciona configuração para tamanho de fonte extra grande
    $wp_customize->add_setting('typography_font_size_xl', array(
        'default'           => CCT_FONT_SIZE_XL,
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_font_size_xl', array(
        'label'    => __('Tamanho de Fonte Extra Grande', 'cct'),
        'section'  => 'typography_section',
        'type'     => 'text',
        'description' => __('Exemplo: 1.5rem ou 24px', 'cct'),
    ));
    
    // Adiciona configuração para tamanho de fonte extra extra grande
    $wp_customize->add_setting('typography_font_size_xxl', array(
        'default'           => CCT_FONT_SIZE_XXL,
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_font_size_xxl', array(
        'label'    => __('Tamanho de Fonte Extra Extra Grande', 'cct'),
        'section'  => 'typography_section',
        'type'     => 'text',
        'description' => __('Exemplo: 2rem ou 32px', 'cct'),
    ));
    
    // Adiciona campo de visualização da fonte
    $wp_customize->add_setting('typography_preview', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'typography_preview', array(
        'label'       => __('Visualização', 'seu-tema'),
        'description' => __('Esta é uma amostra do texto com as configurações atuais.', 'seu-tema'),
        'section'     => 'typography_section',
        'type'        => 'hidden',
    )));

    // Adiciona configuração para tamanho da fonte do corpo (controle deslizante)
    $wp_customize->add_setting('typography_body_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_body_size', array(
        'label'       => __('Tamanho da Fonte do Corpo', 'seu-tema'),
        'section'     => 'typography_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ));
    
    // Adiciona campo para entrada direta do tamanho da fonte
    $wp_customize->add_setting('typography_body_size_input', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_body_size_input', array(
        'label'       => __('Ou digite o tamanho (px)', 'seu-tema'),
        'section'     => 'typography_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 8,
            'max'  => 72,
            'step' => 1,
        ),
    ));
    
    // Adiciona configuração para peso da fonte do corpo
    $wp_customize->add_setting('typography_body_weight', array(
        'default'           => '400',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_body_weight', array(
        'label'       => __('Peso da Fonte do Corpo', 'seu-tema'),
        'section'     => 'typography_section',
        'type'        => 'select',
        'choices'     => array(
            '300' => __('Leve (300)', 'seu-tema'),
            '400' => __('Normal (400)', 'seu-tema'),
            '500' => __('Médio (500)', 'seu-tema'),
            '600' => __('Semi-Negrito (600)', 'seu-tema'),
            '700' => __('Negrito (700)', 'seu-tema'),
        ),
    ));
    
    // Adiciona configuração para altura da linha
    $wp_customize->add_setting('typography_line_height', array(
        'default'           => '1.6',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_line_height', array(
        'label'       => __('Altura da Linha', 'seu-tema'),
        'section'     => 'typography_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));
    
    // Adiciona configuração para espaçamento entre letras
    $wp_customize->add_setting('typography_letter_spacing', array(
        'default'           => '0',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_letter_spacing', array(
        'label'       => __('Espaçamento entre Letras (px)', 'seu-tema'),
        'section'     => 'typography_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => -2,
            'max'  => 5,
            'step' => 0.5,
        ),
    ));
    
    // Adiciona configuração para cor do texto
    $wp_customize->add_setting('typography_text_color', array(
        'default'           => '#333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'typography_text_color', array(
        'label'    => __('Cor do Texto', 'cct'),
        'section'  => 'typography_section',
        'settings' => 'typography_text_color',
    )));
}
add_action('customize_register', 'theme_typography_customizer_settings',20);

/**
 * Adiciona HTML personalizado ao final da seção tipografia e sincroniza os controles
 */
function theme_typography_preview_html() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Adiciona um elemento de visualização após os controles
        $('#customize-control-typography_preview').append(
            '<div id="typography-live-preview" style="margin-top: 15px; padding: 15px; background-color: #fff; border: 1px solid #ddd; border-radius: 3px;">' +
            '<p class="preview-text" style="margin: 0;">Este é um exemplo do texto. Veja como ele aparece com as configurações atuais.</p>' +
            '<p class="preview-text" style="margin: 10px 0 0;">Aqui está outro parágrafo com mais algumas palavras para você visualizar.</p>' +
            '</div>'
        );
        
        // Atualiza a visualização quando carregada
        updatePreviewText();
        
        function updatePreviewText() {
            // Verifica se o objeto wp.customize está disponível
            if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
                return;
            }
            
            // Valores padrão
            var defaults = {
                'typography_font_family': 'system-ui',
                'typography_body_size': '16',
                'typography_body_weight': '400',
                'typography_line_height': '1.6',
                'typography_letter_spacing': '0',
                'typography_text_color': '#333'
            };
            
            // Obtém os valores, usando valores padrão se não existirem
            var fontFamily = wp.customize('typography_font_family') ? wp.customize('typography_font_family').get() : defaults.typography_font_family;
            var fontSize = wp.customize('typography_body_size') ? wp.customize('typography_body_size').get() : defaults.typography_body_size;
            var fontWeight = wp.customize('typography_body_weight') ? wp.customize('typography_body_weight').get() : defaults.typography_body_weight;
            var lineHeight = wp.customize('typography_line_height') ? wp.customize('typography_line_height').get() : defaults.typography_line_height;
            var letterSpacing = wp.customize('typography_letter_spacing') ? wp.customize('typography_letter_spacing').get() : defaults.typography_letter_spacing;
            var textColor = wp.customize('typography_text_color') ? wp.customize('typography_text_color').get() : defaults.typography_text_color;
            
            // Aplica os estilos apenas se o elemento existir
            var $previewText = $('#typography-live-preview .preview-text');
            if ($previewText.length) {
                $previewText.css({
                    'font-family': fontFamily + ', sans-serif',
                    'font-size': fontSize + 'px',
                    'font-weight': fontWeight,
                    'line-height': lineHeight,
                    'letter-spacing': letterSpacing + 'px',
                    'color': textColor
                });
            }
        }
        
        // Função para configurar os bindings de forma segura
        function setupTypographyBindings() {
            // Sincroniza o valor do controle deslizante com o campo de entrada numérica
            if (wp.customize.control('typography_body_size')) {
                wp.customize('typography_body_size', function(value) {
                    if (value) {
                        value.bind(function(newval) {
                            // Quando o controle deslizante muda, atualiza o campo de entrada
                            if (wp.customize('typography_body_size_input')) {
                                wp.customize('typography_body_size_input').set(newval);
                            }
                            updatePreviewText();
                        });
                    }
                });
            }
            
            // Sincroniza o valor do campo de entrada numérica com o controle deslizante
            if (wp.customize.control('typography_body_size_input')) {
                wp.customize('typography_body_size_input', function(value) {
                    if (value) {
                        value.bind(function(newval) {
                            // Quando o campo de entrada muda, atualiza o controle deslizante
                            if (wp.customize('typography_body_size')) {
                                wp.customize('typography_body_size').set(newval);
                            }
                            updatePreviewText();
                        });
                    }
                });
            }
            
            // Monitorar mudanças nas outras configurações
            var settings = [
                'typography_font_family',
                'typography_body_weight',
                'typography_line_height',
                'typography_letter_spacing',
                'typography_text_color'
            ];
            
            settings.forEach(function(setting) {
                if (wp.customize.control(setting)) {
                    wp.customize(setting, function(value) {
                        if (value) {
                            value.bind(function() { 
                                updatePreviewText(); 
                            });
                        }
                    });
                }
            });
        }
        
        // Configura os bindings quando o Customizer estiver pronto
        if (typeof wp.customize !== 'undefined') {
            setupTypographyBindings();
        }
    });
    </script>
    <?php
}
add_action('customize_controls_print_footer_scripts', 'theme_typography_preview_html');

/**
 * Adiciona CSS personalizado com base nas escolhas de tipografia
 */
function theme_typography_customizer_css() {
    ?>
    <style type="text/css">
        body, button, input, select, textarea {
            font-family: <?php echo esc_attr(get_theme_mod('typography_font_family', 'system-ui')); ?>, sans-serif;
            font-size: <?php echo esc_attr(get_theme_mod('typography_body_size', '16')); ?>px;
            font-weight: <?php echo esc_attr(get_theme_mod('typography_body_weight', '400')); ?>;
            line-height: <?php echo esc_attr(get_theme_mod('typography_line_height', '1.6')); ?>;
            letter-spacing: <?php echo esc_attr(get_theme_mod('typography_letter_spacing', '0')); ?>px;
            color: <?php echo esc_attr(get_theme_mod('typography_text_color', '#333')); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'theme_typography_customizer_css');




    // Header Options
    $wp_customize->add_section( 'cct_header', array(
        'title'    => __( 'Header Options', 'cct-theme' ),
        'priority' => 20,
    ) );

    $wp_customize->add_setting( 'header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'header_layout', array(
        'label'    => __( 'Header Layout', 'cct-theme' ),
        'section'  => 'cct_header',
        'type'     => 'radio',
        'choices'  => array(
            'default' => __( 'Default', 'cct-theme' ),
            'centered' => __( 'Centered', 'cct-theme' ),
            'minimal' => __( 'Minimal', 'cct-theme' ),
        ),
    ) );

    // Header Background Color
    $wp_customize->add_setting( 'header_background_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_background_color', array(
        'label'    => __( 'Header Background Color', 'cct-theme' ),
        'section'  => 'cct_header',
    ) ) );

    // Header Background Image
    $wp_customize->add_setting( 'header_background_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'header_background_image', array(
        'label'    => __( 'Header Background Image', 'cct-theme' ),
        'section'  => 'cct_header',
    ) ) );

    // Header Padding
    $wp_customize->add_setting( 'header_padding', array(
        'default'           => '20px 20px 20px 20px',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'header_padding', array(
        'label'    => __( 'Header Padding', 'cct-theme' ),
        'section'  => 'cct_header',
        'type'     => 'text',
    ) );

    // Header Margin
    $wp_customize->add_setting( 'header_margin', array(
        'default'           => '0 0 0 0',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'header_margin', array(
        'label'    => __( 'Header Margin', 'cct-theme' ),
        'section'  => 'cct_header',
        'type'     => 'text',
    ) );

    // Header Height
    $wp_customize->add_setting( 'header_height', array(
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'header_height', array(
        'label'    => __( 'Header Height', 'cct-theme' ),
        'section'  => 'cct_header',
        'type'     => 'text',
    ) );

    // Footer Options
    $wp_customize->add_section( 'cct_footer', array(
        'title'    => __( 'Footer Options', 'cct-theme' ),
        'priority' => 90,
    ) );

    $wp_customize->add_setting( 'footer_columns', array(
        'default'           => '4',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'footer_columns', array(
        'label'    => __( 'Footer Widget Columns', 'cct-theme' ),
        'section'  => 'cct_footer',
        'type'     => 'select',
        'choices'  => array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
        ),
    ) );

    // Performance Options
    $wp_customize->add_section( 'cct_performance', array(
        'title'    => __( 'Performance Options', 'cct-theme' ),
        'priority' => 100,
    ) );

    $wp_customize->add_setting( 'enable_lazy_loading', array(
        'default'           => true,
        'sanitize_callback' => 'cct_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'enable_lazy_loading', array(
        'label'    => __( 'Enable Lazy Loading', 'cct-theme' ),
        'section'  => 'cct_performance',
        'type'     => 'checkbox',
    ) );

    // SEO Options
    $wp_customize->add_section( 'cct_seo', array(
        'title'    => __( 'SEO Options', 'cct-theme' ),
        'priority' => 110,
    ) );

    $wp_customize->add_setting( 'meta_description', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'meta_description', array(
        'label'    => __( 'Default Meta Description', 'cct-theme' ),
        'section'  => 'cct_seo',
        'type'     => 'textarea',
    ) );

    // Social Media Options
    $wp_customize->add_section( 'cct_social_media', array(
        'title'    => __( 'Social Media Options', 'cct-theme' ),
        'priority' => 25,
    ) );

    // Social Media Links and Icons
    $social_networks = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'telegram', 'whatsapp' );
    foreach ( $social_networks as $network ) {
        // Link Setting
        $wp_customize->add_setting( "{$network}_link", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );

        $wp_customize->add_control( "{$network}_link", array(
            'label'    => sprintf( __( '%s Link', 'cct-theme' ), ucfirst( $network ) ),
            'section'  => 'cct_social_media',
            'type'     => 'url',
        ) );

        // Icon Setting
        $wp_customize->add_setting( "{$network}_icon", array(
            'default'           => "fab fa-{$network}", // Define um ícone padrão
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( "{$network}_icon", array(
            'label'    => sprintf( __( '%s Icon', 'cct-theme' ), ucfirst( $network ) ),
            'section'  => 'cct_social_media',
            'type'     => 'select',
            'choices'  => array( // Adicione as opções de ícones aqui
                'fab fa-facebook-f' => 'Facebook',
                'fab fa-twitter' => 'Twitter',
                'fab fa-instagram' => 'Instagram',
                'fab fa-linkedin-in' => 'LinkedIn',
                'fab fa-youtube' => 'YouTube',
                'fab fa-telegram-plane' => 'Telegram',
                'fab fa-whatsapp' => 'WhatsApp'
                // Adicione mais ícones se necessário
            ),
        ) );
    }

    // Social Media Alignment
    $wp_customize->add_setting( 'social_media_alignment', array(
        'default'           => 'right',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'social_media_alignment', array(
        'label'    => __( 'Social Media Alignment', 'cct-theme' ),
        'section'  => 'cct_social_media',
        'type'     => 'radio',
        'choices'  => array(
            'left'   => __( 'Left', 'cct-theme' ),
            'center' => __( 'Center', 'cct-theme' ),
            'right'  => __( 'Right', 'cct-theme' ),
        ),
    ));

    // Icon Size
    $wp_customize->add_setting( 'social_media_icon_size', array(
        'default'           => '36',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'social_media_icon_size', array(
        'label'       => __( 'Icon Size (px)', 'cct-theme' ),
        'section'     => 'cct_social_media',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 80,
            'step' => 2,
        ),
    ));

    // Icon Color
    $wp_customize->add_setting( 'social_media_icon_color', array(
        'default'           => 'rgba(255, 255, 255, 0.6)',
        'sanitize_callback' => 'cct_sanitize_color_with_alpha',
    ) );

    $wp_customize->add_control( 'social_media_icon_color', array(
        'label'       => __( 'Icon Color', 'cct-theme' ),
        'section'     => 'cct_social_media',
        'type'        => 'text',
        'description' => __( 'Use formato: rgba(255, 255, 255, 0.6) ou #ffffff', 'cct-theme' ),
        'input_attrs' => array(
            'placeholder' => 'rgba(255, 255, 255, 0.6)',
        ),
    ) );

    // Background Color
    $wp_customize->add_setting( 'social_media_bg_color', array(
        'default'           => '#1d3771',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'social_media_bg_color', array(
        'label'    => __( 'Background Color', 'cct-theme' ),
        'section'  => 'cct_social_media',
    ) ) );

    // Border Width
    $wp_customize->add_setting( 'social_media_border_width', array(
        'default'           => '0',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'social_media_border_width', array(
        'label'       => __( 'Border Width (px)', 'cct-theme' ),
        'section'     => 'cct_social_media',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 10,
            'step' => 1,
        ),
    ));

    // Border Color
    $wp_customize->add_setting( 'social_media_border_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'social_media_border_color', array(
        'label'    => __( 'Border Color', 'cct-theme' ),
        'section'  => 'cct_social_media',
    ) ) );

    // Border Radius
     $wp_customize->add_setting( 'social_media_border_radius', array(
         'default'           => '50',
         'sanitize_callback' => 'absint',
     ) );

     $wp_customize->add_control( 'social_media_border_radius', array(
         'label'       => __( 'Border Radius (%)', 'cct-theme' ),
         'section'     => 'cct_social_media',
         'type'        => 'range',
         'input_attrs' => array(
             'min'  => 0,
             'max'  => 50,
             'step' => 5,
         ),
      ));

     // Icon Gap
     $wp_customize->add_setting( 'social_media_icon_gap', array(
         'default'           => '6',
         'sanitize_callback' => 'absint',
     ) );

     $wp_customize->add_control( 'social_media_icon_gap', array(
         'label'       => __( 'Icon Gap (px)', 'cct-theme' ),
         'section'     => 'cct_social_media',
         'type'        => 'range',
         'input_attrs' => array(
             'min'  => 0,
             'max'  => 20,
             'step' => 1,
         ),
     ));

     // Reset Button for Social Media
     $wp_customize->add_setting( 'social_media_reset', array(
         'default'           => '',
         'sanitize_callback' => 'sanitize_text_field',
         'transport'         => 'postMessage',
     ) );

     $wp_customize->add_control( 'social_media_reset', array(
         'label'       => __( 'Restaurar Padrões', 'cct-theme' ),
         'section'     => 'cct_social_media',
         'type'        => 'button',
         'description' => __( 'Clique para restaurar todas as configurações de redes sociais para os valores padrão.', 'cct-theme' ),
         'input_attrs' => array(
             'value' => __( 'Restaurar Padrões', 'cct-theme' ),
             'class' => 'button button-secondary cct-social-reset-button',
             'data-action' => 'reset-social-media',
         ),
     ));
     
     // === SEÇÃO DE RESET ===
    $wp_customize->add_section('cct_reset_section', array(
        'title' => __('Redefinir Configurações', 'cct'),
        'priority' => 999,
        'description' => __('Restaure todas as configurações do tema para os valores padrão.', 'cct'),
    ));
    
    // Botão de Reset
    $wp_customize->add_setting('cct_reset_settings', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('cct_reset_settings', array(
        'label' => __('Redefinir Todas as Configurações', 'cct'),
        'section' => 'cct_reset_section',
        'type' => 'button',
        'description' => __('ATENÇÃO: Esta ação irá restaurar todas as configurações do customizer para os valores padrão. Esta ação não pode ser desfeita.', 'cct'),
        'input_attrs' => array(
            'value' => __('Redefinir Agora', 'cct'),
            'class' => 'button button-secondary cct-reset-button',
            'data-action' => 'reset-customizer',
        ),
    ));
    
    // Seção de gerenciador de extensões removida - usando apenas o gerenciador principal
}
add_action( 'customize_register', 'cct_customize_register' );

/**
 * Sanitize colors that can be either hex or rgba
 */
function sanitize_hex_color_rgba( $color ) {
    // Verifica se é um valor rgba
    if ( strpos( $color, 'rgba' ) !== false ) {
        // Remove espaços e converte para minúsculas
        $color = strtolower( trim( $color ) );
        
        // Verifica o formato rgba
        if ( preg_match( '/^rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([01]?\.?[0-9]*)\s*\)$/', $color, $matches ) ) {
            // Valida os valores RGB (0-255) e Alpha (0-1)
            $r = intval( $matches[1] );
            $g = intval( $matches[2] );
            $b = intval( $matches[3] );
            $a = floatval( $matches[4] );
            
            if ( $r >= 0 && $r <= 255 && $g >= 0 && $g <= 255 && $b >= 0 && $b <= 255 && $a >= 0 && $a <= 1 ) {
                return "rgba($r, $g, $b, $a)";
            }
        }
        
        // Se não for um rgba válido, retorna o valor padrão
        return CCT_PRIMARY_COLOR;
    }
    
    // Se for um hex, usa a função padrão do WordPress
    return sanitize_hex_color( $color );
}

// Sanitize checkbox
function cct_sanitize_checkbox( $checked ) {
    // Boolean check.
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize select choices
 */
function cct_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize CSS units (px, %, vw, vh, em, rem)
 */
function cct_sanitize_css_unit( $value ) {
    // Remove espaços
    $value = trim( $value );
    
    // Se estiver vazio, retorna valor padrão
    if ( empty( $value ) ) {
        return CCT_DEFAULT_PANEL_WIDTH;
    }
    
    // Verifica se é apenas número (adiciona px)
    if ( is_numeric( $value ) ) {
        return absint( $value ) . 'px';
    }
    
    // Verifica se tem unidade válida
    if ( preg_match( '/^\d+(\.\d+)?(px|%|vw|vh|em|rem)$/i', $value ) ) {
        return $value;
    }
    
    // Se não for válido, retorna padrão
    return CCT_DEFAULT_PANEL_WIDTH;
}

/**
 * Sanitize font size
 */
function cct_sanitize_font_size( $value ) {
    // Lista de tamanhos válidos
    $valid_sizes = array( 'small', 'medium', 'large', 'x-large', 'xx-large' );
    
    // Se for um dos tamanhos pré-definidos
    if ( in_array( $value, $valid_sizes ) ) {
        return $value;
    }
    
    // Se for um valor com unidade CSS
    if ( preg_match( '/^\d+(\.\d+)?(px|em|rem|%)$/i', $value ) ) {
        return $value;
    }
    
    // Valor padrão
    return CCT_FONT_SIZE_BASE;
}

/**
 * Sanitize number range
 */
function cct_sanitize_number_range( $value, $min = 0, $max = 100 ) {
    $value = intval( $value );
    return max( $min, min( $max, $value ) );
}

// Função para converter cores hex para rgba se necessário
function maybe_convert_to_rgba($color) {
    // Se já for rgba, retorna como está
    if (strpos($color, 'rgba') === 0) {
        return $color;
    }
    
    // Se for transparent, retorna transparent
    if ($color === 'transparent') {
        return $color;
    }
    
    // Se for hex, converte para rgba com opacidade 1
    if (strpos($color, '#') === 0) {
        $hex = str_replace('#', '', $color);
        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "rgba($r, $g, $b, 1)";
    }
    
    // Se não for nenhum dos formatos conhecidos, retorna como está
    return $color;
}

// Output Customizer CSS
function cct_customize_css() {
    
    // Obter valores dos temas
    $primary_color = defined('CCT_PRIMARY_COLOR') ? CCT_PRIMARY_COLOR : '#1D3771';
    
    // Usar output buffering seguro
    $css_output = '<style type="text/css" id="cct-custom-css">' . "\n";
    
    // Estilos para campos de formulário
    $css_output .= '.input-form-uenf,
    .textarea-form-uenf,
    .select-form-uenf {
        width: 100%;
        margin-bottom: 15px;
        font-family: inherit;
        font-size: 16px;
        line-height: 1.5;
        transition: all 0.3s ease;
        box-sizing: border-box;
        color: ' . esc_attr(get_theme_mod('form_input_text_color', '#333333')) . ';
        background-color: ' . esc_attr(get_theme_mod('form_input_bg_color', '#ffffff')) . ';
        border: 1px solid ' . esc_attr(get_theme_mod('form_input_border_color', '#cccccc')) . ';
        padding: 8px 12px;
    }' . "\n\n";
    
    // Estilo para hover nos campos
    $css_output .= '.input-form-uenf:hover,
    .textarea-form-uenf:hover,
    .select-form-uenf:hover {
        border-color: ' . esc_attr(get_theme_mod('form_input_border_hover_color', $primary_color)) . ';
        background-color: ' . esc_attr(get_theme_mod('form_input_bg_hover_color', '#f9f9f9')) . ';
    }' . "\n\n";
    
    // Estilo para foco nos campos
    $css_output .= '.input-form-uenf:focus,
    .textarea-form-uenf:focus,
    .select-form-uenf:focus {
        outline: none;
        border-color: ' . esc_attr(get_theme_mod('form_input_border_hover_color', $primary_color)) . ';
        background-color: ' . esc_attr(get_theme_mod('form_input_bg_hover_color', '#f9f9f9')) . ';
        box-shadow: 0 0 0 2px ' . esc_attr(get_theme_mod('form_input_border_hover_color', $primary_color)) . '20;
    }' . "\n\n";
    
    // Estilo para textarea
    $css_output .= '.textarea-form-uenf {
        min-height: 120px;
        resize: vertical;
    }' . "\n\n";
    
    // Estilo para select personalizado
    $css_output .= '.select-form-uenf {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'12\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23' . ltrim(esc_attr(get_theme_mod('form_input_text_color', '#333333')), '#') . '\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpolyline points=\'6 9 12 15 18 9\'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
        padding-right: 35px;
    }' . "\n\n";
    
    // Variáveis CSS para botões de formulário
    $css_output .= ':root {
        --form-button-bg-color: ' . esc_attr(get_theme_mod('form_button_bg_color', $primary_color)) . ';
        --form-button-text-color: ' . esc_attr(get_theme_mod('form_button_text_color', '#ffffff')) . ';
        --form-button-bg-hover-color: ' . esc_attr(get_theme_mod('form_button_bg_hover_color', $primary_color . 'e6')) . ';
        --form-button-text-hover-color: ' . esc_attr(get_theme_mod('form_button_text_hover_color', '#ffffff')) . ';
        --form-button-border-width: ' . esc_attr(get_theme_mod('form_button_border_width', '1px')) . ';
        --form-button-border-color: ' . esc_attr(get_theme_mod('form_button_border_color', $primary_color)) . ';
        --form-button-border-hover-color: ' . esc_attr(get_theme_mod('form_button_border_hover_color', $primary_color . 'e6')) . ';
        --form-button-padding: ' . esc_attr(get_theme_mod('form_button_padding', '8px 15px 8px 15px')) . ';
        --form-button-border-radius: ' . esc_attr(get_theme_mod('form_button_border_radius', '12px')) . ';
    }' . "\n\n";
    
    // Estilos para botões
    $css_output .= '.btn-submit-uenf,
    .btn-form-uenf,
    button[type="submit"].btn-uenf,
    .wpcf7-submit {
        display: inline-block;
        font-weight: 500;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: var(--form-button-border-width) solid var(--form-button-border-color);
        padding: var(--form-button-padding);
        font-size: 16px;
        line-height: 1.5;
        border-radius: var(--form-button-border-radius);
        transition: all 0.3s ease;
        cursor: pointer;
        background-color: var(--form-button-bg-color);
        color: var(--form-button-text-color);
    }' . "\n\n";
    
    // Estilo hover para botões
    $css_output .= '.btn-submit-uenf:hover,
    .btn-form-uenf:hover,
    button[type="submit"].btn-uenf:hover,
    .wpcf7-submit:hover {
        background-color: var(--form-button-bg-hover-color);
        color: var(--form-button-text-hover-color);
        border-color: var(--form-button-border-hover-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }' . "\n\n";
    
    // Estilo ativo para botões
    $css_output .= '.btn-submit-uenf:active,
    .btn-form-uenf:active,
    button[type="submit"].btn-uenf:active,
    .wpcf7-submit:active {
        transform: translateY(0);
        box-shadow: none;
    }' . "\n\n";
    
    // Estilo para foco no botão
    $css_output .= '.btn-submit-uenf:focus,
    .btn-form-uenf:focus,
    button[type="submit"].btn-uenf:focus,
    .wpcf7-submit:focus {
        outline: none;
        box-shadow: 0 0 0 2px var(--form-button-border-hover-color);
    }' . "\n\n";
    
    // Estilo para labels
    $css_output .= '.form-label-uenf {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: ' . esc_attr(get_theme_mod('form_input_text_color', '#333333')) . ';
    }' . "\n\n";
    
    // Estilo para grupos de formulário
    $css_output .= '.form-group-uenf {
        margin-bottom: 20px;
    }' . "\n";
    
    // Adicionar script para forçar atualização do cache
    echo '<script>
    // Forçar atualização do cache do navegador
    if (window.performance && window.performance.navigation && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
        window.location.reload();
    }
    </script>';
    
    // Adicionar variáveis CSS
    $primary_color = defined('CCT_PRIMARY_COLOR') ? CCT_PRIMARY_COLOR : '#1d3771';
    $white_color = defined('CCT_WHITE') ? CCT_WHITE : '#ffffff';
    $shortcut_panel_width = get_theme_mod('shortcut_panel_width', '300px');
    if (is_numeric($shortcut_panel_width)) {
        $shortcut_panel_width .= 'px';
    }
    
    // Adicionar todas as variáveis CSS em um único bloco
    echo '<style type="text/css">
    :root {
        /* Cores principais */
        --primary-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('primary_color', '#1d3771'))) . ';
        --secondary-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('secondary_color', '#4b6397'))) . ';
        --text-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('text_color', '#333333'))) . ';
        
        /* Botões */
        --button-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('button_normal_color', '#1d3771'))) . ';
        --button-hover-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('button_hover_color', '#152a54'))) . ';
        --button-active-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('button_active_color', '#0f1f3d'))) . ';
        
        /* Menu */
        --menu-link-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('menu_link_color', '#ffffff'))) . ';
        --menu-active-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('menu_active_color', '#1d3771'))) . ';
        --menu-hover-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('menu_hover_color', '#2a4a8c'))) . ';
        --menu-selected-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('menu_selected_color', '#1d3771'))) . ';
        
        /* Links */
        --link-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('link_color', '#26557d'))) . ';
        --link-hover-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('link_hover_color', '#26557d'))) . ';
        
        /* Painel de Atalhos */
        --shortcut-button-bg: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_button_bg', $primary_color))) . ';
        --shortcut-button-icon-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_button_icon_color', $white_color))) . ';
        --shortcut-button-size: ' . esc_attr(get_theme_mod('shortcut_button_size', '50')) . 'px;
        --shortcut-panel-bg: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_panel_bg', $primary_color))) . ';
        --shortcut-panel-width: ' . esc_attr($shortcut_panel_width) . ';
        --shortcut-header-bg: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_header_bg', 'rgba(0, 0, 0, 0.1)'))) . ';
        --shortcut-header-text-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_header_text_color', $white_color))) . ';
        --shortcut-item-bg: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_item_bg', 'transparent'))) . ';
        --shortcut-item-text-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_item_text_color', $white_color))) . ';
        --shortcut-item-hover-bg: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_item_hover_bg', '#ffffff'))) . ';
        --shortcut-item-hover-text-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_item_hover_text_color', $primary_color))) . ';
        --shortcut-item-hover-border-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_item_hover_border_color', 'rgba(255, 255, 255, 0.1)'))) . ';
        --shortcut-item-font-size: ' . esc_attr(get_theme_mod('shortcut_item_font_size', '16')) . 'px;
        --shortcut-close-button-bg: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_close_button_bg', $primary_color))) . ';
        --shortcut-close-button-text-color: ' . esc_attr(maybe_convert_to_rgba(get_theme_mod('shortcut_close_button_text_color', $white_color))) . ';
    }
    </style>';

    // Adicionar estilos para o body, links e tipografia
    echo '<style type="text/css">
    /* Estilos para o body */
    body {
        color: var(--text-color);
        font-family: ' . esc_attr(get_theme_mod('body_font_family', 'Times, system-ui, Arial, Ubuntu, "Open Sans", "Helvetica Neue", sans-serif')) . ';
        font-weight: 400;
    }
    
    /* Estilos para links */
    body a, 
    .site-content a, 
    #content a, 
    article a, 
    .entry-content a {
        color: var(--link-color) !important;
        text-decoration: none;
    }
    
    body a:hover, 
    .site-content a:hover {
        color: var(--link-hover-color) !important;
    }
    
    /* Estilos para texto */
    body, 
    p, 
    .site-content, 
    #content, 
    article, 
    .entry-content {
        color: var(--text-color) !important;
    }
    
    /* Estilos para títulos */
    h1, h2, h3 {
        font-weight: 700;
        color: var(--primary-color);
    }
    
    h4, h5, h6 {
        font-weight: 500;
        color: var(--secondary-color);
    }
    
    /* Estilos para botões */
    .button {
        background-color: var(--button-color);
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .button:hover {
        background-color: var(--button-hover-color);
    }

    .button:active {
        background-color: var(--button-active-color);
    }

    /* Estilos para menu */
    .menu-item a {
        color: var(--menu-link-color);
        transition: color 0.3s ease;
    }

    .menu-item.current-menu-item a {
        color: var(--menu-selected-color);
    }

    .menu-item a:hover {
        color: var(--menu-hover-color);
    }

    .menu-item.current-menu-item a {
        color: var(--menu-active-color);
    }

    /* Cores primárias */
    a, .primary-color {
        color: ' . esc_attr(get_theme_mod('primary_color', '#0073aa')) . ';
    }
    
    /* Estilos para o cabeçalho */
    .site-header {';
    
    // Adicionar estilos dinâmicos para o cabeçalho
    $header_bg_color = get_theme_mod('header_background_color', '#ffffff');
    if ($header_bg_color) {
        echo 'background-color: ' . esc_attr($header_bg_color) . ';';
    }
    
    $header_bg_image = get_theme_mod('header_background_image');
    if ($header_bg_image) {
        echo 'background-image: url(\'' . esc_url($header_bg_image) . '\');';
        echo 'background-size: cover;';
        echo 'background-position: center;';
    }
    
    $header_padding = get_theme_mod('header_padding', '20px 20px 20px 20px');
    if ($header_padding) {
        echo 'padding: ' . esc_attr($header_padding) . ';';
    }
    
    $header_margin = get_theme_mod('header_margin', '0 0 0 0');
    if ($header_margin) {
        echo 'margin: ' . esc_attr($header_margin) . ';';
    }
    
    $header_height = get_theme_mod('header_height', 'auto');
    if ($header_height) {
        echo 'height: ' . esc_attr($header_height) . ';';
    }
    
    $header_layout = get_theme_mod('header_layout', 'default');
    if ('centered' === $header_layout) {
        echo 'text-align: center;';
    }
    
    echo '}';
    
    // Estilos para o rodapé
    $footer_columns = get_theme_mod('footer_columns', 4);
    echo '.footer-widgets {';
    echo 'display: grid;';
    echo 'grid-template-columns: repeat(' . esc_attr($footer_columns) . ', 1fr);';
    echo 'gap: 30px;';
    echo '}';
    
    // Estilos para Campos de Formulário
    echo '.input-form-uenf,
    .textarea-form-uenf,
    .select-form-uenf {';
    echo 'width: 100%;';
    echo 'margin-bottom: 15px;';
    echo 'font-family: inherit;';
    echo 'font-size: 16px;';
    echo 'line-height: 1.5;';
    echo 'transition: all 0.3s ease;';
    echo 'box-sizing: border-box;';
    echo 'color: ' . esc_attr(get_theme_mod('form_input_text_color', '#333333')) . ';';
    echo 'background-color: ' . esc_attr(get_theme_mod('form_input_bg_color', '#ffffff')) . ';';
    echo 'border: 1px solid ' . esc_attr(get_theme_mod('form_input_border_color', '#cccccc')) . ';';
    echo 'padding: 8px 12px;';
    echo '}';
    
    // Estilo para foco nos campos
    echo '.input-form-uenf:focus,
    .textarea-form-uenf:focus,
    .select-form-uenf:focus {';
    echo 'outline: none;';
    echo 'border-color: ' . esc_attr(get_theme_mod('form_input_border_hover_color', '#999999')) . ';';
    echo 'background-color: ' . esc_attr(get_theme_mod('form_input_bg_hover_color', '#f9f9f9')) . ';';
    echo 'box-shadow: 0 0 0 2px rgba(29, 55, 113, 0.1);';
    echo '}';
    
    // Estilo para textarea
    echo '.textarea-form-uenf {';
    echo 'min-height: 120px;';
    echo 'resize: vertical;';
    echo '}';
    
    // Estilos dinâmicos para o menu
    $menu_style = get_theme_mod('menu_style', 'modern');
    $show_hierarchy_icons = get_theme_mod('menu_show_hierarchy_icons', defined('CCT_DEFAULT_MENU_HIERARCHY_ICONS') ? CCT_DEFAULT_MENU_HIERARCHY_ICONS : false);
    
    // Aplicar estilos baseados no estilo selecionado
    switch ($menu_style) {
        case 'classic':
            // Estilo clássico - cores sólidas, sem gradientes
            echo 'body .offcanvas .new-menu, body .new-menu, .offcanvas .new-menu, .new-menu, .navbar-nav, .offcanvas .navbar-nav {';
            echo 'background: #1d3771 !important;';
            echo '}';
            echo 'body .offcanvas .new-menu .sub-menu, body .offcanvas .new-menu .children, body .new-menu .sub-menu, body .new-menu .children, .offcanvas .new-menu .sub-menu, .offcanvas .new-menu .children, .new-menu .sub-menu, .new-menu .children, .navbar-nav .dropdown-menu, .offcanvas .navbar-nav .dropdown-menu {';
            echo 'background: #1d3771 !important;';
            echo 'border-left: 3px solid #ffffff !important;';
            echo 'box-shadow: none !important;';
            echo 'backdrop-filter: none !important;';
            echo '}';
            echo 'body .offcanvas .new-menu > li > a, body .new-menu > li > a, .offcanvas .new-menu > li > a, .new-menu > li > a, .navbar-nav > li > a, .offcanvas .navbar-nav > li > a {';
            echo 'background: #1d3771 !important;';
            echo 'border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;';
            echo '}';
            echo 'body .offcanvas .new-menu > li > a:hover, body .new-menu > li > a:hover {';
            echo 'background: #2a4a8a !important;';
            echo '}';
            echo 'body .offcanvas .new-menu .sub-menu a, body .offcanvas .new-menu .children a, body .new-menu .sub-menu a, body .new-menu .children a {';
            echo 'background: transparent !important;';
            echo '}';
            echo 'body .offcanvas .new-menu .sub-menu a:hover, body .offcanvas .new-menu .children a:hover, body .new-menu .sub-menu a:hover, body .new-menu .children a:hover {';
            echo 'background: rgba(255, 255, 255, 0.1) !important;';
            echo '}';
            // Remover todas as animações e transições no estilo clássico
            echo 'body.menu-classic-style .offcanvas .new-menu *, body.menu-classic-style .new-menu *, body .offcanvas .new-menu *, body .new-menu * {';
            echo 'transition: none !important;';
            echo 'animation: none !important;';
            echo 'transform: none !important;';
            echo '-webkit-transition: none !important;';
            echo '-moz-transition: none !important;';
            echo '-o-transition: none !important;';
            echo '}';
            echo 'body.menu-classic-style .offcanvas .new-menu .sub-menu, body.menu-classic-style .offcanvas .new-menu .children, body.menu-classic-style .new-menu .sub-menu, body.menu-classic-style .new-menu .children {';
            echo 'transition: none !important;';
            echo 'animation: none !important;';
            echo '-webkit-transition: none !important;';
            echo '-moz-transition: none !important;';
            echo '-o-transition: none !important;';
            echo '}';
            echo 'body.menu-classic-style .offcanvas .new-menu .submenu-toggle, body.menu-classic-style .new-menu .submenu-toggle {';
            echo 'transition: none !important;';
            echo 'transform: none !important;';
            echo '-webkit-transition: none !important;';
            echo '-moz-transition: none !important;';
            echo '-o-transition: none !important;';
            echo '-webkit-transform: none !important;';
            echo '-moz-transform: none !important;';
            echo '-o-transform: none !important;';
            echo '}';
            break;
        case 'minimal':
            // Estilo minimalista - transparências e bordas sutis
            echo '.new-menu .sub-menu, .new-menu .children {';
            echo 'background: rgba(29, 55, 113, 0.95) !important;';
            echo 'border-left: 1px solid rgba(255, 255, 255, 0.2) !important;';
            echo 'margin-left: 0 !important;';
            echo 'box-shadow: none !important;';
            echo '}';
            echo '.new-menu > li > a {';
            echo 'border-bottom: none !important;';
            echo '}';
            echo '.new-menu > li > a:hover {';
            echo 'background: rgba(255, 255, 255, 0.05) !important;';
            echo '}';
            break;
        default: // modern
            // Usa os estilos padrão do CSS (gradientes, sombras, etc.)
            break;
    }
    
    // Controlar ícones de hierarquia
    if ($show_hierarchy_icons) {
        // Mostrar ícones de hierarquia
        echo '.new-menu .sub-menu a::before,';
        echo '.new-menu .children a::before {';
        echo 'content: "→" !important;';
        echo 'display: block !important;';
        echo '}';
        echo '.new-menu .sub-menu .sub-menu a::before,';
        echo '.new-menu .children .children a::before {';
        echo 'content: "▸" !important;';
        echo '}';
        echo '.new-menu .sub-menu .sub-menu .sub-menu a::before,';
        echo '.new-menu .children .children .children a::before {';
        echo 'content: "•" !important;';
        echo '}';
    } else {
        // Ocultar ícones de hierarquia
        echo '.new-menu .sub-menu a::before,';
        echo '.new-menu .children a::before {';
        echo 'display: none !important;';
        echo '}';
        echo '.new-menu .sub-menu a,';
        echo '.new-menu .children a {';
        echo 'padding-left: 20px !important;';
        echo '}';
    }
    
    // Estilo para select personalizado
    echo '.select-form-uenf {';
    echo '-webkit-appearance: none;';
    echo '-moz-appearance: none;';
    echo 'background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e");';
    echo 'background-repeat: no-repeat;';
    echo 'background-position: right 10px center;';
    echo 'background-size: 1em;';
    echo 'padding-right: 2.5em;';
    echo '}';
    
    // Estilos para Botões de Formulário
    $css_output .= '.btn-submit-uenf,
    .btn-form-uenf,
    button[type="submit"].btn-uenf {';
    $css_output .= 'background-color: ' . esc_attr(get_theme_mod('form_button_bg_color', $primary_color)) . ';';
    $css_output .= 'color: ' . esc_attr(get_theme_mod('form_button_text_color', '#ffffff')) . ';';
    $css_output .= 'border-radius: ' . esc_attr(get_theme_mod('form_button_border_radius', '12px')) . ';';
    $css_output .= 'padding: ' . esc_attr(get_theme_mod('form_button_padding', '8px 15px 8px 15px')) . ';';
    $css_output .= 'border: ' . esc_attr(get_theme_mod('form_button_border_width', '1px')) . ' solid ' . esc_attr(get_theme_mod('form_button_border_color', $primary_color)) . ';';
    $css_output .= 'cursor: pointer;';
    $css_output .= 'font-size: 16px;';
    $css_output .= 'font-weight: 500;';
    $css_output .= 'text-align: center;';
    $css_output .= 'text-decoration: none;';
    $css_output .= 'display: inline-block;';
    $css_output .= 'transition: all 0.3s ease;';
    $css_output .= '}';
    
    // Estilo para hover do botão
    $css_output .= '.btn-submit-uenf:hover,
    .btn-form-uenf:hover,
    button[type="submit"].btn-uenf:hover {';
    $css_output .= 'background-color: ' . esc_attr(get_theme_mod('form_button_bg_hover_color', $primary_color)) . ';';
    $css_output .= 'color: ' . esc_attr(get_theme_mod('form_button_text_hover_color', '#ffffff')) . ';';
    $css_output .= 'border-color: ' . esc_attr(get_theme_mod('form_button_border_hover_color', $primary_color)) . ';';
    $css_output .= 'transform: translateY(-1px);';
    $css_output .= 'box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);';
    $css_output .= '}';
    
    // Estilo para botão ativo
    $css_output .= '.btn-submit-uenf:active,
    .btn-form-uenf:active,
    button[type="submit"].btn-uenf:active {';
    $css_output .= 'transform: translateY(0);';
    $css_output .= 'box-shadow: none;';
    $css_output .= '}';
    
    // Estilo para foco no botão
    $css_output .= '.btn-submit-uenf:focus,
    .btn-form-uenf:focus,
    button[type="submit"].btn-uenf:focus {';
    $css_output .= 'outline: none;';
    $css_output .= 'box-shadow: 0 0 0 2px rgba(29, 55, 113, 0.25);';
    $css_output .= '}';
    
    // Estilo para labels
    $css_output .= '.form-label-uenf {';
    $css_output .= 'display: block;';
    $css_output .= 'margin-bottom: 5px;';
    $css_output .= 'font-weight: 500;';
    $css_output .= 'color: ' . esc_attr(get_theme_mod('form_input_text_color', '#333333')) . ';';
    $css_output .= '}';
    
    // Estilo para grupos de formulário
    $css_output .= '.form-group-uenf {';
    $css_output .= 'margin-bottom: 20px;';
    $css_output .= '}';
    
    // Fechar a tag de estilo
    $css_output .= '</style>';
    
    // Output seguro sem interferir com buffer
    echo $css_output;
}
add_action('wp_head', 'cct_customize_css', 999);

/**
 * Reset customizer settings to default values
 */
function cct_reset_customizer_settings() {
    // Verificar nonce de segurança
    if (!wp_verify_nonce($_POST['nonce'], 'cct_reset_customizer')) {
        wp_die('Erro de segurança');
    }
    
    // Lista de todas as configurações do customizer para resetar
    $settings_to_reset = array(
        'menu_style' => CCT_DEFAULT_MENU_STYLE,
        'menu_show_hierarchy_icons' => CCT_DEFAULT_MENU_HIERARCHY_ICONS,
        'shortcut_button_bg' => CCT_PRIMARY_COLOR,
        'shortcut_button_icon_color' => CCT_WHITE,
        'shortcut_panel_bg' => CCT_PRIMARY_COLOR,
        'shortcut_panel_width' => CCT_DEFAULT_PANEL_WIDTH,
        'shortcut_header_bg' => CCT_PRIMARY_COLOR,
        'shortcut_header_text_color' => CCT_WHITE,
        'shortcut_close_button_bg' => CCT_DEFAULT_TRANSPARENT,
        'shortcut_close_button_color' => CCT_WHITE,
        // Adicione outras configurações conforme necessário
    );
    
    // Resetar cada configuração
    foreach ($settings_to_reset as $setting => $default_value) {
        remove_theme_mod($setting);
    }
    
    // Retornar sucesso
    wp_send_json_success(array(
        'message' => __('Configurações redefinidas com sucesso!', 'cct')
    ));
}
add_action('wp_ajax_cct_reset_customizer', 'cct_reset_customizer_settings');

/**
 * Enqueue JavaScript for customizer reset functionality
 */
function cct_customizer_reset_scripts() {
    if (is_customize_preview()) {
        return;
    }
    
    wp_add_inline_script('customize-controls', '
        jQuery(document).ready(function($) {
            // Event delegation para botão de reset
            $(document).on("click", "[data-action=reset-customizer]", function(e) {
                e.preventDefault();
                
                if (!confirm("ATENÇÃO: Esta ação irá redefinir TODAS as configurações do customizer para os valores padrão.\n\nEsta ação NÃO PODE ser desfeita.\n\nDeseja continuar?")) {
                    return;
                }
                
                var button = $(this);
                var originalText = button.val();
                button.val("Redefinindo...").prop("disabled", true);
                
                // Verificar se jQuery e ajaxurl existem
                if (typeof ajaxurl === "undefined") {
                    alert("Erro: AJAX não disponível");
                    button.val(originalText).prop("disabled", false);
                    return;
                }
                
                $.post(ajaxurl, {
                    action: "cct_reset_customizer",
                    nonce: "' . wp_create_nonce('cct_reset_customizer') . '"
                }, function(response) {
                    if (response.success) {
                        alert("Configurações redefinidas com sucesso!");
                        window.location.reload();
                    } else {
                         alert("Erro ao redefinir configurações: " + (response.data || "Erro desconhecido"));
                     }
                 }).fail(function() {
                     alert("Erro de conexão. Tente novamente.");
                 }).always(function() {
                     button.val(originalText).prop("disabled", false);
                 });
             });
         });
    ');
}
add_action('customize_controls_enqueue_scripts', 'cct_customizer_reset_scripts');

/**
 * Enqueue preview JavaScript for real-time updates
 */
function cct_customizer_preview_scripts() {
    wp_enqueue_script(
        'cct-customizer-preview',
        get_template_directory_uri() . '/js/customizer-preview.js',
        array('jquery', 'customize-preview'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_preview_init', 'cct_customizer_preview_scripts');

/**
 * Enfileira scripts de preview para redes sociais
 */
function cct_social_media_customizer_preview_scripts() {
    wp_enqueue_script(
        'cct-social-media-preview',
        get_template_directory_uri() . '/js/customizer-social-media-preview.js',
        array('jquery', 'customize-preview'),
        '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'cct_social_media_customizer_preview_scripts');

/**
 * Enfileira scripts de reset para redes sociais no customizer
 */
function cct_social_media_customizer_reset_scripts() {
    wp_enqueue_script(
        'cct-social-media-reset',
        get_template_directory_uri() . '/js/customizer-social-media-reset.js',
        array('jquery', 'customize-controls'),
        '1.0.0',
        true
    );
    
    wp_enqueue_style(
        'cct-social-media-reset-css',
        get_template_directory_uri() . '/css/customizer-social-reset.css',
        array(),
        '1.0.0'
    );
}
add_action('customize_controls_enqueue_scripts', 'cct_social_media_customizer_reset_scripts');

/**
 * Enfileira scripts para exibição de valores nos controles range
 */
function cct_customizer_range_display_scripts() {
    wp_enqueue_script(
        'cct-range-display',
        get_template_directory_uri() . '/js/customizer-range-display.js',
        array('jquery', 'customize-controls'),
        '1.0.0',
        true
    );
}
add_action('customize_controls_enqueue_scripts', 'cct_customizer_range_display_scripts');

/**
 * Configure settings for postMessage transport
 */
function cct_configure_postmessage_settings($wp_customize) {
    // Lista de settings que devem usar postMessage
    $postmessage_settings = array(
        'shortcut_button_bg',
        'shortcut_button_icon_color',
        'shortcut_panel_bg',
        'shortcut_panel_width',
        'shortcut_header_bg',
        'shortcut_header_text_color',
        'shortcut_close_button_bg',
        'shortcut_close_button_color',
        'menu_show_hierarchy_icons',
    );
    
    // Configurar transport para postMessage
    foreach ($postmessage_settings as $setting) {
        if ($wp_customize->get_setting($setting)) {
            $wp_customize->get_setting($setting)->transport = 'postMessage';
        }
    }
}
add_action('customize_register', 'cct_configure_postmessage_settings', 20);

/**
 * Export customizer settings
 */
function cct_export_customizer_settings() {
    // Verificar nonce de segurança
    if (!wp_verify_nonce($_POST['nonce'], 'cct_export_customizer')) {
        wp_die('Erro de segurança');
    }
    
    // Obter todas as configurações do tema
    $theme_mods = get_theme_mods();
    
    // Filtrar apenas configurações do nosso customizer
    $cct_settings = array();
    $cct_prefixes = array('menu_', 'shortcut_', 'cct_', 'header_', 'footer_', 'form_');
    
    foreach ($theme_mods as $key => $value) {
        foreach ($cct_prefixes as $prefix) {
            if (strpos($key, $prefix) === 0) {
                $cct_settings[$key] = $value;
                break;
            }
        }
    }
    
    // Adicionar metadados
    $backup_data = array(
        'version' => wp_get_theme()->get('Version'),
        'date' => current_time('Y-m-d H:i:s'),
        'site_url' => get_site_url(),
        'settings' => $cct_settings
    );
    
    // Retornar dados para download
    wp_send_json_success(array(
        'data' => base64_encode(json_encode($backup_data)),
        'filename' => 'cct-customizer-backup-' . date('Y-m-d-H-i-s') . '.txt'
    ));
}
add_action('wp_ajax_cct_export_customizer', 'cct_export_customizer_settings');

/**
 * Import customizer settings
 */
function cct_import_customizer_settings() {
    // Verificar nonce de segurança
    if (!wp_verify_nonce($_POST['nonce'], 'cct_import_customizer')) {
        wp_die('Erro de segurança');
    }
    
    $import_data = sanitize_textarea_field($_POST['import_data']);
    
    if (empty($import_data)) {
        wp_send_json_error('Dados de importação não fornecidos');
    }
    
    // Decodificar dados
    $decoded_data = base64_decode($import_data);
    $backup_data = json_decode($decoded_data, true);
    
    if (!$backup_data || !isset($backup_data['settings'])) {
        wp_send_json_error('Formato de backup inválido');
    }
    
    // Importar configurações
    $imported_count = 0;
    foreach ($backup_data['settings'] as $key => $value) {
        set_theme_mod($key, $value);
        $imported_count++;
    }
    
    wp_send_json_success(array(
        'message' => sprintf(__('%d configurações importadas com sucesso!', 'cct'), $imported_count),
        'backup_date' => isset($backup_data['date']) ? $backup_data['date'] : 'Desconhecida'
    ));
}
add_action('wp_ajax_cct_import_customizer', 'cct_import_customizer_settings');

/**
 * Enqueue backup/restore JavaScript
 */
function cct_backup_restore_scripts() {
    if (is_customize_preview()) {
        return;
    }
    
    wp_add_inline_script('customize-controls', '
        function cctExportSettings() {
            var button = document.querySelector(".cct-export-button");
            var originalText = button.value;
            button.value = "Exportando...";
            button.disabled = true;
            
            jQuery.post(ajaxurl, {
                action: "cct_export_customizer",
                nonce: "' . wp_create_nonce('cct_export_customizer') . '"
            }, function(response) {
                if (response.success) {
                    // Criar download do arquivo
                    var element = document.createElement("a");
                    element.setAttribute("href", "data:text/plain;charset=utf-8," + encodeURIComponent(atob(response.data.data)));
                    element.setAttribute("download", response.data.filename);
                    element.style.display = "none";
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                    
                    alert("✅ Backup exportado com sucesso!");
                } else {
                    alert("❌ Erro ao exportar: " + response.data);
                }
            }).fail(function() {
                alert("❌ Erro de conexão. Tente novamente.");
            }).always(function() {
                button.value = originalText;
                button.disabled = false;
            });
        }
        
        function cctRestoreSettings() {
            var importData = document.querySelector("#_customize-input-cct_import_settings").value;
            
            if (!importData.trim()) {
                alert("❌ Por favor, cole o conteúdo do backup no campo acima.");
                return;
            }
            
            if (!confirm("⚠️ ATENÇÃO: Esta ação irá sobrescrever TODAS as configurações atuais.\n\nEsta ação NÃO PODE ser desfeita.\n\nDeseja continuar?")) {
                return;
            }
            
            var button = document.querySelector(".cct-restore-button");
            var originalText = button.value;
            button.value = "Restaurando...";
            button.disabled = true;
            
            jQuery.post(ajaxurl, {
                action: "cct_import_customizer",
                nonce: "' . wp_create_nonce('cct_import_customizer') . '",
                import_data: importData
            }, function(response) {
                if (response.success) {
                    alert("✅ " + response.data.message + "\n\nData do backup: " + response.data.backup_date);
                    // Recarregar o customizer
                    window.location.reload();
                } else {
                    alert("❌ Erro ao restaurar: " + response.data);
                }
            }).fail(function() {
                alert("❌ Erro de conexão. Tente novamente.");
            }).always(function() {
                button.value = originalText;
                button.disabled = false;
            });
        }
    ');
}
add_action('customize_controls_enqueue_scripts', 'cct_backup_restore_scripts');

/**
 * Enqueue extension manager JavaScript
 */
function cct_extension_manager_scripts() {
    if (is_customize_preview()) {
        return;
    }
    
    wp_add_inline_script('customize-controls', '
        function cctEnableAllExtensions() {
            // Ativar controle global
            if (wp.customize.control("cct_extensions_global_enabled")) {
                wp.customize.control("cct_extensions_global_enabled").setting.set(true);
            }
            
            // Ativar todas as extensões individuais
            var extensionControls = [
                "cct_extension_dark_mode_enabled",
                "cct_extension_shadows_enabled",
                "cct_extension_breakpoints_enabled",
                "cct_extension_gradients_enabled",
                "cct_extension_animations_enabled",
                "cct_extension_patterns_enabled",
                "cct_extension_design_tokens_enabled",
                "cct_extension_colors_enabled",
                "cct_extension_icons_enabled",
                "cct_extension_typography_enabled"
            ];
            
            extensionControls.forEach(function(controlId) {
                if (wp.customize.control(controlId)) {
                    wp.customize.control(controlId).setting.set(true);
                }
            });
            
            alert("✅ Todas as extensões foram ativadas!");
        }
        
        function cctDisableAllExtensions() {
            if (confirm("⚠️ Tem certeza que deseja desativar todas as extensões?\n\nIsso pode afetar a aparência do seu site.")) {
                // Desativar todas as extensões individuais
                var extensionControls = [
                    "cct_extension_dark_mode_enabled",
                    "cct_extension_shadows_enabled",
                    "cct_extension_breakpoints_enabled",
                    "cct_extension_gradients_enabled",
                    "cct_extension_animations_enabled",
                    "cct_extension_patterns_enabled",
                    "cct_extension_design_tokens_enabled",
                    "cct_extension_colors_enabled",
                    "cct_extension_icons_enabled",
                    "cct_extension_typography_enabled"
                ];
                
                extensionControls.forEach(function(controlId) {
                    if (wp.customize.control(controlId)) {
                        wp.customize.control(controlId).setting.set(false);
                    }
                });
                
                // Opcionalmente desativar o controle global também
                if (wp.customize.control("cct_extensions_global_enabled")) {
                    wp.customize.control("cct_extensions_global_enabled").setting.set(false);
                }
                
                alert("❌ Todas as extensões foram desativadas!");
            }
        }
    ');
}
add_action('customize_controls_enqueue_scripts', 'cct_extension_manager_scripts');

/**
 * Enqueue validation JavaScript for visual feedback
 */
function cct_customizer_validation_scripts() {
    wp_enqueue_script(
        'cct-customizer-validation',
        get_template_directory_uri() . '/js/customizer-validation.js',
        array('jquery', 'customize-controls'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_controls_enqueue_scripts', 'cct_customizer_validation_scripts');

/**
 * Enqueue tooltips JavaScript for contextual help
 */
function cct_customizer_tooltips_scripts() {
    wp_enqueue_script(
        'cct-customizer-tooltips',
        get_template_directory_uri() . '/js/customizer-tooltips.js',
        array('jquery', 'customize-controls'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_controls_enqueue_scripts', 'cct_customizer_tooltips_scripts');

/**
 * Adiciona sistema de combinações de fontes predefinidas
 */
function cct_font_combinations_customizer($wp_customize) {
    // Adicionar painel de Tipografia Avançada - Comentado temporariamente para teste
    // $wp_customize->add_panel('cct_typography_panel', array(
    //     'title' => __('Tipografia Avançada', 'cct'),
    //     'description' => __('Configure fontes, escalas tipográficas e combinações profissionais.', 'cct'),
    //     'priority' => 160,
    // ));
    
    // Seção de Combinações de Fontes - Comentado temporariamente para teste
    // $wp_customize->add_section('cct_font_combinations', array(
    //     'title' => __('Combinações de Fontes', 'cct'),
    //     'description' => __('Pairings profissionais de fontes com preview em tempo real.', 'cct'),
    //     'panel' => 'cct_typography_panel',
    //     'priority' => 10,
    // ));
    
    // Adicionar preview control customizado - Comentado temporariamente para teste
    // $wp_customize->add_setting('cct_font_preview_dummy', array(
    //     'default' => '',
    //     'sanitize_callback' => 'sanitize_text_field',
    //     'transport' => 'postMessage',
    // ));
    // 
    // $wp_customize->add_control('cct_font_preview_dummy', array(
    //     'label' => __('Preview da Combinação', 'cct'),
    //     'description' => __('Visualize como ficará a tipografia com a combinação selecionada.', 'cct'),
    //     'section' => 'cct_font_combinations',
    //     'type' => 'hidden',
    //     'priority' => 15,
    // ));
    // 
    // // Configuração para combinação predefinida
    // $wp_customize->add_setting('cct_font_pairing_preset', array(
    //     'default' => 'theme_default',
    //     'sanitize_callback' => 'cct_sanitize_font_pairing',
    //     'transport' => 'refresh',
    // ));
    // 
    // // Controle para seleção de combinação
    // $wp_customize->add_control('cct_font_pairing_preset', array(
    //     'label' => __('Combinação Predefinida', 'cct'),
    //     'description' => __('Escolha uma combinação profissional de fontes.', 'cct'),
    //     'section' => 'cct_font_combinations',
    //     'type' => 'select',
    //     'choices' => array(
    //         'theme_default' => 'Padrão do Tema - Usar as fontes padrão do tema atual',
    //         'uenf_default' => 'UENF Padrão - Ubuntu + Open Sans (identidade institucional)',
    //         'corporate' => 'Corporativo - Roboto + Lato (profissional e confiável)',
     //         'editorial' => 'Editorial - Merriweather + PT Sans (perfeito para blogs)',
     //         'creative' => 'Criativo - Playfair Display + Source Sans Pro (moderno)',
     //         'academic' => 'Acadêmico - Crimson Text + Open Sans (elegante e legível)',
     //         'tech' => 'Tecnológico - Orbitron + Roboto (moderno e futurista)',
     //     ),
     // ));
     // 
     // // Configuração para aplicar automaticamente
     // $wp_customize->add_setting('cct_apply_font_pairing', array(
     //     'default' => true,
     //     'sanitize_callback' => 'wp_validate_boolean',
     //     'transport' => 'refresh',
     // ));
     // 
     // // Controle para aplicação automática
     // $wp_customize->add_control('cct_apply_font_pairing', array(
     //     'label' => __('Aplicar Combinação Automaticamente', 'cct'),
     //     'description' => __('Aplica automaticamente as fontes da combinação selecionada.', 'cct'),
     //     'section' => 'cct_font_combinations',
     //     'type' => 'checkbox',
     // ));
}
add_action('customize_register', 'cct_font_combinations_customizer');

/**
 * Sanitiza a seleção de font pairing
 */
function cct_sanitize_font_pairing($input) {
    $valid_choices = array('theme_default', 'uenf_default', 'corporate', 'editorial', 'creative', 'academic', 'tech');
    return in_array($input, $valid_choices) ? $input : 'theme_default';
}

/**
 * Sanitize color with alpha (rgba or hex)
 */
function cct_sanitize_color_with_alpha( $color ) {
    // Se estiver vazio, retorna o padrão
    if ( empty( $color ) ) {
        return 'rgba(255, 255, 255, 0.6)';
    }
    
    // Se for rgba
    if ( strpos( $color, 'rgba' ) !== false ) {
        // Validar formato rgba
        if ( preg_match( '/^rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]*\.?[0-9]+)\s*\)$/', $color, $matches ) ) {
            $r = intval( $matches[1] );
            $g = intval( $matches[2] );
            $b = intval( $matches[3] );
            $a = floatval( $matches[4] );
            
            // Validar valores
            if ( $r >= 0 && $r <= 255 && $g >= 0 && $g <= 255 && $b >= 0 && $b <= 255 && $a >= 0 && $a <= 1 ) {
                return $color;
            }
        }
    }
    
    // Se for hex, validar
    if ( strpos( $color, '#' ) === 0 ) {
        return sanitize_hex_color( $color );
    }
    
    // Se não for válido, retorna o padrão
    return 'rgba(255, 255, 255, 0.6)';
}

/**
 * Carrega Google Fonts baseado na combinação selecionada
 */
function cct_enqueue_font_combinations() {
    $font_pairing = get_theme_mod('cct_font_pairing_preset', 'theme_default');
    $apply_pairing = get_theme_mod('cct_apply_font_pairing', true);
    
    if (!$apply_pairing || $font_pairing === 'theme_default') {
        return;
    }
    
    $font_combinations = array(
        'uenf_default' => array(
            'heading' => 'Ubuntu:wght@500;700',
            'body' => 'Open+Sans:wght@400;600',
        ),
        'corporate' => array(
            'heading' => 'Roboto:wght@600',
            'body' => 'Lato:wght@400',
        ),
        'editorial' => array(
            'heading' => 'Merriweather:wght@600',
            'body' => 'PT+Sans:wght@400',
        ),
        'creative' => array(
            'heading' => 'Playfair+Display:wght@600',
            'body' => 'Source+Sans+Pro:wght@400',
        ),
        'academic' => array(
            'heading' => 'Crimson+Text:wght@600',
            'body' => 'Open+Sans:wght@400',
        ),
        'tech' => array(
            'heading' => 'Orbitron:wght@600',
            'body' => 'Roboto:wght@400',
        ),
    );
    
    if (isset($font_combinations[$font_pairing])) {
        $fonts = $font_combinations[$font_pairing];
        $font_url = 'https://fonts.googleapis.com/css2?family=' . $fonts['heading'] . '&family=' . $fonts['body'] . '&display=swap';
        
        // Cache busting baseado na combinação selecionada
        $version = $font_pairing . '_v1';
        
        // Remove fontes anteriores para evitar conflitos
        wp_dequeue_style('cct-font-combinations');
        wp_deregister_style('cct-font-combinations');
        
        // Carrega nova combinação com prioridade alta
        wp_enqueue_style('cct-font-combinations', $font_url, array(), $version);
        
        // Preload para melhor performance
        add_action('wp_head', function() use ($font_url) {
            echo '<link rel="preload" href="' . esc_url($font_url) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
            echo '<noscript><link rel="stylesheet" href="' . esc_url($font_url) . '"></noscript>';
        }, 1);
    }
}
add_action('wp_enqueue_scripts', 'cct_enqueue_font_combinations');

/**
 * Aplica CSS das combinações de fontes
 */
function cct_font_combinations_css() {
    $font_pairing = get_theme_mod('cct_font_pairing_preset', 'theme_default');
    $apply_pairing = get_theme_mod('cct_apply_font_pairing', true);
    
    if (!$apply_pairing || $font_pairing === 'theme_default') {
        return;
    }
    
    $font_styles = array(
        'uenf_default' => array(
            'heading' => '"Ubuntu", "Arial", sans-serif',
            'body' => '"Open Sans", "Helvetica", sans-serif',
        ),
        'corporate' => array(
            'heading' => '"Roboto", "Arial", sans-serif',
            'body' => '"Lato", "Helvetica", sans-serif',
        ),
        'editorial' => array(
            'heading' => '"Merriweather", "Georgia", serif',
            'body' => '"PT Sans", "Arial", sans-serif',
        ),
        'creative' => array(
            'heading' => '"Playfair Display", "Times", serif',
            'body' => '"Source Sans Pro", "Arial", sans-serif',
        ),
        'academic' => array(
            'heading' => '"Crimson Text", "Times", serif',
            'body' => '"Open Sans", "Arial", sans-serif',
        ),
        'tech' => array(
            'heading' => '"Orbitron", "Arial", sans-serif',
            'body' => '"Roboto", "Arial", sans-serif',
        ),
    );
    
    if (isset($font_styles[$font_pairing])) {
        $styles = $font_styles[$font_pairing];
        
        // Usar output buffering seguro para evitar conflitos
        $css_output = '<style type="text/css" id="cct-font-combinations-css">';
        $css_output .= '/* CCT Font Combinations - Priority CSS */';
        $css_output .= 'h1, h2, h3, h4, h5, h6, .entry-title, .site-title, .page-title, .post-title { font-family: ' . $styles['heading'] . ' !important; font-display: swap; }';
        $css_output .= 'body, .entry-content, p, .content-area, .site-description, .widget, .menu { font-family: ' . $styles['body'] . ' !important; font-display: swap; }';
        $css_output .= '/* Force font loading */';
        $css_output .= 'body.fonts-loaded h1, body.fonts-loaded h2, body.fonts-loaded h3, body.fonts-loaded h4, body.fonts-loaded h5, body.fonts-loaded h6 { font-family: ' . $styles['heading'] . ' !important; }';
        $css_output .= 'body.fonts-loaded, body.fonts-loaded p, body.fonts-loaded .entry-content { font-family: ' . $styles['body'] . ' !important; }';
        $css_output .= '</style>';
        
        // JavaScript para verificar carregamento das fontes
        $js_output = '<script>';
        $js_output .= 'document.addEventListener("DOMContentLoaded", function() {';
        $js_output .= '  if (document.fonts && document.fonts.ready) {';
        $js_output .= '    document.fonts.ready.then(function() {';
        $js_output .= '      document.body.classList.add("fonts-loaded");';
        $js_output .= '    });';
        $js_output .= '  } else {';
        $js_output .= '    setTimeout(function() {';
        $js_output .= '      document.body.classList.add("fonts-loaded");';
        $js_output .= '    }, 3000);';
        $js_output .= '  }';
        $js_output .= '});';
        $js_output .= '</script>';
        
        // Output seguro sem interferir com buffer
        echo $css_output . $js_output;
    }
}
add_action('wp_head', 'cct_font_combinations_css', 999);

/**
 * Adiciona JavaScript para preview em tempo real no customizer
 */
function cct_font_combinations_preview_js() {
    wp_enqueue_script(
        'cct-font-combinations-preview',
        get_template_directory_uri() . '/js/customizer-typography.js',
        array('jquery', 'customize-preview'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Adicionar JavaScript inline para preview
    wp_add_inline_script('cct-font-combinations-preview', '
        (function($) {
            // Aguarda o customizer estar pronto
            $(document).ready(function() {
                if (typeof wp !== "undefined" && wp.customize) {
                    
                    // Função para aplicar tipografia no preview
                    function applyTypographyPreview(newval) {
                        console.log("Aplicando preview:", newval);
                        
                        // Remove estilos anteriores
                        $("#cct-preview-typography").remove();
                        
                        var css = "";
                        var fontLink = "";
                        
                        switch(newval) {
                            case "uenf_default":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Ubuntu:wght@500;700&family=Open+Sans:wght@400;600&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Ubuntu\",\"Arial\",sans-serif!important}body,.entry-content,p,.content-area{font-family:\"Open Sans\",\"Helvetica\",sans-serif!important}";
                                break;
                            case "classic_serif":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Source+Sans+Pro:wght@400&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Playfair Display\",\"Times\",serif!important}body,.entry-content,p,.content-area{font-family:\"Source Sans Pro\",\"Arial\",sans-serif!important}";
                                break;
                            case "modern_sans":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans:wght@400&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Montserrat\",\"Arial\",sans-serif!important}body,.entry-content,p,.content-area{font-family:\"Open Sans\",\"Helvetica\",sans-serif!important}";
                                break;
                            case "humanist":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Merriweather:wght@600&family=Lato:wght@400&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Merriweather\",\"Georgia\",serif!important}body,.entry-content,p,.content-area{font-family:\"Lato\",\"Helvetica\",sans-serif!important}";
                                break;
                            case "geometric":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Nunito:wght@400&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Poppins\",\"Arial\",sans-serif!important}body,.entry-content,p,.content-area{font-family:\"Nunito\",\"Helvetica\",sans-serif!important}";
                                break;
                            case "editorial":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Crimson+Text:wght@600&family=PT+Sans:wght@400&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Crimson Text\",\"Times\",serif!important}body,.entry-content,p,.content-area{font-family:\"PT Sans\",\"Arial\",sans-serif!important}";
                                break;
                            case "tech":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto:wght@400&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Orbitron\",\"Arial\",sans-serif!important}body,.entry-content,p,.content-area{font-family:\"Roboto\",\"Arial\",sans-serif!important}";
                                break;
                            case "corporate":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Roboto:wght@600&family=Lato:wght@400&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Roboto\",\"Arial\",sans-serif!important}body,.entry-content,p,.content-area{font-family:\"Lato\",\"Helvetica\",sans-serif!important}";
                                break;
                            case "creative":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Source+Sans+Pro:wght@400&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Playfair Display\",\"Times\",serif!important}body,.entry-content,p,.content-area{font-family:\"Source Sans Pro\",\"Arial\",sans-serif!important}";
                                break;
                            case "academic":
                                fontLink = "<link href=\"https://fonts.googleapis.com/css2?family=Crimson+Text:wght@600&family=Open+Sans:wght@400&display=swap\" rel=\"stylesheet\">";
                                css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:\"Crimson Text\",\"Times\",serif!important}body,.entry-content,p,.content-area{font-family:\"Open Sans\",\"Arial\",sans-serif!important}";
                                break;
                            case "theme_default":
                            default:
                                css = "/* Padrão do tema */";
                                break;
                        }
                        
                        // Aplica as mudanças no preview
                        if (fontLink) {
                            $("head").append(fontLink);
                        }
                        $("head").append("<style id=\"cct-preview-typography\">" + css + "</style>");
                        
                        // Força re-render
                        $("body").addClass("cct-typography-updated").removeClass("cct-typography-updated");
                    }
                    
                    // Bind para mudanças na configuração
                    wp.customize("cct_font_pairing_preset", function(value) {
                        value.bind(applyTypographyPreview);
                    });
                    
                    // Aplica configuração inicial
                    var initialValue = wp.customize("cct_font_pairing_preset").get();
                    if (initialValue) {
                        applyTypographyPreview(initialValue);
                    }
                }
            });
        })(jQuery);
    ');
}
add_action('customize_preview_init', 'cct_font_combinations_preview_js');

// ============================================================================
// SISTEMA DE BUSCA PERSONALIZADO
// ============================================================================

// Incluir controles do sistema de busca
require_once get_template_directory() . '/inc/customizer/class-search-customizer-controls.php';

// Incluir sistema de busca avançada
require_once get_template_directory() . '/inc/class-advanced-search.php';

/**
 * Adicionar CSS dinâmico da busca
 */
function cct_search_customizer_css() {
    $css = CCT_Search_Customizer_Controls::generate_css();
    if (!empty($css)) {
        echo '<style type="text/css" id="cct-search-customizer-css">' . $css . '</style>';
    }
}
add_action('wp_head', 'cct_search_customizer_css');

/**
 * Filtrar theme.json para sincronizar com configurações do Customizer
 */
function cct_filter_theme_json_theme($theme_json) {
    $new_data = array(
        'version' => 2,
        'styles' => array(
            'elements' => array(
                'button' => array(
                    'color' => array(
                        'background' => get_theme_mod('form_button_bg_color', '#1d3771'),
                        'text' => get_theme_mod('form_button_text_color', '#ffffff')
                    ),
                    'border' => array(
                        'radius' => get_theme_mod('form_button_border_radius', '12px')
                    ),
                    ':hover' => array(
                        'color' => array(
                            'background' => get_theme_mod('form_button_bg_hover_color', '#152a54')
                        )
                    )
                )
            ),
            'blocks' => array(
                'core/button' => array(
                    'color' => array(
                        'background' => get_theme_mod('form_button_bg_color', '#1d3771'),
                        'text' => get_theme_mod('form_button_text_color', '#ffffff')
                    ),
                    'border' => array(
                        'radius' => get_theme_mod('form_button_border_radius', '12px')
                    )
                )
            )
        )
    );
    
    return $theme_json->update_with($new_data);
}
add_filter('wp_theme_json_data_theme', 'cct_filter_theme_json_theme');

/**
 * JavaScript para preview do Customizer
 */
function cct_customize_preview_js() {
    wp_enqueue_script(
        'cct-customizer-preview',
        get_template_directory_uri() . '/js/customizer-preview.js',
        array('customize-preview'),
        '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'cct_customize_preview_js');