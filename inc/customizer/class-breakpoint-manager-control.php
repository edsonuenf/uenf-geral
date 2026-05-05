<?php
/**
 * Controle Personalizado para Gerenciador de Breakpoints
 * 
 * Controle avançado para gerenciamento de breakpoints incluindo:
 * - Interface visual para adicionar/editar breakpoints
 * - Preview em tempo real
 * - Drag & drop para reordenação
 * - Templates predefinidos
 * - Validação de dados
 * 
 * @package UENF_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

// Verificação de segurança
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Controle Gerenciador de Breakpoints
 */
class UENF_Breakpoint_Manager_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_breakpoint_manager';
    
    /**
     * Breakpoints disponíveis
     * 
     * @var array
     */
    public $breakpoints = array();
    
    /**
     * Templates de breakpoints
     * 
     * @var array
     */
    public $templates = array();
    
    /**
     * Renderiza o controle
     */
    public function render_content() {
        ?>
        <label>
            <?php if (!empty($this->label)): ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            
            <?php if (!empty($this->description)): ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>
        
        <div class="uenf-breakpoint-manager" data-customize-setting-link="<?php echo esc_attr($this->settings['default']->id); ?>">
            <!-- Barra de ferramentas -->
            <div class="uenf-bp-toolbar">
                <div class="uenf-bp-actions">
                    <button type="button" class="button uenf-add-breakpoint">
                        <span class="dashicons dashicons-plus"></span>
                        <?php _e('Adicionar Breakpoint', 'cct'); ?>
                    </button>
                    
                    <button type="button" class="button uenf-import-template">
                        <span class="dashicons dashicons-download"></span>
                        <?php _e('Importar Template', 'cct'); ?>
                    </button>
                    
                    <button type="button" class="button uenf-export-breakpoints">
                        <span class="dashicons dashicons-upload"></span>
                        <?php _e('Exportar', 'cct'); ?>
                    </button>
                </div>
                
                <div class="uenf-bp-info">
                    <span class="uenf-bp-count">
                        <strong id="uenf-bp-total">0</strong> <?php _e('breakpoints', 'cct'); ?>
                    </span>
                </div>
            </div>
            
            <!-- Lista de breakpoints -->
            <div class="uenf-breakpoints-list" id="uenf-breakpoints-list">
                <!-- Breakpoints serão carregados aqui via JavaScript -->
            </div>
            
            <!-- Template para novo breakpoint -->
            <script type="text/template" id="uenf-breakpoint-template">
                <div class="uenf-breakpoint-item" data-bp-key="{{key}}">
                    <div class="uenf-bp-header">
                        <div class="uenf-bp-handle">
                            <span class="dashicons dashicons-menu"></span>
                        </div>
                        
                        <div class="uenf-bp-icon">
                            <span class="uenf-bp-emoji">{{icon}}</span>
                        </div>
                        
                        <div class="uenf-bp-title">
                            <h4>{{name}} <small>({{label}})</small></h4>
                            <p class="uenf-bp-range">{{min_width}}px - {{max_width_display}}</p>
                        </div>
                        
                        <div class="uenf-bp-status">
                            <label class="uenf-toggle-switch">
                                <input type="checkbox" class="uenf-bp-enabled" {{enabled_checked}}>
                                <span class="uenf-toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div class="uenf-bp-actions">
                            <button type="button" class="button-link uenf-edit-bp" title="<?php _e('Editar', 'cct'); ?>">
                                <span class="dashicons dashicons-edit"></span>
                            </button>
                            <button type="button" class="button-link uenf-preview-bp" title="<?php _e('Preview', 'cct'); ?>">
                                <span class="dashicons dashicons-visibility"></span>
                            </button>
                            <button type="button" class="button-link uenf-delete-bp" title="<?php _e('Excluir', 'cct'); ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="uenf-bp-details" style="display: none;">
                        <div class="uenf-bp-form">
                            <div class="uenf-form-row">
                                <div class="uenf-form-col">
                                    <label><?php _e('Nome:', 'cct'); ?></label>
                                    <input type="text" class="uenf-bp-name" value="{{name}}" placeholder="<?php _e('Ex: Large', 'cct'); ?>">
                                </div>
                                
                                <div class="uenf-form-col">
                                    <label><?php _e('Rótulo:', 'cct'); ?></label>
                                    <input type="text" class="uenf-bp-label" value="{{label}}" placeholder="<?php _e('Ex: Desktops', 'cct'); ?>">
                                </div>
                            </div>
                            
                            <div class="uenf-form-row">
                                <div class="uenf-form-col">
                                    <label><?php _e('Largura Mínima (px):', 'cct'); ?></label>
                                    <input type="number" class="uenf-bp-min" value="{{min_width}}" min="0" step="1">
                                </div>
                                
                                <div class="uenf-form-col">
                                    <label><?php _e('Largura Máxima (px):', 'cct'); ?></label>
                                    <input type="number" class="uenf-bp-max" value="{{max_width}}" min="0" step="1" placeholder="<?php _e('Deixe vazio para ilimitado', 'cct'); ?>">
                                </div>
                            </div>
                            
                            <div class="uenf-form-row">
                                <div class="uenf-form-col">
                                    <label><?php _e('Ícone:', 'cct'); ?></label>
                                    <div class="uenf-icon-picker">
                                        <input type="text" class="uenf-bp-icon" value="{{icon}}" placeholder="📱">
                                        <div class="uenf-icon-options">
                                            <span class="uenf-icon-option" data-icon="📱">📱</span>
                                            <span class="uenf-icon-option" data-icon="📱">📱</span>
                                            <span class="uenf-icon-option" data-icon="💻">💻</span>
                                            <span class="uenf-icon-option" data-icon="🖥️">🖥️</span>
                                            <span class="uenf-icon-option" data-icon="⌚">⌚</span>
                                            <span class="uenf-icon-option" data-icon="📺">📺</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="uenf-form-col">
                                    <label><?php _e('Container (px ou %):', 'cct'); ?></label>
                                    <input type="text" class="uenf-bp-container" value="{{container_width}}" placeholder="1200px">
                                </div>
                            </div>
                            
                            <div class="uenf-form-row">
                                <div class="uenf-form-col">
                                    <label><?php _e('Gutter (px):', 'cct'); ?></label>
                                    <input type="number" class="uenf-bp-gutter" value="{{gutter}}" min="0" max="100" step="2">
                                </div>
                                
                                <div class="uenf-form-col">
                                    <label><?php _e('Colunas:', 'cct'); ?></label>
                                    <input type="number" class="uenf-bp-columns" value="{{columns}}" min="1" max="24" step="1">
                                </div>
                            </div>
                            
                            <div class="uenf-form-row">
                                <div class="uenf-form-col-full">
                                    <label><?php _e('Descrição:', 'cct'); ?></label>
                                    <textarea class="uenf-bp-description" rows="2" placeholder="<?php _e('Descrição opcional do breakpoint...', 'cct'); ?>">{{description}}</textarea>
                                </div>
                            </div>
                            
                            <div class="uenf-form-actions">
                                <button type="button" class="button button-primary uenf-save-bp"><?php _e('Salvar', 'cct'); ?></button>
                                <button type="button" class="button uenf-cancel-bp"><?php _e('Cancelar', 'cct'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            
            <!-- Modal de templates -->
            <div class="uenf-template-modal" id="uenf-template-modal" style="display: none;">
                <div class="uenf-modal-backdrop"></div>
                <div class="uenf-modal-content">
                    <div class="uenf-modal-header">
                        <h3><?php _e('Importar Template de Breakpoints', 'cct'); ?></h3>
                        <button type="button" class="uenf-modal-close">×</button>
                    </div>
                    
                    <div class="uenf-modal-body">
                        <div class="uenf-template-grid">
                            <?php foreach ($this->templates as $template_key => $template): ?>
                                <div class="uenf-template-item" data-template="<?php echo esc_attr($template_key); ?>">
                                    <div class="uenf-template-preview">
                                        <h4><?php echo esc_html($template['name']); ?></h4>
                                        <p><?php echo esc_html($template['description']); ?></p>
                                        
                                        <div class="uenf-template-breakpoints">
                                            <?php if (isset($template['breakpoints'])): ?>
                                                <?php foreach ($template['breakpoints'] as $bp_key => $bp_data): ?>
                                                    <span class="uenf-bp-tag">
                                                        <?php echo esc_html($bp_key); ?>
                                                        <small>(<?php echo esc_html($bp_data['min']); ?>px+)</small>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="uenf-template-actions">
                                        <button type="button" class="button button-primary uenf-apply-template" data-template="<?php echo esc_attr($template_key); ?>">
                                            <?php _e('Aplicar Template', 'cct'); ?>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="uenf-modal-footer">
                        <button type="button" class="button uenf-close-modal"><?php _e('Fechar', 'cct'); ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Preview de breakpoint -->
            <div class="uenf-preview-modal" id="uenf-preview-modal" style="display: none;">
                <div class="uenf-modal-backdrop"></div>
                <div class="uenf-modal-content uenf-preview-content">
                    <div class="uenf-modal-header">
                        <h3 id="uenf-preview-title"><?php _e('Preview do Breakpoint', 'cct'); ?></h3>
                        <button type="button" class="uenf-modal-close">×</button>
                    </div>
                    
                    <div class="uenf-modal-body">
                        <div class="uenf-preview-controls">
                            <div class="uenf-width-slider">
                                <label><?php _e('Largura da Tela:', 'cct'); ?></label>
                                <input type="range" id="uenf-preview-width" min="320" max="1920" step="10" value="1200">
                                <span id="uenf-preview-width-value">1200px</span>
                            </div>
                        </div>
                        
                        <div class="uenf-preview-frame">
                            <iframe id="uenf-preview-iframe" src="about:blank"></iframe>
                        </div>
                        
                        <div class="uenf-preview-info">
                            <div class="uenf-info-item">
                                <strong><?php _e('Breakpoint Ativo:', 'cct'); ?></strong>
                                <span id="uenf-active-bp">-</span>
                            </div>
                            <div class="uenf-info-item">
                                <strong><?php _e('Container:', 'cct'); ?></strong>
                                <span id="uenf-container-width">-</span>
                            </div>
                            <div class="uenf-info-item">
                                <strong><?php _e('Colunas:', 'cct'); ?></strong>
                                <span id="uenf-columns-count">-</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="uenf-modal-footer">
                        <button type="button" class="button uenf-close-modal"><?php _e('Fechar', 'cct'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-breakpoint-manager {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .uenf-bp-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-bp-actions {
            display: flex;
            gap: 8px;
        }
        
        .uenf-bp-actions .button {
            font-size: 12px;
            padding: 6px 12px;
            height: auto;
        }
        
        .uenf-bp-info {
            font-size: 12px;
            color: #666;
        }
        
        .uenf-breakpoints-list {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .uenf-breakpoint-item {
            border-bottom: 1px solid #eee;
            background: white;
            transition: all 0.3s ease;
        }
        
        .uenf-breakpoint-item:hover {
            background: #f8f9fa;
        }
        
        .uenf-breakpoint-item.editing {
            background: #fff3cd;
            border-color: #ffeaa7;
        }
        
        .uenf-bp-header {
            display: flex;
            align-items: center;
            padding: 15px;
            gap: 12px;
        }
        
        .uenf-bp-handle {
            cursor: move;
            color: #999;
            padding: 4px;
        }
        
        .uenf-bp-handle:hover {
            color: #333;
        }
        
        .uenf-bp-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            border-radius: 6px;
        }
        
        .uenf-bp-emoji {
            font-size: 18px;
        }
        
        .uenf-bp-title {
            flex: 1;
        }
        
        .uenf-bp-title h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: 600;
        }
        
        .uenf-bp-title small {
            color: #666;
            font-weight: normal;
        }
        
        .uenf-bp-range {
            margin: 0;
            font-size: 11px;
            color: #888;
        }
        
        .uenf-bp-status {
            margin-right: 8px;
        }
        
        .uenf-toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }
        
        .uenf-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .uenf-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 10px;
        }
        
        .uenf-toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        
        input:checked + .uenf-toggle-slider {
            background-color: #0073aa;
        }
        
        input:checked + .uenf-toggle-slider:before {
            transform: translateX(20px);
        }
        
        .uenf-bp-actions {
            display: flex;
            gap: 4px;
        }
        
        .uenf-bp-actions .button-link {
            padding: 6px;
            color: #666;
            text-decoration: none;
            border-radius: 3px;
            transition: all 0.2s ease;
        }
        
        .uenf-bp-actions .button-link:hover {
            color: #0073aa;
            background: rgba(0, 115, 170, 0.1);
        }
        
        .uenf-bp-details {
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }
        
        .uenf-form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .uenf-form-col {
            flex: 1;
        }
        
        .uenf-form-col-full {
            width: 100%;
        }
        
        .uenf-form-col label {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-form-col input,
        .uenf-form-col textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }
        
        .uenf-icon-picker {
            position: relative;
        }
        
        .uenf-icon-options {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }
        
        .uenf-icon-option {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s ease;
        }
        
        .uenf-icon-option:hover {
            background: #0073aa;
            transform: scale(1.1);
        }
        
        .uenf-form-actions {
            display: flex;
            gap: 8px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .uenf-form-actions .button {
            font-size: 12px;
            padding: 8px 16px;
        }
        
        /* Modal styles */
        .uenf-template-modal,
        .uenf-preview-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999999;
        }
        
        .uenf-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
        }
        
        .uenf-modal-content {
            position: relative;
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            margin: 5vh auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .uenf-preview-content {
            max-width: 1200px;
            width: 95%;
            max-height: 90vh;
        }
        
        .uenf-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .uenf-modal-header h3 {
            margin: 0;
            font-size: 16px;
        }
        
        .uenf-modal-close {
            width: 30px;
            height: 30px;
            border: none;
            background: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }
        
        .uenf-modal-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        
        .uenf-template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .uenf-template-item {
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .uenf-template-item:hover {
            border-color: #0073aa;
            box-shadow: 0 4px 12px rgba(0, 115, 170, 0.1);
        }
        
        .uenf-template-preview {
            padding: 15px;
        }
        
        .uenf-template-preview h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
        }
        
        .uenf-template-preview p {
            margin: 0 0 12px 0;
            font-size: 12px;
            color: #666;
        }
        
        .uenf-template-breakpoints {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        
        .uenf-bp-tag {
            padding: 2px 6px;
            background: #e0e0e0;
            border-radius: 10px;
            font-size: 10px;
            color: #666;
        }
        
        .uenf-template-actions {
            padding: 15px;
            border-top: 1px solid #eee;
            background: #f8f9fa;
        }
        
        .uenf-template-actions .button {
            width: 100%;
            font-size: 12px;
        }
        
        .uenf-preview-controls {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .uenf-width-slider {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .uenf-width-slider label {
            font-size: 12px;
            font-weight: 600;
            min-width: 100px;
        }
        
        .uenf-width-slider input[type="range"] {
            flex: 1;
        }
        
        .uenf-width-slider span {
            font-size: 12px;
            font-weight: 600;
            color: #0073aa;
            min-width: 60px;
            text-align: right;
        }
        
        .uenf-preview-frame {
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 20px;
            height: 400px;
        }
        
        .uenf-preview-frame iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .uenf-preview-info {
            display: flex;
            gap: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 12px;
        }
        
        .uenf-info-item strong {
            color: #333;
        }
        
        .uenf-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px;
            border-top: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .uenf-modal-footer .button {
            font-size: 12px;
            padding: 8px 16px;
        }
        
        /* Sortable styles */
        .uenf-breakpoints-list.ui-sortable .uenf-breakpoint-item {
            cursor: move;
        }
        
        .uenf-breakpoints-list .ui-sortable-helper {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: rotate(2deg);
        }
        
        .uenf-breakpoints-list .ui-sortable-placeholder {
            height: 60px;
            background: #f0f8ff;
            border: 2px dashed #0073aa;
            margin: 5px 0;
        }
        </style>
        <?php
    }
    
    /**
     * Enfileira scripts do controle
     */
    public function enqueue() {
        wp_enqueue_script('jquery-ui-sortable');
        
        wp_enqueue_script(
            'uenf-breakpoint-manager-control',
            get_template_directory_uri() . '/js/uenf-breakpoint-manager-control.js',
            array('jquery', 'jquery-ui-sortable', 'customize-controls'),
            '1.0.0',
            true
        );
        
        wp_localize_script('uenf-breakpoint-manager-control', 'cctBreakpointManager', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('uenf_breakpoints_nonce'),
            'strings' => array(
                'confirmDelete' => __('Tem certeza que deseja excluir este breakpoint?', 'cct'),
                'confirmTemplate' => __('Isso substituirá todos os breakpoints atuais. Continuar?', 'cct'),
                'saved' => __('Breakpoint salvo!', 'cct'),
                'deleted' => __('Breakpoint excluído!', 'cct'),
                'error' => __('Erro ao processar solicitação.', 'cct'),
                'invalidData' => __('Dados inválidos. Verifique os campos obrigatórios.', 'cct'),
                'duplicateRange' => __('Este intervalo de largura já está sendo usado por outro breakpoint.', 'cct')
            ),
            'breakpoints' => $this->breakpoints,
            'templates' => $this->templates
        ));
    }
}