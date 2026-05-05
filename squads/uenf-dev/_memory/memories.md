# Squad Memory — uenf-dev

## Contexto do Projeto
- Tema WordPress: uenf-geral-claude
- Repositório branches: `modelos-uenf` (feature), `main` (produção)
- Stack: PHP (WordPress), SCSS/CSS, JavaScript vanilla, theme.json
- Dockerizado: sim (docker-compose.yml)
- Plugin separado: `uenf-templates` — responsável por patterns/modelos (o squad NÃO gera patterns)
- Plugin montado via Docker volume: `../uenf-templates:/var/www/html/wp-content/plugins/uenf-templates`

## Decisões de Arquitetura
- Patterns (block patterns) são responsabilidade exclusiva do plugin `uenf-templates`
- Este squad foca em: templates, design system, UX, testes e segurança do tema
- Cores registradas com slugs em inglês: `primary` (#1d3771), `secondary` (#222a3b) via `add_theme_support('editor-color-palette')` em functions.php

## Agentes do Squad
| Nome | Papel | Incorpora sugestão anterior |
|------|-------|---------------------------|
| Lucas | WordPress Template Developer | — |
| Isabela | Designer & Design System | ✅ Design Token Reviewer |
| Marina | UX/UI Specialist | — |
| Camila | UX Writer | — |
| Pedro | SEO & GEO Specialist | — |
| Rafael | Frontend Tester | — |
| Diego | Backend Tester | — |
| André | Security Reviewer | ✅ Security Reviewer |
| Beatriz | Release Manager & Documentation | ✅ Release Manager |

## Histórico de Execuções
_(nenhuma execução registrada ainda)_

## Aprendizados Acumulados
- Classes CSS de cor devem usar os mesmos slugs registrados no WordPress (`primary`/`secondary`), não termos em português
- `uenf_get_random_image()` usa placehold.co — função de dev, não usar em produção
- Tema tende a usar muitos `!important` — monitorar para não criar especificidade excessiva
- `prefers-reduced-motion` ainda não implementado — item recorrente de acessibilidade
