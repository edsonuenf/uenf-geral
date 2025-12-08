# Guia de Padrões - Tema UENF Geral

## Tipos de Padrões no Tema

O tema UENF Geral possui **dois sistemas diferentes** de padrões:

### 1. Padrões do Customizer (Sistema Antigo)
- **Localização**: Customizer > Design > Padrões
- **Controle**: Ativar/Desativar via checkbox "Ativar Biblioteca de Padrões"
- **Editabilidade**: Configurações limitadas (cores, textos pré-definidos)
- **Tipos**: FAQ, Pricing, Team, Portfolio
- **Tecnologia**: PHP + JavaScript personalizado

### 2. Padrões Nativos do WordPress (Sistema Novo)
- **Localização**: Editor de Blocos > Botão "+" > Aba "Patterns"
- **Controle**: Sempre ativos (não podem ser desativados individualmente)
- **Editabilidade**: **Totalmente editáveis** no editor de blocos
- **Tipos**: FAQ Accordion, FAQ Tabs, Pricing Table
- **Tecnologia**: Block Patterns nativos do WordPress

## Por que não consigo editar em Design > Padrões?

### Padrões Nativos (Pasta /patterns)
Os padrões nativos **NÃO aparecem** em "Design > Padrões" porque:

1. **São gerenciados pelo WordPress**, não pelo customizer
2. **Não precisam de configuração** - funcionam automaticamente
3. **São editáveis diretamente** no editor de blocos
4. **Seguem o padrão WordPress** para máxima compatibilidade

### Como Editar Padrões Nativos

#### No Editor de Blocos:
1. Crie uma nova página/post
2. Clique no botão "+" para adicionar blocos
3. Vá para a aba "Patterns" ou "Padrões"
4. Procure pelas categorias "FAQ" e "Pricing"
5. Insira o padrão desejado
6. **Edite livremente** - textos, cores, layout, etc.

#### Modificação dos Arquivos (Desenvolvedores):
- **FAQ Accordion**: `/patterns/faq-accordion.php`
- **FAQ Tabs**: `/patterns/faq-tabs.php`
- **Pricing Table**: `/patterns/pricing-table.php`

## Sistema de Ativação/Desativação

### Padrões do Customizer
```
Customizer > Design > Padrões > "Ativar Biblioteca de Padrões"
```
- ✅ **Pode ser ativado/desativado**
- ⚙️ **Configurações limitadas**
- 📝 **Textos pré-definidos**

### Padrões Nativos
```
Sempre ativos - Controlados pelo WordPress
```
- ✅ **Sempre disponíveis**
- 🎨 **Totalmente editáveis**
- 🔧 **Sem configurações necessárias**

## Vantagens dos Padrões Nativos

### ✨ Editabilidade Total
- Modifique textos, cores, layouts
- Adicione/remova elementos
- Personalize completamente

### 🚀 Performance
- Carregamento otimizado
- Sem JavaScript adicional
- CSS minificado

### 🔄 Compatibilidade
- Funciona com qualquer tema
- Padrão WordPress oficial
- Atualizações automáticas

### ♿ Acessibilidade
- Estrutura semântica
- Suporte a leitores de tela
- Navegação por teclado

## Migração Recomendada

### Do Sistema Antigo para o Novo

1. **Desative** os padrões do customizer:
   ```
   Customizer > Design > Padrões > Desmarcar "Ativar Biblioteca de Padrões"
   ```

2. **Use** os padrões nativos:
   ```
   Editor de Blocos > "+" > Patterns > FAQ/Pricing
   ```

3. **Benefícios**:
   - Maior flexibilidade
   - Melhor performance
   - Editabilidade total
   - Compatibilidade futura

## Resolução de Problemas

### Padrões não aparecem no editor?

1. **Verifique o suporte**:
   ```php
   // Deve estar em functions.php
   add_theme_support('core-block-patterns');
   ```

2. **Teste a funcionalidade**:
   ```
   Acesse: /wp-content/themes/uenf-geral/test-patterns.php
   ```

3. **Verifique os arquivos**:
   - `/patterns/faq-accordion.php`
   - `/patterns/faq-tabs.php`
   - `/patterns/pricing-table.php`

### Padrões do customizer não funcionam?

1. **Verifique a ativação**:
   ```
   Customizer > Design > Padrões > "Ativar Biblioteca de Padrões"
   ```

2. **Verifique os arquivos**:
   - `/css/patterns.css`
   - `/js/patterns.js`

## Estrutura de Arquivos

```
uenf-geral/
├── patterns/                    # Padrões Nativos (Novo Sistema)
│   ├── faq-accordion.php       # FAQ em accordion
│   ├── faq-tabs.php           # FAQ em abas
│   └── pricing-table.php      # Tabela de preços
├── css/
│   └── patterns.css           # Estilos dos padrões
├── js/
│   └── patterns.js            # Interatividade dos padrões
└── inc/customizer/
    └── class-pattern-library-manager.php  # Sistema Antigo
```

## Suporte e Documentação

- **Arquivo de Teste**: `/test-patterns.php`
- **Documentação Técnica**: `/PATTERNS-UPGRADE.md`
- **Este Guia**: `/GUIA-PADROES.md`

---

**Resumo**: Use os **padrões nativos** (pasta `/patterns`) para máxima flexibilidade e editabilidade. Os padrões do customizer são mantidos para compatibilidade, mas recomenda-se migrar para o sistema novo.