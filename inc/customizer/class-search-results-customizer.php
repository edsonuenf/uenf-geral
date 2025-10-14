<?php
/**
 * Seção do Customizer: Resultados da Busca
 *
 * Adiciona controles para personalizar a exibição dos resultados de busca
 *
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class CCT_Search_Results_Customizer {
    /**
     * Inicializar controles
     */
    public static function init() {
        add_action('customize_register', array(__CLASS__, 'register_controls'));
    }

    /**
     * Registrar controles no Customizer
     */
    public static function register_controls($wp_customize) {
        // Seção de Resultados da Busca
        $wp_customize->add_section('cct_search_results', array(
            'title'       => 'Resultados da Busca',
            'description' => 'Configure como os resultados de busca são exibidos.',
            'priority'    => 34,
        ));

        // Mostrar miniaturas
        $wp_customize->add_setting('cct_search_results_show_thumbnail', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        ));
        $wp_customize->add_control('cct_search_results_show_thumbnail', array(
            'label'       => 'Mostrar miniaturas',
            'description' => 'Exibe a imagem destacada quando disponível.',
            'section'     => 'cct_search_results',
            'type'        => 'checkbox',
        ));

        // Mostrar meta (tipo, data, autor)
        $wp_customize->add_setting('cct_search_results_show_meta', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        ));
        $wp_customize->add_control('cct_search_results_show_meta', array(
            'label'       => 'Mostrar meta (tipo, data, autor)',
            'section'     => 'cct_search_results',
            'type'        => 'checkbox',
        ));

        // Destacar termos buscados
        $wp_customize->add_setting('cct_search_results_highlight_terms', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        ));
        $wp_customize->add_control('cct_search_results_highlight_terms', array(
            'label'       => 'Destacar termos buscados',
            'description' => 'Destaca o termo buscado no título e resumo.',
            'section'     => 'cct_search_results',
            'type'        => 'checkbox',
        ));

        // Cor de fundo do destaque
        $wp_customize->add_setting('cct_search_results_highlight_bg_color', array(
            'default'           => '#ededc7',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_highlight_bg_color', array(
            'label'       => 'Cor de fundo do destaque',
            'description' => 'Define a cor de fundo dos termos destacados.',
            'section'     => 'cct_search_results',
        )));

        // Cor do texto do destaque
        $wp_customize->add_setting('cct_search_results_highlight_text_color', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_highlight_text_color', array(
            'label'       => 'Cor do texto do destaque',
            'description' => 'Opcionalmente, defina a cor do texto dos termos destacados.',
            'section'     => 'cct_search_results',
        )));

        // Peso da fonte do destaque
        $wp_customize->add_setting('cct_search_results_highlight_font_weight', array(
            'default'           => '700',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_highlight_font_weight', array(
            'label'       => 'Peso da fonte do destaque',
            'description' => 'Ajusta o peso (espessura) da fonte nos termos destacados.',
            'section'     => 'cct_search_results',
            'type'        => 'select',
            'choices'     => array(
                'normal' => 'Normal',
                '500'    => 'Médio (500)',
                '600'    => 'Seminegrito (600)',
                '700'    => 'Negrito (700)',
                'bold'   => 'Bold',
            ),
        ));

        // Tamanho do resumo (número de palavras)
        $wp_customize->add_setting('cct_search_results_excerpt_length', array(
            'default'           => 20,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_excerpt_length', array(
            'label'       => 'Tamanho do resumo',
            'description' => 'Informe o número de palavras exibidas no resumo: <span class="range-value" data-setting="cct_search_results_excerpt_length">20</span> palavras',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 200,
                'step' => 1,
            ),
        ));

        // Estilos do botão "Nova Busca" (estado normal)
        $wp_customize->add_setting('cct_search_results_new_search_button_bg_color', array(
            'default'           => '#1e73be',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_new_search_button_bg_color', array(
            'label'       => 'Nova Busca — cor de fundo',
            'description' => 'Cor de fundo do botão "Nova Busca" na listagem de resultados.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_new_search_button_text_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_new_search_button_text_color', array(
            'label'       => 'Nova Busca — cor do texto',
            'description' => 'Cor do texto/ícone do botão "Nova Busca".',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_new_search_button_border_color', array(
            'default'           => '#0d6efd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_new_search_button_border_color', array(
            'label'       => 'Nova Busca — cor da borda',
            'description' => 'Cor da borda no estado normal.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_new_search_button_border_width', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_new_search_button_border_width', array(
            'label'       => 'Nova Busca — largura da borda (px)',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 10,
                'step' => 1,
            ),
        ));

        // Raio da borda (border-radius)
        $wp_customize->add_setting('cct_search_results_new_search_button_border_radius', array(
            'default'           => 25,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_new_search_button_border_radius', array(
            'label'       => 'Nova Busca — raio da borda (px)',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ),
        ));

        // Estilos do botão "Nova Busca" (hover)
        $wp_customize->add_setting('cct_search_results_new_search_button_hover_bg_color', array(
            'default'           => '#152a5a',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_new_search_button_hover_bg_color', array(
            'label'       => 'Nova Busca — cor de fundo (hover)',
            'description' => 'Cor de fundo ao passar o mouse.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_new_search_button_hover_text_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_new_search_button_hover_text_color', array(
            'label'       => 'Nova Busca — cor do texto (hover)',
            'description' => 'Cor do texto/ícone ao passar o mouse.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_new_search_button_hover_border_color', array(
            'default'           => '#0d6efd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_new_search_button_hover_border_color', array(
            'label'       => 'Nova Busca — cor da borda (hover)',
            'description' => 'Cor da borda ao passar o mouse.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_new_search_button_hover_border_width', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_new_search_button_hover_border_width', array(
            'label'       => 'Nova Busca — largura da borda (hover, px)',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 10,
                'step' => 1,
            ),
        ));

        // ===== Botão "Ler mais" (estado normal) =====
        $wp_customize->add_setting('cct_search_results_read_more_button_bg_color', array(
            'default'           => '#152a5a',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_read_more_button_bg_color', array(
            'label'       => 'Ler mais — cor de fundo',
            'description' => 'Cor de fundo do botão "Ler mais" na listagem de resultados.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_read_more_button_text_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_read_more_button_text_color', array(
            'label'       => 'Ler mais — cor do texto',
            'description' => 'Cor do texto/ícone do botão "Ler mais".',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_read_more_button_border_color', array(
            'default'           => '#0d6efd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_read_more_button_border_color', array(
            'label'       => 'Ler mais — cor da borda',
            'description' => 'Cor da borda no estado normal.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_read_more_button_border_width', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_read_more_button_border_width', array(
            'label'       => 'Ler mais — largura da borda (px)',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 10,
                'step' => 1,
            ),
        ));

        $wp_customize->add_setting('cct_search_results_read_more_button_border_radius', array(
            'default'           => 25,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_read_more_button_border_radius', array(
            'label'       => 'Ler mais — raio da borda (px)',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ),
        ));

        // ===== Botão "Ler mais" (hover) =====
        $wp_customize->add_setting('cct_search_results_read_more_button_hover_bg_color', array(
            'default'           => '#1e73be',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_read_more_button_hover_bg_color', array(
            'label'       => 'Ler mais — cor de fundo (hover)',
            'description' => 'Cor de fundo ao passar o mouse.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_read_more_button_hover_text_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_read_more_button_hover_text_color', array(
            'label'       => 'Ler mais — cor do texto (hover)',
            'description' => 'Cor do texto/ícone ao passar o mouse.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_read_more_button_hover_border_color', array(
            'default'           => '#0d6efd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_read_more_button_hover_border_color', array(
            'label'       => 'Ler mais — cor da borda (hover)',
            'description' => 'Cor da borda ao passar o mouse.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_read_more_button_hover_border_width', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_read_more_button_hover_border_width', array(
            'label'       => 'Ler mais — largura da borda (hover, px)',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 10,
                'step' => 1,
            ),
        ));

        // ===== Botão "Link" (copiar/abrir) =====
        // Estado normal
        $wp_customize->add_setting('cct_search_results_link_button_bg_color', array(
            'default'           => '#afb8bf',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_link_button_bg_color', array(
            'label'       => 'Botão de link — cor de fundo',
            'description' => 'Cor de fundo do botão de link (copiar/abrir).',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_link_button_icon_color', array(
            'default'           => '#000000',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_link_button_icon_color', array(
            'label'       => 'Botão de link — cor do ícone',
            'description' => 'Cor do ícone dentro do botão de link.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_link_button_border_color', array(
            'default'           => '#0d6efd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_link_button_border_color', array(
            'label'       => 'Botão de link — cor da borda',
            'description' => 'Cor da borda no estado normal.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_link_button_border_width', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_link_button_border_width', array(
            'label'       => 'Botão de link — largura da borda (px)',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 10,
                'step' => 1,
            ),
        ));

        $wp_customize->add_setting('cct_search_results_link_button_border_radius', array(
            'default'           => 25,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_link_button_border_radius', array(
            'label'       => 'Botão de link — raio da borda (px)',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ),
        ));

        // Hover
        $wp_customize->add_setting('cct_search_results_link_button_hover_bg_color', array(
            'default'           => '#0d6efd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_link_button_hover_bg_color', array(
            'label'       => 'Botão de link — cor de fundo (hover)',
            'description' => 'Cor de fundo ao passar o mouse.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_link_button_hover_icon_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_link_button_hover_icon_color', array(
            'label'       => 'Botão de link — cor do ícone (hover)',
            'description' => 'Cor do ícone ao passar o mouse.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_link_button_hover_border_color', array(
            'default'           => '#0d6efd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_results_link_button_hover_border_color', array(
            'label'       => 'Botão de link — cor da borda (hover)',
            'description' => 'Cor da borda ao passar o mouse.',
            'section'     => 'cct_search_results',
        )));

        $wp_customize->add_setting('cct_search_results_link_button_hover_border_width', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('cct_search_results_link_button_hover_border_width', array(
            'label'       => 'Botão de link — largura da borda (hover, px)',
            'section'     => 'cct_search_results',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 10,
                'step' => 1,
            ),
        ));
    }

    /**
     * Gerar CSS dinâmico para destaque de termos
     */
    public static function generate_css() {
        $bg   = get_theme_mod('cct_search_results_highlight_bg_color', '#ededc7');
        $fg   = get_theme_mod('cct_search_results_highlight_text_color', '');
        $wght = get_theme_mod('cct_search_results_highlight_font_weight', '700');

        $css = '';
        if (!empty($bg) || !empty($wght)) {
            $css .= "mark.cct-highlight, .cct-highlight {\n";
            if (!empty($bg)) {
                $css .= "    background-color: {$bg} !important;\n";
            }
            if (!empty($wght)) {
                $css .= "    font-weight: {$wght} !important;\n";
            }
            if (!empty($fg)) {
                $css .= "    color: {$fg} !important;\n";
            } else {
                $css .= "    color: inherit;\n";
            }
            $css .= "    padding: 0 .15em;\n";
            $css .= "    border-radius: 2px;\n";
            $css .= "}\n";
        }

        // CSS para o botão "Nova Busca" nos resultados
        $ns_bg         = get_theme_mod('cct_search_results_new_search_button_bg_color', '#1e73be');
        $ns_text       = get_theme_mod('cct_search_results_new_search_button_text_color', '#ffffff');
        $ns_border     = get_theme_mod('cct_search_results_new_search_button_border_color', '#0d6efd');
        $ns_border_w   = absint(get_theme_mod('cct_search_results_new_search_button_border_width', 0));
        $ns_border_r   = absint(get_theme_mod('cct_search_results_new_search_button_border_radius', 25));

        $ns_hover_bg   = get_theme_mod('cct_search_results_new_search_button_hover_bg_color', '#152a5a');
        $ns_hover_text = get_theme_mod('cct_search_results_new_search_button_hover_text_color', '#ffffff');
        $ns_hover_bord = get_theme_mod('cct_search_results_new_search_button_hover_border_color', '#0d6efd');
        $ns_hover_bw   = absint(get_theme_mod('cct_search_results_new_search_button_hover_border_width', 0));

        $css .= ".search-actions .new-search-btn, .result-actions .new-search-btn{\n";
        $css .= "    background-color: {$ns_bg} !important;\n";
        $css .= "    color: {$ns_text} !important;\n";
        $css .= "    border-color: {$ns_border} !important;\n";
        $css .= "    border-width: {$ns_border_w}px !important;\n";
        $css .= "    border-radius: {$ns_border_r}px !important;\n";
        $css .= "}\n";
        // Ícone dentro do botão
        $css .= ".search-actions .new-search-btn i, .result-actions .new-search-btn i{\n";
        $css .= "    color: {$ns_text} !important;\n";
        $css .= "}\n";

        $css .= ".search-actions .new-search-btn:hover, .result-actions .new-search-btn:hover{\n";
        $css .= "    background-color: {$ns_hover_bg} !important;\n";
        $css .= "    color: {$ns_hover_text} !important;\n";
        $css .= "    border-color: {$ns_hover_bord} !important;\n";
        $css .= "    border-width: {$ns_hover_bw}px !important;\n";
        $css .= "}\n";
        // Ícone no hover
        $css .= ".search-actions .new-search-btn:hover i, .result-actions .new-search-btn:hover i{\n";
        $css .= "    color: {$ns_hover_text} !important;\n";
        $css .= "}\n";

        // CSS para o botão "Ler mais" nos resultados (independente)
        $rm_bg         = get_theme_mod('cct_search_results_read_more_button_bg_color', '#152a5a');
        $rm_text       = get_theme_mod('cct_search_results_read_more_button_text_color', '#ffffff');
        $rm_border     = get_theme_mod('cct_search_results_read_more_button_border_color', '#0d6efd');
        $rm_border_w   = absint(get_theme_mod('cct_search_results_read_more_button_border_width', 0));
        $rm_border_r   = absint(get_theme_mod('cct_search_results_read_more_button_border_radius', 25));

        $rm_hover_bg   = get_theme_mod('cct_search_results_read_more_button_hover_bg_color', '#1e73be');
        $rm_hover_text = get_theme_mod('cct_search_results_read_more_button_hover_text_color', '#ffffff');
        $rm_hover_bord = get_theme_mod('cct_search_results_read_more_button_hover_border_color', '#0d6efd');
        $rm_hover_bw   = absint(get_theme_mod('cct_search_results_read_more_button_hover_border_width', 0));

        $css .= ".result-actions .read-more-btn{\n";
        $css .= "    background-color: {$rm_bg} !important;\n";
        $css .= "    color: {$rm_text} !important;\n";
        $css .= "    border-color: {$rm_border} !important;\n";
        $css .= "    border-width: {$rm_border_w}px !important;\n";
        $css .= "    border-radius: {$rm_border_r}px !important;\n";
        $css .= "}\n";
        $css .= ".result-actions .read-more-btn i{\n";
        $css .= "    color: {$rm_text} !important;\n";
        $css .= "}\n";

        $css .= ".result-actions .read-more-btn:hover{\n";
        $css .= "    background-color: {$rm_hover_bg} !important;\n";
        $css .= "    color: {$rm_hover_text} !important;\n";
        $css .= "    border-color: {$rm_hover_bord} !important;\n";
        $css .= "    border-width: {$rm_hover_bw}px !important;\n";
        $css .= "}\n";
        $css .= ".result-actions .read-more-btn:hover i{\n";
        $css .= "    color: {$rm_hover_text} !important;\n";
        $css .= "}\n";

        // CSS para o botão de link (copiar/abrir)
        $lk_bg       = get_theme_mod('cct_search_results_link_button_bg_color', '#afb8bf');
        $lk_icon     = get_theme_mod('cct_search_results_link_button_icon_color', '#000000');
        $lk_border   = get_theme_mod('cct_search_results_link_button_border_color', '#0d6efd');
        $lk_border_w = absint(get_theme_mod('cct_search_results_link_button_border_width', 0));
        $lk_border_r = absint(get_theme_mod('cct_search_results_link_button_border_radius', 25));

        $css .= ".result-actions .copy-link-btn{\n";
        $css .= "    background-color: {$lk_bg} !important;\n";
        $css .= "    border-color: {$lk_border} !important;\n";
        $css .= "    border-width: {$lk_border_w}px !important;\n";
        $css .= "    border-radius: {$lk_border_r}px !important;\n";
        $css .= "}\n";
        // Cor do ícone dentro do botão
        $css .= ".result-actions .copy-link-btn i{\n";
        $css .= "    color: {$lk_icon} !important;\n";
        $css .= "}\n";

        // Foco sem borda por padrão no botão de link
        $css .= ".result-actions .copy-link-btn:focus{\n";
        $css .= "    outline: none !important;\n";
        $css .= "    border-width: 0 !important;\n";
        $css .= "    border-color: transparent !important;\n";
        $css .= "    box-shadow: none !important;\n";
        $css .= "}\n";

        $lk_hover_bg     = get_theme_mod('cct_search_results_link_button_hover_bg_color', '#0d6efd');
        $lk_hover_icon   = get_theme_mod('cct_search_results_link_button_hover_icon_color', '#ffffff');
        $lk_hover_border = get_theme_mod('cct_search_results_link_button_hover_border_color', '#0d6efd');
        $lk_hover_bw     = absint(get_theme_mod('cct_search_results_link_button_hover_border_width', 0));

        $css .= ".result-actions .copy-link-btn:hover{\n";
        $css .= "    background-color: {$lk_hover_bg} !important;\n";
        $css .= "    border-color: {$lk_hover_border} !important;\n";
        $css .= "    border-width: {$lk_hover_bw}px !important;\n";
        $css .= "}\n";
        $css .= ".result-actions .copy-link-btn:hover i{\n";
        $css .= "    color: {$lk_hover_icon} !important;\n";
        $css .= "}\n";

        return $css;
    }
}

// Inicializar
CCT_Search_Results_Customizer::init();