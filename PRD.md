# PRD (Product Requirements Document) - Tema WordPress UENF Geral

## Visão Geral
Tema WordPress institucional para a UENF, focado em organização visual, modularidade de estilos, performance, acessibilidade e fácil manutenção.

## Objetivos
- Modernizar o visual do site institucional.
- Modularizar e migrar estilos para SCSS/Sass.
- Garantir responsividade e compatibilidade com Bootstrap.
- Facilitar manutenção e evolução do tema.
- Otimizar carregamento de CSS e JS.
- Integrar boas práticas de versionamento (Git).

## Funcionalidades Principais
- Estrutura de páginas: home, busca, arquivos, páginas internas, single, 404.
- Hero visual customizável em cada template.
- Breadcrumb dinâmico e visual.
- Menu principal customizado e responsivo.
- Sidebar e widgets configuráveis.
- Footer institucional.
- Suporte a imagens destacadas.
- Paginação customizada.
- Comentários desativados por padrão.
- Carregamento de fontes externas (Google Fonts, FontAwesome).
- Integração com Bootstrap.
- Variáveis CSS centralizadas para fácil customização de cores e fontes.
- Checklist de migração e manutenção.

## Requisitos Técnicos
- SCSS modularizado: `scss/components`, `scss/layout.scss`, `scss/style.scss`.
- Compilação manual do SCSS via comando:
  ```bash
  sass scss/style.scss css/style.min.css --style=compressed
  ```
- Carregamento de CSS minificado e variáveis via `functions.php`.
- Estrutura de arquivos compatível com WordPress.
- Git workflow: branches `main` e `develop`, commits frequentes.
- Documentação de integração e manutenção.

## Regras de Estilo
- `.hero-section`: fundo customizável via variável CSS.
- `.line-breadcrumb`: linha visual abaixo do breadcrumb, cor e espaçamento definidos no SCSS.
- Cores e fontes definidas em `css/variables.css`.
- Layout responsivo com grid Bootstrap.

## Critérios de Aceite
- Visual do site compatível com o design institucional da UENF.
- Hero, menu, breadcrumb e linha visual funcionando conforme especificação.
- SCSS compilando corretamente e refletindo alterações no CSS minificado.
- Nenhum arquivo CSS antigo conflitando com o novo build.
- Documentação e checklist atualizados.

## Manutenção e Evolução
- Novos componentes devem ser criados em SCSS modularizado.
- Checklist de migração e boas práticas mantido em `checklist.md`.
- Atualizações de dependências e build documentadas.

---
Este PRD pode ser expandido conforme novas demandas do projeto.
