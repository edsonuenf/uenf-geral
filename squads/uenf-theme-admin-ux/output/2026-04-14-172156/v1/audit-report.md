# Relatório de Auditoria UX — Painel Admin Tema UENF
**Data:** 2026-04-14
**Auditor:** Ana (UX Auditor)
**Escopo:** Painel wp-admin + Customizer WordPress

---

## Resumo Executivo

O painel admin do Tema UENF apresenta uma interface visualmente consistente e bem estilizada, com cards modernos, gradientes e feedbacks visuais. A maior parte do trabalho de UX do Customizer está bem fundamentado: a estrutura de grupos com indentação em `uenf_panel` é sofisticada e funcional. No entanto, existem **três problemas críticos** que comprometem diretamente a experiência do usuário: (1) a página "Documentação Design" está em branco por falta do arquivo `GUIA-CONFIGURACAO-DESIGN.md`; (2) a função `reset_all_extensions()` chamada na página de Reset não existe no `CCT_Extension_Manager`, causando erro fatal ao clicar em "Resetar Extensões"; (3) a tabela de extensões exibe `ucfirst($id)` como título quando a chave `title` não existe, pois os dados do gerenciador usam a chave `name`. Esses três bugs são funcionais e afetam usuários reais. Além disso, há inconsistências estruturais no Customizer — painéis legados, seções duplicadas e código morto — que aumentam a dívida técnica.

---

## 1. Painel Admin (wp-admin)

### 1.1 Página Principal (`cct_admin_page_callback`)

**Localização:** `functions.php` linhas 765–814

**O que funciona bem:**
- Cards claros com separação visual entre "Acesso Rápido" e "Status do Sistema".
- O "Status do Sistema" exibe contador de extensões ativas vs. total e um indicador de performance por percentual (verde/amarelo/laranja/vermelho). Boa ideia de onboarding.

**Problemas identificados:**

[ALTO] Falta de contexto para o indicador de performance | `functions.php:799` | O usuário vê "Performance: Excelente" mas não sabe **o que isso significa na prática** (ex: "2 de 13 extensões ativas carregam menos JavaScript"). A métrica de performance baseada em % de extensões ativas é questionável — uma extensão pesada ativa pesa mais que cinco leves. O indicador pode enganar o usuário.

[MÉDIO] Nenhum link para "Documentação Design" na página principal | `functions.php:774` | O Acesso Rápido tem dois botões (Extensões e Personalizar). A documentação, que seria o guia de onboarding, não aparece ali. Um novo usuário não vai descobri-la naturalmente.

[BAIXO] Emojis hardcoded no HTML gerado via PHP | `functions.php:769,774,776` | Títulos como `🎓 Tema UENF` e botões com emoji podem ser cortados ou mal renderizados em leitores de tela e em certas versões do Windows. O menu (line 713) foi corrigido — o callback ainda não.

[BAIXO] Sem link de "Documentação" no card de Acesso Rápido | `functions.php:774–778` | Existe submenu "Documentação Design" registrado (line 742) mas ele não aparece nos atalhos rápidos do painel principal.

---

### 1.2 Gerenciador de Extensões (`cct_extensions_page_callback`)

**Localização:** `functions.php` linhas 1157–1529

**O que funciona bem:**
- Visual moderno com estatísticas (total/ativas/inativas/taxa de utilização).
- Seleção em massa com botões "Selecionar Todas" / "Desmarcar Todas".
- Feedback visual nos checkboxes (fundo verde ao marcar).
- Nonce presente para segurança no POST.

**Problemas identificados:**

[CRITICO] Chave `title` não existe nas extensões — callback usa `$extension['title']`, gerenciador armazena `$extension['name']` | `functions.php:1454` vs `inc/extensions/class-extension-manager.php:88` | Na tabela de extensões, o nome exibido é sempre `ucfirst($id)` (ex: "Icons", "Dark_mode") em vez do nome real da extensão. O usuário vê nomes truncados e técnicos, sem contexto.

**Evidência do bug:**
```php
// functions.php linha 1454
$title = isset($extension['title']) ? $extension['title'] : ucfirst($id);
// class-extension-manager.php — extensões usam 'name', não 'title'
'name' => '🎯 Sistema de Ícones',
```

[ALTO] A seção "Acesso Rápido" dentro da página de extensões é redundante | `functions.php:1415` | O card "Acesso Rápido" redireciona para o Customizer com a dica de navegar em `Tema UENF → Gerenciador de Extensões`. Porém esse caminho no Customizer está **comentado e removido** (`class-extension-manager.php:298–307`). A dica está errada.

