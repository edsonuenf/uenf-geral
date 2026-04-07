# Dev Brief — uenf-dev run 2026-03-31

**Autor:** Lucas (WordPress Template Developer)
**Branch:** `aparencia`
**Data:** 2026-03-31

---

## Resumo das Mudanças

Este conjunto de alterações consolida toda a interface do WordPress Customizer sob um painel raiz único (`uenf_panel`), eliminando a fragmentação anterior em múltiplos sub-painéis legados herdados do tema base CCT. A mudança é puramente estrutural no lado da UI do Customizer — nenhuma configuração salva (`theme_mod` ou `option`) foi alterada, e a lógica de output CSS/JS do frontend permanece intacta. Paralelamente, foram removidos dois `add_theme_support` que conflitavam com `theme.json`, e `template-tags.php` recebeu hardening mínimo de segurança na função de placeholder de imagens.

---

## Arquivos Modificados

| Arquivo | Tipo de Mudança | Impacto |
|---|---|---|
| `functions.php` | Adição de lógica (hooks Customizer) + remoção de `add_theme_support` | Alto — controla toda a estrutura do Customizer |
| `inc/customizer.php` | Remoção de seção duplicada `cct_menu_settings` | Baixo — limpeza de duplicata |
| `inc/customizer/class-404-customizer.php` | Adição de `'panel' => 'uenf_panel'` | Baixo |
| `inc/customizer/class-animation-manager.php` | Substituição de sub-painel próprio por `'panel' => 'uenf_panel'` | Baixo |
| `inc/customizer/class-color-manager.php` | Idem | Baixo |
| `inc/customizer/class-dark-mode-manager.php` | Idem | Baixo |
| `inc/customizer/class-design-panel-manager.php` | Método `add_design_panel()` esvaziado; `get_panel_id()` retorna `uenf_panel` | Médio — quebra contratos se chamado externamente |
| `inc/customizer/class-design-tokens-manager.php` | Substituição de sub-painel por `uenf_panel` | Baixo |
| `inc/customizer/class-gradient-manager.php` | Idem | Baixo |
| `inc/customizer/class-icon-manager.php` | Idem | Baixo |
| `inc/customizer/class-layout-manager.php` | Idem | Baixo |
| `inc/customizer/class-menu-customizer.php` | `'panel' => 'uenf_panel'` adicionado à seção `menu_settings` | Baixo |
| `inc/customizer/class-pattern-library-manager.php` | Idem | Baixo |
| `inc/customizer/class-responsive-breakpoints-manager.php` | Idem | Baixo |
| `inc/customizer/class-search-customizer-controls.php` | Idem | Baixo |
| `inc/customizer/class-shadow-manager.php` | Idem | Baixo |
| `inc/customizer/class-typography-customizer.php` | Idem | Baixo |
| `inc/customizer/customizer-loader.php` | Registro de `uenf_panel` adicionado dentro de `load_modules()` | Médio — duplicata de registro (ver riscos) |
| `inc/template-tags.php` | Guard `WP_DEBUG`, `absint()`, `esc_url()` em `uenf_get_random_image()` | Baixo — hardening de segurança |

---

## Analise Tecnica

### 1. Reorganizacao do Customizer

**Arquitetura adotada**

A solucao cria uma hierarquia de tres niveis dentro de um painel unico:

```
uenf_panel (painel raiz)
  uenf_group_cores (secao com pointer-events:none — cabecalho visual)
    cct_text_colors
    cct_menu_colors
    ...
  uenf_group_tipografia
    typography_section
    ...
```

Os "grupos" sao secoes WordPress normais (`WP_Customize_Section`) que recebem CSS para desabilitar interacao e JS para controlar visibilidade dinamica. Cada grupo tem um controle anonimo vazio (`type=uenf_group`) unicamente para que `isContextuallyActive()` do Customizer JS nao oculte a secao por ausencia de controles.

**Sequencia de hooks**

| Prioridade | Responsabilidade |
|---|---|
| 5 | Registra `uenf_panel` (antes dos modulos) |
| 15 | Carrega arquivos PHP dos managers via `require_once` + instancia classes |
| default (10) | `CCT_Customizer_Loader::load_modules()` carrega modulos e registra `uenf_panel` novamente |
| 999 | Dissolve paineis legados (`cct_shortcut_panel`, etc.), redireciona secoes `cct_*` orphas |
| 1000 | Renomeia titulos e define prioridades numericas das secoes-filho |
| 1001 | Registra secoes `uenf_group_*` como cabecalhos |

