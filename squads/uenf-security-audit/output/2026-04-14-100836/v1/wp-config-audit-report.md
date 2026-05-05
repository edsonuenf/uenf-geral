# Relatório de Auditoria WP Config — UENF Theme
**Auditor:** Sofia (WP Config Auditor)
**Data:** 2026-04-14
**Escopo:** docker-compose.yml, inc/optimization.php, functions.php (config), inc/security.php, header.php, .htaccess

---

## Resumo Executivo

O tema UENF apresenta postura de segurança **acima da média** para projeto WordPress acadêmico. O arquivo `inc/security.php` implementa proteções sólidas: remoção de versão WP, bloqueio de XML-RPC, headers HTTP via `send_headers`, bloqueio de enumeração de usuários e lista allowlist de MIME types. Os riscos principais são: (1) dois `error_log()` sem guarda `WP_DEBUG` ativos em produção; (2) `Content-Security-Policy` presente no código mas **comentada**; (3) `Cache-Control: max-age=31536000` aplicado a todas as páginas — inclusive HTML dinâmico; (4) ausência de `.htaccess` no repositório; (5) headers de segurança duplicados com hook incorreto em `functions.php`.

---

## Checklist de Segurança

### 1. Ambiente Docker / Desenvolvimento

| Item | Status | Observação |
|------|--------|-----------|
| Senhas de desenvolvimento (MySQL/WP) | ⚠ DEV-OK | `wordpress/wordpress` — triviais, aceitáveis apenas em dev local isolado |
| WP_DEBUG habilitado explicitamente | ✓ | `WORDPRESS_DEBUG` não definido — padrão WordPress é `false` |
| Porta 8000 exposta em `0.0.0.0` | ⚠ DEV-OK | Expõe para todas as interfaces; risco em redes compartilhadas |
| Credenciais no código-fonte versionado | ✗ | `docker-compose.yml` com senhas em texto plano — ver CONFIG-001 |
| MySQL 5.7 EOL | ⚠ | EOL outubro/2023; sem updates de segurança |

### 2. Headers HTTP de Segurança

| Header | Status | Arquivo | Observação |
|--------|--------|---------|-----------|
| `X-Frame-Options: SAMEORIGIN` | ✓ | `inc/security.php` + `functions.php` | Correto; duplicado em dois lugares |
| `X-Content-Type-Options: nosniff` | ✓ | `inc/security.php` + `functions.php` | Correto; duplicado |
| `Referrer-Policy: strict-origin-when-cross-origin` | ✓ | `functions.php` L1689 | Implementado; ausente em `inc/security.php` |
| `Permissions-Policy` | ✓ | `functions.php` L1692 | `geolocation=(), microphone=(), camera=()` — básico mas funcional |
| `Content-Security-Policy` | ✗ | `inc/security.php` L199-207 | **Comentada** — ver CONFIG-002 |
| `Strict-Transport-Security (HSTS)` | ✓ | `inc/security.php` L188 | Condicional a `is_ssl()` — correto |
| `X-XSS-Protection: 1; mode=block` | ⚠ | `functions.php` L1683 | Depreciado em navegadores modernos; não causa dano |
| Hook `send_headers` para headers | ✓ | `inc/security.php` L178 | Uso correto |
| Hook `after_setup_theme` para headers | ✗ | `functions.php` L1673 | **Hook incorreto** para headers HTTP — ver CONFIG-003 |

### 3. Configuração WordPress