[MÉDIO] Sem agrupamento por categoria das extensões | `class-extension-manager.php:86–195` | As extensões têm campo `category` (design, layout, typography, effects, interface, content) mas na tabela todas aparecem em ordem plana. Um usuário que não conhece o tema não entende quais extensões são relacionadas.

[MÉDIO] Sem descrição do impacto de ativar cada extensão | Geral | Extensões como "Responsive Breakpoints" e "Design Tokens" têm descrições em inglês técnico ("Gerenciador de pontos de quebra customizáveis", "Sistema de tokens de design centralizados") que não comunicam ao usuário final **o que muda no site** quando ativadas.

[BAIXO] Botão de salvar em `input[type=submit]` com classe custom, fora do padrão WordPress | `functions.php:1469` | Mistura de classes custom (`cct-btn-save`) com `input[type=submit]` quebra o padrão de acessibilidade do wp-admin (deveria ser `button[type=submit]`).

---

### 1.3 Reset de Configurações (`cct_reset_page_callback`)

**Localização:** `functions.php` linhas 2550–2644

**O que funciona bem:**
- Nonce presente para todos os POST forms.
- Confirm dialogs JavaScript antes de cada ação destrutiva.
- Três níveis de reset (tema, extensões, completo) bem separados.

**Problemas identificados:**

[CRITICO] `$extension_manager->reset_all_extensions()` chamado na linha 2573, mas esse método **não existe** em `CCT_Extension_Manager` | `functions.php:2573` vs `inc/extensions/class-extension-manager.php` | O método existente é `reset_all_settings()` (linha 577 do class-extension-manager.php). Ao clicar em "Resetar Extensões", o PHP retorna fatal error: chamada a método inexistente. O formulário exibe mensagem de erro em vez de confirmação.

**Evidência:**
```php
// functions.php linha 2573
$result = $extension_manager->reset_all_extensions(); // MÉTODO NÃO EXISTE

// class-extension-manager.php linha 577
public function reset_all_settings() { ... } // MÉTODO CORRETO
```

[ALTO] O método `reset_all_settings()` da classe `CCT_Extension_Manager` (linha 579) verifica `$_POST['nonce']` internamente, mas o formulário na página admin não envia esse campo `nonce` — envia `reset_nonce` | `functions.php:2605,2615,2625` | A verificação de nonce dentro de `reset_all_settings()` vai falhar silenciosamente (retorna `false`) mesmo que o form seja submetido corretamente. Double nonce check inconsistente.

[ALTO] Sem opção de "Exportar/Fazer backup antes de resetar" na página de reset | `functions.php:2591–2643` | O aviso "Esta ação não pode ser desfeita" está presente, mas não há um botão de backup imediato nessa página. O backup está em outra seção (Customizer → Backup). Um usuário que quer resetar mas quer um backup primeiro precisa navegar para outro lugar.

[MÉDIO] Formulários separados sem feedback visual pós-reset além da mensagem padrão | `functions.php:2586–2588` | A mensagem de sucesso usa `notice-success` padrão do WordPress (sem styling customizado como na página de extensões). Inconsistência visual com o restante do painel.

[MÉDIO] Página usa `.wrap` padrão do WordPress enquanto as outras usam cards customizados | `functions.php:2591` | Inconsistência visual: Extensões tem UI moderna com gradiente no header; Reset tem o layout padrão genérico do wp-admin.

---

### 1.4 Documentação Design (`cct_docs_design_page_callback`)

**Localização:** `functions.php` linhas 819–1152

**O que funciona bem:**
- O parser Markdown para HTML é funcional e bem estilizado (h1-h4, listas, blockquotes, code).
- Sanitização com `wp_kses()` presente (linha 1112).
- Header, nav e footer com visual atrativo e consistente.

**Problemas identificados:**

[CRITICO] Arquivo `GUIA-CONFIGURACAO-DESIGN.md` não existe | `functions.php:821` | A página exibe apenas: `"Arquivo de documentação não encontrado."` dentro de um bloco de erro estilizado. O header da página promete "Guia completo e interativo para personalizar o visual do seu site" mas o conteúdo está vazio. Para um novo usuário, essa é a primeira impressão da documentação do tema.

[ALTO] Parser Markdown customizado com bugs potenciais | `functions.php:827–837` | O parser usa `preg_replace` sequencial sem lidar com escapes, nested elements, ou linhas que começam com `#` dentro de code blocks. A regex para listas (`<ul>`) está incorreta — captura com flag `/s` (dotall) mas a substituição envolve apenas o match, criando `<ul>` aninhados incorretos quando há múltiplas listas.

