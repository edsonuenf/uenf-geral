---
id: extract-mobile
agent: mobile-dev
execution: inline
inputFile: squads/uenf-css-performance/output/architecture-plan.md
outputFile: css/responsive/mobile.css
---

# Step 4: Extrair CSS Mobile

Extrair todas as regras CSS mobile do tema para `css/responsive/mobile.css`.

## Instruções

1. Processar cada arquivo CSS identificado na auditoria
2. Para cada @media (max-width: 767.98px) e @media (max-width: 576px):
   - MOVER o conteúdo para css/responsive/mobile.css
   - REMOVER do arquivo original
3. Manter as @media queries no destino (o browser precisa delas)
4. Adicionar header com seções organizadas:
   ```css
   /* =============================================
      Mobile Styles (max-width: 767.98px)
      Carregado condicionalmente via <link media="...">
      ============================================= */
   ```
5. Arquivos prioritários: custom-fixes.css, header.scss (compilado), style.scss, layout.scss, footer.css, shortcuts.css
