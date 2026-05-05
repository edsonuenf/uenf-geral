# Design System Report — uenf-dev | Run 2026-03-30-115040
**Agente:** Isabela — Designer & Design System
**Data:** 2026-03-30

---

## Resumo

| Categoria | Status | Severidade |
|-----------|--------|------------|
| Paleta de cores — conflito de registros | 🔴 Crítico | Bloqueante |
| Valor de `secondary` divergente | 🔴 Crítico | Bloqueante |
| Tipografia — slugs duplicados | 🟡 Médio | — |
| Cor hardcoded `#0693e3` | 🟡 Médio | — |
| Tokens de espaçamento | ✅ OK | — |
| Tokens tipográficos | ✅ OK | — |

---

## 🔴 CRÍTICO 1 — Dois sistemas de paleta em conflito

O tema registra as cores em **dois lugares distintos, com slugs e valores diferentes**. Isso cria classes CSS duplicadas e inconsistentes no editor Gutenberg.

### Registro A — `theme.json` (sistema correto para WordPress moderno)
```json
{ "slug": "primaria",    "color": "#1d3771", "name": "Cor Primária"   }
{ "slug": "secundaria",  "color": "#2c5aa0", "name": "Cor Secundária" }
{ "slug": "texto",       "color": "#333333", "name": "Cor do Texto"   }
{ "slug": "destaque",    "color": "#ff6600", "name": "Cor de Destaque" }
{ "slug": "fundo-claro", "color": "#f8f9fa", "name": "Fundo Claro"    }
{ "slug": "fundo-escuro","color": "#212529", "name": "Fundo Escuro"   }
```
→ Gera classes: `.has-primaria-color`, `.has-secundaria-color`, `.has-texto-color`, etc.

### Registro B — `functions.php` via `add_theme_support('editor-color-palette')` (legado)
```php
['slug' => 'primary',   'color' => '#1d3771']
['slug' => 'secondary', 'color' => '#222a3b']  // ← valor DIFERENTE!
['slug' => 'text',      'color' => '#333333']
```
→ Gera classes: `.has-primary-color`, `.has-secondary-color`, `.has-text-color`

