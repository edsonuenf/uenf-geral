<?php
/**
 * Script para verificar se as configurações do tema UENF foram resetadas
 * 
 * Este script verifica:
 * - Se existem theme_mods ativos
 * - Quais configurações ainda estão definidas
 * - Se as configurações estão nos valores padrão
 */

// Simular ambiente WordPress básico para teste
if (!function_exists('get_theme_mods')) {
    function get_theme_mods() {
        // Simular algumas configurações que poderiam existir
        return array(
            'uenf_patterns_enabled' => true,
            'uenf_font_pairing_preset' => 'theme_default',
            'uenf_shadows_active_preset' => 'material'
        );
    }
}

if (!function_exists('get_theme_mod')) {
    function get_theme_mod($name, $default = false) {
        $mods = get_theme_mods();
        return isset($mods[$name]) ? $mods[$name] : $default;
    }
}

echo "=== VERIFICAÇÃO DE RESET DAS CONFIGURAÇÕES DO TEMA UENF ===\n\n";

// 1. Verificar theme_mods gerais
echo "1. VERIFICANDO THEME_MODS ATIVOS:\n";
echo "-----------------------------------\n";

$theme_mods = get_theme_mods();

if (empty($theme_mods)) {
    echo "✅ SUCESSO: Nenhuma configuração personalizada encontrada.\n";
    echo "   As configurações foram resetadas com sucesso!\n\n";
} else {
    echo "⚠️  ATENÇÃO: Ainda existem " . count($theme_mods) . " configurações ativas:\n";
    foreach ($theme_mods as $mod_name => $mod_value) {
        $value_display = is_bool($mod_value) ? ($mod_value ? 'true' : 'false') : $mod_value;
        echo "   - {$mod_name}: {$value_display}\n";
    }
    echo "\n";
}

// 2. Verificar configurações específicas importantes
echo "2. VERIFICANDO CONFIGURAÇÕES ESPECÍFICAS:\n";
echo "------------------------------------------\n";

$configuracoes_importantes = array(
    'uenf_patterns_enabled' => array(
        'nome' => 'Padrões Habilitados',
        'padrao' => true
    ),
    'uenf_font_pairing_preset' => array(
        'nome' => 'Preset de Fontes',
        'padrao' => 'theme_default'
    ),
    'uenf_shadows_active_preset' => array(
        'nome' => 'Preset de Sombras',
        'padrao' => 'material'
    ),
    'uenf_gradients_enabled' => array(
        'nome' => 'Gradientes Habilitados',
        'padrao' => true
    ),
    'uenf_animations_enabled' => array(
        'nome' => 'Animações Habilitadas',
        'padrao' => true
    ),
    'uenf_dark_mode_enabled' => array(
        'nome' => 'Modo Escuro',
        'padrao' => false
    )
);

$configuracoes_resetadas = 0;
$total_configuracoes = count($configuracoes_importantes);

foreach ($configuracoes_importantes as $config_key => $config_info) {
    $valor_atual = get_theme_mod($config_key, $config_info['padrao']);
    $eh_padrao = ($valor_atual === $config_info['padrao']);
    
    if ($eh_padrao) {
        echo "✅ {$config_info['nome']}: Valor padrão\n";
        $configuracoes_resetadas++;
    } else {
        $valor_display = is_bool($valor_atual) ? ($valor_atual ? 'true' : 'false') : $valor_atual;
        echo "❌ {$config_info['nome']}: {$valor_display} (não é padrão)\n";
    }
}

echo "\n";

// 3. Resumo final
echo "3. RESUMO DA VERIFICAÇÃO:\n";
echo "-------------------------\n";

if (empty($theme_mods)) {
    echo "🎉 RESET COMPLETO CONFIRMADO!\n";
    echo "   Todas as configurações foram removidas com sucesso.\n";
    echo "   O tema está usando apenas valores padrão.\n";
} else {
    $porcentagem = round(($configuracoes_resetadas / $total_configuracoes) * 100);
    echo "📊 STATUS DO RESET: {$porcentagem}% das configurações principais resetadas\n";
    echo "   - Configurações resetadas: {$configuracoes_resetadas}/{$total_configuracoes}\n";
    echo "   - Total de theme_mods ativos: " . count($theme_mods) . "\n";
    
    if ($porcentagem < 100) {
        echo "\n⚠️  RECOMENDAÇÃO:\n";
        echo "   Algumas configurações ainda não estão nos valores padrão.\n";
        echo "   Considere executar o reset novamente ou verificar manualmente.\n";
    }
}

echo "\n=== VERIFICAÇÃO CONCLUÍDA ===\n";

// 4. Instruções para verificação manual
echo "\n4. VERIFICAÇÃO MANUAL NO WORDPRESS:\n";
echo "-----------------------------------\n";
echo "Para verificar no WordPress real, acesse:\n";
echo "1. Painel Admin > Aparência > Personalizar\n";
echo "2. Verifique se as seções estão com valores padrão\n";
echo "3. Especialmente: Design > Padrões, Tipografia, Sombras\n";
echo "\nOu execute no console do WordPress:\n";
echo "var_dump(get_theme_mods());\n";

?>
