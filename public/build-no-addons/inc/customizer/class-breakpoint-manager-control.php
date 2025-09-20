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
 * @package CCT_Theme
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
class CCT_Breakpoint_Manager_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_breakpoint_manager';
    
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
        
        <div class="cct-breakpoint-manager" data-customize-setting-link="<?php echo esc_attr($this->settings['default']->id); ?>">
            <!-- Barra de ferramentas -->
            <div class="cct-bp-toolbar">
                <div class="cct-bp-actions">
                    <button type="button" class="button cct-add-breakpoint">
                        <span class="dashicons dashicons-plus"></span>
                        <?php _e('Adicionar Breakpoint', 'cct'); ?>
                    </button>
                    
                    <button type="button" class="button cct-import-template">
                        <span class="dashicons dashicons-download"></span>
                        <?php _e('Importar Template', 'cct'); ?>
                    </button>
                    
                    <button type="button" class="button cct-export-breakpoints">
                        <span class="dashicons dashicons-upload"></span>
                        <?php _e('Exportar', 'cct'); ?>
                    </button>
                </div>
                
                <div class="cct-bp-info">
                    <span class="cct-bp-count">
                        <strong id="cct-bp-total">0</strong> <?php _e('breakpoints', 'cct'); ?>
                    </span>
                </div>
            </div>
            
            <!-- Lista de breakpoints -->
            <div class="cct-breakpoints-list" id="cct-breakpoints-list">
                <!-- Breakpoints serão carregados aqui via JavaScript -->
            </div>
            
            <!-- Template para novo breakpoint -->
            <script type="text/template" id="cct-breakpoint-template">
                <div class="cct-breakpoint-item" data-bp-key="{{key}}">
                    <div class="cct-bp-header">
                        <div class="cct-bp-handle">
                            <span class="dashicons dashicons-menu"></span>
                        </div>
                        
                        <div class="cct-bp-icon">
                            <span class="cct-bp-emoji">{{icon}}</span>
                        </div>
                        
                        <div class="cct-bp-title">
                            <h4>{{name}} <small>({{label}})</small></h4>
                            <p class="cct-bp-range">{{min_width}}px - {{max_width_display}}</p>
                        </div>
                        
                        <div class="cct-bp-status">
                            <label class="cct-toggle-switch">
                                <input type="checkbox" class="cct-bp-enabled" {{enabled_checked}}>
                                <span class="cct-toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div class="cct-bp-actions">
                            <button type="button" class="button-link cct-edit-bp" title="<?php _e('Editar', 'cct'); ?>">
                                <span class="dashicons dashicons-edit"></span>
                            </button>
                            <button type="button" class="button-link cct-preview-bp" title="<?php _e('Preview', 'cct'); ?>">
                                <span class="dashicons dashicons-visibility"></span>
                            </button>
                            <button type="button" class="button-link cct-delete-bp" title="<?php _e('Excluir', 'cct'); ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="cct-bp-details" style="display: none;">
                        <div class="cct-bp-form">
                            <div class="cct-form-row">
                                <div class="cct-form-col">
                                    <label><?php _e('Nome:', 'cct'); ?></label>
                                    <input type="text" class="cct-bp-name" value="{{name}}" placeholder="<?php _e('Ex: Large', 'cct'); ?>">
                                </div>
                                
                                <div class="cct-form-col">
                                    <label><?php _e('Rótulo:', 'cct'); ?></label>
                                    <input type="text" class="cct-bp-label" value="{{label}}" placeholder="<?php _e('Ex: Desktops', 'cct'); ?>">
                                </div>
                            </div>
                            
                            <div class="cct-form-row">
                                <div class="cct-form-col">
                                    <label><?php _e('Largura Mínima (px):', 'cct'); ?></label>
                                    <input type="number" class="cct-bp-min" value="{{min_width}}" min="0" step="1">
                                </div>
                                
                                <div class="cct-form-col">
                                    <label><?php _e('Largura Máxima (px):', 'cct'); ?></label>
                                    <input type="number" class="cct-bp-max" value="{{max_width}}" min="0" step="1" placeholder="<?php _e('Deixe vazio para ilimitado', 'cct'); ?>">
                                </div>
                            </div>
                            
                            <div class="cct-form-row">
                                <div class="cct-form-col">
                                    <label><?php _e('Ícone:', 'cct'); ?></label>
                                    <div class="cct-icon-picker">
                                        <input type="text" class="cct-bp-icon" value="{{icon}}" placeholder="📱">
                                        <div class="cct-icon-options">
                                            <span class="cct-icon-option" data-icon="📱">📱</span>
                                            <span class="cct-icon-option" data-icon="📱">📱</span>
                                            <span class="cct-icon-option" data-icon="💻">💻</span>
                                            <span class="cct-icon-option" data-icon="🖥️">🖥️</span>
                                            <span class="cct-icon-option" data-icon="⌚">⌚</span>
                                            <span class="cct-icon-option" data-icon="📺">📺</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="cct-form-col">
                                    <label><?php _e('Container (px ou %):', 'cct'); ?></label>
                                    <input type="text" class="cct-bp-container" value="{{container_width}}" placeholder="1200px">
                                </div>
                            </div>
                            
                            <div class="cct-form-row">
                                <div class="cct-form-col">
                                    <label><?php _e('Gutter (px):', 'cct'); ?></label>
                                    <input type="number" class="cct-bp-gutter" value="{{gutter}}" min="0" max="100" step="2">
                                </div>
                                
                                <div class="cct-form-col">
                                    <label><?php _e('Colunas:', 'cct'); ?></label>
                                    <input type="number" class="cct-bp-columns" value="{{columns}}" min="1" max="24" step="1">
                                </div>
                            </div>
                            
                            <div class="cct-form-row">
                                <div class="cct-form-col-full">
                                    <label><?php _e('Descrição:', 'cct'); ?></label>
                                    <textarea class="cct-bp-description" rows="2" placeholder="<?php _e('Descrição opcional do breakpoint...', 'cct'); ?>">{{description}}</textarea>
                                </div>
                            </div>
                            
                            <div class="cct-form-actions">
                                <button type="button" class="button button-primary cct-save-bp"><?php _e('Salvar', 'cct'); ?></button>
                                <button type="button" class="button cct-cancel-bp"><?php _e('Cancelar', 'cct'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            
            <!-- Modal de templates -->
            <div class="cct-template-modal" id="cct-template-modal" style="display: none;">
                <div class="cct-modal-backdrop"></div>
                <div class="cct-modal-content">
                    <div class="cct-modal-header">
                        <h3><?php _e('Importar Template de Breakpoints', 'cct'); ?></h3>
                        <button type="button" class="cct-modal-close">×</button>
                    </div>
                    
                    <div class="cct-modal-body">
                        <div class="cct-template-grid">
                            <?php foreach ($this->templates as $template_key => $template): ?>
                                <div class="cct-template-item" data-template="<?php echo esc_attr($template_key); ?>">
                                    <div class="cct-template-preview">
                                        <h4><?php echo esc_html($template['name']); ?></h4>
                                        <p><?php echo esc_html($template['description']); ?></p>
                                        
                                        <div class="cct-template-breakpoints">
                                            <?php if (isset($template['breakpoints'])): ?>
                                                <?php foreach ($template['breakpoints'] as $bp_key => $bp_data): ?>
                                                    <span class="cct-bp-tag">
                                                        <?php echo esc_html($bp_key); ?>
                                                        <small>(<?php echo esc_html($bp_data['min']); ?>px+)</small>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="cct-template-actions">
                                        <button type="button" class="button button-primary cct-apply-template" data-template="<?php echo esc_attr($template_key); ?>">
                                            <?php _e('Aplicar Template', 'cct'); ?>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="cct-modal-footer">
                        <button type="button" class="button cct-close-modal"><?php _e('Fechar', 'cct'); ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Preview de breakpoint -->
            <div class="cct-preview-modal" id="cct-preview-modal" style="display: none;">
                <div class="cct-modal-backdrop"></div>
                <div class="cct-modal-content cct-preview-content">
                    <div class="cct-modal-header">
                        <h3 id="cct-preview-title"><?php _e('Preview do Breakpoint', 'cct'); ?></h3>
                        <button type="button" class="cct-modal-close">×</button>
                    </div>
                    
                    <div class="cct-modal-body">
                        <div class="cct-preview-controls">
                            <div class="cct-width-slider">
                                <label><?php _e('Largura da Tela:', 'cct'); ?></label>
                                <input type="range" id="cct-preview-width" min="320" max="1920" step="10" value="1200">
                                <span id="cct-preview-width-value">1200px</span>
                            </div>
                        </div>
                        
                        <div class="cct-preview-frame">
                            <iframe id="cct-preview-iframe" src="about:blank"></iframe>
                        </div>
                        
                        <div class="cct-preview-info">
                            <div class="cct-info-item">
                                <strong><?php _e('Breakpoint Ativo:', 'cct'); ?></strong>
                                <span id="cct-active-bp">-</span>
                            </div>
                            <div class="cct-info-item">
                                <strong><?php _e('Container:', 'cct'); ?></strong>
                                <span id="cct-container-width">-</span>
                            </div>
                            <div class="cct-info-item">
                                <strong><?php _e('Colunas:', 'cct'); ?></strong>
                                <span id="cct-columns-count">-</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="cct-modal-footer">
                        <button type="button" class="button cct-close-modal"><?php _e('Fechar', 'cct'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .cct-breakpoint-manager {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .cct-bp-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-bp-actions {
            display: flex;
            gap: 8px;
        }
        
        .cct-bp-actions .button {
            font-size: 12px;
            padding: 6px 12px;
            height: auto;
        }
        
        .cct-bp-info {
            font-size: 12px;
            color: #666;
        }
        
        .cct-breakpoints-list {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .cct-breakpoint-item {
            border-bottom: 1px solid #eee;
            background: white;
            transition: all 0.3s ease;
        }
        
        .cct-breakpoint-item:hover {
            background: #f8f9fa;
        }
        
        .cct-breakpoint-item.editing {
            background: #fff3cd;
            border-color: #ffeaa7;
        }
        
        .cct-bp-header {
            display: flex;
            align-items: center;
            padding: 15px;
            gap: 12px;
        }
        
        .cct-bp-handle {
            cursor: move;
            color: #999;
            padding: 4px;
        }
        
        .cct-bp-handle:hover {
            color: #333;
        }
        
        .cct-bp-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            border-radius: 6px;
        }
        
        .cct-bp-emoji {
            font-size: 18px;
        }
        
        .cct-bp-title {
            flex: 1;
        }
        
        .cct-bp-title h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: 600;
        }
        
        .cct-bp-title small {
            color: #666;
            font-weight: normal;
        }
        
        .cct-bp-range {
            margin: 0;
            font-size: 11px;
            color: #888;
        }
        
        .cct-bp-status {
            margin-right: 8px;
        }
        
        .cct-toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }
        
        .cct-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .cct-toggle-slider {
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
        
        .cct-toggle-slider:before {
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
        
        input:checked + .cct-toggle-slider {
            background-color: #0073aa;
        }
        
        input:checked + .cct-toggle-slider:before {
            transform: translateX(20px);
        }
        
        .cct-bp-actions {
            display: flex;
            gap: 4px;
        }
        
        .cct-bp-actions .button-link {
            padding: 6px;
            color: #666;
            text-decoration: none;
            border-radius: 3px;
            transition: all 0.2s ease;
        }
        
        .cct-bp-actions .button-link:hover {
            color: #0073aa;
            background: rgba(0, 115, 170, 0.1);
        }
        
        .cct-bp-details {
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }
        
        .cct-form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .cct-form-col {
            flex: 1;
        }
        
        .cct-form-col-full {
            width: 100%;
        }
        
        .cct-form-col label {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-form-col input,
        .cct-form-col textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }
        
        .cct-icon-picker {
            position: relative;
        }
        
        .cct-icon-options {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }
        
        .cct-icon-option {
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
        
        .cct-icon-option:hover {
            background: #0073aa;
            transform: scale(1.1);
        }
        
        .cct-form-actions {
            display: flex;
            gap: 8px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .cct-form-actions .button {
            font-size: 12px;
            padding: 8px 16px;
        }
        
        /* Modal styles */
        .cct-template-modal,
        .cct-preview-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999999;
        }
        
        .cct-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
        }
        
        .cct-modal-content {
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
        
        .cct-preview-content {
            max-width: 1200px;
            width: 95%;
            max-height: 90vh;
        }
        
        .cct-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .cct-modal-header h3 {
            margin: 0;
            font-size: 16px;
        }
        
        .cct-modal-close {
            width: 30px;
            height: 30px;
            border: none;
            background: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }
        
        .cct-modal-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        
        .cct-template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .cct-template-item {
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .cct-template-item:hover {
            border-color: #0073aa;
            box-shadow: 0 4px 12px rgba(0, 115, 170, 0.1);
        }
        
        .cct-template-preview {
            padding: 15px;
        }
        
        .cct-template-preview h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
        }
        
        .cct-template-preview p {
            margin: 0 0 12px 0;
            font-size: 12px;
            color: #666;
        }
        
        .cct-template-breakpoints {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        
        .cct-bp-tag {
            padding: 2px 6px;
            background: #e0e0e0;
            border-radius: 10px;
            font-size: 10px;
            color: #666;
        }
        
        .cct-template-actions {
            padding: 15px;
            border-top: 1px solid #eee;
            background: #f8f9fa;
        }
        
        .cct-template-actions .button {
            width: 100%;
            font-size: 12px;
        }
        
        .cct-preview-controls {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .cct-width-slider {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cct-width-slider label {
            font-size: 12px;
            font-weight: 600;
            min-width: 100px;
        }
        
        .cct-width-slider input[type="range"] {
            flex: 1;
        }
        
        .cct-width-slider span {
            font-size: 12px;
            font-weight: 600;
            color: #0073aa;
            min-width: 60px;
            text-align: right;
        }
        
        .cct-preview-frame {
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 20px;
            height: 400px;
        }
        
        .cct-preview-frame iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .cct-preview-info {
            display: flex;
            gap: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 12px;
        }
        
        .cct-info-item strong {
            color: #333;
        }
        
        .cct-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px;
            border-top: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .cct-modal-footer .button {
            font-size: 12px;
            padding: 8px 16px;
        }
        
        /* Sortable styles */
        .cct-breakpoints-list.ui-sortable .cct-breakpoint-item {
            cursor: move;
        }
        
        .cct-breakpoints-list .ui-sortable-helper {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: rotate(2deg);
        }
        
        .cct-breakpoints-list .ui-sortable-placeholder {
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
            'cct-breakpoint-manager-control',
            get_template_directory_uri() . '/js/cct-breakpoint-manager-control.js',
            array('jquery', 'jquery-ui-sortable', 'customize-controls'),
            '1.0.0',
            true
        );
        
        wp_localize_script('cct-breakpoint-manager-control', 'cctBreakpointManager', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_breakpoints_nonce'),
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