[MÉDIO] Smooth scroll JavaScript sem fallback para âncoras | `functions.php:1127–1138` | O script de smooth scroll funciona, mas como o arquivo Markdown não existe, não há âncoras para navegar. Quando o arquivo existir, os `href="#..."` precisarão de IDs correspondentes nos headings — o parser atual não gera IDs automáticos nos headings.

[MÉDIO] Seção "Próximos Passos" no footer da documentação tem conteúdo hardcoded genérico | `functions.php:1116–1123` | "Agora que você conhece as opções de personalização, comece a criar seu design único!" — não há conteúdo para o usuário conhecer, pois a documentação está vazia.

[BAIXO] CSS inline extenso (232 linhas) dentro do callback | `functions.php:843–1074` | Todo o CSS da página de documentação está embutido no callback via `<style>`. Deveria ser enfileirado com `wp_enqueue_style()` para permitir cache e override.

---

## 2. Customizer

### 2.1 Organização e Hierarquia

**Arquivos:** `functions.php` (linha 152–703), `inc/customizer.php`, `inc/customizer/*.php`

**O que funciona bem:**
- Consolidação de tudo sob `uenf_panel` (linha 153) com prioridade controlada.
- Sistema de grupos-cabeçalho (`uenf_group_*`) não-clicáveis com indentação visual é uma solução elegante para uma árvore com 16 grupos e ~47 seções.
- Atualização dinâmica de visibilidade dos grupos via `setInterval(updateGroupVisibility, 200)` funciona bem com a busca nativa do Customizer.
- Acessibilidade básica presente: `aria-hidden`, `tabindex="-1"`, `role="heading"`.

**Problemas identificados:**

[ALTO] Dois sistemas de carregamento de módulos em paralelo, sem coordenação clara | `functions.php:162–353` e `inc/customizer/customizer-loader.php` | O `functions.php` carrega manualmente os arquivos PHP de cada módulo (tipografia, cores, ícones, layout, etc.) via `require_once` em um closure de `customize_register` com prioridade 15. O `CCT_Customizer_Loader` faz o mesmo via `load_modules()` com hook em `customize_register` na sua própria prioridade. Resultado: múltiplos `require_once` redundantes sem certeza de qual sistema "vence". Isso pode causar duplo registro de seções/painéis.

[ALTO] Seções `cct_main_colors`, `cct_text_colors`, e `cct_menu_colors` criadas com `'panel' => 'cct_colors_panel'` que está **comentado e não registrado** | `inc/customizer.php:558,597,993` | Essas seções ficam órfãs no carregamento. O hook de prioridade 999 em `functions.php` tenta mover seções dos painéis legados para `uenf_panel`, mas `cct_colors_panel` está na lista `$legacy_panels` (linha 359). A seção só aparecer no painel correto depende de uma correção post-hoc em vez de estar certa desde o início.

[MÉDIO] Seção `cct_backup_section` criada **dentro** do `foreach ($menu_states as $state => $label)` | `inc/customizer.php:1004–1075` | A seção de Backup/Restore é registrada 4 vezes (uma para cada estado do menu: link, active, hover, selected). O WordPress deduplica `add_section` com o mesmo ID, então só uma versão persiste, mas controles dentro do loop também são afetados — `cct_export_settings`, `cct_import_settings` e `cct_restore_settings` são registrados 4 vezes cada. Bug de indentação que causa registros duplicados.

[MÉDIO] Seção `typography_section` registrada duas vezes com configurações diferentes | `inc/customizer.php:1085` (sem painel, priority 30) e `functions.php:396–402` (move para `uenf_panel`) | A seção é criada pelo arquivo legado e depois movida por um hook separado. Funciona, mas é frágil: se a ordem dos hooks mudar, a seção fica fora do painel.

[MÉDIO] Módulo `cct_colors_panel` está comentado em `customizer.php:297` mas a variável continua sendo referenciada no hook de migração `$legacy_panels` | `functions.php:359` | `cct_colors_panel` nunca é registrado, mas o `remove_panel('cct_colors_panel')` é chamado. Não causa erro, mas é código desnecessário que confunde quem for manter.

[BAIXO] Intervalos de 200ms sem `requestAnimationFrame` para `updateGroupVisibility` | `functions.php:689` | O `setInterval` a 200ms é aceitável para a busca do Customizer, mas há um comentário incorreto: "Intervalo reduzido a 200ms para feedback imediato na busca do Customizer" — 200ms é o intervalo atual, sem comparativo claro de qual era o anterior.

