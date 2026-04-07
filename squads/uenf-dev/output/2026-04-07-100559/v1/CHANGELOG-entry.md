# Changelog — Tema UENF

Todas as mudanças notáveis deste projeto são documentadas neste arquivo.  
Formato baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/).

---

## [Unreleased] — 2026-04-07

### Added

- Debounce de 100ms no listener de `resize` para otimização de performance (`MED-002`)

### Changed

- Layout de `header-media-grid` unificado em `flex`, eliminando conflito entre `flex` e `grid` (`CRIT-002`)
- Escopo do reset de `.row` restrito a `.hero-section .row` em `hero-header-fix.css`, evitando impacto global no layout (`CRIT-001`)
- Cache de termos habilitado via `update_object_term_cache` para eliminar queries N+1 em `wp_get_post_categories` (`BACK-004`)

### Fixed

- `echo get_bloginfo('name')` substituído por saída com `esc_html` em `index.php` (`BACK-003`)
- Chamadas de `error_log` protegidas por guard `WP_DEBUG` em `customizer-loader.php` (`BACK-005`)
- Saída de `$title` e `$excerpt` em `search.php` agora sanitizada com `wp_kses` (`VUL-001`)
- `get_permalink()` em atributos `onclick` agora escapado com `esc_js` (`VUL-002`)
- `get_bloginfo()` sem escape corrigido em 6 templates — saída protegida com `esc_html` (`VUL-004`)
- `the_title()` sem escape corrigido em `single.php` e `page.php` (`VUL-005`)

### Security

- XSS via `$title`/`$excerpt` sem sanitização em `search.php` — **corrigido** (`VUL-001`)
- XSS via `get_permalink()` sem `esc_js` em atributo inline — **corrigido** (`VUL-002`)
- Vazamento de informações de debug via `error_log` sem guarda — **corrigido** (`VUL-003`, `BACK-005`)
- Output não escapado de `get_bloginfo()` em múltiplos templates — **corrigido** (`VUL-004`)
- Output não escapado de `the_title()` em páginas de conteúdo — **corrigido** (`VUL-005`)

---

### Pendências desta sessão (não corrigidas)

- `BACK-001`: Duplicação de `customize_register` em `functions.php` — risco de instanciação dupla
- `BACK-002`: `ob_end_clean()` chamado antes de verificação de `ABSPATH` — ordem incorreta
- `MED-003`: `<p data-excerpt>` invisível sem JavaScript — impacto em acessibilidade
- Auditoria manual pendente: formulário em `front-page.php`, `inc/security.php`, cabeçalhos HTTP
