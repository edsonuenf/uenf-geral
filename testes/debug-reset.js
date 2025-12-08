/**
 * Script de Debug para Sistema de Reset
 * Execute no console do navegador para testar o sistema
 */

// Teste 1: Verificar se as variáveis estão disponíveis
console.log('=== TESTE 1: Verificando variáveis ===');
console.log('uenfResetManager:', typeof uenfResetManager !== 'undefined' ? uenfResetManager : 'NÃO ENCONTRADO');
console.log('jQuery:', typeof jQuery !== 'undefined' ? 'OK' : 'NÃO ENCONTRADO');
console.log('wp.customize:', typeof wp !== 'undefined' && wp.customize ? 'OK' : 'NÃO ENCONTRADO');

// Teste 2: Verificar se os elementos existem
console.log('\n=== TESTE 2: Verificando elementos DOM ===');
console.log('Extensões checkbox:', jQuery('.uenf-extension-checkbox').length);
console.log('Botão reset extensões:', jQuery('.uenf-reset-selected-extensions').length);
console.log('Botão reset completo:', jQuery('.uenf-reset-all-settings').length);

// Teste 3: Testar chamada AJAX simples
console.log('\n=== TESTE 3: Testando AJAX ===');
if (typeof uenfResetManager !== 'undefined') {
    jQuery.ajax({
        url: uenfResetManager.ajaxUrl,
        type: 'POST',
        data: {
            action: 'uenf_reset_extension_settings',
            extension_id: 'test',
            nonce: uenfResetManager.nonce
        },
        success: function(response) {
            console.log('AJAX Success:', response);
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error:', error);
            console.log('Status:', status);
            console.log('Response:', xhr.responseText);
        }
    });
} else {
    console.log('uenfResetManager não disponível para teste AJAX');
}

// Teste 4: Verificar extensões disponíveis
console.log('\n=== TESTE 4: Extensões disponíveis ===');
if (typeof uenfResetManager !== 'undefined' && uenfResetManager.extensions) {
    console.log('Extensões:', uenfResetManager.extensions);
    console.log('Total de extensões:', Object.keys(uenfResetManager.extensions).length);
} else {
    console.log('Nenhuma extensão encontrada');
}

// Teste 5: Simular reset de extensão
function testarResetExtensao(extensionId) {
    console.log('\n=== TESTE 5: Simulando reset da extensão:', extensionId, '===');
    
    if (typeof uenfResetManager === 'undefined') {
        console.log('ERRO: uenfResetManager não disponível');
        return;
    }
    
    jQuery.ajax({
        url: uenfResetManager.ajaxUrl,
        type: 'POST',
        data: {
            action: 'uenf_reset_extension_settings',
            extension_id: extensionId,
            nonce: uenfResetManager.nonce
        },
        success: function(response) {
            console.log('Reset Success:', response);
        },
        error: function(xhr, status, error) {
            console.log('Reset Error:', error);
            console.log('Status:', status);
            console.log('Response Text:', xhr.responseText);
        }
    });
}

// Instruções
console.log('\n=== INSTRUÇÕES ===');
console.log('Para testar reset de uma extensão específica, execute:');
console.log('testarResetExtensao("dark_mode")'); // exemplo
console.log('\nExtensões disponíveis para teste:');
if (typeof uenfResetManager !== 'undefined' && uenfResetManager.extensions) {
    Object.keys(uenfResetManager.extensions).forEach(function(key) {
        console.log('- ' + key);
    });
}