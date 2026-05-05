---
id: security-report
agent: security-reporter
execution: inline
outputFile: squads/uenf-security-audit/output/security-report.md
---

# Step 5: Relatório Consolidado de Segurança

Consolidar os 3 relatórios de auditoria em um único documento executivo.

## Instruções

1. Ler `output/php-audit-report.md`, `output/js-audit-report.md`, `output/wp-config-audit-report.md`
2. Criar tabela unificada de vulnerabilidades com:
   - ID sequencial (SEC-001, SEC-002, ...)
   - Tipo (PHP XSS, JS DOM, WP Config, etc.)
   - Arquivo:linha
   - Severidade (Crítico/Alto/Médio/Baixo/Info)
   - Score CVSS v3.1
   - Descrição em uma linha
3. Executive Summary: 3-5 parágrafos para o CTO/gestor
4. Risk Matrix: vulnerabilidades por categoria × severidade
5. Plano de remediação: Quick Wins primeiro, depois por sprint
6. Métricas: contagem por severidade, % corrigível sem breaking changes
