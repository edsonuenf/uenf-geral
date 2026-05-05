# Relatório de Auditoria CSS — UENF Theme
**Agente:** Ana (CSS Auditor) | **Step:** 01-audit-css

---

## Resumo Executivo

| Métrica | Valor |
|---------|-------|
| Total de arquivos CSS | 38 |
| Total de arquivos SCSS | 7 |
| Total de linhas CSS (não-minificado) | ~16.400 |
| Arquivos com media queries | 24 |
| CSS render-blocking (sem media attribute) | **38/38 = 100%** |
| Media queries mobile-only | 47 blocos |
| Media queries tablet | 18 blocos |
| Media queries desktop | 22 blocos |

**Problema central:** TODOS os 38 CSS são carregados sem `media` attribute, portanto todos bloqueiam render em TODOS os devices — mesmo CSS que só se aplica a um breakpoint específico.

---

## Tabela: Arquivos × Buckets × Linhas

| Arquivo | Linhas | Common | Mobile | Tablet | Desktop | Notas |
|---------|--------|--------|--------|---------|---------|-------|
| `css/custom-fixes.css` | 508 | ~200 | ~280 | 0 | 0 | **Alta prioridade** — barra inferior, atalhos |
| `css/components/shortcuts.css` | 319 | ~240 | ~45 | 0 | 0 | **Alta prioridade** — painel atalhos |
| `css/components/footer.css` | 411 | ~240 | ~100 | ~30 | 0 | Alta prioridade |
| `css/components/header.css` | 184 | ~90 | ~80 | ~14 | 0 | Alta prioridade |
| `css/components/new-menu.css` | 711 | ~580 | ~40 | ~70 | ~21 | Menu responsivo |
| `css/cct-responsive-breakpoints.css` | 939 | ~177 | ~216 | ~144 | ~180 | Breakpoints Bootstrap |
| `css/cct-layout-system.css` | 772 | ~400 | ~15 | ~50 | ~307 | Grid/layout sistema |
| `css/components/search-retractable.css` | 558 | ~380 | ~120 | 0 | 0 | Search mobile |
| `css/components/form-validator.css` | 509 | ~360 | ~100 | 0 | ~49 | Formulários |
| `css/cct-patterns.css` | 1117 | ~900 | ~100 | ~60 | ~57 | Padrões visuais |
| `css/cct-animations.css` | 1085 | ~1040 | ~13 | 0 | 0 | Animações |
| `css/cct-dark-mode.css` | 806 | ~660 | ~38 | ~20 | 0 | Dark mode |
| `css/cct-gradients.css` | 836 | ~640 | ~23 | ~7 | ~26 | Gradientes |
| `css/cct-shadows.css` | 847 | ~660 | ~39 | ~7 | ~21 | Sombras |
| `css/cct-design-tokens.css` | 932 | ~870 | ~27 | 0 | 0 | Design tokens |
| `css/cct-icons.css` | 629 | ~540 | ~11 | ~7 | ~18 | Ícones |
| `css/layout/main.css` | 159 | ~90 | ~69 | 0 | 0 | Layout principal |
| `css/components/search.css` | 385 | ~350 | ~35 | 0 | 0 | Busca |
| `css/css-editor.css` | 667 | ~580 | ~52 | ~15 | ~20 | Editor CSS |
| `css/style.css` | 238 | ~180 | ~58 | 0 | 0 | Estilos base |
| `css/styles.css` | 263 | ~263 | 0 | 0 | 0 | 100% common |
| `css/patterns.css` | 271 | ~220 | ~51 | 0 | 0 | Padrões |
| `css/components/posts-list.css` | 107 | ~92 | ~15 | 0 | 0 | Lista de posts |
| `css/components/page-content.css` | 95 | ~72 | ~23 | 0 | 0 | Conteúdo páginas |
| `css/components/scrollbars.css` | 333 | ~305 | ~28 | 0 | 0 | Scrollbars |
| `css/hero-header-fix.css` | 129 | ~115 | ~14 | 0 | 0 | Hero/header fix |
| `css/cct-responsive-breakpoints.css` | 939 | ~177 | ~216 | ~144 | ~180 | Breakpoints |
| `css/customizer-layout-manager.css` | 336 | ~305 | ~31 | 0 | 0 | Customizer UI |
| `css/variables.css` | 31 | 31 | 0 | 0 | 0 | 100% common |
| `css/reset.css` | 240 | 240 | 0 | 0 | 0 | 100% common |
| `css/cct-patterns.css` | 1117 | ~900 | ~100 | ~60 | ~57 | já acima |
| `scss/style.scss` | 315 | ~280 | ~35 | 0 | 0 | Compilado → style.min.css |
| `scss/layout.scss` | 219 | ~180 | ~39 | 0 | 0 | Compilado → style.min.css |
| `scss/components/header.scss` | 248 | ~180 | ~68 | 0 | 0 | Compilado → style.min.css |
| `scss/components/footer.scss` | 78 | ~68 | ~10 | 0 | 0 | Compilado → style.min.css |
| `css/components/menu-enhancements.css` | 248 | ~248 | 0 | 0 | 0 | sem mobile queries |
| `css/components/menu-styles.css` | 200 | ~200 | 0 | 0 | 0 | sem mobile queries |
| `css/editor-style.css` | 73 | 73 | 0 | 0 | 0 | 100% common |

