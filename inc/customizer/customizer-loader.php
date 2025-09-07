<?php
/**
 * Carregador Modular do Customizer CCT
 * 
 * Este arquivo é responsável por carregar todos os módulos do customizer
 * de forma organizada e modular, substituindo o arquivo monolítico anterior.
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
 * Classe principal do carregador do customizer
 */
class CCT_Customizer_Loader {
    
    /**
     * Instância única da classe (Singleton)
     * 
     * @var CCT_Customizer_Loader
     */
    private static $instance = null;
    
    /**
     * Array de módulos carregados
     * 
     * @var array
     */
    private $modules = array();
    
    /**
     * Diretório dos módulos do customizer
     * 
     * @var string
     */
    private $modules_dir;
    
    /**
     * Construtor privado (Singleton)
     */
    private function __construct() {
        $this->modules_dir = get_template_directory() . '/inc/customizer/';
        $this->init();
    }
    
    /**
     * Obtém a instância única da classe
     * 
     * @return CCT_Customizer_Loader
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Inicializa o carregador
     */
    private function init() {
        add_action('customize_register', array($this, 'load_modules'));
        add_action('wp_head', array($this, 'output_css'), 999);
    }
    
    /**
     * Carrega todos os módulos do customizer
     * 
     * @param WP_Customize_Manager $wp_customize
     */
    public function load_modules($wp_customize) {
        // Carregar classe base primeiro
        $this->load_file('class-customizer-base.php');
        
        // Lista de módulos a serem carregados
        $module_files = array(
            'class-menu-customizer.php',
            'class-typography-customizer.php',
            'class-typography-controls.php',
            
            // Módulos de cores
        'class-color-manager.php',
        'class-color-controls.php',
        
        // Módulos de ícones
        'class-icon-manager.php',
        'class-icon-controls.php',
        
        // Módulos de layout
        'class-layout-manager.php',
        'class-layout-controls.php',
        
        // Módulos de animações
        'class-animation-manager.php',
        'class-animation-controls.php',
        
        // Módulos de gradientes
        'class-gradient-manager.php',
        'class-gradient-controls.php',
        
        // Módulos de sombras
        'class-shadow-manager.php',
        'class-shadow-controls.php',
        
        // Módulos de biblioteca de padrões
        'class-pattern-library-manager.php',
        'class-pattern-library-controls.php',
        
        // Módulos de modo escuro/claro
        'class-dark-mode-manager.php',
        
        // Módulos de responsive breakpoints
        'class-responsive-breakpoints-manager.php',
        'class-breakpoint-manager-control.php',
        
        // Módulos de design tokens
        'class-design-tokens-manager.php',
        'class-design-tokens-control.php',
            // Adicione outros módulos aqui conforme necessário
        );
        
        // Carregar cada módulo
        foreach ($module_files as $file) {
            $this->load_module($file, $wp_customize);
        }
        
        // Manter funcionalidades existentes do customizer original
        $this->load_legacy_customizer($wp_customize);
    }
    
