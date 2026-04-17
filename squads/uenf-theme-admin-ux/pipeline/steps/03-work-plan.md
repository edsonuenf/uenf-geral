---
id: work-plan
agent: work-planner
execution: inline
outputFile: squads/uenf-theme-admin-ux/output/work-plan.md
---

# Step 3 — Plano de Trabalho

## Objetivo
Com base no relatório de auditoria da Ana, criar um plano de trabalho priorizado
com todas as tarefas necessárias para melhorar o UI/UX do painel admin do Tema UENF
e disponibilizar documentação de usuário.

## Input
Relatório de auditoria: `squads/uenf-theme-admin-ux/output/{run_id}/v1/audit-report.md`
(e qualquer ajuste de foco informado pelo Edson no checkpoint anterior)

## Instruções

1. Ler o relatório de auditoria completo
2. Classificar cada issue em P0/P1/P2
3. Para cada tarefa, especificar: arquivo(s) afetado(s), problema, solução descritiva, critério de done, estimativa
4. Agrupar por área: Admin UX / Customizer / Extensões / Documentação / Técnico
5. Organizar em Fase 1 (P0), Fase 2 (P1 principais), Fase 3 (backlog)
6. **Sempre incluir** tarefas de documentação como fase separada ou integrada

## Tarefas Obrigatórias a Incluir
Independente do que a auditoria encontrou, o plano DEVE incluir:
- Criação de GUIA-CONFIGURACAO-DESIGN.md (conteúdo para a página Docs do painel admin)
- Criação de GUIA-USUARIO-ADMIN.md (documentação para administradores)
- Criação de GUIA-DESENVOLVEDOR.md (referência técnica para devs/mantenedores)

## Output
Salvar plano completo em `squads/uenf-theme-admin-ux/output/work-plan.md`

## Veto Conditions
- Plano sem estimativas → adicionar estimativas em horas
- Plano sem tarefas de documentação → adicionar obrigatoriamente
- Tarefas vagas sem critério de done → refinar antes de entregar
