---
id: plan-split
agent: architect
execution: inline
inputFile: squads/uenf-css-performance/output/audit-report.md
outputFile: squads/uenf-css-performance/output/architecture-plan.md
---

# Step 2: Plano de Arquitetura

Com base na auditoria, projetar como dividir o CSS em bundles condicionais.

## Instruções

1. Definir a estrutura de arquivos:
   - `css/responsive/mobile.css` — regras exclusivas mobile (max-width: 767.98px)
   - `css/responsive/tablet.css` — regras exclusivas tablet (768px-991.98px)
   - `css/responsive/desktop.css` — regras exclusivas desktop (min-width: 992px)
   - Regras comuns permanecem nos arquivos originais (sem media query)
2. Definir como o WordPress carrega condicionalmente:
   ```php
   wp_enqueue_style('cct-mobile', .../mobile.css, [...], $ver);
   wp_style_add_data('cct-mobile', 'media', '(max-width:767.98px)');
   ```
3. Explicar que `<link media="(max-width:767.98px)">` faz o browser:
   - BAIXAR o arquivo (para cache)
   - MAS NÃO bloquear render se a media não match
   - Isso é melhor para Core Web Vitals (FCP, LCP)
4. Listar quais arquivos serão modificados e como
5. Estimar a redução de render-blocking CSS por device
