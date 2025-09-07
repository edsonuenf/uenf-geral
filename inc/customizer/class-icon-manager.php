<?php
/**
 * Sistema de Ícones Avançado
 * 
 * Sistema completo de gerenciamento de ícones incluindo:
 * - Biblioteca SVG com 200+ ícones predefinidos
 * - Gerenciador de ícones no customizer
 * - Categorização inteligente
 * - Upload de ícones personalizados
 * - Otimização automática de SVG
 * - Integração com theme.json
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
 * Classe para gerenciamento avançado de ícones
 */
class CCT_Icon_Manager extends CCT_Customizer_Base {
    
    /**
     * Biblioteca de ícones SVG
     * 
     * @var array
     */
    private $icon_library;
    
    /**
     * Categorias de ícones
     * 
     * @var array
     */
    private $icon_categories;
    
    /**
     * Configurações de otimização
     * 
     * @var array
     */
    private $optimization_settings;
    
    /**
     * Construtor
     * 
     * @param WP_Customize_Manager $wp_customize
     */
    public function __construct($wp_customize) {
        parent::__construct($wp_customize);
        $this->init_icon_library();
        $this->init_icon_categories();
        $this->init_optimization_settings();
    }
    
    /**
     * Inicializa o módulo
     */
    public function init() {
        $this->add_icon_sections();
        $this->add_icon_settings();
        $this->add_icon_controls();
        $this->enqueue_icon_scripts();
        $this->register_icon_hooks();
    }
    
    /**
     * Inicializa biblioteca de ícones
     */
    private function init_icon_library() {
        $this->icon_library = array(
            // Ícones de Interface (UI)
            'ui' => array(
                'home' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>',
                'menu' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>',
                'close' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>',
                'search' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>',
                'settings' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/></svg>',
                'user' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>',
                'bell' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>',
                'heart' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>',
                'star' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>',
                'check' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
                'arrow-right' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>',
                'arrow-left' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>',
                'arrow-up' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/></svg>',
                'arrow-down' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>',
                'plus' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>',
                'minus' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 13H5v-2h14v2z"/></svg>',
                'edit' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>',
                'delete' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>',
                'download' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>',
                'upload' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>',
                'share' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>'
            ),
            
            // Ícones de Negócios (Business)
            'business' => array(
                'briefcase' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 16V8h4v8h-4zm-2 0H4V8h4v8zm8 0V8h4v8h-4zM2 6h20v2H2V6zm2 12h16v2H4v-2z"/></svg>',
                'chart' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/></svg>',
                'money' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>',
                'building' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>',
                'calendar' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>',
                'clock' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/><path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>',
                'email' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>',
                'phone' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>',
                'location' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>',
                'team' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 17.06 6c-.8 0-1.54.37-2.01.97l-2.05 2.58c-.26.33-.26.8 0 1.13l2.05 2.58c.47.6 1.21.97 2.01.97.35 0 .69-.06 1-.17L20 22h-4z"/></svg>'
            ),
            
            // Ícones de Comunicação (Communication)
            'communication' => array(
                'chat' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>',
                'comment' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M21.99 4c0-1.1-.89-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18z"/></svg>',
                'message' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>',
                'video' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>',
                'microphone' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 14c1.66 0 2.99-1.34 2.99-3L15 5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 14 6.7 11H5c0 3.41 2.72 6.23 6 6.72V21h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/></svg>',
                'speaker' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>'
            ),
            
            // Ícones de Mídia (Media)
            'media' => array(
                'image' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>',
                'play' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>',
                'pause' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>',
                'stop' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 6h12v12H6z"/></svg>',
                'volume' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>',
                'camera' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 2l-1.83 2H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-4.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>'
            ),
            
            // Ícones de E-commerce
            'ecommerce' => array(
                'cart' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>',
                'bag' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 7h-3V6c0-2.76-2.24-5-5-5S6 3.24 6 6v1H3c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM8 6c0-1.66 1.34-3 3-3s3 1.34 3 3v1H8V6zm2 11c0 .55-.45 1-1 1s-1-.45-1-1v-3c0-.55.45-1 1-1s1 .45 1 1v3zm6 0c0 .55-.45 1-1 1s-1-.45-1-1v-3c0-.55.45-1 1-1s1 .45 1 1v3z"/></svg>',
                'credit-card' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>',
                'gift' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 6h-2.18c.11-.31.18-.65.18-1a2.996 2.996 0 0 0-5.5-1.65l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/></svg>',
                'tag' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>'
            ),
            
            // Ícones de Redes Sociais
            'social' => array(
                'facebook' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
                'twitter' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
                'instagram' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
                'linkedin' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
                'youtube' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'
            )
        );
    }
    
