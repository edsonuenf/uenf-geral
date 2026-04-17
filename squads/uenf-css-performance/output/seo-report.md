# SEO & Core Web Vitals Report
**Agente:** Lucia (SEO Engineer) | **Step:** 10-seo-audit

---

## CSS Render-Blocking: Antes × Depois

### ANTES
| Device | Arquivos render-blocking | Linhas totais |
|--------|--------------------------|--------------|
| Mobile | 38 arquivos (todos) | ~16.400 |
| Tablet | 38 arquivos (todos) | ~16.400 |
| Desktop | 38 arquivos (todos) | ~16.400 |

*Nenhum arquivo usava `media` attribute.*

### DEPOIS
| Device | Render-blocking | Non-render-blocking | Linhas bloqueantes |
|--------|-----------------|---------------------|-------------------|
| Mobile | 38 originais + 1 (mobile.css match) | tablet.css + desktop.css | ~16.385 + 440 |
| Tablet | 38 originais + 1 (tablet.css match) | mobile.css + desktop.css | ~16.400 + 47 |
| Desktop | 38 originais + 1 (desktop.css match) | mobile.css + tablet.css | ~15.912 + 89 |

**Ganho real em desktop:**
- `css/custom-fixes.css`: -185 linhas de regras mobile que eram render-blocking → agora em mobile.css (non-blocking)
- `css/components/footer.css`: -113 linhas mobile
- `css/components/new-menu.css`: -93 linhas (mobile -40, desktop -53)
- `css/components/header.css`: -56 linhas mobile
- `css/components/shortcuts.css`: -41 linhas mobile
- **Total de CSS que deixa de render-bloquear em desktop: ~488 linhas** (dos 5 arquivos modificados)

---

## Estimativa de Impacto em Core Web Vitals

### FCP (First Contentful Paint)
| Cenário | Estimativa de Melhoria |
|---------|----------------------|
| Desktop com conexão lenta (3G) | -40ms a -120ms |
| Desktop fibra | -5ms a -20ms |
| Mobile (não muda render-blocking) | 0ms (estrutural) |

> O FCP melhora porque o browser pode começar a pintar antes de processar
> os CSS non-blocking. Em mobile, o mobile.css STILL render-blocks (media match).
> O maior ganho é para usuários desktop.

### LCP (Largest Contentful Paint)
- Sem `preload` para CSS crítico: LCP não afetado diretamente por este split
- Recomendação: adicionar `<link rel="preload">` para `mobile.css` em mobile

### CLS (Cumulative Layout Shift)
- Baixo risco: estilos foram MOVIDOS, não alterados
- As regras de layout estão nas mesmas media queries, apenas em arquivos separados

---

## Tamanho por Device (payload efetivo)

| Device | CSS carregado | CSS que aplica |
|--------|--------------|----------------|
| Mobile | todos os arquivos (38+3) | 38 originais + mobile.css |
| Tablet | todos os arquivos (38+3) | 38 originais + tablet.css |
| Desktop | todos os arquivos (38+3) | 38 originais + desktop.css |

> Nota: o browser BAIXA todos os CSS (inclusive os non-matching), mas apenas
> o matching-media bloqueia o render. O cache preloads os demais.

---

## Otimizações Adicionais Recomendadas (v2+)

### Alta Prioridade
1. **Splitar `assets/dist/css/style.min.css`** — é o maior bundle (compilado do SCSS). Adicionar entry points separados no webpack:
   ```js
   entry: {
     main: './js/main.js',
     style: './scss/style.scss',
     'style-mobile': './scss/responsive/mobile.scss',
     'style-desktop': './scss/responsive/desktop.scss'
   }
   ```

2. **`preload` para CSS crítico**:
   ```php
   add_action('wp_head', function() {
     echo '<link rel="preload" href="'.CCT_THEME_URI.'/css/responsive/mobile.css" as="style" media="(max-width:767.98px)">';
   }, 1);
   ```

### Média Prioridade
3. **`font-display: swap`** — verificar se já está em uso nas fontes Google/FontAwesome
4. **Lazy loading de imagens** — verificar `loading="lazy"` nos `<img>` do tema
5. **Minificação dos responsive CSS** — adicionar os 3 arquivos no webpack como entry points

### Baixa Prioridade
6. **Splitar `cct-responsive-breakpoints.css`** (939 linhas com breakpoints Bootstrap) — candidato para split completo mobile/tablet/desktop
7. **Inline Critical CSS** — extrair ~4KB de CSS above-the-fold para `<style>` inline no `<head>`

---

## Conformidade SEO

| Critério | Status |
|----------|--------|
| Render-blocking reduzido | Parcial (v1) — 5 arquivos limpos |
| Sem CSS duplicado | OK — regras MOVIDAS, não copiadas |
| Cascade preservada | OK — mesma especificidade, mesma ordem |
| `media` attribute correto | OK — `(max-width:767.98px)` no `<link>` |
| Compatibilidade com WP | OK — `wp_style_add_data()` é a API oficial |
