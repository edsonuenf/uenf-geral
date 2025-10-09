# Guia do Desenvolvedor - Design System CCT

## Visão Geral

O Design System CCT é uma solução completa e modular para WordPress que oferece ferramentas avançadas de design e customização. Este guia fornece informações técnicas detalhadas para desenvolvedores que desejam estender, modificar ou integrar o sistema.

## Arquitetura do Sistema

### Estrutura Modular

O sistema é organizado em módulos independentes, cada um responsável por uma funcionalidade específica:

```
inc/customizer/
├── customizer-loader.php          # Carregador principal
├── class-customizer-base.php      # Classe base
├── class-typography-customizer.php # Sistema de tipografia
├── class-typography-controls.php   # Controles de tipografia
├── class-color-manager.php         # Gerenciador de cores
├── class-color-controls.php        # Controles de cores
├── class-icon-manager.php          # Sistema de ícones
├── class-icon-controls.php         # Controles de ícones
├── class-layout-manager.php        # Sistema de layout
├── class-layout-controls.php       # Controles de layout
├── class-animation-manager.php     # Sistema de animações
├── class-animation-controls.php    # Controles de animações
├── class-gradient-manager.php      # Biblioteca de gradientes
└── class-gradient-controls.php     # Controles de gradientes
```

### Padrão de Carregamento

Todos os módulos seguem o mesmo padrão de carregamento:

1. **Carregamento Automático**: Via `customizer-loader.php`
2. **Carregamento Direto**: Via `functions.php` (fallback)
3. **Instanciação**: No hook `customize_register`

```php
// Exemplo de instanciação
if (class_exists('CCT_Module_Manager')) {
    $module_manager = new CCT_Module_Manager();
    $module_manager->register($wp_customize);
}
```

## Módulos do Sistema

### 1. Editor CSS Avançado

**Arquivos:**
- `class-typography-customizer.php`
- `css/css-editor.css`
- `js/css-editor.js`

**Funcionalidades:**
- Syntax highlighting com CodeMirror
- Backup automático de alterações
- Validação CSS em tempo real
- Minificação automática
- Versionamento de código

**API de Uso:**
```php
// Obter CSS customizado
$custom_css = get_theme_mod('cct_custom_css', '');

// Adicionar CSS via hook
add_action('wp_head', function() {
    $css = get_theme_mod('cct_custom_css', '');
    if (!empty($css)) {
        echo '<style id="cct-custom-css">' . $css . '</style>';
    }
});
```

### 2. Sistema de Tipografia

**Arquivos:**
- `class-typography-customizer.php`
- `class-typography-controls.php`
- `js/customizer-typography.js`

**Funcionalidades:**
- Integração com Google Fonts (800+ fontes)
- Font pairing inteligente
- Preview em tempo real
- Configurações avançadas (peso, estilo, espaçamento)
- Otimização de carregamento

**API de Uso:**
```php
// Obter configurações de tipografia
$typography = get_theme_mod('cct_typography_settings', array());

// Aplicar tipografia a elementos
function apply_typography($element, $settings) {
    $css = "";
    if (isset($settings['font_family'])) {
        $css .= "font-family: {$settings['font_family']};";
    }
    if (isset($settings['font_size'])) {
        $css .= "font-size: {$settings['font_size']};";
    }
    return $css;
}
```

### 3. Gerenciador de Cores

**Arquivos:**
- `class-color-manager.php`
- `class-color-controls.php`
- `js/customizer-color-manager.js`

**Funcionalidades:**
- Paletas de cores predefinidas
- Gerador de paletas automático
- Accessibility checker (WCAG)
- Análise de contraste
- Export/import de paletas

**API de Uso:**
```php
// Obter paleta ativa
$palette = get_theme_mod('cct_active_palette', 'default');

// Obter cor específica
$primary_color = get_theme_mod('cct_color_primary', '#0073aa');

// Gerar variáveis CSS
function generate_color_variables() {
    $colors = get_theme_mod('cct_color_palette', array());
    $css = ":root {";
    foreach ($colors as $key => $color) {
        $css .= "--color-{$key}: {$color};";
    }
    $css .= "}";
    return $css;
}
```

