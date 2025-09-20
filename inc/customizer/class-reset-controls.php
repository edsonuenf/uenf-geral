<?php
/**
 * Controles Customizados para Reset de Configurações
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Controle para reset de extensões individuais
 */
class UENF_Reset_Extensions_Control extends WP_Customize_Control {
    public $type = 'uenf_reset_extensions';
    
    public function render_content() {
        $extensions = $this->get_extensions();
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
        </label>
        
        <div class="uenf-reset-extensions-wrapper">
            <?php if (!empty($extensions)) : ?>
                <div class="uenf-extensions-list">
                    <?php foreach ($extensions as $id => $extension) : ?>
                        <div class="uenf-extension-item">
                            <label>
                                <input type="checkbox" 
                                       class="uenf-extension-checkbox" 
                                       value="<?php echo esc_attr($id); ?>"
                                       data-extension-id="<?php echo esc_attr($id); ?>">
                                <span class="uenf-extension-name">
                                    <?php echo esc_html($extension['name']); ?>
                                </span>
                                <span class="uenf-extension-description">
                                    <?php echo esc_html($extension['description']); ?>
                                </span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="uenf-reset-actions">
                    <button type="button" 
                            class="button button-secondary uenf-select-all-extensions">
                        Selecionar Todas
                    </button>
                    <button type="button" 
                            class="button button-secondary uenf-deselect-all-extensions">
                        Desmarcar Todas
                    </button>
                </div>
                
                <div class="uenf-reset-button-wrapper">
                    <button type="button" 
                            class="button button-primary uenf-reset-selected-extensions"
                            disabled>
                        🔄 Resetar Extensões Selecionadas
                    </button>
                </div>
            <?php else : ?>
                <p class="uenf-no-extensions">Nenhuma extensão disponível para reset.</p>
            <?php endif; ?>
        </div>
        
        <style>
        .uenf-reset-extensions-wrapper {
            margin-top: 10px;
        }
        
        .uenf-extensions-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background: #f9f9f9;
        }
        
        .uenf-extension-item {
            margin-bottom: 10px;
            padding: 8px;
            background: white;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }
        
        .uenf-extension-item label {
            display: block;
            cursor: pointer;
        }
        
        .uenf-extension-checkbox {
            margin-right: 8px;
        }
        
        .uenf-extension-name {
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }
        
        .uenf-extension-description {
            font-size: 12px;
            color: #666;
            display: block;
        }
        
        .uenf-reset-actions {
            margin-bottom: 15px;
        }
        
        .uenf-reset-actions button {
            margin-right: 10px;
        }
        
        .uenf-reset-button-wrapper {
            text-align: center;
        }
        
        .uenf-reset-selected-extensions:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .uenf-no-extensions {
            text-align: center;
            color: #666;
            font-style: italic;
        }
        </style>
        <?php
    }
    
    private function get_extensions() {
        if (function_exists('cct_extension_manager')) {
            $manager = cct_extension_manager();
            return $manager->get_all_extensions();
        }
        return array();
    }
}

/**
 * Controle para reset completo
 */
class UENF_Reset_All_Control extends WP_Customize_Control {
    public $type = 'uenf_reset_all';
    
    public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
        </label>
        
        <div class="uenf-reset-all-wrapper">
            <div class="uenf-warning-box">
                <p><strong>⚠️ ATENÇÃO:</strong></p>
                <p>Esta ação irá remover <strong>TODAS</strong> as configurações do tema e extensões, incluindo:</p>
                <ul>
                    <li>Configurações de cores e tipografia</li>
                    <li>Configurações de layout e design</li>
                    <li>Configurações de todas as extensões</li>
                    <li>Preferências de usuário</li>
                    <li>Customizações personalizadas</li>
                </ul>
                <p><strong>Esta ação é irreversível!</strong></p>
            </div>
            
            <div class="uenf-backup-info">
                <p><strong>💾 Backup Automático:</strong></p>
                <p>Um backup das configurações atuais será criado automaticamente antes do reset.</p>
            </div>
            
