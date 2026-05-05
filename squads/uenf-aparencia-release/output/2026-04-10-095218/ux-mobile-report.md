# UX/Mobile Review Report — branch `aparencia`
**Revisora:** Marina (UX/Mobile Reviewer)  
**Data:** 2026-04-10  
**Foco:** Responsividade mobile, UX, acessibilidade básica  
**Usuários-alvo:** Estudantes, pesquisadores e servidores da UENF

---

## Resumo Executivo

A branch traz melhorias significativas para o usuário mobile da UENF. A barra inferior com home + gear + idiomas está bem pensada. Os pontos de atenção principais são: posicionamento real do ícone de engrenagem, área de toque dos botões, e um breakpoint inconsistente em `posts-list.css`. Nada que impeça o merge, mas recomendo corrigir os dois primeiros antes de ir para produção.

---

## 1. Barra Inferior Mobile (header-media-grid)

### ✅ O que está correto
- Ícone **home** à esquerda com `margin-right: auto` — empurra corretamente o restante para a direita
- Cor branca nos ícones (`color: #fff !important`) — visível sobre fundo UENF blue
- `hover` com `rgba(255,255,255,0.8)` no home — microinteração sutil e adequada
- `env(safe-area-inset-bottom)` no padding — suporte a iOS com home indicator (iPhone X+)
- `aria-label` presente em home e gear — acessível para leitores de tela
- `aria-hidden="true"` nos ícones Font Awesome — correto para ícones decorativos com label no botão

### ⚠️ ISSUE 1 — Gear icon: não está verdadeiramente centrado (MÉDIA)

**Problema:**  
Com o CSS atual:
```
home-icon [margin-right: auto] → gear-btn → idiomas → social
```
O `margin-right: auto` no home empurra tudo para a direita como um grupo. O `gear-btn` fica imediatamente à esquerda dos idiomas, **não no centro real da barra**. Em telas com poucos ícones sociais (1-2), o gear pode parecer centralizado por acaso. Em telas com mais ícones, vai ficar claramente deslocado.

**Para verdadeiro centrado absoluto:**
```css
/* Opção A: posicionamento absoluto do gear */
.header-shortcut-btn {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}
.header-media-grid {
    position: relative; /* precisa de positioned parent */
}

/* Opção B: usar três grupos com flex */
/* [home] [gear no center] [idiomas+social] */
/* requer ajuste no markup do header.php */
```
**Impacto no usuário:** Estético, mas o ícone de atalhos é uma feature core para servidores — vale posicionar corretamente.

---

### ⚠️ ISSUE 2 — Área de toque abaixo do mínimo WCAG (MÉDIA)

**Problema:**  
`.header-home-link` e `.header-shortcut-btn` têm `font-size: 18px` mas sem dimensão mínima explícita. Em mobile, a área de toque recomendada é **44×44px** (WCAG 2.5.5 / Apple HIG / Google Material).

Atualmente o padding do home-link é apenas `padding-right: 15px` e o gear tem `padding: 0 10px` — provavelmente menos de 44px de altura.

**Sugestão:**
```css
.header-home-link,
.header-shortcut-btn {
    min-width: 44px;
    min-height: 44px;
}
```
**Impacto no usuário:** Usuários com motor skills reduzidas (comum em populações universitárias mais velhas) e em situações de movimento (estudantes caminhando) vão errar o toque.

---

## 2. Dropdown de Idiomas

### ✅ O que está correto
- `bottom: calc(100% + 8px)` + `right: 0` — abre para cima e alinha à direita, não corta na tela
- `role="menu"` no painel + `role="menuitem"` nos links — semântica ARIA adequada
- `aria-expanded` atualizado no toggle — correto
- Fecha ao clicar fora (document click handler) — comportamento esperado pelo usuário
- Visual: fundo `#1d3771`, radius `6px`, `box-shadow` direcional para cima — coerente com a barra

### ⚠️ ISSUE 3 — Dropdown sem `id` / falta `aria-controls` no trigger (BAIXA)

**Problema:**  
O painel do dropdown é criado via JavaScript (linha `panel.className = 'lang-dropdown-panel'`) mas sem `id`. O botão trigger não tem `aria-controls` apontando para o painel. Isso quebra a navegabilidade por teclado para usuários de screen reader.

**Sugestão no JS (header.php):**
```javascript
panel.id = 'lang-dropdown-panel-' + Date.now();
trigger.setAttribute('aria-controls', panel.id);
```
**Impacto:** Baixo em contexto real da UENF, mas é bom ter para conformidade básica.

---

## 3. Mobile Search Bar

