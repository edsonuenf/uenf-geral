<?php
/**
 * Template da Página do Editor CSS Avançado
 * 
 * @package UENF_Theme
 * @subpackage Design_Editor
 * @since 1.0.0
 */

// Verificação de segurança
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap uenf-css-editor">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-editor-code"></span>
        Editor CSS Avançado
    </h1>
    
    <div class="uenf-editor-toolbar">
        <div class="uenf-file-selector">
            <label for="uenf-file-select">Arquivo:</label>
            <select id="uenf-file-select" class="uenf-file-select">
                <?php foreach ($this->get_editable_files() as $key => $file): ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($current_file, $key); ?>>
                        <?php echo esc_html($file['label']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="uenf-file-description"><?php echo esc_html($file_info['description']); ?></span>
        </div>
        
        <div class="uenf-editor-actions">
            <button type="button" id="uenf-validate-css" class="button">
                <span class="dashicons dashicons-yes-alt"></span>
                Validar CSS
            </button>
            <button type="button" id="uenf-backup-css" class="button">
                <span class="dashicons dashicons-backup"></span>
                Criar Backup
            </button>
            <button type="button" id="uenf-save-css" class="button button-primary">
                <span class="dashicons dashicons-saved"></span>
                Salvar Alterações
            </button>
        </div>
    </div>
    
    <div class="uenf-editor-container">
        <div class="uenf-editor-main">
            <div class="uenf-editor-header">
                <h3>Editando: <?php echo esc_html($file_info['label']); ?></h3>
                <div class="uenf-editor-status">
                    <span id="uenf-validation-status" class="uenf-status-indicator"></span>
                    <span id="uenf-save-status" class="uenf-status-indicator"></span>
                </div>
            </div>
            
            <div class="uenf-editor-wrapper">
                <textarea id="uenf-css-editor" name="css_content" class="uenf-css-textarea"><?php echo esc_textarea($file_content); ?></textarea>
            </div>
            
            <div class="uenf-editor-footer">
                <div class="uenf-editor-info">
                    <span class="uenf-file-path">Arquivo: <?php echo esc_html($file_info['path']); ?></span>
                    <span class="uenf-file-size">Tamanho: <?php echo size_format(strlen($file_content)); ?></span>
                    <span class="uenf-cursor-position">Linha: <span id="uenf-line-number">1</span>, Coluna: <span id="uenf-column-number">1</span></span>
                </div>
                
                <div class="uenf-editor-shortcuts">
                    <span class="uenf-shortcut">Ctrl+S: Salvar</span>
                    <span class="uenf-shortcut">Ctrl+F: Buscar</span>
                    <span class="uenf-shortcut">Ctrl+Z: Desfazer</span>
                </div>
            </div>
        </div>
        
        <div class="uenf-editor-sidebar">
            <div class="uenf-sidebar-section">
                <h4>
                    <span class="dashicons dashicons-backup"></span>
                    Backups Disponíveis
                </h4>
                
                <div class="uenf-backups-list" id="uenf-backups-list">
                    <?php if (!empty($backups)): ?>
                        <?php foreach (array_slice($backups, 0, 10) as $backup): ?>
                            <div class="uenf-backup-item" data-backup="<?php echo esc_attr($backup['filename']); ?>">
                                <div class="uenf-backup-info">
                                    <strong><?php echo esc_html($backup['date']); ?></strong>
                                    <span class="uenf-backup-user">Usuário: <?php echo esc_html(get_userdata($backup['user_id'])->display_name ?? 'Desconhecido'); ?></span>
                                </div>
                                <div class="uenf-backup-actions">
                                    <button type="button" class="button button-small uenf-restore-backup" data-backup="<?php echo esc_attr($backup['filename']); ?>">
                                        <span class="dashicons dashicons-undo"></span>
                                        Restaurar
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="uenf-no-backups">Nenhum backup disponível.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="uenf-sidebar-section">
                <h4>
                    <span class="dashicons dashicons-info"></span>
                    Dicas de CSS
                </h4>
                
                <div class="uenf-css-tips">
                    <div class="uenf-tip">
                        <strong>Variáveis CSS:</strong>
                        <code>:root { --cor-principal: #1d3771; }</code>
                    </div>
                    
                    <div class="uenf-tip">
                        <strong>Media Queries:</strong>
                        <code>@media (max-width: 768px) { ... }</code>
                    </div>
                    
                    <div class="uenf-tip">
                        <strong>Flexbox:</strong>
                        <code>display: flex; justify-content: center;</code>
                    </div>
                    
                    <div class="uenf-tip">
                        <strong>Grid:</strong>
                        <code>display: grid; grid-template-columns: 1fr 1fr;</code>
                    </div>
                </div>
            </div>
            
            <div class="uenf-sidebar-section">
                <h4>
                    <span class="dashicons dashicons-admin-tools"></span>
                    Ferramentas
                </h4>
                
                <div class="uenf-tools">
                    <button type="button" id="uenf-format-css" class="button button-secondary">
                        <span class="dashicons dashicons-editor-alignleft"></span>
                        Formatar CSS
                    </button>
                    
                    <button type="button" id="uenf-minify-css" class="button button-secondary">
                        <span class="dashicons dashicons-editor-contract"></span>
                        Minificar CSS
                    </button>
                    
                    <button type="button" id="uenf-add-prefixes" class="button button-secondary">
                        <span class="dashicons dashicons-admin-plugins"></span>
                        Adicionar Prefixos
                    </button>
                </div>
            </div>
            
            <div class="uenf-sidebar-section">
                <h4>
                    <span class="dashicons dashicons-chart-line"></span>
                    Estatísticas
                </h4>
                
                <div class="uenf-stats">
                    <div class="uenf-stat">
                        <span class="uenf-stat-label">Linhas:</span>
                        <span class="uenf-stat-value" id="uenf-lines-count"><?php echo substr_count($file_content, "\n") + 1; ?></span>
                    </div>
                    
                    <div class="uenf-stat">
                        <span class="uenf-stat-label">Caracteres:</span>
                        <span class="uenf-stat-value" id="uenf-chars-count"><?php echo strlen($file_content); ?></span>
                    </div>
                    
                    <div class="uenf-stat">
                        <span class="uenf-stat-label">Seletores:</span>
                        <span class="uenf-stat-value" id="uenf-selectors-count"><?php echo substr_count($file_content, '{'); ?></span>
                    </div>
                    
                    <div class="uenf-stat">
                        <span class="uenf-stat-label">Comentários:</span>
                        <span class="uenf-stat-value" id="uenf-comments-count"><?php echo substr_count($file_content, '/*'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Confirmação -->
    <div id="uenf-confirm-modal" class="uenf-modal" style="display: none;">
        <div class="uenf-modal-content">
            <div class="uenf-modal-header">
                <h3>Confirmar Ação</h3>
                <button type="button" class="uenf-modal-close">&times;</button>
            </div>
            <div class="uenf-modal-body">
                <p id="uenf-confirm-message"></p>
            </div>
            <div class="uenf-modal-footer">
                <button type="button" class="button" id="uenf-confirm-cancel">Cancelar</button>
                <button type="button" class="button button-primary" id="uenf-confirm-ok">Confirmar</button>
            </div>
        </div>
    </div>
    
    <!-- Área de Notificações -->
    <div id="uenf-notifications" class="uenf-notifications"></div>
</div>

<script type="text/javascript">
// Dados do arquivo atual para JavaScript
window.cctEditorData = {
    currentFile: <?php echo json_encode($current_file); ?>,
    fileInfo: <?php echo json_encode($file_info); ?>,
    editableFiles: <?php echo json_encode($this->get_editable_files()); ?>
};
</script>