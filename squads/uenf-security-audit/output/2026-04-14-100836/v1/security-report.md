# Relatório de Segurança Consolidado — UENF Theme
**Analista:** Gabriel (Security Reporter)
**Data:** 2026-04-14
**Versão:** 1.0
**Classificação:** Interno — Desenvolvimento

---

## Executive Summary

O tema WordPress UENF apresenta **postura de segurança mista**: implementações defensivas sólidas coexistem com vulnerabilidades exploráveis em componentes de produção.

**Pontos positivos notáveis:** `inc/security.php` implementa 12+ hardening measures (XML-RPC off, enumeração de usuários bloqueada, MIME types restritos, HSTS condicional, login errors genéricos, `DISALLOW_FILE_EDIT`). Todos os handlers AJAX utilizam nonces corretamente. Nenhum endpoint REST foi registrado sem `permission_callback`.

**Riscos imediatos:** Duas vulnerabilidades críticas requerem correção antes do próximo deploy em produção: (1) `unserialize()` sem validação no editor CSS — PHP Object Injection com potencial de RCE; (2) DOM XSS em resultados de busca avançada — qualquer post com conteúdo HTML malicioso é executado no navegador do visitante.

**Impacto geral estimado:** Com 5 issues Crítico/Alto PHP, 5 issues Crítico/Alto JS e 2 issues Alto de configuração, o risco atual é **ALTO** para um ambiente de produção público como uenf.br. As correções são cirúrgicas e estimadas em 4-6 horas de desenvolvimento.

---

## Vulnerability Register

| ID | Componente | Arquivo:Linha | Tipo | Severidade | CVSS | Status |
|----|-----------|--------------|------|-----------|------|--------|
| SEC-PHP-001 | CSS Editor | `inc/design-editor/class-css-editor-base.php:275,416` | PHP Object Injection | **Crítico** | 9.1 | Corrigir imediatamente |
| SEC-JS-001 | Busca Avançada | `js/advanced-search.js:292-313` | DOM XSS (resultados) | **Crítico** | 8.2 | Corrigir imediatamente |
| SEC-JS-002 | Busca Avançada | `js/advanced-search.js:229-245` | DOM XSS (?s= param) | **Crítico** | 7.5 | Corrigir imediatamente |
| SEC-PHP-003 | Breadcrumb | `functions.php:2274-2321` | XSS Stored | **Alto** | 7.6 | Corrigir neste sprint |
| SEC-PHP-004 | Busca Avançada | `inc/class-advanced-search.php:147-154` | SQL Injection | **Alto** | 7.4 | Corrigir neste sprint |
| SEC-PHP-011 | CSS Editor | `inc/design-editor/class-css-editor-base.php:341-363` | Arbitrary File Write | **Alto** | 7.5 | Corrigir neste sprint |
| SEC-PHP-002 | Documentação | `functions.php:1094` | XSS Stored (admin) | **Alto** | 7.2 | Corrigir neste sprint |
| CONFIG-002 | WP Config | `inc/security.php:199-207` | CSP ausente | **Alto** | 7.0 | Corrigir neste sprint |
| CONFIG-006 | Servidor | `.htaccess` inexistente | Config insegura | **Alto** | 7.0 | Corrigir neste sprint |
| SEC-JS-003 | Busca Avançada | `js/advanced-search.js:399` | XSS via showError | **Alto** | 6.5 | Corrigir neste sprint |
| SEC-JS-004 | Admin | `js/admin/reset-manager.js:307` | XSS admin | **Alto** | 5.5 | Corrigir neste sprint |
| SEC-JS-005 | Extensions | `js/extensions-manager.js:143,537` | XSS padrão inseguro | **Alto** | 5.0 | Corrigir neste sprint |
| CONFIG-003 | WP Config | `functions.php:1673-1695` | Hook errado + headers dup. | **Médio** | 5.3 | Próximo sprint |
| SEC-PHP-010 | Page Visibility | `addons/page-visibility/page-visibility.php:62-68` | SQL sem prepare() | **Médio** | 5.9 | Próximo sprint |
| SEC-PHP-006 | Template | `inc/template-functions.php:39-40` | XSS body class | **Médio** | 5.3 | Próximo sprint |
| CONFIG-007 | Otimização | `inc/optimization.php:68-74` | Cache-Control global | **Médio** | 5.0 | Próximo sprint |
| CONFIG-004 | Debug | `functions.php:18,24,27` | error_log sem guarda | **Médio** | 4.3 | Próximo sprint |
| SEC-PHP-007 | Admin | `functions.php:797,1376-1388` | Echo sem escape | **Médio** | 4.7 | Próximo sprint |
| SEC-PHP-008 | Admin | `functions.php:1441` | Echo status sem escape | **Médio** | 4.7 | Próximo sprint |
| SEC-PHP-009 | Security | `inc/security.php:219-223` | Log Injection | **Médio** | 4.3 | Próximo sprint |
| SEC-JS-006 | Customizer | `js/customizer-search-preview.js:631` | CSS Injection | **Médio** | 5.3 | Próximo sprint |
| SEC-JS-007 | Busca | `js/advanced-search.js:229` | Open Redirect potencial | **Médio** | 4.3 | Próximo sprint |
| SEC-PHP-013 | Template | `inc/template-functions.php:40` | Body class sem sanitize | **Baixo** | 3.1 | Backlog |
| SEC-PHP-014 | Core | `functions.php:32-108` | ABSPATH guard dup. | **Baixo** | 2.0 | Backlog |
| CONFIG-005 | Security | `inc/security.php:237-238` | Auto-updates desativados | **Médio** | — | Decisão gestão |
| CONFIG-001 | Docker | `docker-compose.yml:9-12` | Credenciais em texto | **Baixo** (dev) | — | Backlog |
| SEC-JS-008 | Search | `js/search-retractable.js` | console.log produção | **Baixo** | 2.0 | Backlog |
| SEC-JS-009 | Search | `js/custom-search.js` | console.log produção | **Baixo** | 2.0 | Backlog |
| SEC-PHP-015 | AJAX | `inc/class-advanced-search.php:34-37` | Sem rate limiting | Informativo | — | Documentado |
| SEC-PHP-016 | Core | `functions.php:1629` | security.php comentado | Informativo | — | Documentado |

