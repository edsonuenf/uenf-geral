---
id: build-test
agent: build-eng
execution: inline
outputFile: squads/uenf-css-performance/output/build-report.md
---

# Step 8: Build e Teste

Rodar o build e verificar que nenhum estilo visual quebrou.

## Instruções

1. `npm run build` — deve passar sem erros
2. Verificar existência de `css/responsive/mobile.css`, `tablet.css`, `desktop.css`
3. Verificar que o HTML inclui `<link media="(max-width:767.98px)">` etc.
4. Testar no Playwright:
   - Viewport 375×812 (mobile) — capturar screenshot
   - Viewport 768×1024 (tablet) — capturar screenshot
   - Viewport 1920×1080 (desktop) — capturar screenshot
5. Verificar que barra inferior mobile funciona (home, gear, idiomas)
6. Verificar que painel de atalhos abre e fecha
7. Produzir relatório com screenshots e resultado dos testes
