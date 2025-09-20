# Troubleshooting - Sistema de Reset de Configurações

## Problema Reportado
"Erro ao resetar configurações. Tente novamente."

## Correções Implementadas

### 1. Correção de Acesso a Propriedades Privadas
**Problema**: O código estava tentando acessar a propriedade privada `extensions` da classe `CCT_Extension_Manager`.

**Solução**: Alterado para usar o método público `get_all_extensions()`.

**Arquivos corrigidos**:
- `inc/class-theme-reset-manager.php` (linha 368)
- `inc/customizer/class-reset-controls.php` (linha 147)

### 2. Adição de Hooks AJAX Faltantes
**Problema**: Os hooks AJAX para criar e restaurar backups não estavam implementados.

**Solução**: Adicionados os hooks e métodos correspondentes:
- `wp_ajax_uenf_create_backup`
- `wp_ajax_uenf_restore_backup`
- Método `ajax_create_backup()`
- Método `ajax_restore_backup()`
- Método `restore_settings_backup()`

## Como Testar o Sistema

### Teste 1: Verificação no Console do Navegador

1. Acesse o WordPress Customizer (`Aparência > Personalizar`)
2. Abra o Console do Navegador (F12)
3. Copie e cole o conteúdo do arquivo `debug-reset.js`
4. Execute o script e analise os resultados

### Teste 2: Verificação Manual

1. **Verificar se os arquivos estão carregados**:
   ```javascript
   // No console do navegador
   console.log(typeof uenfResetManager);
   // Deve retornar 'object', não 'undefined'
   ```

2. **Verificar se as extensões estão disponíveis**:
   ```javascript
   console.log(uenfResetManager.extensions);
   // Deve mostrar um objeto com as extensões
   ```

3. **Testar AJAX manualmente**:
   ```javascript
   testarResetExtensao('dark_mode');
   // Função disponível após executar debug-reset.js
   ```

### Teste 3: Verificação de Logs

1. **Habilitar debug no WordPress** (wp-config.php):
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

2. **Verificar logs em**: `wp-content/debug.log`

## Possíveis Causas do Erro

### 1. Cache de JavaScript
**Solução**: Limpar cache do navegador e plugins de cache

### 2. Conflito com Outros Plugins
**Diagnóstico**: Desativar temporariamente outros plugins
**Solução**: Identificar plugin conflitante

### 3. Permissões Insuficientes
**Diagnóstico**: Verificar se o usuário tem permissão `edit_theme_options`
**Solução**: Ajustar permissões do usuário

### 4. Nonce Inválido
**Diagnóstico**: Verificar se o nonce está sendo passado corretamente
**Solução**: Recarregar a página do customizer

### 5. Erro de JavaScript
**Diagnóstico**: Verificar console do navegador para erros
**Solução**: Corrigir erros de sintaxe ou conflitos

## Verificações Passo a Passo

### Passo 1: Verificar Carregamento dos Arquivos
```bash
# Verificar se os arquivos existem
ls -la inc/class-theme-reset-manager.php
ls -la inc/customizer/class-reset-controls.php
ls -la js/admin/reset-manager.js
```

### Passo 2: Verificar Inclusão no functions.php
```php
// Deve estar presente no functions.php
require_once CCT_THEME_DIR . '/inc/class-theme-reset-manager.php';
require_once CCT_THEME_DIR . '/inc/customizer/class-reset-controls.php';
```

### Passo 3: Verificar Hooks AJAX
```bash
# Buscar pelos hooks no código
grep -r "wp_ajax_uenf_" inc/
```

### Passo 4: Testar Extensões Individualmente
```javascript
// No console do customizer
Object.keys(uenfResetManager.extensions).forEach(function(ext) {
    console.log('Testando extensão:', ext);
    testarResetExtensao(ext);
});
```

## Soluções Rápidas

### Solução 1: Recarregar Customizer
1. Feche o customizer
2. Limpe o cache do navegador
3. Reabra o customizer

### Solução 2: Verificar Conflitos
1. Desative todos os plugins
2. Teste o sistema de reset
3. Reative plugins um por um

### Solução 3: Verificar Tema Ativo
1. Certifique-se que o tema UENF está ativo
2. Verifique se não há temas child conflitantes

## Logs de Debug Úteis

### JavaScript (Console do Navegador)
```javascript
// Verificar se o sistema está inicializado
console.log('UENFResetManager inicializado:', typeof UENFResetManager);

// Verificar eventos vinculados
jQuery._data(document, 'events');

// Verificar AJAX em tempo real
jQuery(document).ajaxSend(function(event, xhr, settings) {
    if (settings.data && settings.data.indexOf('uenf_reset') !== -1) {
        console.log('AJAX Reset enviado:', settings.data);
    }
});
```

### PHP (debug.log)
```php
// Adicionar logs temporários para debug
error_log('UENF Reset Manager: Classe carregada');
error_log('UENF Reset Manager: Hooks registrados');
error_log('UENF Reset Manager: AJAX recebido - ' . $_POST['action']);
```

## Contato para Suporte

Se o problema persistir após seguir este guia:

1. Execute o script `debug-reset.js` e copie os resultados
2. Verifique o arquivo `wp-content/debug.log`
3. Documente os passos que levaram ao erro
4. Inclua informações sobre plugins ativos e versão do WordPress

## Atualizações Futuras

Para evitar problemas similares:

1. **Sempre testar em ambiente de desenvolvimento**
2. **Manter backups antes de atualizações**
3. **Verificar compatibilidade com plugins**
4. **Monitorar logs regularmente**
5. **Documentar configurações personalizadas**