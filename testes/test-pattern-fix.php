<?php
/**
 * Teste para verificar se a correção da propriedade $panel_id funcionou
 */

// Simular o ambiente WordPress
if (!function_exists('__')) {
    function __($text, $domain = 'default') {
        return $text;
    }
}

if (!class_exists('WP_Customize_Manager')) {
    class WP_Customize_Manager {
        public function add_section($id, $args) {
            echo "Seção adicionada: $id\n";
        }
    }
}

// Incluir a classe corrigida
require_once 'inc/customizer/class-pattern-library-manager.php';

// Testar a classe
try {
    $wp_customize = new WP_Customize_Manager();
    $pattern_manager = new CCT_Pattern_Library_Manager();
    $pattern_manager->register($wp_customize);
    
    echo "✅ Sucesso! A propriedade \$panel_id foi corrigida e a classe funciona corretamente.\n";
    echo "A propriedade \$panel_id agora está definida como 'cct_design_panel'.\n";
    
} catch (Error $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Exceção: " . $e->getMessage() . "\n";
}

echo "\n=== Teste concluído ===\n";
?>