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
        // Adicionar painel principal do Sistema de Busca
        $wp_customize->add_panel('cct_search_panel', array(
            'title'       => '🔍 Sistema de Busca',
            'description' => 'Personalize a aparência e comportamento do formulário de busca',
            'priority'    => 33
        ));

        // Adicionar seção principal (Geral)
        $wp_customize->add_section('cct_search_customizer', array(
            'title'       => 'Geral',
            'description' => 'Configurações gerais do formulário de busca',
            'priority'    => 10,
            'panel'       => 'cct_search_panel'
        ));

        // Seções por dispositivo
        $wp_customize->add_section('cct_search_styles_desktop', array(
            'title'    => 'Estilos por Dispositivo — Desktop',
            'priority' => 20,
            'panel'    => 'cct_search_panel'
        ));
        $wp_customize->add_section('cct_search_styles_tablet', array(
            'title'    => 'Estilos por Dispositivo — Tablet',
            'priority' => 21,
            'panel'    => 'cct_search_panel'
        ));
        $wp_customize->add_section('cct_search_styles_mobile', array(
            'title'    => 'Estilos por Dispositivo — Mobile',
            'priority' => 22,
            'panel'    => 'cct_search_panel'
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
            'transport' => 'refresh'
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
            'default' => 25,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
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
            'transport' => 'refresh'
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
            'default' => 25,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
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
        
        

        // === DIMENSÕES RESPONSIVAS (POR DISPOSITIVO) ===
        // Desktop: largura (valor + unidade) e tamanho de fonte (valor + unidade)
        $wp_customize->add_setting('cct_search_width_desktop', array(
            'default'           => 300,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_setting('cct_search_width_unit_desktop', array(
            'default'           => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_width_desktop', array(
            'label'       => 'Largura do campo de busca',
            'section'     => 'cct_search_styles_desktop',
            'type'        => 'number',
            'input_attrs' => array('min' => 1, 'step' => 1)
        ));
        $wp_customize->add_control('cct_search_width_unit_desktop', array(
            'label'       => '',
            'section'     => 'cct_search_styles_desktop',
            'type'        => 'select',
            'choices'     => array('px' => 'px', 'em' => 'em', 'rem' => 'rem', '%' => '%')
        ));
        $wp_customize->add_setting('cct_search_font_size_desktop', array(
            'default'           => 16,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_setting('cct_search_font_size_unit_desktop', array(
            'default'           => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_font_size_desktop', array(
            'label'       => 'Tamanho da Fonte',
            'section'     => 'cct_search_styles_desktop',
            'type'        => 'number',
            'input_attrs' => array('min' => 1, 'step' => 1)
        ));
        $wp_customize->add_control('cct_search_font_size_unit_desktop', array(
            'label'       => '',
            'section'     => 'cct_search_styles_desktop',
            'type'        => 'select',
            'choices'     => array('px' => 'px', 'em' => 'em', 'rem' => 'rem', '%' => '%')
        ));

        // Desktop - Padding do campo de busca (shorthand)
        $wp_customize->add_setting('cct_search_input_padding_desktop', array(
            'default'           => '6 12 6 12',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_input_padding_desktop', array(
            'label'       => 'Padding do campo de busca (top right bottom left)',
            'section'     => 'cct_search_styles_desktop',
            'type'        => 'text',
            'input_attrs' => array('placeholder' => '10 12 10 12')
        ));

        // Tablet: largura (valor + unidade) e tamanho de fonte (valor + unidade)
        $wp_customize->add_setting('cct_search_width_tablet', array(
            'default'           => 250,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_setting('cct_search_width_unit_tablet', array(
            'default'     => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_width_tablet', array(
            'label'       => 'Largura',
            'section'     => 'cct_search_styles_tablet',
            'type'        => 'number',
            'input_attrs' => array('min' => 1, 'step' => 1)
        ));
        $wp_customize->add_control('cct_search_width_unit_tablet', array(
            'label'       => '',
            'section'     => 'cct_search_styles_tablet',
            'type'        => 'select',
            'choices'     => array('px' => 'px', 'em' => 'em', 'rem' => 'rem', '%' => '%')
        ));
        $wp_customize->add_setting('cct_search_font_size_tablet', array(
            'default'           => 15,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_setting('cct_search_font_size_unit_tablet', array(
            'default'           => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_font_size_tablet', array(
            'label'       => 'Tamanho da Fonte',
            'section'     => 'cct_search_styles_tablet',
            'type'        => 'number',
            'input_attrs' => array('min' => 1, 'step' => 1)
        ));
        $wp_customize->add_control('cct_search_font_size_unit_tablet', array(
            'label'       => '',
            'section'     => 'cct_search_styles_tablet',
            'type'        => 'select',
            'choices'     => array('px' => 'px', 'em' => 'em', 'rem' => 'rem', '%' => '%')
        ));

        // Tablet - Padding do campo de busca (shorthand)
        $wp_customize->add_setting('cct_search_input_padding_tablet', array(
            'default'           => '4 12 4 12',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_input_padding_tablet', array(
            'label'       => 'Padding do campo de busca (top right bottom left)',
            'section'     => 'cct_search_styles_tablet',
            'type'        => 'text',
            'input_attrs' => array('placeholder' => '4 12 4 12')
        ));

        // Mobile: largura (valor + unidade) e tamanho de fonte (valor + unidade)
        $wp_customize->add_setting('cct_search_width_mobile', array(
            'default'           => 200,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_setting('cct_search_width_unit_mobile', array(
            'default'           => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_width_mobile', array(
            'label'       => 'Largura',
            'section'     => 'cct_search_styles_mobile',
            'type'        => 'number',
            'input_attrs' => array('min' => 1, 'step' => 1)
        ));
        $wp_customize->add_control('cct_search_width_unit_mobile', array(
            'section'     => 'cct_search_styles_mobile',
            'type'        => 'select',
            'choices'     => array('px' => 'px', 'em' => 'em', 'rem' => 'rem', '%' => '%')
        ));
        $wp_customize->add_setting('cct_search_font_size_mobile', array(
            'default'           => 14,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_setting('cct_search_font_size_unit_mobile', array(
            'default'           => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_font_size_mobile', array(
            'label'       => 'Tamanho da Fonte',
            'section'     => 'cct_search_styles_mobile',
            'type'        => 'number',
            'input_attrs' => array('min' => 1, 'step' => 1)
        ));
        $wp_customize->add_control('cct_search_font_size_unit_mobile', array(
            'label'       => '',
            'section'     => 'cct_search_styles_mobile',
            'type'        => 'select',
            'choices'     => array('px' => 'px', 'em' => 'em', 'rem' => 'rem', '%' => '%')
        ));

        // Mobile - Padding do campo de busca (shorthand)
        $wp_customize->add_setting('cct_search_input_padding_mobile', array(
            'default'           => '2 12 2 12',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_input_padding_mobile', array(
            'label'       => 'Padding do campo de busca (top right bottom left)',
            'section'     => 'cct_search_styles_mobile',
            'type'        => 'text',
            'input_attrs' => array('placeholder' => '10 12 10 12')
        ));

        // === BOTÃO: ALTURA E PADDING (POR DISPOSITIVO) ===
        // Desktop - Altura
        $wp_customize->add_setting('cct_search_button_height_desktop', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_setting('cct_search_button_height_unit_desktop', array(
            'default'           => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_button_height_desktop', array(
            'label'       => 'Altura do Botão',
            'section'     => 'cct_search_styles_desktop',
            'type'        => 'number',
            'input_attrs' => array('min' => 0, 'step' => 1)
        ));
        $wp_customize->add_control('cct_search_button_height_unit_desktop', array(
            'label'       => '',
            'section'     => 'cct_search_styles_desktop',
            'type'        => 'select',
            'choices'     => array('px' => 'px', 'em' => 'em', 'rem' => 'rem')
        ));

        // Desktop - Padding shorthand (top right bottom left)
        $wp_customize->add_setting('cct_search_button_padding_desktop', array(
            'default'           => '14 18 14 18',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_button_padding_desktop', array(
            'label'       => 'Padding (top right bottom left)',
            'section'     => 'cct_search_styles_desktop',
            'type'        => 'text',
            'input_attrs' => array('placeholder' => '14 18 14 18')
        ));
        // removido: unidade separada para padding (o usuário digita as unidades no próprio campo)

        // Tablet - Altura
        $wp_customize->add_setting('cct_search_button_height_tablet', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_setting('cct_search_button_height_unit_tablet', array(
            'default'           => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_button_height_tablet', array(
            'label'       => 'Altura do Botão',
            'section'     => 'cct_search_styles_tablet',
            'type'        => 'number',
            'input_attrs' => array('min' => 0, 'step' => 1)
        ));
        $wp_customize->add_control('cct_search_button_height_unit_tablet', array(
            'label'       => '',
            'section'     => 'cct_search_styles_tablet',
            'type'        => 'select',
            'choices'     => array('px' => 'px', 'em' => 'em', 'rem' => 'rem')
        ));

        // Tablet - Padding shorthand
        $wp_customize->add_setting('cct_search_button_padding_tablet', array(
            'default'           => '14 18 14 18',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_button_padding_tablet', array(
            'label'       => 'Padding (top right bottom left)',
            'section'     => 'cct_search_styles_tablet',
            'type'        => 'text',
            'input_attrs' => array('placeholder' => '14 18 14 18')
        ));
        // removido: unidade separada para padding (o usuário digita as unidades no próprio campo)

        // Mobile - Altura
        $wp_customize->add_setting('cct_search_button_height_mobile', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_setting('cct_search_button_height_unit_mobile', array(
            'default'           => 'px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_button_height_mobile', array(
            'label'       => 'Altura do Botão',
            'section'     => 'cct_search_styles_mobile',
            'type'        => 'number',
            'input_attrs' => array('min' => 0, 'step' => 1)
        ));
        $wp_customize->add_control('cct_search_button_height_unit_mobile', array(
            'label'       => '',
            'section'     => 'cct_search_styles_mobile',
            'type'        => 'select',
            'choices'     => array('px' => 'px', 'em' => 'em', 'rem' => 'rem')
        ));

        // Mobile - Padding shorthand
        $wp_customize->add_setting('cct_search_button_padding_mobile', array(
            'default'           => '14 18 14 18',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('cct_search_button_padding_mobile', array(
            'label'       => 'Padding (top right bottom left)',
            'section'     => 'cct_search_styles_mobile',
            'type'        => 'text',
            'input_attrs' => array('placeholder' => '14 18 14 18')
        ));
        // removido: unidade separada para padding (o usuário digita as unidades no próprio campo)
        
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
            )
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
        wp_add_inline_script('cct-search-customizer', <<<'JS'
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
        // Resultados da busca: tamanho do resumo
        "cct_search_results_excerpt_length": " palavras",
        "cct_search_retractable_button_font_size": "px",
        "cct_search_retractable_icon_size": "px",
        // Novos controles responsivos
        "cct_search_width_desktop": "px",
        "cct_search_font_size_desktop": "px",
        "cct_search_width_tablet": "%",
        "cct_search_font_size_tablet": "px",
        "cct_search_width_mobile": "%",
        "cct_search_font_size_mobile": "px"
    };

    // Configurar cada setting
    $.each(rangeSettings, function(setting, suffix) {
        if (wp.customize(setting)) {
            var initialValue = wp.customize(setting).get();
            updateRangeValue(setting, initialValue, suffix);
            wp.customize(setting, function(value) {
                value.bind(function(newval) { updateRangeValue(setting, newval, suffix); });
            });
        }
        // Fallback para sliders (mantido por compatibilidade)
        var controlSelector = "#customize-control-" + setting + " input[type=\"range\"]";
        var $rangeControl = jQuery(controlSelector);
        if ($rangeControl.length) {
            updateRangeValue(setting, $rangeControl.val(), suffix);
            $rangeControl.on("input change", function() {
                updateRangeValue(setting, jQuery(this).val(), suffix);
            });
        }
    });

    // Atualizar rótulos de unidade (unit-value)
    var unitSettings = [
        'cct_search_width_unit_desktop',
        'cct_search_font_size_unit_desktop',
        'cct_search_width_unit_tablet',
        'cct_search_font_size_unit_tablet',
        'cct_search_width_unit_mobile',
        'cct_search_font_size_unit_mobile'
    ];

    function updateUnitLabel(settingId) {
        if (wp.customize(settingId)) {
            var val = wp.customize(settingId).get();
            $(".unit-value[data-setting='" + settingId + "']").text(val);
        }
    }

    unitSettings.forEach(function(s){
        updateUnitLabel(s);
        if (wp.customize(s)) {
            wp.customize(s, function(value){
                value.bind(function(){ updateUnitLabel(s); });
            });
        }
    });

    // Controle numérico (postMessage): tamanho do resumo dos resultados
    var $excerptNumber = jQuery('#customize-control-cct_search_results_excerpt_length input[type="number"]');
    if ($excerptNumber.length && typeof wp.customize === 'function') {
        var excerptSetting = wp.customize('cct_search_results_excerpt_length');
        if (excerptSetting) {
            // Atualizar indicador inicial no painel
            var initVal = excerptSetting.get();
            if (typeof initVal !== 'undefined') {
                updateRangeValue('cct_search_results_excerpt_length', initVal, ' palavras');
            }
            // Enviar mudanças conforme digita
            $excerptNumber.on('input', function() {
                var val = parseInt(jQuery(this).val(), 10);
                if (isNaN(val) || val < 1) { val = 1; }
                excerptSetting.set(val);
            });
        }
    }

    // Ajuste do preview por dispositivo (sincroniza com botões do Customizer)
    try {
        if (wp && wp.customize && wp.customize.previewer) {
            wp.customize.previewer.bind('ready', function() {
                function setSizeFor(device) {
                    // Nota: o Customizer já cuida do tamanho, mantemos apenas uma sincronização leve
                    // Pode ser estendido para aplicar classes/data-attrs no preview se necessário
                }
                wp.customize.previewedDevice.bind(function(device){ setSizeFor(device); });
            });
        }
    } catch(e) {
        // silencioso
    }
});
JS
        );

        // Inline JS: mostrar apenas a seção do dispositivo ativo (Geral sempre visível)
        wp_add_inline_script('cct-search-customizer', <<<'JS'
(function($){
    function updateDeviceSections(device){
        var sections = {
            desktop: '#sub-accordion-section-cct_search_styles_desktop',
            tablet:  '#sub-accordion-section-cct_search_styles_tablet',
            mobile:  '#sub-accordion-section-cct_search_styles_mobile'
        };
        // Sempre manter a seção Geral visível
        var geral = '#sub-accordion-section-cct_search_customizer';
        $(geral).show();
        // Esconder todas as seções de dispositivo
        $.each(sections, function(_, sel){ $(sel).hide(); });
        // Mostrar a seção do dispositivo atual
        if (sections[device]) { $(sections[device]).show(); }
    }

    // Determinar dispositivo inicial
    var currentDevice = 'desktop';
    try {
        if (window.wp && wp.customize) {
            if (wp.customize.previewedDevice && typeof wp.customize.previewedDevice.get === 'function') {
                currentDevice = wp.customize.previewedDevice.get() || 'desktop';
            } else if (wp.customize.state && wp.customize.state('device')) {
                currentDevice = wp.customize.state('device').get() || 'desktop';
            }
        }
    } catch(e){}
    updateDeviceSections(currentDevice);

    // Ouvir mudanças do seletor de dispositivo
    try {
        if (wp && wp.customize) {
            if (wp.customize.previewedDevice && typeof wp.customize.previewedDevice.bind === 'function') {
                wp.customize.previewedDevice.bind(function(d){ updateDeviceSections(d); });
            } else if (wp.customize.state && wp.customize.state('device')) {
                wp.customize.state('device').bind(function(d){ updateDeviceSections(d); });
            }
        }
    } catch(e){}
})(jQuery);
JS
        );

        // Inline CSS: colocar campo de valor + select de unidade lado a lado (2 colunas)
        $inline_controls_css = "\n"
        . "#customize-control-cct_search_width_desktop,\n"
        . "#customize-control-cct_search_width_unit_desktop,\n"
        . "#customize-control-cct_search_font_size_desktop,\n"
        . "#customize-control-cct_search_font_size_unit_desktop,\n"
        . "#customize-control-cct_search_width_tablet,\n"
        . "#customize-control-cct_search_width_unit_tablet,\n"
        . "#customize-control-cct_search_font_size_tablet,\n"
        . "#customize-control-cct_search_font_size_unit_tablet,\n"
        . "#customize-control-cct_search_width_mobile,\n"
        . "#customize-control-cct_search_width_unit_mobile,\n"
        . "#customize-control-cct_search_font_size_mobile,\n"
        . "#customize-control-cct_search_font_size_unit_mobile {\n"
        . "  box-sizing:border-box;\n"
        . "  min-width:140px;\n"
        . "}\n"
        // primeira coluna com padding-right
        . "#customize-control-cct_search_width_desktop,\n"
        . "#customize-control-cct_search_font_size_desktop,\n"
        . "#customize-control-cct_search_width_tablet,\n"
        . "#customize-control-cct_search_font_size_tablet,\n"
        . "#customize-control-cct_search_width_mobile,\n"
        . "#customize-control-cct_search_font_size_mobile {\n"
        . "  float:left !important;\n"
        . "  width:calc(50% - 8px) !important;\n"
        . "  padding-right:8px;\n"
        . "  clear:both;\n"
        . "}\n"
        // segunda coluna com padding-left
        . "#customize-control-cct_search_width_unit_desktop,\n"
        . "#customize-control-cct_search_font_size_unit_desktop,\n"
        . "#customize-control-cct_search_width_unit_tablet,\n"
        . "#customize-control-cct_search_font_size_unit_tablet,\n"
        . "#customize-control-cct_search_width_unit_mobile,\n"
        . "#customize-control-cct_search_font_size_unit_mobile {\n"
        . "  float:left !important;\n"
        . "  width:calc(50% - 8px) !important;\n"
        . "  padding-left:8px;\n"
        . "  margin-top:26px; /* alinha com a linha do input ao lado (sem label) */\n"
        . "  clear:none;\n"
        . "}\n"
        // inputs ocupando toda a largura do item
        . "#customize-control-cct_search_width_desktop input,\n"
        . "#customize-control-cct_search_font_size_desktop input,\n"
        . "#customize-control-cct_search_width_tablet input,\n"
        . "#customize-control-cct_search_font_size_tablet input,\n"
        . "#customize-control-cct_search_width_mobile input,\n"
        . "#customize-control-cct_search_font_size_mobile input,\n"
        . "#customize-control-cct_search_width_unit_desktop select,\n"
        . "#customize-control-cct_search_font_size_unit_desktop select,\n"
        . "#customize-control-cct_search_width_unit_tablet select,\n"
        . "#customize-control-cct_search_font_size_unit_tablet select,\n"
        . "#customize-control-cct_search_width_unit_mobile select,\n"
        . "#customize-control-cct_search_font_size_unit_mobile select {\n"
        . "  width:100%;\n"
        . "}\n";

        // Removido: ocultação de títulos dos selects de unidade (labels já estão vazios)

        // Reduzir espaços verticais entre os dois controles da mesma linha
        $inline_controls_css .= "\n"
        . "#customize-control-cct_search_width_desktop,\n"
        . "#customize-control-cct_search_width_unit_desktop,\n"
        . "#customize-control-cct_search_font_size_desktop,\n"
        . "#customize-control-cct_search_font_size_unit_desktop,\n"
        . "#customize-control-cct_search_width_tablet,\n"
        . "#customize-control-cct_search_width_unit_tablet,\n"
        . "#customize-control-cct_search_font_size_tablet,\n"
        . "#customize-control-cct_search_font_size_unit_tablet,\n"
        . "#customize-control-cct_search_width_mobile,\n"
        . "#customize-control-cct_search_width_unit_mobile,\n"
        . "#customize-control-cct_search_font_size_mobile,\n"
        . "#customize-control-cct_search_font_size_unit_mobile {\n"
        . "  margin-bottom:8px;\n"
        . "}\n";

        // Adiciona CSS aos estilos do painel de controles
        wp_add_inline_style('customize-controls', $inline_controls_css);

        // Inline JS: agrupar pares valor+unidade em uma linha usando flex para garantir 2 colunas
        wp_add_inline_script('cct-search-customizer', <<<'JS'
(function($){
    function makeRow(valueId, unitId){
        var $val = $('#customize-control-' + valueId);
        var $unit = $('#customize-control-' + unitId);
        if (!$val.length || !$unit.length) return;
        // já agrupado?
        if ($val.parent().hasClass('cct-two-col-row') || $unit.parent().hasClass('cct-two-col-row')) return;
        // inserimos um wrapper logo antes do primeiro
        var $wrapper = $('<div class="cct-two-col-row"></div>');
        $wrapper.insertBefore($val);
        $wrapper.append($val).append($unit);
    }

    function setupRows(){
        makeRow('cct_search_width_desktop','cct_search_width_unit_desktop');
        makeRow('cct_search_font_size_desktop','cct_search_font_size_unit_desktop');
        makeRow('cct_search_button_height_desktop','cct_search_button_height_unit_desktop');
        makeRow('cct_search_width_tablet','cct_search_width_unit_tablet');
        makeRow('cct_search_font_size_tablet','cct_search_font_size_unit_tablet');
        makeRow('cct_search_button_height_tablet','cct_search_button_height_unit_tablet');
        makeRow('cct_search_width_mobile','cct_search_width_unit_mobile');
        makeRow('cct_search_font_size_mobile','cct_search_font_size_unit_mobile');
        makeRow('cct_search_button_height_mobile','cct_search_button_height_unit_mobile');
    }

    // estilos para a linha em flex (flex-grid)
    var style = document.createElement('style');
    style.innerHTML = "\n"
      + ".cct-two-col-row{ display:flex; gap:4px; align-items:center; margin-bottom:8px; flex-wrap:nowrap; }\n"
      + ".cct-two-col-row > li.customize-control{ flex:1 1 0; min-width:0; width:auto !important; float:none !important; clear:none !important; margin:0 !important; }\n"
      + ".cct-two-col-row > li.customize-control .customize-control-content{ margin-top:0 !important; }\n"
      + ".cct-two-col-row > li:not([id$='_unit_desktop']):not([id$='_unit_tablet']):not([id$='_unit_mobile']) input,\n"
      + ".cct-two-col-row > li:not([id$='_unit_desktop']):not([id$='_unit_tablet']):not([id$='_unit_mobile']) select{ width:100% !important; max-width:100% !important; height:30px; line-height:30px; box-sizing:border-box; }\n"
      + ".cct-two-col-row > li[id$='_unit_desktop'],\n"
      + ".cct-two-col-row > li[id$='_unit_tablet'],\n"
      + ".cct-two-col-row > li[id$='_unit_mobile']{ flex:0 0 40px; max-width:40px; }\n"
      + ".cct-two-col-row > li[id$='_unit_desktop'] select,\n"
      + ".cct-two-col-row > li[id$='_unit_tablet'] select,\n"
      + ".cct-two-col-row > li[id$='_unit_mobile'] select{ width:40px !important; max-width:40px !important; min-width:40px !important; height:30px; line-height:30px; box-sizing:border-box; }\n"
      + ".cct-two-col-row .customize-control-title{ margin-bottom:2px; }\n";
    document.head.appendChild(style);

    // executar ao abrir painel e ao mudar de seção
    $(document).ready(setupRows);
    wp.customize.bind('pane-contents-reflowed', setupRows);

    // sincronizar preview de dispositivo com a seção ativa (desktop/tablet/mobile)
    function bindDevicePreviewSync(){
        var sectionToDevice = {
            'cct_search_styles_desktop': 'desktop',
            'cct_search_styles_tablet': 'tablet',
            'cct_search_styles_mobile': 'mobile'
        };
        function setIfExpanded(){
            Object.keys(sectionToDevice).forEach(function(secId){
                var section = wp.customize.section(secId);
                if (section && section.expanded && section.expanded()) {
                    try { wp.customize.previewedDevice.set(sectionToDevice[secId]); } catch(e){}
                }
            });
        }
        Object.keys(sectionToDevice).forEach(function(secId){
            if (wp.customize.section(secId)) {
                wp.customize.section(secId, function(section){
                    section.expanded.bind(function(isExpanded){
                        if(isExpanded){
                            try { wp.customize.previewedDevice.set(sectionToDevice[secId]); } catch(e){}
                        }
                    });
                });
            }
        });
        // aplicar imediatamente para a seção que já estiver aberta (inclui Mobile)
        setTimeout(setIfExpanded, 0);
    }
    $(document).ready(bindDevicePreviewSync);
    wp.customize.bind('ready', bindDevicePreviewSync);

    // reforço: usar o estado global da seção expandida
    if (wp.customize.state && wp.customize.state('expandedSection')) {
        wp.customize.state('expandedSection').bind(function(section){
            if (!section) return;
            var id = section.id || (section.params && section.params.id);
            var map = {
                'cct_search_styles_desktop': 'desktop',
                'cct_search_styles_tablet': 'tablet',
                'cct_search_styles_mobile': 'mobile'
            };
            var device = map[id];
            if (device) {
                setTimeout(function(){
                    try { wp.customize.previewedDevice.set(device); } catch(e){}
                }, 50);
            }
        });
    }

    // fallback baseado no DOM (headers das seções)
    function bindDomHeaderClicks(){
        var mapSel = [
            { sel: '#accordion-section-cct_search_styles_desktop > h3, #sub-accordion-section-cct_search_styles_desktop > h3', device: 'desktop' },
            { sel: '#accordion-section-cct_search_styles_tablet > h3, #sub-accordion-section-cct_search_styles_tablet > h3', device: 'tablet' },
            { sel: '#accordion-section-cct_search_styles_mobile > h3, #sub-accordion-section-cct_search_styles_mobile > h3', device: 'mobile' }
        ];
        mapSel.forEach(function(m){
            $(document).on('click', m.sel, function(){
                setTimeout(function(){ try { wp.customize.previewedDevice.set(m.device); } catch(e){} }, 0);
            });
        });
    }
    $(document).ready(bindDomHeaderClicks);

    // === Observador de DOM (camada extra) ===
    function domDeviceSync(){
        var map = {
            'cct_search_styles_desktop': 'desktop',
            'cct_search_styles_tablet': 'tablet',
            'cct_search_styles_mobile': 'mobile'
        };
        function readExpandedFromDom(){
            var pairs = [
                { id: 'cct_search_styles_desktop', sel: '#accordion-section-cct_search_styles_desktop > h3, #sub-accordion-section-cct_search_styles_desktop > h3' },
                { id: 'cct_search_styles_tablet',  sel: '#accordion-section-cct_search_styles_tablet > h3, #sub-accordion-section-cct_search_styles_tablet > h3' },
                { id: 'cct_search_styles_mobile',  sel: '#accordion-section-cct_search_styles_mobile > h3, #sub-accordion-section-cct_search_styles_mobile > h3' }
            ];
            for (var i=0;i<pairs.length;i++){
                var $h = jQuery(pairs[i].sel).first();
                if ($h.length){
                    var expanded = ($h.attr('aria-expanded') === 'true') || $h.closest('[aria-expanded="true"], .open').length > 0;
                    if (expanded){
                        var dev = map[pairs[i].id];
                        try { wp.customize.previewedDevice.set(dev); } catch(e){}
                        return; // encontrou a seção aberta
                    }
                }
            }
        }
        // rodar logo após o load, e depois observar mudanças
        setTimeout(readExpandedFromDom, 100);
        setTimeout(readExpandedFromDom, 500);
        try {
            var root = document.querySelector('.wp-full-overlay-sidebar-content') || document.body;
            if (!root) return;
            var obs = new MutationObserver(function(muts){
                for (var i=0;i<muts.length;i++){
                    if (muts[i].type === 'attributes' || muts[i].type === 'childList'){
                        readExpandedFromDom();
                        break;
                    }
                }
            });
            obs.observe(root, { subtree: true, attributes: true, attributeFilter: ['aria-expanded','class'], childList: true });
        } catch(e){}
    }
    $(document).ready(domDeviceSync);

    // ===== Toolbar de dispositivos para melhorar a usabilidade =====
    function addDeviceToolbar(){
        var $toolbar = $(
            '<div class="cct-device-toolbar" role="group" aria-label="Alternar dispositivo">\n'
          + '  <button type="button" class="button device-btn" data-device="desktop">Desktop</button>\n'
          + '  <button type="button" class="button device-btn" data-device="tablet">Tablet</button>\n'
          + '  <button type="button" class="button device-btn" data-device="mobile">Mobile</button>\n'
          + '</div>'
        );
        // tentar inserir no topo do painel da extensão de busca, com fallbacks
        var targets = [
          '#sub-accordion-panel-cct_search_panel .panel-meta',
          '#sub-accordion-panel-cct_search_panel',
          '#accordion-panel-cct_search_panel',
          '.wp-full-overlay-sidebar-content' // fallback genérico
        ];
        var inserted = false;
        targets.some(function(sel){
          var $t = $(sel).first();
          if ($t.length){ $t.prepend($toolbar); inserted = true; return true; }
          return false;
        });
        if (!inserted){
          // último recurso: antes da primeira seção de desktop
          var $sec = $('#accordion-section-cct_search_styles_desktop, #sub-accordion-section-cct_search_styles_desktop').first();
          if ($sec.length){ $sec.before($toolbar); }
        }

        // estilos mínimos
        var style = document.createElement('style');
        style.innerHTML = '\n'
          + '.cct-device-toolbar{ display:flex; gap:6px; padding:8px 10px; position:sticky; top:0; background:#f6f7f7; z-index:5; border-bottom:1px solid #ddd; }\n'
          + '.cct-device-toolbar .device-btn{ min-width:72px; height:28px; line-height:26px; }\n'
          + '.cct-device-toolbar .device-btn.is-active{ background:#2271b1; color:#fff; border-color:#1b5a8d; }\n';
        document.head.appendChild(style);

        // bind de clique
        $(document).on('click', '.cct-device-toolbar .device-btn', function(){
            var device = $(this).data('device');
            try { wp.customize.previewedDevice.set(device); } catch(e){}
        });

        // refletir estado ativo
        function markActive(dev){
            $('.cct-device-toolbar .device-btn').removeClass('is-active');
            $('.cct-device-toolbar .device-btn[data-device="'+dev+'"]').addClass('is-active');
        }
        try {
            if (wp.customize.previewedDevice){
                markActive(wp.customize.previewedDevice());
                wp.customize.previewedDevice.bind(function(dev){ markActive(dev); });
            }
        } catch(e){}
    }
    $(document).ready(addDeviceToolbar);
})(jQuery);
JS
        );
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
        $button_border_radius_top_right = get_theme_mod('cct_search_button_border_radius_top_right', 25);
        $button_border_radius_bottom_left = get_theme_mod('cct_search_button_border_radius_bottom_left', 0);
        $button_border_radius_bottom_right = get_theme_mod('cct_search_button_border_radius_bottom_right', 25);
        
        // Border radius (usando configurações globais como fallback)
        $form_border_radius = get_theme_mod('form_border_radius', '4px');
        
        // Efeitos
        $box_shadow = get_theme_mod('cct_search_box_shadow', false);
        $transitions = get_theme_mod('cct_search_transitions', true);
        
        // CSS personalizado
        $custom_css = get_theme_mod('cct_search_custom_css', '');
        
        $css .= "\n/* Sistema de Busca - Customizer */\n";
        
        // Container (max-width controlado nas regras responsivas por dispositivo)
        $css .= ".search-container.search-custom-uenf {\n";
        $css .= "}\n";
        
        // Campo de busca
        $css .= ".search-container.search-custom-uenf input[type='search'].search-custom-uenf {\n";
        $css .= "    width: calc(100% - 50px) !important;\n"; // Largura dinâmica menos botão
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
        $css .= ".search-container.search-custom-uenf .search-submit.search-custom-uenf,\n";
        $css .= "input[type='submit'].search-custom-uenf,\n";
        $css .= "button[type='submit'].search-custom-uenf,\n";
        $css .= ".search-submit.search-custom-uenf {\n";
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
        $css .= ".search-container.search-custom-uenf .search-submit.search-custom-uenf:hover,\n";
        $css .= "input[type='submit'].search-custom-uenf:hover,\n";
        $css .= "button[type='submit'].search-custom-uenf:hover,\n";
        $css .= ".search-submit.search-custom-uenf:hover {\n";
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
        
        // Responsivo: valores por dispositivo
        $w_desktop = get_theme_mod('cct_search_width_desktop', 300);
        $wu_desktop = get_theme_mod('cct_search_width_unit_desktop', 'px');
        $fs_desktop = get_theme_mod('cct_search_font_size_desktop', 16);
        $fsu_desktop = get_theme_mod('cct_search_font_size_unit_desktop', 'px');
        $ipd_raw = trim(get_theme_mod('cct_search_input_padding_desktop', '10 12 10 12'));
        $ipd = preg_match('/[a-z%]/i', $ipd_raw) ? $ipd_raw : preg_replace('/\b(\d+)\b/', '$1px', $ipd_raw);

        $w_tablet = get_theme_mod('cct_search_width_tablet', 90);
        $wu_tablet = get_theme_mod('cct_search_width_unit_tablet', '%');
        $fs_tablet = get_theme_mod('cct_search_font_size_tablet', 15);
        $fsu_tablet = get_theme_mod('cct_search_font_size_unit_tablet', 'px');
        $ipt_raw = trim(get_theme_mod('cct_search_input_padding_tablet', '10 12 10 12'));
        $ipt = preg_match('/[a-z%]/i', $ipt_raw) ? $ipt_raw : preg_replace('/\b(\d+)\b/', '$1px', $ipt_raw);

        $w_mobile = get_theme_mod('cct_search_width_mobile', 100);
        $wu_mobile = get_theme_mod('cct_search_width_unit_mobile', '%');
        $fs_mobile = get_theme_mod('cct_search_font_size_mobile', 14);
        $fsu_mobile = get_theme_mod('cct_search_font_size_unit_mobile', 'px');
        $ipm_raw = trim(get_theme_mod('cct_search_input_padding_mobile', '10 12 10 12'));
        $ipm = preg_match('/[a-z%]/i', $ipm_raw) ? $ipm_raw : preg_replace('/\b(\d+)\b/', '$1px', $ipm_raw);

        $css .= "\n/* Dimensões por dispositivo */\n";
        // Desktop (>=993px)
        $css .= "@media (min-width: 993px) {\n";
        $css .= "  .search-container.search-custom-uenf { max-width: {$w_desktop}{$wu_desktop}; }\n";
        $css .= "  .search-container.search-custom-uenf input[type='search'].search-custom-uenf { font-size: {$fs_desktop}{$fsu_desktop}; padding: {$ipd} !important; }\n";
        $css .= "  .search-container.search-custom-uenf .search-submit.search-custom-uenf { font-size: {$fs_desktop}{$fsu_desktop}; }\n";
        // Desktop: padding shorthand e altura do botão
        $bh_d = get_theme_mod('cct_search_button_height_desktop', 0);
        $bhu_d = get_theme_mod('cct_search_button_height_unit_desktop', 'px');
        $pad_d_raw = trim(get_theme_mod('cct_search_button_padding_desktop', '14 18 14 18'));
        $pad_d = preg_match('/[a-z%]/i', $pad_d_raw) ? $pad_d_raw : preg_replace('/\b(\d+)\b/', '$1px', $pad_d_raw);
        $css .= "  .search-retractable-toggle-inline.search-custom-uenf,\n";
        $css .= "  .search-retractable-form .search-submit.search-custom-uenf,\n";
        $css .= "  .search-retractable-form-inline .search-submit.search-custom-uenf {\n";
        $css .= "    padding: {$pad_d} !important;\n";
        if ((int)$bh_d > 0) { $css .= "    min-height: {$bh_d}{$bhu_d} !important;\n"; }
        $css .= "  }\n";
        if ((int)$bh_d > 0) {
            $css .= "  .search-container.search-custom-uenf input[type='search'].search-custom-uenf { min-height: {$bh_d}{$bhu_d} !important; }\n";
        }
        $css .= "}\n";
        // Tablet (577px-992px)
        $css .= "@media (min-width: 577px) and (max-width: 992px) {\n";
        $css .= "  .search-container.search-custom-uenf { max-width: {$w_tablet}{$wu_tablet}; }\n";
        $css .= "  .search-container.search-custom-uenf input[type='search'].search-custom-uenf { font-size: {$fs_tablet}{$fsu_tablet}; padding: {$ipt} !important; }\n";
        $css .= "  .search-container.search-custom-uenf .search-submit.search-custom-uenf { font-size: {$fs_tablet}{$fsu_tablet}; }\n";
        // Tablet: padding shorthand e altura do botão
        $bh_t = get_theme_mod('cct_search_button_height_tablet', 0);
        $bhu_t = get_theme_mod('cct_search_button_height_unit_tablet', 'px');
        $pad_t_raw = trim(get_theme_mod('cct_search_button_padding_tablet', '14 18 14 18'));
        $pad_t = preg_match('/[a-z%]/i', $pad_t_raw) ? $pad_t_raw : preg_replace('/\b(\d+)\b/', '$1px', $pad_t_raw);
        $css .= "  .search-retractable-toggle-inline.search-custom-uenf,\n";
        $css .= "  .search-retractable-form .search-submit.search-custom-uenf,\n";
        $css .= "  .search-retractable-form-inline .search-submit.search-custom-uenf {\n";
        $css .= "    padding: {$pad_t} !important;\n";
        if ((int)$bh_t > 0) { $css .= "    min-height: {$bh_t}{$bhu_t} !important;\n"; }
        $css .= "  }\n";
        if ((int)$bh_t > 0) {
            $css .= "  .search-container.search-custom-uenf input[type='search'].search-custom-uenf { min-height: {$bh_t}{$bhu_t} !important; }\n";
        }
        $css .= "}\n";
        // Mobile (<=576px)
        $css .= "@media (max-width: 576px) {\n";
        $css .= "  .search-container.search-custom-uenf { max-width: {$w_mobile}{$wu_mobile}; }\n";
        $css .= "  .search-container.search-custom-uenf input[type='search'].search-custom-uenf { font-size: {$fs_mobile}{$fsu_mobile}; padding: {$ipm} !important; }\n";
        $css .= "  .search-container.search-custom-uenf .search-submit.search-custom-uenf { font-size: {$fs_mobile}{$fsu_mobile}; }\n";
        // Mobile: padding shorthand e altura do botão
        $bh_m = get_theme_mod('cct_search_button_height_mobile', 0);
        $bhu_m = get_theme_mod('cct_search_button_height_unit_mobile', 'px');
        $pad_m_raw = trim(get_theme_mod('cct_search_button_padding_mobile', '14 18 14 18'));
        $pad_m = preg_match('/[a-z%]/i', $pad_m_raw) ? $pad_m_raw : preg_replace('/\b(\d+)\b/', '$1px', $pad_m_raw);
        $css .= "  .search-retractable-toggle-inline.search-custom-uenf,\n";
        $css .= "  .search-retractable-form .search-submit.search-custom-uenf,\n";
        $css .= "  .search-retractable-form-inline .search-submit.search-custom-uenf {\n";
        $css .= "    padding: {$pad_m} !important;\n";
        if ((int)$bh_m > 0) { $css .= "    min-height: {$bh_m}{$bhu_m} !important;\n"; }
        $css .= "  }\n";
        if ((int)$bh_m > 0) {
            $css .= "  .search-container.search-custom-uenf input[type='search'].search-custom-uenf { min-height: {$bh_m}{$bhu_m} !important; }\n";
        }
        $css .= "}\n";

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