**O que funciona bem**

- A abordagem de usar prioridade 999/1000/1001 para reorganizar apos todos os modulos registrarem suas secoes e correta e idiomatica para WordPress.
- `wp_json_encode()` no inline script e o metodo correto para passar dados PHP -> JS no contexto do Customizer.
- `wp_add_inline_style()` e `wp_add_inline_script()` com handles existentes (`customize-controls`) e a forma correta de injetar CSS/JS no Customizer sem enfileirar arquivos adicionais.
- A guard `if (empty($section->panel) && strpos($section->id, 'cct_') === 0)` no hook 999 evita sobrescrever secoes que ja estao corretamente alocadas.

**Problemas identificados**

1. **`setInterval(800ms)` sem `clearInterval`** — O intervalo roda indefinidamente enquanto o Customizer esta aberto. Para paineis com muitas secoes (situacao atual: 60+ secoes), isso dispara traversal de DOM a cada 800ms sem parar. Solucao: usar `MutationObserver` no `#accordion-container` ou ao menos chamar `clearInterval` quando nao ha busca ativa.

2. **Duplo registro de `uenf_panel`** — O painel e registrado em prioridade 5 em `functions.php` e novamente dentro de `CCT_Customizer_Loader::load_modules()` (linha 82 de `customizer-loader.php`). O WordPress nao lanca erro no segundo `add_panel()` para o mesmo ID (simplesmente ignora), mas e ruido e pode causar confusao futura. O registro em `customizer-loader.php` deve ser removido.

3. **`require_once` dentro do hook `customize_register` (prioridade 15)** — Os managers sao carregados tanto pelo hook de prioridade 15 em `functions.php` quanto pelo `CCT_Customizer_Loader::load_modules()` (que tambem roda no `customize_register`). Como `require_once` evita dupla inclusao, nao ha erro, mas a ordem de execucao entre os dois carregadores depende de qual hook disparar primeiro. Se `CCT_Customizer_Loader` estiver conectado na prioridade padrao (10), ele roda antes do hook de prioridade 15 e as classes ja existirao quando o segundo `require_once` tentar carrega-las — comportamento correto, mas fragil.

4. **Anonymous class PHP estendendo `WP_Customize_Control`** — Tecnicamente valido (PHP 7+), mas `WP_Customize_Control` so esta disponivel no contexto do Customizer. O hook 1001 roda em `customize_register`, entao o contexto e correto. Sem problemas aqui, mas vale documentar a dependencia de contexto.

5. **Secoes no mapa `$group_children_map` que podem nao existir** — Se uma extensao estiver desativada (ex.: `patterns` desativado), suas secoes (`cct_patterns_main`, etc.) nao serao registradas. O JS tentara adicionar a classe `uenf-child-section` em elementos inexistentes (falha silenciosa, sem erro). O grupo correspondente (`uenf_group_padroes`) sera corretamente ocultado pelo `updateGroupVisibility()`, entao o impacto visual e zero. Porem o array `$child_sections` contera IDs mortos.

6. **Emoji nos titulos dos grupos** — `uenf_group_cores => '🎨 Cores'`. Emojis em strings de UI do Customizer dependem do suporte a UTF-8 do banco e do tema admin. No WordPress moderno (UTF-8 por padrao) nao ha problema funcional, mas e inconsistente com WordPress Coding Standards que recomenda strings sem decoracao em contextos de internacionalizacao.

### 2. Remocao de add_theme_support (Design System)

A remocao de `add_theme_support('editor-color-palette', [...])` e `add_theme_support('editor-font-sizes', [...])` e tecnicamente correta para temas que usam `theme.json`.

**Por que a remocao e necessaria:**
- A partir do WordPress 5.8, `theme.json` e a fonte autoritativa para paletas de cores e escalas de tipografia do editor de blocos.
- Quando ambos coexistem, `add_theme_support` sobrescreve os valores definidos em `theme.json` para o editor Gutenberg, criando inconsistencia entre o editor e o frontend.
- A remocao elimina essa fonte de verdade duplicada.

