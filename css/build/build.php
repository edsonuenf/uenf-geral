<?php
/**
 * Script de build para minificação e combinação de arquivos CSS
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Configurações iniciais
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/build-error.log');

// Define constantes
define('CCT_THEME_DIR', dirname(dirname(__DIR__)));
define('CCT_CSS_DIR', CCT_THEME_DIR . '/css');

// Carrega configurações
$config = load_config();

/**
 * Carrega as configurações do arquivo config.json
 */
function load_config() {
    $config_file = __DIR__ . '/config.json';
    
    if (!file_exists($config_file)) {
        die("\e[31m❌ Arquivo de configuração não encontrado: $config_file\e[0m\n");
    }
    
    $config = json_decode(file_get_contents($config_file), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("\e[31m❌ Erro ao decodificar o arquivo de configuração: " . json_last_error_msg() . "\e[0m\n");
    }
    
    return $config;
}

/**
 * Minifica o CSS
 */
function minify_css($css) {
    // Remove comentários (exceto /*! importantes */)
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    
    // Remove espaços em branco desnecessários
    $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
    $css = preg_replace('/\s+/', ' ', $css);
    
    // Remove espaços antes e depois de caracteres especiais
    $css = preg_replace('/\s*([{}|:;,])\s*/', '$1', $css);
    
    // Remove o último ponto e vírgula de um bloco de propriedades
    $css = preg_replace('/;}/', '}', $css);
    
    return trim($css);
}

/**
 * Aplica o Autoprefixer (simplificado)
 */
function apply_autoprefixer($css, $browsers) {
    // Prefixos para propriedades comuns
    $prefixes = [
        'appearance' => ['-webkit-', '-moz-'],
        'user-select' => ['-webkit-', '-moz-', '-ms-'],
        'transition' => ['-webkit-', '-o-'],
        'transform' => ['-webkit-', '-ms-'],
        'flex' => ['-webkit-', '-ms-'],
        'flex-direction' => ['-webkit-'],
        'flex-wrap' => ['-webkit-'],
        'align-items' => ['-webkit-'],
        'justify-content' => ['-webkit-']
    ];
    
    foreach ($prefixes as $prop => $prefs) {
        $pattern = '/([^{]|^)(\s*)(' . $prop . '\s*:[^;]+;)/i';
        
        if (preg_match_all($pattern, $css, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $prefixed = $match[0];
                
                foreach ($prefs as $prefix) {
                    $prefixed = str_replace(
                        $match[3],
                        $prefix . $match[3],
                        $prefixed
                    );
                }
                
                $css = str_replace($match[0], $prefixed, $css);
            }
        }
    }
    
    return $css;
}

/**
 * Processa os arquivos CSS
 */
function process_css_files($config) {
    $output = '';
    $source_map = [];
    $line_count = 1;
    $start_time = microtime(true);
    
    echo "\n\e[1m🚀 Iniciando processo de build CSS\e[0m\n";
    echo str_repeat("-", 80) . "\n";
    
    // Cabeçalho do arquivo
    $output .= sprintf(
        "/*! UENF Geral - CSS Minified | %s | %s */\n\n",
        date('Y-m-d H:i:s'),
        'https://uenf.br/'
    );
    
    // Processa cada arquivo
    foreach ($config['files'] as $file) {
        $file_path = CCT_CSS_DIR . '/' . ltrim($file, '/');
        
        if (!file_exists($file_path)) {
            echo "\e[33m⚠️  Arquivo não encontrado: $file_path\e[0m\n";
            continue;
        }
        
        $content = file_get_contents($file_path);
        $file_mtime = date('Y-m-d H:i:s', filemtime($file_path));
        
        echo "\e[32m✓ Processando: $file\e[0m\n";
        echo "  - Tamanho: " . number_format(strlen($content) / 1024, 2) . " KB\n";
        echo "  - Modificado: $file_mtime\n";
        
        // Adiciona comentário com o nome do arquivo (se não for minificar)
        if (empty($config['options']['minify'])) {
            $output .= "\n/* Source: $file */\n";
        }
        
        // Adiciona ao source map
        $source_map[$file] = [
            'start' => $line_count,
            'end' => $line_count + substr_count($content, "\n")
        ];
        
        // Adiciona o conteúdo ao output
        $output .= $content . "\n";
        $line_count += substr_count($content, "\n") + 1;
    }
    
    // Aplica autoprefixer
    if (!empty($config['options']['autoprefixer'])) {
        echo "\n🔧 Aplicando Autoprefixer...\n";
        $output = apply_autoprefixer($output, $config['options']['autoprefixer']['browsers']);
    }
    
    // Minifica o CSS
    if (!empty($config['options']['minify'])) {
        echo "\n🔧 Minificando CSS...\n";
        $output = minify_css($output);
    }
    
    // Salva o arquivo
    $output_file = CCT_CSS_DIR . '/' . ltrim($config['output_file'], '/');
    $saved = file_put_contents($output_file, $output);
    
    if ($saved === false) {
        echo "\e[31m❌ Erro ao salvar o arquivo: $output_file\e[0m\n";
        exit(1);
    }
    
    // Gera o source map (se habilitado)
    if (!empty($config['options']['sourceMap'])) {
        $source_map_file = $output_file . '.map';
        $source_map_content = json_encode([
            'version' => 3,
            'sources' => array_keys($source_map),
            'mappings' => generate_mappings($source_map),
            'file' => basename($output_file)
        ], JSON_PRETTY_PRINT);
        
        file_put_contents($source_map_file, $source_map_content);
        
        // Adiciona referência ao source map
        $output .= "\n/*# sourceMappingURL=" . basename($source_map_file) . " */";
        file_put_contents($output_file, $output);
    }
    
    $end_time = microtime(true);
    $elapsed = round(($end_time - $start_time) * 1000, 2);
    
    echo "\n" . str_repeat("-", 80) . "\n";
    echo "\e[1m✅ Build concluído com sucesso!\e[0m\n";
    echo "📁 Arquivo gerado: " . realpath($output_file) . "\n";
    echo "📊 Tamanho final: " . number_format(strlen($output) / 1024, 2) . " KB\n";
    echo "⏱️  Tempo de execução: {$elapsed}ms\n\n";
    
    return true;
}

/**
 * Gera mapeamentos para o source map (simplificado)
 */
function generate_mappings($source_map) {
    $mappings = [];
    $sources = array_keys($source_map);
    
    foreach ($source_map as $file => $lines) {
        $source_index = array_search($file, $sources);
        
        for ($i = $lines['start']; $i <= $lines['end']; $i++) {
            $mappings[] = [
                'generated' => ['line' => $i, 'column' => 0],
                'source' => $source_index,
                'original' => ['line' => $i - $lines['start'] + 1, 'column' => 0],
                'name' => null
            ];
        }
    }
    
    return $mappings;
}

// Executa o processo
process_css_files($config);

