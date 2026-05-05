# Squad Memory — uenf-security-audit

## Contexto
Squad criada em 2026-04-13 para auditar segurança do tema WordPress UENF.
Run executado em 2026-04-14 (run_id: 2026-04-14-100836).

## Escopo Aprovado pelo Edson
- Código PHP do tema (XSS, SQLi, nonces, capabilities)
- JavaScript do tema (DOM XSS, eval, CSRF, dados expostos)
- Configuração WordPress (wp-config, debug, headers HTTP)
- Dependências npm: excluídas desta rodada

## Arquitetura
- Steps 1-2-3 (audit-php, audit-js, audit-wp-config) rodam em PARALELO
- Checkpoint após os 3 audits
- Fix apenas para Crítico e Alto (CVSS ≥7.0)
- Sem commit automático — Edson aprova antes

## Notas Técnicas
- functions.php tem ~2300 linhas — auditoria PHP foi extensa
- docker-compose.yml usa senhas simples (wordpress/wordpress) — ambiente de dev apenas
- WP_DEBUG tem guarda em optimization.php (comportamento esperado em dev)

## Resultados da Run 2026-04-14

### Issues encontrados
- PHP: 1 Crítico, 4 Altos, 5 Médios, 3 Baixos/Info
- JS: 2 Críticos, 3 Altos, 5 Médios, 4 Baixos/Info
- WP Config: 0 Críticos, 2 Altos, 4 Médios, 1 Baixo

### Patches aplicados (Crítico + Alto)
1. SEC-PHP-001 — `unserialize()` → `json_decode()` no CSS editor
2. SEC-PHP-011 — Rejeitar PHP em CSS editor + validar extensão
3. SEC-PHP-004 — SQL Injection na busca: `$wpdb->prepare()`
4. SEC-PHP-003 — XSS breadcrumb: `esc_url()` + `esc_html()`
5. SEC-PHP-002 — XSS docs admin: `wp_kses()`
6. CONFIG-002 — CSP ativada em `inc/security.php`
7. SEC-JS-001 — DOM XSS `createResultHTML`: reescrito com `.text()`
8. SEC-JS-002 — DOM XSS `highlightSearchTerms`: `?s=` escapado
9. SEC-JS-003 — XSS `showError`: `.text()` em lugar de `.html()`
10. SEC-JS-004 — XSS `reset-manager`: `.text()` + `safeType`
11. SEC-JS-005 — XSS `extensions-manager`: `.text()` em showNotification/showSuccessMessage
12. SEC-JS-006 — CSS Injection customizer: `textContent` em lugar de `.append('<style>')`

### Pendências (Médio/Baixo — próximo sprint)
- CONFIG-003: headers duplicados (`cct_security_headers` no hook errado em functions.php)
- CONFIG-004: `error_log()` sem guarda `WP_DEBUG` em functions.php
- CONFIG-006: .htaccess ausente
- CONFIG-007: Cache-Control global em páginas HTML
- SEC-PHP-006, SEC-PHP-007, SEC-PHP-008, SEC-PHP-009, SEC-PHP-010
- SEC-JS-007, SEC-JS-008, SEC-JS-009

### Preferências detectadas
- Edson aprovou "Prosseguir com tudo" no checkpoint-audit
- Edson aprovou patches sem ajustes no checkpoint-fixes
- Preferência por correções cirúrgicas sem refatoração adjacente
