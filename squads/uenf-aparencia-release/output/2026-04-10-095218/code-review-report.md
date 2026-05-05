# Code Review Report — branch `aparencia`
**Revisor:** Lucas (WordPress Code Reviewer)  
**Data:** 2026-04-10  
**Branch:** aparencia → main

---

## Resumo Executivo

A branch `aparencia` está em bom estado para merge. Os pontos críticos identificados são poucos e corrigíveis. A qualidade geral do código melhorou em relação à branch `main`, com remoção de estilos inline e melhora na sanitização.

---

## ✅ O que está OK

### PHP

| Arquivo | Status | Observação |
|---------|--------|-----------|
| `functions.php` | ✅ OK | Enqueue de `posts-list.css` usa `file_exists()` + `filemtime()` + conditional `is_home() || is_archive()` — correto |
| `single.php` | ✅ OK | Adicionado `esc_html()` em `get_bloginfo('name')` e `get_the_title()` — melhoria de segurança |
| `index.php` | ✅ OK | Adicionado `esc_html()` em `get_bloginfo('name')`. `esc_url()` e `esc_attr()` presentes nos lugares corretos |
| `index.php` | ✅ OK | Object cache pre-warming (`update_object_term_cache`) evita N+1 queries de categorias — boa prática de performance |
| `index.php` | ✅ OK | Resize event com debounce de 100ms — melhora de performance |
| `index.php` | ✅ OK | Remoção do bloco `<style>` inline — movido corretamente para `posts-list.css` |
| `template-parts/content-search.php` | ✅ OK | Sintaxe PHP corrigida — tag `?>` adicionada antes do HTML |
| `header.php` | ✅ OK | `esc_url(home_url('/'))` e `esc_url(get_template_directory_uri())` presentes |

### CSS/SCSS

| Arquivo | Status | Observação |
|---------|--------|-----------|
| `css/variables.css` | ✅ OK | `--heading-color` adicionado corretamente como variável CSS; fallback `#26557d` explícito |
| `css/style.css` | ✅ OK | h1-h6 usam `var(--heading-color)` em vez de `var(--primary-color)` — consistência melhorada |
| `css/components/footer.css` | ✅ OK | Container 80%, gap e padding reduzidos — mudanças de design coerentes |
| `scss/components/header.scss` | ✅ OK | Breakpoints corrigidos de `768px` → `767.98px` (alinhamento com Bootstrap) |
| `scss/style.scss` | ✅ OK | `padding-top` usa `var(--cct-header-height-mobile, 60px)` e `env(safe-area-inset-bottom)` para iOS |
| `scss/layout.scss` | ✅ OK | Regra `display:none !important` do shortcut panel mobile foi removida corretamente |
| `css/components/posts-list.css` | ✅ OK | Arquivo novo, bem estruturado, classes prefixadas com `posts-list-*` evitando colisão com `search.css` |
| `css/custom-fixes.css` | ✅ OK | Remoção do reset global `* { margin: 0; padding: 0 }` que quebrava layout Bootstrap — substituído por `box-sizing: border-box` apenas |
| `css/custom-fixes.css` | ✅ OK | `back-to-top` em mobile com `bottom: 48px` e `z-index: 9999` — correto para ficar atrás da barra inferior |

---

## ⚠️ Issues a Corrigir

### ISSUE 1 — `single.php`: `get_the_date()` sem escape (BAIXA)
**Arquivo:** `single.php`, linha com `<?php echo get_the_date(); ?>`  
**Problema:** `get_the_date()` não está sendo escapado com `esc_html()`. Embora a saída do WP core seja geralmente segura aqui (é uma data formatada), a boa prática é sempre escaping em output.  
**Sugestão:**
```php
// atual:
<?php echo get_the_date(); ?>

// recomendado:
<?php echo esc_html( get_the_date() ); ?>
```
**Impacto:** Baixo — WP core controla o formato, risco real é mínimo. Mas inconsistente com o `esc_html()` já adicionado ao título.

---

### ISSUE 2 — `index.php`: `the_title()` sem escape no `<a>` (BAIXA)
**Arquivo:** `index.php`, linha com `<a href="..."> <?php the_title(); ?></a>`  
**Problema:** `the_title()` faz echo diretamente sem escape. Deveria usar `the_title( '', '', true )` + `esc_html()` ou simplesmente `esc_html( get_the_title() )`.  
**Sugestão:**
```php
// atual:
<a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>

// recomendado:
<a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html( get_the_title() ); ?></a>
```
**Impacto:** Baixo — já tratado em `single.php`, mas falta aqui por consistência.

