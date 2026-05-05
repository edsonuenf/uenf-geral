# Mudanças — Squads UENF (sessão 2026-04-13)

Branch: `aparencia`

---

## 1. Squad `uenf-css-performance` — CSS Split Responsivo

### Objetivo
Dividir CSS responsivo em bundles mobile/tablet/desktop carregados condicionalmente via `<link media="...">`, reduzindo CSS render-blocking por device.

### Arquivos Criados

| Arquivo | Linhas | Descrição |
|---------|--------|-----------|
| `css/responsive/mobile.css` | 440 | Regras `max-width: 767.98px` extraídas dos 5 arquivos prioritários |
| `css/responsive/tablet.css` | 47 | Regras de breakpoint intermediário (≤1024px) |
| `css/responsive/desktop.css` | 89 | Regras `min-width: 1201px` e `min-width: 1921px` |

### Arquivos Modificados (remoção de @media blocks)

| Arquivo | Antes | Depois | Removido |
|---------|-------|--------|---------|
| `css/custom-fixes.css` | 508 linhas | 323 linhas | -185 linhas mobile |
| `css/components/shortcuts.css` | 319 linhas | 278 linhas | -41 linhas mobile |
| `css/components/header.css` | 184 linhas | 128 linhas | -56 linhas mobile/tablet |
| `css/components/footer.css` | 411 linhas | 298 linhas | -113 linhas mobile |
| `css/components/new-menu.css` | 711 linhas | 618 linhas | -93 linhas mobile/desktop |
| **Total** | **2133** | **1645** | **-488 linhas** |

### `functions.php` — Enqueue Condicional (adicionado após linha ~1995)

```php
// CSS Responsivo Condicional — non-render-blocking
wp_enqueue_style('cct-responsive-mobile', .../mobile.css, [...], $ver);
wp_style_add_data('cct-responsive-mobile', 'media', '(max-width:767.98px)');

wp_enqueue_style('cct-responsive-tablet', .../tablet.css, [...], $ver);
wp_style_add_data('cct-responsive-tablet', 'media', '(min-width:768px) and (max-width:991.98px)');

wp_enqueue_style('cct-responsive-desktop', .../desktop.css, [...], $ver);
wp_style_add_data('cct-responsive-desktop', 'media', '(min-width:992px)');
```

### HTML gerado pelo WordPress
```html
<link rel='stylesheet' href='.../mobile.css'  media='(max-width:767.98px)'>
<link rel='stylesheet' href='.../tablet.css'  media='(min-width:768px) and (max-width:991.98px)'>
<link rel='stylesheet' href='.../desktop.css' media='(min-width:992px)'>
```

### Impacto estimado em Core Web Vitals
- Desktop: -488 linhas de CSS que deixam de bloquear render (mobile.css não é render-blocking)
- FCP desktop: -40ms a -120ms (conexão lenta), -5ms a -20ms (fibra)
- Nenhuma regressão visual esperada (regras movidas, não alteradas)

### Outputs gerados
- `squads/uenf-css-performance/output/audit-report.md`
- `squads/uenf-css-performance/output/architecture-plan.md`
- `squads/uenf-css-performance/output/build-report.md`
- `squads/uenf-css-performance/output/seo-report.md`

### Build
```
webpack 5.101.0 compiled successfully in 3982 ms
asset css/style.min.css 11.6 KiB
```

---

## 2. Squad `uenf-security-audit` — Auditoria de Segurança

### Objetivo
Auditar o tema em busca de vulnerabilidades de segurança em PHP, JavaScript e configuração WordPress. Corrigir issues Críticas e Altas automaticamente.

### Status: CRIADA — aguardando execução

Execute com: `/opensquad run uenf-security-audit`

### Agentes

| Agente | Responsável | Escopo |
|--------|------------|--------|
| 🔐 Valentina | php-auditor | XSS, SQLi, nonces, capabilities, sanitização |
| 🛡️ Bruno | js-auditor | DOM XSS, eval, CSRF, dados expostos, localStorage |
| ⚙️ Sofia | wp-config-auditor | wp-config, debug mode, headers HTTP, REST API |
| 📋 Gabriel | security-reporter | Consolidação, classificação CVSS, plano de remediação |
| 🔧 Isabela | security-fixer | Correções para issues Crítico/Alto |
| 📦 Marco | release-mgr | CHANGELOG e commit message (sem commitar) |

### Pipeline (8 steps)
```
[1] audit-php    ─┐
[2] audit-js     ─┼─ PARALELO (3 subagentes simultâneos)
[3] audit-wp-config ┘
[4] checkpoint-audit        ← revisão pelo Edson
[5] security-report         ← relatório consolidado
[6] fix-critical            ← correções automáticas (Crítico/Alto)
[7] checkpoint-fixes        ← revisão das correções
[8] commit-release          ← CHANGELOG (sem commitar)
```

### Escopo aprovado
- PHP: XSS, SQL injection, nonces, capabilities, file inclusion
- JS: DOM XSS, eval, CSRF, localStorage, dados expostos
- WP Config: debug mode, headers HTTP, REST API, permissões
- Excluído: dependências npm (próxima rodada)

---

## ⏳ O que ainda falta fazer

### Imediato (antes do próximo commit)
1. **Validação visual** — o servidor está rodando em http://localhost:8000
   - Verificar mobile (375px): barra inferior, painel de atalhos, footer
   - Verificar tablet (768px): hero section, footer grid
   - Verificar desktop (1920px): menu offcanvas large, layout geral
   - Confirmar que nenhum estilo quebrou com o split de CSS

2. **Commit das mudanças do uenf-css-performance**
   - 6 arquivos modificados, 3 arquivos criados em `css/responsive/`
   - Sugestão de mensagem: `perf(css): split responsivo mobile/tablet/desktop com media attribute`

### Próximo sprint
3. **Executar `uenf-security-audit`** — `/opensquad run uenf-security-audit`
   - Auditoria PHP, JS e WP Config em paralelo
   - Correção automática de issues Críticas/Altas

4. **CSS v2 — splitar `assets/dist/css/style.min.css`**
   - Criar entry points SCSS separados para mobile/desktop no `webpack.config.js`
   - O `style.min.css` ainda contém regras mobile dentro do bundle compilado
   - É o maior CSS do tema (11.6 KiB minificado)

5. **Expandir `tablet.css`**
   - v1 tem apenas 47 linhas (hero section)
   - Candidatos: `cct-responsive-breakpoints.css`, `new-menu.css` (991.98px range)

6. **`cct-responsive-breakpoints.css`** (939 linhas)
   - Arquivo dedicado a breakpoints Bootstrap — candidato ideal para split completo
   - Não foi tocado na v1 por não ser um dos 5 arquivos prioritários
