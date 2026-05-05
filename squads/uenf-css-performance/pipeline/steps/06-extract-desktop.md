---
id: extract-desktop
agent: desktop-dev
execution: inline
inputFile: squads/uenf-css-performance/output/architecture-plan.md
outputFile: css/responsive/desktop.css
---

# Step 6: Extrair CSS Desktop

Extrair regras CSS desktop-only para `css/responsive/desktop.css`.

## Instruções

1. Identificar regras desktop-only:
   - @media (min-width: 992px)
   - @media (min-width: 1200px)
   - @media (min-width: 1400px)
2. Regras sem media query são COMMON, não desktop — não mover
3. Mover as media queries encontradas para desktop.css
4. Organizar por seção
