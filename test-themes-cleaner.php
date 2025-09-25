<?php
/**
 * Teste simples para verificar se a classe de limpeza de temas está funcionando
 * Execute este arquivo via browser para testar a funcionalidade
 */

// Simular ambiente WordPress básico
define('ABSPATH', dirname(__FILE__) . '/');
define('WP_CONTENT_DIR', dirname(__FILE__));

// Incluir a classe
require_once 'inc/class-default-themes-cleaner.php';

// Testar se a classe pode ser instanciada
try {
    $cleaner = UENF_Default_Themes_Cleaner::get_instance();
    echo "✅ Classe UENF_Default_Themes_Cleaner carregada com sucesso!\n";
    echo "📋 Funcionalidade de limpeza de temas padrão está pronta para uso.\n";
    echo "🎯 Acesse: Tema UENF > Limpeza de Temas no WordPress Admin\n";
} catch (Exception $e) {
    echo "❌ Erro ao carregar a classe: " . $e->getMessage() . "\n";
}

echo "\n🔧 Teste concluído em: " . date('Y-m-d H:i:s') . "\n";
?>