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
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_button_hover_color'
        )));
        
        // Cor da borda
        $wp_customize->add_setting('cct_search_border_color', array(
            'default' => '#ddd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cct_search_border_color', array(
            'label' => 'Cor da Borda',
            'section' => 'cct_search_customizer',
            'settings' => 'cct_search_border_color'
        )));
        
        // === DIMENSÕES ===
        
        // Altura (padding)
        $wp_customize->add_setting('cct_search_padding_vertical', array(
            'default' => 6,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_padding_vertical', array(
            'label' => 'Altura (Padding Vertical)',
            'description' => 'Valor em pixels: <span class="range-value" data-setting="cct_search_padding_vertical">6</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 2,
                'max' => 20,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // Largura máxima
        $wp_customize->add_setting('cct_search_max_width', array(
            'default' => 300,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_max_width', array(
            'label' => 'Largura Máxima',
            'description' => 'Valor em pixels: <span class="range-value" data-setting="cct_search_max_width">300</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 200,
                'max' => 500,
                'step' => 10,
                'data-value-display' => 'px'
            )
        ));
        
        // === BORDAS ===
        
        // Raio das bordas
        $wp_customize->add_setting('cct_search_border_radius', array(
            'default' => 4,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_border_radius', array(
            'label' => 'Raio das Bordas',
            'description' => 'Valor em pixels: <span class="range-value" data-setting="cct_search_border_radius">4</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 25,
                'step' => 1,
                'data-value-display' => 'px'
            )
        ));
        
        // === TIPOGRAFIA ===
        
        // Tamanho da fonte
        $wp_customize->add_setting('cct_search_font_size', array(
            'default' => 14,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('cct_search_font_size', array(
            'label' => 'Tamanho da Fonte',
            'description' => 'Valor em pixels: <span class="range-value" data-setting="cct_search_font_size">14</span>px',
            'section' => 'cct_search_customizer',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 10,
                'max' => 20,
                'step' => 1,
                'data-value-display' => 'px'
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
            'default' => true,
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
            'default' => true,
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
        wp_enqueue_script(
            'cct-search-customizer-preview',
            get_template_directory_uri() . '/js/customizer-search-preview.js',
            array('customize-preview'),
            filemtime(get_template_directory() . '/js/customizer-search-preview.js'),
            true
        );
    }
    
    /**
     * Adiciona scripts do customizer
     */
    public static function enqueue_customizer_scripts() {
        wp_enqueue_script(
            'cct-search-customizer',
            get_template_directory_uri() . '/js/customizer-search.js',
            array('jquery', 'customize-controls'),
            wp_get_theme()->get('Version'),
            true
        );
        
        // Adicionar JavaScript inline para atualizar valores dos range sliders
        wp_add_inline_script('cct-search-customizer', '
            jQuery(document).ready(function($) {
                // Função para atualizar valores dos range sliders
                function updateRangeValue(setting, value, suffix) {
                    $(".range-value[data-setting=\"" + setting + "\"]").text(value);
                }
                
                // Configurar listeners para cada setting
                 var rangeSettings = {
                      "cct_search_padding_vertical": "px",
                      "cct_search_max_width": "px",
                      "cct_search_border_radius": "px",
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
        
        // Dimensões
        $padding_vertical = get_theme_mod('cct_search_padding_vertical', 6);
        $max_width = get_theme_mod('cct_search_max_width', 300);
        $border_radius = get_theme_mod('cct_search_border_radius', 4);
        $font_size = get_theme_mod('cct_search_font_size', 14);
        
        // Efeitos
        $box_shadow = get_theme_mod('cct_search_box_shadow', false);
        $transitions = get_theme_mod('cct_search_transitions', true);
        
        // CSS personalizado
        $custom_css = get_theme_mod('cct_search_custom_css', '');
        
        $css .= "\n/* Sistema de Busca - Customizer */\n";
        
        // Container
        $css .= ".search-container {\n";
        $css .= "    max-width: {$max_width}px;\n";
        $css .= "}\n";
        
        // Campo de busca
        $css .= ".search-container input[type='search'] {\n";
        $css .= "    width: calc(100% - 50px) !important;\n"; // Largura dinâmica menos botão
        $css .= "    max-width: " . ($max_width - 50) . "px !important;\n"; // Max width menos botão
        $css .= "    padding: {$padding_vertical}px 12px;\n";
        $css .= "    border-color: {$border_color};\n";
        $css .= "    border-radius: {$border_radius}px 0 0 {$border_radius}px;\n";
        $css .= "    font-size: {$font_size}px;\n";
        if ($transitions) {
            $css .= "    transition: all 0.3s ease;\n";
         }
         $css .= "}\n";
        
        // Botão (input e button)
        $css .= ".search-container input[type='submit'],\n";
        $css .= ".search-container button[type='submit'],\n";
        $css .= ".search-container .search-submit {\n";
        $css .= "    padding: {$padding_vertical}px 12px;\n";
        $css .= "    background: {$button_color} !important;\n";
        $css .= "    border-color: {$button_color} !important;\n";
        $css .= "    color: white !important;\n";
        $css .= "    border-radius: 0 {$border_radius}px {$border_radius}px 0;\n";
        $css .= "    font-size: {$font_size}px;\n";
        if ($transitions) {
            $css .= "    transition: all 0.3s ease;\n";
        }
        $css .= "}\n";
        
        // Hover do botão
        $css .= ".search-container input[type='submit']:hover,\n";
        $css .= ".search-container button[type='submit']:hover,\n";
        $css .= ".search-container .search-submit:hover {\n";
        $css .= "    background: {$button_hover_color} !important;\n";
         $css .= "    border-color: {$button_hover_color} !important;\n";
         $css .= "}\n";
        
        // Sombra
        if ($box_shadow) {
            $css .= ".search-container form {\n";
            $css .= "    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);\n";
            $css .= "}\n";
        }
        
        // Controle responsivo do texto do botão
        $show_text_desktop = get_theme_mod('cct_search_show_button_text_desktop', true);
        $show_text_tablet = get_theme_mod('cct_search_show_button_text_tablet', true);
        $show_text_mobile = get_theme_mod('cct_search_show_button_text_mobile', false);
        $hide_mobile_tablet = get_theme_mod('cct_search_hide_text_mobile_tablet', false);
        
        // CSS responsivo para texto do botão
        $css .= "\n/* Controle responsivo do texto do botão */\n";
        
        // Primeiro, definir comportamento padrão para todos
        $css .= ".search-container .search-submit .search-text {\n";
        $css .= "    display: inline;\n";
        $css .= "}\n";
        
        // Controle combinado Mobile + Tablet (<=992px)
        if ($hide_mobile_tablet) {
            $css .= "@media (max-width: 992px) {\n";
            $css .= "    .search-container .search-submit .search-text {\n";
            $css .= "        display: none !important;\n";
            $css .= "    }\n";
            $css .= "}\n";
        } else {
            // Controles individuais (só aplicar se o combinado não estiver ativo)
            
            // Desktop (>=993px) - breakpoint exclusivo
            if (!$show_text_desktop) {
                $css .= "@media (min-width: 993px) {\n";
                $css .= "    .search-container .search-submit .search-text {\n";
                $css .= "        display: none !important;\n";
                $css .= "    }\n";
                $css .= "}\n";
            }
            
            // Tablet (577px-992px) - breakpoint exclusivo
            if (!$show_text_tablet) {
                $css .= "@media (min-width: 577px) and (max-width: 992px) {\n";
                $css .= "    .search-container .search-submit .search-text {\n";
                $css .= "        display: none !important;\n";
                $css .= "    }\n";
                $css .= "}\n";
            }
            
            // Mobile (<=576px) - breakpoint exclusivo
            if (!$show_text_mobile) {
                $css .= "@media (max-width: 576px) {\n";
                $css .= "    .search-container .search-submit .search-text {\n";
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
            $css .= "    .search-container,\n";
            $css .= "    .custom-search-form {\n";
            $css .= "        display: none !important;\n";
            $css .= "    }\n";
            $css .= "}\n";
        }
        
        // Tablet (577px-992px)
        if (!$show_form_tablet) {
            $css .= "@media (min-width: 577px) and (max-width: 992px) {\n";
            $css .= "    .search-container,\n";
            $css .= "    .custom-search-form {\n";
            $css .= "        display: none !important;\n";
            $css .= "    }\n";
            $css .= "}\n";
        }
        
        // Mobile (<=576px)
        if (!$show_form_mobile) {
            $css .= "@media (max-width: 576px) {\n";
            $css .= "    .search-container,\n";
            $css .= "    .custom-search-form {\n";
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
            $css .= ".search-retractable-toggle {\n";
            $css .= "    background: {$button_color} !important;\n";
            $css .= "    font-size: {$button_font_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-toggle i {\n";
            $css .= "    font-size: {$icon_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-toggle:hover {\n";
            $css .= "    background: {$button_hover_color} !important;\n";
            $css .= "}\n";
            
            // Botão inline
            $css .= ".search-retractable-toggle-inline {\n";
            $css .= "    background: {$button_color} !important;\n";
            $css .= "    padding: {$button_height}px {$button_width}px !important;\n";
            $css .= "    font-size: {$button_font_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-toggle-inline i {\n";
            $css .= "    font-size: {$icon_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-toggle-inline:hover {\n";
            $css .= "    background: {$button_hover_color} !important;\n";
            $css .= "}\n";
            
            // Botões de submit nos formulários
            $css .= ".search-retractable-form .search-submit,\n";
            $css .= ".search-retractable-form-inline .search-submit {\n";
            $css .= "    background: {$button_color} !important;\n";
            $css .= "    padding: {$button_height}px {$button_width}px !important;\n";
            $css .= "    font-size: {$button_font_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-form .search-submit i,\n";
            $css .= ".search-retractable-form-inline .search-submit i {\n";
            $css .= "    font-size: {$icon_size}px !important;\n";
            $css .= "}\n";
            $css .= ".search-retractable-form .search-submit:hover,\n";
            $css .= ".search-retractable-form-inline .search-submit:hover {\n";
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