<?php
/**
 * Script temporário para resetar o icon size para o padrão
 * Execute este arquivo uma vez e depois delete-o
 */

// Carregar WordPress
require_once('../../../wp-load.php');

// Remover apenas o valor do icon size para usar o padrão
remove_theme_mod('social_media_icon_size');

echo "✅ Icon size resetado para o padrão (32px)!\n";
echo "Agora você pode deletar este arquivo.\n";
?>
