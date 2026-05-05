# SEO & GEO Report — uenf-dev | Run 2026-03-30-115040
**Agente:** Pedro — SEO & GEO Specialist
**Data:** 2026-03-30

---

## Resumo

| Categoria | Status | Impacto |
|-----------|--------|---------|
| wp_head / wp_footer | ✅ Presentes e corretos | — |
| Feeds RSS | ✅ Não removidos | — |
| Breadcrumb semântico | ✅ Implementado | Positivo |
| Headings — impacto das alterações | ✅ Sem regressão | — |
| Schema.org — nesta sessão | ℹ️ Não alterado | Oportunidade |
| Query Loop — fix de bullets | ✅ Melhora SEO | Positivo |
| `placehold.co` em produção | 🔴 Risco SEO | Alto |
| Migração de CSS — impacto em LCP | 🟡 Monitorar | Médio |
| GEO — estrutura de conteúdo | 🟡 Oportunidade | Médio |

---

## ✅ Fundamentos WordPress — Corretos

### `wp_head()` e `wp_footer()`
```php
// header.php
if ( function_exists( 'wp_head' ) ) :
    wp_head(); // correto
endif;

// footer.php
wp_footer(); // correto
```
Ambos presentes. Nenhum hook de SEO foi removido de `wp_head`. Plugins como Yoast SEO, RankMath e All in One SEO funcionarão corretamente.

**Nota positiva:** O tema verifica `function_exists('wp_head')` antes de chamar — isso é um bom padrão de compatibilidade, mas ligeiramente paranóico (wp_head sempre existe em WordPress). Não é um problema.

### Feeds RSS
Nenhum `remove_action` encontrado removendo feeds. Os feeds RSS/Atom permanecem ativos, o que é importante para:
- Agregadores de conteúdo
- Crawlers de IA (ChatGPT, Perplexity, outros) que indexam conteúdo via feed
- Google Discover e alertas de notícias

---

## ✅ Breadcrumb — Implementação Positiva para SEO e GEO

O tema tem uma implementação de breadcrumb customizada (`cct_custom_breadcrumb`) que é relevante para SEO:

```php
echo '<nav aria-label="breadcrumb">';
echo '<ol class="custom-breadcrumb">';
// hierarquia: Home > Categoria > Artigo
```

**Pontos positivos:**
- Usa `<nav>` com `aria-label` — acessível e semântico
- Usa `<ol>` — correto para hierarquia ordenada
- Tem `aria-current="page"` no item ativo — correto para ARIA

**Oportunidade de melhoria para SEO:**
O breadcrumb não inclui marcação `BreadcrumbList` do Schema.org. Adicionar JSON-LD aumentaria a chance de Google exibir breadcrumbs nos resultados:

```php
// Adicionar em functions.php junto ao breadcrumb:
add_action('wp_head', function() {
    if (function_exists('cct_custom_breadcrumb')) {
        // Gerar JSON-LD BreadcrumbList baseado na mesma lógica
        echo '<script type="application/ld+json">...</script>';
    }
});
```
Este item não é uma regressão desta sessão — é uma oportunidade.

---

## 🔴 Risco SEO — `placehold.co` em produção

```php
return "https://placehold.co/{$width}x{$height}/{$bg}/ffffff?text=UENF+Image";
```

**Impacto SEO direto:**
1. **Imagens sem conteúdo real não são indexadas** — `placehold.co` gera imagens genéricas sem valor para indexação de imagens do Google
2. **Slow LCP** — dependência de serviço externo para carregar imagens acima da dobra aumenta o Largest Contentful Paint, impactando Core Web Vitals
3. **URLs externas em conteúdo** — o Google identifica imagens de serviços de placeholder como conteúdo de baixa qualidade
4. **Soft 404 risk** — se `placehold.co` ficar indisponível, a página exibirá imagens quebradas, impactando UX e sinalização negativa de qualidade