| Item | Status | Observação |
|------|--------|-----------|
| `DISALLOW_FILE_EDIT` definido | ✓ | Em `functions.php` L114 e `inc/security.php` L231 (redundante) |
| Versão WP exposta via `wp_generator` | ✓ | Removida em `inc/security.php` L52 |
| Versão WP em scripts/estilos | ✓ | Filtros em `inc/security.php` e `optimization.php` |
| `the_generator` filter | ✓ | `__return_empty_string` em `inc/security.php` L17 |
| `error_log()` sem guarda `WP_DEBUG` | ✗ | `functions.php` linhas 18, 24, 27 — ver CONFIG-004 |
| XML-RPC desabilitado | ✓ | `inc/security.php` L76 |
| X-Pingback removido | ✓ | `inc/security.php` L80-84 |
| Enumeração de usuários bloqueada | ✓ | Redirecionamento de `?author=N` em `inc/security.php` L64 |
| MIME types de upload restritos (allowlist) | ✓ | `cct_restrict_mime_types` em `inc/security.php` L91 |
| Login errors genéricos | ✓ | `inc/security.php` L243 |
| Auto-update plugins/temas desativado | ⚠ | `__return_false` em `inc/security.php` L237-238 — ver CONFIG-005 |

### 4. REST API e Endpoints

| Item | Status | Observação |
|------|--------|-----------|
| `register_rest_route()` no tema | ✓ | Nenhuma chamada encontrada em todo o codebase |
| Endpoints sem `permission_callback` | ✓ | N/A — nenhum endpoint registrado |

### 5. Arquivo .htaccess

| Item | Status | Observação |
|------|--------|-----------|
| `.htaccess` presente | ✗ | **Não encontrado** — ver CONFIG-006 |
| Proteção `wp-config.php` | ✗ | Dependente do servidor de produção |
| `Options -Indexes` | ✗ | Status desconhecido sem .htaccess |

### 6. inc/optimization.php — Análise Específica

| Item | Status | Observação |
|------|--------|-----------|
| `cct_remove_script_version` — guarda `WP_DEBUG` | ✓ | Lógica correta: em dev mantém `?ver=` para cache-bust |
| `cct_add_browser_caching` — Cache-Control global | ✗ | `max-age=31536000` em **todas** as páginas — ver CONFIG-007 |
| `cct_enable_gzip` via `ini_set` | ⚠ | Pode ser ignorado pelo servidor; preferível configurar no Apache/nginx |

---

## Issues Identificados

### CONFIG-001 — Credenciais triviais versionadas no docker-compose.yml — BAIXA (dev)
**Status:** ⚠ Risco de Desenvolvimento
**Arquivo:** `docker-compose.yml` linhas 9-12, 24
**Descrição:** Credenciais `wordpress/wordpress` em texto plano no repositório git. Aceitáveis para dev local isolado, mas se o repositório for público ou compartilhado qualquer pessoa tem as credenciais do banco.
**Recomendação:** Usar arquivo `.env` (no `.gitignore`) com `MYSQL_PASSWORD=${DB_PASSWORD}` e um `docker-compose.override.yml` para credenciais locais.

---

### CONFIG-002 — Content-Security-Policy ausente — ALTA
**Status:** ✗ Problema
**Arquivo:** `inc/security.php` linhas 199-207
**Descrição:** A CSP está implementada no código mas está comentada. É a proteção mais efetiva contra XSS. O tema usa Bootstrap CDN, FontAwesome CDN e Google Fonts, portanto a CSP precisa ser calibrada para esses domínios externos.
**Recomendação:** Descomente e adapte para os domínios reais do tema. Usar `Content-Security-Policy-Report-Only` primeiro para validar sem quebrar o site.

---

### CONFIG-003 — Headers de segurança no hook errado + duplicação — MÉDIA
**Status:** ✗ Problema
**Arquivo:** `functions.php` linhas 1673-1695
**Descrição:** `cct_security_headers()` emite headers via `header()` mas está hookada em `after_setup_theme`. Este hook não é o ponto adequado para emissão de headers HTTP. Os mesmos headers já são emitidos corretamente via `send_headers` em `inc/security.php`.
**Recomendação:** Remover `cct_security_headers()` de `functions.php` — `inc/security.php` já cobre o requisito com o hook correto. Adicionar `Referrer-Policy` e `Permissions-Policy` em `inc/security.php` para consolidar tudo em um único lugar.

