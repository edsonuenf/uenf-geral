<?php
/**
 * Script para resetar a configuração de border-radius dos botões no banco de dados
 * Acesse este arquivo via navegador: http://seusite.local/wp-content/themes/uenf-geral/reset-button-radius.php
 */

// Inclui o WordPress
require_once('../../../wp-config.php');

// Verifica se é uma requisição válida
if (!defined('ABSPATH')) {
    die('Acesso negado');
}

// Remove a configuração específica do customizer do banco de dados
remove_theme_mod('form_button_border_radius');

echo "<h2>Reset de Configuração Concluído</h2>";
echo "<p>✅ Configuração <strong>form_button_border_radius</strong> removida do banco de dados.</p>";
echo "<p>✅ O tema agora usará o valor padrão de <strong>12px</strong>.</p>";
echo "<p>📝 Acesse o <a href='/wp-admin/customize.php' target='_blank'>Customizer</a> para verificar a alteração.</p>";
echo "<p>⚠️ <strong>Importante:</strong> Delete este arquivo após o uso por segurança.</p>";
?>