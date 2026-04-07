# Relatório de Acessibilidade & Performance — theme-review
**Data:** 2026-03-30
**Branch:** modelos-uenf
**Executado por:** Accessibility & Performance Tester (Opensquad)
**Padrão de referência:** WCAG 2.1 AA

---

## Resumo

| Categoria | Status | Issues |
|-----------|--------|--------|
| Contraste de cores | ⚠️ Verificação necessária | 1 item crítico |
| Navegação por teclado | ✅ Adequado | Observações menores |
| Movimento e animações | ⚠️ Pendência | `prefers-reduced-motion` ausente |
| Semântica HTML | ✅ Existente | Boas práticas já aplicadas |
| Performance de assets | ✅ Melhorado | Refatoração positiva |
| Slugs de cor WordPress | 🔴 BUG CRÍTICO | Classes CSS inaplicáveis |

---

## 🔴 BUG CRÍTICO: Slugs de cor incompatíveis com WordPress

### Problema
Em `functions.php`, as cores são registradas com slugs em inglês:
```php
array( 'slug' => 'primary',   'color' => '#1d3771' ),
array( 'slug' => 'secondary', 'color' => '#222a3b' ),
```

O WordPress gera automaticamente as classes CSS:
- `.has-primary-color` / `.has-primary-background-color`
- `.has-secondary-color` / `.has-secondary-background-color`

Porém, em `scss/style.scss`, as novas classes utilitárias adicionadas usam slugs em **português**:
```scss
.has-primaria-color { color: variables.$primary-color !important; }
.has-secundaria-color { color: variables.$secondary-color !important; }
```

**Essas classes nunca serão aplicadas pelo WordPress** — o editor Gutenberg aplica `.has-primary-color` e `.has-secondary-color`, não `.has-primaria-color` e `.has-secundaria-color`.

### Impacto
- Qualquer bloco com cor "Primary" ou "Secondary" selecionada no editor **não terá o estilo aplicado**
- Pode causar confusão de manutenção: o CSS existe mas nunca é usado
- O `.wp-block-cover .has-secondary-color` (linha 277 do style.scss) usa a classe correta — o que cria comportamento assimétrico

### Correção recomendada
Substituir as classes adicionadas por:
```scss
.has-primary-color {
  color: variables.$primary-color !important;
}
.has-secondary-color {
  color: variables.$secondary-color !important;
}
```

---

## Contraste de Cores (WCAG 2.1 — Critério 1.4.3)

### Botões padrão (outline pill)
- **Fundo:** transparente / branco (`#fff`)
- **Cor do texto/borda:** `#1d3771` (azul UENF primário)
- **Contraste estimado:** ~9.8:1 ✅ (passa AA e AAA)

### Botões em Cover blocks
- **Fundo:** `#0693e3` (azul claro hardcoded)
- **Cor do texto:** `#fff`
- **Contraste estimado:** ~3.2:1 ⚠️
  - Para texto normal (≥18px bold ou ≥24px): passa AA (mínimo 3:1)
  - Para texto pequeno (<18px ou não-bold): **não passa AA** (mínimo 4.5:1)
- **Recomendação:** Verificar o tamanho real do texto nos botões de Cover blocks. Se for menor que 18px bold, aumentar o contraste ou escurecer a cor de fundo para ~`#0277c8`.

### `.wp-block-cover .has-secondary-color` (elemento "Próximo Evento")
- Mesmo fundo `#0693e3` — mesma análise de contraste acima.

---

## Navegação por Teclado (WCAG 2.1 — Critério 2.1.1)

### Botões
```scss
&:focus {
  background-color: variables.$primary-color !important;
  color: #fff !important;
}
```
✅ Estado `:focus` definido nos botões — usuários de teclado conseguem identificar o foco.

⚠️ **Observação:** O estado `:focus` é idêntico ao `:hover`. Para melhor distinguibilidade, considere adicionar `:focus-visible` com `outline` explícito, conforme WCAG 2.2 (Critério 2.4.11 — Focus Appearance):
```scss
&:focus-visible {
  outline: 3px solid variables.$primary-color;
  outline-offset: 2px;
}
```

### Cover block — `.has-secondary-color`
⚠️ Este elemento não tem estados `:hover` ou `:focus` definidos (é tratado visualmente como botão mas semanticamente pode ser um heading). Verificar se é interativo (clicável) — se sim, deve ter foco adequado.

---

## Animações e Movimento (WCAG 2.1 — Critério 2.3.3)

### Problema
```scss
transition: all 0.3s ease;  // em .wp-block-button__link
transition: all 0.3s ease;  // em footer links
transition: color 0.2s ease; // em layout.scss
```

Nenhum dos arquivos SCSS implementa `@media (prefers-reduced-motion: reduce)` para desabilitar transições para usuários que configuram "reduzir movimento" no SO.

**Recomendação:** Adicionar em `style.scss` ou em arquivo global:
```scss
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

---

## Semântica e ARIA (WCAG 2.1 — Critérios 1.3.1, 4.1.2)

### Existente e correto ✅
- `screen-reader-text` usado em links de comentário e edição (template-tags.php)
- `aria-hidden="true"` + `tabindex="-1"` na thumbnail de post (não-interativo corretamente oculto)
- `alt` em imagens via `the_title_attribute()` — semântico

### `uenf_get_random_image()` — placehold.co
As imagens geradas por `placehold.co` incluem texto na imagem (`?text=UENF+Image`). Quando usadas em `<img>`, o atributo `alt` deverá descrever o conteúdo real (não "UENF Image"). Verificar como os padrões que usam essa função implementam o atributo `alt`.

---

## Performance de Assets

### Positivo ✅
- Uso de `style.min.css` (CSS minificado) em produção — correto
- Cache-busting via `filemtime()` — evita stale cache
- Volume nomeado `wp_uploads` no Docker — uploads não são recriados a cada rebuild

### Observação
- `transition: all 0.3s ease` nos botões causa recálculo de layout em propriedades não compostas. Preferir `transition: background-color 0.3s ease, color 0.3s ease` para performance de renderização (evita reflows desnecessários).

---

## Lista de Issues

| # | Critério WCAG | Arquivo | Severidade | Tipo |
|---|---------------|---------|------------|------|
| A1 | N/A (Bug WP) | `scss/style.scss` | 🔴 Crítico | Classes `.has-primaria/secundaria-color` inválidas |
| A2 | 1.4.3 Contraste | `scss/style.scss` | 🟡 Médio | Verificar contraste de botões em Cover (#0693e3) |
| A3 | 2.3.3 Movimento | `scss/style.scss` | 🟡 Médio | `prefers-reduced-motion` não implementado |
| A4 | 2.4.11 Foco | `scss/style.scss` | 🟢 Baixo | Adicionar `:focus-visible` com outline explícito |
| A5 | Performance | `scss/style.scss` | 🟢 Baixo | `transition: all` → especificar propriedades |
