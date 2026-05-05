# Plano de Arquitetura — CSS Split Responsivo
**Agente:** Rafael (Frontend Architect) | **Step:** 02-plan-split

---

## Objetivos

1. Reduzir CSS render-blocking por device extraindo regras responsivas exclusivas para bundles com `media` attribute
2. Manter backward compatibility total — sem quebrar nenhum estilo existente
3. Não alterar webpack.config.js (v1) — trabalhar nos arquivos CSS não-compilados

---

## Princípio do media attribute

```html
<!-- Render-blocking para TODOS os devices (atual): -->
<link rel="stylesheet" href="mobile.css">

<!-- Download em TODOS, mas não bloqueia render em desktop (após split): -->
<link rel="stylesheet" href="mobile.css" media="(max-width:767.98px)">
```

O browser **baixa** o arquivo de qualquer forma (para cache/pré-carregamento),
mas só **bloqueia** o render se a media query bate com o viewport atual.
Isso reduz o render-blocking path em desktop e tablet.

---

## Arquivos a Criar

### `css/responsive/mobile.css`
Media attribute: `(max-width:767.98px)`

Contém blocos `@media (max-width: 768px)` e menores extraídos de:

| Origem | Seletores-chave |
|--------|----------------|
| `custom-fixes.css` | `.back-to-top`, `.shortcut-panel container`, `.header-home-link`, `.header-shortcut-btn`, `.header-media-grid`, `body`, `.idiomas-bandeiras`, tipografia h1-h6 |
| `components/shortcuts.css` | `#uenf-shortcut-panel-container .shortcut-panel`, `.shortcut-icon` |
| `components/header.css` | `.hero-section`, `.social-media-links`, `.header-media-grid` |
| `components/footer.css` | `#colophon .footer-widgets` (≤768px, ≤576px, ≤360px) |
| `components/new-menu.css` | `.new-menu a`, `.submenu-toggle` (≤768px) |

**Estimativa:** ~540 linhas de CSS que hoje bloqueiam render em desktop/tablet.

### `css/responsive/tablet.css`
Media attribute: `(min-width:768px) and (max-width:991.98px)`

Contém blocos tablet-only extraídos de:

| Origem | Seletores-chave |
|--------|----------------|
| `components/footer.css` | `.footer-widgets` grid 2 colunas (≤992px) |
| `components/new-menu.css` | `.navbar-brand-top` (≤991.98px) |
| `components/header.css` | `.hero-section` (≤1024px) |

**Estimativa:** ~80 linhas.

### `css/responsive/desktop.css`
Media attribute: `(min-width:992px)`

Contém blocos desktop-only extraídos de:

| Origem | Seletores-chave |
|--------|----------------|
| `components/new-menu.css` | `.offcanvas.offcanvas-start` (≥1201px, ≥1921px) |

**Estimativa:** ~40 linhas.

---

## Arquivos a Modificar (remover blocos extraídos)

| Arquivo | Blocos a remover |
|---------|-----------------|
| `css/custom-fixes.css` | 6 blocos @media mobile |
| `css/components/shortcuts.css` | 2 blocos @media mobile |
| `css/components/header.css` | @media ≤768px, ≤480px (social media + hero) |
| `css/components/footer.css` | @media ≤992px, ≤768px, ≤576px, ≤360px |
| `css/components/new-menu.css` | @media ≤768px, ≤991.98px, ≥1201px, ≥1921px |

---

## Enqueue Condicional — functions.php

```php
// Adicionar APÓS o enqueue de 'cct-custom-fixes' (linha ~1995):

// --- CSS Responsivo Condicional ---
$responsive_base = get_template_directory() . '/css/responsive/';

$mobile_path = $responsive_base . 'mobile.css';
if (file_exists($mobile_path)) {
    wp_enqueue_style(
        'cct-responsive-mobile',
        CCT_THEME_URI . '/css/responsive/mobile.css',
        array('cct-custom-fixes'),
        filemtime($mobile_path)
    );
    wp_style_add_data('cct-responsive-mobile', 'media', '(max-width:767.98px)');
}

$tablet_path = $responsive_base . 'tablet.css';
if (file_exists($tablet_path)) {
    wp_enqueue_style(
        'cct-responsive-tablet',
        CCT_THEME_URI . '/css/responsive/tablet.css',
        array('cct-custom-fixes'),
        filemtime($tablet_path)
    );
    wp_style_add_data('cct-responsive-tablet', 'media', '(min-width:768px) and (max-width:991.98px)');
}

$desktop_path = $responsive_base . 'desktop.css';
if (file_exists($desktop_path)) {
    wp_enqueue_style(
        'cct-responsive-desktop',
        CCT_THEME_URI . '/css/responsive/desktop.css',
        array('cct-custom-fixes'),
        filemtime($desktop_path)
    );
    wp_style_add_data('cct-responsive-desktop', 'media', '(min-width:992px)');
}
```

