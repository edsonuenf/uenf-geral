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
        // Remove extensão .php
        $name = str_replace('.php', '', $filename);
        
        // Converte de kebab-case para PascalCase
        $parts = explode('-', $name);
        $class_name = '';
        
        foreach ($parts as $part) {
            $class_name .= ucfirst($part);
        }
        
        // Adiciona prefixo CCT_ se não existir
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