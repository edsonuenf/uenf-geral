---
id: documentation
agent: doc-writer
execution: inline
outputFile: squads/uenf-theme-admin-ux/output/GUIA-CONFIGURACAO-DESIGN.md
---

# Step 4 — Documentação de Usuário

## Objetivo
Produzir a documentação de usuário do Tema UENF para duas audiências:
1. **Administrador do site** — como usar o painel admin e o Customizer
2. **Desenvolvedor/mantenedor** — arquitetura, extensões, hooks, convenções

## Input
- Relatório de auditoria: `squads/uenf-theme-admin-ux/output/{run_id}/v1/audit-report.md`
- Plano de trabalho: `squads/uenf-theme-admin-ux/output/{run_id}/v1/work-plan.md`
- Mapa do tema (já conhecido): 5 páginas admin, 47 seções customizer, 13 extensões

## Documentos a Produzir

### 1. GUIA-CONFIGURACAO-DESIGN.md (outputFile principal)
Este é o arquivo lido pelo painel "Documentação Design" do wp-admin.
- Linguagem acessível para administradores
- Foco em: como usar as seções do Customizer para personalizar o visual
- Estrutura: Introdução → Cores → Tipografia → Header/Footer → Extensões → FAQ
- Deve ser Markdown válido e bem formatado

### 2. Seção no output sobre documentação de dev
Como adendo inline, também esboçar a estrutura do GUIA-DESENVOLVEDOR.md
(outline completo com as seções e o que cada uma deve conter — não precisa ser completo agora)

## Instruções Específicas

**Para GUIA-CONFIGURACAO-DESIGN.md:**
- Tom: amigável, direto, sem jargão técnico
- Para cada seção do Customizer: o que é, como acessar, o que pode configurar
- Incluir notas sobre extensões que precisam ser ativadas para aparecer certas opções
- Adicionar FAQ com as 5 perguntas mais comuns que um admin faria

**Para o esboço de GUIA-DESENVOLVEDOR.md:**
- Listar todas as seções com 1-2 frases descrevendo o conteúdo de cada uma
- Incluir quais hooks/filtros existem (baseado no que a auditoria encontrou)
- Estrutura de diretórios do tema

## Output Principal
`GUIA-CONFIGURACAO-DESIGN.md` — pronto para ser colocado na raiz do tema

## Veto Conditions
- Documento sem seção de Extensões → adicionar obrigatoriamente
- Menos de 3 seções do Customizer documentadas → expandir
- Linguagem técnica/jargão para seções de admin → reescrever de forma acessível
