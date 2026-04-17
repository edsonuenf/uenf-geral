# Changelog

## [Unreleased] — Security Patch

### Segurança
- Corrigido: PHP Object Injection via `unserialize()` no editor CSS — substituído por `json_decode/wp_json_encode`
- Corrigido: Arbitrary File Write no editor CSS — rejeita conteúdo PHP e valida extensão do arquivo destino
- Corrigido: SQL Injection na busca avançada — queries agora usam `$wpdb->prepare()`
- Corrigido: XSS Stored no breadcrumb — adicionados `esc_url()` e `esc_html()` em todos os pontos de output
- Corrigido: XSS na documentação admin — output do Markdown filtrado com `wp_kses()`
- Corrigido: DOM XSS nos resultados de busca avançada — `createResultHTML` reescrito com API DOM segura
- Corrigido: DOM XSS via parâmetro `?s=` no destaque de termos de busca
- Corrigido: XSS em mensagens de erro da busca, notificações do reset-manager e extensions-manager
- Corrigido: CSS Injection no preview do Customizer — `textContent` em lugar de `.append('<style>')`
- Melhorado: Content-Security-Policy ativada e calibrada para Bootstrap CDN, FontAwesome e Google Fonts

### Arquivos Modificados
- `inc/design-editor/class-css-editor-base.php`
- `inc/class-advanced-search.php`
- `functions.php`
- `inc/security.php`
- `js/advanced-search.js`
- `js/admin/reset-manager.js`
- `js/extensions-manager.js`
- `js/customizer-search-preview.js`

---

## [0.2.0] - 2026-04-13 — branch `aparencia`

### Adicionado
- Barra inferior mobile com ícone Home, botão Atalhos e dropdown de idiomas
- Painel de atalhos rápidos funcional em mobile (Home, Contato, Telefone, Localização)
- Lupa de busca mobile ao lado do hamburguer, com barra expansível abaixo do header
- CSS independente para listagem de posts (`posts-list.css`), sem dependência de `search.css`
- Variável `--heading-color` para cor unificada de títulos h1-h6
- Suporte a `env(safe-area-inset-bottom)` para iOS (home indicator)
- Object cache pre-warming em `index.php` para evitar N+1 queries de categorias
- Debounce no resize do excerpt responsivo

### Corrigido
- Erro crítico PHP: tag `?>` ausente em `template-parts/content-search.php`
- Painel de atalhos não abria em mobile (`display: none !important` no `footer.php` + `right: -100%` nunca resetado no `shortcuts.css`)
- Dropdown de idiomas cortando na borda direita da tela (alterado para `right: 0`)
- Back-to-top sobrepondo a barra inferior (movido para `bottom: 56px`)
- Breakpoints inconsistentes (`768px` → `767.98px` alinhado com Bootstrap)
- Reset global `* { margin: 0; padding: 0 }` quebrando layout Bootstrap

### Melhorado
- Escape de output: `esc_html()` adicionado em `get_the_date()`, `get_the_title()`, `get_bloginfo('name')`
- Single post: hero image antes do header, data com ícone de calendário antes do título
- Footer: container 80%, gaps e paddings reduzidos pela metade
- Estilos inline removidos de `index.php` (movidos para `posts-list.css`)
- Logo mobile reduzida de 180px → 144px
- Altura do header mobile controlada por variável CSS (`--cct-header-height-mobile`)

### Known Limitations
- Ícone de engrenagem na barra inferior não está geometricamente centrado (posição depende do flexbox, não é `position: absolute`)
- Lint (stylelint/eslint) sem configuração — scripts existem no `package.json` mas `.stylelintrc` e `.eslintrc` não foram criados

---

## [0.1.0] - 2026-04-09

Release inicial da branch `aparencia`.
