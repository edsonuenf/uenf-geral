---
id: seo-audit
agent: seo-eng
execution: inline
outputFile: squads/uenf-css-performance/output/seo-report.md
---

# Step 10: Auditoria SEO e Performance

Medir o impacto das mudanças nos Core Web Vitals.

## Instruções

1. Contar CSS render-blocking ANTES (todos os <link> sem media condicional)
2. Contar CSS render-blocking DEPOIS (apenas os que não têm media attribute)
3. Calcular tamanho total dos CSS e tamanho efetivo por device:
   - Mobile: common.css + mobile.css (tablet e desktop são baixados mas não bloqueiam render)
   - Tablet: common.css + tablet.css
   - Desktop: common.css + desktop.css
4. Estimar impacto no FCP (CSS render-blocking é o principal fator)
5. Listar otimizações adicionais recomendadas:
   - font-display: swap (já usar?)
   - preload para CSS crítico
   - async loading de CSS não-crítico
   - image lazy loading
   - minificação de CSS responsivos