---

### 2.2 Extensões no Customizer

**Arquivo:** `inc/extensions/class-extension-manager.php`

**O que funciona bem:**
- Defaults claros: `icons`, `colors`, `search_customizer` ativos; restantes inativos.
- Painel `cct_theme_uenf` ("Tema UENF") criado no Customizer com painel filho `cct_active_extensions`.
- Mecanismo de dependências presente (campo `dependencies` em cada extensão).

**Problemas identificados:**

[ALTO] O painel do Gerenciador de Extensões no Customizer (`cct_extensions`) está **completamente comentado** | `class-extension-manager.php:298–414` | O método `add_customizer_controls()` registra apenas os painéis vazios `cct_theme_uenf` e `cct_active_extensions` sem nenhum controle funcional. Todo o código de controles individuais por extensão está comentado. No Customizer, o painel "Tema UENF" existe mas não tem seções visíveis para controle de extensões — o usuário não consegue ativar/desativar extensões via Customizer (apenas via página admin de Extensões).

[MÉDIO] Emoji no campo `name` de algumas extensões mas não de outras | `class-extension-manager.php:88–194` | Extensões como "Ícones" e "Cores" têm emoji no `name` (`'🎯 Sistema de Ícones'`), mas "Modo Escuro", "Sombras", "Breakpoints", etc., não têm. Inconsistência visual que afeta qualquer interface que exiba os nomes.

[MÉDIO] Extensão `font_combinations` referencia `'file' => 'customizer.php'` como arquivo de classe | `class-extension-manager.php:137` | O arquivo `customizer.php` contém milhares de linhas e não é o arquivo de classe da extensão. O campo `file` não é utilizado no carregamento atual, mas é confuso e potencialmente problemático se for usado no futuro.

[BAIXO] `get_performance_status()` é um método privado que nunca é chamado | `class-extension-manager.php:475` | Código morto. A mesma lógica está duplicada em `functions.php:799–808` (inline no callback da página principal).

---

## 3. Tarefas Pendentes Identificadas

| # | Tarefa | Arquivo | Prioridade Sugerida |
|---|--------|---------|---------------------|
| 1 | Criar `GUIA-CONFIGURACAO-DESIGN.md` com conteúdo mínimo para a página de documentação não ficar em branco | Raiz do tema | **CRÍTICA** |
| 2 | Corrigir chamada `reset_all_extensions()` → `reset_all_settings()` na página de Reset | `functions.php:2573` | **CRÍTICA** |
| 3 | Corrigir chave `$extension['title']` → `$extension['name']` na tabela de extensões | `functions.php:1454` | **CRÍTICA** |
| 4 | Corrigir nonce inconsistente: `reset_all_settings()` espera `$_POST['nonce']` mas form envia `$_POST['reset_nonce']` | `functions.php:2573` / `class-extension-manager.php:579` | **ALTA** |
| 5 | Mover `cct_main_colors`, `cct_text_colors`, `cct_menu_colors` para `uenf_panel` diretamente (remover dependência do painel comentado `cct_colors_panel`) | `inc/customizer.php:557–597, 990–995` | **ALTA** |
| 6 | Mover `cct_backup_section` e seus controles para fora do `foreach ($menu_states)` | `inc/customizer.php:1004–1075` | **ALTA** |
| 7 | Remover carregamento duplo de módulos (ou unificar em CCT_Customizer_Loader, ou em functions.php) | `functions.php:162–353` + `inc/customizer/customizer-loader.php` | **ALTA** |
| 8 | Reativar controles individuais de extensões no Customizer (ou remover o painel vazio `cct_theme_uenf`) | `inc/extensions/class-extension-manager.php:298–414` | **ALTA** |
| 9 | Adicionar agrupamento por categoria na tabela de extensões da página admin | `functions.php:1439–1466` | **MÉDIA** |
| 10 | Corrigir parser Markdown: remover flag `/s` na regex de `<ul>` e adicionar geração de IDs em headings | `functions.php:835` | **MÉDIA** |
| 11 | Adicionar botão "Fazer backup agora" na página de Reset antes das ações destrutivas | `functions.php:2591–2643` | **MÉDIA** |
| 12 | Padronizar visual da página de Reset com o restante do painel (header com gradiente, cards) | `functions.php:2591` | **MÉDIA** |
| 13 | Adicionar link para "Documentação Design" no card de Acesso Rápido da página principal | `functions.php:774–778` | **MÉDIA** |
| 14 | Mover CSS inline da página de documentação (232 linhas) para arquivo enfileirado com `wp_enqueue_style` | `functions.php:843–1074` | **BAIXA** |
| 15 | Padronizar emoji nos nomes das extensões (todos ou nenhum) | `class-extension-manager.php:88–194` | **BAIXA** |
| 16 | Remover método `get_performance_status()` não utilizado (código morto) | `class-extension-manager.php:475–487` | **BAIXA** |
| 17 | Remover referência obsoleta ao arquivo `customizer.php` no campo `file` da extensão `font_combinations` | `class-extension-manager.php:137` | **BAIXA** |

