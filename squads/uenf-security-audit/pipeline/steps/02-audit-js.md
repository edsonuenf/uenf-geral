---
id: audit-js
agent: js-auditor
execution: subagent
parallel_group: security-audit
outputFile: squads/uenf-security-audit/output/js-audit-report.md
---

# Step 2: Auditoria JavaScript

Auditar todos os arquivos JavaScript do tema em busca de vulnerabilidades.

## Instruções

1. Listar todos os arquivos JS em `js/` (fonte) — ignorar `node_modules/` e `assets/dist/`
2. Verificar também JS inline em `header.php`, `footer.php`, `functions.php`
3. Para cada arquivo, verificar:
   - **DOM XSS**: `.innerHTML`, `.outerHTML`, `$(el).html()`, `document.write()`
   - **eval()**: uso de `eval`, `new Function()`, `setTimeout(string)`
   - **Dados sensíveis**: credenciais hardcoded, `console.log` com dados privados
   - **Ajax sem nonce**: `$.ajax` / `fetch` para `ajaxurl` sem enviar nonce
   - **Open Redirect**: `window.location` baseado em input de URL
   - **localStorage sensível**: armazenamento de tokens/credentials
4. Verificar `wp_localize_script()` em `functions.php` — dados expostos necessários?
5. Classificar por severidade CVSS

## Output
Relatório markdown com tabela de issues + código vulnerável vs. seguro.
