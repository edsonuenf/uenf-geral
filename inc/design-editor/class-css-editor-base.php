<?php
/**
 * Classe Base do Editor CSS Avançado
 * 
 * Fornece a estrutura básica para o sistema de edição CSS avançado,
 * incluindo syntax highlighting, backup automático e validação.
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
 * Classe base para o editor CSS avançado
 * 
 * Esta classe fornece funcionalidades básicas para edição de CSS
 * com recursos avançados como syntax highlighting e backup automático.
 */
class CCT_CSS_Editor_Base {
    
    /**
     * Versão do editor
     * 
     * @var string
     */
    const VERSION = '1.0.0';
    
    /**
     * Diretório de backups
     * 
     * @var string
     */
    private $backup_dir;
    
    /**
     * Arquivos CSS editáveis
     * 
     * @var array
     */
    private $editable_files;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->backup_dir = wp_upload_dir()['basedir'] . '/css-backups/';
        $this->init_editable_files();
        $this->init_hooks();
    }
    
    /**
     * Inicializa os hooks do WordPress
     */
    private function init_hooks() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_cct_save_css', array($this, 'ajax_save_css'));
        add_action('wp_ajax_cct_backup_css', array($this, 'ajax_backup_css'));
        add_action('wp_ajax_cct_restore_css', array($this, 'ajax_restore_css'));
        add_action('wp_ajax_cct_validate_css', array($this, 'ajax_validate_css'));
    }
    
    /**
     * Inicializa a lista de arquivos CSS editáveis
     */
    private function init_editable_files() {
        $theme_dir = get_template_directory();
        
        $this->editable_files = array(
            'style.css' => array(
                'path' => $theme_dir . '/style.css',
                'label' => 'Estilo Principal',
                'description' => 'Arquivo CSS principal do tema',
                'editable' => true
            ),
            'custom-fixes.css' => array(
                'path' => $theme_dir . '/css/custom-fixes.css',
                'label' => 'Correções Personalizadas',
                'description' => 'CSS para correções específicas',
                'editable' => true
            ),
            'variables.css' => array(
                'path' => $theme_dir . '/css/variables.css',
                'label' => 'Variáveis CSS',
                'description' => 'Variáveis e configurações globais',
                'editable' => true
            ),
            'components/header.css' => array(
                'path' => $theme_dir . '/css/components/header.css',
                'label' => 'Cabeçalho',
                'description' => 'Estilos do cabeçalho',
                'editable' => true
            ),
            'components/footer.css' => array(
                'path' => $theme_dir . '/css/components/footer.css',
                'label' => 'Rodapé',
                'description' => 'Estilos do rodapé',
                'editable' => true
            ),
            'components/menu-styles.css' => array(
                'path' => $theme_dir . '/css/components/menu-styles.css',
                'label' => 'Menu de Navegação',
                'description' => 'Estilos do menu principal',
                'editable' => true
            )
        );
        
        // Filtrar apenas arquivos que existem
        $this->editable_files = array_filter($this->editable_files, function($file) {
            return file_exists($file['path']);
        });
    }
    
    /**
     * Adiciona menu no admin do WordPress
     */
    public function add_admin_menu() {
        add_theme_page(
            'Editor CSS Avançado',
            'Editor CSS',
            'edit_theme_options',
            'cct-css-editor',
            array($this, 'render_editor_page')
        );
    }
    
    /**
     * Carrega scripts e estilos necessários
     */
    public function enqueue_scripts($hook) {
        if ($hook !== 'appearance_page_cct-css-editor') {
            return;
        }
        
        // CodeMirror para syntax highlighting
        wp_enqueue_script(
            'codemirror',
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js',
            array(),
            '5.65.2',
            true
        );
        
        wp_enqueue_script(
            'codemirror-css',
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js',
            array('codemirror'),
            '5.65.2',
            true
        );
        
        wp_enqueue_style(
            'codemirror-css',
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css',
            array(),
            '5.65.2'
        );
        
        wp_enqueue_style(
            'codemirror-theme',
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/material.min.css',
            array('codemirror-css'),
            '5.65.2'
        );
        
        // Script personalizado do editor
        wp_enqueue_script(
            'cct-css-editor',
            get_template_directory_uri() . '/js/css-editor.js',
            array('jquery', 'codemirror', 'codemirror-css'),
            self::VERSION,
            true
        );
        
        // Estilo personalizado do editor
        wp_enqueue_style(
            'cct-css-editor',
            get_template_directory_uri() . '/css/css-editor.css',
            array('codemirror-css'),
            self::VERSION
        );
        
        // Localização para AJAX
        wp_localize_script('cct-css-editor', 'cctCssEditor', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_css_editor'),
            'messages' => array(
                'saved' => 'CSS salvo com sucesso!',
                'backup_created' => 'Backup criado com sucesso!',
                'restored' => 'CSS restaurado com sucesso!',
                'error' => 'Erro ao processar solicitação.',
                'confirm_restore' => 'Tem certeza que deseja restaurar este backup? As alterações atuais serão perdidas.',
                'validation_error' => 'Erro de sintaxe CSS encontrado:',
                'validation_success' => 'CSS válido!'
            )
        ));
    }
    
    /**
     * Renderiza a página do editor
     */
    public function render_editor_page() {
        $current_file = isset($_GET['file']) ? sanitize_text_field($_GET['file']) : 'style.css';
        
        if (!isset($this->editable_files[$current_file])) {
            $current_file = 'style.css';
        }
        
        $file_info = $this->editable_files[$current_file];
        $file_content = file_exists($file_info['path']) ? file_get_contents($file_info['path']) : '';
        $backups = $this->get_backups($current_file);
        
        include get_template_directory() . '/inc/design-editor/templates/editor-page.php';
    }
    
    /**
     * Cria backup automático de um arquivo
     */
    public function create_backup($file_key, $content = null) {
        if (!isset($this->editable_files[$file_key])) {
            return false;
        }
        
        $file_info = $this->editable_files[$file_key];
        
        if ($content === null) {
            if (!file_exists($file_info['path'])) {
                return false;
            }
            $content = file_get_contents($file_info['path']);
        }
        
        // Criar diretório de backup se não existir
        if (!file_exists($this->backup_dir)) {
            wp_mkdir_p($this->backup_dir);
        }
        
        $backup_filename = sprintf(
            '%s_%s_%s.css',
            sanitize_file_name($file_key),
            date('Y-m-d_H-i-s'),
            wp_generate_password(8, false)
        );
        
        $backup_path = $this->backup_dir . $backup_filename;
        
        $backup_data = array(
            'timestamp' => current_time('timestamp'),
            'file_key' => $file_key,
            'file_path' => $file_info['path'],
            'user_id' => get_current_user_id(),
            'content' => $content
        );
        
        return file_put_contents($backup_path, serialize($backup_data)) !== false ? $backup_filename : false;
    }
    
    /**
     * Obtém lista de backups para um arquivo
     */
    public function get_backups($file_key) {
        if (!file_exists($this->backup_dir)) {
            return array();
        }
        
        $backups = array();
        $files = glob($this->backup_dir . sanitize_file_name($file_key) . '_*.css');
        
        foreach ($files as $file) {
            $data = unserialize(file_get_contents($file));
            if ($data && isset($data['timestamp'])) {
                $backups[] = array(
                    'filename' => basename($file),
                    'timestamp' => $data['timestamp'],
                    'date' => date('d/m/Y H:i:s', $data['timestamp']),
                    'user_id' => $data['user_id'] ?? 0
                );
            }
        }
        
        // Ordenar por timestamp (mais recente primeiro)
        usort($backups, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        return $backups;
    }
    
    /**
     * Valida sintaxe CSS
     */
    public function validate_css($css) {
        // Validação básica de CSS
        $errors = array();
        
        // Verificar chaves balanceadas
        $open_braces = substr_count($css, '{');
        $close_braces = substr_count($css, '}');
        
        if ($open_braces !== $close_braces) {
            $errors[] = 'Chaves não balanceadas: ' . $open_braces . ' aberturas, ' . $close_braces . ' fechamentos';
        }
        
        // Verificar parênteses balanceados
        $open_parens = substr_count($css, '(');
        $close_parens = substr_count($css, ')');
        
        if ($open_parens !== $close_parens) {
            $errors[] = 'Parênteses não balanceados: ' . $open_parens . ' aberturas, ' . $close_parens . ' fechamentos';
        }
        
        // Verificar comentários balanceados
        $open_comments = substr_count($css, '/*');
        $close_comments = substr_count($css, '*/');
        
        if ($open_comments !== $close_comments) {
            $errors[] = 'Comentários não balanceados: ' . $open_comments . ' aberturas, ' . $close_comments . ' fechamentos';
        }
        
        return array(
            'valid' => empty($errors),
            'errors' => $errors
        );
    }
    
    /**
     * AJAX: Salvar CSS
     */
    public function ajax_save_css() {
        check_ajax_referer('cct_css_editor', 'nonce');
        
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissão negada');
        }
        
        $file_key = sanitize_text_field($_POST['file']);
        $content = wp_unslash($_POST['content']);
        
        if (!isset($this->editable_files[$file_key])) {
            wp_send_json_error('Arquivo inválido');
        }
        
        $file_info = $this->editable_files[$file_key];
        
        // Validar CSS
        $validation = $this->validate_css($content);
        if (!$validation['valid']) {
            wp_send_json_error(array(
                'message' => 'Erro de sintaxe CSS',
                'errors' => $validation['errors']
            ));
        }
        
        // Criar backup antes de salvar
        $backup_filename = $this->create_backup($file_key);
        
        // Salvar arquivo
        $result = file_put_contents($file_info['path'], $content);
        
        if ($result !== false) {
            wp_send_json_success(array(
                'message' => 'CSS salvo com sucesso!',
                'backup' => $backup_filename
            ));
        } else {
            wp_send_json_error('Erro ao salvar arquivo');
        }
    }
    
    /**
     * AJAX: Criar backup
     */
    public function ajax_backup_css() {
        check_ajax_referer('cct_css_editor', 'nonce');
        
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissão negada');
        }
        
        $file_key = sanitize_text_field($_POST['file']);
        
        $backup_filename = $this->create_backup($file_key);
        
        if ($backup_filename) {
            wp_send_json_success(array(
                'message' => 'Backup criado com sucesso!',
                'filename' => $backup_filename
            ));
        } else {
            wp_send_json_error('Erro ao criar backup');
        }
    }
    
    /**
     * AJAX: Restaurar backup
     */
    public function ajax_restore_css() {
        check_ajax_referer('cct_css_editor', 'nonce');
        
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissão negada');
        }
        
        $backup_filename = sanitize_file_name($_POST['backup']);
        $backup_path = $this->backup_dir . $backup_filename;
        
        if (!file_exists($backup_path)) {
            wp_send_json_error('Backup não encontrado');
        }
        
        $backup_data = unserialize(file_get_contents($backup_path));
        
        if (!$backup_data || !isset($backup_data['content'])) {
            wp_send_json_error('Backup corrompido');
        }
        
        $file_key = $backup_data['file_key'];
        
        if (!isset($this->editable_files[$file_key])) {
            wp_send_json_error('Arquivo de destino inválido');
        }
        
        $file_info = $this->editable_files[$file_key];
        
        // Criar backup do estado atual antes de restaurar
        $current_backup = $this->create_backup($file_key);
        
        // Restaurar conteúdo
        $result = file_put_contents($file_info['path'], $backup_data['content']);
        
        if ($result !== false) {
            wp_send_json_success(array(
                'message' => 'CSS restaurado com sucesso!',
                'content' => $backup_data['content'],
                'current_backup' => $current_backup
            ));
        } else {
            wp_send_json_error('Erro ao restaurar arquivo');
        }
    }
    
    /**
     * AJAX: Validar CSS
     */
    public function ajax_validate_css() {
        check_ajax_referer('cct_css_editor', 'nonce');
        
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissão negada');
        }
        
        $content = wp_unslash($_POST['content']);
        $validation = $this->validate_css($content);
        
        wp_send_json_success($validation);
    }
    
    /**
     * Obtém informações dos arquivos editáveis
     */
    public function get_editable_files() {
        return $this->editable_files;
    }
}