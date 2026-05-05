#!/usr/bin/env php
<?php
// Definir diretório do tema
define('UENF_THEME_DIR', '/var/www/html/wp-content/themes/uenf-geral');

// Mensagem de inicialização
echo "\e[32m=== Watch CSS iniciado ===\e[0m\n";
echo "\e[32mMonitorando arquivos CSS para mudanças...\e[0m\n\n";

// Função para minificar e salvar
function buildCss() {
    echo "\e[34m⚙️ Iniciando minificação...\e[0m\n";
    require_once UENF_THEME_DIR . '/css/build/build.php';
    echo "\e[32m✅ Minificação e combinação de CSS concluída\e[0m\n"; // Verde
}

// Função para limpar o terminal
function clearTerminal() {
    echo "\e[H\e[J"; // Limpa o terminal
    echo "\e[32m=== Watch CSS iniciado ===\e[0m\n";
    echo "\e[32mMonitorando arquivos CSS para mudanças...\e[0m\n\n";
}

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

// Verifica se todos os arquivos existem
echo "\e[34m⚙️ Arquivos sendo monitorados:\e[0m\n";
foreach ($cssFiles as $file) {
    if (!file_exists($file)) {
        echo "\e[31m❌ Arquivo não encontrado: " . basename($file) . "\e[0m\n";
        exit(1);
    }
    echo "\e[34m- " . basename($file) . "\e[0m\n";
}

echo "\n\e[34m⚙️ Iniciando monitoramento...\e[0m\n\n";

// Comando inotifywait para monitorar mudanças
$command = 'inotifywait -m -e modify ' . implode(' ', $cssFiles);

// Executa o comando e monitora as mudanças
exec($command, $output, $returnVar);

// Quando uma mudança é detectada
foreach ($output as $line) {
    echo "\e[32m✅ Mudança detectada!\e[0m\n";
    clearTerminal();
    buildCss();
    echo "\e[32m✅ Arquivo style.css atualizado em \e[0m" . date('Y-m-d H:i:s') . "\n";
    echo "\e[32m===================================\e[0m\n\n";
}