---

## 4. Pontos Positivos

1. **Estrutura modular do Customizer** — A divisão em 30+ arquivos de classe (`class-*.php`) com separação entre Manager e Controls é uma arquitetura sólida. Cada extensão tem seu próprio gerenciador de seções e controles, facilitando manutenção.

2. **Sistema de grupos-cabeçalho** no Customizer (`uenf_group_*`) — A solução de usar seções não-clicáveis como rótulos de grupo, com CSS de indentação e JavaScript de visibilidade dinâmica, é elegante e funcional. O resultado visual é próximo de um componente `<details>`/`<summary>` sem requerer custom controls complexos.

3. **Segurança bem implementada** — Nonces em todos os formulários, `wp_kses()` no output da documentação, `esc_html()` e `esc_attr()` consistentes na tabela de extensões, verificação de `manage_options` no callback de Reset.

4. **Feedback visual de performance** na página principal — O indicador de "% de extensões ativas → performance" é uma ideia interessante de onboarding que incentiva o usuário a usar apenas o que precisa.

5. **Design visual consistente** entre página de Extensões e página de Documentação — Header com gradiente azul-roxo, cards brancos com sombra suave, bordas arredondadas e tipografia clara. A identidade visual do painel é coesa.

6. **Defaults corretos por padrão** — Apenas 3 extensões ativas por padrão (`icons`, `colors`, `search_customizer`) é uma decisão sensata que mantém o site rápido na instalação inicial.

---

## 5. Resumo de Issues

| Crítico | Alto | Médio | Baixo |
|---------|------|-------|-------|
| 3 | 6 | 9 | 5 |

### Issues por arquivo

**`functions.php`**
- [CRÍTICO] `reset_all_extensions()` inexistente → fatal error no Reset de Extensões | `linha 2573` | Impacto: reset de extensões quebrado
- [CRÍTICO] `$extension['title']` → exibe `ucfirst($id)` em vez do nome real | `linha 1454` | Impacto: nomes técnicos e sem contexto na tabela
- [ALTO] Carregamento duplo de módulos do Customizer | `linhas 162–353` | Impacto: registros duplicados de seções/controles
- [ALTO] Dica de caminho errada no "Acesso Rápido" de Extensões | `linha 1421` | Impacto: usuário não encontra extensões no Customizer
- [MÉDIO] Backup/Restore criado dentro de `foreach` → 4x registros duplicados | `linhas 1010–1068` | Impacto: comportamento inesperado de controles
- [MÉDIO] Sem link para Documentação no Acesso Rápido | `linha 774` | Impacto: feature descoberta tardia

**`inc/customizer.php`**
- [ALTO] `cct_main_colors`, `cct_text_colors`, `cct_menu_colors` orphans com painel comentado | `linhas 558, 597, 993` | Impacto: seções dependem de correção post-hoc
- [MÉDIO] `typography_section` criada sem painel e movida em hook separado | `linha 1085` | Impacto: frágil a mudanças de prioridade

**`inc/extensions/class-extension-manager.php`**
- [ALTO] `reset_all_settings()` checa `$_POST['nonce']` mas form envia `$_POST['reset_nonce']` | `linha 579` | Impacto: nonce check silenciosamente falha
- [ALTO] Painel de extensões no Customizer completamente comentado | `linhas 298–414` | Impacto: usuário não controla extensões pelo Customizer
- [MÉDIO] Inconsistência de emoji nos nomes das extensões | `linhas 88–194` | Impacto: visual inconsistente

**Raiz do tema**
- [CRÍTICO] `GUIA-CONFIGURACAO-DESIGN.md` ausente | `(arquivo não existe)` | Impacto: página de Documentação Design em branco

---

*Relatório gerado com investigação real do código-fonte. Todos os números de linha referem-se ao estado do repositório em 2026-04-14, branch `aparencia`.*
