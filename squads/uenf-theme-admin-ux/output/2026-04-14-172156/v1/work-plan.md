# Plano de Trabalho — Tema UENF Admin UX
**Gerado em:** 2026-04-14
**Planejador:** Bruno (Work Planner)
**Baseado em:** Relatório de Auditoria UX — Ana (UX Auditor)

---

## Resumo do Plano

| Métrica | Valor |
|---------|-------|
| Total de tarefas | 20 |
| P0 — Crítico (bugs que quebram) | 4 |
| P1 — Alto (melhora significativamente) | 9 |
| P2 — Incremental (polimento) | 7 |
| Estimativa total | ~22h |
| Estimativa Fase 1 (P0) | ~4h |
| Estimativa Fase 2 (P1) | ~12h |
| Estimativa Fase 3 (P2) | ~6h |

---

## Fase 1 — Imediato (P0) · ~4h

### [P0-01] Corrigir fatal error no Reset de Extensões
**Área:** Admin UX — Reset  
**Arquivo(s):** `functions.php:2573`  
**Problema:** `$extension_manager->reset_all_extensions()` chamado, mas o método não existe. Clicar em "Resetar Extensões" causa PHP fatal error.  
**Solução:** Trocar a chamada para `$extension_manager->reset_all_settings()`, que é o método correto em `CCT_Extension_Manager`.  
**Critério de Done:** Clicar em "Resetar Extensões" executa sem erro e confirma sucesso.  
**Estimativa:** 15 min

---

### [P0-02] Corrigir nomes das extensões na tabela
**Área:** Admin UX — Extensões  
**Arquivo(s):** `functions.php:1454`  
**Problema:** O callback usa `$extension['title']` que não existe; exibe `ucfirst($id)` como fallback ("Dark_mode", "Search_customizer").  
**Solução:** Trocar `$extension['title']` por `$extension['name']` que é a chave correta no `CCT_Extension_Manager`.  
**Critério de Done:** A tabela exibe "🌙 Modo Escuro/Claro", "🔍 Sistema de Busca Personalizado", etc.  
**Estimativa:** 10 min

---

### [P0-03] Criar GUIA-CONFIGURACAO-DESIGN.md
**Área:** Documentação  
**Arquivo(s):** `/GUIA-CONFIGURACAO-DESIGN.md` (criar na raiz)  
**Problema:** Arquivo não existe; página "Documentação Design" exibe apenas mensagem de erro. É a primeira impressão da documentação do tema.  
**Solução:** Criar o arquivo com conteúdo mínimo funcional (estrutura, introdução, principais seções do Customizer). A Clara (Doc Writer) produz o conteúdo completo como entregável desta squad.  
**Critério de Done:** A página "Documentação Design" exibe conteúdo ao invés de mensagem de erro.  
**Estimativa:** Executado na Fase de Documentação desta squad (Step 4)

---

### [P0-04] Corrigir verificação de nonce no Reset
**Área:** Admin UX — Reset / Segurança  
**Arquivo(s):** `functions.php:2573`, `inc/extensions/class-extension-manager.php:579`  
**Problema:** O formulário de reset envia `$_POST['reset_nonce']` mas `reset_all_settings()` verifica `$_POST['nonce']`. O check falha silenciosamente — a ação é executada mas o nonce não protege efetivamente.  
**Solução:** Duas opções: (a) Renomear o campo do form de `reset_nonce` para `nonce`, ou (b) adaptar `reset_all_settings()` para aceitar o nome correto via parâmetro. Opção (a) é mais simples.  
**Critério de Done:** O nonce é verificado corretamente e rejeitado se adulterado.  
**Estimativa:** 20 min

---

## Fase 2 — Próximo Sprint (P1) · ~12h

### [P1-01] Corrigir seções órfãs de cores no Customizer
**Área:** Customizer  
**Arquivo(s):** `inc/customizer.php:557–597, 990–995`  
**Problema:** `cct_main_colors`, `cct_text_colors`, `cct_menu_colors` apontam para `cct_colors_panel` que está comentado/inexistente. Ficam como seções soltas, dependendo do hook de migração de prioridade 999.  
**Solução:** Alterar `'panel' => 'cct_colors_panel'` para `'panel' => 'uenf_panel'` diretamente nessas seções. Remover `cct_colors_panel` da lista `$legacy_panels` em `functions.php:359`.  
**Critério de Done:** Seções de cores aparecem diretamente em `uenf_panel` sem depender do hook de migração.  
**Estimativa:** 45 min

