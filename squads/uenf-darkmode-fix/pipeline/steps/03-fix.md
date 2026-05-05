---
id: 03-fix
agent: fixer
execution: inline
outputFile: squads/uenf-darkmode-fix/output/fix-report.md
---

# Step 3 — Aplicar Correções

**Agente:** Carlos Correção 🔧

## Objetivo
Aplicar as correções identificadas na auditoria diretamente nos arquivos do tema.

## Input
Relatório de auditoria do Step 1.

## Correções a aplicar

Para cada bug no relatório de auditoria, aplique a correção mínima necessária:

### Correções esperadas (baseadas na auditoria):

**Bug de nomenclatura CSS (Modo Claro):**
- Arquivo: `inc/customizer/class-dark-mode-manager.php`, método `output_custom_css`
- Problema: variável gerada usa prefixo diferente do CSS estático
- Correção: alinhar o prefixo gerado com `--cct-color-{key}` no `:root`

**Bug de sanitize_callback para rgba:**
- Arquivo: `inc/customizer/class-dark-mode-manager.php`, método `add_dark_mode_settings`
- Problema: cores com valor `rgba(...)` usam `sanitize_hex_color` que retorna vazio
- Correção: usar `sanitize_text_field` para `shadow` e `overlay`, ou remover do color picker

**Verificar se há outros bugs listados na auditoria e corrigi-los.**

## Output esperado
- Relatório das correções aplicadas: arquivo, linha antes → depois
- Confirmação de que cada bug do relatório foi endereçado

## Veto Conditions
- Não corrigir bugs fora do escopo da auditoria
- Não alterar lógica de negócio
