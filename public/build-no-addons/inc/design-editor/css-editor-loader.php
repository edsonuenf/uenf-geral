<?php
/**
 * Carregador do Editor CSS Avançado
 * 
 * Inicializa e gerencia o sistema de edição CSS avançado,
 * seguindo o padrão modular estabelecido no customizer.
 * 
 * @package CCT_Theme
 * @subpackage Design_Editor
 * @since 1.0.0
 */

// Verificação de segurança
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe principal do carregador do Editor CSS
 */
class CCT_CSS_Editor_Loader {
    
    /**
     * Instância única da classe (Singleton)
     * 
     * @var CCT_CSS_Editor_Loader
     */
    private static $instance = null;
    
    /**
     * Instância do editor CSS
     * 
     * @var CCT_CSS_Editor_Base
     */
    private $css_editor;
    
    /**
     * Diretório dos módulos do editor
     * 
     * @var string
     */
    private $modules_dir;
    
    /**
     * Construtor privado (Singleton)
     */
    private function __construct() {
        $this->modules_dir = get_template_directory() . '/inc/design-editor/';
        $this->init();
    }
    
    /**
     * Obtém a instância única da classe
     * 
     * @return CCT_CSS_Editor_Loader
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
        // Carregar dependências
        $this->load_dependencies();
        
        // Inicializar editor se usuário tem permissão
        if (current_user_can('edit_theme_options')) {
            $this->init_css_editor();
        }
        
        // Hooks de inicialização
        add_action('init', array($this, 'init_hooks'));
        add_action('admin_init', array($this, 'check_requirements'));
    }
    
    /**
     * Carrega as dependências necessárias
     */
    private function load_dependencies() {
        // Carregar classe base do editor
        $base_file = $this->modules_dir . 'class-css-editor-base.php';
        if (file_exists($base_file)) {
            require_once $base_file;
        } else {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Editor CSS: Arquivo base não encontrado.</p></div>';
            });
            return false;
        }
        
        return true;
    }
    
    /**
     * Inicializa o editor CSS
     */
    private function init_css_editor() {
        if (class_exists('CCT_CSS_Editor_Base')) {
            $this->css_editor = new CCT_CSS_Editor_Base();
        }
    }
    
    /**
     * Inicializa hooks do WordPress
     */
    public function init_hooks() {
        // Hook para adicionar link no menu de aparência
        add_action('admin_menu', array($this, 'add_appearance_link'), 20);
        
        // Hook para adicionar capacidades
        add_action('admin_init', array($this, 'add_capabilities'));
        
        // Hook para limpeza de backups antigos
        add_action('cct_cleanup_css_backups', array($this, 'cleanup_old_backups'));
        
        // Agendar limpeza de backups (diário)
        if (!wp_next_scheduled('cct_cleanup_css_backups')) {
            wp_schedule_event(time(), 'daily', 'cct_cleanup_css_backups');
        }
    }
    
    /**
     * Verifica requisitos do sistema
     */
    public function check_requirements() {
        $requirements = array(
            'php_version' => '7.4',
            'wp_version' => '5.0',
            'functions' => array('file_get_contents', 'file_put_contents', 'wp_mkdir_p')
        );
        
        $errors = array();
        
        // Verificar versão do PHP
        if (version_compare(PHP_VERSION, $requirements['php_version'], '<')) {
            $errors[] = sprintf(
                'Editor CSS requer PHP %s ou superior. Versão atual: %s',
                $requirements['php_version'],
                PHP_VERSION
            );
        }
        
        // Verificar versão do WordPress
        global $wp_version;
        if (version_compare($wp_version, $requirements['wp_version'], '<')) {
            $errors[] = sprintf(
                'Editor CSS requer WordPress %s ou superior. Versão atual: %s',
                $requirements['wp_version'],
                $wp_version
            );
        }
        
        // Verificar funções necessárias
        foreach ($requirements['functions'] as $function) {
            if (!function_exists($function)) {
                $errors[] = sprintf('Função necessária não disponível: %s', $function);
            }
        }
        
        // Verificar permissões de escrita
        $upload_dir = wp_upload_dir();
        if (!is_writable($upload_dir['basedir'])) {
            $errors[] = 'Diretório de uploads não tem permissão de escrita.';
        }
        
        // Exibir erros se houver
        if (!empty($errors)) {
            add_action('admin_notices', function() use ($errors) {
                echo '<div class="notice notice-error">';
                echo '<p><strong>Editor CSS - Requisitos não atendidos:</strong></p>';
                echo '<ul>';
                foreach ($errors as $error) {
                    echo '<li>' . esc_html($error) . '</li>';
                }
                echo '</ul>';
                echo '</div>';
            });
        }
    }
    
    /**
     * Adiciona link no menu de aparência
     */
    public function add_appearance_link() {
        // Link já é adicionado pela classe base, mas podemos adicionar submenu adicional
        add_submenu_page(
            'themes.php',
            'Configurações do Editor CSS',
            'Config. Editor CSS',
            'manage_options',
            'cct-css-editor-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Adiciona capacidades necessárias
     */
    public function add_capabilities() {
        $role = get_role('administrator');
        if ($role) {
            $role->add_cap('cct_edit_css');
            $role->add_cap('cct_manage_css_backups');
        }
        
        // Adicionar para editores também
        $editor_role = get_role('editor');
        if ($editor_role) {
            $editor_role->add_cap('cct_edit_css');
        }
    }
    
    /**
     * Renderiza página de configurações
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die('Acesso negado');
        }
        
        // Processar formulário se enviado
        if (isset($_POST['submit']) && wp_verify_nonce($_POST['_wpnonce'], 'cct_css_editor_settings')) {
            $this->save_settings();
        }
        
        $settings = $this->get_settings();
        
        include $this->modules_dir . 'templates/settings-page.php';
    }
    
    /**
     * Obtém configurações do editor
     */
    public function get_settings() {
        return wp_parse_args(get_option('cct_css_editor_settings', array()), array(
            'auto_backup' => true,
            'backup_retention_days' => 30,
            'enable_validation' => true,
            'enable_minification' => false,
            'editor_theme' => 'material',
            'font_size' => 14,
            'tab_size' => 2,
            'line_wrapping' => true,
            'show_line_numbers' => true,
            'enable_autocomplete' => true
        ));
    }
    
    /**
     * Salva configurações do editor
     */
    private function save_settings() {
        $settings = array(
            'auto_backup' => isset($_POST['auto_backup']),
            'backup_retention_days' => absint($_POST['backup_retention_days']),
            'enable_validation' => isset($_POST['enable_validation']),
            'enable_minification' => isset($_POST['enable_minification']),
            'editor_theme' => sanitize_text_field($_POST['editor_theme']),
            'font_size' => absint($_POST['font_size']),
            'tab_size' => absint($_POST['tab_size']),
            'line_wrapping' => isset($_POST['line_wrapping']),
            'show_line_numbers' => isset($_POST['show_line_numbers']),
            'enable_autocomplete' => isset($_POST['enable_autocomplete'])
        );
        
        update_option('cct_css_editor_settings', $settings);
        
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Configurações salvas com sucesso!</p></div>';
        });
    }
    
    /**
     * Limpa backups antigos
     */
    public function cleanup_old_backups() {
        $settings = $this->get_settings();
        $retention_days = $settings['backup_retention_days'];
        
        if ($retention_days <= 0) {
            return; // Não limpar se configurado para manter indefinidamente
        }
        
        $upload_dir = wp_upload_dir();
        $backup_dir = $upload_dir['basedir'] . '/css-backups/';
        
        if (!file_exists($backup_dir)) {
            return;
        }
        
        $files = glob($backup_dir . '*.css');
        $cutoff_time = time() - ($retention_days * DAY_IN_SECONDS);
        $deleted_count = 0;
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoff_time) {
                if (unlink($file)) {
                    $deleted_count++;
                }
            }
        }
        
        // Log da limpeza
        if ($deleted_count > 0) {
            error_log(sprintf(
                'CCT CSS Editor: %d backups antigos foram removidos.',
                $deleted_count
            ));
        }
    }
    
    /**
     * Obtém estatísticas do editor
     */
    public function get_statistics() {
        $upload_dir = wp_upload_dir();
        $backup_dir = $upload_dir['basedir'] . '/css-backups/';
        
        $stats = array(
            'total_backups' => 0,
            'backup_size' => 0,
            'oldest_backup' => null,
            'newest_backup' => null
        );
        
        if (file_exists($backup_dir)) {
            $files = glob($backup_dir . '*.css');
            $stats['total_backups'] = count($files);
            
            $total_size = 0;
            $oldest_time = PHP_INT_MAX;
            $newest_time = 0;
            
            foreach ($files as $file) {
                $size = filesize($file);
                $time = filemtime($file);
                
                $total_size += $size;
                
                if ($time < $oldest_time) {
                    $oldest_time = $time;
                    $stats['oldest_backup'] = $time;
                }
                
                if ($time > $newest_time) {
                    $newest_time = $time;
                    $stats['newest_backup'] = $time;
                }
            }
            
            $stats['backup_size'] = $total_size;
        }
        
        return $stats;
    }
    
    /**
     * Obtém instância do editor CSS
     */
    public function get_css_editor() {
        return $this->css_editor;
    }
    
    /**
     * Verifica se o editor está ativo
     */
    public function is_editor_active() {
        return $this->css_editor !== null;
    }
    
    /**
     * Desativa hooks ao destruir
     */
    public function __destruct() {
        // Limpar hooks se necessário
    }
}

// Inicializar o carregador
CCT_CSS_Editor_Loader::get_instance();

/**
 * Função helper para obter instância do editor
 * 
 * @return CCT_CSS_Editor_Loader
 */
function cct_get_css_editor_loader() {
    return CCT_CSS_Editor_Loader::get_instance();
}

/**
 * Função helper para verificar se o editor está disponível
 * 
 * @return bool
 */
function cct_is_css_editor_available() {
    $loader = CCT_CSS_Editor_Loader::get_instance();
    return $loader->is_editor_active() && current_user_can('edit_theme_options');
}