# Relatório de Frontend — Rafael, Frontend Tester
**Run:** 2026-04-07-100559 | **Agente:** Rafael | **Step:** 7 — frontend-testing

---

## Sumário Executivo

Análise estática completa dos 6 arquivos modificados. Foram encontradas **2 issues críticas**, **4 médias** e **4 baixas**. A lógica JavaScript de filtro e truncagem está correta, mas com edge cases não tratados. O enfileiramento de assets tem dependência frágil. A principal ameaça de regressão é o **reset global de `.row`** em `hero-header-fix.css` que quebra o grid Bootstrap em todo o site.

---

## Resultados por Área

### CSS/SCSS Compilado

| Item | Status |
|---|---|
| `style.min.css` enfileirado com `filemtime` (anti-cache correto) | ✅ passou |
| `.hero-section` definida em `hero-header-fix.css` E em `css/components/header.css` — regras duplicadas | ❌ conflito |
| `.row { margin-left: 0 !important; margin-right: 0 !important }` em `hero-header-fix.css` — reset GLOBAL | ❌ crítico |
| `.container-fluid { padding: 0 !important }` em `hero-header-fix.css` — reset global | ⚠️ alerta |
| `header-media-grid`: `display: flex !important` no SCSS vs `display: grid` em `header.css` — conflito direto | ❌ crítico |
| `z-index` hierarquia: offcanvas (20000) > header fixo (10001) > rodapé (10000) | ✅ passou |
| `body padding-bottom: 40px` hard-coded para compensar rodapé fixo mobile | ⚠️ alerta |

### JavaScript — index.php

| Item | Status |
|---|---|
| `truncate(str, len)` — lógica base correta | ✅ passou |
| `truncate("")` → retorna `""` sem `...` | ✅ correto |
| `truncate(str, 0)` → retorna `"..."` | ⚠️ edge case sem guard |
| `truncate(null, 10)` → TypeError — sem guard para null/undefined | ❌ alerta |
| `getLimit()` — todos os 4 breakpoints retornam valor correto | ✅ passou |
| `getLimit()` usa `window.innerWidth` (inclui scrollbar em alguns browsers) | ⚠️ alerta |
| `resize` sem debounce — potencial jank em listas longas | ⚠️ alerta |
| Filtro "Todos" — exibe todos os posts | ✅ passou |
| Filtro categoria específica — oculta itens não relacionados | ✅ passou |
| Categoria sem posts — exibe `#uenf-no-results` | ✅ passou |
| Guard `if (!select) return` — protege ausência de elemento | ✅ passou |
| `<p data-excerpt>` vazio sem JS — excerpt invisível para screen readers | ⚠️ médio |

### Estados Interativos

| Item | Status |
|---|---|
| `.read-more-btn` hover (Bootstrap `btn-outline-primary`) | ✅ passou |
| `.uenf-cat-select` focus com `box-shadow` | ✅ passou |
| `.uenf-cat-select` `outline: none` — inacessível em alto contraste Windows | ⚠️ alerta |
| `.social-link:focus` com `outline: 2px solid currentColor` | ✅ passou |

### Responsividade

| Breakpoint | Limit Excerpt | Status |
|---|---|---|
| ≤576px | 80 chars | ✅ |
| ≤992px | 180 chars | ✅ |
| ≤1200px | 310 chars | ✅ |
| >1200px | 500 chars | ✅ |
| ≤768px header fixo + rodapé fixo | ✅ estrutura correta |

### Assets (functions.php)

| Item | Status |
|---|---|
| Bootstrap CDN com SRI integrity hash | ✅ passou |
| `cct-spacing-fixes` depende de `cct-new-menu-style` — handle registrado condicionalmente | ❌ dependência frágil |
| CSS inline no `header.php` duplica regras do `style.scss` | ⚠️ baixo |

### Acessibilidade

| Item | Status |
|---|---|
| `<label for="uenf-cat-filter">` associado ao select | ✅ passou |
| `aria-hidden="true"` nos ícones decorativos | ✅ passou |
| `hidden` nativo em `#uenf-no-results` | ✅ passou |
| Excerpt em `data-*` — invisível para AT sem JS | ⚠️ médio |

---

## Casos de Teste TDD

### Suite 1 — `truncate(str, len)`