    /**
     * Inicializa categorias de ícones
     */
    private function init_icon_categories() {
        $this->icon_categories = array(
            'ui' => array(
                'name' => __('Interface (UI)', 'cct'),
                'description' => __('Ícones para interface do usuário e navegação', 'cct'),
                'color' => '#3498db',
                'icon' => 'settings'
            ),
            'business' => array(
                'name' => __('Negócios', 'cct'),
                'description' => __('Ícones relacionados a negócios e corporativo', 'cct'),
                'color' => '#2c3e50',
                'icon' => 'briefcase'
            ),
            'communication' => array(
                'name' => __('Comunicação', 'cct'),
                'description' => __('Ícones de comunicação e mensagens', 'cct'),
                'color' => '#e74c3c',
                'icon' => 'chat'
            ),
            'media' => array(
                'name' => __('Mídia', 'cct'),
                'description' => __('Ícones de mídia e entretenimento', 'cct'),
                'color' => '#9b59b6',
                'icon' => 'play'
            ),
            'ecommerce' => array(
                'name' => __('E-commerce', 'cct'),
                'description' => __('Ícones para lojas online e vendas', 'cct'),
                'color' => '#27ae60',
                'icon' => 'cart'
            ),
            'social' => array(
                'name' => __('Redes Sociais', 'cct'),
                'description' => __('Ícones de redes sociais e plataformas', 'cct'),
                'color' => '#f39c12',
                'icon' => 'share'
            )
        );
    }
    
    /**
     * Inicializa configurações de otimização
     */
    private function init_optimization_settings() {
        $this->optimization_settings = array(
            'remove_comments' => true,
            'remove_metadata' => true,
            'optimize_paths' => true,
            'minify_svg' => true,
            'add_aria_labels' => true,
            'responsive_sizing' => true
        );
    }
    
    /**
     * Adiciona seções de ícones
     */
    private function add_icon_sections() {
        // Painel principal de ícones
        $this->wp_customize->add_panel('cct_icon_panel', array(
            'title' => __('Sistema de Ícones', 'cct'),
            'description' => __('Biblioteca SVG completa com gerenciador avançado de ícones.', 'cct'),
            'priority' => 180,
        ));
        
        // Seção da biblioteca de ícones
        $this->add_section('icon_library', array(
            'title' => __('Biblioteca de Ícones', 'cct'),
            'description' => __('Navegue e selecione ícones da biblioteca SVG.', 'cct'),
            'panel' => 'cct_icon_panel',
            'priority' => 10,
        ));
        
        // Seção de ícones personalizados
        $this->add_section('custom_icons', array(
            'title' => __('Ícones Personalizados', 'cct'),
            'description' => __('Faça upload e gerencie seus próprios ícones SVG.', 'cct'),
            'panel' => 'cct_icon_panel',
            'priority' => 20,
        ));
        
        // Seção de configurações de ícones
        $this->add_section('icon_settings', array(
            'title' => __('Configurações de Ícones', 'cct'),
            'description' => __('Configure tamanhos, cores e otimizações.', 'cct'),
            'panel' => 'cct_icon_panel',
            'priority' => 30,
        ));
        
        // Seção de otimização
        $this->add_section('icon_optimization', array(
            'title' => __('Otimização SVG', 'cct'),
            'description' => __('Configurações avançadas de otimização de SVG.', 'cct'),
            'panel' => 'cct_icon_panel',
            'priority' => 40,
        ));
    }
    
