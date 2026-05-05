# UX Writing Report — uenf-dev run 2026-03-31

**Autora:** Camila (UX Writer)
**Escopo:** Textos do Customizer do tema UENF — 16 grupos-cabeçalho e ~60 seções-filho

---

## Status Geral

**APROVADO COM RESSALVAS**

A estrutura geral é sólida e a maioria dos textos está em português com boa intenção de clareza. Contudo, há inconsistências de capitalização, repetição excessiva do rótulo "Geral", termos técnicos sem tradução ou contexto adequado para o perfil de administrador universitário, e alguns rótulos ambíguos que precisam de ajuste antes de ir a produção.

---

## 1. Análise de Clareza

**O que funciona bem:**
- A maioria das seções têm nomes diretos: "Cores de Texto", "Combinações de Fontes", "Escala", "Preview Multi-dispositivo".
- Os emojis nos grupos facilitam a escaneabilidade visual.
- Labels de campo como "Ativar Animações", "Modo Padrão", "Respeitar Preferência do Sistema" são claros.

**Pontos de atenção:**
- "Geral" repete-se em 6 grupos distintos sem contexto diferenciador.
- "Interface" (Modo Escuro > Interface) é vago — refere-se ao botão toggle, mas o nome não comunica isso.
- "Tokens Primitivos" / "Tokens Semânticos" são inacessíveis ao público universitário típico.
- "Construtor" (Layout) gera expectativa de page builder completo.
- "Otimização SVG" pressupõe que o admin sabe o que é SVG.
- Seções do grupo Padrões misturam idiomas: "FAQ", "Pricing", "Team" junto com PT.

---

## 2. Consistência de Capitalização e Gramática

Padrão adotado: **primeira palavra capitalizada, demais em minúsculas, exceto siglas** — correto para WordPress em português.

**Quebras de consistência identificadas:**

| Contexto | Problema |
|---|---|
| Seções do grupo Padrões | "FAQ", "Pricing", "Team" — prefixo PT, sufixo EN |
| `class-search-customizer-controls.php` | Labels sem `__()` e em inglês: "Border Radius Superior Esquerdo - Campo" |
| `class-404-customizer.php` | Labels sem `__()` — não internacionalizados |
| Animações — nomes dos presets | "Fade", "Slide", "Scale", "Rotate", "Bounce", "Flip" — todos em inglês sem tradução |
| Curvas de easing | "Ease In", "Ease Out", "Ease In Out" — inglês técnico sem alternativa PT |

---

## 3. Jargões e Termos Técnicos

| Termo | Adequado para admin universitário? | Recomendação |
|---|---|---|
| Tokens Primitivos | Não | "Valores Base" |
| Tokens Semânticos | Não | "Variáveis de Contexto" |
| Tokens de Componente | Não | "Estilos de Componentes" |
| Breakpoints | Parcialmente | Manter com descrição: "Breakpoints (pontos de adaptação)" |
| Presets | Parcialmente | "Estilos Predefinidos" |
| SVG | Sim — sigla técnica necessária | Manter, adicionar contexto: "Otimização de Ícones (SVG)" |
| Toggle | Não | "Alternância" ou "Botão de Alternância" |
| Masonry | Não | "Grade com alturas variáveis" ou "Layout Mosaico" |

**Termos aceitáveis sem alteração:** Google Fonts, WCAG, FAQ, SEO, SVG.

---

## 4. Nomes Ambíguos ou Repetidos

**"Geral" — repetição crítica em 6 grupos:**

| Grupo | Seção atual | Sugestão |
|---|---|---|
| Modo Escuro | Geral | "Ativação e Comportamento" |
| Responsividade | Geral | "Configurações do Sistema" |
| Padrões | Geral (Visão Geral) | Manter como "Visão Geral" |
| Animações | Geral | "Configurações Globais" |
| Design System | Geral | "Visão Geral" |
| Sistema | Configurações Gerais | "Opções do Sistema" |