    /**
     * Carrega um arquivo específico
     * 
     * @param string $filename Nome do arquivo
     */
    private function load_file($filename) {
        $file_path = $this->modules_dir . $filename;
        
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
    
    /**
     * Carrega e instancia um módulo específico
     * 
     * @param string $filename Nome do arquivo do módulo
     * @param WP_Customize_Manager $wp_customize
     */
    private function load_module($filename, $wp_customize) {
        $this->load_file($filename);
        
        // Determinar nome da classe baseado no nome do arquivo
        $class_name = $this->get_class_name_from_file($filename);
        
        if (class_exists($class_name)) {
            $module_instance = new $class_name($wp_customize);
            $this->modules[$class_name] = $module_instance;
        }
    }
    
    /**
     * Determina o nome da classe baseado no nome do arquivo
     * 
     * @param string $filename Nome do arquivo
     * @return string Nome da classe
     */
    private function get_class_name_from_file($filename) {
        // Mapeamento específico para nomes de classes
        $class_map = array(
            'class-menu-customizer.php' => 'CCT_Menu_Customizer',
            'class-typography-customizer.php' => 'CCT_Typography_Customizer',
            'class-typography-controls.php' => 'CCT_Typography_Preview_Control', // Primeira classe do arquivo
            'class-color-customizer.php' => 'CCT_Color_Customizer',
            'class-color-manager.php' => 'CCT_Color_Manager',
            'class-color-controls.php' => 'CCT_Color_Palette_Preview_Control', // Classe principal do arquivo
            'class-icon-manager.php' => 'CCT_Icon_Manager',
            'class-icon-controls.php' => 'CCT_Icon_Category_Browser_Control', // Classe principal do arquivo
            'class-layout-manager.php' => 'CCT_Layout_Manager',
            'class-layout-controls.php' => 'CCT_Grid_Preview_Control', // Classe principal do arquivo
            'class-animation-manager.php' => 'CCT_Animation_Manager',
            'class-animation-controls.php' => 'CCT_Animation_Preview_Control', // Classe principal do arquivo
            'class-gradient-manager.php' => 'CCT_Gradient_Manager',
            'class-gradient-controls.php' => 'CCT_Gradient_Browser_Control', // Classe principal do arquivo
            'class-shadow-manager.php' => 'CCT_Shadow_Manager',
            'class-shadow-controls.php' => 'CCT_Elevation_Preview_Control', // Classe principal do arquivo
            'class-pattern-library-manager.php' => 'CCT_Pattern_Library_Manager',
            'class-pattern-library-controls.php' => 'CCT_Pattern_Browser_Control', // Classe principal do arquivo
            'class-dark-mode-manager.php' => 'CCT_Dark_Mode_Manager',
            'class-responsive-breakpoints-manager.php' => 'CCT_Responsive_Breakpoints_Manager',
            'class-breakpoint-manager-control.php' => 'CCT_Breakpoint_Manager_Control',
            'class-design-tokens-manager.php' => 'CCT_Design_Tokens_Manager',
            'class-design-tokens-control.php' => 'CCT_Design_Tokens_Control',
        );
        
        // Verificar se existe mapeamento específico
        if (isset($class_map[$filename])) {
            return $class_map[$filename];
        }
        
        // Fallback para conversão automática
        $name = str_replace('.php', '', $filename);
        $parts = explode('-', $name);
        $class_name = '';
        
        foreach ($parts as $part) {
            $class_name .= ucfirst($part);
        }
        
        if (strpos($class_name, 'CCT') !== 0) {
            $class_name = 'CCT_' . $class_name;
        }
        
        return $class_name;
    }
    
    /**
     * Carrega o customizer legado para manter compatibilidade
     * 
     * @param WP_Customize_Manager $wp_customize
     */
    private function load_legacy_customizer($wp_customize) {
        // Incluir o arquivo original do customizer para funcionalidades não migradas
        $legacy_file = get_template_directory() . '/inc/customizer.php';
        
        if (file_exists($legacy_file)) {
            // Verificar se a função principal ainda não foi carregada
            if (function_exists('cct_customize_register')) {
                // Executar apenas as partes não migradas
                $this->load_legacy_sections($wp_customize);
            }
        }
    }
    
    /**
     * Carrega seções legadas não migradas
     * 
     * @param WP_Customize_Manager $wp_customize
     */
    private function load_legacy_sections($wp_customize) {
        // Aqui você pode chamar partes específicas do customizer original
        // que ainda não foram migradas para a estrutura modular
        
        // Por exemplo, se você quiser manter algumas seções do arquivo original:
        // $this->load_colors_section($wp_customize);
        // $this->load_shortcuts_section($wp_customize);
    }
    
    /**
     * Gera e exibe o CSS de todos os módulos
     */
    public function output_css() {
        $css = '';
        
        // Coletar CSS de todos os módulos carregados
        foreach ($this->modules as $module) {
            if (method_exists($module, 'generate_css')) {
                $css .= $module->generate_css();
            } elseif (method_exists($module, 'generate_menu_css')) {
                $css .= $module->generate_menu_css();
            }
        }
        
        // Adicionar CSS legado se necessário
        $css .= $this->get_legacy_css();
        
        // Exibir CSS se houver conteúdo
        if (!empty($css)) {
            echo '<style type="text/css" id="cct-customizer-css">';
            echo $this->minify_css($css);
            echo '</style>';
        }
    }
    
    /**
     * Obtém CSS do sistema legado
     * 
     * @return string CSS legado
     */
    private function get_legacy_css() {
        // Aqui você pode incluir CSS de funcionalidades não migradas
        // Por enquanto, retorna string vazia
        return '';
    }
    
    /**
     * Minifica CSS removendo espaços desnecessários
     * 
     * @param string $css CSS a ser minificado
     * @return string CSS minificado
     */
    private function minify_css($css) {
        // Remove comentários
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove espaços desnecessários
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        
        return trim($css);
    }
    
    /**
     * Obtém um módulo específico
     * 
     * @param string $class_name Nome da classe do módulo
     * @return object|null Instância do módulo ou null se não encontrado
     */
    public function get_module($class_name) {
        return isset($this->modules[$class_name]) ? $this->modules[$class_name] : null;
    }
    
    /**
     * Obtém todos os módulos carregados
     * 
     * @return array Array de módulos
     */
    public function get_all_modules() {
        return $this->modules;
    }
}

// Inicializar o carregador
CCT_Customizer_Loader::get_instance();