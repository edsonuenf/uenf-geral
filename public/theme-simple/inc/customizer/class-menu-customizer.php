<?php
/**
 * Módulo do Customizer para Menu de Navegação
 * 
 * Gerencia todas as configurações relacionadas ao menu de navegação,
 * incluindo estilo, ícones, cores e comportamento.
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
 * Classe para configurações do menu no customizer
 */
class CCT_Menu_Customizer extends CCT_Customizer_Base {
    
    /**
     * Inicializa as configurações do menu
     */
    protected function init() {
        $this->add_menu_section();
        $this->add_menu_settings();
    }
    
    /**
     * Adiciona a seção do menu
     */
    private function add_menu_section() {
        $this->add_section('menu_settings', array(
            'title' => __('Menu de Navegação', 'cct'),
            'priority' => 35,
            'description' => __('Configure a aparência do menu de navegação.', 'cct'),
        ));
    }
    
    /**
     * Adiciona todas as configurações do menu
     */
    private function add_menu_settings() {
        $this->add_menu_style_setting();
        $this->add_hierarchy_icons_setting();
    }
    
    /**
     * Configuração do estilo do menu
     */
    private function add_menu_style_setting() {
        // Setting
        $this->add_setting('menu_style', array(
            'default' => defined('CCT_DEFAULT_MENU_STYLE') ? CCT_DEFAULT_MENU_STYLE : 'modern',
            'sanitize_callback' => array($this, 'sanitize_menu_style'),
            'transport' => 'refresh',
        ));
        
        // Control
        $this->add_control('menu_style', array(
            'label' => __('Estilo do Menu', 'cct'),
            'section' => $this->prefix . 'menu_settings',
            'type' => 'select',
            'description' => __('Escolha o estilo visual do menu.', 'cct'),
            'choices' => array(
                'modern' => __('Moderno (com gradientes)', 'cct'),
                'classic' => __('Clássico (cores sólidas)', 'cct'),
                'minimal' => __('Minimalista (transparente)', 'cct'),
            ),
        ));
    }
    
    /**
     * Configuração dos ícones de hierarquia
     */
    private function add_hierarchy_icons_setting() {
        // Setting
        $this->add_setting('menu_show_hierarchy_icons', array(
            'default' => defined('CCT_DEFAULT_MENU_HIERARCHY_ICONS') ? CCT_DEFAULT_MENU_HIERARCHY_ICONS : true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'postMessage',
        ));
        
        // Control
        $this->add_control('menu_show_hierarchy_icons', array(
            'label' => __('Mostrar Ícones de Hierarquia', 'cct'),
            'section' => $this->prefix . 'menu_settings',
            'type' => 'checkbox',
            'description' => __('Exibe setas e símbolos para indicar a hierarquia dos submenus.', 'cct'),
        ));
    }
    
    /**
     * Sanitiza o valor do estilo do menu
     * 
     * @param string $input Valor de entrada
     * @return string Valor sanitizado
     */
    public function sanitize_menu_style($input) {
        $valid_styles = array('modern', 'classic', 'minimal');
        
        if (in_array($input, $valid_styles)) {
            return $input;
        }
        
        return defined('CCT_DEFAULT_MENU_STYLE') ? CCT_DEFAULT_MENU_STYLE : 'modern';
    }
    
    /**
     * Gera CSS para o menu baseado nas configurações
     * 
     * @return string CSS do menu
     */
    public function generate_menu_css() {
        $css = '';
        $menu_style = $this->get_theme_mod('menu_style', 'modern');
        
        switch ($menu_style) {
            case 'classic':
                $css .= $this->generate_classic_menu_css();
                break;
                
            case 'minimal':
                $css .= $this->generate_minimal_menu_css();
                break;
                
            default: // modern
                // Estilo moderno usa CSS padrão
                break;
        }
        
        // CSS para ícones de hierarquia
        if (!$this->get_theme_mod('menu_show_hierarchy_icons', true)) {
            $css .= '.submenu-toggle { display: none !important; }';
        }
        
        return $css;
    }
    
    /**
     * Gera CSS para o estilo clássico
     * 
     * @return string CSS do estilo clássico
     */
    private function generate_classic_menu_css() {
        return '
            body .offcanvas .new-menu, 
            body .new-menu, 
            .offcanvas .new-menu, 
            .new-menu, 
            .navbar-nav, 
            .offcanvas .navbar-nav {
                background: #1d3771 !important;
            }
            
            body .offcanvas .new-menu .sub-menu, 
            body .offcanvas .new-menu .children, 
            body .new-menu .sub-menu, 
            body .new-menu .children, 
            .offcanvas .new-menu .sub-menu, 
            .offcanvas .new-menu .children, 
            .new-menu .sub-menu, 
            .new-menu .children, 
            .navbar-nav .dropdown-menu, 
            .offcanvas .navbar-nav .dropdown-menu {
                background: #1d3771 !important;
                border-left: 3px solid #ffffff !important;
                box-shadow: none !important;
                backdrop-filter: none !important;
            }
            
            body.menu-classic-style .offcanvas .new-menu *, 
            body.menu-classic-style .new-menu *, 
            body .offcanvas .new-menu *, 
            body .new-menu * {
                transition: none !important;
                animation: none !important;
                transform: none !important;
                -webkit-transition: none !important;
                -moz-transition: none !important;
                -o-transition: none !important;
            }
        ';
    }
    
    /**
     * Gera CSS para o estilo minimalista
     * 
     * @return string CSS do estilo minimalista
     */
    private function generate_minimal_menu_css() {
        return '
            .navbar-nav, 
            .new-menu {
                background: rgba(29, 55, 113, 0.8) !important;
            }
            
            .navbar-nav .dropdown-menu, 
            .new-menu .sub-menu {
                background: rgba(29, 55, 113, 0.9) !important;
                border-left: 1px solid rgba(255, 255, 255, 0.3) !important;
                backdrop-filter: blur(5px);
            }
        ';
    }
}