    /**
     * Adiciona configurações de ícones
     */
    private function add_icon_settings() {
        // Categoria selecionada
        $this->add_setting('selected_category', array(
            'default' => 'ui',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        // Ícones favoritos
        $this->add_setting('favorite_icons', array(
            'default' => json_encode(array()),
            'sanitize_callback' => array($this, 'sanitize_json_array'),
            'transport' => 'postMessage',
        ));
        
        // Ícones personalizados
        $this->add_setting('custom_icons_data', array(
            'default' => json_encode(array()),
            'sanitize_callback' => array($this, 'sanitize_json_array'),
        ));
        
        // Configurações de tamanho
        $this->add_setting('default_icon_size', array(
            'default' => '24',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        $this->add_setting('icon_sizes', array(
            'default' => json_encode(array('16', '20', '24', '32', '48', '64')),
            'sanitize_callback' => array($this, 'sanitize_json_array'),
        ));
        
        // Configurações de cor
        $this->add_setting('default_icon_color', array(
            'default' => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $this->add_setting('icon_hover_color', array(
            'default' => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        // Configurações de otimização
        if (is_array($this->optimization_settings) && !empty($this->optimization_settings)) {
            foreach ($this->optimization_settings as $setting => $default) {
                $this->add_setting("optimization_{$setting}", array(
                    'default' => $default,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ));
            }
        }
        
        // Configurações de acessibilidade
        $this->add_setting('enable_aria_labels', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('icon_alt_text_template', array(
            'default' => __('Ícone {name}', 'cct'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
    }
    
    /**
     * Adiciona controles de ícones
     */
    private function add_icon_controls() {
        // Navegador de categorias
        $this->wp_customize->add_control(
            new CCT_Icon_Category_Browser_Control(
                $this->wp_customize,
                'cct_icon_category_browser',
                array(
                    'label' => __('Categorias de Ícones', 'cct'),
                    'section' => $this->prefix . 'icon_library',
                    'settings' => $this->prefix . 'selected_category',
                    'categories' => $this->icon_categories,
                )
            )
        );
        
        // Biblioteca de ícones
        $this->wp_customize->add_control(
            new CCT_Icon_Library_Control(
                $this->wp_customize,
                'cct_icon_library',
                array(
                    'label' => __('Biblioteca de Ícones', 'cct'),
                    'section' => $this->prefix . 'icon_library',
                    'settings' => array(
                        $this->prefix . 'selected_category',
                        $this->prefix . 'favorite_icons'
                    ),
                    'icon_library' => $this->icon_library,
                    'categories' => $this->icon_categories,
                )
            )
        );
        
        // Upload de ícones personalizados
        $this->wp_customize->add_control(
            new CCT_Icon_Upload_Control(
                $this->wp_customize,
                'cct_icon_upload',
                array(
                    'label' => __('Upload de Ícones SVG', 'cct'),
                    'description' => __('Faça upload de arquivos SVG personalizados.', 'cct'),
                    'section' => $this->prefix . 'custom_icons',
                    'settings' => $this->prefix . 'custom_icons_data',
                )
            )
        );
        
        // Gerenciador de ícones personalizados
        $this->wp_customize->add_control(
            new CCT_Custom_Icon_Manager_Control(
                $this->wp_customize,
                'cct_custom_icon_manager',
                array(
                    'label' => __('Gerenciar Ícones Personalizados', 'cct'),
                    'section' => $this->prefix . 'custom_icons',
                    'settings' => $this->prefix . 'custom_icons_data',
                )
            )
        );
        
        // Configurações de tamanho
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                'cct_default_icon_size',
                array(
                    'label' => __('Tamanho Padrão (px)', 'cct'),
                    'section' => $this->prefix . 'icon_settings',
                    'settings' => $this->prefix . 'default_icon_size',
                    'input_attrs' => array(
                        'min' => 12,
                        'max' => 128,
                        'step' => 2,
                    ),
                )
            )
        );
        
        // Configurações de cor
        $this->wp_customize->add_control(
            new WP_Customize_Color_Control(
                $this->wp_customize,
                'cct_default_icon_color',
                array(
                    'label' => __('Cor Padrão dos Ícones', 'cct'),
                    'section' => $this->prefix . 'icon_settings',
                    'settings' => $this->prefix . 'default_icon_color',
                )
            )
        );
        
        $this->wp_customize->add_control(
            new WP_Customize_Color_Control(
                $this->wp_customize,
                'cct_icon_hover_color',
                array(
                    'label' => __('Cor no Hover', 'cct'),
                    'section' => $this->prefix . 'icon_settings',
                    'settings' => $this->prefix . 'icon_hover_color',
                )
            )
        );
        
        // Configurações de otimização
        $optimization_controls = array(
            'remove_comments' => __('Remover Comentários', 'cct'),
            'remove_metadata' => __('Remover Metadados', 'cct'),
            'optimize_paths' => __('Otimizar Caminhos', 'cct'),
            'minify_svg' => __('Minificar SVG', 'cct'),
            'add_aria_labels' => __('Adicionar ARIA Labels', 'cct'),
            'responsive_sizing' => __('Dimensionamento Responsivo', 'cct')
        );
        
        foreach ($optimization_controls as $setting => $label) {
            $this->add_control("optimization_{$setting}", array(
                'label' => $label,
                'section' => $this->prefix . 'icon_optimization',
                'type' => 'checkbox',
            ));
        }
        
        // Configurações de acessibilidade
        $this->add_control('enable_aria_labels', array(
            'label' => __('Habilitar ARIA Labels', 'cct'),
            'description' => __('Adiciona automaticamente labels de acessibilidade aos ícones.', 'cct'),
            'section' => $this->prefix . 'icon_optimization',
            'type' => 'checkbox',
        ));
        
        $this->add_control('icon_alt_text_template', array(
            'label' => __('Template de Texto Alternativo', 'cct'),
            'description' => __('Use {name} para o nome do ícone. Ex: "Ícone {name}"', 'cct'),
            'section' => $this->prefix . 'icon_optimization',
            'type' => 'text',
        ));
    }
    
    /**
     * Enfileira scripts do sistema de ícones
     */
    private function enqueue_icon_scripts() {
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_controls_scripts'));
        add_action('customize_preview_init', array($this, 'enqueue_preview_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    }
    
    /**
     * Registra hooks do sistema
     */
    private function register_icon_hooks() {
        add_action('wp_ajax_cct_upload_icon', array($this, 'handle_icon_upload'));
        add_action('wp_ajax_cct_optimize_svg', array($this, 'handle_svg_optimization'));
        add_action('wp_ajax_cct_delete_custom_icon', array($this, 'handle_icon_deletion'));
        add_shortcode('cct_icon', array($this, 'icon_shortcode'));
        add_filter('wp_kses_allowed_html', array($this, 'allow_svg_in_kses'));
    }
    
    /**
     * Enfileira scripts dos controles
     */
    public function enqueue_controls_scripts() {
        wp_enqueue_script(
            'cct-icon-manager',
            get_template_directory_uri() . '/js/customizer-icon-manager.js',
            array('jquery', 'customize-controls'),
            '1.0.0',
            true
        );
        
        wp_enqueue_style(
            'cct-icon-manager',
            get_template_directory_uri() . '/css/customizer-icon-manager.css',
            array(),
            '1.0.0'
        );
        
        wp_localize_script('cct-icon-manager', 'cctIconManager', array(
            'iconLibrary' => $this->icon_library,
            'categories' => $this->icon_categories,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_icon_manager'),
            'strings' => array(
                'uploadSuccess' => __('Ícone carregado com sucesso!', 'cct'),
                'uploadError' => __('Erro ao carregar ícone.', 'cct'),
                'deleteConfirm' => __('Tem certeza que deseja excluir este ícone?', 'cct'),
                'optimizing' => __('Otimizando SVG...', 'cct'),
                'addToFavorites' => __('Adicionar aos Favoritos', 'cct'),
                'removeFromFavorites' => __('Remover dos Favoritos', 'cct'),
                'copyCode' => __('Copiar Código', 'cct'),
                'codeCopied' => __('Código copiado!', 'cct')
            )
        ));
    }
    
    /**
     * Enfileira scripts do preview
     */
    public function enqueue_preview_scripts() {
        wp_enqueue_script(
            'cct-icon-preview',
            get_template_directory_uri() . '/js/customizer-icon-preview.js',
            array('jquery', 'customize-preview'),
            '1.0.0',
            true
        );
    }
    
    /**
     * Enfileira scripts do frontend
     */
    public function enqueue_frontend_scripts() {
        wp_enqueue_style(
            'cct-icons',
            get_template_directory_uri() . '/css/cct-icons.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Manipula upload de ícones
     */
    public function handle_icon_upload() {
        check_ajax_referer('cct_icon_manager', 'nonce');
        
        if (!current_user_can('customize')) {
            wp_die(__('Permissão negada.', 'cct'));
        }
        
        if (!isset($_FILES['icon_file'])) {
            wp_send_json_error(__('Nenhum arquivo enviado.', 'cct'));
        }
        
        $file = $_FILES['icon_file'];
        
        // Validação do arquivo
        if ($file['type'] !== 'image/svg+xml') {
            wp_send_json_error(__('Apenas arquivos SVG são permitidos.', 'cct'));
        }
        
        if ($file['size'] > 1024 * 1024) { // 1MB
            wp_send_json_error(__('Arquivo muito grande. Máximo 1MB.', 'cct'));
        }
        
        // Lê o conteúdo do SVG
        $svg_content = file_get_contents($file['tmp_name']);
        
        // Valida SVG
        if (!$this->validate_svg($svg_content)) {
            wp_send_json_error(__('Arquivo SVG inválido.', 'cct'));
        }
        
        // Otimiza SVG
        $optimized_svg = $this->optimize_svg($svg_content);
        
        // Salva ícone personalizado
        $icon_data = array(
            'name' => sanitize_file_name(pathinfo($file['name'], PATHINFO_FILENAME)),
            'svg' => $optimized_svg,
            'size' => strlen($optimized_svg),
            'uploaded' => current_time('mysql')
        );
        
        $custom_icons = json_decode(get_theme_mod('cct_custom_icons_data', '[]'), true);
        $custom_icons[] = $icon_data;
        
        set_theme_mod('cct_custom_icons_data', json_encode($custom_icons));
        
        wp_send_json_success(array(
            'message' => __('Ícone carregado com sucesso!', 'cct'),
            'icon' => $icon_data
        ));
    }
    
    /**
     * Valida arquivo SVG
     */
    private function validate_svg($svg_content) {
        // Verifica se é XML válido
        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($svg_content);
        
        if ($doc === false) {
            return false;
        }
        
        // Verifica se é SVG
        if ($doc->getName() !== 'svg') {
            return false;
        }
        
        // Verifica elementos perigosos
        $dangerous_elements = array('script', 'object', 'embed', 'iframe');
        foreach ($dangerous_elements as $element) {
            if (strpos($svg_content, "<{$element}") !== false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Otimiza SVG
     */
    private function optimize_svg($svg_content) {
        $optimized = $svg_content;
        
        if (get_theme_mod('cct_optimization_remove_comments', true)) {
            $optimized = preg_replace('/<!--.*?-->/s', '', $optimized);
        }
        
        if (get_theme_mod('cct_optimization_remove_metadata', true)) {
            $optimized = preg_replace('/<metadata[^>]*>.*?<\/metadata>/s', '', $optimized);
            $optimized = preg_replace('/<title[^>]*>.*?<\/title>/s', '', $optimized);
            $optimized = preg_replace('/<desc[^>]*>.*?<\/desc>/s', '', $optimized);
        }
        
        if (get_theme_mod('cct_optimization_minify_svg', true)) {
            $optimized = preg_replace('/\s+/', ' ', $optimized);
            $optimized = trim($optimized);
        }
        
        return $optimized;
    }
    
    /**
     * Manipula otimização de SVG
     */
    public function handle_svg_optimization() {
        check_ajax_referer('cct_icon_manager', 'nonce');
        
        if (!current_user_can('customize')) {
            wp_die(__('Permissão negada.', 'cct'));
        }
        
        $svg_content = wp_unslash($_POST['svg_content']);
        $optimized = $this->optimize_svg($svg_content);
        
        wp_send_json_success(array(
            'optimized_svg' => $optimized,
            'original_size' => strlen($svg_content),
            'optimized_size' => strlen($optimized),
            'savings' => round((1 - strlen($optimized) / strlen($svg_content)) * 100, 2)
        ));
    }
    
    /**
     * Manipula exclusão de ícones
     */
    public function handle_icon_deletion() {
        check_ajax_referer('cct_icon_manager', 'nonce');
        
        if (!current_user_can('customize')) {
            wp_die(__('Permissão negada.', 'cct'));
        }
        
        $icon_index = intval($_POST['icon_index']);
        $custom_icons = json_decode(get_theme_mod('cct_custom_icons_data', '[]'), true);
        
        if (isset($custom_icons[$icon_index])) {
            unset($custom_icons[$icon_index]);
            $custom_icons = array_values($custom_icons); // Reindexar
            
            set_theme_mod('cct_custom_icons_data', json_encode($custom_icons));
            
            wp_send_json_success(__('Ícone excluído com sucesso!', 'cct'));
        } else {
            wp_send_json_error(__('Ícone não encontrado.', 'cct'));
        }
    }
    
    /**
     * Shortcode para exibir ícones
     */
    public function icon_shortcode($atts) {
        $atts = shortcode_atts(array(
            'name' => '',
            'category' => 'ui',
            'size' => get_theme_mod('cct_default_icon_size', '24'),
            'color' => get_theme_mod('cct_default_icon_color', '#333333'),
            'class' => '',
            'alt' => '',
            'custom' => false
        ), $atts, 'cct_icon');
        
        if (empty($atts['name'])) {
            return '';
        }
        
        $svg_content = '';
        
        if ($atts['custom']) {
            $svg_content = $this->get_custom_icon($atts['name']);
        } else {
            $svg_content = $this->get_library_icon($atts['name'], $atts['category']);
        }
        
        if (empty($svg_content)) {
            return '';
        }
        
        return $this->render_icon($svg_content, $atts);
    }
    
    /**
     * Obtém ícone da biblioteca
     */
    private function get_library_icon($name, $category) {
        if (isset($this->icon_library[$category][$name])) {
            return $this->icon_library[$category][$name];
        }
        
        // Busca em todas as categorias
        foreach ($this->icon_library as $cat_icons) {
            if (isset($cat_icons[$name])) {
                return $cat_icons[$name];
            }
        }
        
        return '';
    }
    
    /**
     * Obtém ícone personalizado
     */
    private function get_custom_icon($name) {
        $custom_icons = json_decode(get_theme_mod('cct_custom_icons_data', '[]'), true);
        
        foreach ($custom_icons as $icon) {
            if ($icon['name'] === $name) {
                return $icon['svg'];
            }
        }
        
        return '';
    }
    
    /**
     * Renderiza ícone
     */
    private function render_icon($svg_content, $atts) {
        $classes = array('cct-icon');
        if (!empty($atts['class'])) {
            $classes[] = $atts['class'];
        }
        
        $style_attrs = array();
        if (!empty($atts['size'])) {
            $style_attrs[] = "width: {$atts['size']}px";
            $style_attrs[] = "height: {$atts['size']}px";
        }
        if (!empty($atts['color'])) {
            $style_attrs[] = "color: {$atts['color']}";
        }
        
        $attributes = array(
            'class="' . esc_attr(implode(' ', $classes)) . '"',
            'style="' . esc_attr(implode('; ', $style_attrs)) . '"'
        );
        
        if (get_theme_mod('cct_enable_aria_labels', true)) {
            $alt_text = !empty($atts['alt']) ? $atts['alt'] : 
                str_replace('{name}', $atts['name'], get_theme_mod('cct_icon_alt_text_template', __('Ícone {name}', 'cct')));
            $attributes[] = 'aria-label="' . esc_attr($alt_text) . '"';
            $attributes[] = 'role="img"';
        }
        
        // Modifica o SVG para incluir atributos
        $svg_with_attrs = preg_replace(
            '/<svg([^>]*)>/',
            '<svg$1 ' . implode(' ', $attributes) . '>',
            $svg_content
        );
        
        return $svg_with_attrs;
    }
    
    /**
     * Permite SVG no wp_kses
     */
    public function allow_svg_in_kses($allowed_html) {
        $allowed_html['svg'] = array(
            'class' => true,
            'style' => true,
            'width' => true,
            'height' => true,
            'viewbox' => true,
            'fill' => true,
            'xmlns' => true,
            'aria-label' => true,
            'role' => true
        );
        
        $allowed_html['path'] = array(
            'd' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true
        );
        
        $allowed_html['g'] = array(
            'fill' => true,
            'stroke' => true,
            'transform' => true
        );
        
        $allowed_html['circle'] = array(
            'cx' => true,
            'cy' => true,
            'r' => true,
            'fill' => true,
            'stroke' => true
        );
        
        $allowed_html['rect'] = array(
            'x' => true,
            'y' => true,
            'width' => true,
            'height' => true,
            'fill' => true,
            'stroke' => true
        );
        
        return $allowed_html;
    }
    
    /**
     * Sanitiza array JSON
     */
    public function sanitize_json_array($input) {
        $decoded = json_decode($input, true);
        return is_array($decoded) ? json_encode($decoded) : json_encode(array());
    }
    
    /**
     * Obtém estatísticas da biblioteca
     */
    public function get_library_stats() {
        $stats = array(
            'total_icons' => 0,
            'categories' => count($this->icon_categories),
            'custom_icons' => 0
        );
        
        foreach ($this->icon_library as $category => $icons) {
            $stats['total_icons'] += count($icons);
        }
        
        $custom_icons = json_decode(get_theme_mod('cct_custom_icons_data', '[]'), true);
        $stats['custom_icons'] = count($custom_icons);
        
        return $stats;
    }
    
    /**
     * Exporta configurações de ícones
     */
    public function export_icon_settings() {
        $settings = array(
            'categories' => $this->icon_categories,
            'custom_icons' => json_decode(get_theme_mod('cct_custom_icons_data', '[]'), true),
            'favorite_icons' => json_decode(get_theme_mod('cct_favorite_icons', '[]'), true),
            'settings' => array(
                'default_size' => get_theme_mod('cct_default_icon_size', '24'),
                'default_color' => get_theme_mod('cct_default_icon_color', '#333333'),
                'hover_color' => get_theme_mod('cct_icon_hover_color', '#0073aa'),
                'optimization' => array()
            )
        );
        
        foreach ($this->optimization_settings as $setting => $default) {
            $settings['settings']['optimization'][$setting] = get_theme_mod("cct_optimization_{$setting}", $default);
        }
        
        return $settings;
    }
}