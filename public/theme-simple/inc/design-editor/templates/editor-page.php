<?php
/**
 * Template da Página do Editor CSS Avançado
 * 
 * @package CCT_Theme
 * @subpackage Design_Editor
 * @since 1.0.0
 */

// Verificação de segurança
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap cct-css-editor">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-editor-code"></span>
        Editor CSS Avançado
    </h1>
    
    <div class="cct-editor-toolbar">
        <div class="cct-file-selector">
            <label for="cct-file-select">Arquivo:</label>
            <select id="cct-file-select" class="cct-file-select">
                <?php foreach ($this->get_editable_files() as $key => $file): ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($current_file, $key); ?>>
                        <?php echo esc_html($file['label']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="cct-file-description"><?php echo esc_html($file_info['description']); ?></span>
        </div>
        
        <div class="cct-editor-actions">
            <button type="button" id="cct-validate-css" class="button">
                <span class="dashicons dashicons-yes-alt"></span>
                Validar CSS
            </button>
            <button type="button" id="cct-backup-css" class="button">
                <span class="dashicons dashicons-backup"></span>
                Criar Backup
            </button>
            <button type="button" id="cct-save-css" class="button button-primary">
                <span class="dashicons dashicons-saved"></span>
                Salvar Alterações
            </button>
        </div>
    </div>
    
    <div class="cct-editor-container">
        <div class="cct-editor-main">
            <div class="cct-editor-header">
                <h3>Editando: <?php echo esc_html($file_info['label']); ?></h3>
                <div class="cct-editor-status">
                    <span id="cct-validation-status" class="cct-status-indicator"></span>
                    <span id="cct-save-status" class="cct-status-indicator"></span>
                </div>
            </div>
            
            <div class="cct-editor-wrapper">
                <textarea id="cct-css-editor" name="css_content" class="cct-css-textarea"><?php echo esc_textarea($file_content); ?></textarea>
            </div>
            
            <div class="cct-editor-footer">
                <div class="cct-editor-info">
                    <span class="cct-file-path">Arquivo: <?php echo esc_html($file_info['path']); ?></span>
                    <span class="cct-file-size">Tamanho: <?php echo size_format(strlen($file_content)); ?></span>
                    <span class="cct-cursor-position">Linha: <span id="cct-line-number">1</span>, Coluna: <span id="cct-column-number">1</span></span>
                </div>
                
                <div class="cct-editor-shortcuts">
                    <span class="cct-shortcut">Ctrl+S: Salvar</span>
                    <span class="cct-shortcut">Ctrl+F: Buscar</span>
                    <span class="cct-shortcut">Ctrl+Z: Desfazer</span>
                </div>
            </div>
        </div>
        
        <div class="cct-editor-sidebar">
            <div class="cct-sidebar-section">
                <h4>
                    <span class="dashicons dashicons-backup"></span>
                    Backups Disponíveis
                </h4>
                
                <div class="cct-backups-list" id="cct-backups-list">
                    <?php if (!empty($backups)): ?>
                        <?php foreach (array_slice($backups, 0, 10) as $backup): ?>
                            <div class="cct-backup-item" data-backup="<?php echo esc_attr($backup['filename']); ?>">
                                <div class="cct-backup-info">
                                    <strong><?php echo esc_html($backup['date']); ?></strong>
                                    <span class="cct-backup-user">Usuário: <?php echo esc_html(get_userdata($backup['user_id'])->display_name ?? 'Desconhecido'); ?></span>
                                </div>
                                <div class="cct-backup-actions">
                                    <button type="button" class="button button-small cct-restore-backup" data-backup="<?php echo esc_attr($backup['filename']); ?>">
                                        <span class="dashicons dashicons-undo"></span>
                                        Restaurar
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="cct-no-backups">Nenhum backup disponível.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="cct-sidebar-section">
                <h4>
                    <span class="dashicons dashicons-info"></span>
                    Dicas de CSS
                </h4>
                
                <div class="cct-css-tips">
                    <div class="cct-tip">
                        <strong>Variáveis CSS:</strong>
                        <code>:root { --cor-principal: #1d3771; }</code>
                    </div>
                    
                    <div class="cct-tip">
                        <strong>Media Queries:</strong>
                        <code>@media (max-width: 768px) { ... }</code>
                    </div>
                    
                    <div class="cct-tip">
                        <strong>Flexbox:</strong>
                        <code>display: flex; justify-content: center;</code>
                    </div>
                    
                    <div class="cct-tip">
                        <strong>Grid:</strong>
                        <code>display: grid; grid-template-columns: 1fr 1fr;</code>
                    </div>
                </div>
            </div>
            
            <div class="cct-sidebar-section">
                <h4>
                    <span class="dashicons dashicons-admin-tools"></span>
                    Ferramentas
                </h4>
                
                <div class="cct-tools">
                    <button type="button" id="cct-format-css" class="button button-secondary">
                        <span class="dashicons dashicons-editor-alignleft"></span>
                        Formatar CSS
                    </button>
                    
                    <button type="button" id="cct-minify-css" class="button button-secondary">
                        <span class="dashicons dashicons-editor-contract"></span>
                        Minificar CSS
                    </button>
                    
                    <button type="button" id="cct-add-prefixes" class="button button-secondary">
                        <span class="dashicons dashicons-admin-plugins"></span>
                        Adicionar Prefixos
                    </button>
                </div>
            </div>
            
            <div class="cct-sidebar-section">
                <h4>
                    <span class="dashicons dashicons-chart-line"></span>
                    Estatísticas
                </h4>
                
                <div class="cct-stats">
                    <div class="cct-stat">
                        <span class="cct-stat-label">Linhas:</span>
                        <span class="cct-stat-value" id="cct-lines-count"><?php echo substr_count($file_content, "\n") + 1; ?></span>
                    </div>
                    
                    <div class="cct-stat">
                        <span class="cct-stat-label">Caracteres:</span>
                        <span class="cct-stat-value" id="cct-chars-count"><?php echo strlen($file_content); ?></span>
                    </div>
                    
                    <div class="cct-stat">
                        <span class="cct-stat-label">Seletores:</span>
                        <span class="cct-stat-value" id="cct-selectors-count"><?php echo substr_count($file_content, '{'); ?></span>
                    </div>
                    
                    <div class="cct-stat">
                        <span class="cct-stat-label">Comentários:</span>
                        <span class="cct-stat-value" id="cct-comments-count"><?php echo substr_count($file_content, '/*'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Confirmação -->
    <div id="cct-confirm-modal" class="cct-modal" style="display: none;">
        <div class="cct-modal-content">
            <div class="cct-modal-header">
                <h3>Confirmar Ação</h3>
                <button type="button" class="cct-modal-close">&times;</button>
            </div>
            <div class="cct-modal-body">
                <p id="cct-confirm-message"></p>
            </div>
            <div class="cct-modal-footer">
                <button type="button" class="button" id="cct-confirm-cancel">Cancelar</button>
                <button type="button" class="button button-primary" id="cct-confirm-ok">Confirmar</button>
            </div>
        </div>
    </div>
    
    <!-- Área de Notificações -->
    <div id="cct-notifications" class="cct-notifications"></div>
</div>

<script type="text/javascript">
// Dados do arquivo atual para JavaScript
window.cctEditorData = {
    currentFile: <?php echo json_encode($current_file); ?>,
    fileInfo: <?php echo json_encode($file_info); ?>,
    editableFiles: <?php echo json_encode($this->get_editable_files()); ?>
};
</script>