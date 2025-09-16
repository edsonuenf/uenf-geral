# 🔧 Gerenciador de Extensões CCT

## 📋 Visão Geral

O Gerenciador de Extensões é um sistema centralizado que permite controlar todas as funcionalidades avançadas do tema UENF Geral. Com ele, você pode ativar/desativar extensões individualmente ou em massa, melhorando a performance e organizando melhor as funcionalidades.

## 🎯 Funcionalidades

### ✅ **Controle Global**
- Ativar/desativar todas as extensões de uma vez
- Indicador de performance baseado no número de extensões ativas
- Contador de extensões ativas vs total

### ✅ **Controle Individual**
- Ativar/desativar extensões específicas
- Organização por categorias
- Verificação de dependências
- Descrições detalhadas de cada extensão

### ✅ **Interface Intuitiva**
- Painel dedicado no WordPress Customizer
- Badges visuais para status das categorias
- Mensagens de feedback
- Botão de reset para configurações padrão

## 📊 Extensões Disponíveis

### 🎨 **Design e Aparência**
- **Modo Escuro/Claro**: Toggle automático + preferências do usuário
- **Sistema de Sombras**: Elevation system + depth layers
- **Design Tokens**: Sistema de tokens de design centralizados
- **Sistema de Gradientes**: Gradientes personalizáveis
- **Sistema de Ícones**: Gerenciador de ícones personalizáveis
- **Sistema de Cores**: Paleta de cores avançada

### 📐 **Layout e Estrutura**
- **Responsive Breakpoints**: Gerenciador de pontos de quebra customizáveis

### ✍️ **Tipografia**
- **Combinações de Fontes**: Sistema de tipografia predefinida

### 📄 **Conteúdo**
- **Biblioteca de Padrões**: Seções FAQ, Pricing, Team, Portfolio

### ✨ **Efeitos e Animações**
- **Sistema de Animações**: Animações e transições

## 🚀 Como Usar

### 📍 **Acessando o Gerenciador**

1. Acesse **Aparência → Personalizar**
2. Procure pelo painel **🔧 Gerenciador de Extensões**
3. Explore as diferentes seções:
   - **⚙️ Controle Global**
   - **🎨 Design e Aparência**
   - **📐 Layout e Estrutura**
   - **✍️ Tipografia**
   - **📄 Conteúdo**
   - **✨ Efeitos e Animações**

### 📍 **Controle Global**

```
☑️ Ativar Todas as Extensões
```

- **Marcado**: Todas as extensões ficam disponíveis
- **Desmarcado**: Todas as extensões são desabilitadas (modo performance)

### 📍 **Controle Individual**

Cada categoria mostra:
- **Badge com contador**: `3/5` (3 ativas de 5 total)
- **Checkboxes individuais** para cada extensão
- **Descrições** explicando o que cada extensão faz

### 📍 **Indicador de Performance**

```
Extensões ativas: 7 de 10
Performance: 🟡 Boa
```

- **🟢 Excelente**: ≤ 30% das extensões ativas
- **🟡 Boa**: 31-60% das extensões ativas
- **🟠 Moderada**: 61-80% das extensões ativas
- **🔴 Pesada**: > 80% das extensões ativas

## 🔧 Para Desenvolvedores

### 📍 **Verificando se Extensão está Ativa**

```php
// Verificar se uma extensão específica está ativa
if (cct_is_extension_active('dark_mode')) {
    // Código específico para modo escuro
}

// Ou usando o gerenciador diretamente
$extension_manager = cct_extension_manager();
if ($extension_manager->is_extension_active('shadows')) {
    // Código específico para sombras
}
```

### 📍 **Adicionando Nova Extensão**

1. **Criar a classe da extensão**:

```php
class CCT_Nova_Extensao_Manager {
    public function register($wp_customize) {
        // Código do customizer
    }
    
    public function output_custom_css() {
        // CSS da extensão
    }
}
```

2. **Registrar no gerenciador**:

Edite `class-extension-manager.php` e adicione na função `load_extensions()`:

```php
'nova_extensao' => array(
    'name' => 'Nova Extensão',
    'description' => 'Descrição da nova funcionalidade',
    'class' => 'CCT_Nova_Extensao_Manager',
    'file' => 'class-nova-extensao-manager.php',
    'category' => 'design', // ou layout, typography, content, effects
    'priority' => 110,
    'dependencies' => array() // extensões necessárias
),
```

