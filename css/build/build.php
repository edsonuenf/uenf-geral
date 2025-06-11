<?php
define('CCT_THEME_DIR', '/var/www/html/wp-content/themes/uenf-geral');

// Função de minificação mais robusta
function minify_css($css) {
    // Remove comentários
    $css = preg_replace('!/*[^*]*\*+([^/][^*]*\*+)*\*/!', '', $css);
    
    // Remove quebras de linha e espaços extras
    $css = preg_replace('/\s+/', ' ', $css);
    
    // Remove espaços antes e depois de colchetes
    $css = preg_replace('/\s*([{};:])\s*/', '$1', $css);
    
    // Remove espaços antes e depois de seletores
    $css = preg_replace('/\s*([,>+~])\s*/', '$1', $css);
    
    return trim($css);
}

// Função para verificar se o arquivo foi modificado
function file_was_modified($file) {
    if (!file_exists($file)) {
        echo "\e[31m❌ Arquivo não encontrado: $file\e[0m\n";
        return false;
    }
    
    $mtime = filemtime($file);
    echo "\e[34m⚙️ Verificando arquivo: $file\e[0m\n";
    echo "\e[34m- Última modificação: " . date('Y-m-d H:i:s', $mtime) . "\e[0m\n";
    return true;
}

// Caminho para os arquivos CSS na ordem correta de carregamento
$cssFiles = [
    // 1. Variáveis e reset
    CCT_THEME_DIR . '/css/variables.css',
    
    // 2. Fontes e tipografia
    CCT_THEME_DIR . '/css/fonts_css.css',
    CCT_THEME_DIR . '/css/custom-fontawesome.css',
    
    // 3. Layout base
    CCT_THEME_DIR . '/css/layout/main.css',
    
    // 4. Componentes (em ordem de especificidade)
    CCT_THEME_DIR . '/css/components/header.css',
    CCT_THEME_DIR . '/css/components/menu.css',
    CCT_THEME_DIR . '/css/components/search.css',
    CCT_THEME_DIR . '/css/components/footer.css',
    CCT_THEME_DIR . '/css/components/page-content.css',
    CCT_THEME_DIR . '/css/components/scrollbars.css',
    CCT_THEME_DIR . '/css/components/shortcuts.css',
    
    // 5. Páginas específicas
    CCT_THEME_DIR . '/css/404.css',
    CCT_THEME_DIR . '/css/search.css',
    
    // 6. Estilos globais e utilitários (sobrescreve os anteriores se necessário)
    CCT_THEME_DIR . '/css/styles.css'
];

// Verifica se todos os arquivos existem
$allFilesExist = true;
foreach ($cssFiles as $file) {
    if (!file_was_modified($file)) {
        $allFilesExist = false;
    }
}

if (!$allFilesExist) {
    echo "\e[31m❌ Erro: Alguns arquivos necessários não foram encontrados.\e[0m\n";
    exit(1);
}

// Combina e minifica
$combinedCss = '';
foreach ($cssFiles as $file) {
    echo "\e[34m⚙️ Processando arquivo: $file\e[0m\n";
    $cssContent = file_get_contents($file);
    $combinedCss .= $cssContent . "\n";
}

$minifiedCss = minify_css($combinedCss);

// Salva o arquivo minificado
$stylePath = CCT_THEME_DIR . '/css/style.css';
if (file_put_contents($stylePath, $minifiedCss) !== false) {
    echo "\e[32m✅ CSS minificado e salvo com sucesso em $stylePath!\e[0m\n";
    echo "\e[32m✅ Tamanho do arquivo: " . filesize($stylePath) . " bytes\e[0m\n";
    echo "\e[32m✅ Última modificação: " . date('Y-m-d H:i:s') . "\e[0m\n";
} else {
    echo "\e[31m❌ Erro ao salvar o arquivo CSS minificado.\e[0m\n";
    exit(1);
}
