---
id: audit-php
agent: php-auditor
execution: subagent
parallel_group: security-audit
outputFile: squads/uenf-security-audit/output/php-audit-report.md
---

# Step 1: Auditoria PHP

Auditar TODOS os arquivos PHP do tema UENF em busca de vulnerabilidades.

## Instruções

1. Listar todos os arquivos PHP do tema (exceto `node_modules/`, `vendor/`)
2. Para cada arquivo, verificar:
   - **XSS**: `echo`, `print`, `<?=` sem `esc_html()`, `esc_attr()`, `esc_url()`, `esc_js()`
   - **SQL Injection**: queries sem `$wpdb->prepare()`
   - **Nonces**: forms e ajax handlers sem nonce
   - **Capabilities**: operações privilegiadas sem `current_user_can()`
   - **Sanitização de entrada**: `$_GET`/`$_POST`/`$_REQUEST` sem sanitize_*
   - **AJAX inseguro**: `add_action('wp_ajax_nopriv_...')` expondo dados
3. Classificar cada issue por severidade CVSS v3.1
4. Ignorar: arquivos dentro de `node_modules/`, arquivos CSS/JS

## Arquivos Prioritários
- `functions.php` — maior arquivo do tema (~2300 linhas)
- `inc/*.php` — includes com lógica de negócio
- `header.php`, `footer.php` — output direto ao HTML
- `template-parts/*.php` — templates com output

## Output
Relatório markdown:
- Tabela: arquivo | linha | tipo | severidade | descrição
- Para cada Crítico/Alto: código vulnerável + código corrigido
- Resumo: X crítico, Y alto, Z médio, W baixo
