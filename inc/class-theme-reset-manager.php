<?php
/**
 * Gerenciador de Reset de Configurações do Tema
 * 
 * Permite resetar configurações de extensões e do tema para valores padrão
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class UENF_Theme_Reset_Manager {
    private static $instance = null;
    
    /**
     * Singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Inicializa hooks
     */
    private function init_hooks() {
        add_action('wp_ajax_uenf_reset_theme_settings', array($this, 'ajax_reset_theme_settings'));
        add_action('wp_ajax_uenf_reset_extension_settings', array($this, 'ajax_reset_extension_settings'));
        add_action('wp_ajax_uenf_reset_all_settings', array($this, 'ajax_reset_all_settings'));
        add_action('wp_ajax_uenf_create_backup', array($this, 'ajax_create_backup'));
        add_action('wp_ajax_uenf_restore_backup', array($this, 'ajax_restore_backup'));
        // add_action('customize_register', array($this, 'add_reset_controls'), 999); // Removido: duplicação com menu Tema UENF
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_reset_scripts'));
    }
    
    /**
     * Adiciona controles de reset no customizer
     */
    public function add_reset_controls($wp_customize) {
        // Carregar controles customizados apenas quando necessário
        require_once get_template_directory() . '/inc/customizer/class-reset-controls.php';
        
        // Seção de Reset
        $wp_customize->add_section('uenf_reset_section', array(
            'title' => '🔄 Reset de Configurações',
            'description' => 'Restaure configurações para valores padrão. <strong>Atenção:</strong> Esta ação não pode ser desfeita.',
            'panel' => 'uenf_theme_uenf',
            'priority' => 999,
        ));
        
        // Controle para reset de extensões individuais
        $wp_customize->add_setting('uenf_reset_extensions', array(
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control(new UENF_Reset_Extensions_Control(
            $wp_customize,
            'uenf_reset_extensions',
            array(
                'label' => 'Reset de Extensões Individuais',
                'description' => 'Selecione extensões específicas para resetar suas configurações.',
                'section' => 'uenf_reset_section',
                'priority' => 10,
            )
        ));
        
        // Controle para reset completo
        $wp_customize->add_setting('uenf_reset_all', array(
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control(new UENF_Reset_All_Control(
            $wp_customize,
            'uenf_reset_all',
            array(
                'label' => 'Reset Completo do Tema',
                'description' => 'Remove TODAS as configurações do tema e extensões.',
                'section' => 'uenf_reset_section',
                'priority' => 20,
            )
        ));
    }
    
    /**
     * Enfileira scripts do reset manager
     */
    public function enqueue_reset_scripts() {
        wp_enqueue_script(
            'uenf-reset-manager',
            get_template_directory_uri() . '/js/admin/reset-manager.js',
            array('jquery', 'customize-controls'),
            '1.0.0',
            true
        );
        
        wp_localize_script('uenf-reset-manager', 'uenfResetManager', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('uenf_reset_nonce'),
            'extensions' => $this->get_available_extensions(),
            'messages' => array(
                'confirmReset' => 'Tem certeza que deseja resetar as extensões selecionadas? Esta ação não pode ser desfeita.',
                'confirmResetAll' => 'ATENÇÃO: Esta ação irá resetar TODAS as configurações do tema e extensões. Esta ação não pode ser desfeita. Tem certeza que deseja continuar?',
                'resetSuccess' => 'Configurações resetadas com sucesso!',
                'resetError' => 'Erro ao resetar configurações. Tente novamente.',
                'processing' => 'Processando...'
            )
        ));
    }
    
    /**
     * Obtém extensões disponíveis
     */
    public function get_available_extensions() {
        if (function_exists('uenf_extension_manager')) {
            return uenf_extension_manager()->get_all_extensions();
        }
        return array();
    }
    
    /**
     * AJAX: Reset configurações do tema
     */
    public function ajax_reset_theme_settings() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'uenf_reset_nonce')) {
            wp_die('Erro de segurança');
        }
        
        // Verificar permissões
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissões insuficientes');
        }
        
        $result = $this->reset_theme_settings();
        
        wp_send_json(array(
            'success' => $result,
            'message' => $result ? 'Configurações do tema resetadas com sucesso!' : 'Erro ao resetar configurações do tema.'
        ));
    }
    
    /**
     * AJAX: Reset configurações de extensão
     */
    public function ajax_reset_extension_settings() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'uenf_reset_nonce')) {
            wp_die('Erro de segurança');
        }
        
        // Verificar permissões
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissões insuficientes');
        }
        
        $extension_id = sanitize_text_field($_POST['extension_id']);
        $result = $this->reset_extension_settings($extension_id);
        
        wp_send_json(array(
            'success' => $result,
            'message' => $result ? 'Configurações da extensão resetadas com sucesso!' : 'Erro ao resetar configurações da extensão.'
        ));
    }
    
    /**
     * AJAX: Reset todas as configurações
     */
    public function ajax_reset_all_settings() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'uenf_reset_nonce')) {
            wp_die('Erro de segurança');
        }
        
        // Verificar permissões
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissões insuficientes');
        }
        
        $result = $this->reset_all_settings();
        
        wp_send_json(array(
            'success' => $result,
            'message' => $result ? 'Todas as configurações resetadas com sucesso!' : 'Erro ao resetar todas as configurações.'
        ));
    }
    
    /**
     * Reset configurações do tema
     */
    public function reset_theme_settings() {
        try {
            // Remover todas as theme_mods
            $theme_mods = get_theme_mods();
            foreach ($theme_mods as $mod_name => $mod_value) {
                remove_theme_mod($mod_name);
            }

            // Reaplicar padrões de ativação das extensões
            if (function_exists('uenf_extension_manager')) {
                uenf_extension_manager()->enforce_default_activation();
            }
            
            // Log da ação
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UENF Theme Reset: Configurações do tema resetadas');
            }
            
            return true;
            
        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UENF Reset Theme Error: ' . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Reset configurações de uma extensão específica
     */
    public function reset_extension_settings($extension_id) {
        try {
            if (function_exists('uenf_extension_manager')) {
                return uenf_extension_manager()->reset_extension_settings($extension_id);
            }
            return false;
            
        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UENF Reset Extension Error: ' . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Reset todas as configurações
     */
    public function reset_all_settings() {
        try {
            // Reset configurações do tema
            $theme_mods = get_theme_mods();
            foreach ($theme_mods as $mod_name => $mod_value) {
                remove_theme_mod($mod_name);
            }
            
            // Reset configuração global de extensões
            delete_option('uenf_extensions_config');

            // Reaplicar padrões de ativação das extensões
            if (function_exists('uenf_extension_manager')) {
                uenf_extension_manager()->enforce_default_activation();
            }
            
            // Log da ação
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UENF Theme Reset: Todas as configurações resetadas');
            }
            
            return true;
            
        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UENF Reset All Error: ' . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Cria backup das configurações atuais
     */
    public function create_settings_backup() {
        try {
            $backup_data = array(
                'timestamp' => current_time('timestamp'),
                'theme_mods' => get_theme_mods(),
                'extensions_config' => get_option('uenf_extensions_config', array())
            );
            
            $backup_key = 'uenf_theme_backup_' . date('Y_m_d_H_i_s');
            
            if (add_option($backup_key, $backup_data)) {
                // Log da ação
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('UENF Theme Reset: Backup criado - ' . $backup_key);
                }
                
                // Manter apenas os 5 backups mais recentes
                $this->cleanup_old_backups();
                
                return $backup_key;
            }
            
            return false;
            
        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UENF Create Backup Error: ' . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Remove backups antigos
     */
    private function cleanup_old_backups() {
        global $wpdb;
        
        // SECURITY FIX: PHP-A05 — Adicionado $wpdb->prepare() conforme WordPress Coding Standards.
        // O LIKE era hardcoded (sem variável de usuário), mas o padrão sem prepare() em código de
        // componente crítico (reset manager) é perigoso e viola as boas práticas do WordPress.
        $backups = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name FROM {$wpdb->options}
                 WHERE option_name LIKE %s
                 ORDER BY option_name DESC",
                'uenf_theme_backup_%'
            )
        );
        
        if (count($backups) > 5) {
            $old_backups = array_slice($backups, 5);
            foreach ($old_backups as $backup) {
                delete_option($backup->option_name);
            }
        }
    }
    
    /**
     * Restaura configurações de um backup
     */
    public function restore_settings_backup($backup_key) {
        try {
            $backup_data = get_option($backup_key);
            
            if (!$backup_data || !isset($backup_data['theme_mods'])) {
                return false;
            }
            
            // Remover todas as configurações atuais
            $current_mods = get_theme_mods();
            foreach ($current_mods as $mod_name => $mod_value) {
                remove_theme_mod($mod_name);
            }
            
            // Restaurar configurações do backup
            foreach ($backup_data['theme_mods'] as $mod_name => $mod_value) {
                set_theme_mod($mod_name, $mod_value);
            }
            
            // Log da ação
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UENF Theme Reset: Backup restaurado - ' . $backup_key);
            }
            
            return true;
            
        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UENF Restore Backup Error: ' . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * AJAX: Criar backup
     */
    public function ajax_create_backup() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'uenf_reset_nonce')) {
            wp_die('Erro de segurança');
        }
        
        // Verificar permissões
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissões insuficientes');
        }
        
        $result = $this->create_settings_backup();
        
        wp_send_json(array(
            'success' => $result !== false,
            'backup_key' => $result,
            'message' => $result !== false ? 'Backup criado com sucesso!' : 'Erro ao criar backup.'
        ));
    }
    
    /**
     * AJAX: Restaurar backup
     */
    public function ajax_restore_backup() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'uenf_reset_nonce')) {
            wp_die('Erro de segurança');
        }
        
        // Verificar permissões
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissões insuficientes');
        }
        
        $backup_key = sanitize_text_field($_POST['backup_key']);
        $result = $this->restore_settings_backup($backup_key);
        
        wp_send_json(array(
            'success' => $result,
            'message' => $result ? 'Backup restaurado com sucesso!' : 'Erro ao restaurar backup.'
        ));
    }
}

/**
 * Função helper para acessar o gerenciador
 */
function uenf_reset_manager() {
    return UENF_Theme_Reset_Manager::get_instance();
}

// Inicializar o gerenciador
add_action('init', 'uenf_reset_manager');