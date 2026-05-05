---
id: 01-audit
agent: auditor
execution: inline
outputFile: squads/uenf-darkmode-fix/output/audit-report.md
---

# Step 1 — Auditoria Dark/Light Mode

**Agente:** Lara Luz 🔍

## Objetivo
Mapear todos os bugs de variáveis CSS nos modos Claro e Escuro do Customizer UENF.

## Arquivos a analisar
- `inc/customizer/class-dark-mode-manager.php` — registro de settings, output_custom_css, color_palettes
- `css/cct-dark-mode.css` — variáveis CSS usadas nos elementos

## Tarefas de auditoria

### 1. Mapear color_palettes vs CSS output
- Liste todas as chaves em `$this->color_palettes['light']` e `['dark']`
- Para cada chave, identifique o nome da variável CSS gerada pelo PHP em `output_custom_css`
- Para cada chave, identifique o nome da variável CSS usada no arquivo CSS

### 2. Identificar discrepâncias
- Light mode: PHP gera `--cct-light-{key}` ou `--cct-color-{key}`?
- Dark mode: PHP gera `--cct-dark-{key}` ou `--cct-color-{key}`?
- CSS estático usa qual prefixo?
- As variáveis geradas pelo PHP sobrescrevem as do CSS estático?

### 3. Verificar sanitize_callback
- Quais cores usam `sanitize_hex_color`?
- Alguma cor padrão no palette usa `rgba(...)` — incompatível com `sanitize_hex_color`?

### 4. Verificar aplicação nos elementos
- Quais elementos no CSS usam `var(--cct-color-*)` para cores além de background?
- O body usa background-color e color com as variáveis?

## Output esperado
Relatório markdown com:
- Tabela de bugs por severidade (P0/P1/P2)
- Para cada bug: descrição, arquivo+linha, evidência de código, correção proposta

## Veto Conditions
- Não reportar bugs sem evidência de código (arquivo + linha)