            <div class="uenf-confirmation-wrapper">
                <label>
                    <input type="checkbox" class="uenf-confirm-reset-all">
                    Eu entendo que esta ação é irreversível e desejo continuar
                </label>
            </div>
            
            <div class="uenf-reset-all-button-wrapper">
                <button type="button" 
                        class="button button-primary uenf-reset-all-settings"
                        disabled>
                    🗑️ RESETAR TODAS AS CONFIGURAÇÕES
                </button>
            </div>
        </div>
        
        <style>
        .uenf-reset-all-wrapper {
            margin-top: 10px;
        }
        
        .uenf-warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .uenf-warning-box p {
            margin: 0 0 10px 0;
        }
        
        .uenf-warning-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .uenf-warning-box li {
            margin-bottom: 5px;
        }
        
        .uenf-backup-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .uenf-backup-info p {
            margin: 0 0 10px 0;
        }
        
        .uenf-confirmation-wrapper {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        
        .uenf-confirmation-wrapper label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .uenf-confirmation-wrapper input[type="checkbox"] {
            margin-right: 8px;
        }
        
        .uenf-reset-all-button-wrapper {
            text-align: center;
        }
        
        .uenf-reset-all-settings {
            background: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
            font-weight: bold;
            padding: 10px 20px;
        }
        
        .uenf-reset-all-settings:hover:not(:disabled) {
            background: #c82333 !important;
            border-color: #bd2130 !important;
        }
        
        .uenf-reset-all-settings:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        </style>
        <?php
    }
}

/**
 * Controle para backup e restore
 */
class UENF_Backup_Control extends WP_Customize_Control {
    public $type = 'uenf_backup';
    
    public function render_content() {
        $backups = $this->get_available_backups();
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
        </label>
        
        <div class="uenf-backup-wrapper">
            <div class="uenf-backup-actions">
                <button type="button" class="button button-secondary uenf-create-backup">
                    💾 Criar Backup Manual
                </button>
            </div>
            
            <?php if (!empty($backups)) : ?>
                <div class="uenf-backups-list">
                    <h4>Backups Disponíveis:</h4>
                    <?php foreach ($backups as $backup) : ?>
                        <div class="uenf-backup-item">
                            <span class="uenf-backup-date"><?php echo esc_html($backup['date']); ?></span>
                            <button type="button" 
                                    class="button button-small uenf-restore-backup"
                                    data-backup-key="<?php echo esc_attr($backup['key']); ?>">
                                Restaurar
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="uenf-no-backups">Nenhum backup disponível.</p>
            <?php endif; ?>
        </div>
        
        <style>
        .uenf-backup-wrapper {
            margin-top: 10px;
        }
        
        .uenf-backup-actions {
            margin-bottom: 15px;
        }
        
        .uenf-backups-list {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            background: #f9f9f9;
        }
        
        .uenf-backups-list h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        
        .uenf-backup-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            background: white;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        
        .uenf-backup-date {
            font-size: 12px;
            color: #666;
        }
        
        .uenf-no-backups {
            text-align: center;
            color: #666;
            font-style: italic;
        }
        </style>
        <?php
    }
    
    private function get_available_backups() {
        global $wpdb;
        
        $backups = $wpdb->get_results(
            "SELECT option_name, option_value FROM {$wpdb->options} 
             WHERE option_name LIKE 'uenf_theme_backup_%' 
             ORDER BY option_name DESC"
        );
        
        $formatted_backups = array();
        foreach ($backups as $backup) {
            $data = maybe_unserialize($backup->option_value);
            if (isset($data['timestamp'])) {
                $formatted_backups[] = array(
                    'key' => $backup->option_name,
                    'date' => date('d/m/Y H:i:s', strtotime($data['timestamp'])),
                    'timestamp' => $data['timestamp']
                );
            }
        }
        
        return $formatted_backups;
    }
}