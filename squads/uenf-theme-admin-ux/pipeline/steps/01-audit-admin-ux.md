---
id: audit-admin-ux
agent: ux-auditor
execution: subagent
model_tier: powerful
outputFile: squads/uenf-theme-admin-ux/output/audit-report.md
---

# Step 1 — Auditoria UX do Painel Admin

## Objetivo
Auditar completamente o painel de administração do Tema UENF (páginas wp-admin + Customizer + Extensões) e produzir um relatório estruturado de problemas de UI/UX e tarefas pendentes.

## Contexto do Tema
O Tema UENF tem:
- **5 páginas admin**: Menu UENF, Extensões, Reset, Documentação Design, Personalizar
- **47 seções no Customizer** organizadas em 16 grupos + painel principal `uenf_panel`
- **13 extensões** (3 ativas por padrão, 10 inativas)
- **Documentação vazia**: GUIA-CONFIGURACAO-DESIGN.md não existe ou está vazia

## Arquivos a Analisar

### Painel Admin
- `functions.php` — callbacks das páginas admin (`cct_admin_page_callback`, `cct_extensions_page_callback`, `cct_reset_page_callback`, `cct_docs_design_page_callback`)
- `inc/class-extension-manager.php` ou similar — gerenciamento de extensões
- `dashboard/` — se existir, páginas de admin

### Customizer
- `inc/customizer/` — todos os managers e controls
- `inc/customizer.php` — arquivo principal do customizer

### Código em Geral
- Buscar TODOs, FIXMEs, HACKs em todos os `.php`
- Verificar existência e conteúdo de `GUIA-CONFIGURACAO-DESIGN.md`

## Critérios de Avaliação
1. **Clareza**: o usuário entende o que cada opção faz?
2. **Organização**: a hierarquia de menus/seções é lógica?
3. **Feedback**: o sistema dá confirmação de ações?
4. **Onboarding**: novo usuário consegue começar sem documentação?
5. **Consistência**: terminologia e design pattern consistentes?
6. **Completude**: há funcionalidades prometidas mas não entregues?

## Output
Salvar relatório completo em `squads/uenf-theme-admin-ux/output/audit-report.md`

## Veto Conditions
- Relatório sem classificação de severidade nos issues → reescrever com severidades
- Menos de 5 issues identificados → investigação insuficiente, refazer
- Sem seção de "Tarefas Pendentes" → adicionar obrigatoriamente