**O que verificar apos a remocao:**
- Confirmar que `theme.json` define `settings.color.palette` e `settings.typography.fontSizes` com os valores equivalentes.
- Testar o editor de blocos: o painel "Cor" e "Tipografia" da barra lateral devem exibir as opcoes de `theme.json`.
- Se houver posts com blocos que referenciavam as classes CSS geradas pelo antigo `add_theme_support` (ex.: `has-primary-color`), verificar se `theme.json` gera as mesmas classes via `settings.color.palette[].slug`.

### 3. Melhorias em template-tags.php

A funcao `uenf_get_random_image()` recebeu tres melhorias:

**Guard `WP_DEBUG`:**
```php
if ( ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
    return '';
}
```
Correto. Funcao de placeholder nao deve executar em producao. A implementacao esta correta — retorna string vazia em producao, o que e mais seguro que retornar uma URL externa.

**`absint()` nos parametros:**
Correto. Previne que valores invalidos (strings, negativos, null) sejam interpolados na URL sem sanitizacao. O fallback `?: 1200` e `?: 800` garante valores padrao caso `absint()` retorne 0.

**`esc_url()` no retorno:**
Correto para uso em atributos `src`/`href`. A URL e construida internamente com valores ja sanitizados, entao `esc_url()` e redundante mas inofensivo e alinha com o principio de "escapar na saida".

**Observacao:** A funcao usa `placehold.co` como servico externo. Em ambientes sem acesso a internet (dev local, CI), isso pode causar imagens quebradas mesmo com `WP_DEBUG = true`. Considerar fallback para imagem local ou `data:image/svg+xml`.

### 4. Padronizacao dos class-*-manager.php

**Padrao aplicado:** Todos os 14 managers que antes criavam sub-paineis proprios agora passam `'panel' => 'uenf_panel'` ao registrar suas secoes.

**Verificacao de consistencia:** Todos os 14 arquivos presentes em `inc/customizer/` com o sufixo `-manager.php` ou `-customizer.php` confirmados com `'panel' => 'uenf_panel'`. Nenhum arquivo ficou para tras.

**`class-design-panel-manager.php`:** Caso especial. A classe `Design_Panel_Manager` tinha como responsabilidade registrar o sub-painel de design. Com a consolidacao, `add_design_panel()` foi esvaziado e `get_panel_id()` agora retorna `'uenf_panel'`. O metodo `panel_exists()` usa `get_panel_id()` e continuara funcionando. Qualquer codigo externo que chamava `$design_panel_manager->get_panel_id()` obtera o ID correto. Sem regressao.

**`class-menu-customizer.php`:** A secao `menu_settings` (sem prefixo `cct_`) foi adicionada ao `uenf_panel` diretamente via `'panel' => 'uenf_panel'`. Isso significa que ela nao sera capturada pelo hook 999 (que so age em secoes sem painel com prefixo `cct_`) — correto, pois ja esta alocada.

---

## Conformidade com WordPress Coding Standards

### Itens em conformidade

- `ABSPATH` check em todos os arquivos de classe.
- `add_action` e `add_filter` com callbacks nomeados onde apropriado.
- `wp_json_encode()` para dados PHP -> JS.
- `wp_add_inline_style()` / `wp_add_inline_script()` com handles validos.
- `absint()`, `esc_url()`, `esc_html__()` presentes onde necessario.
- `capability => 'edit_theme_options'` no painel registrado.
- `sanitize_callback => '__return_empty_string'` nos settings dos grupos (adequado para settings que nunca sao salvos).
- Arquivos de classe com nome `class-nome-da-classe.php` (padrao WPCS).

### Itens que precisam atencao

1. **`error_log()` sem condicional `WP_DEBUG` em `customizer-loader.php`** (linha 68, 79): chamadas incondicionais de `error_log()` em producao. Devem ser encapsuladas em `if (WP_DEBUG)`.

2. **Fallbacks de funcoes WordPress em `inc/customizer.php`** (linhas 66-80): definicoes de `__()` e `esc_attr()` como fallback. Isso viola o principio de nao redefinir funcoes do core. Em contexto normal de carregamento WordPress, essas funcoes sempre existem. Esses fallbacks devem ser removidos.

