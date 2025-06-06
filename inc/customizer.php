<?php
/**
 * CCT Theme Customizer
 */

// Verificar se estamos no WordPress
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Debug
error_log('Customizer.php está sendo carregado');

// Verificar se o WordPress está carregado
if (!function_exists('add_action')) {
    error_log('WordPress não está carregado no Customizer');
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
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', array(
        'label' => __('Cor do Texto', 'cct'),
        'section' => 'cct_text_colors',
        'settings' => 'text_color',
    )));

    // Cor dos Links
    $wp_customize->add_setting('link_color', array(
        'default' => '#26557d',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_color', array(
        'label' => __('Cor dos Links', 'cct'),
        'section' => 'cct_text_colors',
        'settings' => 'link_color',
    )));

// Cor dos Links (Hover)
$wp_customize->add_setting('link_hover_color', array(
    'default' => '#26557d',
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
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', array(
        'label' => __('Cor do Texto', 'cct'),
        'section' => 'cct_text_colors',
        'settings' => 'text_color',
    )));

    // Seção de Cores de Botões
    $wp_customize->add_section('cct_button_colors', array(
        'title' => __('Cores de Botões', 'cct'),
        'panel' => 'cct_colors_panel',
        'priority' => 30,
    ));

    // Cores de Botões
    $button_states = array(
        'normal' => __('Normal', 'cct'),
        'hover' => __('Hover', 'cct'),
        'active' => __('Ativo', 'cct'),
    );

    foreach ($button_states as $state => $label) {
        $wp_customize->add_setting("button_{$state}_color", array(
            'default' => '#1d3771',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "button_{$state}_color", array(
            'label' => sprintf(__('Cor do Botão (%s)', 'cct'), $label),
            'section' => 'cct_button_colors',
            'settings' => "button_{$state}_color",
        )));
    }

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
        'title'    => __('Tipografia', 'seu-tema'),
        'priority' => 30,
    ));
    
    // Adiciona configuração para seleção de fonte
    $wp_customize->add_setting('typography_font_family', array(
        'default'           => 'Ubuntu',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('typography_font_family', array(
        'label'    => __('Família de Fonte', 'seu-tema'),
        'section'  => 'typography_section',
        'type'     => 'select',
        'choices'  => array(
            'Ubuntu'            => __('Ubuntu', 'seu-tema'),
            'system-ui'         => __('System UI', 'seu-tema'),
            'Arial'             => __('Arial', 'seu-tema'),
            'Open Sans'         => __('Open Sans', 'seu-tema'),
            'Helvetica Neue'    => __('Helvetica Neue', 'seu-tema'),
            'sans-serif'        => __('Sans-serif', 'seu-tema'),
        ),
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
            var fontFamily = wp.customize('typography_font_family').get();
            var fontSize = wp.customize('typography_body_size').get();
            var fontWeight = wp.customize('typography_body_weight').get();
            var lineHeight = wp.customize('typography_line_height').get();
            var letterSpacing = wp.customize('typography_letter_spacing').get();
            
            $('#typography-live-preview .preview-text').css({
                'font-family': fontFamily + ', sans-serif',
                'font-size': fontSize + 'px',
                'font-weight': fontWeight,
                'line-height': lineHeight,
                'letter-spacing': letterSpacing + 'px'
            });
        }
        
        // Sincroniza o valor do controle deslizante com o campo de entrada numérica
        wp.customize('typography_body_size', function(value) {
            value.bind(function(newval) {
                // Quando o controle deslizante muda, atualiza o campo de entrada
                wp.customize('typography_body_size_input').set(newval);
                updatePreviewText();
            });
        });
        
        // Sincroniza o valor do campo de entrada numérica com o controle deslizante
        wp.customize('typography_body_size_input', function(value) {
            value.bind(function(newval) {
                // Quando o campo de entrada muda, atualiza o controle deslizante
                wp.customize('typography_body_size').set(newval);
                updatePreviewText();
            });
        });
        
        // Monitorar mudanças nas outras configurações
        wp.customize('typography_font_family', function(value) {
            value.bind(function() { updatePreviewText(); });
        });
        
        wp.customize('typography_body_weight', function(value) {
            value.bind(function() { updatePreviewText(); });
        });
        
        wp.customize('typography_line_height', function(value) {
            value.bind(function() { updatePreviewText(); });
        });
        
        wp.customize('typography_letter_spacing', function(value) {
            value.bind(function() { updatePreviewText(); });
        });
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
    ) );
}
add_action( 'customize_register', 'cct_customize_register' );

