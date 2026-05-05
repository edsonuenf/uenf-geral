# Relatório de Segurança — Tema WordPress UENF

**Auditor:** André (Security Reviewer)
**Data:** 2026-04-07
**Escopo:** index.php, functions.php, header.php, footer.php, template-parts/content.php, inc/customizer/class-header-manager.php, class-cct-custom-controls.php, class-color-manager.php, customizer-loader.php, single.php, page.php, archive.php, search.php
**Metodologia:** OWASP WordPress Security Testing Guide + revisão manual de código

---

## Sumário Executivo

**Nível de Risco Geral: MÉDIO**

O tema apresenta base de segurança razoável: handlers AJAX verificam nonces corretamente, callbacks de sanitização do Customizer são adequados, e operações privilegiadas verificam `current_user_can()`. Não foram encontradas falhas de SQL Injection ou CSRF. Os problemas concretos identificados concentram-se em ausência de escape em outputs de texto — facilmente corrigíveis.

---

## Vulnerabilidades Encontradas

### VUL-001 — XSS: Destaque de termos sem escape em `search.php`
**Severidade: Médio** | `search.php` linhas 133–138 e 166–169

O código aplica `preg_replace()` para envolver termos de busca em `<mark>` e depois emite o resultado com `echo $title` / `echo $excerpt` sem nenhum escape:

```php
$title = get_the_title();
if ($highlight && $search_query) {
    $title = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark class="cct-highlight">$1</mark>', $title);
}
echo $title;   // ← SEM esc_html() / wp_kses()
```

`get_the_title()` retorna o título do banco sem escapar. Se um editor cadastrar um título com HTML (ex.: `"><img src=x onerror=alert(1)>`), o `echo $title` emite o HTML bruto na página de resultados. Risco reduzido para Médio pois requer acesso de editor/autor.

**Correção:**
```php
$allowed = array('mark' => array('class' => array()));
echo wp_kses($title, $allowed);
echo wp_kses($excerpt, $allowed);
```

---

### VUL-002 — XSS via `onclick` sem `esc_js()` em `search.php`
**Severidade: Baixo-Médio** | `search.php` linha 179

```php
<button onclick="navigator.clipboard.writeText('<?php echo get_permalink(); ?>')" ...>
```

`get_permalink()` inserido diretamente em atributo `onclick` sem `esc_url()` nem `esc_js()`. URLs com aspas simples (edge cases com custom slugs) podem encerrar a string JS prematuramente.

**Correção:**
```php
<button onclick="navigator.clipboard.writeText('<?php echo esc_js(get_permalink()); ?>')" ...>
```
Ou melhor: mover para `data-permalink="<?php echo esc_url(get_permalink()); ?>"` com handler JS separado.

---

### VUL-003 — `error_log()` sem guard `WP_DEBUG` em `customizer-loader.php`
**Severidade: Baixo** | `inc/customizer/customizer-loader.php` linhas 209–215

Seis chamadas a `error_log()` estão fora de qualquer condição `WP_DEBUG`:

```php
// SEM if (defined('WP_DEBUG') && WP_DEBUG)
error_log('CCT: Carregando ' . count($module_files) . ' módulos');
foreach ($module_files as $file) {
    error_log('CCT: Tentando carregar módulo: ' . $file);  // expõe path completo
    $result = $this->load_module($file, $wp_customize);
    error_log('CCT: Módulo ' . $file . ' - Resultado: ' . ($result ? 'SUCESSO' : 'FALHA'));
}
error_log('CCT: Carregamento de módulos concluído. Total de módulos carregados: ' . count($this->modules));
```

Em produção, expõem caminhos de servidor e enumeram todos os módulos carregados.

**Correção:** Envolver com `if (defined('WP_DEBUG') && WP_DEBUG) { ... }`.

---

### VUL-004 — `get_bloginfo('name')` sem `esc_html()` em 6 templates
**Severidade: Baixo** | `index.php:27`, `single.php:16`, `page.php:16`, `archive.php:17`, `search.php:25`, `footer.php:33`

