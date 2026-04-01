# Design System — Tema WordPress UENF Geral

**Versão:** 0.0.3 | **Stack:** WordPress + Bootstrap 5 + SCSS + FontAwesome 6  
**Pacote PHP:** `CCT_Theme` | **Branch ativo:** `aparencia`

---

## Sumário

1. [Paleta de Cores](#1-paleta-de-cores)
2. [Tipografia](#2-tipografia)
3. [Espaçamentos](#3-espaçamentos)
4. [Sombras e Elevação](#4-sombras-e-elevação)
5. [Bordas e Raios](#5-bordas-e-raios)
6. [Componentes](#6-componentes)
   - [Botões](#61-botões)
   - [Cards e Resultados de Busca](#62-cards-e-resultados-de-busca)
   - [Hero Section](#63-hero-section)
   - [Filtros de Categoria](#64-filtros-de-categoria)
   - [Navegação](#65-navegação)
   - [Breadcrumb](#66-breadcrumb)
   - [Formulário de Busca](#67-formulário-de-busca)
   - [Lista de Posts (index)](#68-lista-de-posts-index)
   - [Footer](#69-footer)
7. [Ícones](#7-ícones)
8. [Grid e Layout](#8-grid-e-layout)
9. [CSS Custom Properties](#9-css-custom-properties)
10. [Padrões de Nomenclatura](#10-padrões-de-nomenclatura)
11. [Paletas Predefinidas do Customizer](#11-paletas-predefinidas-do-customizer)
12. [Inconsistências Identificadas](#12-inconsistências-identificadas)

---

## 1. Paleta de Cores

### 1.1 Tokens do `theme.json` (WordPress Block Editor)

Expostos como `var(--wp--preset--color--<slug>)` no editor de blocos.

| Token (slug) | Nome | Hex | Uso |
|---|---|---|---|
| `primaria` | Cor Primária | `#1d3771` | Headings h1/h2, links, botões primários, navbar, fundo do header |
| `secundaria` | Cor Secundária | `#2c5aa0` | Hover de links, hover de botões, variações de profundidade do azul |
| `texto` | Cor do Texto | `#333333` | Corpo do texto, parágrafos |
| `acento` | Cor de Acento | `#0693e3` | Botões sobre imagens (Cover blocks), destaques interativos |
| `destaque` | Cor de Destaque | `#ff6600` | Destaques pontuais, CTAs de alta visibilidade |
| `fundo-claro` | Fundo Claro | `#f8f9fa` | Fundos de seções alternadas, cards |
| `fundo-escuro` | Fundo Escuro | `#212529` | Seções dark, rodapé escuro |

### 1.2 CSS Custom Properties do tema (`css/variables.css`)

```css
:root {
  --bs-uenf-blue:        #1D3771;     /* Azul principal UENF */
  --bs-uenf-blue-light:  #1D3770BF;  /* Azul com 75% opacidade */
  --title-h1:            var(--bs-uenf-blue-light);
  --primary-color:       var(--bs-uenf-blue);
  --primary-color-light: var(--bs-uenf-blue-light);
  --text-color:          #333;
  --orange-color:        #f5121b;     /* Vermelho-laranja (alertas) */
  --border-color:        rgba(255,255,255,0.2);
  --hover-bg:            #54689290;
  --active-bg:           #54689295;
  --white:               rgba(255,255,255,0.9);
  --white-solid:         #ffffff;
  --black:               #000000;
  --hero-bg:             #386A9430;  /* Azul translúcido do hero */
  --transition:          all 0.3s ease;
}
```

### 1.3 Variáveis SCSS (`scss/variables.scss`)

```scss
$primary-color:    #1d3771;
$secondary-color:  #2c5aa0;
$accent-color:     #0693e3;
$text-color:       #333;
$link-color:       #1B366F;
$link-hover:       #1d4b6e;
$font-family-base: 'Roboto', 'Open Sans', Arial, sans-serif;
$border-radius:    8px;
```

### 1.4 Escala de Cinzas (Design Tokens — `cct-design-tokens.css`)

| Token | Hex |
|---|---|
| `--cct-colors-gray-50` | `#f9fafb` |
| `--cct-colors-gray-100` | `#f3f4f6` |
| `--cct-colors-gray-200` | `#e5e7eb` |
| `--cct-colors-gray-300` | `#d1d5db` |
| `--cct-colors-gray-400` | `#9ca3af` |
| `--cct-colors-gray-500` | `#6b7280` |
| `--cct-colors-gray-600` | `#4b5563` |
| `--cct-colors-gray-700` | `#374151` |
| `--cct-colors-gray-800` | `#1f2937` |
| `--cct-colors-gray-900` | `#111827` |

### 1.5 Cores Semânticas (Design Tokens)

| Papel | Token | Hex |
|---|---|---|
| Sucesso | `--cct-colors-green-500` | `#22c55e` |
| Sucesso escuro | `--cct-colors-green-600` | `#16a34a` |
| Erro | `--cct-colors-red-500` | `#ef4444` |
| Erro escuro | `--cct-colors-red-600` | `#dc2626` |
| Aviso | `--cct-colors-yellow-500` | `#eab308` |
| Aviso escuro | `--cct-colors-yellow-600` | `#ca8a04` |
| Info | `--cct-colors-blue-500` | `#3b82f6` |

### 1.6 Paletas Predefinidas do Customizer

| Paleta | Primary | Secondary | Accent | Success | Warning | Danger |
|---|---|---|---|---|---|---|
| **Corporativo** (padrão) | `#1d3771` | `#2c5aa0` | `#3498db` | `#27ae60` | `#f39c12` | `#e74c3c` |
| **Criativo** | `#9b59b6` | `#e91e63` | `#ff5722` | `#4caf50` | `#ff9800` | `#f44336` |
| **Natureza** | `#2e7d32` | `#388e3c` | `#66bb6a` | `#4caf50` | `#ff8f00` | `#d32f2f` |
| **Minimalista** | `#424242` | `#616161` | `#757575` | `#66bb6a` | `#ffb74d` | `#ef5350` |
| **Oceano** | `#0277bd` | `#0288d1` | `#29b6f6` | `#26a69a` | `#ffa726` | `#ef5350` |
| **Pôr do Sol** | `#ff5722` | `#ff7043` | `#ff8a65` | `#66bb6a` | `#ffb74d` | `#e53935` |

---

## 2. Tipografia

### 2.1 Famílias de Fonte

| Slug | Família | CSS |
|---|---|---|
| `open-sans` | Open Sans | `'Open Sans', sans-serif` |
| `roboto` | Roboto | `'Roboto', sans-serif` |
| `montserrat` | Montserrat | `'Montserrat', sans-serif` |
| `lato` | Lato | `'Lato', sans-serif` |
| `poppins` | Poppins | `'Poppins', sans-serif` |
| `nunito` | Nunito | `'Nunito', sans-serif` |
| `pt-sans` | PT Sans | `'PT Sans', sans-serif` |
| `source-sans-pro` | Source Sans Pro | `'Source Sans Pro', sans-serif` |
| `playfair-display` | Playfair Display (serif) | `'Playfair Display', serif` |
| `merriweather` | Merriweather (serif) | `'Merriweather', serif` |
| `crimson-text` | Crimson Text (serif) | `'Crimson Text', serif` |
| `orbitron` | Orbitron (mono) | `'Orbitron', monospace` |

### 2.2 Escala de Tamanhos (`theme.json` — `--wp--preset--font-size--*`)

| Slug | Token WP | Tamanho | Uso |
|---|---|---|---|
| `pequeno` | `--wp--preset--font-size--pequeno` | `14px` | Metadados, badges, datas |
| `normal` | `--wp--preset--font-size--normal` | `16px` | Corpo do texto, botões |
| `medio` | `--wp--preset--font-size--medio` | `18px` | Citações, h4 |
| `grande` | `--wp--preset--font-size--grande` | `24px` | h3 |
| `muito-grande` | `--wp--preset--font-size--muito-grande` | `32px` | h2, títulos de seção |
| `gigante` | `--wp--preset--font-size--gigante` | `48px` | h1 |

### 2.3 Hierarquia de Headings

| Elemento | Família | Tamanho | Peso | Line-height | Cor |
|---|---|---|---|---|---|
| `h1` | Roboto | 48px | 600 | 1.2 | `#1d3771` |
| `h2` | Roboto | 32px | 600 | 1.3 | `#1d3771` |
| `h3` | Roboto | 24px | 600 | 1.4 | Herda |
| `h4` | Roboto | 18px | 500 | 1.4 | Herda |
| `h5` | Roboto | 16px | 500 | 1.5 | Herda |
| `h6` | Roboto | 14px | 500 | 1.5 | Herda — uppercase, `letter-spacing: 0.5px` |

### 2.4 Pesos de Fonte

| Token | Valor |
|---|---|
| `--cct-typography-font-weight-thin` | `100` |
| `--cct-typography-font-weight-light` | `300` |
| `--cct-typography-font-weight-normal` | `400` |
| `--cct-typography-font-weight-medium` | `500` |
| `--cct-typography-font-weight-semibold` | `600` |
| `--cct-typography-font-weight-bold` | `700` |

### 2.5 Line-Heights

| Token | Valor |
|---|---|
| `--cct-typography-line-height-tight` | `1.25` |
| `--cct-typography-line-height-snug` | `1.375` |
| `--cct-typography-line-height-normal` | `1.5` |
| `--cct-typography-line-height-relaxed` | `1.625` |
| `--cct-typography-line-height-loose` | `2` |
| Corpo (global) | `1.6` |
| `.entry-content` | `1.7` |

---

## 3. Espaçamentos

### 3.1 Escala do `theme.json`

| Slug | Token | Valor |
|---|---|---|
| `mini` | `--wp--preset--spacing--mini` | `4px` |
| `pequeno` | `--wp--preset--spacing--pequeno` | `8px` |
| `normal` | `--wp--preset--spacing--normal` | `16px` |
| `medio` | `--wp--preset--spacing--medio` | `24px` |
| `grande` | `--wp--preset--spacing--grande` | `32px` |
| `muito-grande` | `--wp--preset--spacing--muito-grande` | `48px` |
| `gigante` | `--wp--preset--spacing--gigante` | `64px` |

### 3.2 Escala CCT (Tailwind-like)

| Token | rem | px |
|---|---|---|
| `--cct-spacing-1` | `0.25rem` | 4px |
| `--cct-spacing-2` | `0.5rem` | 8px |
| `--cct-spacing-4` | `1rem` | 16px |
| `--cct-spacing-6` | `1.5rem` | 24px |
| `--cct-spacing-8` | `2rem` | 32px |
| `--cct-spacing-12` | `3rem` | 48px |
| `--cct-spacing-16` | `4rem` | 64px |

---

## 4. Sombras e Elevação

Sistema Material Design com 8 níveis (`css/cct-shadows.css`):

| Token | Uso |
|---|---|
| `--cct-elevation-0` | `none` |
| `--cct-elevation-1` | Cards em repouso |
| `--cct-elevation-2` | Dropdowns, tooltips |
| `--cct-elevation-4` | Modais pequenos |
| `--cct-elevation-8` | Drawers |
| `--cct-shadows-sm` | `0 1px 2px 0 rgba(0,0,0,0.05)` |
| `--cct-shadows-base` | `0 1px 3px 0 rgba(0,0,0,0.1)` |
| `--cct-shadows-md` | `0 4px 6px -1px rgba(0,0,0,0.1)` |
| `--cct-shadows-lg` | `0 10px 15px -3px rgba(0,0,0,0.1)` |

---

## 5. Bordas e Raios

| Token | Valor | Uso |
|---|---|---|
| `$border-radius` (SCSS) | `8px` | Padrão geral |
| `--cct-border-radius-base` | `4px` | Botões padrão |
| `--cct-border-radius-md` | `6px` | Cards de posts |
| `--cct-border-radius-lg` | `8px` | Containers |
| `--cct-border-radius-full` | `9999px` | Botões pill, back-to-top |

---

## 6. Componentes

### 6.1 Botões

#### Botão Bootstrap Outline Primary (padrão dos templates PHP)

Usado em `search.php`, `index.php`. Deve estar dentro de `.result-actions` para receber os estilos do Customizer.

```html
<div class="result-actions">
  <a href="/post-url" class="btn btn-outline-primary btn-sm read-more-btn">
    <i class="fas fa-arrow-right me-1" aria-hidden="true"></i>
    Ler Mais
  </a>
</div>
```

> ⚠️ **Importante:** O Customizer gera CSS com seletor `.result-actions .read-more-btn`. O botão DEVE estar dentro de `.result-actions` para receber os estilos corretos.

#### Botão Nova Busca (collapse trigger)

```html
<div class="search-actions">
  <button class="btn btn-outline-primary btn-sm new-search-btn"
          data-bs-toggle="collapse"
          data-bs-target="#newSearchForm">
    <i class="fas fa-search me-2" aria-hidden="true"></i>
    Nova Busca
  </button>
</div>
```

#### Botão Pill Outline (blocos WordPress Gutenberg)

```html
<div class="wp-block-button">
  <a class="wp-block-button__link" href="#">Saiba Mais</a>
</div>
```

Especificações: `border-radius: 50px`, `border: 1px solid #1d3771`, `background: transparent`, `padding: 10px 24px`. Hover inverte cores.

#### Botão Sólido (Cover blocks — sobre imagens)

```html
<div class="wp-block-cover">
  <div class="wp-block-button">
    <a class="wp-block-button__link" href="#">Ver Mais</a>
  </div>
</div>
```

Especificações: `background: #0693e3` (acento), `border: none`, `color: #fff`. Hover: `opacity: 0.9`.

#### Badge / Pill de Tipo de Conteúdo

```html
<span class="badge bg-primary me-2">Notícia</span>
```

---

### 6.2 Cards e Resultados de Busca

#### Card de Resultado de Busca (`search.php`)

```html
<article class="search-result-card">
  <div class="card h-100 shadow-sm">
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <div class="result-thumbnail">
            <a href="/post-url">
              <img src="..." class="img-fluid rounded" alt="Título">
            </a>
          </div>
        </div>
        <div class="col-md-9">
          <div class="result-content">
            <div class="result-meta mb-2">
              <span class="badge bg-primary me-2">Post</span>
              <span class="text-muted">
                <i class="fas fa-calendar me-1"></i>
                01/04/2026
              </span>
            </div>
            <h2 class="result-title">
              <a href="/post-url" class="text-decoration-none">
                Título com <mark class="cct-highlight">termo buscado</mark>
              </a>
            </h2>
            <div class="result-excerpt text-muted mb-3">
              Resumo com <mark class="cct-highlight">termo</mark>...
            </div>
            <div class="result-actions">
              <a href="/post-url" class="btn btn-outline-primary btn-sm read-more-btn">
                <i class="fas fa-arrow-right me-1"></i> Ler Mais
              </a>
              <button class="btn btn-outline-secondary btn-sm ms-2 copy-link-btn"
                      aria-label="Copiar link">
                <i class="fas fa-link"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</article>
```

#### Card de Post (`index.php` — `uenf-post-item`)

```html
<article class="uenf-post-item" data-cats="cat-5 cat-12">
  <span class="uenf-post-date">
    <i class="fas fa-calendar me-1" aria-hidden="true"></i>
    01 de abril de 2026
  </span>
  <h2 class="uenf-post-title">
    <a href="/post-url">Título do Post</a>
  </h2>
  <p class="uenf-post-excerpt">Resumo com até 60 caracteres...</p>
  <div class="result-actions">
    <a href="/post-url" class="btn btn-outline-primary btn-sm read-more-btn">
      <i class="fas fa-arrow-right me-1" aria-hidden="true"></i>Ler Mais
    </a>
  </div>
</article>
```

**Especificações CSS:**
- `background: #fff` | `border: 1px solid #e0e6f0` | `border-radius: 6px`
- `padding: 1.5rem` | `margin-bottom: 1.25rem`
- Data: `font-size: 0.78rem`, `color: #6c757d`, `text-transform: uppercase`
- Título: `font-size: 32px`, `font-weight: 700`, `line-height: 51px`, `color: #26557d`
- Excerpt: `font-size: 0.9rem`, `color: #495057`

---

### 6.3 Hero Section

```html
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center mb-3">
      <div class="col-lg-12">
        <div class="display-5 fw-bold text-uenf-blue mb-3 hero-title">
          Nome do Site UENF
        </div>
      </div>
    </div>
  </div>
</section>
```

**Especificações:**
- `background-color: var(--hero-bg)` → `#386A9430` (azul 19% opacidade)
- `width: 100vw` + `margin-left: calc(-50vw + 50%)` (full-bleed)
- `.hero-title`: `font-size: 2.5rem`, `letter-spacing: -1px`, `color: var(--bs-uenf-blue)`
- `.hero-title` padding: `40px 0 30px 0`
- Mobile (≤768px): `font-size: 2rem`, padding `30px 0 20px 0`

---

### 6.4 Filtros de Categoria

Aparece em `index.php` quando há pelo menos 1 categoria real com posts.

```html
<div class="uenf-posts-filter mb-4">
  <label for="uenf-cat-filter" class="uenf-filter-label me-2">Filtrar:</label>
  <select id="uenf-cat-filter" class="uenf-cat-select">
    <option value="all">Todos</option>
    <option value="cat-5">Notícias</option>
    <option value="cat-12">Eventos</option>
  </select>
</div>
```

**Especificações:**
- Label: `font-weight: 600`, `font-size: 0.9rem`, `color: var(--bs-uenf-blue)`
- Select: `border: 2px solid var(--bs-uenf-blue)`, `border-radius: 4px`
- Focus: `box-shadow: 0 0 0 3px rgba(29,55,113,.2)`
- Filtragem via JS nativo no DOM (sem AJAX/reload)
- Categorias excluídas automaticamente: slugs iniciando com `sem-categoria` e `uncategorized`

---

### 6.5 Navegação

```html
<header id="masthead" class="site-header">
  <!-- Barra superior: logo + idiomas + redes sociais -->
  <div class="bg-header-logo">
    <div class="container">
      <div class="row">
        <div class="col-md-4"><!-- Logo UENF --></div>
        <div class="col-md-8 header-media-grid">
          <div class="idiomas-bandeiras"><!-- Idiomas --></div>
          <div class="social-media"><!-- Redes sociais --></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Navbar com offcanvas -->
  <nav class="navbar navbar-dark navbar-uenf">
    <div class="container header-grid-container">
      <button class="navbar-toggler" type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#menuLateral">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-start" id="menuLateral">
        <div class="offcanvas-header">
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="new-menu"><!-- itens do menu --></ul>
        </div>
      </div>
    </div>
  </nav>
</header>
```

---

### 6.6 Breadcrumb

```html
<ol class="custom-breadcrumb">
  <li class="breadcrumb-item home">
    <a href="/" class="breadcrumb-link"><!-- ícone home SVG --></a>
  </li>
  <li class="breadcrumb-item">
    <a href="/categoria" class="breadcrumb-link">Categoria</a>
  </li>
  <li class="breadcrumb-item active">Página Atual</li>
</ol>
<section class="line-breadcrumb"></section>
```

**Especificações:**
- `font-size: 0.9rem` | Separador: `»` via `::after`
- Item ativo: `color: $primary-color`, `font-weight: 600`
- `.line-breadcrumb`: `border-bottom: 1px solid #e3eaf2`

---

### 6.7 Formulário de Busca

```html
<div class="search-container search-custom-uenf">
  <form role="search" method="get" class="custom-search-form search-custom-uenf" action="/">
    <input type="search"
           class="search-field search-custom-uenf"
           placeholder="Buscar..."
           name="s" />
    <button type="submit" class="search-submit search-custom-uenf" aria-label="Buscar">
      <i class="fas fa-search" aria-hidden="true"></i>
      <span class="search-text">Buscar</span>
    </button>
  </form>
</div>
```

**Highlight de termos:**
```html
<mark class="cct-highlight">termo buscado</mark>
```
Padrão: `background: #ededc7`, `font-weight: 700`. Configurável via Customizer.

---

### 6.8 Lista de Posts (index)

Estrutura completa de `index.php`:

```html
<main id="primary" class="site-main">
  <!-- Hero -->
  <section class="hero-section">...</section>

  <div class="container py-4">
    <!-- Filtro (condicional: só aparece se houver categorias reais) -->
    <div class="uenf-posts-filter mb-4">...</div>

    <!-- Lista -->
    <div id="uenf-posts-list">
      <article class="uenf-post-item" data-cats="cat-5">...</article>
    </div>

    <!-- Mensagem sem resultados (controlada por JS — só aparece ao filtrar) -->
    <p id="uenf-no-results" class="uenf-no-results-msg" style="display:none">
      Nenhum post encontrado nesta categoria.
    </p>

    <!-- Paginação -->
  </div>
</main>
```

---

### 6.9 Footer

```html
<footer class="footer">
  <div class="contact-info-container">
    <span class="contact-item"><strong>Email:</strong> contato@uenf.br</span>
  </div>
</footer>

<!-- Botão back-to-top -->
<a href="#" class="back-to-top" aria-label="Voltar ao topo">
  <i class="fas fa-chevron-up"></i>
</a>
```

**Back-to-top:** `position: fixed`, `right: 20px`, `bottom: 30px`, `50x50px`, `border-radius: 50%`, `background: var(--bs-uenf-blue)`, `z-index: 999999`.

---

## 7. Ícones

### 7.1 Font Awesome 6 (biblioteca principal)

| Prefixo | Conjunto | Uso |
|---|---|---|
| `fas` | Solid | Interface (busca, calendário, seta...) |
| `far` | Regular | Variantes outline |
| `fa-brands` / `fab` | Brands | Redes sociais |

**Exemplos de uso:**

```html
<i class="fas fa-search" aria-hidden="true"></i>           <!-- busca -->
<i class="fas fa-calendar me-1" aria-hidden="true"></i>    <!-- data do post -->
<i class="fas fa-arrow-right me-1" aria-hidden="true"></i> <!-- "Ler Mais" -->
<i class="fas fa-chevron-up"></i>                          <!-- back-to-top -->
<i class="fa-brands fa-facebook-f"></i>                    <!-- redes sociais -->
<i class="fa-brands fa-instagram"></i>
<i class="fa-brands fa-linkedin-in"></i>
<i class="fa-brands fa-youtube"></i>
<i class="fa-brands fa-whatsapp"></i>
```

> ⚠️ Sempre use `aria-hidden="true"` em ícones decorativos.

### 7.2 Sistema SVG CCT (`cct-icons.css`)

```html
<span class="cct-icon cct-icon-xs"><!-- SVG 12px --></span>
<span class="cct-icon cct-icon-sm"><!-- SVG 16px --></span>
<span class="cct-icon cct-icon-md"><!-- SVG 20px (padrão) --></span>
<span class="cct-icon cct-icon-lg"><!-- SVG 24px --></span>
<span class="cct-icon cct-icon-xl"><!-- SVG 32px --></span>
```

---

## 8. Grid e Layout

### 8.1 Containers

| Classe | Largura máxima | Uso |
|---|---|---|
| `.container` | `1200px` | Conteúdo principal (padrão) |
| `.container-fluid` | `100%` | Seções full-width |
| `.cct-container-narrow` | `800px` | Conteúdo editorial estreito |

### 8.2 Breakpoints

| Nome | Min | Classe Bootstrap |
|---|---|---|
| xs | 0px | (padrão) |
| sm | 576px | `col-sm-*` |
| md | 768px | `col-md-*` |
| lg | 992px | `col-lg-*` |
| xl | 1200px | `col-xl-*` |
| xxl | 1400px | `col-xxl-*` |

### 8.3 Larguras do Block Editor (`theme.json`)

- `contentSize`: `1200px` (padrão)
- `wideSize`: `1400px` (wide)

### 8.4 Padrão Full-Bleed (hero e seções especiais)

```css
.hero-section {
  width: 100vw;
  margin-left: calc(-50vw + 50%);
  margin-right: calc(-50vw + 50%);
}
```

---

## 9. CSS Custom Properties

| Namespace | Arquivo de origem | Descrição |
|---|---|---|
| `--bs-uenf-*` | `css/variables.css` | Cores e identidade UENF |
| `--wp--preset--color--*` | `theme.json` | Tokens de cor do Block Editor |
| `--wp--preset--font-size--*` | `theme.json` | Tamanhos tipográficos |
| `--wp--preset--spacing--*` | `theme.json` | Escala de espaçamentos |
| `--cct-colors-*` | `cct-design-tokens.css` | Paleta expandida |
| `--cct-typography-*` | `cct-design-tokens.css` | Tipografia detalhada |
| `--cct-spacing-*` | `cct-design-tokens.css` | Espaçamentos |
| `--cct-border-radius-*` | `cct-design-tokens.css` | Bordas arredondadas |
| `--cct-shadows-*` | `cct-design-tokens.css` | Sombras semânticas |
| `--cct-elevation-*` | `cct-shadows.css` | Sistema Material Elevation |
| `--cct-breakpoint-*` | `cct-layout-system.css` | Breakpoints responsivos |
| `--hero-bg` | `css/variables.css` | Fundo do hero |
| `--transition` | `css/variables.css` | `all 0.3s ease` |

---

## 10. Padrões de Nomenclatura

### Bootstrap 5 (framework base)

Classes mais usadas no tema:
```
Espaçamento:  py-4, py-5, mb-4, mb-3, me-2, ms-2, me-1
Texto:        text-muted, fw-bold, lead, display-5
Grid:         container, row, col-md-*, col-lg-*
Flexbox:      d-flex, align-items-center
Componentes:  card, card-body, h-100, shadow-sm, badge, collapse, offcanvas
```

### Prefixo `uenf-` (componentes específicos)

```
.uenf-posts-filter      — wrapper do filtro de categorias
.uenf-post-item         — artigo individual na lista
.uenf-post-date         — data do post
.uenf-post-title        — título do post
.uenf-post-excerpt      — resumo do post
.uenf-cat-select        — elemento select do filtro
.uenf-filter-label      — label do filtro
```

### Prefixo `cct-` (Custom CCT Theme)

```
.cct-icon-*             — ícones SVG em escala
.cct-container-*        — containers customizados
mark.cct-highlight      — destaque de termos de busca
```

### Classes de Identidade do Tema

```
.hero-section           — faixa hero full-width
.hero-title             — título dentro do hero
.line-breadcrumb        — separador após breadcrumb
.bg-header-logo         — barra superior do header
.navbar-uenf            — navbar customizada
.new-menu               — menu lateral offcanvas
.search-custom-uenf     — formulário de busca
.result-actions         — wrapper de botões de ação (obrigatório para estilos do Customizer)
.result-title           — título do resultado de busca
.result-excerpt         — resumo do resultado
.result-meta            — metadados (tipo, data)
.result-thumbnail       — wrapper da imagem
.read-more-btn          — botão "Ler Mais" (deve estar dentro de .result-actions)
.new-search-btn         — botão "Nova Busca"
.back-to-top            — botão flutuante retorno ao topo
.custom-breadcrumb      — lista de breadcrumb
.content-block          — bloco imagem + texto lado a lado
```

---

## 12. Inconsistências Identificadas

| # | Problema | Impacto | Localização |
|---|---|---|---|
| 1 | **Três escalas de spacing paralelas** (`--wp--preset--spacing--*`, `--cct-spacing-*` layout, `--cct-spacing-<n>` tokens) | Médio — inconsistência ao criar novos componentes | `theme.json`, `cct-layout-system.css`, `cct-design-tokens.css` |
| 2 | **Fonte base divergente** — `css/variables.css` declara `Ubuntu` em `--font-family`, mas `theme.json` define Open Sans e SCSS usa Roboto | Baixo — `--font-family` não é aplicado globalmente | `css/variables.css` |
| 3 | **Line-height fixo no título do post** — `.uenf-post-title` usa `line-height: 51px` (px absoluto) | Baixo — problemático em fontes grandes ou zoom | `index.php` inline CSS |
| 4 | **Regras CSS do hero duplicadas** — `css/hero-header-fix.css`, `css/components/header.css` e `scss/components/hero.scss` com valores ligeiramente diferentes | Médio — difícil de manter | Três arquivos |
| 5 | **Breakpoints do hero hardcoded** — `768px` e `1024px` não usam tokens `--cct-breakpoint-*` | Baixo | `css/hero-header-fix.css` |

---

*Documento gerado em 2026-04-01 | Análise por agente UI/UX sobre o tema uenf-geral v0.0.3*
