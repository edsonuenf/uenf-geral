---
id: audit-css
agent: auditor
execution: inline
outputFile: squads/uenf-css-performance/output/audit-report.md
---

# Step 1: Auditoria CSS

Auditar TODOS os arquivos CSS e SCSS do tema UENF.

## Instruções

1. Listar todos os arquivos em `css/`, `css/components/`, `css/layout/`, `scss/`, `scss/components/`
2. Para cada arquivo, contar linhas e extrair todas as `@media` queries
3. Classificar cada bloco:
   - **common**: sem @media ou @media all/print
   - **mobile**: max-width: 767.98px, max-width: 576px
   - **tablet**: max-width: 991.98px (que não é mobile), min-width: 768px and max-width: 991.98px
   - **desktop**: min-width: 992px, min-width: 1200px, min-width: 1400px
4. Verificar como cada CSS é enqueued no `functions.php` (condicional? universal?)
5. Verificar o webpack.config.js (o que é compilado via SCSS)
6. Produzir relatório com tabela resumo e recomendações
