<?php
/**
 * Arquivo temporário para forçar atualização do cache do Customizer
 * Execute este arquivo uma vez e depois delete-o
 */

// Forçar limpeza do cache do Customizer
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

// Limpar transients relacionados ao Customizer
delete_transient('cct_customizer_cache');
delete_option('theme_mods_' . get_option('stylesheet'));

// Forçar recarregamento dos arquivos do tema
if (function_exists('wp_clean_themes_cache')) {
    wp_clean_themes_cache();
}

echo "Cache do Customizer limpo com sucesso!";
echo "<br>Agora vá para Aparência > Personalizar para ver as novas opções.";
echo "<br><strong>IMPORTANTE: Delete este arquivo após usar!</strong>";
?>