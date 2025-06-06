const chokidar = require('chokidar');
const path = require('path');
const fs = require('fs');

// Definir diretório do tema
const CCT_THEME_DIR = '/var/www/html/wp-content/themes/uenf-geral';

// Lista de arquivos CSS a monitorar
const cssFiles = [
    'variables.css',
    'layout/main.css',
    'components/header.css',
    'components/menu.css',
    'components/search.css',
    'components/footer.css',
    'custom-fontawesome.css',
    'fonts_css.css',
    '404.css',
    'search.css',
    'styles.css'
].map(file => path.join(CCT_THEME_DIR, 'css', file));

// Função para minificar e salvar
function buildCss() {
    console.log('⚙️ Iniciando minificação...');
    require('./build.php');
    console.log('✅ Minificação e combinação de CSS concluída');
}

// Função para verificar se um arquivo existe
function checkFileExists(file) {
    if (!fs.existsSync(file)) {
        console.log(`❌ Arquivo não encontrado: ${path.basename(file)}`);
        return false;
    }
    return true;
}

// Verificar se todos os arquivos existem
console.log('=== Watch CSS iniciado ===');
console.log('Monitorando arquivos CSS para mudanças...\n');

console.log('⚙️ Arquivos sendo monitorados:');
let validFiles = cssFiles.filter(file => {
    const exists = checkFileExists(file);
    if (exists) {
        console.log(`- ${path.basename(file)}`);
    }
    return exists;
});

console.log('\n⚙️ Iniciando monitoramento...\n');

// Inicializar o watch com chokidar
const watcher = chokidar.watch(validFiles, {
    persistent: true,
    ignoreInitial: true,
    awaitWriteFinish: {
        stabilityThreshold: 2000,
        pollInterval: 100
    }
});

// Evento quando um arquivo é alterado
watcher.on('change', (path) => {
    console.log(`\n✅ Mudança detectada em: ${path.basename(path)}`);
    console.log(`  Data/hora: ${new Date().toISOString()}`);
    buildCss();
});

// Evento quando um arquivo é adicionado
watcher.on('add', (path) => {
    console.log(`\n✅ Novo arquivo adicionado: ${path.basename(path)}`);
    console.log(`  Data/hora: ${new Date().toISOString()}`);
    buildCss();
});

// Evento quando um arquivo é removido
watcher.on('unlink', (path) => {
    console.log(`\n⚠️ Arquivo removido: ${path.basename(path)}`);
    console.log(`  Data/hora: ${new Date().toISOString()}`);
    buildCss();
});

// Evento de erro
watcher.on('error', (error) => {
    console.error(`❌ Erro: ${error}`);
});