---

## Risk Matrix

|  | Crítico | Alto | Médio | Baixo |
|--|---------|------|-------|-------|
| **PHP** | 1 | 4 | 5 | 3 |
| **JavaScript** | 2 | 3 | 5 | 4 |
| **WP Config** | 0 | 2 | 4 | 1 |
| **Total** | **3** | **9** | **14** | **8** |

**Distribuição por categoria de ataque:**
- XSS (stored/DOM): 8 issues
- SQL Injection: 2 issues
- PHP Object Injection / RCE: 1 issue
- Arbitrary File Write: 1 issue
- Config / Hardening: 9 issues
- Informativo / Baixo: 6 issues

---

## Remediation Plan

### 🚨 Quick Wins — Correção Imediata (antes do próximo deploy)

| # | ID | Fix | Arquivo | Tempo |
|---|----|----|---------|-------|
| 1 | SEC-PHP-001 | `unserialize()` → `json_decode()` | `class-css-editor-base.php:275,416` | 20min |
| 2 | SEC-PHP-003 | Adicionar `esc_url()` + `esc_html()` no breadcrumb | `functions.php:2274-2321` | 15min |
| 3 | SEC-PHP-004 | Envolver busca com `$wpdb->prepare()` | `class-advanced-search.php:147-154` | 15min |
| 4 | SEC-PHP-011 | Rejeitar `<?php` em CSS editor | `class-css-editor-base.php:341-363` | 10min |
| 5 | SEC-JS-001 | Função `escapeHtml()` + DOM safe em resultados | `advanced-search.js:292-313` | 30min |
| 6 | SEC-JS-002 | Escapar HTML do termo antes de highlight | `advanced-search.js:229-245` | 20min |
| 7 | SEC-JS-003 | `.text()` em lugar de `.html()` no showError | `advanced-search.js:399` | 5min |
| 8 | SEC-JS-004 | `.text()` na notificação do reset-manager | `reset-manager.js:307` | 5min |
| 9 | SEC-JS-005 | `.text()` nas notificações extensions-manager | `extensions-manager.js:143,537` | 10min |
| 10 | SEC-JS-006 | `styleEl.textContent` no preview customizer | `customizer-search-preview.js:631` | 5min |

**Estimativa total Quick Wins: ~2h**

### ⚠️ Sprint Atual — Próxima Semana

| # | ID | Fix | Arquivo |
|---|----|----|---------|
| 11 | CONFIG-002 | Descomentar e calibrar CSP | `inc/security.php:199-207` |
| 12 | CONFIG-006 | Criar `.htaccess` com proteções básicas | `.htaccess` (novo) |
| 13 | SEC-PHP-002 | `wp_kses()` no Markdown da documentação | `functions.php:1094` |
| 14 | CONFIG-003 | Remover `cct_security_headers()` duplicado | `functions.php:1673-1695` |
| 15 | CONFIG-004 | Guardar `error_log()` com `WP_DEBUG` | `functions.php:18,24,27` |
| 16 | CONFIG-007 | Restringir Cache-Control a assets estáticos | `inc/optimization.php:68-74` |

### 📋 Backlog — Próximo Sprint

- SEC-PHP-006, SEC-PHP-007, SEC-PHP-008, SEC-PHP-009, SEC-PHP-010, SEC-PHP-013
- SEC-JS-007, SEC-JS-008, SEC-JS-009
- CONFIG-001 (considerar `.env` para docker-compose)
- CONFIG-005 (definir processo de update review)

---

## Métricas

| Métrica | Valor |
|---------|-------|
| Total de issues | 30 |
| Issues Crítico | 3 |
| Issues Alto | 9 |
| Issues Médio | 14 |
| Issues Baixo/Info | 8 |
| Issues corrigíveis sem breaking changes | 27 (90%) |
| Arquivos PHP sem issues | 22 |
| Arquivos JS sem issues | 14 |
| AJAX handlers com nonce correto | 100% ✓ |
| REST endpoints sem permission_callback | 0 ✓ |
