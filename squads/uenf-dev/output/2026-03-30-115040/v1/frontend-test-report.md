# Frontend Test Report — uenf-dev | Run 2026-03-30-115040
**Agente:** Rafael — Frontend Tester
**Data:** 2026-03-30

---

## Resumo

| Área | Status | Severidade |
|------|--------|------------|
| CSS compilado — integridade | ✅ Correto | — |
| Novos estilos no build | ✅ Presentes | — |
| Carregamento de assets (enqueue) | ⚠️ Atenção | Médio |
| Estratégia de defer em scripts | ✅ Implementada | — |
| Lazy loading de imagens | ✅ Implementado | — |
| CSS compilado — tamanho | 🟡 Monitorar | Baixo |
| Regressão de `!important` em headings | ✅ Sem regressão | — |
| `.has-primaria-color` no build | ⚠️ Confirmado no CSS, inativo no editor | Médio |

---

## ✅ CSS Compilado — Build Correto

O arquivo `assets/dist/css/style.min.css` (7.5 KB) contém **todos** os novos estilos da branch:

**Novos estilos presentes no build:**
```css
/* Utilitários de cor — presentes mas com slug incorreto para o editor */
.has-primaria-color { color: #1d3771 !important; }
.has-secundaria-color { color: #2c5aa0 !important; }

/* Botão pill outline */
.wp-block-button__link {
  background-color: transparent !important;
  border: 1px solid #1d3771 !important;
  border-radius: 50px !important;
  color: #1d3771 !important;
  padding: 10px 24px !important;
  transition: all .3s ease;
}
.wp-block-button__link:focus,
.wp-block-button__link:hover {
  background-color: #1d3771 !important;
  color: #fff !important;
}

/* Botão em Cover */
.wp-block-cover .wp-block-button__link {
  background-color: #0693e3 !important;
  border: none !important;
}

/* Fix Query Loop */
.wp-block-post-template,
.wp-block-post-template li {
  list-style: none !important;
  margin-left: 0 !important;
  padding-left: 0 !important;
}
```
Build funcionando corretamente. O SCSS foi compilado e minificado sem erros detectados.

---

## ⚠️ `.has-primaria-color` — Existe no CSS, mas não será aplicada pelo editor

Confirmado no build: `.has-primaria-color { color: #1d3771 !important }` está presente no CSS compilado.

**O CSS está lá e tecnicamente funciona** — se alguém adicionar manualmente a classe `has-primaria-color` num elemento HTML, a cor será aplicada.

**O problema** (já apontado por Isabela e Lucas): o editor Gutenberg aplica `.has-primaria-color` via `theme.json` (slug `primaria`), mas aplica `.has-primary-color` via `functions.php` (slug `primary`). Como **ambos os sistemas estão ativos**, o editor vai gerar **duas classes diferentes** dependendo de qual sistema ganhar precedência. Resultado imprevisível.

**Do ponto de vista do frontend:** a cor `#1d3771` aparece igual nas duas classes — então visualmente pode não haver diferença imediata. O risco é em conteúdo publicado antes da correção.

---

## ⚠️ Carregamento de Assets — Ordem e Dependências

A cadeia de enqueue tem **muitas dependências em série**:

```
1. Google Fonts (externo, bloqueante)
2. Font Awesome CDN (externo, bloqueante)
3. Bootstrap CSS (externo, bloqueante)
4. variables.css (local) → depende de Bootstrap
5. reset.css (local)
6. hero-header-fix.css (local) → depende de reset
7. style.min.css (local) ← CSS principal
8. styles.css (local) → depende de style
9. custom-fixes.css (local) → depende de styles
10. patterns.css (local) → depende de style
11. spacing-fixes.css (condicional)
```

**Pontos de atenção:**
- 3 requisições externas bloqueantes antes do CSS principal
- `style.min.css` é um arquivo novo em `assets/dist/css/` mas `styles.css` e `custom-fixes.css` ainda vivem em `/css/` — **dois diretórios diferentes sendo servidos em paralelo**
- A cadeia pode causar FOUC (Flash of Unstyled Content) se algum CDN externo demorar

**Positivo:**
- `defer` implementado via `cct_add_defer_to_scripts` para todos os scripts `cct-*` e `uenf-*`
- jQuery sem defer (correto — é dependência síncrona)
- Lazy loading via `loading="lazy" decoding="async"` em imagens

---

## ✅ Lazy Loading — Implementado Corretamente

```php
$html = str_replace('<img', '<img loading="lazy" decoding="async"', $html);
```

Aplicado globalmente via filtro. Estratégia correta para performance de carregamento de imagens.

---

## 🟡 CSS Compilado — Tamanho (7.5 KB)

O arquivo `style.min.css` tem apenas **7.5 KB** minificado. Este é o arquivo principal do SCSS customizado do tema. Para comparação:
- Bootstrap carregado antes: ~190 KB (CDN)
- Font Awesome: ~30 KB (CDN)
- O CSS principal do tema é proporcionalmente pequeno

**Monitorar:** À medida que mais estilos forem adicionados, avaliar se um único arquivo minificado é suficiente ou se vale considerar critical CSS inline + deferred loading.

---

## ✅ Regressão de `line-height` — Sem Impacto Visual

A mudança de `line-height: 1.6 !important` para `line-height: 1.6` em headings:
- Valor idêntico — **zero impacto visual** em browsers padrão
- Remoção do `!important` é positiva: permite que blocos Gutenberg com estilos inline sobrescrevam se necessário
- Confirmado no build: `h1,h2,h3,h4,h5,h6,p { display:block!important; line-height:1.6; margin:0 0 1rem!important; white-space:normal!important }` — correto

---

## Checklist Frontend

| Item | Status |
|------|--------|
| CSS novo compilado e presente no build | ✅ |
| Nenhum erro de sintaxe SCSS evidente | ✅ |
| Estilos de hover/focus nos botões | ✅ |
| fix de Query Loop no build | ✅ |
| Scripts com defer | ✅ |
| Lazy loading em imagens | ✅ |
| `fit-content` com vendor prefix `-moz-fit-content` | ✅ (autoprefixer aplicado) |
| `transition: all` — preferir propriedades específicas | ⚠️ Melhoria futura |
| `prefers-reduced-motion` ausente | ⚠️ Acessibilidade — a corrigir |
| Ordem de enqueue com 3 CDNs bloqueantes | ⚠️ Performance — a monitorar |