---

## Media Queries Únicas Encontradas

### Mobile (max-width)
```
@media (max-width: 575px)
@media (max-width: 575.98px)
@media (max-width: 576px)
@media screen and (max-width: 768px)
@media (max-width: 768px)         ← mais comum
@media (max-width: 767.98px)      ← Bootstrap canonical
@media (max-width: 480px)
@media (max-width: 360px)
@media (max-width: 782px)         ← WP Admin, ignorar
```

### Tablet (mixed range)
```
@media (min-width: 576px) and (max-width: 767px)
@media (min-width: 576px) and (max-width: 767.98px)
@media (min-width: 768px) and (max-width: 991px)
@media (min-width: 768px) and (max-width: 991.98px)
@media (min-width: 769px) and (max-width: 1024px)
@media (max-width: 991.98px)      ← tablet + mobile
@media (max-width: 992px)
```

### Desktop (min-width)
```
@media (min-width: 992px)
@media (min-width: 992px) and (max-width: 1199px)
@media (min-width: 992px) and (max-width: 1199.98px)
@media (min-width: 1200px)
@media (min-width: 1200px) and (max-width: 1399px)
@media (min-width: 1400px)
@media (min-width: 1921px)
```

### Outros (não quebrar)
```
@media (prefers-reduced-motion: reduce)
@media (prefers-contrast: high)
@media (prefers-color-scheme: dark)
@media (forced-colors: active)
@media print
@media screen and (-ms-high-contrast: active)
@media (prefers-reduced-data)
```

---

## Estratégia de Enqueue Atual

```php
// Todos esses são render-blocking sem media attribute:
wp_enqueue_style('cct-style', '.../assets/dist/css/style.min.css', ...);  // SCSS compilado
wp_enqueue_style('cct-styles-additional', '.../css/styles.css', ...);
wp_enqueue_style('cct-custom-fixes', '.../css/custom-fixes.css', ...);
wp_enqueue_style('cct-patterns', '.../css/patterns.css', ...);
// + 7 componentes no loop: new-menu, menu-enhancements, scrollbars, menu-styles,
//                          shortcuts, search-modern, search-retractable
```

**Nenhum** dos 38 arquivos usa `wp_style_add_data('handle', 'media', '...')`.

---

## Arquivos de Alta Prioridade para Split

Estes 4 arquivos têm alta proporção de regras mobile-only e são carregados universalmente:

| Arquivo | Linhas mobile | Impacto |
|---------|--------------|---------|
| `css/custom-fixes.css` | ~280 linhas | Barra inferior, atalhos, idiomas — SÓ mobile |
| `css/components/shortcuts.css` | ~45 linhas | Painel de atalhos mobile |
| `css/components/header.css` | ~80 linhas | Header responsivo |
| `css/components/footer.css` | ~100 linhas | Footer responsivo |

**Juntos: ~505 linhas de CSS mobile-only carregadas em desktop sem necessidade.**

---

## Webpack e SCSS

- Entry: `scss/style.scss` → `assets/dist/css/style.min.css`
- A SCSS tem ~140 linhas de mobile rules (header, layout, style) compiladas no bundle
- Splitar o bundle SCSS requer novos entry points — recomendado para v2
- Para v1: focar nos arquivos CSS separados (não compilados)

---

## Recomendações

### v1 (esta sprint)
1. Extrair blocos `@media (max-width: 767.98px)` dos 4 arquivos prioritários → `css/responsive/mobile.css`
2. Extrair blocos tablet de `footer.css`, `new-menu.css`, `header.css` → `css/responsive/tablet.css`
3. Extrair blocos desktop de `cct-layout-system.css`, `new-menu.css` → `css/responsive/desktop.css`
4. Enqueue com `wp_style_add_data(..., 'media', '(max-width:767.98px)')`

### v2 (próxima sprint)
5. Criar entry points SCSS separados (`scss/responsive/mobile.scss`, etc.)
6. Adicionar ao webpack.config.js como entries adicionais
7. `cct-responsive-breakpoints.css` — candidato para split completo (classes utilitárias responsivas)

---

## Estimativa de Impacto

| Device | CSS Atual Render-Blocking | CSS Após Split (render-blocking) | Economia |
|--------|--------------------------|----------------------------------|---------|
| Mobile | ~16.400 linhas | ~16.400 linhas (unchanged) | — |
| Tablet | ~16.400 linhas | ~16.100 linhas | ~300 linhas mobile |
| Desktop | ~16.400 linhas | ~15.895 linhas | ~505 linhas mobile |

> Nota: o FCP/LCP melhora quando arquivos non-matching deixam de bloquear o render thread.
> A redução real de payload é menor (browser baixa todos de qualquer forma),
> mas o **render-blocking** diminui significativamente.