---

### [P1-02] Mover cct_backup_section para fora do foreach
**Área:** Customizer  
**Arquivo(s):** `inc/customizer.php:1004–1075`  
**Problema:** Seção de Backup/Restore registrada 4 vezes dentro do loop `foreach ($menu_states)`. Controles também registrados 4x. WordPress deduplication previne crash mas gera inconsistências.  
**Solução:** Extrair o bloco de `cct_backup_section` + seus controles para fora do `foreach`, registrando uma única vez.  
**Critério de Done:** Seção de Backup aparece uma única vez no Customizer, sem duplicatas no código.  
**Estimativa:** 1h

---

### [P1-03] Unificar carregamento de módulos do Customizer
**Área:** Técnico — Customizer  
**Arquivo(s):** `functions.php:162–353`, `inc/customizer/customizer-loader.php`  
**Problema:** Dois sistemas de carregamento em paralelo (manual em `functions.php` + `CCT_Customizer_Loader`) causam `require_once` duplos e risco de registros duplicados.  
**Solução:** Manter apenas o `CCT_Customizer_Loader` como sistema oficial. Remover os `require_once` manuais de `functions.php` que estão dentro do hook `customize_register` (prioridade 15). Verificar que o Loader cobre todos os módulos.  
**Critério de Done:** Customizer carrega sem warnings de "already declared" e sem seções duplicadas.  
**Estimativa:** 2h (inclui verificação de que nada quebrou)

---

### [P1-04] Resolver painel de extensões no Customizer
**Área:** Customizer — Extensões  
**Arquivo(s):** `inc/extensions/class-extension-manager.php:298–414`  
**Problema:** O painel `cct_theme_uenf` existe no Customizer mas não tem controles — o código foi comentado. Usuário vê um painel vazio sem utilidade.  
**Solução (opção A):** Reativar e revisar o código comentado de controles de extensões no Customizer.  
**Solução (opção B — mais rápida):** Remover o painel vazio `cct_theme_uenf` e `cct_active_extensions` do Customizer. Gerenciamento de extensões fica apenas na página admin (onde já funciona bem).  
**Critério de Done:** Não há painel vazio no Customizer. Se opção A: painel funcional com toggle de extensões.  
**Estimativa:** 1h (opção B) ou 4h (opção A)  
**Recomendação:** Opção B agora, opção A em sprint futuro.

---

### [P1-05] Adicionar agrupamento por categoria nas extensões
**Área:** Admin UX — Extensões  
**Arquivo(s):** `functions.php:1439–1466`  
**Problema:** 13 extensões em lista plana. Usuário não vê relação entre elas. Campo `category` já existe nos dados mas não é usado na UI.  
**Solução:** Agrupar as extensões por categoria no HTML da tabela, adicionando cabeçalhos de grupo (Design, Layout, Tipografia, Efeitos, Interface, Conteúdo). Usar os dados do campo `category` já disponíveis.  
**Critério de Done:** Tabela exibe extensões agrupadas por categoria com título de seção.  
**Estimativa:** 1.5h

---

### [P1-06] Corrigir parser Markdown da página de Documentação
**Área:** Admin UX — Documentação  
**Arquivo(s):** `functions.php:827–837`  
**Problema:** (a) Flag `/s` na regex de `<ul>` cria listas aninhadas incorretas; (b) headings não geram IDs automáticos, quebrando o smooth scroll/navegação interna.  
**Solução:** (a) Remover flag `/s` da regex de listas; (b) Modificar a substituição de `h1-h4` para adicionar `id="{slug-do-heading}"` automaticamente usando `sanitize_title()`.  
**Critério de Done:** Documentação renderiza listas corretamente; links de navegação no header saltam para as seções corretas.  
**Estimativa:** 1h

---

### [P1-07] Padronizar visual da página Reset com restante do painel
**Área:** Admin UX — Reset  
**Arquivo(s):** `functions.php:2591`  
**Problema:** Página de Reset usa `.wrap` padrão do WordPress enquanto Extensões e Documentação têm header com gradiente, cards e visual moderno.  
**Solução:** Aplicar o mesmo wrapper HTML/CSS das outras páginas admin (header com gradiente, cards para cada tipo de reset).  
**Critério de Done:** Página Reset tem visual consistente com Extensões e Documentação.  
**Estimativa:** 1.5h

