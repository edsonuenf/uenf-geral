---
id: extract-tablet
agent: tablet-dev
execution: inline
inputFile: squads/uenf-css-performance/output/architecture-plan.md
outputFile: css/responsive/tablet.css
---

# Step 5: Extrair CSS Tablet

Extrair regras CSS tablet-only para `css/responsive/tablet.css`.

## Instruções

1. Identificar regras tablet-only:
   - @media (min-width: 768px) and (max-width: 991.98px)
   - @media (max-width: 991.98px) que NÃO são mobile (verificar se há overlap)
2. Para ranges que cobrem mobile+tablet (ex: max-width: 991.98px), manter no arquivo original com a media query (é common entre os dois)
3. Apenas regras EXCLUSIVAS de tablet vão para tablet.css
4. Organizar por seção com header de comentários
