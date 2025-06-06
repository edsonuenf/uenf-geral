# Procedimento para Gerenciamento de Plugins no Tema UENF

## 📌 Visão Geral
Este documento estabelece o procedimento padrão para adicionar novas funcionalidades ao tema UENF que requeiram a criação de plugins. Todas as novas funcionalidades devem ser implementadas como plugins gerenciáveis através do sistema de Feature Manager do tema.

## 📋 Diretrizes Gerais

1. **Estrutura de Diretórios**
   - Plugins devem ser armazenados em: `/wp-content/plugins/`
   - Configurações específicas do tema para plugins devem ficar em: `/inc/feature-manager/`

2. **Nomenclatura**
   - Prefixo: `uenf-` (ex: `uenf-seo-manager`)
   - Nomes em minúsculas, separados por hífens
   - Nomes descritivos e auto-explicativos

3. **Estrutura Mínima de um Plugin**
   ```
   uenf-meu-plugin/
   ├── admin/                 # Arquivos do painel administrativo
   │   ├── css/              # Estilos do admin
   │   ├── js/               # Scripts do admin
   │   └── partials/         # Templates parciais
   ├── includes/             # Código principal
   ├── languages/            # Arquivos de tradução
   ├── public/               # Arquivos públicos
   │   ├── css/             # Estilos públicos
   │   ├── js/              # Scripts públicos
   │   └── images/          # Imagens públicas
   ├── uninstall.php         # Código de desinstalação
   └── uenf-meu-plugin.php   # Arquivo principal
   ```


## 📝 Passo a Passo para Adicionar um Novo Plugin

### 1. Planejamento
- [ ] Definir o propósito e funcionalidades do plugin
- [ ] Verificar se já existe solução similar
- [ ] Documentar requisitos e especificações

### 2. Desenvolvimento
- [ ] Criar a estrutura de diretórios
- [ ] Implementar o código seguindo os padrões WordPress
- [ ] Adicionar suporte a traduções
- [ ] Documentar o código

### 3. Integração com o Feature Manager

#### 3.1 Criar Classe do Plugin
```php
class UENF_Meu_Plugin_Manager {
    private $enabled = false;

    public function __construct() {
        // Inicializar configurações
        $this->enabled = get_theme_mod('uenf_meu_plugin_enabled', false);
        
        if ($this->enabled) {
            $this->init();
        }
    }

    
    private function init() {
        // Inicializar funcionalidades
        add_action('init', array($this, 'register_assets'));
        // ... outras ações/filtros
    }
    
    public function register_assets() {
        // Registrar scripts e estilos
    }
}
```

#### 3.2 Adicionar ao Feature Manager
```php
// Em functions.php ou arquivo específico do feature manager
function uenf_register_feature_plugins() {
    $features = array(
        'meu_plugin' => array(
            'title'       => __('Meu Plugin', 'uenf'),
            'description' => __('Descrição do que o plugin faz', 'uenf'),
            'class'       => 'UENF_Meu_Plugin_Manager',
            'settings'    => array(
                'uenf_meu_plugin_enabled' => array(
                    'default'   => false,
                    'type'      => 'boolean',
                    'label'     => __('Ativar Meu Plugin', 'uenf'),
                    'section'   => 'uenf_features',
                    'priority'  => 10
                ),
                // Outras configurações específicas
            )
        ),
        // Outros plugins...
    );
    
    return apply_filters('uenf_available_features', $features);
}
```

### 4. Adicionar Configurações ao Customizer

#### 4.1 Criar Seção no Customizer
```php
function uenf_meu_plugin_customize_register($wp_customize) {
    // Seção para o plugin
    $wp_customize->add_section('uenf_meu_plugin', array(
        'title'    => __('Meu Plugin', 'uenf'),
        'priority' => 160,
    ));
    
    // Configurações específicas
    $wp_customize->add_setting('uenf_meu_plugin_setting', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('uenf_meu_plugin_setting', array(
        'label'    => __('Configuração do Plugin', 'uenf'),
        'section'  => 'uenf_meu_plugin',
        'type'     => 'text',
    ));
}
add_action('customize_register', 'uenf_meu_plugin_customize_register');
```

### 5. Documentação

#### 5.1 Arquivo README.md
```markdown
# Meu Plugin UENF

## Descrição
Breve descrição do que o plugin faz.

## Requisitos
- WordPress 5.0+
- PHP 7.4+
- Tema UENF 2.0+

## Instalação
1. Faça upload do diretório `uenf-meu-plugin` para `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Configure as opções em 'Aparência > Personalizar > Meu Plugin'

## Uso
Instruções detalhadas de uso.
```

#### 5.2 Documentação Técnica
- Documentar hooks, filtros e funções públicas
- Incluir exemplos de uso
- Documentar estrutura de banco de dados (se aplicável)

### 6. Testes
- [ ] Testar ativação/desativação
- [ ] Verificar compatibilidade com versões do WordPress
- [ ] Testar em diferentes navegadores e dispositivos
- [ ] Validar acessibilidade
- [ ] Testar desempenho

## 🔄 Atualização do config.json

Atualize o arquivo `config.json` com as informações do novo plugin:

```json
{
  "plugins": {
    "meu_plugin": {
      "nome": "Meu Plugin",
      "slug": "uenf-meu-plugin",
      "descricao": "Descrição do que o plugin faz",
      "versao_minima": "1.0.0",
      "testado_ate": "6.4",
      "requer_php": "7.4",
      "pagina_config": "admin.php?page=meu-plugin",
      "secao_customizer": "uenf_meu_plugin",
      "dependencias": ["advanced-custom-fields"]
    }
  }
}
```

## 📌 Boas Práticas

1. **Segurança**
   - Validar e sanitizar todas as entradas
   - Usar nonces para formulários
   - Implementar verificações de capacidade
   - Seguir as diretrizes de codificação do WordPress

2. **Desempenho**
   - Usar carregamento condicional de assets
   - Implementar cache quando apropriado
   - Otimizar consultas ao banco de dados
   - Minimizar requisições HTTP

3. **Manutenibilidade**
   - Seguir os padrões de codificação do WordPress
   - Documentar o código extensivamente
   - Usar namespaces e autoloading
   - Manter o código DRY (Don't Repeat Yourself)

## 🔄 Atualizações Futuras

1. Manter o plugin atualizado com as versões mais recentes do WordPress
2. Monitorar e corrigir problemas de segurança
3. Coletar feedback dos usuários para melhorias
4. Manter a documentação atualizada

---

Atualizado em: 06/06/2025
```

Este documento deve ser consultado sempre que for necessário adicionar ou modificar funcionalidades que requeiram a criação de plugins no tema UENF.
