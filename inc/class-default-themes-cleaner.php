<?php
/**
 * Limpador de Temas Padrão do WordPress
 * Remove temas padrão (twentytwenty, twentytwentyone, etc.) tanto dos arquivos quanto do banco de dados
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class UENF_Default_Themes_Cleaner {
    private static $instance = null;
    
    /**
     * Temas padrão do WordPress que podem ser removidos
     */
    private $default_themes = array(
        'twentytwentyfour',
        'twentytwentythree', 
        'twentytwentytwo',
        'twentytwentyone',
        'twentytwenty',
        'twentynineteen',
        'twentyeighteen',
        'twentyseventeen',
        'twentysixteen',
        'twentyfifteen',
        'twentyfourteen',
        'twentythirteen',
        'twentytwelve',
        'twentyeleven',
        'twentyten'
    );
    
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
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_uenf_clean_default_themes', array($this, 'ajax_clean_default_themes'));
        add_action('wp_ajax_uenf_scan_default_themes', array($this, 'ajax_scan_default_themes'));
    }
    
    /**
     * Adiciona menu no admin
     */
    public function add_admin_menu() {
        add_submenu_page(
            'tema-uenf',
            'Limpeza de Temas Padrão',
            'Limpeza de Temas',
            'manage_options',
            'tema-uenf-clean-themes',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Renderiza a página administrativa
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1>🧹 Limpeza de Temas Padrão do WordPress</h1>
            
            <div class="notice notice-info">
                <p><strong>Atenção:</strong> Esta ferramenta remove completamente os temas padrão do WordPress, incluindo arquivos e registros no banco de dados. Esta ação não pode ser desfeita.</p>
            </div>
            
            <div class="card">
                <h2>Verificar Temas Padrão Instalados</h2>
                <p>Clique no botão abaixo para verificar quais temas padrão estão instalados no seu WordPress.</p>
                <button id="scan-themes" class="button button-secondary">🔍 Verificar Temas Padrão</button>
                <div id="scan-results" style="margin-top: 20px;"></div>
            </div>
            
            <div class="card" style="margin-top: 20px;">
                <h2>Remover Temas Padrão</h2>
                <p>Remove todos os temas padrão encontrados (arquivos + banco de dados).</p>
                <button id="clean-themes" class="button button-primary" disabled>🗑️ Remover Temas Padrão</button>
                <div id="clean-results" style="margin-top: 20px;"></div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            // Verificar temas padrão
            $('#scan-themes').on('click', function() {
                var button = $(this);
                button.prop('disabled', true).text('🔍 Verificando...');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'uenf_scan_default_themes',
                        nonce: '<?php echo wp_create_nonce('uenf_clean_themes_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#scan-results').html(response.data.html);
                            if (response.data.found_themes > 0) {
                                $('#clean-themes').prop('disabled', false);
                            }
                        } else {
                            $('#scan-results').html('<div class="notice notice-error"><p>' + response.data + '</p></div>');
                        }
                    },
                    error: function() {
                        $('#scan-results').html('<div class="notice notice-error"><p>Erro ao verificar temas.</p></div>');
                    },
                    complete: function() {
                        button.prop('disabled', false).text('🔍 Verificar Temas Padrão');
                    }
                });
            });
            
            // Limpar temas padrão
            $('#clean-themes').on('click', function() {
                if (!confirm('⚠️ ATENÇÃO: Esta ação irá remover PERMANENTEMENTE todos os temas padrão do WordPress, incluindo arquivos e registros no banco de dados.\n\nEsta ação NÃO PODE ser desfeita.\n\nTem certeza que deseja continuar?')) {
                    return;
                }
                
                var button = $(this);
                button.prop('disabled', true).text('🗑️ Removendo...');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'uenf_clean_default_themes',
                        nonce: '<?php echo wp_create_nonce('uenf_clean_themes_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#clean-results').html('<div class="notice notice-success"><p>' + response.data.message + '</p></div>');
                            // Atualizar a verificação
                            $('#scan-themes').click();
                        } else {
                            $('#clean-results').html('<div class="notice notice-error"><p>' + response.data + '</p></div>');
                        }
                    },
                    error: function() {
                        $('#clean-results').html('<div class="notice notice-error"><p>Erro ao remover temas.</p></div>');
                    },
                    complete: function() {
                        button.prop('disabled', false).text('🗑️ Remover Temas Padrão');
                    }
                });
            });
        });
        </script>
        
        <style>
        .card {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .theme-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #ddd;
            margin: 5px 0;
            border-radius: 4px;
            background: #f9f9f9;
        }
        .theme-item .dashicons {
            margin-right: 10px;
            color: #d63638;
        }
        .theme-item.active {
            background: #fff2cc;
            border-color: #f0b849;
        }
        .theme-item.active .dashicons {
            color: #f0b849;
        }
        </style>
        <?php
    }
    
    /**
     * AJAX: Verificar temas padrão instalados
     */
    public function ajax_scan_default_themes() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'uenf_clean_themes_nonce')) {
            wp_die('Erro de segurança');
        }
        
        // Verificar permissões
        if (!current_user_can('manage_options')) {
            wp_die('Permissões insuficientes');
        }
        
        $found_themes = $this->scan_default_themes();
        $current_theme = get_stylesheet();
        
        $html = '<h3>Resultados da Verificação:</h3>';
        
        if (empty($found_themes)) {
            $html .= '<div class="notice notice-success"><p>✅ Nenhum tema padrão encontrado! Seu WordPress está limpo.</p></div>';
        } else {
            $html .= '<div class="notice notice-warning"><p>⚠️ Encontrados ' . count($found_themes) . ' tema(s) padrão instalado(s):</p></div>';
            
            foreach ($found_themes as $theme_slug => $theme_data) {
                $is_active = ($theme_slug === $current_theme);
                $status_class = $is_active ? 'active' : '';
                $status_text = $is_active ? ' (ATIVO - NÃO SERÁ REMOVIDO)' : '';
                $icon = $is_active ? 'warning' : 'trash';
                
                $html .= '<div class="theme-item ' . $status_class . '">';
                $html .= '<span class="dashicons dashicons-' . $icon . '"></span>';
                $html .= '<strong>' . $theme_data['name'] . '</strong> (' . $theme_slug . ')' . $status_text;
                $html .= '</div>';
            }
            
            if (count($found_themes) > 0) {
                $removable_count = 0;
                foreach ($found_themes as $theme_slug => $theme_data) {
                    if ($theme_slug !== $current_theme) {
                        $removable_count++;
                    }
                }
                
                if ($removable_count > 0) {
                    $html .= '<p><strong>Serão removidos ' . $removable_count . ' tema(s).</strong></p>';
                } else {
                    $html .= '<div class="notice notice-info"><p>ℹ️ Todos os temas padrão encontrados estão ativos e não podem ser removidos.</p></div>';
                }
            }
        }
        
        wp_send_json_success(array(
            'html' => $html,
            'found_themes' => count($found_themes)
        ));
    }
    
    /**
     * AJAX: Limpar temas padrão
     */
    public function ajax_clean_default_themes() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'uenf_clean_themes_nonce')) {
            wp_die('Erro de segurança');
        }
        
        // Verificar permissões
        if (!current_user_can('manage_options')) {
            wp_die('Permissões insuficientes');
        }
        
        $result = $this->clean_default_themes();
        
        if ($result['success']) {
            wp_send_json_success(array(
                'message' => '✅ Limpeza concluída! ' . $result['removed_count'] . ' tema(s) removido(s) com sucesso.'
            ));
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * Verifica quais temas padrão estão instalados
     */
    private function scan_default_themes() {
        $installed_themes = wp_get_themes();
        $found_themes = array();
        
        foreach ($this->default_themes as $theme_slug) {
            if (isset($installed_themes[$theme_slug])) {
                $found_themes[$theme_slug] = array(
                    'name' => $installed_themes[$theme_slug]->get('Name'),
                    'version' => $installed_themes[$theme_slug]->get('Version'),
                    'path' => $installed_themes[$theme_slug]->get_stylesheet_directory()
                );
            }
        }
        
        return $found_themes;
    }
    
    /**
     * Remove temas padrão (arquivos + banco de dados)
     */
    private function clean_default_themes() {
        $found_themes = $this->scan_default_themes();
        $current_theme = get_stylesheet();
        $removed_count = 0;
        $errors = array();
        
        foreach ($found_themes as $theme_slug => $theme_data) {
            // Não remover o tema ativo
            if ($theme_slug === $current_theme) {
                continue;
            }
            
            // Remover arquivos do tema
            $theme_path = $theme_data['path'];
            if (is_dir($theme_path)) {
                if ($this->delete_directory($theme_path)) {
                    $removed_count++;
                    
                    // Limpar registros do banco de dados
                    $this->clean_theme_database_records($theme_slug);
                } else {
                    $errors[] = 'Erro ao remover arquivos do tema: ' . $theme_slug;
                }
            }
        }
        
        // Limpar cache de temas
        wp_clean_themes_cache();
        
        if (!empty($errors)) {
            return array(
                'success' => false,
                'message' => 'Alguns erros ocorreram: ' . implode(', ', $errors)
            );
        }
        
        return array(
            'success' => true,
            'removed_count' => $removed_count
        );
    }
    
    /**
     * Remove registros do tema no banco de dados
     */
    private function clean_theme_database_records($theme_slug) {
        global $wpdb;
        
        // Remover theme mods
        delete_option('theme_mods_' . $theme_slug);
        
        // Remover configurações do customizer
        $wpdb->delete(
            $wpdb->options,
            array('option_name' => 'theme_mods_' . $theme_slug),
            array('%s')
        );
        
        // Remover transients relacionados ao tema
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
            '_transient_%' . $theme_slug . '%'
        ));
        
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
            '_transient_timeout_%' . $theme_slug . '%'
        ));
        
        // Limpar cache
        wp_cache_delete('alloptions', 'options');
    }
    
    /**
     * Remove diretório recursivamente
     */
    private function delete_directory($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            
            if (is_dir($path)) {
                $this->delete_directory($path);
            } else {
                unlink($path);
            }
        }
        
        return rmdir($dir);
    }
}

// Inicializar a classe
function uenf_default_themes_cleaner() {
    return UENF_Default_Themes_Cleaner::get_instance();
}

// Inicializar quando o WordPress estiver pronto
add_action('init', 'uenf_default_themes_cleaner');