---

### CONFIG-004 — error_log() sem guarda WP_DEBUG em functions.php — MÉDIA
**Status:** ✗ Problema
**Arquivo:** `functions.php` linhas 18, 24, 27
**Descrição:** Três chamadas `error_log()` na função `cct_load_404_customizer()` são executadas incondicionalmente. Em produção com `WP_DEBUG_LOG = true`, são gravadas no `debug.log`. A linha 27 expõe o caminho físico completo do servidor: `error_log('ERRO: Arquivo não encontrado: ' . $file_path)`.
**Recomendação:**
```php
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Classe CCT_404_Customizer carregada com sucesso!');
}
```

---

### CONFIG-005 — Auto-updates de plugins/temas desativados em produção — MÉDIA
**Status:** ⚠ Atenção para Produção
**Arquivo:** `inc/security.php` linhas 237-238
**Descrição:** `auto_update_plugin` e `auto_update_theme` retornam `false`. Em produção impede que atualizações de segurança sejam aplicadas automaticamente.
**Recomendação:** Documentar e implementar processo de revisão mensal de atualizações, ou condicionar o filtro para que updates de segurança ainda sejam aplicados.

---

### CONFIG-006 — .htaccess ausente no repositório — ALTA
**Status:** ✗ Ausente
**Arquivo:** `.htaccess` (inexistente)
**Descrição:** Nenhum `.htaccess` encontrado. O container Docker `wordpress:latest` usa Apache. Sem `.htaccess` adequado, não há: bloqueio de listagem de diretórios, proteção de `wp-config.php`, bloqueio de execução de PHP em `uploads/`.
**Recomendação:** Fornecer um arquivo `.htaccess` de referência em `documentacoes/` com proteções mínimas: `Options -Indexes`, proteção de `wp-config.php`, bloqueio de PHP em `uploads/`.

---

### CONFIG-007 — Cache-Control global de 1 ano aplicado a páginas HTML dinâmicas — MÉDIA
**Status:** ✗ Problema
**Arquivo:** `inc/optimization.php` linhas 68-74
**Descrição:** `cct_add_browser_caching()` aplica `Cache-Control: public, max-age=31536000` a todas as requisições não-admin — incluindo páginas HTML dinâmicas. Browsers cachearão páginas de notícias e conteúdo por 1 ano.
**Recomendação:** Restringir `max-age=31536000` apenas a assets estáticos. Para páginas HTML usar `Cache-Control: public, max-age=300, must-revalidate`.

---

## Issues de Desenvolvimento (não aplicáveis a produção)

| Issue | Arquivo | Motivo para ser DEV-OK |
|-------|---------|----------------------|
| Senha `wordpress/wordpress` | `docker-compose.yml` | Ambiente local sem acesso externo; dados de teste |
| MySQL 5.7 EOL | `docker-compose.yml` | Apenas para dev; sem dados reais |
| Porta 8000 em `0.0.0.0` | `docker-compose.yml` | Aceitável em dev; para maior segurança usar `127.0.0.1:8000:80` |

---

## Resumo de Prioridades

| # | Issue | Severidade | Esforço |
|---|-------|-----------|---------|
| CONFIG-002 | CSP ausente | ALTA | Médio |
| CONFIG-006 | .htaccess ausente | ALTA | Baixo |
| CONFIG-003 | Headers no hook errado + duplicação | MÉDIA | Baixo |
| CONFIG-004 | error_log sem guarda WP_DEBUG | MÉDIA | Baixo |
| CONFIG-007 | Cache-Control global em páginas HTML | MÉDIA | Baixo |
| CONFIG-005 | Auto-updates desativados | MÉDIA | Decisão de gestão |
| CONFIG-001 | Credenciais versionadas docker-compose | BAIXA (dev) | Médio |

*Referências: WordPress Security Guidelines, OWASP Top 10, Mozilla Web Security Cheatsheet*