### 4. Sistema de Ícones

**Arquivos:**
- `class-icon-manager.php`
- `class-icon-controls.php`
- `css/cct-icons.css`
- `js/customizer-icon-manager.js`

**Funcionalidades:**
- Biblioteca SVG com 500+ ícones
- Categorização inteligente
- Busca e filtros avançados
- Upload de ícones personalizados
- Otimização automática

**API de Uso:**
```php
// Renderizar ícone
function cct_render_icon($icon_name, $size = 24, $class = '') {
    $icon_data = CCT_Icon_Manager::get_icon($icon_name);
    if ($icon_data) {
        return sprintf(
            '<svg class="cct-icon %s" width="%d" height="%d">%s</svg>',
            esc_attr($class),
            $size,
            $size,
            $icon_data['svg']
        );
    }
    return '';
}

// Shortcode de ícone
// [cct_icon name="home" size="32" class="my-icon"]
```

### 5. Componentes de Layout

**Arquivos:**
- `class-layout-manager.php`
- `class-layout-controls.php`
- `css/cct-layout-system.css`

**Funcionalidades:**
- Grid system flexível (12 colunas)
- 6 breakpoints responsivos
- 5 tipos de containers
- Layout builder visual
- 200+ classes utilitárias

**API de Uso:**
```php
// Classes de grid
// .cct-container, .cct-row, .cct-col-*

// Shortcodes de layout
// [cct_container type="fluid"]
// [cct_row]
// [cct_col size="6" md="4" lg="3"]

// Obter configurações de grid
$grid_settings = get_theme_mod('cct_layout_grid_settings', array());
```

### 6. Sistema de Animações

**Arquivos:**
- `class-animation-manager.php`
- `class-animation-controls.php`
- `css/cct-animations.css`
- `js/cct-animations.js`

**Funcionalidades:**
- 15+ animações predefinidas
- Micro-interações avançadas
- Intersection Observer
- Performance otimizada
- Acessibilidade (prefers-reduced-motion)

**API de Uso:**
```php
// Shortcodes de animação
// [cct_animate type="fadeIn" duration="0.5" delay="0.2"]
// [cct_hover_effect effect="lift" duration="0.3"]

// JavaScript API
// CCTAnimations.animate('#element', 'bounceIn', {duration: 0.8});

// Classes CSS
// .cct-animate, .cct-fadeIn, .cct-hover-lift
```

### 7. Biblioteca de Gradientes

**Arquivos:**
- `class-gradient-manager.php`
- `class-gradient-controls.php`
- `css/cct-gradients.css`
- `js/cct-gradients.js`

**Funcionalidades:**
- 14 gradientes predefinidos
- Gerador visual avançado
- 3 tipos (linear, radial, cônico)
- Export em 4 formatos
- Browser interativo

**API de Uso:**
```php
// Shortcodes de gradiente
// [cct_gradient name="sunset" type="background"]
// [cct_gradient_text gradient="neon"]
// [cct_gradient_button gradient="gold" href="#"]

// Classes CSS
// .cct-bg-gradient-sunset, .cct-text-gradient-neon

// JavaScript API
// CCTGradients.applyGradient('ocean');
```

## Desenvolvimento de Novos Módulos

### Estrutura Base

Todo módulo deve seguir esta estrutura:

```php
<?php
class CCT_New_Module_Manager {
    private $wp_customize;
    private $prefix = 'cct_new_module_';
    
    public function __construct() {
        // Inicialização
    }
    
    public function register($wp_customize) {
        $this->wp_customize = $wp_customize;
        $this->init_hooks();
        $this->register_customizer();
    }
    
    private function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'output_custom_css'));
    }
    
    public function register_customizer() {
        $this->add_panel();
        $this->add_sections();
        $this->add_settings();
        $this->add_controls();
    }
    
    // Implementar métodos específicos
}
```