```php
<?php echo get_bloginfo('name'); ?>
```

WordPress não escapa o nome do site automaticamente.

**Correção:**
```php
<?php echo esc_html(get_bloginfo('name')); ?>
```

---

### VUL-005 — `the_title()` sem escape em `single.php` e `page.php`
**Severidade: Baixo** | `single.php:39`, `page.php:37`

```php
<h1 class="entry-title"><?php the_title(); ?></h1>
```

**Correção:**
```php
<h1 class="entry-title"><?php echo esc_html(get_the_title()); ?></h1>
```

---

## Itens Verificados e Aprovados

| Item | Resultado |
|------|-----------|
| Nonces em formulários (`wp_nonce_field`) | APROVADO |
| Nonces em AJAX (`check_ajax_referer` / `wp_verify_nonce`) | APROVADO |
| `current_user_can()` em operações privilegiadas | APROVADO |
| Sanitização do Customizer — `class-header-manager.php` | APROVADO — `absint`, `sanitize_hex_color`, `rest_sanitize_boolean` |
| Sanitização do Customizer — `class-color-manager.php` | APROVADO — `sanitize_hex_color`, `sanitize_text_field`, `rest_sanitize_boolean` |
| SQL Injection | APROVADO — `$wpdb->prepare()` em todos os pontos críticos |
| Guard `ABSPATH` em arquivos PHP | APROVADO — todos os 49 arquivos verificados |
| `esc_url()` em links de posts | APROVADO — `index.php:65,74`, `content.php:13,56` |
| `esc_attr()` em data-attributes | APROVADO — `data-cats` e `data-excerpt` em `index.php` |
| DOM XSS via `dataset` (index.php JS) | APROVADO — usa `textContent`, nunca `innerHTML` |
| `DISALLOW_FILE_EDIT` | APROVADO — definido em `functions.php:113` |
| `the_content()` e `the_excerpt()` | APROVADO — WordPress aplica KSES automaticamente |

---

## Itens Pendentes de Verificação Manual

| Item | Motivo |
|------|--------|
| `front-page.php` — formulário de contato | Nonce presente, lógica de backend não auditada |
| `inc/security.php` | Pode conter filtros de cabeçalhos HTTP a confirmar |
| Cabeçalhos HTTP de segurança | Não confirmado se implementados via PHP ou servidor |
| `addons/page-visibility/` — REST API | Ocultação de páginas pode vazar via `/wp-json/wp/v2/posts` |

---

## Score de Segurança: 7,0 / 10

| Critério | Peso | Nota |
|----------|------|------|
| Proteção CSRF (nonces) | 20% | 9,5 |
| Sanitização de inputs | 20% | 8,0 |
| Escape de outputs HTML | 20% | 6,0 |
| Controle de acesso (capabilities) | 15% | 9,0 |
| SQL Injection | 10% | 9,5 |
| Exposição de informação | 10% | 6,0 |
| Boas práticas gerais | 5% | 8,0 |

**Score ponderado: 7,0** — dedução pela repetição de `get_bloginfo('name')` sem escape em 6 templates e pelos dois problemas no search.php.

---

## Plano de Correção Priorizado

| # | Vulnerabilidade | Arquivo | Esforço |
|---|-----------------|---------|---------||
| 1 | VUL-001: `echo $title` / `echo $excerpt` sem `wp_kses()` | `search.php:138,169` | 2 linhas |
| 2 | VUL-004: `echo get_bloginfo('name')` → `esc_html()` | 6 templates | 6 linhas |
| 3 | VUL-005: `the_title()` → `echo esc_html(get_the_title())` | `single.php:39`, `page.php:37` | 2 linhas |
| 4 | VUL-002: `get_permalink()` em onclick → `esc_js()` | `search.php:179` | 1 linha |
| 5 | VUL-003: `error_log()` sem guard → envolver com `if (WP_DEBUG)` | `customizer-loader.php:209–215` | 2 linhas |

Todas as correções são de baixo esforço e podem ser resolvidas em uma única sessão.
