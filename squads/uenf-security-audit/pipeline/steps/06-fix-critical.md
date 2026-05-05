---
id: fix-critical
agent: security-fixer
execution: inline
inputFile: squads/uenf-security-audit/output/security-report.md
---

# Step 6: Correção de Vulnerabilidades Críticas e Altas

Implementar correções para issues classificadas como Crítico (CVSS ≥9.0) e Alto (CVSS ≥7.0).

## Instruções

1. Ler `output/security-report.md` e filtrar issues Crítico/Alto
2. Para cada issue:
   a. Ler o arquivo e linha indicados
   b. Entender o contexto da vulnerabilidade
   c. Aplicar a correção mínima usando a API WordPress correta
   d. Adicionar comentário `// SECURITY FIX (SEC-XXX): <descrição>`
3. Não alterar issues Médio/Baixo — apenas documentar o fix recomendado
4. Produzir lista de patches aplicados

## Regras
- Correção deve ser cirúrgica — não refatorar código adjacente
- Se a correção quebrar funcionalidade, documentar e escalar para o Edson
- Usar sempre a API WordPress mais específica disponível