### Consequências do conflito
1. **O editor mostra cores duplicadas na paleta** — o usuário vê "Primária" e "Primary" como opções separadas
2. **`secondary` (#222a3b ≈ azul muito escuro) ≠ `secundaria` (#2c5aa0 ≈ azul médio)** — ao escolher "Secondary" no editor, a cor aplicada é diferente da esperada
3. **O SCSS usa `$secondary-color: #2c5aa0`** (alinhado com `theme.json`), mas o estilo `.wp-block-cover .has-secondary-color` aplica sobre a classe gerada pelo `functions.php` — potencial inconsistência de cor
4. **As classes `.has-primaria-color` adicionadas ao SCSS são corretas** para o sistema `theme.json`, mas coexistem com as classes inglesas do `functions.php`

### Resolução recomendada
**Remover o `add_theme_support('editor-color-palette')` do `functions.php`** — `theme.json` é o mecanismo correto para WordPress 5.8+. Manter apenas um dos dois:

```php
// REMOVER de functions.php:
add_theme_support('editor-color-palette', array(...));
```

Se houver conteúdo publicado com as classes em inglês (`.has-primary-color`, `.has-secondary-color`), fazer uma migration de conteúdo antes de remover.

---

## 🔴 CRÍTICO 2 — Valor de `$secondary-color` inconsistente

| Fonte | Slug | Valor | Classe gerada |
|-------|------|-------|---------------|
| `theme.json` | `secundaria` | `#2c5aa0` (azul médio) | `.has-secundaria-color` |
| `functions.php` | `secondary` | `#222a3b` (azul escuro) | `.has-secondary-color` |
| `scss/variables.scss` | `$secondary-color` | `#2c5aa0` | (usada em SCSS) |

O SCSS aponta para `#2c5aa0` (alinhado com `theme.json`), mas o PHP registra `secondary` como `#222a3b`. Se alguém aplica "Secondary color" via editor usando a paleta do PHP, recebe `#222a3b`; se a cor vem do SCSS/theme.json, recebe `#2c5aa0`.

**O design system não tem uma única fonte de verdade para a cor secundária.**

---

## 🟡 MÉDIO — Tipografia: slugs do `functions.php` em conflito com `theme.json`

`theme.json` usa slugs em português para tamanhos de fonte:
- `pequeno`, `normal`, `medio`, `grande`, `muito-grande`, `gigante`

`functions.php` registra via `add_theme_support('editor-font-sizes')`:
- `small`, `medium`, `large`, `huge`

Mesma duplicação que existe para cores — dois sistemas de tamanho de fonte rodando em paralelo.

---

## 🟡 MÉDIO — Cor `#0693e3` não pertence ao design system

Em `scss/style.scss`, o estilo de botão em Cover blocks usa:
```scss
background-color: #0693e3 !important; // duas ocorrências
```

Este azul (`#0693e3`) não existe em nenhum registro do design system:
- Não está em `theme.json`
- Não está em `functions.php`
- Não está em `scss/variables.scss`

É um azul de destaque (semelhante ao padrão WordPress/Gutenberg), mas sem nome ou token formal. Deve ser adicionado ao design system ou substituído por um token existente.

**Sugestão:** Adicionar em `variables.scss`:
```scss
$color-action: #0693e3; // Azul de ação — botões em superfícies escuras
```
E em `theme.json`:
```json
{ "slug": "acao", "color": "#0693e3", "name": "Cor de Ação" }
```

---

## ✅ Tokens em Conformidade

### Escala tipográfica (`theme.json`)
Bem definida com 6 tamanhos coerentes: `pequeno` (14px) → `gigante`. Os estilos de heading usam as variáveis CSS `--wp--preset--font-size--gigante` e `--wp--preset--font-size--muito-grande` corretamente.

### Paleta completa (`theme.json`)
6 cores registradas com valores semanticamente coerentes para uma identidade visual universitária. A identidade azul UENF está bem representada.

### Espaçamento (`theme.json`)
`spacingSizes` presentes. Não foram alterados nesta sessão — sem regressões.

### Família tipográfica
`$font-family-base: 'Roboto', 'Open Sans', Arial, sans-serif` no SCSS.
`theme.json` registra 11 famílias (`roboto`, `open-sans`, `montserrat`, `lato`, etc.) — extenso mas estruturado.

---

## Tabela de Tokens — Estado Atual

| Token | theme.json | functions.php | variables.scss | Alinhado? |
|-------|-----------|--------------|---------------|-----------|
| Cor primária | `primaria` #1d3771 | `primary` #1d3771 | `$primary-color` #1d3771 | ⚠️ Valor OK, slug diverge |
| Cor secundária | `secundaria` #2c5aa0 | `secondary` #222a3b | `$secondary-color` #2c5aa0 | 🔴 Valor e slug divergem |
| Cor de ação | ❌ ausente | ❌ ausente | ❌ ausente | 🔴 Token não existe |
| Cor do texto | `texto` #333333 | `text` #333333 | `$text-color` #333 | ⚠️ Valor OK, slug diverge |
| Cor do link | ❌ ausente | ❌ ausente | `$link-color` #1B366F | 🟡 Só no SCSS |
| Border radius | ❌ ausente | ❌ ausente | `$border-radius` 8px | 🟡 Só no SCSS |

---

## Recomendações

1. **Imediato:** Remover `add_theme_support('editor-color-palette')` e `add_theme_support('editor-font-sizes')` do `functions.php` — `theme.json` é a fonte de verdade
2. **Curto prazo:** Unificar o valor da cor secundária (decidir entre `#2c5aa0` e `#222a3b`)
3. **Curto prazo:** Criar token formal para `#0693e3` (cor de ação)
4. **Médio prazo:** Adicionar `$link-color` e `$border-radius` ao `theme.json` como tokens customizados