### Controles Personalizados

Para criar controles personalizados:

```php
class CCT_Custom_Control extends WP_Customize_Control {
    public $type = 'cct_custom';
    
    public function render_content() {
        // HTML do controle
    }
    
    public function enqueue() {
        // Scripts e estilos específicos
    }
}
```

### Integração com o Loader

1. Adicione o arquivo ao array em `customizer-loader.php`:
```php
$module_files = array(
    // ... outros módulos
    'class-new-module-manager.php',
);
```

2. Adicione o mapeamento de classe:
```php
$class_map = array(
    // ... outros mapeamentos
    'class-new-module-manager.php' => 'CCT_New_Module_Manager',
);
```

3. Adicione a instanciação em `functions.php`:
```php
if (class_exists('CCT_New_Module_Manager')) {
    $new_module = new CCT_New_Module_Manager();
    $new_module->register($wp_customize);
}
```

## Padrões de Código

### Nomenclatura

- **Classes**: `CCT_Module_Name`
- **Métodos**: `snake_case`
- **Propriedades**: `snake_case`
- **Constantes**: `UPPER_CASE`
- **Hooks**: `cct_module_action`
- **Configurações**: `cct_module_setting`

### Documentação

Todos os métodos devem ter documentação PHPDoc:

```php
/**
 * Descrição do método
 * 
 * @param string $param1 Descrição do parâmetro
 * @param array $param2 Descrição do parâmetro
 * @return bool Descrição do retorno
 * @since 1.0.0
 */
public function method_name($param1, $param2 = array()) {
    // Implementação
}
```

### Sanitização

Sempre sanitize dados de entrada:

```php
// Texto
$text = sanitize_text_field($input);

// HTML
$html = wp_kses_post($input);

// URL
$url = esc_url_raw($input);

// Cor hexadecimal
$color = sanitize_hex_color($input);

// Array JSON
public function sanitize_json_array($input) {
    if (is_string($input)) {
        $decoded = json_decode($input, true);
        return is_array($decoded) ? $decoded : array();
    }
    return is_array($input) ? $input : array();
}
```

### Segurança

- Use nonces para AJAX: `wp_create_nonce('action_name')`
- Verifique capabilities: `current_user_can('edit_theme_options')`
- Escape output: `esc_html()`, `esc_attr()`, `esc_url()`
- Sanitize input: funções apropriadas do WordPress

## Performance

### Otimizações Implementadas

1. **Carregamento Condicional**: Módulos só carregam quando necessário
2. **Minificação**: CSS e JS são minificados automaticamente
3. **Cache**: Configurações são cacheadas
4. **Lazy Loading**: Recursos carregam sob demanda
5. **GPU Acceleration**: Animações usam aceleração por hardware

### Boas Práticas

```php
// Cache de configurações
private function get_cached_settings() {
    static $cache = null;
    if ($cache === null) {
        $cache = get_theme_mods();
    }
    return $cache;
}

// Enqueue condicional
public function enqueue_scripts() {
    if (!$this->is_module_enabled()) {
        return;
    }
    // Enqueue scripts
}

// Debounce para eventos
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
```

## Debugging

### Modo Debug

Ative o modo debug adicionando ao `wp-config.php`:

```php
define('CCT_DEBUG', true);
```

Isso ativa:
- Logs detalhados
- Informações de performance
- Validação extra
- Modo debug nos controles

### Logs

```php
// Log de debug
if (defined('CCT_DEBUG') && CCT_DEBUG) {
    error_log('[CCT] ' . $message);
}

// Log JavaScript
if (window.cctDebug) {
    console.log('[CCT]', message, data);
}
```

### Ferramentas de Debug

1. **Browser DevTools**: Inspecionar elementos e CSS gerado
2. **WordPress Debug**: `WP_DEBUG` e `WP_DEBUG_LOG`
3. **Query Monitor**: Plugin para análise de performance
4. **Debug Bar**: Plugin para informações de debug