// Sanitize checkbox
function cct_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

// Output Customizer CSS
function cct_customize_css() {
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr(get_theme_mod('primary_color', '#1d3771')); ?>;
            --secondary-color: <?php echo esc_attr(get_theme_mod('secondary_color', '#4b6397')); ?>;
            --text-color: <?php echo esc_attr(get_theme_mod('text_color', '#333333')); ?>;
            --button-color: <?php echo esc_attr(get_theme_mod('button_normal_color', '#1d3771')); ?>;
            --button-hover-color: <?php echo esc_attr(get_theme_mod('button_hover_color', '#152a54')); ?>;
            --button-active-color: <?php echo esc_attr(get_theme_mod('button_active_color', '#0f1f3d')); ?>;
            --menu-link-color: <?php echo esc_attr(get_theme_mod('menu_link_color', '#ffffff')); ?>;
            --menu-active-color: <?php echo esc_attr(get_theme_mod('menu_active_color', '#1d3771')); ?>;
            --menu-hover-color: <?php echo esc_attr(get_theme_mod('menu_hover_color', '#2a4a8c')); ?>;
            --menu-selected-color: <?php echo esc_attr(get_theme_mod('menu_selected_color', '#1d3771')); ?>;
            --link-color: <?php echo esc_attr(get_theme_mod('link_color', '#26557d')); ?>;
            --link-hover-color: <?php echo esc_attr(get_theme_mod('link_hover_color', '#26557d')); ?>;
        }

        body {
            color: var(--text-color);
            font-family: <?php echo esc_attr(get_theme_mod('body_font_family', 'Times, system-ui, Arial, Ubuntu, "Open Sans", "Helvetica Neue", sans-serif')); ?>;
            font-weight: 300;
        }

        /* Para links */
        body a, 
        .site-content a, 
        #content a, 
        article a, 
        .entry-content a {
            color: var(--link-color) !important;
            text-decoration: none;
        }

        body a:hover, .site-content a:hover /* etc... */ {
            color: var(--link-hover-color) !important;
        }


        /* Para texto */
        body, 
        p, 
        .site-content, 
        #content, 
        article, 
        .entry-content {
            color: var(--text-color) !important;
        }

        h1, h2, h3 {
            font-weight: 700;
            color: var(--primary-color);
        }

        h4, h5, h6 {
            font-weight: 500;
            color: var(--secondary-color);
        }

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

/*        body {
            font-family: <?php echo esc_attr( get_theme_mod( 'body_font_family', 'Arial, sans-serif' ) ); ?>;
        }
        */
        a, .primary-color {
            color: <?php echo esc_attr( get_theme_mod( 'primary_color', '#0073aa' ) ); ?>;
        }

        .site-header {
            <?php 
            $header_bg_color = get_theme_mod( 'header_background_color', '#ffffff' );
            if ( $header_bg_color ) {
                echo "background-color: " . esc_attr( $header_bg_color ) . ";\n";
            }

            $header_bg_image = get_theme_mod( 'header_background_image' );
            if ( $header_bg_image ) {
                echo "background-image: url('" . esc_url( $header_bg_image ) . "');\n";
                echo "background-size: cover;\n";
                echo "background-position: center;\n";
            }

            $header_padding = get_theme_mod( 'header_padding', '20px 20px 20px 20px' );
            if ( $header_padding ) {
                echo "padding: " . esc_attr( $header_padding ) . ";\n";
            }

            $header_margin = get_theme_mod( 'header_margin', '0 0 0 0' );
            if ( $header_margin ) {
                echo "margin: " . esc_attr( $header_margin ) . ";\n";
            }

            $header_height = get_theme_mod( 'header_height', 'auto' );
            if ( $header_height ) {
                echo "height: " . esc_attr( $header_height ) . ";\n";
            }
            
            $header_layout = get_theme_mod( 'header_layout', 'default' );
            if ( 'centered' === $header_layout ) {
                echo "text-align: center;\n";
            }
            ?>
        }

        .footer-widgets {
            <?php
            $footer_columns = get_theme_mod( 'footer_columns', 4 );
            echo "display: grid;\n";
            echo "grid-template-columns: repeat(" . esc_attr( $footer_columns ) . ", 1fr);\n";
            echo "gap: 30px;\n";
            ?>
        }
    </style>
    <?php
}
add_action('wp_head', 'cct_customize_css', 100); 