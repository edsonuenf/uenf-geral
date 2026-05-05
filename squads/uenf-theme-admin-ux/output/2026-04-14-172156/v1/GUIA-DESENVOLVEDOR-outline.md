# Outline — GUIA-DESENVOLVEDOR.md (Esboço)

> **Status:** Outline aprovado · Conteúdo completo planejado para P2 do work-plan (tarefa P2-06)
> **Gerado por:** Clara (Doc Writer) · Squad uenf-theme-admin-ux · Abril 2026

---

Este documento define a estrutura completa do `GUIA-DESENVOLVEDOR.md` — guia técnico para desenvolvedores e mantenedores do Tema UENF. O conteúdo completo de cada seção será escrito na tarefa [P2-06] do plano de trabalho.

---

## Estrutura Proposta

### 1. Visão Geral da Arquitetura

Conteúdo: diagrama textual da relação entre `functions.php`, `inc/`, extensões e Customizer. Explicação do fluxo de inicialização do tema (hooks de ordem: `after_setup_theme` → `customize_register` com prioridades 5/15/20/999). Princípios de design adotados no tema.

---

### 2. Estrutura de Diretórios

Conteúdo: árvore completa de diretórios com comentário de 1–2 linhas sobre o propósito de cada pasta/arquivo principal.

```
/
├── functions.php          # Ponto de entrada — admin, Customizer, hooks globais
├── style.css              # Metadados do tema + CSS base compilado
├── css/                   # Fontes CSS por componente e responsividade
│   ├── components/        # Cabeçalho, rodapé, menus, atalhos
│   └── responsive/        # mobile.css, tablet.css, desktop.css
├── inc/
│   ├── customizer/        # Managers e Controls de cada seção do Customizer
│   └── extensions/        # Sistema de extensões (class-extension-manager.php)
├── template-parts/        # Partes reutilizáveis de template (header, footer, etc.)
└── squads/                # Documentação e outputs das squads de desenvolvimento
```

---

### 3. Sistema de Extensões

Conteúdo: como uma extensão é registrada no `CCT_Extension_Manager`, campos obrigatórios (`name`, `description`, `class`, `file`, `category`, `priority`, `dependencies`), como criar uma nova extensão passo a passo. Exemplo de extensão mínima funcional.

**Hooks relevantes identificados:**
- `cct_available_extensions` — filtro para adicionar extensões externas ao gerenciador
- `after_setup_theme` — hook onde extensões são inicializadas
- `customize_register` (prioridade 20) — onde controles das extensões são registrados

---

### 4. Customizer — Como Funciona

Conteúdo: hierarquia de panels e sections (`uenf_panel` como raiz, grupos-cabeçalho como `uenf_group_*`). Como registrar uma nova seção dentro do painel UENF. Como usar o `CCT_Customizer_Base` como classe base para novos managers. Sistema de grupos não-clicáveis com `updateGroupVisibility` via `setInterval`.

**Classes base disponíveis:**
- `CCT_Customizer_Base` — base para managers de Customizer
- `CCT_Color_Manager` — exemplo de manager com paleta
- `CCT_Typography_Customizer` — exemplo com controles complexos

---

### 5. CSS e Responsividade

Conteúdo: arquivo de variáveis CSS disponíveis (cores, tipografia, espaçamentos). Breakpoints oficiais do tema e onde estão definidos. Arquivos de responsividade e qual cobertura cada um tem. Convenções de nomenclatura de classes CSS.

**Breakpoints identificados:**
- Mobile: `max-width: 767.98px` (`css/responsive/mobile.css`)
- Tablet: `768px – 1023.98px` (`css/responsive/tablet.css`)
- Desktop: `min-width: 1024px` (`css/responsive/desktop.css`)

---

### 6. Painel Admin — Adicionando Páginas

Conteúdo: como adicionar novas páginas e subpáginas ao painel "Tema UENF" no wp-admin. Padrão visual a seguir (header com gradiente, cards brancos, classes CSS disponíveis). Função de sanitização e verificação de nonce recomendadas. Exemplo de callback de página completo.

---

### 7. Segurança

Conteúdo: checklist de segurança para cada tipo de entrada/saída. Funções de sanitização recomendadas por tipo de dado (`sanitize_text_field`, `esc_html`, `esc_attr`, `wp_kses`). Padrão de nonce para formulários admin (campo `nonce`, verificação com `check_admin_referer`). Problemas conhecidos a evitar (nonce inconsistente documentado na auditoria).

---

### 8. Convenções de Código

Conteúdo: prefixo `cct_` para funções e opções, prefixo `uenf_` para painéis do Customizer. Padrão de nomenclatura de classes (`CCT_NomeDoSistema_Manager`, `CCT_NomeDoSistema_Controls`). Padrão de comentários PHP e JS. Como nomear seções e controles do Customizer.

---

### 9. Contribuindo e Versionamento

Conteúdo: fluxo de branches (main, aparencia, features). Como fazer uma PR. Como rodar o ambiente local. O que testar antes de abrir uma PR (Customizer, extensões, reset, page de docs).

---

### 10. Changelog e Histórico

Conteúdo: versão atual (v0.1.0), lista de mudanças por versão, links para commits e PRs relevantes.

---

## Prioridade de Escrita

Para o sprint P2, recomenda-se escrever as seções nesta ordem:

1. **Seção 2** (Estrutura de Diretórios) — referência rápida mais consultada
2. **Seção 3** (Sistema de Extensões) — necessário para novos contribuidores
3. **Seção 7** (Segurança) — resolve os patterns incorretos identificados na auditoria
4. **Seção 4** (Customizer) — contexto para as correções P1 do work-plan
5. Demais seções por ordem de demanda

---

*Outline gerado como adendo ao Step 4 da squad uenf-theme-admin-ux · Clara (Doc Writer)*