## Testes

### Estrutura de Testes

```
tests/
├── bootstrap.php
├── test-functions.php
└── unit/
    ├── test-typography.php
    ├── test-colors.php
    ├── test-icons.php
    ├── test-layout.php
    ├── test-animations.php
    └── test-gradients.php
```

### Exemplo de Teste

```php
class Test_Typography extends WP_UnitTestCase {
    
    public function setUp() {
        parent::setUp();
        $this->typography = new CCT_Typography_Customizer();
    }
    
    public function test_font_loading() {
        $fonts = $this->typography->get_google_fonts();
        $this->assertNotEmpty($fonts);
        $this->assertArrayHasKey('Roboto', $fonts);
    }
    
    public function test_css_generation() {
        $settings = array(
            'font_family' => 'Roboto',
            'font_size' => '16px',
            'font_weight' => '400'
        );
        
        $css = $this->typography->generate_css($settings);
        $this->assertContains('font-family: Roboto', $css);
        $this->assertContains('font-size: 16px', $css);
    }
}
```

### Executar Testes

```bash
# Instalar dependências
composer install

# Executar todos os testes
phpunit

# Executar teste específico
phpunit tests/unit/test-typography.php

# Com coverage
phpunit --coverage-html coverage/
```

## Deployment

### Checklist de Deploy

- [ ] Todos os testes passando
- [ ] Código minificado
- [ ] Documentação atualizada
- [ ] Versionamento correto
- [ ] Backup do banco de dados
- [ ] Teste em ambiente de staging

### Versionamento

Siga o padrão Semantic Versioning:
- **MAJOR**: Mudanças incompatíveis
- **MINOR**: Novas funcionalidades compatíveis
- **PATCH**: Correções de bugs

### Build Process

```bash
# Instalar dependências
npm install

# Build de desenvolvimento
npm run dev

# Build de produção
npm run build

# Watch para desenvolvimento
npm run watch
```

## Contribuição

### Fluxo de Contribuição

1. Fork do repositório
2. Criar branch feature: `git checkout -b feature/nova-funcionalidade`
3. Commit das mudanças: `git commit -m 'Adiciona nova funcionalidade'`
4. Push para branch: `git push origin feature/nova-funcionalidade`
5. Criar Pull Request

### Padrões de Commit

```
feat: adiciona nova funcionalidade
fix: corrige bug específico
docs: atualiza documentação
style: mudanças de formatação
refactor: refatoração de código
test: adiciona ou modifica testes
chore: tarefas de manutenção
```

## 🚀 Build e Deploy

### Estrutura de Build

O tema utiliza **Webpack** para compilação e otimização de assets:

```
webpack.config.js          # Configuração do Webpack
package.json               # Dependências e scripts NPM
assets/
├── dist/                  # Arquivos compilados (gerados)
│   ├── css/
│   │   └── style.min.css  # CSS minificado
│   └── js/
│       ├── main.js        # JavaScript principal
│       └── style.js       # JavaScript de estilos
├── fonts/                 # Fontes do tema
├── images/                # Imagens estáticas
└── js/                    # JavaScript fonte
```

### Scripts NPM Disponíveis

```bash
# Desenvolvimento - watch mode com hot reload
npm run watch

# Produção - build otimizado e minificado
npm run build

# Instalação de dependências
npm install
```

### Processo de Build de Produção

#### 1. Preparação do Ambiente

```bash
# Verificar se está na branch correta
git branch --show-current

# Instalar dependências (se necessário)
npm install
```

#### 2. Execução do Build

```bash
# Build de produção
npm run build
```

**Arquivos gerados:**
- `assets/dist/css/style.min.css` (~3.35 KiB minificado)
- `assets/dist/js/main.js` (~4.84 KiB minificado)
- `assets/dist/js/style.js` (JavaScript de estilos)

#### 3. Verificação dos Assets

