<?php
/**
 * Template da Página de Configurações do Editor CSS
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

<div class="wrap cct-css-editor-settings">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-admin-settings"></span>
        Configurações do Editor CSS
    </h1>
    
    <p class="description">
        Configure as opções do Editor CSS Avançado para personalizar sua experiência de edição.
    </p>
    
    <form method="post" action="">
        <?php wp_nonce_field('cct_css_editor_settings'); ?>
        
        <table class="form-table" role="presentation">
            <tbody>
                <!-- Configurações de Backup -->
                <tr>
                    <th scope="row">
                        <label for="auto_backup">Backup Automático</label>
                    </th>
                    <td>
                        <fieldset>
                            <label for="auto_backup">
                                <input name="auto_backup" type="checkbox" id="auto_backup" value="1" <?php checked($settings['auto_backup']); ?>>
                                Criar backup automaticamente antes de salvar
                            </label>
                            <p class="description">
                                Quando ativado, um backup será criado automaticamente antes de cada salvamento.
                            </p>
                        </fieldset>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="backup_retention_days">Retenção de Backups</label>
                    </th>
                    <td>
                        <input name="backup_retention_days" type="number" id="backup_retention_days" value="<?php echo esc_attr($settings['backup_retention_days']); ?>" min="1" max="365" class="small-text">
                        <span>dias</span>
                        <p class="description">
                            Número de dias para manter os backups. Backups mais antigos serão removidos automaticamente.
                        </p>
                    </td>
                </tr>
                
                <!-- Configurações de Validação -->
                <tr>
                    <th scope="row">
                        <label for="enable_validation">Validação de CSS</label>
                    </th>
                    <td>
                        <fieldset>
                            <label for="enable_validation">
                                <input name="enable_validation" type="checkbox" id="enable_validation" value="1" <?php checked($settings['enable_validation']); ?>>
                                Ativar validação automática de CSS
                            </label>
                            <p class="description">
                                Valida a sintaxe do CSS em tempo real enquanto você digita.
                            </p>
                        </fieldset>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="enable_minification">Minificação</label>
                    </th>
                    <td>
                        <fieldset>
                            <label for="enable_minification">
                                <input name="enable_minification" type="checkbox" id="enable_minification" value="1" <?php checked($settings['enable_minification']); ?>>
                                Minificar CSS automaticamente ao salvar
                            </label>
                            <p class="description">
                                Remove espaços e comentários desnecessários para otimizar o tamanho do arquivo.
                            </p>
                        </fieldset>
                    </td>
                </tr>
                
                <!-- Configurações do Editor -->
                <tr>
                    <th scope="row">
                        <label for="editor_theme">Tema do Editor</label>
                    </th>
                    <td>
                        <select name="editor_theme" id="editor_theme">
                            <option value="material" <?php selected($settings['editor_theme'], 'material'); ?>>Material Design</option>
                            <option value="monokai" <?php selected($settings['editor_theme'], 'monokai'); ?>>Monokai</option>
                            <option value="dracula" <?php selected($settings['editor_theme'], 'dracula'); ?>>Dracula</option>
                            <option value="solarized" <?php selected($settings['editor_theme'], 'solarized'); ?>>Solarized</option>
                            <option value="default" <?php selected($settings['editor_theme'], 'default'); ?>>Padrão</option>
                        </select>
                        <p class="description">
                            Escolha o tema de cores para o editor de código.
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="font_size">Tamanho da Fonte</label>
                    </th>
                    <td>
                        <input name="font_size" type="number" id="font_size" value="<?php echo esc_attr($settings['font_size']); ?>" min="10" max="24" class="small-text">
                        <span>px</span>
                        <p class="description">
                            Tamanho da fonte no editor de código (10-24px).
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="tab_size">Tamanho da Tabulação</label>
                    </th>
                    <td>
                        <select name="tab_size" id="tab_size">
                            <option value="2" <?php selected($settings['tab_size'], 2); ?>>2 espaços</option>
                            <option value="4" <?php selected($settings['tab_size'], 4); ?>>4 espaços</option>
                            <option value="8" <?php selected($settings['tab_size'], 8); ?>>8 espaços</option>
                        </select>
                        <p class="description">
                            Número de espaços para cada nível de indentação.
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="line_wrapping">Quebra de Linha</label>
                    </th>
                    <td>
                        <fieldset>
                            <label for="line_wrapping">
                                <input name="line_wrapping" type="checkbox" id="line_wrapping" value="1" <?php checked($settings['line_wrapping']); ?>>
                                Quebrar linhas longas automaticamente
                            </label>
                            <p class="description">
                                Quebra linhas que excedem a largura do editor.
                            </p>
                        </fieldset>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="show_line_numbers">Numeração de Linhas</label>
                    </th>
                    <td>
                        <fieldset>
                            <label for="show_line_numbers">
                                <input name="show_line_numbers" type="checkbox" id="show_line_numbers" value="1" <?php checked($settings['show_line_numbers']); ?>>
                                Mostrar números das linhas
                            </label>
                            <p class="description">
                                Exibe a numeração das linhas na lateral esquerda do editor.
                            </p>
                        </fieldset>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="enable_autocomplete">Autocomplete</label>
                    </th>
                    <td>
                        <fieldset>
                            <label for="enable_autocomplete">
                                <input name="enable_autocomplete" type="checkbox" id="enable_autocomplete" value="1" <?php checked($settings['enable_autocomplete']); ?>>
                                Ativar sugestões automáticas de código
                            </label>
                            <p class="description">
                                Mostra sugestões de propriedades e valores CSS enquanto você digita.
                            </p>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!-- Estatísticas -->
        <h2>Estatísticas do Editor</h2>
        
        <?php 
        $stats = $this->get_statistics();
        ?>
        
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">Total de Backups</th>
                    <td>
                        <strong><?php echo number_format($stats['total_backups']); ?></strong> arquivos
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">Espaço Usado</th>
                    <td>
                        <strong><?php echo size_format($stats['backup_size']); ?></strong>
                    </td>
                </tr>
                
                <?php if ($stats['oldest_backup']): ?>
                <tr>
                    <th scope="row">Backup Mais Antigo</th>
                    <td>
                        <?php echo date('d/m/Y H:i:s', $stats['oldest_backup']); ?>
                    </td>
                </tr>
                <?php endif; ?>
                
                <?php if ($stats['newest_backup']): ?>
                <tr>
                    <th scope="row">Backup Mais Recente</th>
                    <td>
                        <?php echo date('d/m/Y H:i:s', $stats['newest_backup']); ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Ações de Manutenção -->
        <h2>Manutenção</h2>
        
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">Limpeza de Backups</th>
                    <td>
                        <button type="button" class="button" onclick="cctCleanupBackups()">
                            <span class="dashicons dashicons-trash"></span>
                            Limpar Backups Antigos
                        </button>
                        <p class="description">
                            Remove backups mais antigos que o período de retenção configurado.
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">Reset de Configurações</th>
                    <td>
                        <button type="button" class="button" onclick="cctResetSettings()">
                            <span class="dashicons dashicons-undo"></span>
                            Restaurar Padrões
                        </button>
                        <p class="description">
                            Restaura todas as configurações para os valores padrão.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <?php submit_button('Salvar Configurações'); ?>
    </form>
</div>

<style>
.cct-css-editor-settings .wp-heading-inline {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.cct-css-editor-settings .wp-heading-inline .dashicons {
    font-size: 24px;
    color: #1d3771;
}

.cct-css-editor-settings .form-table th {
    width: 200px;
}

.cct-css-editor-settings .button .dashicons {
    font-size: 16px;
    margin-right: 5px;
    vertical-align: middle;
}
</style>

<script>
function cctCleanupBackups() {
    if (confirm('Tem certeza que deseja limpar os backups antigos? Esta ação não pode ser desfeita.')) {
        // Implementar AJAX para limpeza
        alert('Funcionalidade de limpeza será implementada em breve.');
    }
}

function cctResetSettings() {
    if (confirm('Tem certeza que deseja restaurar todas as configurações para os valores padrão?')) {
        // Reset dos campos do formulário
        document.getElementById('auto_backup').checked = true;
        document.getElementById('backup_retention_days').value = '30';
        document.getElementById('enable_validation').checked = true;
        document.getElementById('enable_minification').checked = false;
        document.getElementById('editor_theme').value = 'material';
        document.getElementById('font_size').value = '14';
        document.getElementById('tab_size').value = '2';
        document.getElementById('line_wrapping').checked = true;
        document.getElementById('show_line_numbers').checked = true;
        document.getElementById('enable_autocomplete').checked = true;
        
        alert('Configurações restauradas para os valores padrão. Clique em "Salvar Configurações" para aplicar.');
    }
}
</script>