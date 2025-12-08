# Atualização dos Block Patterns - WordPress Nativo

## Resumo das Melhorias

Este documento descreve as melhorias implementadas na biblioteca de patterns do tema UENF Geral, migrando de um sistema customizado para o formato nativo do WordPress.

## O que foi Implementado

### 1. Estrutura Nativa de Patterns

**Pasta criada:** `/patterns/`

Os patterns agora seguem o padrão nativo do WordPress, sendo automaticamente detectados e registrados pelo sistema.

### 2. Novos Patterns Disponíveis

#### FAQ Accordion (`faq-accordion.php`)
- **Título:** FAQ - Accordion
- **Descrição:** Seção de perguntas frequentes em formato accordion
- **Categorias:** text, faq
- **Recursos:**
  - Suporte a Pattern Overrides
  - Animações suaves
  - Acessibilidade completa
  - Design responsivo

#### FAQ Tabs (`faq-tabs.php`)
- **Título:** FAQ - Abas
- **Descrição:** Seção de perguntas frequentes organizadas em abas
- **Categorias:** text, faq
- **Recursos:**
  - Navegação por abas
  - Suporte a teclado
  - Pattern Overrides
  - Interface moderna

#### Pricing Table (`pricing-table.php`)
- **Título:** Tabela de Preços
- **Descrição:** Tabela de preços com 3 planos e destaque
- **Categorias:** call-to-action, pricing
- **Recursos:**
  - 3 colunas de planos
  - Plano em destaque
  - Botões de ação
  - Design profissional

### 3. Estilos e Interatividade

#### CSS (`/css/patterns.css`)
- Estilos modernos e responsivos
- Suporte a dark mode
- Animações e transições
- Acessibilidade (redução de movimento)
- Estilos de impressão

#### JavaScript (`/js/patterns.js`)
- Funcionalidade de abas FAQ
- Accordion interativo
- Animações de entrada
- Navegação por teclado
- Intersection Observer para performance

### 4. Integração com o Tema

Os arquivos CSS e JS são automaticamente carregados através da função `cct_scripts()` no `functions.php`:

```php
// Estilos dos Block Patterns
$patterns_css_path = get_template_directory() . '/css/patterns.css';
if (file_exists($patterns_css_path)) {
    $patterns_css_version = filemtime($patterns_css_path);
    wp_enqueue_style('cct-patterns', CCT_THEME_URI . '/css/patterns.css', array('cct-style'), $patterns_css_version);
}

// Script dos Block Patterns
'cct-patterns' => array(
    'path' => '/js/patterns.js',
    'deps' => array('jquery'),
    'force' => true
)
```

## Vantagens da Nova Implementação

### 1. **Compatibilidade Nativa**
- Totalmente compatível com o WordPress 6.0+
- Funciona com qualquer editor de blocos
- Suporte completo ao Site Editor

### 2. **Pattern Overrides**
- Usuários podem editar o conteúdo dos patterns
- Alterações são salvas automaticamente
- Não afeta outros usos do mesmo pattern

### 3. **Performance Otimizada**
- Carregamento condicional de recursos
- CSS e JS minificados
- Intersection Observer para animações

### 4. **Acessibilidade**
- Navegação por teclado completa
- ARIA labels apropriados
- Suporte a leitores de tela
- Respeita preferências de movimento reduzido

### 5. **Manutenibilidade**
- Código organizado em arquivos separados
- Documentação completa
- Fácil extensão e modificação

## Como Usar os Novos Patterns

### No Editor de Blocos
1. Abra o editor de posts/páginas
2. Clique no botão "+" para adicionar blocos
3. Vá para a aba "Patterns"
4. Procure pelas categorias "FAQ" ou "Pricing"
5. Clique no pattern desejado para inserir

### No Site Editor (WordPress 6.0+)
1. Vá para Aparência → Editor de Temas
2. Escolha o template que deseja editar
3. Use os patterns da mesma forma que no editor de blocos

### Personalização
Todos os patterns suportam Pattern Overrides, permitindo:
- Editar textos diretamente
- Alterar cores e estilos
- Modificar estrutura de blocos
- Adicionar/remover elementos

## Migração do Sistema Antigo

### O que foi Mantido
- Patterns básicos (Seção de Chamada, Seção de Serviços)
- Compatibilidade com customizer existente
- Estilos e funcionalidades principais

### O que foi Melhorado
- FAQ e Pricing agora são patterns nativos
- Melhor integração com WordPress
- Suporte a Pattern Overrides
- Performance otimizada

### Próximos Passos Recomendados
1. **Testar os novos patterns** em diferentes contextos
2. **Migrar conteúdo existente** para os novos patterns
3. **Simplificar o customizer** removendo controles duplicados
4. **Documentar para usuários finais** como usar os patterns

## Estrutura de Arquivos

```
theme-root/
├── patterns/
│   ├── faq-accordion.php
│   ├── faq-tabs.php
│   └── pricing-table.php
├── css/
│   └── patterns.css
├── js/
│   └── patterns.js
└── functions.php (atualizado)
```

## Suporte e Manutenção

### Debugging
Para debugar os patterns, ative `WP_DEBUG` no `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

### Logs
Os logs de carregamento de estilos estão disponíveis em `/wp-content/debug.log` quando o debug está ativo.

### Atualizações Futuras
Para adicionar novos patterns:
1. Crie um novo arquivo PHP na pasta `/patterns/`
2. Siga a estrutura dos patterns existentes
3. Adicione estilos específicos em `/css/patterns.css`
4. Adicione JavaScript se necessário em `/js/patterns.js`

---

**Data da Implementação:** Janeiro 2025  
**Versão do WordPress:** 6.0+  
**Compatibilidade:** Testado com editores de blocos e Site Editor