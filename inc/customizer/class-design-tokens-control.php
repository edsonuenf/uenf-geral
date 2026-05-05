<?php
/**
 * Controle Personalizado para Gerenciador de Design Tokens
 * 
 * Controle avançado para gerenciamento de design tokens incluindo:
 * - Interface visual para editar tokens
 * - Preview em tempo real
 * - Export/Import em múltiplos formatos
 * - Validação de tokens
 * - Documentação automática
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
 * Controle Gerenciador de Design Tokens
 */
class UENF_Design_Tokens_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_design_tokens';
    
    /**
     * Tokens primitivos
     * 
     * @var array
     */
    public $primitive_tokens = array();
    
    /**
     * Tokens semânticos
     * 
     * @var array
     */
    public $semantic_tokens = array();
    
    /**
     * Tokens de componente
     * 
     * @var array
     */
    public $component_tokens = array();
    
    /**
     * Formatos de export
     * 
     * @var array
     */
    public $export_formats = array();
    
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
        
        <div class="uenf-design-tokens-manager" data-customize-setting-link="<?php echo esc_attr($this->settings['default']->id); ?>">
            <!-- Barra de ferramentas -->
            <div class="uenf-tokens-toolbar">
                <div class="uenf-tokens-tabs">
                    <button type="button" class="uenf-tab-btn active" data-tab="primitive">
                        <span class="dashicons dashicons-admin-settings"></span>
                        <?php _e('Primitivos', 'cct'); ?>
                    </button>
                    <button type="button" class="uenf-tab-btn" data-tab="semantic">
                        <span class="dashicons dashicons-tag"></span>
                        <?php _e('Semânticos', 'cct'); ?>
                    </button>
                    <button type="button" class="uenf-tab-btn" data-tab="component">
                        <span class="dashicons dashicons-admin-appearance"></span>
                        <?php _e('Componentes', 'cct'); ?>
                    </button>
                    <button type="button" class="uenf-tab-btn" data-tab="documentation">
                        <span class="dashicons dashicons-media-document"></span>
                        <?php _e('Documentação', 'cct'); ?>
                    </button>
                </div>
                
                <div class="uenf-tokens-actions">
                    <button type="button" class="button uenf-add-token">
                        <span class="dashicons dashicons-plus"></span>
                        <?php _e('Adicionar Token', 'cct'); ?>
                    </button>
                    
                    <div class="uenf-export-dropdown">
                        <button type="button" class="button uenf-export-btn">
                            <span class="dashicons dashicons-download"></span>
                            <?php _e('Exportar', 'cct'); ?>
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </button>
                        <div class="uenf-export-menu">
                            <?php foreach ($this->export_formats as $format_key => $format_data): ?>
                                <button type="button" class="uenf-export-format" data-format="<?php echo esc_attr($format_key); ?>">
                                    <?php echo esc_html($format_data['name']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <button type="button" class="button uenf-import-tokens">
                        <span class="dashicons dashicons-upload"></span>
                        <?php _e('Importar', 'cct'); ?>
                    </button>
                    
                    <button type="button" class="button uenf-sync-tokens">
                        <span class="dashicons dashicons-update"></span>
                        <?php _e('Sincronizar', 'cct'); ?>
                    </button>
                </div>
            </div>
            
            <!-- Conteúdo das abas -->
            <div class="uenf-tokens-content">
                <!-- Aba Tokens Primitivos -->
                <div class="uenf-tab-content active" data-tab="primitive">
                    <div class="uenf-tokens-grid" id="uenf-primitive-tokens">
                        <!-- Tokens primitivos serão carregados aqui via JavaScript -->
                    </div>
                </div>
                
                <!-- Aba Tokens Semânticos -->
                <div class="uenf-tab-content" data-tab="semantic">
                    <div class="uenf-tokens-grid" id="uenf-semantic-tokens">
                        <!-- Tokens semânticos serão carregados aqui via JavaScript -->
                    </div>
                </div>
                
                <!-- Aba Tokens de Componente -->
                <div class="uenf-tab-content" data-tab="component">
                    <div class="uenf-tokens-grid" id="uenf-component-tokens">
                        <!-- Tokens de componente serão carregados aqui via JavaScript -->
                    </div>
                </div>
                
                <!-- Aba Documentação -->
                <div class="uenf-tab-content" data-tab="documentation">
                    <div class="uenf-documentation-content">
                        <div class="uenf-doc-header">
                            <h3><?php _e('Documentação dos Design Tokens', 'cct'); ?></h3>
                            <div class="uenf-doc-actions">
                                <button type="button" class="button uenf-generate-docs">
                                    <span class="dashicons dashicons-admin-page"></span>
                                    <?php _e('Gerar Documentação', 'cct'); ?>
                                </button>
                                <button type="button" class="button uenf-copy-css">
                                    <span class="dashicons dashicons-admin-page"></span>
                                    <?php _e('Copiar CSS', 'cct'); ?>
                                </button>
                            </div>
                        </div>
                        
                        <div class="uenf-doc-stats">
                            <div class="uenf-stat-item">
                                <strong id="uenf-total-tokens">0</strong>
                                <span><?php _e('Total de Tokens', 'cct'); ?></span>
                            </div>
                            <div class="uenf-stat-item">
                                <strong id="uenf-primitive-count">0</strong>
                                <span><?php _e('Primitivos', 'cct'); ?></span>
                            </div>
                            <div class="uenf-stat-item">
                                <strong id="uenf-semantic-count">0</strong>
                                <span><?php _e('Semânticos', 'cct'); ?></span>
                            </div>
                            <div class="uenf-stat-item">
                                <strong id="uenf-component-count">0</strong>
                                <span><?php _e('Componentes', 'cct'); ?></span>
                            </div>
                        </div>
                        
                        <div class="uenf-doc-preview" id="uenf-doc-preview">
                            <!-- Documentação será gerada aqui -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Template para token -->
            <script type="text/template" id="uenf-token-template">
                <div class="uenf-token-item" data-token-id="{{id}}" data-category="{{category}}" data-subcategory="{{subcategory}}">
                    <div class="uenf-token-header">
                        <div class="uenf-token-preview" style="{{preview_style}}">
                            <span class="uenf-token-preview-value">{{preview_value}}</span>
                        </div>
                        
                        <div class="uenf-token-info">
                            <h4 class="uenf-token-name">{{name}}</h4>
                            <p class="uenf-token-path">{{category}}.{{subcategory}}.{{name}}</p>
                            <p class="uenf-token-value">{{value}}</p>
                        </div>
                        
                        <div class="uenf-token-actions">
                            <button type="button" class="button-link uenf-edit-token" title="<?php _e('Editar', 'cct'); ?>">
                                <span class="dashicons dashicons-edit"></span>
                            </button>
                            <button type="button" class="button-link uenf-copy-token" title="<?php _e('Copiar CSS', 'cct'); ?>">
                                <span class="dashicons dashicons-admin-page"></span>
                            </button>
                            <button type="button" class="button-link uenf-delete-token" title="<?php _e('Excluir', 'cct'); ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="uenf-token-details" style="display: none;">
                        <div class="uenf-token-form">
                            <div class="uenf-form-row">
                                <div class="uenf-form-col">
                                    <label><?php _e('Nome:', 'cct'); ?></label>
                                    <input type="text" class="uenf-token-name-input" value="{{name}}" placeholder="<?php _e('Ex: primary', 'cct'); ?>">
                                </div>
                                
                                <div class="uenf-form-col">
                                    <label><?php _e('Categoria:', 'cct'); ?></label>
                                    <select class="uenf-token-category-select">
                                        <option value="colors" {{selected_colors}}><?php _e('Cores', 'cct'); ?></option>
                                        <option value="typography" {{selected_typography}}><?php _e('Tipografia', 'cct'); ?></option>
                                        <option value="spacing" {{selected_spacing}}><?php _e('Espaçamento', 'cct'); ?></option>
                                        <option value="border-radius" {{selected_border}}><?php _e('Bordas', 'cct'); ?></option>
                                        <option value="shadows" {{selected_shadows}}><?php _e('Sombras', 'cct'); ?></option>
                                        <option value="transitions" {{selected_transitions}}><?php _e('Transições', 'cct'); ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="uenf-form-row">
                                <div class="uenf-form-col">
                                    <label><?php _e('Subcategoria:', 'cct'); ?></label>
                                    <input type="text" class="uenf-token-subcategory-input" value="{{subcategory}}" placeholder="<?php _e('Ex: base', 'cct'); ?>">
                                </div>
                                
                                <div class="uenf-form-col">
                                    <label><?php _e('Valor:', 'cct'); ?></label>
                                    <div class="uenf-value-input-container">
                                        <input type="text" class="uenf-token-value-input" value="{{value}}" placeholder="<?php _e('Ex: #3b82f6', 'cct'); ?>">
                                        <input type="color" class="uenf-token-color-picker" value="{{color_value}}" style="{{color_picker_style}}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="uenf-form-row">
                                <div class="uenf-form-col-full">
                                    <label><?php _e('Descrição:', 'cct'); ?></label>
                                    <textarea class="uenf-token-description-input" rows="2" placeholder="<?php _e('Descrição opcional do token...', 'cct'); ?>">{{description}}</textarea>
                                </div>
                            </div>
                            
                            <div class="uenf-form-row">
                                <div class="uenf-form-col-full">
                                    <label><?php _e('Variável CSS:', 'cct'); ?></label>
                                    <code class="uenf-css-variable">var({{css_variable}})</code>
                                </div>
                            </div>
                            
                            <div class="uenf-form-actions">
                                <button type="button" class="button button-primary uenf-save-token"><?php _e('Salvar', 'cct'); ?></button>
                                <button type="button" class="button uenf-cancel-token"><?php _e('Cancelar', 'cct'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            
            <!-- Template para categoria de tokens -->
            <script type="text/template" id="uenf-category-template">
                <div class="uenf-token-category" data-category="{{category}}">
                    <div class="uenf-category-header">
                        <h3 class="uenf-category-title">
                            <span class="uenf-category-icon">{{icon}}</span>
                            {{title}}
                            <span class="uenf-category-count">({{count}})</span>
                        </h3>
                        <button type="button" class="button-link uenf-toggle-category">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </button>
                    </div>
                    
                    <div class="uenf-category-content">
                        <div class="uenf-subcategory-grid">
                            {{subcategories}}
                        </div>
                    </div>
                </div>
            </script>
            
            <!-- Template para subcategoria -->
            <script type="text/template" id="uenf-subcategory-template">
                <div class="uenf-token-subcategory" data-subcategory="{{subcategory}}">
                    <h4 class="uenf-subcategory-title">{{title}} <span class="uenf-subcategory-count">({{count}})</span></h4>
                    <div class="uenf-tokens-list">
                        {{tokens}}
                    </div>
                </div>
            </script>
            
            <!-- Modal de importação -->
            <div class="uenf-import-modal" id="uenf-import-modal" style="display: none;">
                <div class="uenf-modal-backdrop"></div>
                <div class="uenf-modal-content">
                    <div class="uenf-modal-header">
                        <h3><?php _e('Importar Design Tokens', 'cct'); ?></h3>
                        <button type="button" class="uenf-modal-close">×</button>
                    </div>
                    
                    <div class="uenf-modal-body">
                        <div class="uenf-import-options">
                            <div class="uenf-import-method">
                                <h4><?php _e('Método de Importação', 'cct'); ?></h4>
                                <label>
                                    <input type="radio" name="import_method" value="file" checked>
                                    <?php _e('Arquivo JSON', 'cct'); ?>
                                </label>
                                <label>
                                    <input type="radio" name="import_method" value="text">
                                    <?php _e('Texto/JSON', 'cct'); ?>
                                </label>
                            </div>
                            
                            <div class="uenf-import-file" id="uenf-import-file">
                                <input type="file" id="uenf-file-input" accept=".json" />
                                <label for="uenf-file-input" class="uenf-file-label">
                                    <span class="dashicons dashicons-upload"></span>
                                    <?php _e('Escolher arquivo JSON', 'cct'); ?>
                                </label>
                            </div>
                            
                            <div class="uenf-import-text" id="uenf-import-text" style="display: none;">
                                <textarea id="uenf-import-textarea" rows="10" placeholder="<?php _e('Cole aqui o JSON dos tokens...', 'cct'); ?>"></textarea>
                            </div>
                            
                            <div class="uenf-import-options-advanced">
                                <h4><?php _e('Opções Avançadas', 'cct'); ?></h4>
                                <label>
                                    <input type="checkbox" id="uenf-merge-tokens" checked>
                                    <?php _e('Mesclar com tokens existentes', 'cct'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" id="uenf-backup-before-import" checked>
                                    <?php _e('Criar backup antes da importação', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="uenf-modal-footer">
                        <button type="button" class="button button-primary uenf-import-confirm"><?php _e('Importar', 'cct'); ?></button>
                        <button type="button" class="button uenf-close-modal"><?php _e('Cancelar', 'cct'); ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Modal de preview de export -->
            <div class="uenf-export-modal" id="uenf-export-modal" style="display: none;">
                <div class="uenf-modal-backdrop"></div>
                <div class="uenf-modal-content uenf-export-content">
                    <div class="uenf-modal-header">
                        <h3 id="uenf-export-title"><?php _e('Preview do Export', 'cct'); ?></h3>
                        <button type="button" class="uenf-modal-close">×</button>
                    </div>
                    
                    <div class="uenf-modal-body">
                        <div class="uenf-export-preview">
                            <div class="uenf-export-info">
                                <p><strong><?php _e('Formato:', 'cct'); ?></strong> <span id="uenf-export-format"></span></p>
                                <p><strong><?php _e('Arquivo:', 'cct'); ?></strong> <span id="uenf-export-filename"></span></p>
                                <p><strong><?php _e('Tamanho:', 'cct'); ?></strong> <span id="uenf-export-size"></span></p>
                            </div>
                            
                            <div class="uenf-export-code">
                                <pre><code id="uenf-export-content"></code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="uenf-modal-footer">
                        <button type="button" class="button button-primary uenf-download-export">
                            <span class="dashicons dashicons-download"></span>
                            <?php _e('Baixar', 'cct'); ?>
                        </button>
                        <button type="button" class="button uenf-copy-export">
                            <span class="dashicons dashicons-admin-page"></span>
                            <?php _e('Copiar', 'cct'); ?>
                        </button>
                        <button type="button" class="button uenf-close-modal"><?php _e('Fechar', 'cct'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-design-tokens-manager {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        
        .uenf-tokens-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-tokens-tabs {
            display: flex;
            gap: 5px;
        }
        
        .uenf-tab-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s ease;
        }
        
        .uenf-tab-btn:hover {
            background: #f0f8ff;
            border-color: #0073aa;
        }
        
        .uenf-tab-btn.active {
            background: #0073aa;
            color: white;
            border-color: #0073aa;
        }
        
        .uenf-tokens-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .uenf-tokens-actions .button {
            font-size: 12px;
            padding: 6px 12px;
            height: auto;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .uenf-export-dropdown {
            position: relative;
        }
        
        .uenf-export-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            min-width: 150px;
            display: none;
        }
        
        .uenf-export-dropdown.active .uenf-export-menu {
            display: block;
        }
        
        .uenf-export-format {
            display: block;
            width: 100%;
            padding: 8px 12px;
            border: none;
            background: none;
            text-align: left;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .uenf-export-format:hover {
            background: #f0f8ff;
        }
        
        .uenf-tokens-content {
            min-height: 400px;
            max-height: 600px;
            overflow-y: auto;
        }
        
        .uenf-tab-content {
            display: none;
            padding: 20px;
        }
        
        .uenf-tab-content.active {
            display: block;
        }
        
        .uenf-tokens-grid {
            display: grid;
            gap: 20px;
        }
        
        .uenf-token-category {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .uenf-category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
        }
        
        .uenf-category-title {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .uenf-category-icon {
            font-size: 16px;
        }
        
        .uenf-category-count {
            color: #666;
            font-weight: normal;
            font-size: 12px;
        }
        
        .uenf-toggle-category {
            padding: 4px;
            color: #666;
            transition: transform 0.2s ease;
        }
        
        .uenf-token-category.collapsed .uenf-toggle-category {
            transform: rotate(-90deg);
        }
        
        .uenf-category-content {
            padding: 15px;
        }
        
        .uenf-token-category.collapsed .uenf-category-content {
            display: none;
        }
        
        .uenf-subcategory-grid {
            display: grid;
            gap: 15px;
        }
        
        .uenf-token-subcategory {
            border: 1px solid #f0f0f0;
            border-radius: 4px;
            padding: 12px;
        }
        
        .uenf-subcategory-title {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-subcategory-count {
            color: #666;
            font-weight: normal;
        }
        
        .uenf-tokens-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }
        
        .uenf-token-item {
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        
        .uenf-token-item:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 8px rgba(0, 115, 170, 0.1);
        }
        
        .uenf-token-item.editing {
            border-color: #ffc107;
            background: #fff3cd;
        }
        
        .uenf-token-header {
            padding: 10px;
            background: white;
        }
        
        .uenf-token-preview {
            width: 100%;
            height: 40px;
            border-radius: 3px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 500;
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .uenf-token-name {
            margin: 0 0 4px 0;
            font-size: 13px;
            font-weight: 600;
        }
        
        .uenf-token-path {
            margin: 0 0 4px 0;
            font-size: 10px;
            color: #666;
            font-family: monospace;
        }
        
        .uenf-token-value {
            margin: 0 0 8px 0;
            font-size: 11px;
            color: #333;
            font-family: monospace;
            background: #f8f9fa;
            padding: 2px 4px;
            border-radius: 2px;
        }
        
        .uenf-token-actions {
            display: flex;
            gap: 4px;
            justify-content: flex-end;
        }
        
        .uenf-token-actions .button-link {
            padding: 4px;
            color: #666;
            text-decoration: none;
            border-radius: 2px;
            transition: all 0.2s ease;
        }
        
        .uenf-token-actions .button-link:hover {
            color: #0073aa;
            background: rgba(0, 115, 170, 0.1);
        }
        
        .uenf-token-details {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
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
        .uenf-form-col select,
        .uenf-form-col textarea {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .uenf-value-input-container {
            display: flex;
            gap: 5px;
        }
        
        .uenf-token-color-picker {
            width: 40px;
            height: 32px;
            padding: 0;
            border: 1px solid #ddd;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .uenf-css-variable {
            display: block;
            padding: 6px 8px;
            background: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 11px;
            color: #333;
        }
        
        .uenf-form-actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .uenf-form-actions .button {
            font-size: 12px;
            padding: 6px 12px;
        }
        
        /* Documentação */
        .uenf-documentation-content {
            padding: 0;
        }
        
        .uenf-doc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .uenf-doc-header h3 {
            margin: 0;
            font-size: 16px;
        }
        
        .uenf-doc-actions {
            display: flex;
            gap: 8px;
        }
        
        .uenf-doc-actions .button {
            font-size: 12px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .uenf-doc-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .uenf-stat-item {
            text-align: center;
        }
        
        .uenf-stat-item strong {
            display: block;
            font-size: 24px;
            color: #0073aa;
            margin-bottom: 4px;
        }
        
        .uenf-stat-item span {
            font-size: 12px;
            color: #666;
        }
        
        .uenf-doc-preview {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 20px;
            background: white;
            max-height: 400px;
            overflow-y: auto;
        }
        
        /* Modais */
        .uenf-import-modal,
        .uenf-export-modal {
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
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            margin: 5vh auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .uenf-export-content {
            max-width: 800px;
            width: 95%;
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
        
        .uenf-import-method {
            margin-bottom: 20px;
        }
        
        .uenf-import-method h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        
        .uenf-import-method label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .uenf-file-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            background: #f0f8ff;
            border: 2px dashed #0073aa;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            color: #0073aa;
            transition: all 0.2s ease;
        }
        
        .uenf-file-label:hover {
            background: #e6f3ff;
        }
        
        #uenf-file-input {
            display: none;
        }
        
        #uenf-import-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            resize: vertical;
        }
        
        .uenf-import-options-advanced {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .uenf-import-options-advanced h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        
        .uenf-import-options-advanced label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .uenf-export-preview {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .uenf-export-info {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .uenf-export-info p {
            margin: 0 0 8px 0;
            font-size: 13px;
        }
        
        .uenf-export-info p:last-child {
            margin-bottom: 0;
        }
        
        .uenf-export-code {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .uenf-export-code pre {
            margin: 0;
            padding: 15px;
            background: #f8f9fa;
            overflow-x: auto;
            max-height: 300px;
        }
        
        .uenf-export-code code {
            font-family: monospace;
            font-size: 12px;
            line-height: 1.4;
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
            display: flex;
            align-items: center;
            gap: 4px;
        }
        </style>
        <?php
    }
    
    /**
     * Enfileira scripts do controle
     */
    public function enqueue() {
        wp_enqueue_script(
            'uenf-design-tokens-control',
            get_template_directory_uri() . '/js/uenf-design-tokens-control.js',
            array('jquery', 'customize-controls'),
            '1.0.0',
            true
        );
        
        wp_localize_script('uenf-design-tokens-control', 'cctDesignTokensControl', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('uenf_design_tokens_nonce'),
            'strings' => array(
                'confirmDelete' => __('Tem certeza que deseja excluir este token?', 'cct'),
                'confirmImport' => __('Isso substituirá os tokens existentes. Continuar?', 'cct'),
                'tokenSaved' => __('Token salvo!', 'cct'),
                'tokenDeleted' => __('Token excluído!', 'cct'),
                'exportReady' => __('Export pronto!', 'cct'),
                'importComplete' => __('Import concluído!', 'cct'),
                'syncComplete' => __('Sincronização concluída!', 'cct'),
                'error' => __('Erro ao processar solicitação.', 'cct'),
                'invalidData' => __('Dados inválidos. Verifique os campos obrigatórios.', 'cct'),
                'copied' => __('Copiado para a área de transferência!', 'cct')
            ),
            'primitiveTokens' => $this->primitive_tokens,
            'semanticTokens' => $this->semantic_tokens,
            'componentTokens' => $this->component_tokens,
            'exportFormats' => $this->export_formats
        ));
    }
}