| Caso | Entrada | Esperado | Resultado |
|---|---|---|---|
| TC-001 | `truncate("", 100)` | `""` | ✅ PASS |
| TC-002 | `truncate("Olá mundo", 100)` | `"Olá mundo..."` | ✅ PASS |
| TC-003 | `truncate("abcde", 5)` | `"abcde..."` | ✅ PASS |
| TC-004 | `truncate("Hello World Test", 5)` | `"Hello..."` | ✅ PASS |
| TC-005 | `truncate("Hello World", 6)` | `"Hello..."` (trimEnd remove espaço) | ✅ PASS |
| TC-006 | `truncate("ção", 2)` | pode cortar surrogate pairs de emoji | ⚠️ ALERTA |
| TC-007 | `truncate("texto", 0)` | deveria ser `""` — atual: `"..."` | ⚠️ ALERTA |
| TC-008 | `truncate(null, 10)` | guard esperado — atual: TypeError | ❌ FAIL |

### Suite 2 — `getLimit()`

| Caso | `innerWidth` | Esperado | Resultado |
|---|---|---|---|
| TC-101 | 576 | 80 | ✅ PASS |
| TC-102 | 577 | 180 | ✅ PASS |
| TC-103 | 992 | 180 | ✅ PASS |
| TC-104 | 993 | 310 | ✅ PASS |
| TC-105 | 1200 | 310 | ✅ PASS |
| TC-106 | 1201 | 500 | ✅ PASS |

### Suite 3 — Filtro de Categorias

| Caso | Ação | Esperado | Resultado |
|---|---|---|---|
| TC-201 | Selecionar "Todos" | Todos os posts visíveis, `#uenf-no-results` hidden | ✅ PASS |
| TC-202 | Selecionar cat-5 (com posts) | Posts da cat-5 visíveis, resto oculto | ✅ PASS |
| TC-203 | Selecionar cat sem posts no DOM | Todos ocultos, mensagem visível | ✅ PASS |
| TC-204 | Post com múltiplas cats — filtrar por uma delas | Post visível | ✅ PASS |
| TC-205 | Post sem categoria — filtrar qualquer cat | Post oculto | ✅ PASS |
| TC-206 | `#uenf-no-results` ausente do DOM | Sem erro (guard `if (noResults)`) | ✅ PASS |

### Suite 4 — Botão "Ler Mais"

| Caso | Verificação | Resultado |
|---|---|---|
| TC-301 | Presente em cada `.uenf-post-item` | ✅ PASS |
| TC-302 | `href` = permalink sanitizado com `esc_url` | ✅ PASS |
| TC-303 | Dentro de `.result-actions` | ✅ PASS |
| TC-304 | `aria-hidden="true"` no ícone | ✅ PASS |
| TC-305 | Texto "Ler Mais" visível (não é ícone-only) | ✅ PASS |

---

## Issues — Resumo por Severidade

### 🔴 Crítico

- **CRIT-001** — `hero-header-fix.css`: `.row { margin-left: 0 !important }` é reset global que destrói grids Bootstrap em todas as páginas. Deve ser escopado para `.hero-section .row`.
- **CRIT-002** — `header-media-grid` declarado como `flex` no SCSS compilado e `grid` no `css/components/header.css`. Conflito de modelo de layout com resultado visual imprevisível.

### 🟡 Médio

- **MED-001** — `functions.php`: `cct-spacing-fixes` depende de `cct-new-menu-style` que pode não estar registrado.
- **MED-002** — `index.php`: `applyExcerpts` no `resize` sem debounce.
- **MED-003** — `index.php`: `<p data-excerpt>` vazio sem JS — inacessível para AT e crawlers.
- **MED-004** — `hero-header-fix.css`: `.container-fluid { padding: 0 !important }` — reset global desnecessário.

### 🟢 Baixo

- **LOW-001** — CSS inline duplicado no `header.php`.
- **LOW-002** — Estilos de `index.php` deveriam estar em `scss/components/_posts-list.scss`.
- **LOW-003** — `truncate()` sem guard para `null`/`undefined`.
- **LOW-004** — `scss/style.scss`: `h6` duplicado na lista de seletores.

---

## Recomendações de Correção

1. **CRIT-001**: Escopar `.row` reset para `.hero-section .row` em `hero-header-fix.css`
2. **CRIT-002**: Unificar `header-media-grid` em um único modelo (flex ou grid)
3. **MED-002**: Adicionar debounce de 100ms no listener `resize`
4. **MED-003**: Pré-popular `<p>` com valor do servidor, sobrescrever via JS
5. **LOW-002**: Mover estilos inline para `scss/components/_posts-list.scss`