```bash
# Verificar se os arquivos foram gerados
ls -la assets/dist/css/
ls -la assets/dist/js/
```

### Estratégia de Branches

#### Branch de Desenvolvimento: `sistema-de-busca`
- Desenvolvimento ativo de funcionalidades
- Testes e experimentações
- Assets não compilados (desenvolvimento)

#### Branch de Produção: `production`
- **Apenas código pronto para produção**
- **Assets compilados e otimizados**
- **Versionamento de releases**

### Workflow de Deploy

#### Criação da Branch de Produção

```bash
# 1. Criar branch de produção
git checkout -b production

# 2. Instalar dependências
npm install

# 3. Build de produção
npm run build

# 4. Commit dos assets compilados
git add assets/dist/
git commit -m "build: Adiciona arquivos de produção compilados

- Gera assets/dist/css/style.min.css (3.35 KiB minificado)
- Gera assets/dist/js/main.js (4.84 KiB minificado)
- Build executado com webpack --mode=production"

# 5. Push para GitHub
git push -u origin production
```

#### Atualizações da Branch de Produção

```bash
# 1. Voltar para desenvolvimento
git checkout sistema-de-busca

# 2. Fazer mudanças e commits normalmente
git add .
git commit -m "feat: nova funcionalidade"
git push origin sistema-de-busca

# 3. Atualizar produção
git checkout production
git merge sistema-de-busca

# 4. Rebuild dos assets
npm run build

# 5. Commit e push
git add assets/dist/
git commit -m "build: Atualiza assets de produção"
git push origin production
```

### Configuração do Webpack

O `webpack.config.js` está configurado para:

- **Modo desenvolvimento**: Source maps, hot reload
- **Modo produção**: Minificação, otimização, tree shaking
- **Entry points**: `js/main.js` e `style.css`
- **Output**: `assets/dist/`

### Dicas Importantes

#### ⚠️ Assets Compilados
- **NUNCA** commite `node_modules/`
- **SEMPRE** commite `assets/dist/` na branch `production`
- **NUNCA** commite `assets/dist/` nas branches de desenvolvimento

#### 🔧 Troubleshooting

**Erro: "Module not found"**
```bash
# Limpar cache e reinstalar
rm -rf node_modules package-lock.json
npm install
```

**Erro: "Permission denied"**
```bash
# Linux/Mac - ajustar permissões
chmod +x node_modules/.bin/webpack
```

**Build falha silenciosamente**
```bash
# Executar com verbose
npm run build -- --verbose
```

#### 📦 Deploy em Servidor

1. **Via Git** (recomendado):
```bash
# No servidor
git clone https://github.com/usuario/repo.git
git checkout production
# Assets já estão compilados
```

2. **Via FTP/Upload**:
- Fazer download da branch `production`
- Upload apenas dos arquivos necessários
- **Incluir** pasta `assets/dist/`

#### 🔄 Rollback

Em caso de problemas:
```bash
# Voltar para commit anterior
git checkout production
git reset --hard HEAD~1
git push --force-with-lease origin production
```

### Monitoramento de Performance

Após deploy, verificar:
- **Tamanho dos assets**: Manter CSS < 5KB, JS < 10KB
- **Tempo de carregamento**: < 3 segundos
- **Core Web Vitals**: LCP, FID, CLS
- **Console do navegador**: Sem erros JavaScript

## Suporte e Recursos

### Documentação Adicional

- [Manual do Usuário](manual-usuario.md)
- [Changelog](../CHANGELOG.md)
- [FAQ](faq.md)
- [Troubleshooting](troubleshooting.md)

### Comunidade

- **Issues**: Reporte bugs e solicite funcionalidades
- **Discussions**: Discussões gerais e dúvidas
- **Wiki**: Documentação colaborativa
- **Discord**: Chat em tempo real

### Licença

Este projeto está licenciado sob a GPL v2 ou posterior.

---

**Última atualização**: Janeiro 2024  
**Versão**: 1.0.0  
**Autor**: Equipe CCT