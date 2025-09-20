<?php
/**
 * Arquivo de teste para verificar se os patterns estão carregando
 * Acesse: /wp-content/themes/uenf-geral/test-patterns.php
 */

// Verificar se o WordPress está carregado
if (!defined('ABSPATH')) {
    // Carregar o WordPress
    require_once('../../../wp-load.php');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste de Block Patterns - UENF Geral</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .pattern-info { background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <h1>Teste de Block Patterns - Tema UENF Geral</h1>
    
    <?php
    // Verificar se o suporte a patterns está ativo
    echo '<div class="pattern-info">';
    echo '<h2>1. Suporte a Block Patterns</h2>';
    if (current_theme_supports('core-block-patterns')) {
        echo '<p class="success">✓ Suporte a core-block-patterns está ATIVO</p>';
    } else {
        echo '<p class="error">✗ Suporte a core-block-patterns NÃO está ativo</p>';
    }
    echo '</div>';
    
    // Verificar se os arquivos de patterns existem
    echo '<div class="pattern-info">';
    echo '<h2>2. Arquivos de Patterns</h2>';
    $pattern_files = [
        'faq-accordion.php',
        'faq-tabs.php', 
        'pricing-table.php'
    ];
    
    foreach ($pattern_files as $file) {
        $path = get_template_directory() . '/patterns/' . $file;
        if (file_exists($path)) {
            echo '<p class="success">✓ ' . $file . ' existe</p>';
        } else {
            echo '<p class="error">✗ ' . $file . ' NÃO existe</p>';
        }
    }
    echo '</div>';
    
    // Verificar se os estilos e scripts estão sendo carregados
    echo '<div class="pattern-info">';
    echo '<h2>3. Arquivos CSS e JS</h2>';
    
    $css_path = get_template_directory() . '/css/patterns.css';
    if (file_exists($css_path)) {
        echo '<p class="success">✓ patterns.css existe</p>';
    } else {
        echo '<p class="error">✗ patterns.css NÃO existe</p>';
    }
    
    $js_path = get_template_directory() . '/js/patterns.js';
    if (file_exists($js_path)) {
        echo '<p class="success">✓ patterns.js existe</p>';
    } else {
        echo '<p class="error">✗ patterns.js NÃO existe</p>';
    }
    echo '</div>';
    
    // Verificar categorias registradas
    echo '<div class="pattern-info">';
    echo '<h2>4. Categorias de Patterns</h2>';
    if (function_exists('get_block_pattern_categories')) {
        $categories = get_block_pattern_categories();
        $uenf_categories = array_filter($categories, function($cat) {
            return strpos($cat['name'], 'uenf-') === 0;
        });
        
        if (!empty($uenf_categories)) {
            echo '<p class="success">✓ Categorias UENF encontradas:</p>';
            foreach ($uenf_categories as $cat) {
                echo '<p>- ' . $cat['label'] . ' (' . $cat['name'] . ')</p>';
            }
        } else {
            echo '<p class="warning">⚠ Nenhuma categoria UENF encontrada</p>';
        }
    } else {
        echo '<p class="error">✗ Função get_block_pattern_categories não existe</p>';
    }
    echo '</div>';
    
    echo '<div class="pattern-info">';
    echo '<h2>5. Instruções</h2>';
    echo '<p>Se todos os itens acima estão com ✓, os patterns devem aparecer no editor de blocos do WordPress.</p>';
    echo '<p><strong>Para testar:</strong></p>';
    echo '<ol>';
    echo '<li>Vá para o admin do WordPress</li>';
    echo '<li>Crie uma nova página ou post</li>';
    echo '<li>No editor de blocos, clique no botão "+" para adicionar blocos</li>';
    echo '<li>Procure pela aba "Patterns" ou "Padrões"</li>';
    echo '<li>Você deve ver as categorias "FAQ" e "Pricing" com os patterns</li>';
    echo '</ol>';
    echo '</div>';
    ?>
    
</body>
</html>