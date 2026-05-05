<?php
/**
 * Gerenciador de Cabeçalho Avançado (Design System CCT)
 * 
 * Permite customizar:
 * - Altura do cabeçalho (Desktop/Mobile)
 * - Cores e transparência
 * - Comportamento Sticky
 * 
 * @package UENF_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class UENF_Header_Manager extends UENF_Customizer_Base {
    
    protected $prefix = 'uenf_header_';
    
    protected function init() {
        $this->add_sections();
        $this->add_settings();
        $this->add_controls();
    }
    
    private function add_sections() {
        $this->add_section('settings', array(
            'title' => __('Cabeçalho & Navegação', 'cct'),
            'description' => __('Configure o visual e comportamento do cabeçalho.', 'cct'),
            'panel' => 'uenf_panel',
            'priority' => 15,
        ));
    }
    
    private function add_settings() {
        // Altura Desktop
        $this->add_setting('height_desktop', array(
            'default' => 100,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        // Altura Mobile
        $this->add_setting('height_mobile', array(
            'default' => 60,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        // Cor de Fundo
        $this->add_setting('bg_color', array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        // Sticky Header
        $this->add_setting('sticky', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ));
    }
    
    private function add_controls() {
        // Altura Desktop
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                $this->prefix . 'height_desktop',
                array(
                    'label' => __('Altura Desktop (px)', 'cct'),
                    'section' => $this->prefix . 'settings',
                    'input_attrs' => array(
                        'min' => 40,
                        'max' => 200,
                        'step' => 1,
                    ),
                )
            )
        );
        
        // Altura Mobile
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                $this->prefix . 'height_mobile',
                array(
                    'label' => __('Altura Mobile (px)', 'cct'),
                    'section' => $this->prefix . 'settings',
                    'input_attrs' => array(
                        'min' => 40,
                        'max' => 120,
                        'step' => 1,
                    ),
                )
            )
        );
        
        // Cor de Fundo
        $this->add_color_control('bg_color', array(
            'label' => __('Cor de Fundo', 'cct'),
            'section' => $this->prefix . 'settings',
        ));
        
        // Sticky Header Checkbox
        $this->add_control('sticky', array(
            'label' => __('Habilitar Cabeçalho Fixo (Sticky)', 'cct'),
            'section' => $this->prefix . 'settings',
            'type' => 'checkbox',
        ));
    }
    
    public function generate_css() {
        $h_desktop = $this->get_theme_mod('height_desktop', 100);
        $h_mobile = $this->get_theme_mod('height_mobile', 60);
        $bg_color = $this->get_theme_mod('bg_color', '#ffffff');
        $is_sticky = $this->get_theme_mod('sticky', true);
        
        $css = "
        :root {
            --uenf-header-height-desktop: {$h_desktop}px;
            --uenf-header-height-mobile: {$h_mobile}px;
            --uenf-header-bg: {$bg_color};
        }
        ";
        
        if ($is_sticky) {
            $css .= "
            @media (max-width: 768px) {
                .bg-header-logo {
                    position: fixed !important;
                    top: 0;
                    width: 100%;
                    height: var(--uenf-header-height-mobile) !important;
                    background-color: var(--uenf-header-bg) !important;
                    z-index: 9999;
                }
                body {
                    padding-top: var(--uenf-header-height-mobile) !important;
                }
                body.admin-bar {
                    padding-top: calc(32px + var(--uenf-header-height-mobile)) !important;
                }
                body.admin-bar .bg-header-logo {
                    top: 32px !important;
                }
            }
            ";
        }
        
        return $css;
    }
}