3. **Adicionar ao mapeamento**:

Edite `functions.php` na função `cct_init_customizer_extensions()`:

```php
$extension_classes = array(
    // ... extensões existentes ...
    'nova_extensao' => 'CCT_Nova_Extensao_Manager',
);
```

### 📍 **Sistema de Dependências**

```php
'extensao_dependente' => array(
    'name' => 'Extensão Dependente',
    'description' => 'Precisa de outras extensões para funcionar',
    'class' => 'CCT_Extensao_Dependente',
    'file' => 'class-extensao-dependente.php',
    'category' => 'design',
    'priority' => 120,
    'dependencies' => array('design_tokens', 'colors') // Precisa dessas extensões
),
```

### 📍 **Hooks Disponíveis**

```php
// Filtro para adicionar extensões
add_filter('cct_available_extensions', function($extensions) {
    $extensions['minha_extensao'] = array(
        // configuração da extensão
    );
    return $extensions;
});

// Ação após carregar extensão
add_action('cct_extension_loaded', function($extension_id, $extension_instance) {
    // Código após carregar extensão
}, 10, 2);
```

## 🎨 Personalização da Interface

### 📍 **CSS Customizado**

O JavaScript adiciona classes CSS que podem ser personalizadas:

```css
/* Extensão ativa */
.extension-active {
    background: rgba(76, 175, 80, 0.1);
    border-left: 3px solid #4CAF50;
}

/* Badges das categorias */
.cct-category-badge.badge-full {
    background: #4CAF50; /* Verde - todas ativas */
}

.cct-category-badge.badge-partial {
    background: #FF9800; /* Laranja - algumas ativas */
}

.cct-category-badge.badge-empty {
    background: #9E9E9E; /* Cinza - nenhuma ativa */
}
```

### 📍 **JavaScript Customizado**

```javascript
// Acessar o gerenciador
const manager = window.CCTExtensionsManager;

// Adicionar evento customizado
manager.onExtensionToggle = function(extensionId, enabled) {
    console.log(`Extensão ${extensionId} foi ${enabled ? 'ativada' : 'desativada'}`);
};
```

## 🔍 Debug e Troubleshooting

### 📍 **Logs de Debug**

Com `WP_DEBUG` ativo, o sistema gera logs:

```
CCT: Extensão dark_mode carregada com sucesso
CCT: Extensão shadows está desabilitada
CCT: Erro ao carregar extensão gradients: Class not found
```

### 📍 **Console do Navegador**

No customizer, abra o console (F12) para ver:

```
🔧 CCT Extensions Manager
├── Total de extensões: 10
├── Extensões ativas: 7
└── Extensões disponíveis: {objeto com todas as extensões}
```

### 📍 **Problemas Comuns**

**Extensão não aparece:**
- Verificar se a classe existe
- Verificar se o arquivo está sendo incluído
- Verificar se está registrada no gerenciador

**Extensão não carrega:**
- Verificar dependências
- Verificar logs de erro
- Verificar se o método `register()` existe

**Performance ruim:**
- Desativar extensões não utilizadas
- Verificar indicador de performance
- Usar modo global desabilitado para troubleshooting

## 📈 Benefícios

### ✅ **Para Usuários**
- **Controle total** sobre funcionalidades
- **Interface organizada** e intuitiva
- **Feedback visual** do status
- **Performance otimizada**

### ✅ **Para Desenvolvedores**
- **Código organizado** e modular
- **Sistema extensível** e flexível
- **Debug facilitado** com logs
- **API consistente** para extensões

### ✅ **Para Performance**
- **Carregamento condicional** de recursos
- **Redução de overhead** quando desabilitado
- **Otimização automática** baseada no uso
- **Monitoramento visual** da performance

## 🔄 Atualizações Futuras

### 📍 **Planejadas**
- Dashboard administrativo dedicado
- Sistema de templates para extensões
- Importação/exportação de configurações
- Estatísticas de uso das extensões
- Sistema de notificações para atualizações

### 📍 **Possíveis Melhorias**
- Verificação automática de conflitos
- Sistema de rating das extensões
- Marketplace de extensões
- Backup automático de configurações
- Modo de manutenção para extensões

---

**📝 Documentação criada em:** Janeiro 2025  
**🔄 Última atualização:** Janeiro 2025  
**👨‍💻 Desenvolvido por:** Equipe CCT UENF