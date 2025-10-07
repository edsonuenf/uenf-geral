<?php
/**
 * Controles do Customizer para Sistema de Busca
 * 
 * Adiciona seção e controles para personalizar a busca quando a extensão estiver ativa
 * 
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class CCT_Search_Customizer_Controls {
    
    /**
     * Inicializar controles
     */
    public static function init() {
        add_action('customize_register', array(__CLASS__, 'register_controls'));
        add_action('customize_preview_init', array(__CLASS__, 'preview_scripts'));
        add_action('customize_controls_enqueue_scripts', array(__CLASS__, 'enqueue_customizer_scripts'));
    }
    
    /**
     * Registrar controles no customizer
     */
    public static function register_controls($wp_customize) {
        // Adicionar seção
        $wp_customize->add_section('cct_search_customizer', array(
            'title' => '🔍 Sistema de Busca',
            'description' => 'Personalize a aparência e comportamento do formulário de busca',
            'priority' => 35
        ));
        
        // === CORES ===
        
        // Cor do botão
        $wp_customize->add_setting('cct_search_button_color', array(
            'default' => '#1d3771',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_button_color', array(
            'label' => 'Cor do Botão',
            'description' => 'Define a cor de fundo do botão de busca',
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_button_color'
        )));
        
        // Cor do hover
        $wp_customize->add_setting('cct_search_button_hover_color', array(
            'default' => '#152a5a',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_button_hover_color', array(
            'label' => 'Cor do Botão (Hover)',
            'description' => 'Cor do botão quando o mouse passa sobre ele',
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_button_hover_color'
        )));
        
        // Espessura da borda do campo de busca
        $wp_customize->add_setting('cct_search_border_width', array(
            'default' => 1,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_border_width', array(
            'label' => 'Espessura da Borda do Campo de Busca',
            'description' => 'Espessura da borda em pixels: <span class="range-value" data-setting="cct_search_border_width">1</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 5,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // Cor da borda do campo de busca
        $wp_customize->add_setting('cct_search_border_color', array(
            'default' => '#ddd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_border_color', array(
            'label' => 'Cor da Borda do Campo de Busca',
            'description' => 'Define a cor da borda do campo de entrada de texto',
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_border_color'
        )));
        
        // Cor de fundo do campo de texto
        $wp_customize->add_setting('cct_search_field_bg_color', array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_field_bg_color', array(
            'label' => 'Cor de Fundo do Campo de Texto',
            'description' => 'Define a cor de fundo do campo de entrada de texto',
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_field_bg_color'
        )));
        
        // Cor do texto no campo de busca
        $wp_customize->add_setting('cct_search_field_text_color', array(
            'default' => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_field_text_color', array(
            'label' => 'Cor do Texto do Campo',
            'description' => 'Define a cor do texto digitado no campo de busca',
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_field_text_color'
        )));
        
        // Cor do placeholder
        $wp_customize->add_setting('cct_search_field_placeholder_color', array(
            'default' => '#999999',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_field_placeholder_color', array(
            'label' => 'Cor do Placeholder',
            'description' => 'Define a cor do texto de placeholder no campo de busca',
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_field_placeholder_color'
        )));
        
        // Cor de foco do campo
        $wp_customize->add_setting('cct_search_field_focus_color', array(
            'default' => '#1d3771',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_field_focus_color', array(
            'label' => 'Cor de Foco do Campo',
            'description' => 'Define a cor da borda quando o campo está em foco',
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_field_focus_color'
        )));
        
        // === BORDER RADIUS INDIVIDUAIS - CAMPO DE BUSCA ===
        
        // Border radius top-left do campo
        $wp_customize->add_setting('cct_search_border_radius_top_left', array(
            'default' => 25,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('cct_search_border_radius_top_left', array(
            'label' => 'Border Radius Superior Esquerdo - Campo',
            'description' => 'Define o raio da borda superior esquerda do campo. Valor: <span class="range-value" data-setting="cct_search_border_radius_top_left">25</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // Border radius top-right do campo
        $wp_customize->add_setting('cct_search_border_radius_top_right', array(
            'default' => 0,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('cct_search_border_radius_top_right', array(
            'label' => 'Border Radius Superior Direito - Campo',
            'description' => 'Define o raio da borda superior direita do campo. Valor: <span class="range-value" data-setting="cct_search_border_radius_top_right">0</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // Border radius bottom-left do campo
        $wp_customize->add_setting('cct_search_border_radius_bottom_left', array(
            'default' => 25,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('cct_search_border_radius_bottom_left', array(
            'label' => 'Border Radius Inferior Esquerdo - Campo',
            'description' => 'Define o raio da borda inferior esquerda do campo. Valor: <span class="range-value" data-setting="cct_search_border_radius_bottom_left">25</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // Border radius bottom-right do campo
        $wp_customize->add_setting('cct_search_border_radius_bottom_right', array(
            'default' => 0,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('cct_search_border_radius_bottom_right', array(
            'label' => 'Border Radius Inferior Direito - Campo',
            'description' => 'Define o raio da borda inferior direita do campo. Valor: <span class="range-value" data-setting="cct_search_border_radius_bottom_right">0</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));

        
        // Cor da borda do botão
        $wp_customize->add_setting('cct_search_button_border_color', array(
            'default' => '#1d3771',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_button_border_color', array(
            'label' => 'Cor da Borda do Botão',
            'description' => 'Define a cor da borda do botão de busca',
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_button_border_color'
        )));
        
        // Espessura da borda do botão
        $wp_customize->add_setting('cct_search_button_border_width', array(
            'default' => 1,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_button_border_width', array(
            'label' => 'Espessura da Borda do Botão',
            'description' => 'Espessura da borda em pixels: <span class="range-value" data-setting="cct_search_button_border_width">1</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 5,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // === BORDER RADIUS INDIVIDUAIS - BOTÃO DE BUSCA ===
        
        // Border radius top-left do botão
        $wp_customize->add_setting('cct_search_button_border_radius_top_left', array(
            'default' => 0,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('cct_search_button_border_radius_top_left', array(
            'label' => 'Border Radius Superior Esquerdo - Botão',
            'description' => 'Define o raio da borda superior esquerda do botão. Valor: <span class="range-value" data-setting="cct_search_button_border_radius_top_left">0</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // Border radius top-right do botão
        $wp_customize->add_setting('cct_search_button_border_radius_top_right', array(
            'default' => 4,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('cct_search_button_border_radius_top_right', array(
            'label' => 'Border Radius Superior Direito - Botão',
            'description' => 'Define o raio da borda superior direita do botão. Valor: <span class="range-value" data-setting="cct_search_button_border_radius_top_right">4</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // Border radius bottom-left do botão
        $wp_customize->add_setting('cct_search_button_border_radius_bottom_left', array(
            'default' => 0,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('cct_search_button_border_radius_bottom_left', array(
            'label' => 'Border Radius Inferior Esquerdo - Botão',
            'description' => 'Define o raio da borda inferior esquerda do botão. Valor: <span class="range-value" data-setting="cct_search_button_border_radius_bottom_left">0</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // Border radius bottom-right do botão
        $wp_customize->add_setting('cct_search_button_border_radius_bottom_right', array(
            'default' => 4,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('cct_search_button_border_radius_bottom_right', array(
            'label' => 'Border Radius Inferior Direito - Botão',
            'description' => 'Define o raio da borda inferior direita do botão. Valor: <span class="range-value" data-setting="cct_search_button_border_radius_bottom_right">4</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // === DIMENSÕES ===
        
        // Altura (padding) - Valor
        $wp_customize->add_setting('cct_search_padding_vertical', array(
            'default' => 6,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        // Altura (padding) - Unidade
        $wp_customize->add_setting('cct_search_padding_vertical_unit', array(
            'default' => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_padding_vertical', array(
            'label' => 'Altura (Padding Vertical)',
            'description' => 'Define a altura interna dos campos. Valor: <span class="range-value" data-setting="cct_search_padding_vertical">6</span><span class="unit-value" data-setting="cct_search_padding_vertical_unit">px</span>',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 2,
                'max' => 20,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        $wp_customize->add_control('cct_search_padding_vertical_unit', array(
            'label' => 'Unidade - Altura',
            'description' => 'Selecione a unidade de medida para a altura',
            'section' => 'cct_search_customizer',
            'type' => 'select',
            'choices' => array(
                'px' => 'Pixels (px)',
                'em' => 'Em (em)',
                'rem' => 'Rem (rem)',
                '%' => 'Porcentagem (%)'
            )
        ));
        
        // Largura máxima - Valor
        $wp_customize->add_setting('cct_search_max_width', array(
            'default' => 300,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        // Largura máxima - Unidade
        $wp_customize->add_setting('cct_search_max_width_unit', array(
            'default' => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_max_width', array(
            'label' => 'Largura Máxima',
            'description' => 'Define a largura máxima do formulário de busca. Valor: <span class="range-value" data-setting="cct_search_max_width">300</span><span class="unit-value" data-setting="cct_search_max_width_unit">px</span>',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 200,
                'max' => 500,
                'step' => 10,
                'data-value-display' => 'px'
            )
        ));
        
        $wp_customize->add_control('cct_search_max_width_unit', array(
            'label' => 'Unidade - Largura',
            'description' => 'Selecione a unidade de medida para a largura',
            'section' => 'cct_search_customizer',
            'type' => 'select',
            'choices' => array(
                'px' => 'Pixels (px)',
                'em' => 'Em (em)',
                'rem' => 'Rem (rem)',
                '%' => 'Porcentagem (%)'
            )
        ));
        
        // === BORDAS ===
        
        // === BORDAS DO BOTÃO ===
        
        // === TIPOGRAFIA ===
        
        // Tamanho da fonte
        $wp_customize->add_setting('cct_search_font_size', array(
            'default' => 14,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_setting('cct_search_font_size_unit', array(
            'default' => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_font_size', array(
            'label' => 'Tamanho da Fonte',
            'description' => 'Define o tamanho do texto nos campos de busca. Valor: <span class="range-value" data-setting="cct_search_font_size">14</span><span class="unit-value" data-setting="cct_search_font_size_unit">px</span>',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 10,
                'max' => 20,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        $wp_customize->add_control('cct_search_font_size_unit', array(
            'label' => 'Unidade do Tamanho da Fonte',
            'description' => 'Selecione a unidade de medida para o tamanho da fonte.',
            'section' => 'cct_search_customizer',
            'type' => 'select',
            'choices' => array(
                'px' => 'Pixels (px)',
                'em' => 'Em (em)',
                'rem' => 'Rem (rem)',
                '%' => 'Porcentagem (%)'
            )
        ));
        
        // === EFEITOS ===
        
        // Sombra
        $wp_customize->add_setting('cct_search_box_shadow', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_box_shadow', array(
            'label' => 'Adicionar Sombra',
            'description' => 'Adiciona uma sombra sutil ao formulário de busca para destacá-lo',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // Transições
        $wp_customize->add_setting('cct_search_transitions', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_transitions', array(
            'label' => 'Ativar Transições',
            'description' => 'Ativa animações suaves (0.3s) para mudanças de cor e efeitos hover',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // === CONFIGURAÇÕES DE BUSCA ===
        
        // Escopo da busca
        $wp_customize->add_setting('cct_search_scope', array(
            'default' => 'all',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_scope', array(
            'label' => 'Escopo da Busca',
            'description' => 'Definir onde a busca deve procurar',
            'section' => 'cct_search_customizer',
            'type' => 'select',
            'choices' => array(
                'all' => 'Todo o conteúdo',
                'posts' => 'Apenas posts',
                'pages' => 'Apenas páginas',
                'posts_pages' => 'Posts e páginas',
                'custom' => 'Tipos personalizados'
            )
        ));
        
        // Tipos de post personalizados
        $wp_customize->add_setting('cct_search_post_types', array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_post_types', array(
            'label' => 'Tipos de Post Personalizados',
            'description' => 'Lista separada por vírgulas (ex: produto,evento)',
            'section' => 'cct_search_customizer',
            'type' => 'text',
            'active_callback' => function() {
                return get_theme_mod('cct_search_scope') === 'custom';
            }
        
        ));
        
        // Busca em multisite
        if (is_multisite()) {
            $wp_customize->add_setting('cct_search_multisite', array(
                'default' => false,
                'sanitize_callback' => 'wp_validate_boolean',
                'transport' => 'refresh'
            ));
            
            $wp_customize->add_control('cct_search_multisite', array(
                'label' => 'Buscar em Todos os Sites',
                'description' => 'Incluir resultados de todos os sites da rede',
                'section' => 'cct_search_customizer',
                'type' => 'checkbox'
            ));
            
            // Sites específicos para busca
            $wp_customize->add_setting('cct_search_sites', array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport' => 'refresh'
            ));
            
            $wp_customize->add_control('cct_search_sites', array(
                'label' => 'Sites Específicos',
                'description' => 'IDs dos sites separados por vírgula (deixe vazio para todos)',
                'section' => 'cct_search_customizer',
                'type' => 'text',
                'active_callback' => function() {
                    return get_theme_mod('cct_search_multisite', false);
                }
            ));
        }
        
        // Número de resultados por página
        $wp_customize->add_setting('cct_search_results_per_page', array(
            'default' => 10,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_results_per_page', array(
            'label' => 'Resultados por Página',
            'description' => 'Número de resultados a exibir por página: <span class="range-value" data-setting="cct_search_results_per_page">10</span> resultados',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 5,
                'max' => 50,
                'step' => 5,
                'data-value-display' => ' resultados'
            )
        ));
        
        // Ordenação dos resultados
        $wp_customize->add_setting('cct_search_orderby', array(
            'default' => 'relevance',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_orderby', array(
            'label' => 'Ordenar Resultados Por',
            'section' => 'cct_search_customizer',
            'type' => 'select',
            'choices' => array(
                'relevance' => 'Relevância',
                'date' => 'Data (mais recente)',
                'date_asc' => 'Data (mais antigo)',
                'title' => 'Título (A-Z)',
                'title_desc' => 'Título (Z-A)',
                'modified' => 'Última modificação'
            )
        ));
        
        // === TEXTOS PERSONALIZADOS ===
        
        // Placeholder do campo de busca
        $wp_customize->add_setting('cct_search_placeholder', array(
            'default' => 'Buscar...',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_placeholder', array(
            'label' => 'Placeholder do Campo',
            'description' => 'Texto que aparece dentro do campo de busca',
            'section' => 'cct_search_customizer',
            'type' => 'text',
            'input_attrs' => array(
                'placeholder' => 'Ex: Tente uma nova busca...'
            )
        ));
        
        // Texto do botão
        $wp_customize->add_setting('cct_search_button_text', array(
            'default' => 'Buscar',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_button_text', array(
            'label' => 'Texto do Botão',
            'description' => 'Texto que aparece no botão de busca',
            'section' => 'cct_search_customizer',
            'type' => 'text',
            'input_attrs' => array(
                'placeholder' => 'Ex: Buscar, Pesquisar, Procurar'
            )
        ));
        
        // Mostrar texto do botão - Desktop
        $wp_customize->add_setting('cct_search_show_button_text_desktop', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_show_button_text_desktop', array(
            'label' => 'Mostrar Texto - Desktop',
            'description' => 'Exibir texto do botão em telas grandes (>992px)',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // Mostrar texto do botão - Tablet
        $wp_customize->add_setting('cct_search_show_button_text_tablet', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_show_button_text_tablet', array(
            'label' => 'Mostrar Texto - Tablet',
            'description' => 'Exibir texto do botão em tablets (577px-992px)',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // Mostrar texto do botão - Mobile
        $wp_customize->add_setting('cct_search_show_button_text_mobile', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_show_button_text_mobile', array(
            'label' => 'Mostrar Texto - Mobile',
            'description' => 'Exibir texto do botão em celulares (<577px)',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // Esconder texto para Mobile e Tablet
        $wp_customize->add_setting('cct_search_hide_text_mobile_tablet', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_hide_text_mobile_tablet', array(
            'label' => 'Esconder Texto - Mobile + Tablet',
            'description' => 'Esconder texto do botão em dispositivos móveis e tablets (<=992px)',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // === COMPORTAMENTO RETRÁTIL ===
        
        // Ativar busca retrátil
        $wp_customize->add_setting('cct_search_retractable', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_retractable', array(
            'label' => 'Busca Retratil',
            'description' => 'Transforma a busca em um botao que expande o formulario ao clicar',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // Ícone do botão retrátil
        $wp_customize->add_setting('cct_search_retractable_icon', array(
            'default' => 'fas fa-search',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_retractable_icon', array(
            'label' => 'Icone do Botao',
            'description' => 'Classe do icone FontAwesome (ex: fas fa-search)',
            'section' => 'cct_search_customizer',
            'type' => 'text',
            'active_callback' => function() {
                return get_theme_mod('cct_search_retractable', false);
            }
        ));
        
        // Posição do botão retrátil
        $wp_customize->add_setting('cct_search_retractable_position', array(
            'default' => 'top-right',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_retractable_position', array(
            'label' => 'Posicao do Botao',
            'section' => 'cct_search_customizer',
            'type' => 'select',
            'choices' => array(
                'top-left' => 'Superior Esquerda',
                'top-right' => 'Superior Direita',
                'bottom-left' => 'Inferior Esquerda',
                'bottom-right' => 'Inferior Direita'
            ),
            'active_callback' => function() {
                return get_theme_mod('cct_search_retractable', false);
            }
        ));
        
        // Altura do botão (padding vertical)
        $wp_customize->add_setting('cct_search_retractable_button_height', array(
            'default' => 14,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_retractable_button_height', array(
            'label' => 'Altura do Botao',
            'description' => 'Padding vertical em pixels: <span class="range-value" data-setting="cct_search_retractable_button_height">14</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 6,
                'max' => 24,
                'step' => 1,
                'data-value-display' => 'px'
            ),
            'active_callback' => function() {
                return get_theme_mod('cct_search_retractable', false);
            }
        ));
        
        // Largura do botão (padding horizontal)
        $wp_customize->add_setting('cct_search_retractable_button_width', array(
            'default' => 18,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_retractable_button_width', array(
            'label' => 'Largura do Botao',
            'description' => 'Padding horizontal em pixels: <span class="range-value" data-setting="cct_search_retractable_button_width">18</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 8,
                'max' => 32,
                'step' => 1,
                'data-value-display' => 'px'
            ),
            'active_callback' => function() {
                return get_theme_mod('cct_search_retractable', false);
            }
        ));
        
        // Tamanho da fonte do botão
        $wp_customize->add_setting('cct_search_retractable_button_font_size', array(
            'default' => 14,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_retractable_button_font_size', array(
            'label' => 'Tamanho da Fonte do Botao',
            'description' => 'Tamanho em pixels: <span class="range-value" data-setting="cct_search_retractable_button_font_size">14</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 10,
                'max' => 20,
                'step' => 1,
                'data-value-display' => 'px'
            ),
            'active_callback' => function() {
                return get_theme_mod('cct_search_retractable', false);
            }
        ));
        
        // Tamanho do ícone do botão
        $wp_customize->add_setting('cct_search_retractable_icon_size', array(
            'default' => 18,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_retractable_icon_size', array(
            'label' => 'Tamanho do Icone',
            'description' => 'Tamanho do ícone em pixels: <span class="range-value" data-setting="cct_search_retractable_icon_size">18</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 12,
                'max' => 24,
                'step' => 1,
                'data-value-display' => 'px'
            ),
            'active_callback' => function() {
                return get_theme_mod('cct_search_retractable', false);
            }
        ));
        
        // === VISIBILIDADE DO FORMULÁRIO ===
        
        // Mostrar formulário de busca - Desktop
        $wp_customize->add_setting('cct_search_show_form_desktop', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_show_form_desktop', array(
            'label' => 'Mostrar Formulário - Desktop',
            'description' => 'Exibir formulário de busca em telas grandes (>992px)',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // Mostrar formulário de busca - Tablet
        $wp_customize->add_setting('cct_search_show_form_tablet', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_show_form_tablet', array(
            'label' => 'Mostrar Formulário - Tablet',
            'description' => 'Exibir formulário de busca em tablets (577px-992px)',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // Mostrar formulário de busca - Mobile
        $wp_customize->add_setting('cct_search_show_form_mobile', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_show_form_mobile', array(
            'label' => 'Mostrar Formulário - Mobile',
            'description' => 'Exibir formulário de busca em celulares (<577px)',
            'section' => 'cct_search_customizer',
            'type' => 'checkbox'
        ));
        
        // === CSS PERSONALIZADO ===
        
        // CSS adicional
        $wp_customize->add_setting('cct_search_custom_css', array(
            'default' => '',
            'sanitize_callback' => 'wp_strip_all_tags',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_custom_css', array(
            'label' => 'CSS Personalizado',
            'description' => 'CSS adicional para a busca (sem tags &lt;style&gt;)',
            'section' => 'cct_search_customizer',
            'type' => 'textarea',
            'input_attrs' => array(
                'rows' => 10,
                'placeholder' => '.search-container input[type="search"] {\n    /* Seus estilos aqui */\n}'
            )
        ));
    }
    
    /**
     * Scripts para preview
     */
    public static function preview_scripts() {
        // Garantir que as dependências do WordPress estejam carregadas
        wp_enqueue_script('jquery');
        wp_enqueue_script('underscore');
        wp_enqueue_script('backbone');
        wp_enqueue_script('wp-util');
        
        wp_enqueue_script(
            'cct-search-customizer-preview',
            get_template_directory_uri() . '/js/customizer-search-preview.js',
            array('customize-preview', 'jquery', 'underscore', 'backbone', 'wp-util'),
            filemtime(get_template_directory() . '/js/customizer-search-preview.js'),
            true
        );
    }
    
    /**
     * Adiciona scripts do customizer
     */
    public static function enqueue_customizer_scripts() {
        // Garantir que as dependências do WordPress estejam carregadas
        wp_enqueue_script('jquery');
        wp_enqueue_script('underscore');
        wp_enqueue_script('backbone');
        wp_enqueue_script('wp-util');
        
        wp_enqueue_script(
            'cct-search-customizer',
            get_template_directory_uri() . '/js/customizer-search.js',
            array('jquery', 'customize-controls', 'underscore', 'backbone', 'wp-util'),
            wp_get_theme()->get('Version'),
            true
        );
        
        // Adicionar JavaScript inline para atualizar valores dos range sliders
        wp_add_inline_script('cct-search-customizer', '
            jQuery(document).ready(function($) {
                // Verificar se wp.customize está disponível
                if (typeof wp === "undefined" || typeof wp.customize === "undefined") {
                    console.warn("wp.customize não está disponível no search customizer");
                    return;
                }
                
                // Função para atualizar valores dos range sliders
                function updateRangeValue(setting, value, suffix) {
                    $(".range-value[data-setting=\"" + setting + "\"]").text(value);
                }
                
                // Configurar listeners para cada setting
                 var rangeSettings = {
                      "cct_search_padding_vertical": "px",
                      "cct_search_max_width": "px",
                      "cct_search_border_width": "px",
                      "cct_search_button_border_width": "px",
                      "cct_search_border_radius_top_left": "px",
                      "cct_search_border_radius_top_right": "px",
                      "cct_search_border_radius_bottom_left": "px",
                      "cct_search_border_radius_bottom_right": "px",
                      "cct_search_button_border_radius_top_left": "px",
                      "cct_search_button_border_radius_top_right": "px",
                      "cct_search_button_border_radius_bottom_left": "px",
                      "cct_search_button_border_radius_bottom_right": "px",
                      "cct_search_font_size": "px",
                      "cct_search_results_per_page": " resultados",
                      "cct_search_retractable_button_height": "px",
                      "cct_search_retractable_button_width": "px",
                      "cct_search_retractable_button_font_size": "px",
                      "cct_search_retractable_icon_size": "px"
                  };
                
                // Configurar cada setting
                $.each(rangeSettings, function(setting, suffix) {
                    if (wp.customize(setting)) {
                        // Atualizar valor inicial
                        var initialValue = wp.customize(setting).get();
                        updateRangeValue(setting, initialValue, suffix);
                        
                        // Listener para mudanças
                        wp.customize(setting, function(value) {
                            value.bind(function(newval) {
                                updateRangeValue(setting, newval, suffix);
                            });
                        });
                    }
                });
            });
        ');
    }
    
    /**
     * Gerar CSS dinâmico
     */
    public static function generate_css() {
        // Verificar se a extensão está ativa
        if (!get_theme_mod('cct_extension_search_customizer_enabled', false)) {
            return '';
        }
        
        $css = '';
        
        // Cores
        $button_color = get_theme_mod('cct_search_button_color', '#1d3771');
        $button_hover_color = get_theme_mod('cct_search_button_hover_color', '#152a5a');
        $border_color = get_theme_mod('cct_search_border_color', '#ddd');
        $button_border_color = get_theme_mod('cct_search_button_border_color', '#1d3771');
        
        // Cores do campo de texto
        $field_bg_color = get_theme_mod('cct_search_field_bg_color', '#ffffff');
        $field_text_color = get_theme_mod('cct_search_field_text_color', '#333333');
        $field_placeholder_color = get_theme_mod('cct_search_field_placeholder_color', '#999999');
        $field_focus_color = get_theme_mod('cct_search_field_focus_color', '#1d3771');
        
        // Dimensões
        $padding_vertical = get_theme_mod('cct_search_padding_vertical', 6);
        $padding_vertical_unit = get_theme_mod('cct_search_padding_vertical_unit', 'px');
        $max_width = get_theme_mod('cct_search_max_width', 300);
        $max_width_unit = get_theme_mod('cct_search_max_width_unit', 'px');
        $font_size = get_theme_mod('cct_search_font_size', 14);
        $font_size_unit = get_theme_mod('cct_search_font_size_unit', 'px');
        
        // Bordas
        $border_width = get_theme_mod('cct_search_border_width', 1);
        $button_border_width = get_theme_mod('cct_search_button_border_width', 1);
        
        // Border radius individuais - Campo
        $field_border_radius_top_left = get_theme_mod('cct_search_border_radius_top_left', 25);
        $field_border_radius_top_right = get_theme_mod('cct_search_border_radius_top_right', 0);
        $field_border_radius_bottom_left = get_theme_mod('cct_search_border_radius_bottom_left', 25);
        $field_border_radius_bottom_right = get_theme_mod('cct_search_border_radius_bottom_right', 0);
        
        // Border radius individuais - Botão
        $button_border_radius_top_left = get_theme_mod('cct_search_button_border_radius_top_left', 0);
        $button_border_radius_top_right = get_theme_mod('cct_search_button_border_radius_top_right', 4);
        $button_border_radius_bottom_left = get_theme_mod('cct_search_button_border_radius_bottom_left', 0);
        $button_border_radius_bottom_right = get_theme_mod('cct_search_button_border_radius_bottom_right', 4);
        
        // Border radius (usando configurações globais como fallback)
        $form_border_radius = get_theme_mod('form_border_radius', '4px');
        $form_button_border_radius = get_theme_mod('form_button_border_radius', '0px 25px 25px 0px');
        
        // Efeitos
        $box_shadow = get_theme_mod('cct_search_box_shadow', false);
        $transitions = get_theme_mod('cct_search_transitions', true);
        
        // CSS personalizado
        $custom_css = get_theme_mod('cct_search_custom_css', '');
        
        $css .= "\n/* Sistema de Busca - Customizer */\n";
        
        // Container
        $css .= ".search-container.search-custom-uenf {\n";
        $css .= "    max-width: {$max_width}{$max_width_unit};\n";
        $css .= "}\n";
        
        // Campo de busca
        $css .= ".search-container.search-custom-uenf input[type='search'].search-custom-uenf {\n";
        $css .= "    width: calc(100% - 50px) !important;\n"; // Largura dinâmica menos botão
        if ($max_width_unit === 'px') {
            $css .= "    max-width: " . ($max_width - 50) . "px !important;\n"; // Max width menos botão
        } else {
            $css .= "    max-width: calc({$max_width}{$max_width_unit} - 50px) !important;\n";
        }
        $css .= "    padding: {$padding_vertical}{$padding_vertical_unit} 12px;\n";
        $css .= "    border-color: {$border_color};\n";
        $css .= "    border-width: {$border_width}px;\n";
        $css .= "    border-radius: {$field_border_radius_top_left}px {$field_border_radius_top_right}px {$field_border_radius_bottom_right}px {$field_border_radius_bottom_left}px;\n";
        $css .= "    font-size: {$font_size}{$font_size_unit};\n";
        $css .= "    background-color: {$field_bg_color};\n";
        $css .= "    color: {$field_text_color};\n";
        if ($transitions) {
            $css .= "    transition: all 0.3s ease;\n";
         }
         $css .= "}\n";
         
         // Placeholder do campo de busca
         $css .= ".search-container.search-custom-uenf input[type='search'].search-custom-uenf::placeholder {\n";
         $css .= "    color: {$field_placeholder_color};\n";
         $css .= "}\n";
         
         // Estado de foco do campo de busca
         $css .= ".search-container.search-custom-uenf input[type='search'].search-custom-uenf:focus {\n";
         $css .= "    border-color: {$field_focus_color};\n";
         $css .= "    outline: none;\n";
         $css .= "    box-shadow: 0 0 0 2px rgba(" . implode(',', sscanf($field_focus_color, '#%02x%02x%02x')) . ", 0.2);\n";
         $css .= "}\n";
        
        // Botão (input e button)
        $css .= ".search-container.search-custom-uenf input[type='submit'].search-custom-uenf,\n";
        $css .= ".search-container.search-custom-uenf button[type='submit'].search-custom-uenf,\n";
        $css .= ".search-container.search-custom-uenf .search-submit.search-custom-uenf {\n";
        $css .= "    padding: {$padding_vertical}{$padding_vertical_unit} 12px;\n";
        $css .= "    background: {$button_color} !important;\n";
        $css .= "    border-color: {$button_border_color} !important;\n";
        $css .= "    border-width: {$button_border_width}px;\n";
        $css .= "    color: white !important;\n";
        $css .= "    border-radius: {$button_border_radius_top_left}px {$button_border_radius_top_right}px {$button_border_radius_bottom_right}px {$button_border_radius_bottom_left}px;\n";
        $css .= "    font-size: {$font_size}{$font_size_unit};\n";
        if ($transitions) {
            $css .= "    transition: all 0.3s ease;\n";
        }
        $css .= "}\n";
        
        // Hover do botão
        $css .= ".search-container.search-custom-uenf input[type='submit'].search-custom-uenf:hover,\n";
        $css .= ".search-container.search-custom-uenf button[type='submit'].search-custom-uenf:hover,\n";
        $css .= ".search-container.search-custom-uenf .search-submit.search-custom-uenf:hover {\n";
        $css .= "    background: {$button_hover_color} !important;\n";
         $css .= "    border-color: {$button_border_color} !important;\n";
         $css .= "}\n";
        
        // Sombra
        if ($box_shadow) {
            $css .= ".search-container.search-custom-uenf form.search-custom-uenf {\n";
            $css .= "    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);\n";
            $css .= "}\n";
        }
        
        // Controle responsivo do texto do botão
        $show_text_desktop = get_theme_mod('cct_search_show_button_text_desktop', false);
        $show_text_tablet = get_theme_mod('cct_search_show_button_text_tablet', false);
        $show_text_mobile = get_theme_mod('cct_search_show_button_text_mobile', false);
        $hide_mobile_tablet = get_theme_mod('cct_search_hide_text_mobile_tablet', false);
        
        // CSS responsivo para texto do botão
        $css .= "\n/* Controle responsivo do texto do botão */\n";
        
        // Primeiro, definir comportamento padrão para todos
        $css .= ".search-container.search-custom-uenf .search-submit.search-custom-uenf .search-text {\n";
        $css .= "    display: inline;\n";
        $css .= "}\n";
        
        // Controle combinado Mobile + Tablet (<=992px)
        if ($hide_mobile_tablet) {
            $css .= "@media (max-width: 992px) {\n";
            $css .= "    .search-container.search-custom-uenf .search-submit.search-custom-uenf .search-text {\n";
            $css .= "        display: none !important;\n";
            $css .= "    }\n";
            $css .= "}\n";
        } else {
            // Controles individuais (só aplicar se o combinado não estiver ativo)
            
            // Desktop (>=993px) - breakpoint exclusivo
            if (!$show_text_desktop) {
                $css .= "@media (min-width: 993px) {\n";
                $css .= "    .search-container.search-custom-uenf .search-submit.search-custom-uenf .search-text {\n";
                $css .= "        display: none !important;\n";
                $css .= "    }\n";
                $css .= "}\n";
            }
            
            // Tablet (577px-992px) - breakpoint exclusivo
            if (!$show_text_tablet) {
                $css .= "@media (min-width: 577px) and (max-width: 992px) {\n";
                $css .= "    .search-container.search-custom-uenf .search-submit.search-custom-uenf .search-text {\n";
                $css .= "        display: none !important;\n";
                $css .= "    }\n";
                $css .= "}\n";
            }
            
            // Mobile (<=576px) - breakpoint exclusivo
            if (!$show_text_mobile) {
                $css .= "@media (max-width: 576px) {\n";
                $css .= "    .search-container.search-custom-uenf .search-submit.search-custom-uenf .search-text {\n";
                $css .= "        display: none !important;\n";
                $css .= "    }\n";
                $css .= "}\n";
            }
        }
        
        // Controle de visibilidade do formulário
        $show_form_desktop = get_theme_mod('cct_search_show_form_desktop', true);
        $show_form_tablet = get_theme_mod('cct_search_show_form_tablet', true);
        $show_form_mobile = get_theme_mod('cct_search_show_form_mobile', true);
        
        // CSS para visibilidade do formulário
        $css .= "\n/* Controle de visibilidade do formulário de busca */\n";
        
        // Desktop (>=993px)
        if (!$show_form_desktop) {
            $css .= "@media (min-width: 993px) {\n";
            $css .= "    .search-container.search-custom-uenf,\n";
            $css .= "    .custom-search-form.search-custom-uenf {\n";
            $css .= "        display: none !important;\n";
            $css .= "    }\n";
            $css .= "}\n";
        }
        
        // Tablet (577px-992px)
        if (!$show_form_tablet) {
            $css .= "@media (min-width: 577px) and (max-width: 992px) {\n";
            $css .= "    .search-container.search-custom-uenf,\n";
            $css .= "    .custom-search-form.search-custom-uenf {\n";
            $css .= "        display: none !important;\n";
            $css .= "    }\n";
            $css .= "}\n";
        }
        
        // Mobile (<=576px)
        if (!$show_form_mobile) {
            $css .= "@media (max-width: 576px) {\n";
            $css .= "    .search-container.search-custom-uenf,\n";
            $css .= "    .custom-search-form.search-custom-uenf {\n";
            $css .= "        display: none !important;\n";
            $css .= "    }\n";
            $css .= "}\n";
        }
        
        // CSS da busca retrátil
        $is_retractable = get_theme_mod('cct_search_retractable', false);
        if ($is_retractable) {
            $retractable_position = get_theme_mod('cct_search_retractable_position', 'top-right');
            $button_color = get_theme_mod('cct_search_button_color', '#1d3771');
            $button_hover_color = get_theme_mod('cct_search_button_hover_color', '#152a5a');
            $button_height = get_theme_mod('cct_search_retractable_button_height', 14);
            $button_width = get_theme_mod('cct_search_retractable_button_width', 18);
            $button_font_size = get_theme_mod('cct_search_retractable_button_font_size', 14);
            $icon_size = get_theme_mod('cct_search_retractable_icon_size', 18);
            
            $css .= "\n/* CSS da Busca Retrátil */\n";
            
            // Botão flutuante
            $css .= ".search-retractable-toggle.search-custom-uenf {\n";
            $css .= "    background: {$button_color} !important;\n";
            $css .= "    font-size: {$button_font_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-toggle.search-custom-uenf i {\n";
            $css .= "    font-size: {$icon_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-toggle.search-custom-uenf:hover {\n";
            $css .= "    background: {$button_hover_color} !important;\n";
            $css .= "}\n";
            
            // Botão inline
            $css .= ".search-retractable-toggle-inline.search-custom-uenf {\n";
            $css .= "    background: {$button_color} !important;\n";
            $css .= "    padding: {$button_height}px {$button_width}px !important;\n";
            $css .= "    font-size: {$button_font_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-toggle-inline.search-custom-uenf i {\n";
            $css .= "    font-size: {$icon_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-toggle-inline.search-custom-uenf:hover {\n";
            $css .= "    background: {$button_hover_color} !important;\n";
            $css .= "}\n";
            
            // Botões de submit nos formulários
            $css .= ".search-retractable-form .search-submit.search-custom-uenf,\n";
            $css .= ".search-retractable-form-inline .search-submit.search-custom-uenf {\n";
            $css .= "    background: {$button_color} !important;\n";
            $css .= "    padding: {$button_height}px {$button_width}px !important;\n";
            $css .= "    font-size: {$button_font_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-form .search-submit.search-custom-uenf i,\n";
            $css .= ".search-retractable-form-inline .search-submit.search-custom-uenf i {\n";
            $css .= "    font-size: {$icon_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-form .search-submit.search-custom-uenf:hover,\n";
            $css .= ".search-retractable-form-inline .search-submit.search-custom-uenf:hover {\n";
            $css .= "    background: {$button_hover_color} !important;\n";
            $css .= "}\n";
        }
        
        // CSS personalizado
        if (!empty($custom_css)) {
            $css .= "\n/* CSS Personalizado */\n";
            $css .= $custom_css . "\n";
        }
        
        return $css;
    }
}

// Inicializar
CCT_Search_Customizer_Controls::init();