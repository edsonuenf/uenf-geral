# UX/UI Report — uenf-dev run 2026-03-31

**Autora:** Marina (UX/UI Specialist)
**Data:** 2026-03-31
**Escopo:** Aparência > Personalizar > UENF — nova estrutura de grupos-cabeçalho

---

## Status Geral

**APROVADO COM RESSALVAS**

A estrutura de grupos-cabeçalho é uma melhoria real em relação à lista plana anterior: o admin da UENF agora tem uma visão organizada de 60+ seções distribuídas em 16 categorias. A abordagem técnica é engenhosa e funciona dentro das limitações do WordPress Customizer. Porém há problemas de acessibilidade, tipografia e performance que precisam ser endereçados antes de considerar a UI pronta para produção.

---

## 1. Hierarquia Visual e Escaneabilidade

**Resultado: Satisfatório com ajustes necessários**

A distinção visual entre grupos-cabeçalho e seções-filho está razoavelmente clara: fundo `#e8edf4`, texto azul-escuro `#1d3771`, uppercase com letter-spacing, sem seta de expansão. O `border-left: 3px solid #c8d3e8` nas seções-filho cria uma hierarquia visual que remete ao padrão de listas aninhadas.

**Pontos positivos:**
- A remoção do `::after` (seta de expansão) nos grupos elimina o sinal de "clicável" — reduz risco de confusão com seções reais.
- O `padding-left: 28px` nas seções-filho é suficiente para criar percepção de subordinação.
- Os emojis nos títulos dos grupos ajudam na escaneabilidade rápida — decisão acertada.

**Problemas:**
- `margin-top: 4px` nos grupos é muito compacto para separar 16 categorias em lista longa. Admin pode perder a percepção de nova categoria ao rolar.
- SEO e Social Media standalone ao final quebram o padrão visual sem justificativa aparente.

---

## 2. Acessibilidade (teclado, leitor de tela, WCAG)

**Resultado: Problemas críticos identificados**

**Navegação por teclado:** `pointer-events: none` afeta apenas o mouse. O botão `.accordion-section-title` dos grupos ainda pode receber foco via Tab. Ao navegar por teclado, o admin chegará ao botão do grupo-cabeçalho, tentará ativá-lo com Enter/Space e nada acontecerá — sem feedback de que é intencionalmente não-interativo.

*Recomendação:* Adicionar `tabindex="-1"` no botão interno via JS e `aria-hidden="true"` na seção inteira.

**Leitores de tela:** Sem role ou aria-label específicos, leitores de tela anunciam os grupos como "botões". Usuário de NVDA/VoiceOver ouvirá "🎨 Cores, botão" e tentará interagir — o silêncio é confuso.

*Recomendação:* Adicionar `role="heading" aria-level="2"` para que leitores de tela anunciem como títulos, não controles.

**Foco visual:** Não há estilo `:focus` explícito nas seções-filho — apenas `:hover`. Admin que navega por teclado não tem feedback visual adequado.

*Recomendação:* Adicionar `.uenf-child-section .accordion-section-title:focus { border-left-color: #1d3771; outline: 2px solid #1d3771; }`.

---

## 3. Contraste e Tipografia

**Resultado: Uma falha de legibilidade, um risco de acessibilidade**

**Contraste `#1d3771` sobre `#e8edf4`:** Razão ~11.4:1 — **passa WCAG AA e AAA**. Sem problema.

**`font-size: 10px` nos cabeçalhos de grupo:** Abaixo do mínimo recomendado de 12px para texto de interface. A combinação `10px + uppercase + letter-spacing` melhora a percepção relativa, mas não compensa o tamanho absoluto. Considerando que a UENF é instituição pública sujeita ao e-MAG (baseado em WCAG 2.0 AA), isso é relevante.

*Recomendação:* Aumentar para `11px` (mínimo) ou `12px` (ideal).

**`font-size: 12px` nas seções-filho:** No limite mínimo — considerar 13px para alinhar com a base do WordPress Admin.

---

## 4. Organização dos Grupos

**Resultado: Boa estrutura com três pontos de atenção**

A taxonomia dos 16 grupos reflete bem a complexidade do tema. A ordem (Cores em 100, Sistema em 1600) é uma boa decisão de priorização por frequência de uso.