---

### ISSUE 3 — `css/custom-fixes.css`: `.shortcut-icon` z-index 10001 conflita com `.bg-header-logo` (MÉDIA)
**Arquivo:** `css/custom-fixes.css`  
**Problema:** O `.shortcut-icon` fixado na barra inferior tem `z-index: 10001 !important`, mas o `.bg-header-logo` em `header.scss` também usa z-index 10001. O `#header-shortcut-btn` (botão independente adicionado no header.php) tem z-index 10000 via `.header-media-grid`. A lógica de z-index está funcional, mas há duplicação entre o botão visível (`#header-shortcut-btn`) e o shortcut icon original (que deveria ficar oculto).  
**Observação:** A solução atual com `#header-shortcut-btn` como botão independente é pragmaticamente correta e evita conflitos com o CSS do shortcut panel. A regra do `.shortcut-icon` em `custom-fixes.css` pode ser considerada dead code se o botão original está oculto e apenas o `#header-shortcut-btn` é visível. Não é um bug, mas vale limpar.  
**Sugestão:** Se o `.shortcut-icon` original está oculto em mobile e apenas o `#header-shortcut-btn` é exibido, remover o bloco de override do `.shortcut-icon` em `custom-fixes.css` para reduzir CSS desnecessário.

---

### ISSUE 4 — `header.php`: Verificação `$search_extension_active ?? false` redundante (MUITO BAIXA)
**Arquivo:** `header.php`, linha 103  
**Problema:** A variável `$search_extension_active` já foi atribuída na linha 92 com `get_theme_mod(...)`. O operador `??` na linha 103 é redundante (o `get_theme_mod()` nunca retorna `null`).  
**Sugestão:** Usar apenas `if ($search_extension_active)` na linha 103.  
**Impacto:** Cosmético — sem consequência funcional.

---

### ISSUE 5 — `css/custom-fixes.css`: `!important` em `.header-media-grid justify-content` (MUITO BAIXA)
**Arquivo:** `css/custom-fixes.css`  
**Problema:** `.header-media-grid { justify-content: flex-start !important; }` no bloco mobile — o `!important` é desnecessário se o SCSS do `.header-media-grid` foi atualizado para não usar `!important`. No `header.scss`, a linha foi alterada de `display: flex !important` para `display: flex` (sem `!important`).  
**Impacto:** Cosmético.

---

## ❌ Nenhum Issue Crítico

Não foram encontrados:
- Blocos PHP abertos sem fechar (`<?php` sem `?>`)
- SQL injection / XSS via output não escapado em contextos de risco
- Enqueue de assets fora do hook `wp_enqueue_scripts`
- Funções duplicadas no `functions.php`
- Uso de `eval()`, `exec()` ou funções inseguras

---

## Checklist de posts-list.css no functions.php

✅ `posts-list.css` está enfileirado condicionalmente em `is_home() || is_archive()`  
✅ Usa `file_exists()` para verificar existência do arquivo  
✅ Usa `filemtime()` para cache-busting automático  
✅ Depende de `cct-custom-fixes` (ordem de carga correta)  
✅ Classes CSS no arquivo (`posts-list-*`) correspondem às classes usadas em `index.php`  

---

## Resumo das Issues

| # | Arquivo | Severidade | Descrição |
|---|---------|-----------|-----------|
| 1 | `single.php` | Baixa | `get_the_date()` sem `esc_html()` |
| 2 | `index.php` | Baixa | `the_title()` sem `esc_html()` |
| 3 | `custom-fixes.css` | Média | `.shortcut-icon` z-index pode ser dead code |
| 4 | `header.php` | Muito baixa | `?? false` redundante |
| 5 | `custom-fixes.css` | Muito baixa | `!important` desnecessário em `justify-content` |

**Recomendação:** Issues 1 e 2 podem ser corrigidos rapidamente antes do merge. Issues 3-5 são cosméticos e podem ser tratados em uma PR de cleanup posterior.

---

*Relatório gerado por Lucas — uenf-aparencia-release pipeline — Step 1 de 9*
