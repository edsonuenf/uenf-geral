# Regras de Validação Estrita para Padrões de Blocos (Gutenberg)

Para evitar o erro "Este bloco contém conteúdo inesperado ou inválido" ao inserir padrões, siga estritamente as regras abaixo. O editor Gutenberg compara o comentário JSON do bloco com o HTML gerado caractere por caractere.

## 1. Cores de Borda
**NUNCA** use cores hexadecimais (`#e0e0e0`) diretamente no atributo `style` para bordas.
**SEMPRE** use as classes de cor do tema (`has-border-color`, `has-secondary-border-color`).

**Errado (Gera Erro):**
```html
<!-- wp:column {"style":{"border":{"color":"#e0e0e0"}}} -->
<div class="wp-block-column" style="border-color:#e0e0e0">
```

**Correto:**
```html
<!-- wp:column {"borderColor":"secondary"} -->
<div class="wp-block-column has-border-color has-secondary-border-color">
```

## 2. Ordem das Propriedades CSS
No atributo `style=""` do HTML, as propriedades devem seguir uma ordem específica para validar.

**Ordem Obrigatória:**
1. `background-color` (se aplicável)
2. `border-width`
3. `border-radius`
4. `box-shadow`
5. `color` (texto)
6. `display` (ex: `table` para badges)
7. `font-size`
8. `font-weight`
9. `margin-left` (para alinhamento)
10. `padding-top`
11. `padding-right`
12. `padding-bottom`
13. `padding-left`
14. `text-transform`

**Exemplo Validado:**
```html
<div class="..." style="border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--grande)...">
```

## 3. Padding Expandido
Evite usar a notação shorthand `padding: value`. O Gutenberg frequentemente expande isso para os 4 lados no HTML salvo.

**Preferência:** Defina explicitamente `top`, `right`, `bottom`, `left` no JSON e no style inline.

```json
"spacing":{"padding":{"top":"var:preset|spacing|grande","right":"...","bottom":"...","left":"..."}}
```

## 4. Estrutura de Cartões
Ao criar grids de cartões, aplique o estilo de borda/fundo diretamente na `wp:column` se possível, evitando aninhar muitos `wp:group` desnecessários que complicam a validação.