### ✅ O que está correto
- Posicionada abaixo do header fixo (`top: var(--cct-header-height-mobile, 60px)`) — não sobrepõe navegação
- Animação `slideDown` suave (0.2s ease) — UI responsiva
- `hidden` attribute para toggle — semântico e não cria FOUC
- `focus()` no input quando barra abre — UX excelente, usuário pode digitar imediatamente
- Fecha ao clicar fora — comportamento esperado
- `aria-expanded` e `aria-controls` no botão toggle — correto
- Formulário com `border-radius` pill style — consistente com design do navbar desktop

### ✅ `background-color: #566694` na barra
O valor hex `#566694` equivale ao `#1D3770BF` (azul UENF com 75% opacidade sobre branco) — a cor visual é coerente com a navbar desktop. Boa atenção ao detalhe.

### Observação: search bar só aparece se `cct_extension_search_customizer_enabled` = true
Comportamento condicional está correto. Usuários sem a extensão não veem botão nem barra. Sem problema.

---

## 4. Single Post

### ✅ Hierarquia visual corrigida
Ordem atual: **hero image → data → título → conteúdo**  
Esta sequência é excelente para UX editorial:
- Imagem engaja primeiro (contexto visual)
- Data estabelece temporalidade
- Título confirma o assunto

### ✅ `img-fluid w-100` na hero image
Imagem ocupa 100% da largura do container em todos os breakpoints — correto para mobile.

### ✅ Ícone de calendário + data
`fa-regular fa-calendar-days` + data uppercase pequena (`0.78rem`) em cinza muted — hierarquia tipográfica adequada, não compete com o título.

### ✅ Espaçamento data→título
`entry-title { margin-bottom: -2px }` + `entry-meta { margin-top: 4px }` — data e título ficam visualmente "colados", reforçando que pertencem à mesma unidade. Escolha acertada de design.

---

## 5. Posts List (index.php)

### ✅ Independência de search.css
Classes renomeadas de `uenf-*` para `posts-list-*`, CSS movido para `posts-list.css` dedicado. Isso elimina o problema de estilos da página de busca vazando para a listagem de posts.

### ✅ Cards com hover interativo
`.posts-list-item:hover { box-shadow: ... }` — microinteração que indica clicabilidade.

### ✅ Excerpt responsivo com debounce
Debounce de 100ms no resize — não travará o scroll em mobile.

### ⚠️ ISSUE 4 — `posts-list.css`: breakpoint 768px inconsistente (BAIXA)

**Arquivo:** `css/components/posts-list.css`, linha 99  
```css
@media (max-width: 768px) { /* deveria ser 767.98px */
```
Todos os outros breakpoints da branch usam `767.98px` para alinhar com Bootstrap. Este arquivo usa `768px`, o que significa que em exatamente `768px` de largura o estilo mobile NÃO vai se aplicar (Bootstrap considera 768px como `md`, breakpoint desktop).

**Sugestão:**
```css
@media (max-width: 767.98px) {
```

---

## 6. Back-to-top

### ✅ Correto
- `z-index: 9999` — abaixo da barra inferior (10000) — não sobrepõe a navegação
- `bottom: 48px` — espaço suficiente acima da barra inferior (≈38px de altura)
- Breakpoint `767.98px` — correto

---

## 7. Footer

### ✅ Container 80%
Footer com `width: 80%` centralizado — visual mais limpo e respirado, adequado para instituição. Breakpoints específicos por device ajustam bem.

### ✅ Redução de gap/padding em 50%
`gap` e `padding` reduzidos à metade em todos os breakpoints — footer mais compacto sem perder legibilidade.

---

## 8. Acessibilidade Geral

| Elemento | aria-label | role | aria-expanded | Contraste |
|----------|-----------|------|--------------|----------|
| Botão home | ✅ | link | — | ✅ #fff sobre #1d3771 |
| Botão gear (header) | ✅ | button | — | ✅ #fff sobre #1d3771 |
| Botão lupa mobile | ✅ | button | ✅ | ✅ #fff sobre #1d3771 |
| Dropdown idiomas trigger | ✅ | button | ✅ | ✅ |
| Dropdown idiomas panel | — | menu | — | ✅ |
| Navbar toggler | ✅ | button | — | ✅ |

**Contraste geral:** Ícones brancos sobre UENF blue (`#1d3771`) têm ratio ≈ 8:1 — passa WCAG AA e AAA.

---

## Resumo das Issues

| # | Área | Severidade | Descrição |
|---|------|-----------|-----------|
| 1 | Barra inferior | Média | Gear não está geometricamente centrado na barra |
| 2 | Barra inferior | Média | Área de toque < 44px nos ícones home e gear |
| 3 | Dropdown idiomas | Baixa | Sem `id` no painel / sem `aria-controls` |
| 4 | Posts list | Baixa | Breakpoint `768px` em vez de `767.98px` no posts-list.css |

**Recomendação:** Issues 1 e 2 são candidatas a correção rápida antes do merge. Issues 3 e 4 podem ir num PR de polish posterior.

---

*Relatório gerado por Marina — uenf-aparencia-release pipeline — Step 2 de 9*