3. **Namespace misto**: `class-design-panel-manager.php` usa `namespace UENF\CCT\Customizer`, enquanto todos os outros managers usam o padrao procedural sem namespace (ex.: `class CCT_Color_Manager`). Inconsistencia que complica o autoload e a leitura do codigo.

4. **Texto de UI nao internacionalizavel**: titulos como `'🎨 Cores'` e `'🌙 Modo Escuro'` nos grupos nao estao envoltos em `__()`. Embora o tema seja em portugues, e boa pratica usar `__( 'Cores', 'cct' )` para todos os strings de UI.

5. **Anonymous class sem docblock**: A anonymous class que estende `WP_Customize_Control` no hook 1001 nao possui docblock explicando o proposito. Para manutencao futura, um comentario inline e suficiente (ja existe um), mas uma named class seria mais idiomatica para WordPress.

6. **`$section->panel = 'uenf_panel'` via mutacao direta de propriedade**: O hook 999 acessa `$section->panel` diretamente. `WP_Customize_Section::$panel` e propriedade publica, entao nao ha problema tecnico, mas `$wp_customize->get_section($id)` retorna o objeto por referencia — a mutacao e efetiva. Documentar isso e recomendavel para evitar confusao.

---

## Riscos e Observacoes

1. **Risco: `setInterval` sem limpeza** — Performance degradada no Customizer com muitas secoes. Prioridade: media. Impacto: UX lento ao digitar na busca do Customizer. Solucao recomendada: `MutationObserver`.

2. **Risco: Duplo registro de `uenf_panel`** — Atualmente inofensivo (WordPress ignora o segundo `add_panel()`), mas e tecnica incorreta. Se a ordem dos hooks mudar, o segundo registro pode sobrescrever propriedades do primeiro (ex.: `priority` diferente). Remover o registro em `customizer-loader.php`.

3. **Risco: Secao `menu_settings` com ID sem prefixo `cct_`** — O hook 999 nao a captura por design (ja tem `panel`). O hook 1000 a renomeia para `'Menu'` com prioridade 502. OK. Porem se a secao nao for registrada (ex.: extensao desativada), o hook 1000 fara `get_section('menu_settings')` retornar `null` e o `if ($section)` absorvera silenciosamente. Sem regressao visivel.

4. **Risco: Compatibilidade de blocos apos remocao de `add_theme_support`** — Posts existentes com blocos que usam classes como `has-primary-color`, `has-large-font-size` precisam ser verificados. Se `theme.json` nao declara slugs identicos, essas classes CSS nao serao geradas e o styling do conteudo existente pode quebrar. **Este e o risco mais alto do conjunto de mudancas.**

5. **Risco: Servico externo `placehold.co` em `uenf_get_random_image()`** — Dependencia de terceiro em ambiente dev. Baixo impacto (so em `WP_DEBUG`), mas deve ser documentado.

6. **Observacao: `class-design-panel-manager.php` usa namespace, outros nao** — Se o tema evoluir para PSR-4 autoload, essa inconsistencia causara falha de carregamento. Alinhar para um padrao unico.

7. **Observacao: `error_log()` incondicional em producao** — `customizer-loader.php` tem `error_log()` nas linhas 68 e 79 fora de qualquer condicional. Em producao com `WP_DEBUG = false` e `WP_DEBUG_LOG = false`, o PHP nao grava o log (comportamento padrao), mas a chamada de funcao ainda ocorre. Com `WP_DEBUG_LOG = true` em producao (configuracao errada mas possivel), esses logs poluem o debug.log. Encapsular em `if (defined('WP_DEBUG') && WP_DEBUG)`.

---

## Contexto para os Demais Agentes

### Para todos os agentes

A estrutura do Customizer mudou. O painel raiz e agora `uenf_panel`. Qualquer referencia a `cct_shortcut_panel`, `cct_personalizando_panel`, `cct_forms_panel`, ou `cct_colors_panel` e obsoleta.

### Isabela (UX/Acessibilidade)

