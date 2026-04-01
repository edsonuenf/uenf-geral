<?php
/**
 * Gerenciador do Painel Design Principal
 *
 * @package CCT
 * @subpackage Customizer
 * @since 1.0.0
 */

namespace UENF\CCT\Customizer;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe responsável por gerenciar o painel principal de Design
 * que agrupa todos os módulos relacionados ao design system
 */
class Design_Panel_Manager {
    
    /**
     * Instância do WP_Customize_Manager
     *
     * @var \WP_Customize_Manager
     */
    private $wp_customize;
    
    /**
     * Prefixo para identificadores
     *
     * @var string
     */
    private $prefix = 'cct_design_';
    
    /**
     * Construtor
     *
     * @param \WP_Customize_Manager $wp_customize Instância do customizer
     */
    public function __construct($wp_customize) {
        $this->wp_customize = $wp_customize;
    }
    
    /**
     * Inicializa o gerenciador
     */
    public function init() {
        add_action('customize_register', array($this, 'register'));
    }
    
    /**
     * Registra o painel principal de Design (padrão moderno)
     *
     * @param \WP_Customize_Manager $wp_customize
     */
    public function register($wp_customize) {
        $this->wp_customize = $wp_customize;
        $this->add_design_panel();
    }
    
    /**
     * Adiciona o painel principal de Design
     */
    private function add_design_panel() {
        // cct_design_panel foi consolidado em uenf_panel — registrado no customizer-loader.php
        // Mantido para compatibilidade de referências externas
    }
    
    /**
     * Obtém o ID do painel de design
     *
     * @return string
     */
    public function get_panel_id() {
        return 'uenf_panel';
    }
    
    /**
     * Verifica se o painel de design existe
     *
     * @return bool
     */
    public function panel_exists() {
        return $this->wp_customize->get_panel($this->get_panel_id()) !== null;
    }
}