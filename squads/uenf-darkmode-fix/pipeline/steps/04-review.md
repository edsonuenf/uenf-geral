---
id: 04-review
agent: reviewer
execution: inline
outputFile: squads/uenf-darkmode-fix/output/review.md
---

# Step 4 — Code Review

**Agente:** Rafael Revisão ✅

## Objetivo
Verificar que cada correção aplicada endereça corretamente o bug identificado.

## Input
- Relatório de auditoria (Step 1)
- Relatório de correções (Step 3)

## Verificações

Para cada bug no relatório de auditoria:
1. A correção foi aplicada?
2. A correção endereça a causa raiz?
3. Há risco de regressão?

## Output esperado
Tabela resumo:

| Bug | Status | Notas |
|-----|--------|-------|
| ... | ✅/❌ | ... |

**Resultado final: APROVADO / BLOQUEADO**

## Veto Conditions
- Bloquear se algum P0 não foi corrigido