---

### [P1-08] Adicionar link de Documentação no Acesso Rápido
**Área:** Admin UX — Página Principal  
**Arquivo(s):** `functions.php:774–778`  
**Problema:** O card "Acesso Rápido" tem apenas 2 botões (Extensões, Personalizar). A Documentação existe como submenu mas não aparece nos atalhos.  
**Solução:** Adicionar terceiro botão "Documentação" no card de Acesso Rápido, apontando para `admin.php?page=tema-uenf-docs-design`.  
**Critério de Done:** Usuário acessa Documentação diretamente da página principal do painel.  
**Estimativa:** 20 min

---

### [P1-09] Criar GUIA-USUARIO-ADMIN.md
**Área:** Documentação  
**Arquivo(s):** `/GUIA-USUARIO-ADMIN.md` (criar na raiz)  
**Problema:** Não existe documentação de usuário para administradores do site.  
**Solução:** Clara (Doc Writer) produz o guia completo como parte desta squad (Step 4). Após aprovação, colocar na raiz do tema.  
**Critério de Done:** Arquivo criado com todas as seções do painel admin e Customizer documentadas em linguagem acessível.  
**Estimativa:** Executado na Fase de Documentação desta squad (Step 4)

---

## Fase 3 — Backlog (P1 + P2) · ~6h

### [P2-01] Mover CSS inline da documentação para arquivo enfileirado
**Área:** Técnico — Performance  
**Arquivo(s):** `functions.php:843–1074`  
**Problema:** 232 linhas de CSS inline no callback PHP da documentação. Sem cache, sem possibilidade de override.  
**Solução:** Criar `css/admin/docs-page.css`, enfileirar com `wp_enqueue_style()` no hook `admin_enqueue_scripts` apenas quando a página for `tema-uenf-docs-design`.  
**Critério de Done:** CSS da documentação em arquivo separado, enfileirado condicionalmente.  
**Estimativa:** 45 min

---

### [P2-02] Corrigir dica de caminho errada em Extensões
**Área:** Admin UX — Extensões  
**Arquivo(s):** `functions.php:1421`  
**Problema:** Card "Acesso Rápido" nas Extensões diz "Gerenciador de Extensões → Tema UENF no Customizer", mas esse painel está comentado/vazio.  
**Solução:** Remover o card de "Acesso Rápido" com dica errada, ou atualizar para refletir a realidade (extensões apenas via página admin).  
**Critério de Done:** Nenhuma dica incorreta sobre o Customizer na página de Extensões.  
**Estimativa:** 15 min

---

### [P2-03] Padronizar emoji nos nomes das extensões
**Área:** Admin UX — Extensões  
**Arquivo(s):** `inc/extensions/class-extension-manager.php:88–194`  
**Problema:** Algumas extensões têm emoji no `name` (🎯, 🎨, 🔍), outras não (Modo Escuro, Sombras, Breakpoints).  
**Solução:** Adicionar emoji coerentes a todas as extensões que não têm.  
**Critério de Done:** Todas as 13 extensões têm emoji no `name`.  
**Estimativa:** 20 min

---

### [P2-04] Adicionar botão "Fazer backup antes de resetar"
**Área:** Admin UX — Reset  
**Arquivo(s):** `functions.php:2591–2643`  
**Problema:** A ação de reset é irreversível mas não há botão de backup imediato nessa página.  
**Solução:** Adicionar link/botão no topo da página de Reset que direciona para a seção de Backup no Customizer (`customize.php?autofocus[section]=cct_backup_section`).  
**Critério de Done:** Usuário tem acesso ao backup com 1 clique antes de resetar.  
**Estimativa:** 30 min

---

### [P2-05] Remover código morto (get_performance_status, referência customizer.php)
**Área:** Técnico — Manutenção  
**Arquivo(s):** `inc/extensions/class-extension-manager.php:475`, `class-extension-manager.php:137`  
**Problema:** Método `get_performance_status()` privado nunca chamado + referência ao `customizer.php` no campo `file` de `font_combinations`.  
**Solução:** Remover método não usado + corrigir/remover campo `file` da extensão `font_combinations`.  
**Critério de Done:** Sem métodos privados não chamados, sem referências a arquivos incorretos.  
**Estimativa:** 20 min

