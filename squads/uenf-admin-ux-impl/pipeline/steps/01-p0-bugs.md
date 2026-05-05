---
id: p0-bugs
agent: bug-fixer
execution: inline
---

# Step 1 — Correções P0 (Bugs Críticos)

## Agente
Felipe Falha 🔧

## Tarefas

### P0-01: Corrigir fatal error no Reset de Extensões
**Arquivo:** `functions.php`
**Problema:** `$extension_manager->reset_all_extensions()` não existe. O método correto é `reset_all_settings()`.
**Ação:** Encontrar a chamada e substituir.

### P0-02: Corrigir nomes das extensões na tabela
**Arquivo:** `functions.php`
**Problema:** `$extension['title']` não existe. Os dados usam a chave `name`.
**Ação:** Substituir `$extension['title']` por `$extension['name']` no fallback.

### P0-04: Corrigir nonce inconsistente no Reset
**Arquivos:** `functions.php` (formulário de reset)
**Problema:** O form envia `reset_nonce` mas `reset_all_settings()` espera `$_POST['nonce']`.
**Ação:** Renomear o campo hidden `reset_nonce` → `nonce` nos formulários de reset.

## Critério de Done
- `reset_all_settings()` é chamado em vez de `reset_all_extensions()`
- A tabela exibe o nome real das extensões (ex: "🎯 Sistema de Ícones")
- O nonce é verificado corretamente no reset de extensões

## Veto Conditions
- Não modificar a lógica de reset além da chamada de método
- Não alterar outros campos dos formulários além do campo nonce
