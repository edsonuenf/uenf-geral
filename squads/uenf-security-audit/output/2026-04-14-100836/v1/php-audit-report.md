# Relatório de Auditoria PHP — UENF Theme
**Auditor:** Valentina (PHP Security Auditor)
**Data:** 2026-04-14
**Escopo:** functions.php, inc/*.php, template-parts/*.php, addons/**/*.php, components/**/*.php, *.php raiz

---

## Resumo Executivo

| Severidade | Quantidade |
|-----------|-----------|
| Crítico (CVSS ≥ 9.0) | 1 |
| Alto (CVSS ≥ 7.0) | 4 |
| Médio (CVSS ≥ 4.0) | 5 |
| Baixo (CVSS ≥ 0.1) | 3 |
| Informativo | 2 |
| **Total** | **15** |

---

## Tabela de Vulnerabilidades

| ID | Arquivo:Linha | Tipo | Severidade | CVSS |
|----|--------------|------|-----------|------|
| SEC-PHP-001 | `inc/design-editor/class-css-editor-base.php:275,416` | Deserialização Insegura (PHP Object Injection) | **Crítico** | 9.1 |
| SEC-PHP-002 | `functions.php:1094` | XSS Stored (admin) via Markdown | **Alto** | 7.2 |
| SEC-PHP-003 | `functions.php:2274,2283,2291,2300,2317,2319,2321` | XSS Stored — Breadcrumb sem escape | **Alto** | 7.6 |
| SEC-PHP-004 | `inc/class-advanced-search.php:147-154` | SQL Injection via `posts_search` sem `prepare()` | **Alto** | 7.4 |
| SEC-PHP-005 | `inc/optimization.php:106-120` | Queries DELETE sem `prepare()` + sem auth check | **Alto** | 7.0 |
| SEC-PHP-006 | `inc/template-functions.php:39-40` | XSS via Body Class Injection | **Médio** | 5.3 |
| SEC-PHP-007 | `functions.php:797,1376,1380,1384,1388` | Echo de inteiros sem escape em admin | **Médio** | 4.7 |
| SEC-PHP-008 | `functions.php:1441` | Echo de status_class/status_text sem escape | **Médio** | 4.7 |
| SEC-PHP-009 | `inc/security.php:219-223` | Log Injection via username/IP | **Médio** | 4.3 |
| SEC-PHP-010 | `addons/page-visibility/page-visibility.php:62-68` | Query SQL sem `prepare()` | **Médio** | 5.9 |
| SEC-PHP-011 | `inc/design-editor/class-css-editor-base.php:341-363` | Arbitrary File Write (conteúdo PHP em CSS) | **Alto** | 7.5 |
| SEC-PHP-013 | `inc/template-functions.php:40` | Customizer setting sem `sanitize_html_class` | **Baixo** | 3.1 |
| SEC-PHP-014 | `functions.php:32-108` | Guard clause ABSPATH duplicado/mal posicionado | **Baixo** | 2.0 |
| SEC-PHP-015 | `inc/class-advanced-search.php:34-37` | AJAX público sem rate limiting | Informativo | — |
| SEC-PHP-016 | `functions.php:1629` | `security.php` comentado (não carregado) | Informativo | — |

---

## Detalhes das Vulnerabilidades Críticas e Altas

### SEC-PHP-001 — Deserialização Insegura — CRÍTICO (CVSS 9.1)

**Localização:** `inc/design-editor/class-css-editor-base.php` linhas 275 e 416

**Código vulnerável:**
```php
// Linha 275 — get_backups()
$data = unserialize(file_get_contents($file));

// Linha 416 — ajax_restore_css()
$backup_data = unserialize(file_get_contents($backup_path));
```

**Código corrigido:**
```php
// Gravar (create_backup):
file_put_contents($backup_path, wp_json_encode($backup_data));

// Ler:
$data = json_decode(file_get_contents($file), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    return array(); // falha segura
}
```

