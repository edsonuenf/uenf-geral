# Design System Report — uenf-dev run 2026-03-31

**Autora:** Isabela (Designer & Design System)
**Branch analisada:** `aparencia`
**Data:** 2026-03-31

---

## Status Geral

**APROVADO COM RESSALVAS**

A branch resolve o principal problema de conflito identificado anteriormente. A remoção dos registros PHP legados é tecnicamente correta. Entretanto, as cores `#e8edf4` e `#c8d3e8` do CSS do Customizer não possuem tokens formais no design system.

---

## 1. Remoção do `add_theme_support` — Impacto no Editor

A remoção foi feita corretamente. O comentário em `functions.php` documenta a decisão:

```php
// Cores e tamanhos de fonte registrados via theme.json (padrão moderno WordPress 5.8+)
// Não usar add_theme_support('editor-color-palette') ou add_theme_support('editor-font-sizes')
// pois conflita com as definições em theme.json
```

O `theme.json` cobre plenamente as responsabilidades removidas:

| Funcionalidade removida (PHP) | Cobertura em `theme.json` | Status |
|-------------------------------|---------------------------|--------|
| `editor-color-palette` (slugs inglês: primary, secondary, text) | `settings.color.palette` com 6 slugs em português | Coberto — slugs diferentes |
| `editor-font-sizes` (slugs inglês: small, medium, large, huge) | `settings.typography.fontSizes` com 6 slugs em português | Coberto |

**⚠️ Ponto de atenção alto (deploy):** Conteúdo publicado com classes `.has-primary-color`, `.has-secondary-color`, `.has-small-font-size`, `.has-large-font-size` perderá o estilo visual — essas classes não serão mais geradas pelo WordPress. Varredura no banco de dados é necessária antes do deploy.

---

## 2. Cores no CSS do Customizer

Três cores hardcoded no CSS injetado via `customize_controls_enqueue_scripts`:

| Valor | Uso | Token existente? |
|-------|-----|-----------------|
| `#1d3771` | Texto dos cabeçalhos de grupo | **Sim** — `theme.json` slug `primaria`, `$primary-color` SCSS, `CCT_PRIMARY_COLOR` PHP |
| `#e8edf4` | Background dos cabeçalhos de grupo | **Não** — sem token formal em nenhuma camada |
| `#c8d3e8` | Bordas dos grupos e seções-filho | **Não** — sem token formal em nenhuma camada |

> Este CSS é **exclusivamente de interface admin** (painel Customizer), não afeta o front-end público. Impacto no design system do tema é baixo, mas há lacuna de documentação.

As cores `#e8edf4` e `#c8d3e8` são semanticamente coerentes — derivações de `#1d3771` com alta quantidade de branco — mas estão soltas sem nome ou registro formal.

---

## 3. Paleta de Cores — Consistência

| Slug `theme.json` | Valor | SCSS `variables.scss` | Constante PHP | Status |
|--------------------|-------|----------------------|---------------|--------|
| `primaria` | `#1d3771` | `$primary-color: #1d3771` | `CCT_PRIMARY_COLOR` | ✅ Alinhado |
| `secundaria` | `#2c5aa0` | `$secondary-color: #2c5aa0` | Ausente | ⚠️ Lacuna PHP |
| `texto` | `#333333` | `$text-color: #333` | `CCT_TEXT_COLOR` | ✅ Alinhado |
| `destaque` | `#ff6600` | Ausente | Ausente | ❌ Lacuna SCSS + PHP |
| `fundo-claro` | `#f8f9fa` | Ausente | Ausente | ❌ Lacuna SCSS + PHP |
| `fundo-escuro` | `#212529` | Ausente | Ausente | ❌ Lacuna SCSS + PHP |

Com a remoção do `add_theme_support`, os slugs em inglês (`primary`, `secondary`, `text`) deixam de existir. Os slugs ativos são exclusivamente os do `theme.json` em português — situação correta e desejável.

---

## 4. Tipografia — Consistência

`theme.json` define `settings.typography.fontSizes` com 6 entradas coerentes:

| Slug | Tamanho | Referenciado em `styles`? |
|------|---------|--------------------------|
| `pequeno` | 14px | Sim (h6) |
| `normal` | 16px | Sim (body, h5, button) |
| `medio` | 18px | Sim (h4, blockquote) |
| `grande` | 24px | Sim (h3) |
| `muito-grande` | 32px | Sim (h2) |
| `gigante` | 48px | Sim (h1) |

Todos os elementos em `styles.elements` usam `var(--wp--preset--font-size--[slug])` — sem hardcodes de tamanho no `theme.json`. Escala tipográfica internamente consistente.

A família de fontes padrão (`open-sans` para body, `roboto` para headings) está alinhada com as constantes PHP `CCT_DEFAULT_BODY_FONT` e `CCT_DEFAULT_HEADING_FONT`.

---

## 5. Issues Encontradas

| # | Issue | Severidade | Recomendação |
|---|-------|-----------|--------------|
| 1 | Conteúdo publicado com classes `.has-primary-color`, `.has-small-font-size` etc. perderá estilos após remoção do `add_theme_support` legado | **Alta** (deploy) | Varrer banco antes do deploy; CSS de compatibilidade temporário |
| 2 | `#e8edf4` sem token no design system (fundo dos grupos no Customizer) | Baixa | Criar `$color-ui-group-bg: #e8edf4` em `variables.scss` |
| 3 | `#c8d3e8` sem token no design system (bordas dos grupos no Customizer) | Baixa | Criar `$color-ui-border: #c8d3e8` em `variables.scss` |
| 4 | `scss/variables.scss` sem tokens para `destaque`, `fundo-claro`, `fundo-escuro` | Média | Adicionar variáveis SCSS correspondentes |
| 5 | Constantes PHP `CCT_*` incompletas — não cobrem `secundaria`, `destaque`, `fundo-claro`, `fundo-escuro` | Baixa | Adicionar constantes ou aceitar como lacuna documentada |
| 6 | `"text": "#ffffff"` hardcoded em `styles` do `theme.json` em vez de token nomeado | Muito Baixa | Avaliar adicionar slug `branco` à paleta |

---

## 6. Recomendações

**Prioridade Alta — antes do deploy:**
Varredura no banco de dados por classes com slugs em inglês. Se encontradas, criar CSS de compatibilidade temporário:
```css
/* Compatibilidade temporária — remover após migração de conteúdo */
.has-primary-color { color: #1d3771; }
.has-secondary-color { color: #2c5aa0; }
.has-small-font-size { font-size: 14px; }
.has-large-font-size { font-size: 24px; }
```

**Prioridade Média — design system:**
Completar `scss/variables.scss` com os tokens faltantes:
```scss
$color-destaque:     #ff6600;
$color-fundo-claro:  #f8f9fa;
$color-fundo-escuro: #212529;
// UI interna (Customizer) — derivadas da cor primária:
$color-ui-group-bg:  #e8edf4;
$color-ui-border:    #c8d3e8;
```
