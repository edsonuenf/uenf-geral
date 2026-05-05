<?php
/**
 * Controles Personalizados para Sistema de Ícones
 * 
 * Controles avançados para o gerenciador de ícones incluindo:
 * - Navegador de categorias interativo
 * - Biblioteca de ícones com busca
 * - Upload de ícones SVG personalizados
 * - Gerenciador de ícones favoritos
 * - Preview e otimização em tempo real
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
 * Controle Navegador de Categorias de Ícones
 */
class UENF_Icon_Category_Browser_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_icon_category_browser';
    
    /**
     * Categorias de ícones
     * 
     * @var array
     */
    public $categories = array();
    
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
        
        <div class="uenf-category-browser">
            <?php foreach ($this->categories as $category_id => $category): ?>
                <div class="uenf-category-item" data-category="<?php echo esc_attr($category_id); ?>">
                    <div class="uenf-category-icon" style="background-color: <?php echo esc_attr($category['color']); ?>">
                        <span class="uenf-category-icon-symbol"><?php echo esc_html(substr($category['name'], 0, 2)); ?></span>
                    </div>
                    
                    <div class="uenf-category-info">
                        <h4><?php echo esc_html($category['name']); ?></h4>
                        <p><?php echo esc_html($category['description']); ?></p>
                    </div>
                    
                    <div class="uenf-category-stats">
                        <span class="uenf-icon-count" data-category="<?php echo esc_attr($category_id); ?>">-</span>
                        <small><?php _e('ícones', 'cct'); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <style>
        .uenf-category-browser {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-top: 10px;
        }
        
        .uenf-category-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
        }
        
        .uenf-category-item:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 8px rgba(0,115,170,0.1);
            transform: translateY(-1px);
        }
        
        .uenf-category-item.active {
            border-color: #0073aa;
            background-color: #f0f8ff;
        }
        
        .uenf-category-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .uenf-category-icon-symbol {
            color: white;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .uenf-category-info {
            flex: 1;
        }
        
        .uenf-category-info h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-category-info p {
            margin: 0;
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .uenf-category-stats {
            text-align: center;
            min-width: 50px;
        }
        
        .uenf-icon-count {
            display: block;
            font-size: 18px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-category-stats small {
            color: #666;
            font-size: 10px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Biblioteca de Ícones
 */
class UENF_Icon_Library_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_icon_library';
    
    /**
     * Biblioteca de ícones
     * 
     * @var array
     */
    public $icon_library = array();
    
    /**
     * Categorias de ícones
     * 
     * @var array
     */
    public $categories = array();
    
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
        
        <div class="uenf-icon-library">
            <!-- Barra de busca -->
            <div class="uenf-icon-search">
                <input type="text" 
                       class="uenf-icon-search-input" 
                       placeholder="<?php esc_attr_e('Buscar ícones...', 'cct'); ?>"
                       autocomplete="off">
                <button type="button" class="uenf-search-clear" style="display: none;">
                    <span class="dashicons dashicons-no-alt"></span>
                </button>
            </div>
            
            <!-- Filtros -->
            <div class="uenf-icon-filters">
                <div class="uenf-filter-group">
                    <label><?php _e('Categoria:', 'cct'); ?></label>
                    <select class="uenf-category-filter">
                        <option value="all"><?php _e('Todas', 'cct'); ?></option>
                        <?php foreach ($this->categories as $cat_id => $category): ?>
                            <option value="<?php echo esc_attr($cat_id); ?>">
                                <?php echo esc_html($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="uenf-filter-group">
                    <label><?php _e('Exibir:', 'cct'); ?></label>
                    <select class="uenf-view-filter">
                        <option value="all"><?php _e('Todos', 'cct'); ?></option>
                        <option value="favorites"><?php _e('Favoritos', 'cct'); ?></option>
                        <option value="recent"><?php _e('Recentes', 'cct'); ?></option>
                    </select>
                </div>
            </div>
            
            <!-- Estatísticas -->
            <div class="uenf-library-stats">
                <span class="uenf-stats-item">
                    <strong class="uenf-total-icons">0</strong> ícones
                </span>
                <span class="uenf-stats-item">
                    <strong class="uenf-filtered-icons">0</strong> exibidos
                </span>
                <span class="uenf-stats-item">
                    <strong class="uenf-favorite-count">0</strong> favoritos
                </span>
            </div>
            
            <!-- Grid de ícones -->
            <div class="uenf-icon-grid" id="uenf-icon-grid">
                <!-- Ícones serão carregados via JavaScript -->
            </div>
            
            <!-- Loading -->
            <div class="uenf-loading" style="display: none;">
                <span class="spinner is-active"></span>
                <p><?php _e('Carregando ícones...', 'cct'); ?></p>
            </div>
            
            <!-- Mensagem vazia -->
            <div class="uenf-empty-message" style="display: none;">
                <p><?php _e('Nenhum ícone encontrado.', 'cct'); ?></p>
                <button type="button" class="button uenf-clear-filters">
                    <?php _e('Limpar Filtros', 'cct'); ?>
                </button>
            </div>
        </div>
        
        <!-- Modal de detalhes do ícone -->
        <div class="uenf-icon-modal" id="uenf-icon-modal" style="display: none;">
            <div class="uenf-modal-content">
                <div class="uenf-modal-header">
                    <h3 class="uenf-modal-title"></h3>
                    <button type="button" class="uenf-modal-close">
                        <span class="dashicons dashicons-no-alt"></span>
                    </button>
                </div>
                
                <div class="uenf-modal-body">
                    <div class="uenf-icon-preview">
                        <div class="uenf-icon-display"></div>
                        <div class="uenf-icon-info">
                            <p class="uenf-icon-category"></p>
                            <p class="uenf-icon-size"></p>
                        </div>
                    </div>
                    
                    <div class="uenf-icon-actions">
                        <button type="button" class="button button-primary uenf-copy-shortcode">
                            <?php _e('Copiar Shortcode', 'cct'); ?>
                        </button>
                        <button type="button" class="button uenf-copy-svg">
                            <?php _e('Copiar SVG', 'cct'); ?>
                        </button>
                        <button type="button" class="button uenf-toggle-favorite">
                            <?php _e('Favoritar', 'cct'); ?>
                        </button>
                    </div>
                    
                    <div class="uenf-code-examples">
                        <h4><?php _e('Exemplos de Uso:', 'cct'); ?></h4>
                        
                        <div class="uenf-code-example">
                            <label><?php _e('Shortcode:', 'cct'); ?></label>
                            <code class="uenf-shortcode-example"></code>
                        </div>
                        
                        <div class="uenf-code-example">
                            <label><?php _e('PHP:', 'cct'); ?></label>
                            <code class="uenf-php-example"></code>
                        </div>
                        
                        <div class="uenf-code-example">
                            <label><?php _e('SVG Direto:', 'cct'); ?></label>
                            <textarea class="uenf-svg-code" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uenf-modal-backdrop"></div>
        </div>
        
        <style>
        .uenf-icon-library {
            margin-top: 10px;
        }
        
        .uenf-icon-search {
            position: relative;
            margin-bottom: 15px;
        }
        
        .uenf-icon-search-input {
            width: 100%;
            padding: 8px 35px 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .uenf-search-clear {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
        }
        
        .uenf-icon-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .uenf-filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .uenf-filter-group label {
            font-size: 12px;
            font-weight: 500;
            color: #666;
        }
        
        .uenf-filter-group select {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .uenf-library-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .uenf-stats-item strong {
            color: #0073aa;
        }
        
        .uenf-icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 8px;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
        }
        
        .uenf-icon-item {
            position: relative;
            width: 60px;
            height: 60px;
            border: 2px solid transparent;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #fff;
        }
        
        .uenf-icon-item:hover {
            border-color: #0073aa;
            background: #f0f8ff;
            transform: scale(1.05);
        }
        
        .uenf-icon-item.favorite {
            border-color: #f39c12;
        }
        
        .uenf-icon-item.favorite::after {
            content: "★";
            position: absolute;
            top: -2px;
            right: -2px;
            background: #f39c12;
            color: white;
            font-size: 10px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .uenf-icon-item svg {
            width: 24px;
            height: 24px;
            color: #333;
        }
        
        .uenf-loading {
            text-align: center;
            padding: 40px;
        }
        
        .uenf-empty-message {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .uenf-icon-modal {
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
            background: rgba(0,0,0,0.5);
        }
        
        .uenf-modal-content {
            position: relative;
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            max-height: calc(100vh - 100px);
            overflow-y: auto;
        }
        
        .uenf-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-modal-title {
            margin: 0;
            font-size: 18px;
        }
        
        .uenf-modal-close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #666;
        }
        
        .uenf-modal-body {
            padding: 20px;
        }
        
        .uenf-icon-preview {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            align-items: center;
        }
        
        .uenf-icon-display {
            width: 80px;
            height: 80px;
            border: 2px solid #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
        }
        
        .uenf-icon-display svg {
            width: 48px;
            height: 48px;
            color: #333;
        }
        
        .uenf-icon-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .uenf-code-examples {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .uenf-code-examples h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
        }
        
        .uenf-code-example {
            margin-bottom: 15px;
        }
        
        .uenf-code-example label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #666;
        }
        
        .uenf-code-example code {
            display: block;
            padding: 8px;
            background: #f1f1f1;
            border-radius: 4px;
            font-size: 12px;
            word-break: break-all;
        }
        
        .uenf-svg-code {
            width: 100%;
            height: 100px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            font-size: 11px;
            resize: vertical;
        }
        </style>
        <?php
    }
}

/**
 * Controle Upload de Ícones
 */
class UENF_Icon_Upload_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_icon_upload';
    
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
        
        <div class="uenf-icon-upload">
            <!-- Área de upload -->
            <div class="uenf-upload-area" id="uenf-upload-area">
                <div class="uenf-upload-content">
                    <span class="dashicons dashicons-upload"></span>
                    <h4><?php _e('Arraste arquivos SVG aqui', 'cct'); ?></h4>
                    <p><?php _e('ou clique para selecionar', 'cct'); ?></p>
                    <input type="file" 
                           class="uenf-file-input" 
                           id="uenf-file-input"
                           accept=".svg,image/svg+xml"
                           multiple>
                </div>
                
                <div class="uenf-upload-info">
                    <ul>
                        <li><?php _e('Apenas arquivos SVG são aceitos', 'cct'); ?></li>
                        <li><?php _e('Tamanho máximo: 1MB por arquivo', 'cct'); ?></li>
                        <li><?php _e('Múltiplos arquivos podem ser enviados', 'cct'); ?></li>
                        <li><?php _e('Otimização automática será aplicada', 'cct'); ?></li>
                    </ul>
                </div>
            </div>
            
            <!-- Progresso do upload -->
            <div class="uenf-upload-progress" style="display: none;">
                <div class="uenf-progress-bar">
                    <div class="uenf-progress-fill"></div>
                </div>
                <p class="uenf-progress-text">0%</p>
            </div>
            
            <!-- Lista de arquivos -->
            <div class="uenf-file-list" id="uenf-file-list"></div>
            
            <!-- Configurações de otimização -->
            <div class="uenf-optimization-settings">
                <h4><?php _e('Configurações de Otimização', 'cct'); ?></h4>
                
                <label class="uenf-checkbox-label">
                    <input type="checkbox" class="uenf-optimize-remove-comments" checked>
                    <?php _e('Remover comentários', 'cct'); ?>
                </label>
                
                <label class="uenf-checkbox-label">
                    <input type="checkbox" class="uenf-optimize-remove-metadata" checked>
                    <?php _e('Remover metadados', 'cct'); ?>
                </label>
                
                <label class="uenf-checkbox-label">
                    <input type="checkbox" class="uenf-optimize-minify" checked>
                    <?php _e('Minificar SVG', 'cct'); ?>
                </label>
                
                <label class="uenf-checkbox-label">
                    <input type="checkbox" class="uenf-add-aria-labels" checked>
                    <?php _e('Adicionar ARIA labels', 'cct'); ?>
                </label>
            </div>
        </div>
        
        <style>
        .uenf-icon-upload {
            margin-top: 10px;
        }
        
        .uenf-upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 30px 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        
        .uenf-upload-area:hover,
        .uenf-upload-area.dragover {
            border-color: #0073aa;
            background-color: #f0f8ff;
        }
        
        .uenf-upload-content .dashicons {
            font-size: 48px;
            color: #0073aa;
            margin-bottom: 10px;
        }
        
        .uenf-upload-content h4 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: #333;
        }
        
        .uenf-upload-content p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .uenf-file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        
        .uenf-upload-info {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .uenf-upload-info ul {
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 12px;
            color: #666;
        }
        
        .uenf-upload-info li {
            margin-bottom: 4px;
            position: relative;
            padding-left: 15px;
        }
        
        .uenf-upload-info li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #0073aa;
        }
        
        .uenf-upload-progress {
            margin: 15px 0;
        }
        
        .uenf-progress-bar {
            width: 100%;
            height: 8px;
            background: #f1f1f1;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .uenf-progress-fill {
            height: 100%;
            background: #0073aa;
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .uenf-progress-text {
            text-align: center;
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        
        .uenf-file-list {
            margin-top: 15px;
        }
        
        .uenf-file-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
            background: #fff;
        }
        
        .uenf-file-icon {
            width: 32px;
            height: 32px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .uenf-file-info {
            flex: 1;
        }
        
        .uenf-file-name {
            font-weight: 500;
            font-size: 13px;
            margin-bottom: 2px;
        }
        
        .uenf-file-size {
            font-size: 11px;
            color: #666;
        }
        
        .uenf-file-status {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
            margin-left: 10px;
        }
        
        .uenf-file-status.uploading {
            background: #fff3cd;
            color: #856404;
        }
        
        .uenf-file-status.success {
            background: #d4edda;
            color: #155724;
        }
        
        .uenf-file-status.error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .uenf-optimization-settings {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .uenf-optimization-settings h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
        }
        
        .uenf-checkbox-label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            cursor: pointer;
        }
        
        .uenf-checkbox-label input {
            margin-right: 8px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Gerenciador de Ícones Personalizados
 */
class UENF_Custom_Icon_Manager_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_custom_icon_manager';
    
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
        
        <div class="uenf-custom-icon-manager">
            <!-- Estatísticas -->
            <div class="uenf-manager-stats">
                <div class="uenf-stat-item">
                    <strong class="uenf-custom-count">0</strong>
                    <span><?php _e('Ícones Personalizados', 'cct'); ?></span>
                </div>
                <div class="uenf-stat-item">
                    <strong class="uenf-total-size">0 KB</strong>
                    <span><?php _e('Tamanho Total', 'cct'); ?></span>
                </div>
            </div>
            
            <!-- Ações em lote -->
            <div class="uenf-bulk-actions">
                <select class="uenf-bulk-select">
                    <option value=""><?php _e('Ações em lote', 'cct'); ?></option>
                    <option value="optimize"><?php _e('Otimizar selecionados', 'cct'); ?></option>
                    <option value="export"><?php _e('Exportar selecionados', 'cct'); ?></option>
                    <option value="delete"><?php _e('Excluir selecionados', 'cct'); ?></option>
                </select>
                <button type="button" class="button uenf-apply-bulk" disabled>
                    <?php _e('Aplicar', 'cct'); ?>
                </button>
            </div>
            
            <!-- Lista de ícones personalizados -->
            <div class="uenf-custom-icons-list" id="uenf-custom-icons-list">
                <!-- Ícones serão carregados via JavaScript -->
            </div>
            
            <!-- Mensagem vazia -->
            <div class="uenf-empty-custom-icons" style="display: none;">
                <p><?php _e('Nenhum ícone personalizado encontrado.', 'cct'); ?></p>
                <p><?php _e('Use a seção de upload para adicionar seus próprios ícones SVG.', 'cct'); ?></p>
            </div>
        </div>
        
        <style>
        .uenf-custom-icon-manager {
            margin-top: 10px;
        }
        
        .uenf-manager-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 6px;
        }
        
        .uenf-stat-item {
            text-align: center;
        }
        
        .uenf-stat-item strong {
            display: block;
            font-size: 18px;
            color: #0073aa;
            margin-bottom: 2px;
        }
        
        .uenf-stat-item span {
            font-size: 11px;
            color: #666;
        }
        
        .uenf-bulk-actions {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .uenf-bulk-select {
            flex: 1;
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .uenf-apply-bulk {
            font-size: 12px;
        }
        
        .uenf-custom-icons-list {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .uenf-custom-icon-item {
            display: flex;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s ease;
        }
        
        .uenf-custom-icon-item:last-child {
            border-bottom: none;
        }
        
        .uenf-custom-icon-item:hover {
            background-color: #f9f9f9;
        }
        
        .uenf-custom-icon-item.selected {
            background-color: #e3f2fd;
        }
        
        .uenf-icon-checkbox {
            margin-right: 10px;
        }
        
        .uenf-custom-icon-preview {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            flex-shrink: 0;
        }
        
        .uenf-custom-icon-preview svg {
            width: 24px;
            height: 24px;
            color: #333;
        }
        
        .uenf-custom-icon-details {
            flex: 1;
            min-width: 0;
        }
        
        .uenf-icon-name {
            font-weight: 500;
            font-size: 13px;
            margin-bottom: 2px;
            word-break: break-word;
        }
        
        .uenf-icon-meta {
            font-size: 11px;
            color: #666;
        }
        
        .uenf-custom-icon-actions {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }
        
        .uenf-icon-action {
            padding: 4px 8px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 3px;
            cursor: pointer;
            font-size: 11px;
            transition: all 0.2s ease;
        }
        
        .uenf-icon-action:hover {
            background: #f0f0f0;
            border-color: #999;
        }
        
        .uenf-icon-action.danger:hover {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        .uenf-empty-custom-icons {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        
        .uenf-empty-custom-icons p {
            margin-bottom: 8px;
        }
        </style>
        <?php
    }
}