**Justificativa:** `unserialize()` de conteúdo de arquivo no diretório `wp-uploads/css-backups/` é vulnerável a PHP Object Injection. Se um atacante conseguir escrever um payload serializado malicioso no diretório de backups (via outro vetor), a desserialização pode instanciar objetos PHP arbitrários, executando código via magic methods `__wakeup` ou `__destruct`. A substituição por `wp_json_encode/json_decode` elimina completamente a superfície de ataque.

---

### SEC-PHP-003 — XSS Stored no Breadcrumb — ALTO (CVSS 7.6)

**Localização:** `functions.php`, função `cct_custom_breadcrumb()`, linhas 2274–2321

**Código vulnerável:**
```php
// $clean_title é apenas trim() — ZERO escape HTML
echo '<a href="' . get_permalink($ancestor) . '" class="breadcrumb-link">'
    . $clean_title(get_the_title($ancestor)) . '</a>';

echo '<a href="' . get_category_link($category->term_id) . '" class="breadcrumb-link">'
    . $clean_title($category->name) . '</a>';

echo '<li class="breadcrumb-item active">' . $clean_title(get_the_title()) . '</li>';
```

**Código corrigido:**
```php
echo '<a href="' . esc_url(get_permalink($ancestor)) . '" class="breadcrumb-link">'
    . esc_html(get_the_title($ancestor)) . '</a>';

echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="breadcrumb-link">'
    . esc_html($category->name) . '</a>';

echo '<li class="breadcrumb-item active">' . esc_html(get_the_title()) . '</li>';
```

**Justificativa:** Editores e colaboradores podem criar posts com títulos ou categorias contendo HTML/JS malicioso. `$clean_title` é apenas `function($title) { return trim($title); }` — não realiza escape algum. O breadcrumb é exibido para todos os visitantes: XSS Stored de impacto público.

---

### SEC-PHP-004 — SQL Injection na Busca Avançada — ALTO (CVSS 7.4)

**Localização:** `inc/class-advanced-search.php`, método `modify_search_sql()`, linhas 147–154

**Código vulnerável:**
```php
$search_term = $wpdb->esc_like($search_term); // apenas escapa % e _ de LIKE
$search  = " AND (";
$search .= "({$wpdb->posts}.post_title LIKE '%{$search_term}%') OR ";   // interpolação direta
$search .= "({$wpdb->posts}.post_content LIKE '%{$search_term}%') OR ";
$search .= "({$wpdb->posts}.post_excerpt LIKE '%{$search_term}%')";
$search .= ")";
```

**Código corrigido:**
```php
$like = '%' . $wpdb->esc_like($search_term) . '%';
$search = $wpdb->prepare(
    " AND ({$wpdb->posts}.post_title LIKE %s
      OR {$wpdb->posts}.post_content LIKE %s
      OR {$wpdb->posts}.post_excerpt LIKE %s)",
    $like, $like, $like
);
```

**Justificativa:** `$wpdb->esc_like()` escapa apenas metacaracteres LIKE — **não** escapa aspas simples nem outros terminadores SQL. O padrão WordPress é inequívoco: toda query com valores externos exige `$wpdb->prepare()`.

---

### SEC-PHP-011 — Arbitrary File Write no CSS Editor — ALTO (CVSS 7.5)

**Localização:** `inc/design-editor/class-css-editor-base.php`, método `ajax_save_css()`, linhas 341–363

**Código vulnerável:**
```php
$content = wp_unslash($_POST['content']); // conteúdo bruto

// Validação trivialmente bypassável (conta chaves {}):
$validation = $this->validate_css($content);

// Grava no disco sem verificar presença de PHP:
$result = file_put_contents($file_info['path'], $content);
```

**Código corrigido:**
```php
$content = wp_unslash($_POST['content'] ?? '');

// Verificar que não há tags PHP (defesa em profundidade)
if (preg_match('/<\?(?:php)?/i', $content)) {
    wp_send_json_error('Conteúdo PHP não permitido em arquivos CSS');
    return;
}

// Verificar extensão do arquivo destino
if (pathinfo($file_info['path'], PATHINFO_EXTENSION) !== 'css') {
    wp_send_json_error('Tipo de arquivo não permitido');
    return;
}
```

