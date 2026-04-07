# UX/UI Report — uenf-dev | Run 2026-03-30-115040
**Agente:** Marina — UX/UI Specialist
**Data:** 2026-03-30

---

## Resumo

| Área | Status | Severidade |
|------|--------|------------|
| Consistência visual de botões | ⚠️ Pendência | Médio |
| Estados interativos (hover/focus) | ⚠️ Incompleto | Médio |
| Semântica vs. aparência (elemento "Próximo Evento") | 🔴 Problema | Médio |
| Responsividade dos novos estilos | ✅ Adequado | — |
| Hierarquia visual | ✅ Mantida | — |
| Consistência com design system | Depende das correções de Isabela | — |

---

## 🔴 Problema de UX — Semântica vs. Aparência

### `.wp-block-cover .has-secondary-color` — Elemento não-botão com aparência de botão

O estilo adicionado transforma visualmente um elemento de conteúdo (provavelmente um heading `<h2>`) em um componente que parece um botão:

```scss
.wp-block-cover .has-secondary-color {
  background-color: #0693e3 !important;
  color: #fff !important;
  padding: 10px 24px !important;
  border-radius: 50px;          // pill shape
  display: inline-block !important;
  width: fit-content !important;
  font-size: 1rem !important;
  font-weight: 700 !important;
}
```

**Problema:** Um `<h2>` estilizado como botão cria confusão de affordance — o usuário espera que algo parecido com botão seja clicável. Se este elemento não for interativo (não tem `href` ou `onclick`), viola o princípio de affordance.

**Implicações adicionais:**
- Sem estado `:hover` ou `:focus` — se for clicável, o usuário não recebe feedback visual
- O comentário no código diz `"Próximo Evento"` — se é uma label de seção, deve ser um `<h2>` com aparência de heading, não de botão
- Se é um link/CTA, deve ser um `<a>` ou `<button>` real, não um heading estilizado

**Recomendação:** Decidir a semântica antes de estilizar:
- Se é **informativo** (label "Próximo Evento"): manter como heading, ajustar visual para ser distintivo mas não parecer botão
- Se é **interativo** (link para a seção de eventos): usar `wp-block-button` real ou um link com classe específica

---

## 🟡 Inconsistência Visual de Botões — Dois estilos diferentes

O tema agora tem **dois estilos de botão visuais** para o mesmo componente `wp-block-button__link`:

| Contexto | Estilo | Cor de fundo | Cor do texto |
|----------|--------|-------------|-------------|
| Geral (fora de Cover) | Pill outline | Transparente | Azul primário `#1d3771` |
| Dentro de Cover block | Pill sólido | `#0693e3` | Branco |

Esta bifurcação é **intencional e justificada** pelo contexto (botão sobre foto precisa de fundo sólido para legibilidade), mas cria um sistema de dois estados visuais de botão. O usuário que edita conteúdo no Gutenberg pode não entender por que o mesmo bloco de botão parece diferente dependendo de onde é inserido.

**Recomendação:** Documentar este comportamento nos guias de uso do tema (ou no plugin de templates) para que editores de conteúdo entendam a regra contextual.

---

## 🟡 Estados Interativos — Focus incompleto para acessibilidade

### Botão padrão
```scss
&:hover, &:focus {
  background-color: variables.$primary-color !important;
  color: #fff !important;
}
```
✅ Estado `:focus` existe — usuários de teclado conseguem identificar o foco.
⚠️ O estado de foco é **idêntico** ao hover — não tem outline explícito, o que pode não ser suficiente para usuários com baixa visão distinguirem navegação por teclado de hover por mouse.

### Botão em Cover block
```scss
&:hover, &:focus {
  opacity: 0.9;
  color: #fff !important;
}
```
⚠️ A mudança de `opacity: 0.9` é muito sutil como indicador de foco. Para um usuário navegando por teclado, esta mudança pode ser imperceptível.

### Elemento "Próximo Evento" (`.has-secondary-color` em Cover)
❌ **Nenhum estado hover ou focus definido.** Se este elemento for interativo, está sem feedback visual.

**Recomendação geral:** Adicionar `:focus-visible` com `outline` explícito:
```scss
&:focus-visible {
  outline: 3px solid currentColor;
  outline-offset: 3px;
}
```

---

## ✅ Hierarquia Visual — Mantida

- A remoção de `!important` de `line-height` em headings não impacta a hierarquia visual estabelecida — os tamanhos de fonte permanecem
- O estilo pill nos botões é consistente em todo o tema (mesma forma, mesma lógica de cor)
- O fix de bullets no Query Loop resolve um problema de hierarquia visual real (itens de lista com bullets inesperados)

---

## ✅ Responsividade — Adequada

Os estilos de botão não usam unidades fixas problemáticas:
- `padding: 10px 24px` — adequado para toque em mobile
- `border-radius: 50px` — escalável
- `width: fit-content` no elemento "Próximo Evento" — correto para evitar que o elemento estique full-width desnecessariamente

---

## Recomendações Prioritárias

1. **Decidir semântica do "Próximo Evento"** — heading informativo ou CTA interativo? Define o elemento HTML correto
2. **Adicionar `:focus-visible` com outline** nos botões e no elemento "Próximo Evento" (se interativo)
3. **Documentar a bifurcação de estilo** (outline vs. sólido) para editores de conteúdo