- A navegacao do Customizer ganhou grupos visuais com titulos em uppercase. Verificar se a hierarquia visual esta clara para o usuario administrador.
- Os grupos tem `pointer-events:none` — usuarios de teclado e leitores de tela nao conseguem "ativar" o grupo (esperado, e um cabecalho visual). Verificar se a ordem de tabulacao entre secoes-filho esta correta.
- A funcao `uenf_get_random_image()` so retorna URL em `WP_DEBUG`. Em producao retorna string vazia — componentes que usam essa funcao devem ter fallback visual adequado.

### Marina (Frontend/CSS)

- O CSS injetado via `wp_add_inline_style('customize-controls', $css)` so e carregado no contexto do Customizer (`/wp-admin/customize.php`), nao no frontend. Zero impacto no CSS do site publico.
- A remocao de `add_theme_support('editor-color-palette')` pode afetar classes CSS geradas automaticamente pelo WordPress para blocos (`has-*-color`). Verificar `theme.json` para garantir que os slugs das paletas batem com os usados nos blocos existentes.
- `add_editor_style('assets/dist/css/style.min.css')` permanece ativo — o CSS do tema e aplicado no editor.

### Camila (Gutenberg/Blocos)

- **Ponto critico:** `add_theme_support('editor-color-palette')` e `add_theme_support('editor-font-sizes')` foram removidos. As cores e tamanhos de fonte do editor de blocos agora vem exclusivamente de `theme.json`. Testar no editor: criar um bloco paragrafo, verificar se as cores e tamanhos aparecem corretamente na barra lateral.
- Verificar se algum block pattern usa `uenf_get_random_image()` — em producao a funcao retorna string vazia, o que pode resultar em `src=""` em tags `<img>`.

### Pedro (Performance)

- O `setInterval(800ms)` no Customizer JS e um ponto de atencao. Nao afeta o frontend publico, apenas a experiencia do administrador no Customizer. Para instalacos com muitas secoes ativas, pode causar jank ao digitar na busca. Recomendado substituir por `MutationObserver`.
- Os `require_once` dentro do hook `customize_register` adicionam latencia ao carregamento do Customizer. Ja era o padrao anterior, nao e regressao desta PR.

### Rafael (Backend/PHP)

- **Duplo registro de `uenf_panel`**: remover o `add_panel('uenf_panel', ...)` dentro de `CCT_Customizer_Loader::load_modules()` (linha 82 de `customizer-loader.php`). O registro canonico e o hook de prioridade 5 em `functions.php`.
- **`error_log()` incondicional** em `customizer-loader.php` linhas 68 e 79: encapsular em condicional `WP_DEBUG`.
- **Fallbacks de funcoes WordPress** em `inc/customizer.php`: remover as definicoes condicionais de `__()` e `esc_attr()`. Essas funcoes sempre existem no contexto WordPress.
- **Namespace misto**: decidir se o projeto adotara namespaces (PSR-4) ou nao, e alinhar `class-design-panel-manager.php` com o restante.

### Diego (QA)

Cenarios de teste prioritarios:

1. Abrir o Customizer: verificar que `uenf_panel` aparece e contem todos os grupos.
2. Verificar que grupos (`🎨 Cores`, etc.) nao sao clicaveis.
3. Verificar que secoes-filho estao indentadas e clicaveis.
4. Desativar uma extensao (ex.: `animations`) e verificar que o grupo `✨ Animacoes` desaparece do Customizer.
5. Editor de blocos: criar paragrafo e verificar paleta de cores (tema deve vir de `theme.json`).
6. Editor de blocos: verificar tamanhos de fonte disponiveis.
7. Frontend: verificar que posts existentes com blocos coloridos renderizam corretamente.
8. `WP_DEBUG = true`: chamar `uenf_get_random_image()` e verificar que retorna URL valida.
9. `WP_DEBUG = false`: chamar `uenf_get_random_image()` e verificar que retorna string vazia.

### Andre (DevOps/Deploy)

- Esta PR nao altera banco de dados, nao cria/remove opcoes do WordPress.
- Nao ha migracao necessaria.
- Recomendado limpar o cache de opcode (OPcache) apos deploy, pois varios arquivos PHP foram modificados.
- Verificar se o ambiente de producao tem `WP_DEBUG = false` e `WP_DEBUG_LOG = false` para evitar que os `error_log()` incondicionais em `customizer-loader.php` poluam logs.
