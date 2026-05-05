# Squad Memory — uenf-admin-ux-impl

## Contexto
Squad criada em 2026-04-15 por solicitação do Edson.
Objetivo: implementar correções P0 + melhorias UX/UI P1 nas páginas admin do Tema UENF.

## Fonte
Baseada no relatório de auditoria da squad `uenf-theme-admin-ux` (run 2026-04-14-172156).

## Escopo
- P0-01: reset_all_extensions → reset_all_settings
- P0-02: $extension['title'] → $extension['name']
- P0-04: nonce inconsistente no Reset
- P1-04: remover painel vazio Customizer
- P1-05: agrupar extensões por categoria
- P1-06: corrigir parser Markdown
- P1-07: padronizar visual página Reset
- P1-08: adicionar link Docs no Acesso Rápido

## Arquivos Principais
- `functions.php` — a maioria das mudanças
- `inc/extensions/class-extension-manager.php` — P1-04
