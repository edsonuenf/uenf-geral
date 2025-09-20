<?php
/**
 * Classe Base do Customizer CCT
 * 
 * Classe abstrata que define a estrutura básica para módulos do customizer.
 * Fornece métodos comuns e padroniza a implementação de seções do customizer.
 * 
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

// Verificação de segurança
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe base abstrata para módulos do customizer
 * 
 * Esta classe fornece a estrutura básica que todos os módulos do customizer
 * devem seguir, garantindo consistência e reutilização de código.
 */
abstract class CCT_Customizer_Base {
    
    /**
     * Instância do gerenciador do customizer
     * 
     * @var WP_Customize_Manager
     */
    protected $wp_customize;
    
    /**
     * Prefixo para IDs de configurações
     * 
     * @var string
     */
    protected $prefix = 'cct_';
    
    /**
     * Construtor
     * 
     * @param WP_Customize_Manager $wp_customize Instância do customizer
     */
    public function __construct($wp_customize) {
        $this->wp_customize = $wp_customize;
        $this->init();
    }
    
    /**
     * Inicialização do módulo
     * 
     * Método abstrato que deve ser implementado por cada módulo
     * para registrar suas configurações específicas.
     */
    abstract protected function init();
    
    /**
     * Adiciona uma seção ao customizer
     * 
     * @param string $id ID da seção
     * @param array $args Argumentos da seção
     */
    protected function add_section($id, $args = array()) {
        $defaults = array(
            'capability' => 'edit_theme_options',
        );
        
        $args = wp_parse_args($args, $defaults);
        $this->wp_customize->add_section($this->prefix . $id, $args);
    }
    
    /**
     * Adiciona uma configuração ao customizer
     * 
     * @param string $id ID da configuração
     * @param array $args Argumentos da configuração
     */
    protected function add_setting($id, $args = array()) {
        $defaults = array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        );
        
        $args = wp_parse_args($args, $defaults);
        $this->wp_customize->add_setting($this->prefix . $id, $args);
    }
    
    /**
     * Adiciona um controle ao customizer
     * 
     * @param string $id ID do controle
     * @param array $args Argumentos do controle
     */
    protected function add_control($id, $args = array()) {
        $defaults = array(
            'settings' => $this->prefix . $id,
        );
        
        $args = wp_parse_args($args, $defaults);
        $this->wp_customize->add_control($this->prefix . $id, $args);
    }
    
    /**
     * Adiciona um controle de cor
     * 
     * @param string $id ID do controle
     * @param array $args Argumentos do controle
     */
    protected function add_color_control($id, $args = array()) {
        $this->wp_customize->add_control(
            new WP_Customize_Color_Control(
                $this->wp_customize,
                $this->prefix . $id,
                $args
            )
        );
    }
    
    /**
     * Cria uma configuração completa (setting + control)
     * 
     * @param string $id ID da configuração
     * @param array $setting_args Argumentos da configuração
     * @param array $control_args Argumentos do controle
     */
    protected function add_setting_and_control($id, $setting_args = array(), $control_args = array()) {
        $this->add_setting($id, $setting_args);
        $this->add_control($id, $control_args);
    }
    
    /**
     * Obtém o valor de uma configuração do tema
     * 
     * @param string $id ID da configuração (sem prefixo)
     * @param mixed $default Valor padrão
     * @return mixed Valor da configuração
     */
    protected function get_theme_mod($id, $default = false) {
        return get_theme_mod($this->prefix . $id, $default);
    }
    
    /**
     * Gera CSS para uma propriedade
     * 
     * @param string $selector Seletor CSS
     * @param string $property Propriedade CSS
     * @param string $setting_id ID da configuração
     * @param string $default Valor padrão
     * @param string $suffix Sufixo para o valor (ex: 'px', '%')
     * @return string CSS gerado
     */
    protected function generate_css($selector, $property, $setting_id, $default = '', $suffix = '') {
        $value = $this->get_theme_mod($setting_id, $default);
        
        if (empty($value) || $value === $default) {
            return '';
        }
        
        return sprintf(
            '%s { %s: %s%s; }',
            $selector,
            $property,
            esc_attr($value),
            $suffix
        );
    }
}