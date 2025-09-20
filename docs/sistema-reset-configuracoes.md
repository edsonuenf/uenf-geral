# Sistema de Reset de Configurações - Tema UENF

## Visão Geral

O sistema de reset de configurações permite restaurar as configurações do tema e suas extensões aos valores padrão, facilitando o processo de deploy e manutenção.

## Arquivos Criados

### 1. Gerenciador Principal
- **Arquivo**: `inc/class-theme-reset-manager.php`
- **Função**: Gerencia todas as operações de reset e backup
- **Recursos**:
  - Reset de extensões individuais
  - Reset completo do tema
  - Sistema de backup automático
  - Limpeza de backups antigos
  - Hooks AJAX para interface

### 2. Controles do Customizer
- **Arquivo**: `inc/customizer/class-reset-controls.php`
- **Função**: Adiciona controles de reset no WordPress Customizer
- **Controles Disponíveis**:
  - `UENF_Reset_Extensions_Control`: Reset de extensões específicas
  - `UENF_Reset_All_Control`: Reset completo de configurações
  - `UENF_Backup_Control`: Gerenciamento de backups

### 3. Interface JavaScript
- **Arquivo**: `js/admin/reset-manager.js`
- **Função**: Gerencia a interface de usuário para operações de reset
- **Recursos**:
  - Confirmações de segurança
  - Feedback visual de operações
  - Gerenciamento de estado dos botões
  - Comunicação AJAX com backend

## Como Usar

### No WordPress Customizer

1. **Acesse o Customizer**:
   - Vá para `Aparência > Personalizar`
   - Navegue até `Tema UENF > Configurações de Reset`

2. **Reset de Extensões Específicas**:
   - Selecione a extensão desejada no dropdown
   - Clique em "Resetar Extensão"
   - Confirme a operação

3. **Reset Completo**:
   - Clique em "Resetar Todas as Configurações"
   - Confirme a operação (irreversível)

4. **Gerenciar Backups**:
   - Visualize backups disponíveis
   - Restaure configurações de backup específico
   - Limpe backups antigos

### Via Código PHP

```php
// Obter instância do gerenciador
$reset_manager = UENF_Theme_Reset_Manager::get_instance();

// Reset de extensão específica
$reset_manager->reset_extension_settings('dark_mode');

// Reset completo
$reset_manager->reset_all_theme_settings();

// Criar backup
$reset_manager->create_backup();

// Restaurar backup
$reset_manager->restore_backup('backup_20240115_143022');
```

## Extensões Suportadas

O sistema suporta reset das seguintes extensões:
- Dark Mode
- Shadows
- Breakpoints
- Design Tokens
- Biblioteca de Padrões
- Combinações de Fontes
- Tipografia Avançada
- Gradientes
- Animações
- Sistema de Ícones
- Gerenciador de Cores
- Sistema de Busca Personalizado

## Processo de Deploy

### Pré-Deploy
1. **Criar Backup Completo**:
   ```php
   UENF_Theme_Reset_Manager::get_instance()->create_backup();
   ```

2. **Verificar Configurações Atuais**:
   - Documente configurações críticas
   - Exporte configurações personalizadas se necessário

### Durante o Deploy
1. **Reset Opcional**:
   - Se necessário, execute reset completo
   - Aplique configurações padrão

2. **Teste de Funcionalidades**:
   - Verifique se todas as extensões funcionam
   - Teste controles do customizer

### Pós-Deploy
1. **Configuração Final**:
   - Aplique configurações específicas do ambiente
   - Teste funcionalidades críticas

2. **Limpeza**:
   - Remova backups desnecessários
   - Documente configurações aplicadas

## Segurança

### Verificações Implementadas
- **Nonce Verification**: Todas as operações AJAX verificam nonces
- **Capability Check**: Apenas usuários com `manage_options` podem executar resets
- **Confirmações**: Interface requer confirmação para operações destrutivas
- **Backups Automáticos**: Backup criado antes de operações de reset

### Boas Práticas
- Sempre criar backup antes de reset completo
- Testar em ambiente de desenvolvimento primeiro
- Documentar configurações personalizadas importantes
- Limpar backups antigos regularmente

## Troubleshooting

### Problemas Comuns

1. **Reset não funciona**:
   - Verificar permissões de usuário
   - Verificar se JavaScript está habilitado
   - Verificar console do navegador para erros

2. **Backup falha**:
   - Verificar espaço em disco
   - Verificar permissões de escrita
   - Verificar logs do WordPress

3. **Interface não aparece**:
   - Verificar se arquivos foram incluídos corretamente
   - Verificar se customizer está funcionando
   - Limpar cache se necessário

### Logs e Debug

O sistema registra operações importantes nos logs do WordPress:
```php
// Habilitar debug no wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Manutenção

### Limpeza Automática
- Backups são automaticamente limpos após 30 dias
- Configuração pode ser alterada via filtro `uenf_backup_retention_days`

### Monitoramento
- Verificar logs regularmente
- Monitorar uso de espaço em disco
- Testar funcionalidades periodicamente

## Extensibilidade

### Adicionar Nova Extensão

1. **Registrar no Extension Manager**:
   ```php
   // Em class-extension-manager.php
   'nova_extensao' => array(
       'name' => 'Nova Extensão',
       'description' => 'Descrição da extensão',
       // ... outras configurações
   )
   ```

2. **Implementar Reset**:
   - A extensão será automaticamente incluída no sistema de reset
   - Configurações são removidas via `remove_theme_mod()`

### Hooks Disponíveis

```php
// Antes do reset de extensão
do_action('uenf_before_extension_reset', $extension_key);

// Após reset de extensão
do_action('uenf_after_extension_reset', $extension_key);

// Antes do reset completo
do_action('uenf_before_complete_reset');

// Após reset completo
do_action('uenf_after_complete_reset');

// Filtro para retenção de backup
apply_filters('uenf_backup_retention_days', 30);
```

## Conclusão

O sistema de reset de configurações fornece uma solução robusta e segura para gerenciar configurações do tema UENF, facilitando deploys e manutenção while mantendo a flexibilidade e segurança necessárias para um ambiente de produção.