**GEO adicional:** LLMs que crawleiam o site encontrarão imagens sem contexto semântico útil — o texto `"UENF Image"` não fornece nenhuma informação sobre o conteúdo da imagem para modelos generativos.

**Mitigação já apontada por Lucas:** Adicionar guard de ambiente para garantir que esta função nunca retorne URL externa em produção:
```php
function uenf_get_random_image( $width = 1200, $height = 800 ) {
    if ( ! defined('WP_DEBUG') || ! WP_DEBUG ) {
        return ''; // Retorna vazio em produção
    }
    // ... resto da função
}
```

---

## 🟡 LCP — Migração de CSS e Carregamento de Fontes

A migração do stylesheet principal para `assets/dist/css/style.min.css` mantém o CSS minificado, o que é positivo. Porém, a ordem de enqueue no tema é:

```php
1. Google Fonts (externo)
2. Font Awesome (externo CDN)
3. Bootstrap CSS (externo CDN)
4. variables.css, reset.css, hero-header-fix.css... (locais)
5. style.min.css (local)
```

**Risco de LCP:** Fontes do Google Fonts e Bootstrap estão enfileirados como dependências bloqueantes de renderização antes do CSS principal. O tema tem um comentário: `"Fontes externas (carregadas primeiro para evitar FOUT"` — FOUT (Flash of Unstyled Text) é uma preocupação legítima, mas a estratégia atual pode impactar negativamente o FCP (First Contentful Paint) em conexões lentas.

**Recomendação:** Avaliar uso de `font-display: swap` nas Google Fonts e preconnect para CDNs:
```php
// Adicionar ao wp_head:
echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
```
Esta não é uma regressão desta sessão — é uma oportunidade de melhoria.

---

## ✅ Fix de Query Loop — Impacto SEO Positivo

```scss
.wp-block-post-template {
  list-style: none !important;
}
```

Este fix remove bullets indesejados da `<ul>` do Query Loop. Do ponto de vista SEO:
- **Positivo:** Remove ruído visual que poderia confundir o rendering do Google Search Console
- **Positivo:** A estrutura `<ul><li>` do Query Loop permanece semanticamente correta — apenas o estilo visual foi ajustado
- Nenhuma regressão semântica

---

## 🟡 GEO — Oportunidades para Indexação por LLMs

As alterações desta sessão não impactam negativamente a indexação por motores generativos. Contudo, identifico oportunidades relevantes para a UENF:

### Oportunidades (não regressões)

**1. Organization Schema na homepage/rodapé**
A UENF como instituição se beneficiaria de um JSON-LD `Organization`:
```json
{
  "@type": "Organization",
  "name": "Universidade Estadual do Norte Fluminense Darcy Ribeiro",
  "alternateName": "UENF",
  "url": "https://uenf.br",
  "sameAs": ["https://instagram.com/uenf_oficial", "https://facebook.com/uenfoficial"]
}
```

**2. FAQPage Schema em páginas com perguntas frequentes**
Se os padrões do tipo FAQ forem implementados no plugin `uenf-templates`, considerar adicionar `FAQPage` Schema para que o conteúdo seja respondido diretamente por LLMs.

**3. Dados estruturados para Cursos**
`Course` e `CourseInstance` do Schema.org são prioritários para universidades — LLMs citam cursos de universidades que têm esses dados estruturados.

---

## Resumo de Issues SEO/GEO

| # | Issue | Tipo | Prioridade |
|---|-------|------|------------|
| S1 | `placehold.co` pode vazar para produção | Risco | 🔴 Alta |
| S2 | Fontes externas bloqueantes antes do CSS principal | Performance | 🟡 Média |
| S3 | Breadcrumb sem Schema.org BreadcrumbList | Oportunidade | 🟢 Baixa |
| S4 | Ausência de Organization Schema | Oportunidade | 🟢 Baixa |
| S5 | Ausência de Course/FAQPage Schema | Oportunidade | 🟢 Baixa |
