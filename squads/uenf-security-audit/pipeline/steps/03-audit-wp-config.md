---
id: audit-wp-config
agent: wp-config-auditor
execution: subagent
parallel_group: security-audit
outputFile: squads/uenf-security-audit/output/wp-config-audit-report.md
---

# Step 3: Auditoria de Configuração WordPress

Auditar a configuração do ambiente WordPress e do tema.

## Instruções

1. **docker-compose.yml**: verificar variáveis de ambiente
   - Senhas fracas (wordpress/wordpress)?
   - Debug mode habilitado?
   - Portas expostas desnecessariamente?

2. **inc/optimization.php**: verificar `cct_remove_script_version`
   - A guarda `WP_DEBUG` funciona corretamente?
   - Alguma função remove headers de segurança?

3. **functions.php**: verificar seções de debug
   - `error_log()` expondo dados sensíveis?
   - `WP_DEBUG` dependências corretas?

4. **Headers HTTP de segurança**: verificar se o tema emite headers
   - `add_action('send_headers', ...)` presente?
   - `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`?

5. **REST API**: verificar se endpoints do tema têm `permission_callback`
   - `register_rest_route()` sem `permission_callback`?
   - `/wp-json/wp/v2/users` acessível?

6. **DISALLOW_FILE_EDIT**: verificado via constants?

## Output
Checklist com status ✓/✗/⚠ para cada item + recomendações de correção.