**HTML gerado:**
```html
<link id='cct-responsive-mobile-css' rel='stylesheet'
  href='.../css/responsive/mobile.css?ver=...'
  media='(max-width:767.98px)'>
<link id='cct-responsive-tablet-css' rel='stylesheet'
  href='.../css/responsive/tablet.css?ver=...'
  media='(min-width:768px) and (max-width:991.98px)'>
<link id='cct-responsive-desktop-css' rel='stylesheet'
  href='.../css/responsive/desktop.css?ver=...'
  media='(min-width:992px)'>
```

---

## Diagrama de Arquitetura

```
ANTES (todos render-blocking):
┌──────────────────────────────────────────────────┐
│ custom-fixes.css → 508 linhas (common + mobile)  │ ← bloqueia desktop
│ shortcuts.css    → 319 linhas (common + mobile)  │ ← bloqueia desktop
│ footer.css       → 411 linhas (common + resp)    │ ← bloqueia desktop
│ header.css       → 184 linhas (common + resp)    │ ← bloqueia mobile
│ new-menu.css     → 711 linhas (common + resp)    │ ← bloqueia tudo
└──────────────────────────────────────────────────┘

DEPOIS:
┌──────────────────────────────────────────────────┐
│ custom-fixes.css → ~228 linhas (common only)     │ render-blocking todos
│ shortcuts.css    → ~274 linhas (common only)     │ render-blocking todos
│ footer.css       → ~230 linhas (common only)     │ render-blocking todos
│ header.css       → ~95 linhas (common only)      │ render-blocking todos
│ new-menu.css     → ~600 linhas (common only)     │ render-blocking todos
├──────────────────────────────────────────────────┤
│ mobile.css       → ~540 linhas media=mobile      │ NÃO bloqueia desktop
│ tablet.css       → ~80 linhas  media=tablet      │ NÃO bloqueia mobile/desktop
│ desktop.css      → ~40 linhas  media=desktop     │ NÃO bloqueia mobile/tablet
└──────────────────────────────────────────────────┘
```

---

## Regras de Extração

### Classificação de breakpoints
```
mobile  = max-width ≤ 767.98px (inclui 576px, 480px, 360px)
tablet  = min-width 768px AND max-width 991.98px (inclui 992px sem min)
desktop = min-width ≥ 992px
```

### O que NÃO extrair
- `@media print` → manter nos arquivos originais
- `@media (prefers-color-scheme: dark)` → manter
- `@media (prefers-reduced-motion: reduce)` → manter
- `@media (prefers-contrast: high)` → manter
- `@media (forced-colors: active)` → manter
- `@media (max-width: 782px)` em `customizer-social-reset.css` → WP Admin, manter
- Blocos dentro do SCSS compilado (`assets/dist/css/style.min.css`) → v2

### Critério de extração
Um bloco `@media` é extraído apenas se:
1. Pertence exclusivamente a um único bucket (mobile OU tablet OU desktop)
2. Está em um dos 5 arquivos prioritários listados acima
3. Não tem dependência de ordem com regras comuns do mesmo arquivo (verificar especificidade)

---

## Execução em Paralelo (Steps 4-5-6)

Os 3 agentes trabalham simultaneamente porque:
- **Thiago** (mobile-dev): opera em `max-width: 767.98px` e abaixo
- **Camila** (tablet-dev): opera em range `768px–991.98px`
- **Pedro** (desktop-dev): opera em `min-width: 992px` e acima

Não há conflito entre os 3 agents — os buckets são disjuntos.
Cada agent cria seu arquivo CSS e remove seus blocos dos arquivos-fonte.

---

## Estimativa de Impacto em Core Web Vitals

| Métrica | Antes | Depois | Melhoria |
|---------|-------|--------|---------|
| CSS render-blocking (desktop) | 16.400 linhas | ~15.860 linhas | -3,3% |
| CSS render-blocking (mobile) | 16.400 linhas | ~16.360 linhas | -0,2% |
| Arquivos com media attribute | 0/38 | 3/41 | +3 |
| FCP desktop (estimado) | baseline | -30ms a -80ms | melhora |
| Largest Contentful Paint | baseline | -20ms a -50ms | melhora |

> Nota: o ganho real depende do tamanho do CSS no critical path.
> Arquivos maiores (style.min.css) ficam para v2.
