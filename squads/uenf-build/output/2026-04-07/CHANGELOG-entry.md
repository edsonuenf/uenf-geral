# CHANGELOG — Entrada v0.1.0

## [0.1.0] - 2026-04-07

### Added
- Lista de posts com exibição responsiva: excerpt truncado por breakpoint (80 / 180 / 310 / 500 chars) via JavaScript
- Filtro de categorias na página inicial — select dinâmico com opção "Todos" e categorias reais (exclui "sem-categoria" / "uncategorized")
- Novo manager de cabeçalho via Customizer (`class-header-manager.php`) com suporte a altura, cor de fundo e comportamento sticky
- Design token `$accent-color: #0693e3` adicionado ao `variables.scss` e ao `theme.json` como cor de acento
- Screenshot oficial do tema adicionado (`screenshot.png`)
- Documentação `DESIGN-SYSTEM.md` com referência completa do sistema visual

### Changed
- Customizer reorganizado: todos os painéis consolidados sob `uenf_panel` com seções de grupo (`uenf_group_*`)
- Header responsivo com posição fixa no mobile + breadcrumbs + painel de atalhos
- `index.php` reescrito para usar `get_the_content()` (conteúdo completo) com truncagem JS, substituindo `get_the_excerpt()`
- `header-media-grid` unificado para `display: flex` em `header.css` e `header.scss` (conflito grid/flex resolvido)
- Título da hero pré-populado com `get_bloginfo('name')` na página inicial
- Patterns migrados para o plugin `uenf-templates` (removidos do tema)
- Fonte de tamanho dos labels do Customizer aumentada para 13px (era 11-12px)

### Fixed
- `hero-header-fix.css`: reset `.row { margin: 0 !important }` escopado para `.hero-section .row` — não quebra mais grids Bootstrap globais
- `template-parts/content.php`: parse error corrigido (tag `?>` ausente antes do HTML)
- `index.php`: `wp_get_post_categories()` agora usa `update_object_term_cache()` antes do loop, eliminando N+1 queries
- `index.php`: debounce de 100ms adicionado ao listener `resize` no JavaScript
- GitHub Theme URI atualizado em `style.css`

### Security
- `search.php`: `echo $title` e `echo $excerpt` substituídos por `wp_kses()` com tag `<mark>` permitida — previne XSS por editor
- `search.php`: `get_permalink()` em atributo `onclick` agora usa `esc_js()` — previne quebra de string JS em URLs com aspas simples
- `customizer-loader.php`: 6 chamadas `error_log()` envolvidas em guard `if (WP_DEBUG)` — não expõe mais paths de servidor em produção
- `get_bloginfo('name')` substituído por `esc_html(get_bloginfo('name'))` em 6 templates: `index.php`, `single.php`, `page.php`, `archive.php`, `footer.php`, `search.php`
- `the_title()` substituído por `echo esc_html(get_the_title())` em `single.php` e `page.php`