**Pontos de atenção:**

- **"Atalho Rápido" fora de contexto:** Posicionado entre Formulários (700) e Ícones (900), longe de Navegação (500) onde faria mais sentido semanticamente.
- **Sobreposição "Layout" vs "Responsividade":** `cct_layout_breakpoints` (em Layout) e `cct_breakpoints_*` (em Responsividade) dividem o mesmo tema — admin não sabe onde ir para ajustar breakpoints.
- **Padrões com 9 seções-filho:** O maior grupo. Avaliar subcategorização futura.

---

## 5. Comportamento da Busca

**Resultado: Funcional, mas com problemas de performance e UX**

**setInterval(800ms):** ~4.500 consultas DOM em 60 segundos de uso. Negligível em hardware moderno, perceptível em máquinas lentas de uso administrativo.

**Delay perceptível:** Para busca em tempo real, o padrão de UX é feedback < 300ms. Com 800ms, o admin pode ver grupos mudando visualmente após terminar de digitar, criando impressão de lentidão.

**Lógica de visibilidade correta:** "any visible = show group" é a decisão certa — quando a busca retorna 1 filho de 9, o admin ainda vê o contexto do grupo.

*Recomendação imediata:* Reduzir para `200ms`. Para produção: migrar para `MutationObserver`.

---

## 6. Issues Encontradas

| Issue | Severidade | Recomendação |
|---|---|---|
| Grupos-cabeçalho recebem foco por teclado sem função interativa | **Alta** | `tabindex="-1"` no botão interno + `aria-hidden="true"` na seção |
| `font-size: 10px` nos cabeçalhos abaixo do mínimo recomendado | **Alta** | Aumentar para `11px` ou `12px` mantendo uppercase + letter-spacing |
| Ausência de estilo `:focus` nas seções-filho | Média | Adicionar `border-left-color: #1d3771` + `outline` visível no `:focus` |
| `setInterval(800ms)` sem `clearInterval` — delay de 800ms na busca | Média | Reduzir para 200ms; criar ticket para MutationObserver |
| Sem role semântico nos grupos para leitores de tela | Média | Adicionar `role="heading" aria-level="2"` ou `aria-label` descritivo |
| SEO e Social Media standalone sem marcação visual distinta | Baixa | Adicionar separador visual ou grupo neutro "Outros" |
| "Atalho Rápido" posicionado longe de grupos relacionados | Baixa | Mover para prioridade 450–550, próximo a Navegação |
| Sobreposição conceitual Layout vs Responsividade (ambos com breakpoints) | Baixa | Consolidar breakpoints em um único grupo |
| `font-size: 12px` nas seções-filho no limite mínimo | Baixa | Considerar 13px para alinhar com base do WP Admin |
| Grupos com 7–9 seções-filho podem causar fadiga de scroll | Info | Monitorar; avaliar subcategorização futura |

---

## 7. Recomendações Prioritárias

### Prioridade 1 — Antes de merge (acessibilidade crítica)

**A. Remover grupos-cabeçalho da ordem de tabulação** — No bloco JS do `customize_controls_enqueue_scripts`:

```js
Object.keys(groupChildren).forEach(function(groupId) {
    var $group = $("#accordion-section-" + groupId);
    $group.attr("aria-hidden", "true");
    $group.find(".accordion-section-title").attr("tabindex", "-1");
});
```

**B. Aumentar font-size dos grupos para 11px** — No CSS inline, alterar `font-size: 10px !important` para `font-size: 11px !important`.

### Prioridade 2 — Sprint seguinte

**C. Adicionar estilo `:focus` nas seções-filho:**
```css
.uenf-child-section .accordion-section-title:focus {
    border-left-color: #1d3771 !important;
    outline: 2px solid #1d3771 !important;
    outline-offset: -2px !important;
}
```

**D. Reduzir delay do setInterval** — Alterar `800` para `200` como fix imediato. Criar ticket para MutationObserver.

### Prioridade 3 — Backlog

**E.** Revisar posição de "Atalho Rápido" — mover para prioridade 450 ou 550.

**F.** Clarificar "Layout" vs "Responsividade" — consolidar breakpoints em um grupo.

**G.** Marcação para SEO e Social Media standalone — separador visual ou grupo neutro.
