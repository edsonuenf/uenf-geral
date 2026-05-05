#!/usr/bin/env php
<?php
// Definir diretório do tema
define('UENF_THEME_DIR', '/var/www/html/wp-content/themes/uenf-geral');

// Lista de arquivos CSS a monitorar
$cssFiles = [
    UENF_THEME_DIR . '/css/variables.css',
    UENF_THEME_DIR . '/css/layout/main.css',
    UENF_THEME_DIR . '/css/components/header.css',
    UENF_THEME_DIR . '/css/components/menu.css',
    UENF_THEME_DIR . '/css/components/search.css',
    UENF_THEME_DIR . '/css/components/footer.css',
    UENF_THEME_DIR . '/css/custom-fontawesome.css',
    UENF_THEME_DIR . '/css/fonts_css.css',
    UENF_THEME_DIR . '/css/404.css',
    UENF_THEME_DIR . '/css/search.css',
    UENF_THEME_DIR . '/css/styles.css'
];

// Função para minificar e salvar
function buildCss() {
    require_once UENF_THEME_DIR . '/css/build/build.php';
    echo "✅ Minificação e combinação de CSS concluída\n";
}

// Função para verificar mudanças
function checkForChanges($cssFiles) {
    foreach ($cssFiles as $file) {
        if (!file_exists($file)) {
            echo "❌ Arquivo não encontrado: " . basename($file) . "\n";
            continue;
        }
        
        $mtime = filemtime($file);
        echo "⚙️ Verificando: " . basename($file) . "\n";
        echo "  Última modificação: " . date('Y-m-d H:i:s', $mtime) . "\n";
    }
}

// Inicialização
echo "=== Watch CSS iniciado ===\n";
echo "Monitorando arquivos CSS para mudanças...\n\n";

echo "⚙️ Arquivos sendo monitorados:\n";
foreach ($cssFiles as $file) {
    echo "- " . basename($file) . "\n";
}

echo "\n⚙️ Iniciando monitoramento...\n\n";

// Loop principal
while (true) {
    checkForChanges($cssFiles);
    sleep(1); // Espera 1 segundo antes de verificar novamente
}
