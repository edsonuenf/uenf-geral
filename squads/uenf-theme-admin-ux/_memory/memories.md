# Squad Memory — uenf-theme-admin-ux

## Contexto
Squad criada em 2026-04-14 por solicitação do Edson.
Objetivo: auditar UI/UX do painel admin do Tema UENF, criar plano de trabalho e documentação.

## Escopo Definido pelo Edson
- Foco: Painel WP Admin (Tema UENF) — não o menu de frontend
- Audiências da documentação: Administrador do site + Desenvolvedor/mantenedor
- Modo: Econômico (pipeline enxuto)

## Contexto Técnico Coletado
- 5 páginas admin: Menu UENF, Extensões, Reset, Documentação Design, Personalizar
- 47 seções no Customizer em 16 grupos + painel uenf_panel
- 13 extensões (3 ativas: icons, colors, search_customizer; 10 inativas)
- GUIA-CONFIGURACAO-DESIGN.md criado e copiado para a raiz do tema ✅
- Painel admin principal é básico (só cards de status + links rápidos)

## Run Concluída — 2026-04-14-172156
- Status: **Concluída com sucesso**
- Outputs aprovados pelo Edson no checkpoint final

## Bugs Críticos Identificados (aguardando correção)
- P0-01: `reset_all_extensions()` → `reset_all_settings()` em `functions.php:2573`
- P0-02: `$extension['title']` → `$extension['name']` em `functions.php:1454`
- P0-04: nonce inconsistente — form envia `reset_nonce`, método espera `nonce`

## Backlog de Tarefas (do work-plan)
Ver: `output/2026-04-14-172156/v1/work-plan.md`
- Fase P0: 4 tarefas (~45min total) — bugs críticos
- Fase P1: 9 tarefas (~12h) — melhorias significativas
- Fase P2: 7 tarefas (~6h) — polimento e documentação de dev

## Arquivos Entregues
- `output/2026-04-14-172156/v1/audit-report.md` — relatório de auditoria completo
- `output/2026-04-14-172156/v1/work-plan.md` — plano de trabalho com 20 tarefas
- `output/2026-04-14-172156/v1/GUIA-CONFIGURACAO-DESIGN.md` — guia para admins
- `output/2026-04-14-172156/v1/GUIA-DESENVOLVEDOR-outline.md` — outline do guia dev
- `GUIA-CONFIGURACAO-DESIGN.md` — **copiado para a raiz do tema** ✅
