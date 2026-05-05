# Entrada de CHANGELOG — branch modelos-uenf

> Adicionar ao topo de `CHANGELOG.md` quando o branch for mergeado em `main`.
> Versão sugerida: **v0.1.0** (mudanças de funcionalidade + refatoração)

---

## [0.1.0] — Não lançado (branch: modelos-uenf)

### Adicionado
- `scss/variables.scss`: variável `$secondary-color: #2c5aa0` para cor secundária do tema
- `scss/style.scss`: estilos de botão "pill outline" com estados hover/focus para `.wp-block-button__link`
- `scss/style.scss`: exceção de estilo para botões dentro de `.wp-block-cover` (sólido/preenchido para visibilidade)
- `scss/style.scss`: fix de lista para `.wp-block-post-template` (remove bullets indesejados do Query Loop)
- `inc/template-tags.php`: função helper `uenf_get_random_image()` para imagens placeholder em padrões de desenvolvimento
- `docker-compose.yml`: volume para plugin `uenf-templates` (`../uenf-templates`) e volume nomeado `wp_uploads`

### Modificado
- `functions.php`: caminho do editor style atualizado de `css/editor-style.css` para `assets/dist/css/style.min.css`
- `functions.php`: caminho do CSS de produção atualizado de `/css/style.min.css` para `/assets/dist/css/style.min.css`
- `scss/style.scss`: removido `!important` de `line-height` em headings h2/h3 (permite overrides específicos)

### Removido
- `patterns/contact-card.php`: padrão removido (migrado para plugin ou descontinuado)
- `patterns/faq-accordion.php`: padrão removido
- `patterns/faq-tabs.php`: padrão removido
- `patterns/highlights-grid.php`: padrão removido
- `patterns/news-list.php`: padrão removido
- `patterns/pricing-table.php`: padrão removido

### Pendente antes do merge (issues da revisão)
- [ ] **CRÍTICO:** Corrigir classes `.has-primaria-color` e `.has-secundaria-color` → renomear para `.has-primary-color` e `.has-secondary-color` (slugs registrados no WordPress)
- [ ] Verificar contraste do botão de Cover block (`#0693e3` sobre fundo de foto)
- [ ] Implementar `@media (prefers-reduced-motion: reduce)` para transições CSS
