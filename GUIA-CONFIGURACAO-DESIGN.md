# Guia de Configuração e Design — Tema UENF

> **Versão:** 1.0 — Abril 2026
> Tema desenvolvido para a Universidade Estadual do Norte Fluminense Darcy Ribeiro (UENF)

---

## Índice

- [Introdução](#introducao)
- [Como Acessar o Personalizador](#como-acessar)
- [Cores](#cores)
- [Tipografia](#tipografia)
- [Ícones](#icones)
- [Header (Cabeçalho)](#header)
- [Menu de Navegação](#menu)
- [Busca](#busca)
- [Página 404](#pagina-404)
- [Backup e Restauração](#backup)
- [Extensões Disponíveis](#extensoes)
- [Painel Tema UENF no wp-admin](#painel-admin)
- [Perguntas Frequentes](#faq)

---

## Introdução

O **Tema UENF** é o tema oficial do site institucional da UENF. Ele oferece um conjunto completo de ferramentas de personalização visual, acessíveis diretamente pelo painel de administração do WordPress — sem necessidade de editar código.

As configurações visuais ficam no **Personalizador do WordPress** (também chamado de Customizer), onde você pode visualizar as mudanças em tempo real antes de publicá-las.

> **Nota:** Algumas seções do Personalizador só aparecem quando a extensão correspondente está ativa. Veja a seção [Extensões Disponíveis](#extensoes) para saber como ativar cada uma.

---

## Como Acessar o Personalizador

1. Faça login no painel de administração do WordPress (`/wp-admin`)
2. No menu lateral, clique em **Aparência → Personalizar**
3. O Personalizador abre com uma prévia ao vivo do site
4. No painel esquerdo, clique em **UENF** para ver todas as opções de personalização do tema

> **Dica:** Você pode pesquisar seções pelo nome digitando na barra de busca no topo do Personalizador.

---

## Cores

**Como acessar:** Personalizador → UENF → Cores

> **Requer extensão:** 🎨 Gerenciador de Cores (ativa por padrão)

O sistema de cores do Tema UENF permite personalizar as cores em três grupos:

### Cores Principais

Definem a paleta base do site:

- **Cor primária** — Usada no cabeçalho, botões principais e destaques. Por padrão: azul UENF (`#1d3771`)
- **Cor secundária** — Usada em elementos de apoio e hover
- **Cor de destaque** — Para chamadas de ação e elementos que precisam de atenção
- **Cor de fundo** — Fundo geral das páginas

### Cores de Texto

Controlam a legibilidade do conteúdo:

- **Texto principal** — Cor dos parágrafos e conteúdo geral
- **Texto de títulos** — Cor dos títulos (h1–h6)
- **Links** — Cor dos links no conteúdo
- **Links ao passar o mouse** — Cor ao fazer hover nos links

### Cores do Menu

Personalizam a aparência do menu de navegação:

- **Fundo do menu** — Cor de fundo da barra de navegação
- **Texto dos itens** — Cor dos links do menu
- **Item ativo/selecionado** — Destaque para a página atual
- **Hover** — Cor ao passar o mouse

> **Importante:** Ao alterar as cores principais, verifique o contraste entre texto e fundo para garantir acessibilidade. Recomenda-se uma relação de contraste mínima de 4.5:1.

---

## Tipografia

**Como acessar:** Personalizador → UENF → Tipografia

> **Requer extensão:** 📝 Tipografia Avançada (inativa por padrão — ative se precisar de controle detalhado)

### Configurações Básicas

Com a extensão de tipografia ativada, você pode configurar:

- **Fonte principal** — Fonte dos textos gerais (parágrafos, menus, botões)
- **Fonte dos títulos** — Fonte para H1, H2, H3... (pode ser diferente do corpo)
- **Tamanho base do texto** — Tamanho padrão dos parágrafos (recomendado: 16px)
- **Altura de linha** — Espaçamento entre linhas (recomendado: 1.6)

### Tipografia por Elemento

Controles individuais para:
- Títulos H1 a H6 (tamanho, peso, espaçamento)
- Parágrafos (tamanho, linha)
- Menus (tamanho, peso, transformação)
- Rodapé (tamanho, cor)

### Combinações de Fontes

**Como acessar:** Personalizador → UENF → Combinações de Fontes

> **Requer extensão:** Combinações de Fontes (inativa por padrão)

Oferece pares de fontes predefinidos e harmonizados — escolha um par e todo o site é atualizado automaticamente.

---

## Ícones

**Como acessar:** Personalizador → UENF → Ícones

> **Requer extensão:** 🎯 Sistema de Ícones (ativa por padrão)

Permite personalizar os ícones usados no site:

- **Biblioteca de ícones** — Selecione entre as bibliotecas disponíveis (Font Awesome, etc.)
- **Ícones do menu** — Ícones nos itens do menu principal
- **Ícones de redes sociais** — Personalização dos links de redes sociais
- **Ícone de busca** — Aparência do botão de busca

---

## Header (Cabeçalho)

**Como acessar:** Personalizador → UENF → Header

O cabeçalho é a parte superior do site. As configurações incluem:

### Logo e Identidade

- **Logo do site** — Imagem que aparece no canto esquerdo do cabeçalho
- **Logo alternativa** — Versão para fundo claro/escuro (se usar modo escuro)
- **Título do site** — Texto que aparece quando não há logo (configurado em Configurações → Geral)

### Comportamento do Cabeçalho

- **Cabeçalho fixo** — O menu permanece visível ao rolar a página
- **Transparência inicial** — O header começa transparente sobre o banner

### Barra de Idiomas

- Controles para exibição das bandeiras de idioma no cabeçalho

---

## Menu de Navegação

**Como acessar:** Personalizador → UENF → Menu

> As cores do menu são configuradas em [Cores → Cores do Menu](#cores-do-menu)

Personalize a aparência do menu principal:

- **Estilo do menu** — Cores de fundo, hover e item ativo
- **Submenu** — Aparência dos menus dropdown
- **Menu mobile** — Comportamento em telas menores

> **Para adicionar ou remover itens do menu:** vá em Aparência → Menus (fora do Personalizador)

---

## Busca

**Como acessar:** Personalizador → UENF → Busca

> **Requer extensão:** 🔍 Sistema de Busca Personalizado (ativa por padrão)

O Tema UENF inclui um sistema de busca nativo que substitui plugins externos:

- **Estilo do campo de busca** — Aparência do input de pesquisa
- **Resultados** — Layout e informações exibidas nos resultados
- **Placeholder** — Texto de dica dentro do campo de busca
- **Cor de destaque** — Cor dos termos encontrados nos resultados

---

## Página 404

**Como acessar:** Personalizador → UENF → Página 404

Personalize a página exibida quando um endereço não é encontrado:

- **Título** — Mensagem principal da página de erro
- **Subtítulo** — Mensagem de apoio/orientação
- **Botão de ação** — Texto e link do botão (geralmente "Voltar ao início")
- **Imagem** — Ilustração ou imagem de fundo

---

## Backup e Restauração

**Como acessar:** Personalizador → UENF → Backup / Restaurar

> **Importante:** Sempre faça backup antes de grandes mudanças ou antes de usar a página de Reset.

### Exportar Configurações

- Salva todas as configurações do tema em um arquivo `.json`
- Guarde esse arquivo em lugar seguro

### Importar Configurações

- Restaura configurações a partir de um arquivo `.json` exportado anteriormente
- Útil para migrar configurações entre ambientes (desenvolvimento → produção)

### Restaurar Padrões

- Reverte as configurações do Personalizador para os valores padrão do tema
- Esta ação **não pode ser desfeita** — exporte um backup antes

---

## Extensões Disponíveis

As extensões são funcionalidades opcionais do tema. Ative apenas o que o seu site realmente usa — isso mantém o site mais rápido.

**Como gerenciar:** Acesse **Tema UENF → Extensões** no menu do wp-admin

### Extensões Ativas por Padrão

| Extensão | O que faz |
|----------|-----------|
| 🎨 Gerenciador de Cores | Controle avançado das cores do site com paleta completa no Personalizador |
| 🎯 Sistema de Ícones | Personalização dos ícones usados em todo o site |
| 🔍 Sistema de Busca Personalizado | Busca integrada que substitui plugins externos como Ivory Search |

### Extensões Disponíveis (inativas)

**Categoria: Design**

| Extensão | O que faz | Quando ativar |
|----------|-----------|---------------|
| Modo Escuro/Claro | Adiciona botão para o visitante alternar entre tema claro e escuro | Sites que atendem públicos com preferência por modo escuro |
| Sistema de Sombras | Controle fino das sombras em cards, botões e elementos | Quando precisar personalizar a profundidade visual dos elementos |
| Sistema de Gradientes | Adiciona controles de gradiente no Personalizador | Quando quiser fondos e elementos com gradientes personalizados |
| Design Tokens | Centraliza todas as variáveis visuais em um único lugar | Recomendado para uso por desenvolvedores |

**Categoria: Tipografia**

| Extensão | O que faz | Quando ativar |
|----------|-----------|---------------|
| 📝 Tipografia Avançada | Controle detalhado de fontes, tamanhos e espaçamentos para cada elemento | Quando precisar de ajuste fino da tipografia |
| Combinações de Fontes | Pares de fontes predefinidos e harmonizados | Quando quiser mudar o visual tipográfico rapidamente |

**Categoria: Layout**

| Extensão | O que faz | Quando ativar |
|----------|-----------|---------------|
| Responsive Breakpoints | Define os pontos de quebra de layout para diferentes tamanhos de tela | Uso técnico — consulte o desenvolvedor |

**Categoria: Efeitos**

| Extensão | O que faz | Quando ativar |
|----------|-----------|---------------|
| Sistema de Animações | Adiciona animações e transições ao carregar elementos da página | Com moderação — animações excessivas prejudicam a experiência |

**Categoria: Conteúdo**

| Extensão | O que faz | Quando ativar |
|----------|-----------|---------------|
| Biblioteca de Padrões | Seções prontas para uso: FAQ, Preços, Equipe, Portfolio | Quando precisar de blocos de conteúdo estruturados |

---

## Painel Tema UENF no wp-admin

Além do Personalizador, o tema tem um painel próprio no wp-admin. Acesse pelo menu lateral: **Tema UENF**.

### Página Principal

Exibe um resumo rápido do status do tema:
- Extensões ativas e inativas
- Atalhos para as seções mais usadas

### Extensões (`Tema UENF → Extensões`)

- Ativar ou desativar extensões individualmente
- Ver o nome e a descrição de cada extensão
- Usar "Selecionar Todas" / "Desmarcar Todas" para mudanças em lote
- Clicar em **Salvar Configurações** para aplicar

### Reset de Configurações (`Tema UENF → Reset`)

Permite desfazer configurações do tema. Há três tipos de reset:

1. **Reset do Tema** — Reverte as configurações do Personalizador para os padrões
2. **Reset de Extensões** — Desativa todas as extensões e reverte suas configurações
3. **Reset Completo** — Executa os dois resets acima juntos

> **Atenção:** Essas ações não têm desfazer. Acesse **Personalizador → Backup** antes de prosseguir.

### Documentação Design (`Tema UENF → Documentação Design`)

Esta é a página que você está lendo agora. Aqui você encontra o guia completo de personalização do tema.

---

## Perguntas Frequentes

### 1. O Personalizador não está mostrando as opções do Tema UENF. O que fazer?

Verifique se o tema UENF está ativo em **Aparência → Temas**. Se o tema estiver ativo e as seções ainda não aparecerem, desative todos os plugins temporariamente para verificar se há conflito, depois reative um por um para identificar qual causa o problema.

---

### 2. Ativei uma extensão mas não vi nenhuma mudança no site. Por quê?

A maioria das extensões adiciona novas seções no **Personalizador** — não muda o site diretamente ao ativar. Após ativar uma extensão, abra o Personalizador (Aparência → Personalizar → UENF) e procure pelas novas seções relacionadas à extensão.

---

### 3. Alterei cores no Personalizador mas o site não atualizou. Como resolver?

- Certifique-se de clicar em **Publicar** no topo do Personalizador (não apenas fechar a janela)
- Se publicou e o site ainda mostra as cores antigas, pode ser cache. Limpe o cache do plugin de cache (se houver) e do seu navegador com `Ctrl+F5`

---

### 4. Como reverter o site para as configurações originais?

Acesse **Tema UENF → Reset** e escolha o tipo de reset desejado. Antes, faça backup em **Personalizador → Backup → Exportar**. O arquivo exportado permite restaurar as configurações anteriores se necessário.

---

### 5. Posso usar o tema em outro site da UENF?

Sim. Exporte as configurações em **Personalizador → Backup → Exportar**, instale e ative o Tema UENF no outro site, e importe o arquivo em **Personalizador → Backup → Importar**. As configurações de cores, tipografia e extensões serão restauradas.

---

*Guia produzido pela squad uenf-theme-admin-ux · Versão 1.0 · Abril 2026*
