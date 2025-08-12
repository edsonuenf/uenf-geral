<?php
/**
 * CCT Theme Customizer
 */

// Verificar se estamos no WordPress
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Verificar se o WordPress está carregado
if (!function_exists('add_action')) {
    return;
}

// Add this at the top of the file
if (!function_exists('__')) {
    function __($text, $domain) {
        return $text;
    }
}

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

// Ensure the necessary classes are available
if ( ! class_exists( 'WP_Customize_Color_Control' ) ) {
    class WP_Customize_Color_Control {
        // Placeholder for WP_Customize_Color_Control
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

function cct_customize_register( $wp_customize ) {
    // Painel de Cores
    $wp_customize->add_panel('cct_colors_panel', array(
        'title' => __('Cores do Tema', 'cct'),
        'priority' => 30,
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
        'default' => '300px',
        'sanitize_callback' => function($value) {
            // Remove espaços em branco
            $value = trim($value);
            
            // Se não tiver unidade, adiciona 'px' como padrão
            if (is_numeric($value)) {
                return absint($value) . 'px';
            }
            
            // Verifica se tem uma unidade válida (px, %, vw)
            if (!preg_match('/^\d+(\.\d+)?(px|%|vw)$/i', $value)) {
                // Se não for válido, retorna o valor padrão
                return '300px';
            }
            
            return $value;
        },
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
        'default' => 'transparent',
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
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_button_bg_color', array(
        'label' => __('Cor de Fundo do Botão', 'cct'),
        'section' => 'cct_form_buttons',
        'settings' => 'form_button_bg_color',
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
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_button_text_color', array(
        'label' => __('Cor do Texto do Botão', 'cct'),
        'section' => 'cct_form_buttons',
        'settings' => 'form_button_text_color',
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
        'default' => '4px',
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
        'default' => '10px 20px 10px 20px',
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
        'default' => '1px',
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
                'typography_letter_spacing': '0'
            };
            
            // Obtém os valores, usando valores padrão se não existirem
            var fontFamily = wp.customize('typography_font_family') ? wp.customize('typography_font_family').get() : defaults.typography_font_family;
            var fontSize = wp.customize('typography_body_size') ? wp.customize('typography_body_size').get() : defaults.typography_body_size;
            var fontWeight = wp.customize('typography_body_weight') ? wp.customize('typography_body_weight').get() : defaults.typography_body_weight;
            var lineHeight = wp.customize('typography_line_height') ? wp.customize('typography_line_height').get() : defaults.typography_line_height;
            var letterSpacing = wp.customize('typography_letter_spacing') ? wp.customize('typography_letter_spacing').get() : defaults.typography_letter_spacing;
            
            // Aplica os estilos apenas se o elemento existir
            var $previewText = $('#typography-live-preview .preview-text');
            if ($previewText.length) {
                $previewText.css({
                    'font-family': fontFamily + ', sans-serif',
                    'font-size': fontSize + 'px',
                    'font-weight': fontWeight,
                    'line-height': lineHeight,
                    'letter-spacing': letterSpacing + 'px'
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
                'typography_letter_spacing'
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
    
    // Iniciar a saída do CSS
    echo '<style type="text/css' . '" id="cct-custom-css">' . "\n";
    
    // Obter valores dos temas
    $primary_color = defined('CCT_PRIMARY_COLOR') ? CCT_PRIMARY_COLOR : '#1D3771';
    
    // Estilos para campos de formulário
    echo '.input-form-uenf,
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
    echo '.input-form-uenf:hover,
    .textarea-form-uenf:hover,
    .select-form-uenf:hover {
        border-color: ' . esc_attr(get_theme_mod('form_input_border_hover_color', $primary_color)) . ';
        background-color: ' . esc_attr(get_theme_mod('form_input_bg_hover_color', '#f9f9f9')) . ';
    }' . "\n\n";
    
    // Estilo para foco nos campos
    echo '.input-form-uenf:focus,
    .textarea-form-uenf:focus,
    .select-form-uenf:focus {
        outline: none;
        border-color: ' . esc_attr(get_theme_mod('form_input_border_hover_color', $primary_color)) . ';
        background-color: ' . esc_attr(get_theme_mod('form_input_bg_hover_color', '#f9f9f9')) . ';
        box-shadow: 0 0 0 2px ' . esc_attr(get_theme_mod('form_input_border_hover_color', $primary_color)) . '20;
    }' . "\n\n";
    
    // Estilo para textarea
    echo '.textarea-form-uenf {
        min-height: 120px;
        resize: vertical;
    }' . "\n\n";
    
    // Estilo para select personalizado
    echo '.select-form-uenf {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'12\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23' . ltrim(esc_attr(get_theme_mod('form_input_text_color', '#333333')), '#') . '\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpolyline points=\'6 9 12 15 18 9\'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
        padding-right: 35px;
    }' . "\n\n";
    
    // Estilos para botões
    echo '.btn-submit-uenf,
    .btn-form-uenf,
    button[type="submit"].btn-uenf {
        display: inline-block;
        font-weight: 500;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: ' . esc_attr(get_theme_mod('form_button_padding', '10px 20px')) . ';
        font-size: 16px;
        line-height: 1.5;
        border-radius: ' . esc_attr(get_theme_mod('form_button_border_radius', '4px')) . ';
        transition: all 0.3s ease;
        cursor: pointer;
        background-color: ' . esc_attr(get_theme_mod('form_button_bg_color', $primary_color)) . ';
        color: ' . esc_attr(get_theme_mod('form_button_text_color', '#ffffff')) . ';
        border: ' . esc_attr(get_theme_mod('form_button_border_width', '1px')) . ' solid ' . esc_attr(get_theme_mod('form_button_border_color', $primary_color)) . ';
    }' . "\n\n";
    
    // Estilo hover para botões
    echo '.btn-submit-uenf:hover,
    .btn-form-uenf:hover,
    button[type="submit"].btn-uenf:hover {
        background-color: ' . esc_attr(get_theme_mod('form_button_bg_hover_color', $primary_color . 'e6')) . ';
        color: ' . esc_attr(get_theme_mod('form_button_text_hover_color', '#ffffff')) . ';
        border-color: ' . esc_attr(get_theme_mod('form_button_border_hover_color', $primary_color . 'e6')) . ';
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }' . "\n\n";
    
    // Estilo ativo para botões
    echo '.btn-submit-uenf:active,
    .btn-form-uenf:active,
    button[type="submit"].btn-uenf:active {
        transform: translateY(0);
        box-shadow: none;
    }' . "\n\n";
    
    // Estilo para foco no botão
    echo '.btn-submit-uenf:focus,
    .btn-form-uenf:focus,
    button[type="submit"].btn-uenf:focus {
        outline: none;
        box-shadow: 0 0 0 2px ' . esc_attr(get_theme_mod('form_button_border_hover_color', $primary_color . '40')) . ';
    }' . "\n\n";
    
    // Estilo para labels
    echo '.form-label-uenf {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: ' . esc_attr(get_theme_mod('form_input_text_color', '#333333')) . ';
    }' . "\n\n";
    
    // Estilo para grupos de formulário
    echo '.form-group-uenf {
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
        font-weight: 300;
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
    echo '.btn-submit-uenf,
    .btn-form-uenf,
    button[type="submit"].btn-uenf {';
    echo 'background-color: ' . esc_attr(get_theme_mod('form_button_bg_color', $primary_color)) . ';';
    echo 'color: ' . esc_attr(get_theme_mod('form_button_text_color', '#ffffff')) . ';';
    echo 'border-radius: ' . esc_attr(get_theme_mod('form_button_border_radius', '4px')) . ';';
    echo 'padding: ' . esc_attr(get_theme_mod('form_button_padding', '10px 20px')) . ';';
    echo 'border: ' . esc_attr(get_theme_mod('form_button_border_width', '1px')) . ' solid ' . esc_attr(get_theme_mod('form_button_border_color', $primary_color)) . ';';
    echo 'cursor: pointer;';
    echo 'font-size: 16px;';
    echo 'font-weight: 500;';
    echo 'text-align: center;';
    echo 'text-decoration: none;';
    echo 'display: inline-block;';
    echo 'transition: all 0.3s ease;';
    echo '}';
    
    // Estilo para hover do botão
    echo '.btn-submit-uenf:hover,
    .btn-form-uenf:hover,
    button[type="submit"].btn-uenf:hover {';
    echo 'background-color: ' . esc_attr(get_theme_mod('form_button_bg_hover_color', $primary_color)) . ';';
    echo 'color: ' . esc_attr(get_theme_mod('form_button_text_hover_color', '#ffffff')) . ';';
    echo 'border-color: ' . esc_attr(get_theme_mod('form_button_border_hover_color', $primary_color)) . ';';
    echo 'transform: translateY(-1px);';
    echo 'box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);';
    echo '}';
    
    // Estilo para botão ativo
    echo '.btn-submit-uenf:active,
    .btn-form-uenf:active,
    button[type="submit"].btn-uenf:active {';
    echo 'transform: translateY(0);';
    echo 'box-shadow: none;';
    echo '}';
    
    // Estilo para foco no botão
    echo '.btn-submit-uenf:focus,
    .btn-form-uenf:focus,
    button[type="submit"].btn-uenf:focus {';
    echo 'outline: none;';
    echo 'box-shadow: 0 0 0 2px rgba(29, 55, 113, 0.25);';
    echo '}';
    
    // Estilo para labels
    echo '.form-label-uenf {';
    echo 'display: block;';
    echo 'margin-bottom: 5px;';
    echo 'font-weight: 500;';
    echo 'color: ' . esc_attr(get_theme_mod('form_input_text_color', '#333333')) . ';';
    echo '}';
    
    // Estilo para grupos de formulário
    echo '.form-group-uenf {';
    echo 'margin-bottom: 20px;';
    echo '}';
    
    // Fechar a tag de estilo
    echo '</style>';
}
add_action('wp_head', 'cct_customize_css', 100); 