**Outros nomes ambíguos:**

| Seção | Problema |
|---|---|
| Interface (Modo Escuro) | Refere-se ao botão toggle — não fica claro |
| Aplicação (Gradientes) | Vago — "aplicar onde?" |
| Gerenciamento (Design System) | Pouco diferenciado de "Configurações" |
| Construtor (Layout) | Pode ser confundido com page builder |

---

## 5. Issues Encontradas

| Texto atual | Tipo de issue | Sugestão |
|---|---|---|
| Geral (Modo Escuro) | Ambíguo / repetido | "Ativação e Comportamento" |
| Geral (Responsividade) | Ambíguo / repetido | "Configurações do Sistema" |
| Geral (Animações) | Ambíguo / repetido | "Configurações Globais" |
| Geral (Design System) | Ambíguo / repetido | "Visão Geral" |
| Interface (Modo Escuro) | Vago | "Botão de Alternância" |
| Toggle Automático | Anglicismo | "Alternância Automática" |
| Tokens Primitivos | Jargão inacessível | "Valores Base" |
| Tokens Semânticos | Jargão inacessível | "Variáveis de Contexto" |
| Tokens de Componente | Jargão inacessível | "Estilos de Componentes" |
| Otimização SVG | Jargão sem contexto | "Otimização de Ícones (SVG)" |
| Padrões FAQ | Idioma misto | "Perguntas Frequentes (FAQ)" |
| Padrões Pricing | Idioma misto + jargão | "Tabelas de Preços" |
| Padrões Team | Idioma misto | "Equipe" |
| Padrões Portfolio | Idioma misto | "Portfólio" |
| Templates (Responsividade) | Anglicismo | "Modelos" |
| Preview (Responsividade) | Anglicismo | "Visualização" |
| Aplicação (Gradientes) | Vago | "Onde Aplicar Gradientes" |
| Construtor (Layout) | Expectativa inadequada | "Configuração de Layout" |
| Border Radius Superior Esquerdo - Campo | Inglês técnico / sem `__()` | "Arredondamento Superior Esquerdo — Campo" |
| Posição Customizada | Anglicismo | "Posição Personalizada" |
| Lembrar Escolha do Usuário | Tom informal | "Salvar Preferência do Usuário" |
| Masonry Portfolio | Jargão de layout | "Portfólio em Grade Dinâmica" |
| Team Grid / Team Carousel / Team List | Inglês no Customizer | "Equipe em Grade / em Carrossel / em Lista" |

**Total:** ~37 issues — 6 de ambiguidade/repetição, 18 de anglicismo/idioma misto, 7 de jargão técnico, 4 de capitalização/gramática, 2 arquivos com problemas de internacionalização.

---

## 6. Recomendações

**Prioridade Alta:**
1. Eliminar "Geral" isolado em todos os grupos — cada seção deve ter nome que descreva seu conteúdo.
2. Renomear seções de Design Tokens — "Tokens Primitivos" e "Tokens Semânticos" não comunicam nada ao admin universitário.
3. Padronizar idioma do grupo Padrões — todos os nomes em português.
4. Corrigir labels sem `__()` em `class-404-customizer.php` e `class-search-customizer-controls.php`.

**Prioridade Média:**
5. Substituir anglicismos desnecessários: "Toggle" → "Alternância", "Templates" → "Modelos", "Preview" → "Visualização", "Customizado" → "Personalizado".
6. Contextualizar "Aplicação" em Gradientes e "Gerenciamento" em Design System.

**Prioridade Baixa:**
7. Emojis nos grupos: manter onde agregam significado visual (🌙 🎨), avaliar remover onde são apenas decorativos (▪ ◇ ◆).
8. Substituir "Lembrar Escolha do Usuário" por "Salvar Preferência do Usuário" — tom mais institucional.
9. Revisar mensagens de "preview será implementado em versão futura" — considerar ocultar o controle até estar pronto.
