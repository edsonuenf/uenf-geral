<?php
/**
 * Gerenciador de Extensões CCT
 * Controla ativação/desativação de funcionalidades avançadas
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class UENF_Extension_Manager {
    private static $instance = null;
    private $extensions = array();
    private $active_extensions = array();
    
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
        $this->load_extensions();
        $this->init_hooks();
    }
    
    /**
     * Registra uma extensão
     */
    public function register_extension($id, $extension) {
        $this->extensions[$id] = $extension;
    }
    
    /**
     * Verifica se extensão está ativa
     */
    public function is_extension_active($extension_id) {
        // Verificar configuração global
        $global_enabled = get_theme_mod('uenf_extensions_global_enabled', true);
        if (!$global_enabled) {
            return false;
        }
        
        // Defaults de instalação (sem reset executado)
        $defaults = array(
            'icons' => true,
            'colors' => true,
            'search_customizer' => true,
            'dark_mode' => false,
        );
        $default_enabled = isset($defaults[$extension_id]) ? $defaults[$extension_id] : false;
        
        // Verificar configuração individual com fallback
        return get_theme_mod('uenf_extension_' . $extension_id . '_enabled', $default_enabled);
    }
    
    /**
     * Ativa/desativa extensão
     */
    public function toggle_extension($extension_id, $enabled) {
        set_theme_mod('uenf_extension_' . $extension_id . '_enabled', $enabled);
    }
    
    /**
     * Ativa/desativa todas as extensões
     */
    public function toggle_all_extensions($enabled) {
        set_theme_mod('uenf_extensions_global_enabled', $enabled);
    }
    
    /**
     * Carrega extensões disponíveis
     */
    private function load_extensions() {
        $extensions = array(
            'dark_mode' => array(
                'name' => 'Modo Escuro/Claro',
                'description' => 'Toggle automático + preferências do usuário',
                'class' => 'UENF_Dark_Mode_Manager',
                'file' => 'class-dark-mode-manager.php',
                'category' => 'design',
                'priority' => 10,
                'dependencies' => array()
            ),
            'shadows' => array(
                'name' => 'Sistema de Sombras',
                'description' => 'Elevation system + depth layers',
                'class' => 'UENF_Shadow_Manager',
                'file' => 'class-shadow-manager.php',
                'category' => 'design',
                'priority' => 20,
                'dependencies' => array()
            ),
            'breakpoints' => array(
                'name' => 'Responsive Breakpoints',
                'description' => 'Gerenciador de pontos de quebra customizáveis',
                'class' => 'UENF_Responsive_Breakpoints_Manager',
                'file' => 'class-responsive-breakpoints-manager.php',
                'category' => 'layout',
                'priority' => 30,
                'dependencies' => array()
            ),
            'design_tokens' => array(
                'name' => 'Design Tokens',
                'description' => 'Sistema de tokens de design centralizados',
                'class' => 'UENF_Design_Tokens_Manager',
                'file' => 'class-design-tokens-manager.php',
                'category' => 'design',
                'priority' => 40,
                'dependencies' => array()
            ),
            'patterns' => array(
                'name' => 'Biblioteca de Padrões',
                'description' => 'Seções FAQ, Pricing, Team, Portfolio',
                'class' => 'UENF_Pattern_Library_Manager',
                'file' => 'class-pattern-library-manager.php',
                'category' => 'content',
                'priority' => 50,
                'dependencies' => array()
            ),
            'font_combinations' => array(
                'name' => 'Combinações de Fontes',
                'description' => 'Sistema de tipografia predefinida',
                'class' => 'UENF_Font_Combinations',
                'file' => 'customizer.php',
                'category' => 'typography',
                'priority' => 60,
                'dependencies' => array()
            ),
            'typography' => array(
                'name' => '📝 Tipografia Avançada',
                'description' => 'Sistema completo de tipografia com controles avançados',
                'class' => 'UENF_Typography_Customizer',
                'file' => 'class-typography-customizer.php',
                'category' => 'typography',
                'priority' => 55,
                'dependencies' => array()
            ),
            'gradients' => array(
                'name' => 'Sistema de Gradientes',
                'description' => 'Gradientes personalizáveis',
                'class' => 'UENF_Gradient_Manager',
                'file' => 'class-gradient-manager.php',
                'category' => 'design',
                'priority' => 70,
                'dependencies' => array()
            ),
            'animations' => array(
                'name' => 'Sistema de Animações',
                'description' => 'Animações e transições',
                'class' => 'UENF_Animation_Manager',
                'file' => 'class-animation-manager.php',
                'category' => 'effects',
                'priority' => 80,
                'dependencies' => array()
            ),
            'icons' => array(
                'name' => '🎯 Sistema de Ícones',
                'description' => 'Gerenciador de ícones personalizáveis',
                'class' => 'UENF_Icon_Manager',
                'file' => 'class-icon-manager.php',
                'category' => 'design',
                'priority' => 90,
                'dependencies' => array()
            ),
            'colors' => array(
                'name' => '🎨 Gerenciador de Cores',
                'description' => 'Paleta de cores avançada',
                'class' => 'UENF_Color_Manager',
                'file' => 'class-color-manager.php',
                'category' => 'design',
                'priority' => 100,
                'dependencies' => array()
            ),
            'search_customizer' => array(
                'name' => '🔍 Sistema de Busca Personalizado',
                'description' => 'Sistema de busca nativo que substitui plugins externos como Ivory Search',
                'class' => 'UENF_Search_Customizer',
                'file' => 'class-search-customizer.php',
                'category' => 'interface',
                'priority' => 110,
                'dependencies' => array()
            )
        );
        
        $this->extensions = apply_filters('uenf_available_extensions', $extensions);
    }
    
    /**
     * Inicializa hooks
     */
    private function init_hooks() {
        add_action('after_setup_theme', array($this, 'init_active_extensions'));
        add_action('customize_register', array($this, 'add_customizer_controls'), 20); // Prioridade 20 para carregar após outros painéis
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_customizer_scripts'));
    }
    
    /**
     * Inicializa extensões ativas
     */
    public function init_active_extensions() {
        // Verificar se o sistema está habilitado globalmente
        $global_enabled = get_theme_mod('uenf_extensions_global_enabled', true);
        
        if (!$global_enabled) {
            // Log quando sistema está desabilitado
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('CCT Extension Manager: Sistema de extensões desabilitado globalmente');
            }
            return; // Não carregar nenhuma extensão
        }
        
        foreach ($this->extensions as $id => $extension) {
            if ($this->is_extension_active($id)) {
                $this->load_extension($id, $extension);
            }
        }
    }
    
    /**
     * Carrega uma extensão específica
     */
    private function load_extension($id, $extension) {
        try {
            // Verificar dependências
            if (!empty($extension['dependencies'])) {
                foreach ($extension['dependencies'] as $dependency) {
                    if (!$this->is_extension_active($dependency)) {
                        if (defined('WP_DEBUG') && WP_DEBUG) {
                            error_log("CCT Extension Manager: Extensão {$id} não carregada - dependência {$dependency} não ativa");
                        }
                        return false; // Não carregar se dependência não está ativa
                    }
                }
            }
            
            // Verificar se a classe existe
            if (!isset($extension['class'])) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log("CCT Extension Manager: Extensão {$id} não tem classe definida");
                }
                return false;
            }
            
            if (!class_exists($extension['class'])) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log("CCT Extension Manager: Classe {$extension['class']} não encontrada para extensão {$id}");
                }
                return false;
            }
            
            // Carregar a extensão
            $this->active_extensions[$id] = new $extension['class']();
            
            // Log para debug
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('CCT Extension Manager: Loaded extension ' . $id);
            }
            
            return true;
            
        } catch (Exception $e) {
            // Log de erro
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("CCT Extension Manager: Erro ao carregar extensão {$id}: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Adiciona controles no customizer
     */
    public function add_customizer_controls($wp_customize) {
        // Debug log
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('CCT Extension Manager: Adicionando controles do customizer');
        }
        
        // Painéis uenf_theme_uenf e uenf_active_extensions removidos do Customizer:
        // não possuem controles ativos (código comentado) e exibiam painéis vazios.
        // Gerenciamento de extensões disponível em: Tema UENF → Extensões (wp-admin).
        
        // Debug log
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('CCT Extension Manager: Painel criado com sucesso');
        }
        
        // Seções do Gerenciador de Extensões - REMOVIDAS
        // Não são mais necessárias pois já existe no menu Tema UENF
        /*
        // Seção de controle global
        $wp_customize->add_section('uenf_extensions_global', array(
            'title' => '⚙️ Controle Global',
            'description' => 'Configurações gerais do sistema de extensões.',
            'panel' => 'uenf_extensions',
            'priority' => 10,
        ));
        
        // Controle global
        $wp_customize->add_setting('uenf_extensions_global_enabled', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('uenf_extensions_global_enabled', array(
            'label' => '🔄 Ativar/Desativar Todas as Extensões',
            'description' => 'Controle mestre: Ative para habilitar todas as extensões ou desative para desabilitar todas de uma vez. Útil para troubleshooting ou otimização de performance.',
            'section' => 'uenf_extensions_global',
            'type' => 'checkbox',
        ));
        
        // Debug log após criar controle
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('CCT Extension Manager: Controle global criado');
        }
        
        // Seção de teste simples
        $wp_customize->add_section('uenf_extensions_test', array(
            'title' => '🧪 Teste de Extensões',
            'description' => 'Seção de teste para verificar funcionamento.',
            'panel' => 'uenf_extensions',
            'priority' => 20,
        ));
        */
        
        // Controles do Gerenciador de Extensões - REMOVIDOS
        // Não são mais necessários pois já existe no menu Tema UENF
        /*
        // Controle de teste
        $wp_customize->add_setting('uenf_test_extension', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('uenf_test_extension', array(
            'label' => 'Extensão de Teste',
            'description' => 'Controle de teste para verificar se o painel funciona.',
            'section' => 'uenf_extensions_test',
            'type' => 'checkbox',
        ));
        
        // Debug log
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('CCT Extension Manager: Seção de teste criada');
        }
        
        // Seção de extensões
        $wp_customize->add_section('uenf_extensions_individual', array(
            'title' => '✨ Extensões',
            'description' => 'Ative ou desative cada extensão individualmente.',
            'panel' => 'uenf_extensions',
            'priority' => 30,
        ));
        
        // Criar controles individuais para cada extensão
        $priority = 10;
        foreach ($this->extensions as $extension_id => $extension) {
            // Setting para a extensão individual
            $wp_customize->add_setting('uenf_extension_' . $extension_id . '_enabled', array(
                'default' => true,
                'sanitize_callback' => 'wp_validate_boolean',
                'transport' => 'refresh',
            ));
            
            // Controle para a extensão individual
            $wp_customize->add_control('uenf_extension_' . $extension_id . '_enabled', array(
                'label' => sprintf('✨ %s', $extension['name']),
                'description' => $extension['description'],
                'section' => 'uenf_extensions_individual',
                'type' => 'checkbox',
                'priority' => $priority,
            ));
            
            $priority += 10;
        }
        */
        
        // Removida seção de ações rápidas para evitar duplicação com o controle global
        
        // Debug final
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('CCT Extension Manager: Controles individuais criados para ' . count($this->extensions) . ' extensões');
        }
    }
    
    /**
     * Enfileira scripts do customizer
     */
    public function enqueue_customizer_scripts() {
        wp_enqueue_script(
            'uenf-extensions-manager',
            get_template_directory_uri() . '/js/extensions-manager.js',
            array('jquery', 'customize-controls'),
            wp_get_theme()->get('Version'),
            true
        );
        
        // Passar dados para o JavaScript
        wp_localize_script('uenf-extensions-manager', 'cctExtensions', array(
            'extensions' => $this->extensions,
            'activeCount' => $this->get_active_extensions_count(),
            'totalCount' => count($this->extensions),
            'nonce' => wp_create_nonce('uenf_extensions_nonce')
        ));
    }
    
    /**
     * Obtém extensões ativas
     */
    public function get_active_extensions() {
        return $this->active_extensions;
    }
    
    /**
     * Obtém todas as extensões
     */
    public function get_all_extensions() {
        return $this->extensions;
    }
    
    /**
     * Conta extensões ativas
     */
    private function get_active_extensions_count() {
        $count = 0;
        foreach ($this->extensions as $id => $extension) {
            if ($this->is_extension_active($id)) {
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Obtém status de performance
     */
    private function get_performance_status($active, $total) {
        $percentage = ($active / $total) * 100;
        
        if ($percentage <= 30) {
            return '🟢 Excelente';
        } elseif ($percentage <= 60) {
            return '🟡 Boa';
        } elseif ($percentage <= 80) {
            return '🟠 Moderada';
        } else {
            return '🔴 Pesada';
        }
    }
    
    /**
     * Verifica dependências de uma extensão
     */
    public function check_dependencies($extension_id) {
        if (!isset($this->extensions[$extension_id])) {
            return false;
        }
        
        $extension = $this->extensions[$extension_id];
        
        if (empty($extension['dependencies'])) {
            return true;
        }
        
        foreach ($extension['dependencies'] as $dependency) {
            if (!$this->is_extension_active($dependency)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Obtém extensões por categoria
     */
    public function get_extensions_by_category($category) {
        $extensions = array();
        
        foreach ($this->extensions as $id => $extension) {
            if ($extension['category'] === $category) {
                $extensions[$id] = $extension;
            }
        }
        
        return $extensions;
    }
    
    /**
     * Define a configuração padrão de ativação das extensões
     * - Ativos por padrão: icons, colors, search_customizer
     * - Desativado por padrão: dark_mode
     * - Demais extensões: desativadas
     */
    public function enforce_default_activation() {
        // Habilitar controle global por padrão
        set_theme_mod('uenf_extensions_global_enabled', true);

        // Mapa de padrões
        $defaults = array(
            'icons' => true,
            'colors' => true,
            'search_customizer' => true,
            'dark_mode' => false,
        );

        // Aplicar defaults para todas as extensões conhecidas
        foreach ($this->extensions as $id => $extension) {
            $enabled = isset($defaults[$id]) ? $defaults[$id] : false;
            set_theme_mod('uenf_extension_' . $id . '_enabled', $enabled);
        }
    }

    /**
     * Reset das configurações de uma extensão específica
     * Mantém apenas o estado de ativação conforme os padrões
     */
    public function reset_extension_settings($extension_id) {
        // Remover todos os mods associados à extensão (se existirem)
        // Por simplicidade, garantimos o estado de ativação padrão
        $defaults = array(
            'icons' => true,
            'colors' => true,
            'search_customizer' => true,
            'dark_mode' => false,
        );

        $enabled = isset($defaults[$extension_id]) ? $defaults[$extension_id] : false;

        // Resetar flag de ativação para o padrão
        set_theme_mod('uenf_extension_' . $extension_id . '_enabled', $enabled);

        return true;
    }

    /**
     * Reset todas as configurações de extensões
     */
    public function reset_all_settings() {
        // Nonce verificado pelo callback da página chamante (uenf_reset_page_callback)
        // Resetar configuração global
        remove_theme_mod('uenf_extensions_global_enabled');
        
        // Resetar configurações individuais
        foreach ($this->extensions as $id => $extension) {
            remove_theme_mod('uenf_extension_' . $id . '_enabled');
        }
        
        // Aplicar padrões após reset
        $this->enforce_default_activation();

        return true;
    }
}

/**
 * Função helper para acessar o gerenciador
 */
function uenf_extension_manager() {
    return UENF_Extension_Manager::get_instance();
}

/**
 * Função helper para verificar se extensão está ativa
 */
function uenf_is_extension_active($extension_id) {
    return uenf_extension_manager()->is_extension_active($extension_id);
}

// Inicializar o gerenciador
add_action('after_setup_theme', 'uenf_extension_manager', 5); // Prioridade 5 para carregar cedo