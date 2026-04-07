# Frontend Test Report — uenf-dev run 2026-03-31

**Autor:** Rafael (Frontend Tester)
**Branch analisada:** `aparencia`
**Arquivos inspecionados:** `functions.php` (linhas 568–657, 1651–1704), `inc/template-tags.php` (linhas 143–157), `theme.json`

---

## Status Geral

**APROVADO COM RESSALVAS**

O código da branch `aparencia` é funcionalmente correto e não introduz regressões no frontend público. Foram identificados dois problemas de severidade média — o `setInterval` sem `clearInterval` e o uso massivo de `!important` — além de dois pontos de atenção de severidade baixa. Nenhum bug bloqueia o lançamento, mas as ressalvas devem ser endereçadas antes de uma sessão de uso intenso do Customizer.

---

## A. CSS do Customizer — Análise

### A.1 — Seletor `[id^="accordion-section-uenf_group_"]`

**APROVADO.** CSS Selectors Level 3, suporte universal (Chrome 1+, Firefox 1+, Safari 3+, Edge 12+). O target `#accordion-section-{id}` bate com o markup padrão do WP Customizer.

### A.2 — `pointer-events: none` nos grupos

**APROVADO.** Suporte universal em browsers modernos. Ponto de atenção: não bloqueia foco via teclado (`Tab`). Um usuário pode focar e expandir grupos-cabeçalho via `Enter`, revelando painel vazio — problema de a11y, não de compatibilidade de browser.

### A.3 — `border-left` em `.uenf-child-section` — especificidade

**APROVADO COM ATENÇÃO.** Seletor `.uenf-child-section .accordion-section-title` tem especificidade `(0,2,0)` + `!important`, suficiente para sobrescrever estilos do WP Admin. Risco residual: dependência do nome de classe `accordion-section-title` que é interno ao WP Core.

### A.4 — `!important` em excesso

**ATENÇÃO — SEVERIDADE MÉDIA.** O bloco CSS contém **19 declarações `!important`**. Pragmaticamente justificado pois o próprio `customize-controls.css` do Core usa `!important`. Risco real: conflito com plugins de Customizer de terceiros que também injetem CSS com `!important` no mesmo handle, onde a ordem de enfileiramento determina o vencedor.

---

## B. JavaScript do Customizer — Análise

### B.1 — `wp.customize.bind("ready", ...)`

**APROVADO.** Timing correto e recomendado pelo WordPress para manipulação do DOM do Customizer após inicialização completa.

### B.2 — `jQuery("#accordion-section-" + id)`

**APROVADO.** Padrão `#accordion-section-{section_id}` gerado pelo WP Core. Consistente com uso no restante do codebase.

### B.3 — `setInterval` sem `clearInterval`

**BUG — SEVERIDADE MÉDIA.** O intervalo é iniciado e nunca cancelado. Três consequências:
1. Manutenção de referências aos closures `groupChildren` e jQuery indefinidamente.
2. Em recargas parciais do Customizer, intervalos se acumulam sem cancelar os anteriores.
3. ~75 execuções/min fazendo múltiplas queries jQuery ao DOM durante sessão longa.

Impacto prático é baixo (o Customizer é de vida curta), mas é um bug real.

### B.4 — `array_merge(...array_values($group_children_map))`

**APROVADO.** Spread operator com `array_values()` produz array flat com chaves numéricas sequenciais. `wp_json_encode` gera array JSON (não objeto), compatível com `.forEach()` no JavaScript.

---

## C. Assets e Enfileiramento

### C.1 — `wp_add_inline_style('customize-controls', $css)`

**APROVADO.** Handle `customize-controls` existe e é registrado pelo WP Core no Customizer. Hook `customize_controls_enqueue_scripts` garante execução exclusiva no contexto do Customizer.

### C.2 — `wp_add_inline_script('customize-controls', $js)`

**APROVADO COM OBSERVAÇÃO.** Handle correto. `jQuery` e `wp.customize` estão garantidamente carregados antes de `customize-controls` — dependência implícita satisfeita, mas não declarada. Aceitável para inline scripts.

---

## D. Editor de Blocos (Gutenberg)

### D.1 — Remoção de `add_theme_support`

**APROVADO.** Desde o WordPress 5.8, `theme.json` é o mecanismo canônico. Remoção corretamente documentada no código.

### D.2 — Variáveis CSS `--wp--preset--color--*`

**APROVADO.** 6 cores definidas em `theme.json` com slugs consistentes. Todas as referências em `styles.elements` batem com os slugs — nenhuma referência quebrada.

### D.3 — `fontSizes` no `theme.json`

**APROVADO.** 6 tamanhos definidos (`pequeno` a `gigante`). Todas as referências em `styles.elements` são consistentes com os slugs.

---

## E. Regressões Visuais no Frontend Público

### E.1 — Isolamento do CSS do Customizer

**APROVADO — SEM VAZAMENTO.** `wp_add_inline_style('customize-controls', ...)` dentro de `customize_controls_enqueue_scripts` é exclusivo do contexto `/wp-admin/customize.php`. O frontend público não carrega o handle `customize-controls`.

### E.2 — Alterações de `add_theme_support`

Sem impacto no frontend público. As funções removidas controlam apenas a UI do editor Gutenberg.

### E.3 — `uenf_get_random_image()` retornando `''`

**ATENÇÃO — RISCO RESIDUAL (pré-existente).** Correto e intencional. O risco é de templates usarem o retorno sem verificar se é não-vazio, gerando `<img src="">`. Não introduzido por esta branch.

---

## Bugs Encontrados

| Bug | Severidade | Arquivo:Linha | Recomendação |
|---|---|---|---|
| `setInterval` sem `clearInterval` — acúmulo em sessão longa ou reload parcial do Customizer | Média | `functions.php:651` | Armazenar ID do intervalo e chamar `clearInterval`; ou substituir por listener de evento `wp.customize.section` |
| 19 `!important` no CSS inline — risco de conflito com plugins de Customizer de terceiros | Média | `functions.php:595–625` | Aceitar para esta branch; documentar para integradores de plugins |
| `pointer-events: none` não bloqueia foco via teclado — grupos expandíveis por Tab+Enter revelam painel vazio | Baixa | `functions.php:595–596` | Adicionar `tabindex="-1"` nos grupos via JS |
| Dependência implícita do nome de classe `accordion-section-title` do WP Core | Baixa | `functions.php:598–626` | Documentar e testar após atualizações do WordPress Core |

---

## Recomendações

1. **[Alta — Próximo sprint]** Corrigir `setInterval` sem `clearInterval` em `functions.php:651`.
2. **[Média]** Adicionar `tabindex="-1"` nos grupos-cabeçalho via JS (bloco `bind("ready", ...)` existente).
3. **[Baixa — Documentação]** Comentar no CSS por que `!important` é necessário e risco com plugins de terceiros.
4. **[Baixa — Manutenção]** Criar teste de smoke para `.accordion-section-title` após atualizações do Core.
5. **[Confirmação]** Auditar plugin `uenf-templates` — todo uso de `uenf_get_random_image()` deve ter `if ( $img )` antes de atribuir ao `src`.
