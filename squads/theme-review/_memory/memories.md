# Squad Memory — theme-review

## Contexto do Projeto
- Tema WordPress: uenf-geral-claude
- Repositório: branch `modelos-uenf` (feature), `main` (produção)
- Stack: PHP (WordPress), SCSS/CSS, JavaScript vanilla
- Dockerizado: sim (docker-compose.yml)
- Plugin externo: `uenf-templates` (deve estar em `../uenf-templates` relativo ao projeto)

## Histórico de Execuções

### Run: 2026-03-30-111755
- **Branch revisada:** modelos-uenf
- **Veredicto:** CONDITIONAL APPROVE (aprovado com 3 itens bloqueantes)
- **Bug crítico encontrado:** classes `.has-primaria-color` / `.has-secundaria-color` inválidas — WordPress usa slugs `primary`/`secondary` (inglês)
- **Output:** `squads/theme-review/output/2026-03-30-111755/v1/`

## Aprendizados
- As cores do tema UENF são registradas com slugs em inglês (`primary`, `secondary`) via `add_theme_support('editor-color-palette')` em `functions.php` — sempre verificar os slugs antes de criar classes utilitárias CSS
- `uenf_get_random_image()` usa `placehold.co` — função exclusiva para uso em padrões de desenvolvimento
- O plugin `uenf-templates` está sendo integrado via Docker volume

## Padrões Observados
- Tema tende a usar muitos `!important` nos estilos — pode causar dificuldade de override no futuro
- Não há implementação de `prefers-reduced-motion` — item recorrente para acessibilidade
- Cores hardcoded aparecem no CSS mesmo com variáveis disponíveis no SCSS
