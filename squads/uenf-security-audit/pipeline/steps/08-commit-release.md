---
id: commit-release
agent: release-mgr
execution: inline
---

# Step 8: CHANGELOG e Mensagem de Commit

Preparar documentação do patch de segurança.

## Instruções

1. Atualizar `CHANGELOG.md` com entrada `[0.3.1]`:
   - Listar issues corrigidas sem revelar detalhes de exploit
   - Formato: `### Security` seguido de bullets
2. Preparar commit message:
   ```
   fix(security): patches de segurança PHP, JS e WP config
   ```
3. Listar todos os arquivos modificados
4. **NÃO commitar** — aguardar aprovação do Edson