---

### SEC-PHP-002 — XSS via Markdown sem Escape — ALTO (CVSS 7.2)

**Localização:** `functions.php:1094`

**Código vulnerável:**
```php
// $docs_content lido de .md e transformado por preg_replace sem sanitização:
$docs_content = preg_replace('/^# (.+)$/m', '<h1 class="docs-h1">$1</h1>', $docs_content);
// ...
echo $docs_content; // SEM esc_html() ou wp_kses()
```

**Código corrigido:**
```php
$allowed_tags = array(
    'h1' => array('class' => array()), 'h2' => array('class' => array()),
    'h3' => array('class' => array()), 'h4' => array('class' => array()),
    'strong' => array('class' => array()), 'em' => array('class' => array()),
    'code' => array('class' => array()), 'ul' => array('class' => array()),
    'li' => array('class' => array()), 'blockquote' => array('class' => array()),
    'br' => array(),
);
echo wp_kses($docs_content, $allowed_tags);
```

---

## Vulnerabilidades Médias (resumo)

**SEC-PHP-006** — `$classes[] = 'header-layout-' . $header_layout` sem `sanitize_html_class()` (`inc/template-functions.php:40`).
Fix: `$classes[] = 'header-layout-' . sanitize_html_class(get_theme_mod('header_layout', 'default'));`

**SEC-PHP-007** — `echo $total_count` / `echo $active_count` sem intcast em admin (`functions.php:1376-1388`).
Fix: `echo absint($total_count);`

**SEC-PHP-008** — `echo '<span class="' . $status_class . '">' . $status_text . '</span>'` sem escape (`functions.php:1441`).
Fix: usar `esc_attr()` e `esc_html()`.

**SEC-PHP-009** — Log injection: `$username` não sanitizado no `error_log()` de `cct_login_failed()` (`inc/security.php:219`).
Fix: `sanitize_user($username)` e `FILTER_VALIDATE_IP` para o IP.

**SEC-PHP-010** — Query raw sem `$wpdb->prepare()` em `modify_menu_query()` (`addons/page-visibility/page-visibility.php:62-68`). Valores são literais hardcoded — risco prático baixo mas viola WPCS.

---

## Prioridade de Correção

**Correção Imediata (antes do próximo deploy):**
1. SEC-PHP-001 — Substituir `unserialize()` por `json_decode()` no editor CSS
2. SEC-PHP-003 — Adicionar `esc_url()` e `esc_html()` em todos os pontos do breadcrumb
3. SEC-PHP-004 — Envolver SQL da busca avançada com `$wpdb->prepare()`
4. SEC-PHP-011 — Rejeitar conteúdo PHP (`<?php`) no CSS editor

**Próximo sprint:**
5. SEC-PHP-002 — `wp_kses()` no Markdown da documentação
6. SEC-PHP-006 — `sanitize_html_class()` no body class filter
7. SEC-PHP-009 — Sanitizar inputs no log de login

**Backlog:**
8. SEC-PHP-005, SEC-PHP-007, SEC-PHP-008, SEC-PHP-010, SEC-PHP-013, SEC-PHP-014

---

## Arquivos Auditados Sem Vulnerabilidades

`header.php`, `footer.php`, `searchform.php`, `single.php`, `page.php`, `archive.php`, `search.php`, `404.php`, `sidebar.php`, `template-parts/content.php`, `template-parts/content-page.php`, `template-parts/content-none.php`, `template-parts/content-search.php`, `inc/seo.php`, `inc/template-tags.php`, `inc/class-theme-reset-manager.php` (nonces + capabilities corretos), `inc/class-form-validator.php`, `inc/customizer/*.php` (sanitize_callbacks presentes), `inc/design-editor/templates/editor-page.php` (esc_* corretos, JSON para JS), `components/menu/class-uenf-menu.php`, `components/menu/templates/menu-template.php`, `inc/extensions/class-extension-manager.php`.