---

### [P2-06] Criar GUIA-DESENVOLVEDOR.md
**Área:** Documentação — Devs  
**Arquivo(s):** `/GUIA-DESENVOLVEDOR.md` (criar na raiz)  
**Problema:** Não existe documentação técnica para desenvolvedores/mantenedores.  
**Solução:** Clara (Doc Writer) produz o outline completo como parte desta squad (Step 4). Conteúdo completo em sprint futuro.  
**Critério de Done:** Arquivo criado com estrutura de diretórios, hooks disponíveis, como criar extensão.  
**Estimativa:** Executado na Fase de Documentação desta squad (Step 4)

---

### [P2-07] Rever indicador de "Performance" na página principal
**Área:** Admin UX — Página Principal  
**Arquivo(s):** `functions.php:799–808`  
**Problema:** Métrica de "performance = % extensões ativas" é enganosa — uma extensão pesada ativa pesa mais que cinco leves.  
**Solução:** Mudar indicador para "Extensões ativas: N/13" sem qualificador de performance, ou calcular peso real baseado em scripts/styles enfileirados.  
**Critério de Done:** Indicador não induz o usuário a conclusões incorretas sobre performance.  
**Estimativa:** 45 min

---

## Tabela Resumo

| # | Título | Prioridade | Área | Estimativa | Status |
|---|--------|-----------|------|-----------|--------|
| P0-01 | Corrigir fatal error Reset de Extensões | P0 | Admin — Reset | 15 min | ⬜ Pendente |
| P0-02 | Corrigir nomes das extensões na tabela | P0 | Admin — Extensões | 10 min | ⬜ Pendente |
| P0-03 | Criar GUIA-CONFIGURACAO-DESIGN.md | P0 | Documentação | Esta squad | 🔄 Em andamento |
| P0-04 | Corrigir nonce inconsistente no Reset | P0 | Segurança | 20 min | ⬜ Pendente |
| P1-01 | Corrigir seções órfãs de cores Customizer | P1 | Customizer | 45 min | ⬜ Pendente |
| P1-02 | Mover backup_section para fora do foreach | P1 | Customizer | 1h | ⬜ Pendente |
| P1-03 | Unificar carregamento módulos Customizer | P1 | Técnico | 2h | ⬜ Pendente |
| P1-04 | Resolver painel extensões vazio Customizer | P1 | Customizer | 1h | ⬜ Pendente |
| P1-05 | Agrupar extensões por categoria | P1 | Admin — Extensões | 1.5h | ⬜ Pendente |
| P1-06 | Corrigir parser Markdown documentação | P1 | Admin — Docs | 1h | ⬜ Pendente |
| P1-07 | Padronizar visual página Reset | P1 | Admin — Reset | 1.5h | ⬜ Pendente |
| P1-08 | Adicionar Docs no Acesso Rápido | P1 | Admin — Principal | 20 min | ⬜ Pendente |
| P1-09 | Criar GUIA-USUARIO-ADMIN.md | P1 | Documentação | Esta squad | 🔄 Em andamento |
| P2-01 | Mover CSS inline docs para arquivo | P2 | Técnico | 45 min | ⬜ Pendente |
| P2-02 | Corrigir dica errada em Extensões | P2 | Admin — Extensões | 15 min | ⬜ Pendente |
| P2-03 | Padronizar emoji nas extensões | P2 | Admin — Extensões | 20 min | ⬜ Pendente |
| P2-04 | Botão backup antes de resetar | P2 | Admin — Reset | 30 min | ⬜ Pendente |
| P2-05 | Remover código morto | P2 | Técnico | 20 min | ⬜ Pendente |
| P2-06 | Criar GUIA-DESENVOLVEDOR.md | P2 | Documentação | Esta squad | 🔄 Em andamento |
| P2-07 | Rever indicador de performance | P2 | Admin — Principal | 45 min | ⬜ Pendente |

---

*Plano gerado com base na auditoria real do código-fonte. Tarefas P0 podem ser implementadas em < 1h total.*
