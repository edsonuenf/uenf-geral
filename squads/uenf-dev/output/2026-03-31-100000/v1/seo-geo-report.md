# SEO/GEO Report — uenf-dev run 2026-03-31

**Autor:** Pedro (SEO & GEO Specialist)
**Branch analisada:** `aparencia`
**Data:** 2026-03-31

---

## Status Geral

**SEM IMPACTO** — Nenhuma das mudanças da branch `aparencia` afeta negativamente o HTML público indexável. A reorganização do Customizer é puramente administrativa. Há um ponto de atenção de baixa severidade relacionado ao uso potencial de `uenf_get_random_image()` em templates de produção.

---

## 1. Impacto no HTML Público

**Resultado: Nenhum impacto.**

A reorganização do WordPress Customizer opera exclusivamente no contexto `customize_register` — escopo de admin, sem renderização no frontend. Verificado: `language_attributes()`, `<meta charset>`, `<meta viewport>`, `add_theme_support('title-tag')`, `add_theme_support('automatic-feed-links')` — todos presentes e intocados. Schema.org em `inc/seo.php`, OG tags e Twitter Cards também intactos. Estrutura de headings nos templates não foi alterada.

---

## 2. Risco: `uenf_get_random_image()` em Produção

**Resultado: Risco baixo — guard correto, mas dependência em templates externos não auditada.**

A função em `inc/template-tags.php` retorna `''` quando `WP_DEBUG = false`. Se algum template do plugin `uenf-templates` inserir esse retorno diretamente em `src=""` sem verificação, o resultado em produção seria `<img src="">` — causando request HTTP para a URL atual, potencial CLS e imagem inválida para crawlers. O risco não foi introduzido por esta branch, mas existe na arquitetura.

---

## 3. Estrutura de Navegação

**Resultado: Nenhum impacto.**

A remoção da seção duplicada `cct_menu_settings` eliminou redundância administrativa. O `register_nav_menus()` está intocado em `functions.php`. A renderização via `UENF_Menu_Component::display_menu()` no `header.php` não foi alterada. O grafo de links internos para crawlers permanece idêntico.

---

## 4. Editor de Blocos — Classes CSS

**Resultado: Nenhum impacto em produção.**

A remoção de `add_theme_support('editor-color-palette')` e `add_theme_support('editor-font-sizes')` foi substituída pela definição via `theme.json` (padrão WP 5.8+). As classes `has-{slug}-color` já salvas em posts existentes permanecem intactas no `post_content`. Crawlers e LLMs que processam o HTML público não são afetados.

---

## 5. wp_head/wp_footer e Feeds

**Resultado: Tudo correto e intocado.**

- `wp_head()` presente em `header.php` — com guard `function_exists`
- `wp_footer()` presente em `footer.php` — posição correta antes de `</body>`
- `wp_body_open()` presente em `header.php`
- `add_theme_support('automatic-feed-links')` mantido — feeds RSS ativos no `<head>`
- Hooks SEO ativos via `wp_head`: meta description, OG tags, Twitter Cards, Schema.org `BlogPosting`, canonical URL, meta robots, preconnect/dns-prefetch para Google Fonts e CDNs

---

## 6. Issues Identificadas

| Issue | Severidade | Recomendação |
|-------|-----------|--------------|
| `uenf_get_random_image()` retorna `""` — templates do plugin podem gerar `<img src="">` | Baixa | Auditar `uenf-templates`: verificar `if ( $img )` antes de usar em `src` |
| Schema.org usa `http://schema.org` (HTTP) em `inc/seo.php` | Baixa | Trocar para `https://schema.org` — padrão canônico atual |
| `cct_posted_by()` vazia — autoria ausente nos templates | Informacional | Sem impacto crítico; `get_the_author()` ainda alimenta Schema.org diretamente |

---

## 7. Recomendações

**R1 — Prioridade Alta (pré-existente, fora desta branch):** Auditar plugin `uenf-templates` — todo uso de `uenf_get_random_image()` deve ter fallback `if ( $img ) { ... }` antes de inserir em `src`.

**R2 — Prioridade Baixa:** Em `inc/seo.php`, alterar `'@context' => 'http://schema.org'` para `'@context' => 'https://schema.org'`.

**R3 — Monitoramento:** Confirmar que `theme.json` define a paleta de cores UENF explicitamente (não afeta HTML público, mas afeta consistência editorial).

**Veredicto: APROVADO** — Branch `aparencia` pode ser mergeada sem riscos SEO/GEO.
