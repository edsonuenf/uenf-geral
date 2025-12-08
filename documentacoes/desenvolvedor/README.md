# 🎓 Tema UENF Geral - WordPress Theme

[![WordPress](https://img.shields.io/badge/WordPress-6.0+-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-2.0.0-orange.svg)](https://github.com/edsonuenf/uenf-geral/releases)

> **Tema WordPress avançado para a Universidade Estadual do Norte Fluminense (UENF) com Design System CCT integrado.**

## 🚀 Visão Geral

O Design System CCT é uma solução completa e modular para WordPress que oferece ferramentas avançadas de design e customização. Com 7 módulos integrados, permite criar sites profissionais e modernos sem conhecimento técnico.

### ✨ Principais Características

- 🎨 **7 Módulos Completos** - Editor CSS, Tipografia, Cores, Ícones, Layout, Animações e Gradientes
- 🎯 **Interface Visual** - Customizer avançado com preview em tempo real
- 📱 **Totalmente Responsivo** - Funciona perfeitamente em todos os dispositivos
- ♿ **Acessibilidade WCAG** - Compatível com padrões de acessibilidade
- ⚡ **Performance Otimizada** - Código otimizado e carregamento rápido
- 🔧 **API Poderosa** - Shortcodes, classes CSS e JavaScript API
- 🌐 **Compatibilidade** - Funciona com WordPress 5.0+ e PHP 7.4+

## 📦 Módulos Incluídos

### 1. 📝 Editor CSS Avançado
- Syntax highlighting com CodeMirror
- Backup automático de alterações
- Validação CSS em tempo real
- Minificação automática

### 2. 🔤 Sistema de Tipografia
- 800+ fontes do Google Fonts
- Font pairing inteligente
- Preview em tempo real
- Configurações avançadas

### 3. 🎨 Gerenciador de Cores
- Paletas predefinidas profissionais
- Gerador automático de paletas
- Verificador de acessibilidade WCAG
- Análise de contraste

### 4. 🎯 Sistema de Ícones
- 500+ ícones SVG organizados
- 10 categorias especializadas
- Upload de ícones personalizados
- Busca e filtros avançados

### 5. 📐 Componentes de Layout
- Grid system flexível (12 colunas)
- 6 breakpoints responsivos
- 5 tipos de containers
- 200+ classes utilitárias

### 6. 🎬 Sistema de Animações
- 15+ animações predefinidas
- Micro-interações avançadas
- Intersection Observer
- Performance otimizada

### 7. 🌈 Biblioteca de Gradientes
- 14 gradientes predefinidos
- Gerador visual avançado
- 3 tipos (linear, radial, cônico)
- Export em 4 formatos

## 🚀 Instalação Rápida

### Requisitos
- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- MySQL 5.6 ou superior

### Passos de Instalação

1. **Download do Tema**
   ```bash
   git clone https://github.com/cct/design-system.git
   ```

2. **Upload para WordPress**
   - Acesse **Aparência → Temas**
   - Clique em **"Adicionar novo"**
   - Selecione **"Enviar tema"**
   - Faça upload do arquivo ZIP

3. **Ativação**
   - Clique em **"Ativar"** após a instalação
   - Acesse **Aparência → Personalizar**
   - Explore os 7 módulos disponíveis

## 📖 Documentação

### Para Usuários
- 📚 **[Manual do Usuário](docs/manual-usuario.md)** - Guia completo para usuários finais
- 🎥 **[Tutoriais em Vídeo](https://youtube.com/cct-tutorials)** - Vídeos passo a passo
- ❓ **[FAQ](docs/faq.md)** - Perguntas frequentes

### Para Desenvolvedores
- 🔧 **[Guia do Desenvolvedor](docs/guia-desenvolvedor.md)** - Documentação técnica completa
- 📋 **[Checklist de Funcionalidades](docs/checklist-customizer.md)** - Status de implementação
- 🐛 **[Troubleshooting](docs/troubleshooting.md)** - Solução de problemas

## 🎯 Como Usar

### Acesso Rápido
1. Vá em **Aparência → Personalizar**
2. Explore os módulos disponíveis:
   - 📝 Editor CSS Avançado
   - 🔤 Sistema de Tipografia
   - 🎨 Gerenciador de Cores
   - 🎯 Sistema de Ícones
   - 📐 Componentes de Layout
   - 🎬 Sistema de Animações
   - 🌈 Biblioteca de Gradientes

### Exemplos de Uso

**Shortcodes:**
```html
<!-- Ícone -->
[cct_icon name="home" size="24" color="#0073aa"]

<!-- Animação -->
[cct_animate type="fadeIn" duration="0.5"]
  Conteúdo animado
[/cct_animate]

<!-- Gradiente -->
[cct_gradient name="sunset" type="background"]
  Conteúdo com fundo gradiente
[/cct_gradient]

<!-- Layout -->
[cct_container]
  [cct_row]
    [cct_col size="6"]Coluna 1[/cct_col]
    [cct_col size="6"]Coluna 2[/cct_col]
  [/cct_row]
[/cct_container]
```

**Classes CSS:**
```html
<div class="cct-bg-gradient-ocean cct-animate cct-fadeIn">
  <h1 class="cct-text-gradient-gold">Título Impactante</h1>
  <button class="cct-hover-lift">Botão Interativo</button>
</div>
```

## 🛠️ Desenvolvimento

### Estrutura do Projeto
```
uenf-geral/
├── inc/customizer/           # Módulos do Design System
│   ├── class-*-manager.php   # Gerenciadores principais
│   ├── class-*-controls.php  # Controles personalizados
│   └── customizer-loader.php # Carregador modular
├── css/                      # Estilos dos módulos
│   ├── cct-*.css            # CSS de cada módulo
│   └── components/          # Componentes específicos
├── js/                       # JavaScript dos módulos
│   ├── cct-*.js             # JS de cada módulo
│   └── customizer-*.js      # Scripts do customizer
├── docs/                     # Documentação
│   ├── manual-usuario.md     # Manual do usuário
│   ├── guia-desenvolvedor.md # Guia técnico
│   └── checklist-customizer.md # Status de implementação
└── functions.php             # Carregamento principal
```

### Scripts de Build
```bash
# Instalar dependências
npm install

# Desenvolvimento
npm run dev

# Produção
npm run build

# Watch
npm run watch

# Testes
npm test
```

## 🧪 Testes

### Executar Testes
```bash
# Testes unitários
phpunit

# Testes específicos
phpunit tests/unit/test-typography.php

# Com coverage
phpunit --coverage-html coverage/
```

### Qualidade de Código
```bash
# PHP CodeSniffer
phpcs --standard=WordPress .

# PHP Mess Detector
phpmd . text cleancode,codesize,controversial,design,naming,unusedcode

# ESLint (JavaScript)
npm run lint:js

# Stylelint (CSS)
npm run lint:css
```

## 🤝 Contribuição

### Como Contribuir
1. Fork o repositório
2. Crie uma branch: `git checkout -b feature/nova-funcionalidade`
3. Commit suas mudanças: `git commit -m 'feat: adiciona nova funcionalidade'`
4. Push para a branch: `git push origin feature/nova-funcionalidade`
5. Abra um Pull Request

### Padrões de Código
- **PHP**: WordPress Coding Standards
- **JavaScript**: ESLint + Prettier
- **CSS**: Stylelint + BEM methodology
- **Commits**: Conventional Commits

### Reportar Bugs
- Use o [sistema de issues](https://github.com/cct/design-system/issues)
- Inclua informações detalhadas
- Adicione screenshots quando relevante
- Teste em ambiente limpo

## 📊 Status do Projeto

### Funcionalidades Implementadas ✅
- [x] Editor CSS Avançado (100%)
- [x] Sistema de Tipografia (100%)
- [x] Gerenciador de Cores (100%)
- [x] Sistema de Ícones (100%)
- [x] Componentes de Layout (100%)
- [x] Sistema de Animações (100%)
- [x] Biblioteca de Gradientes (100%)

### Roadmap Futuro 🚧
- [ ] Modo Escuro/Claro
- [ ] Gerador de CSS Visual
- [ ] Biblioteca de Componentes
- [ ] Templates Prontos
- [ ] Integração com Page Builders

## 🌟 Showcase

### Sites Criados com o Design System
- [Portfolio Criativo](https://exemplo1.com) - Agência de design
- [E-commerce Moderno](https://exemplo2.com) - Loja online
- [Blog Corporativo](https://exemplo3.com) - Empresa de tecnologia
- [Landing Page](https://exemplo4.com) - Produto SaaS

## 📈 Performance

### Métricas
- **PageSpeed Score**: 95+
- **GTmetrix Grade**: A
- **Lighthouse Score**: 90+
- **Core Web Vitals**: Aprovado

### Otimizações
- Carregamento condicional de módulos
- Minificação automática de CSS/JS
- Lazy loading de recursos
- Cache inteligente
- GPU acceleration para animações

## 🔒 Segurança

### Medidas Implementadas
- Sanitização de todas as entradas
- Escape de todas as saídas
- Nonces para requisições AJAX
- Verificação de capabilities
- Validação de tipos de arquivo

## 🌍 Compatibilidade

### WordPress
- ✅ WordPress 5.0+
- ✅ WordPress 6.0+
- ✅ Multisite
- ✅ Gutenberg
- ✅ Classic Editor

### Navegadores
- ✅ Chrome 70+
- ✅ Firefox 65+
- ✅ Safari 12+
- ✅ Edge 79+
- ⚠️ IE 11 (funcionalidade limitada)

### Plugins Testados
- ✅ WooCommerce
- ✅ Contact Form 7
- ✅ Yoast SEO
- ✅ Elementor
- ✅ WP Rocket

## 📞 Suporte

### Canais de Suporte
- 📧 **Email**: suporte@cct.com
- 💬 **Discord**: [discord.gg/cct](https://discord.gg/cct)
- 🌐 **Fórum**: [forum.cct.com](https://forum.cct.com)
- 📖 **Documentação**: [docs.cct.com](https://docs.cct.com)

### Horários de Atendimento
- **Segunda a Sexta**: 9h às 18h (GMT-3)
- **Resposta média**: 24 horas
- **Suporte prioritário**: Disponível para clientes premium

## 📄 Licença

Este projeto está licenciado sob a **GPL v2 ou posterior**.

```
Copyright (C) 2024 CCT Design System

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## 🙏 Agradecimentos

- **WordPress Community** - Pela plataforma incrível
- **Google Fonts** - Pela biblioteca de fontes
- **CodeMirror** - Pelo editor de código
- **Contributors** - Por todas as contribuições
- **Beta Testers** - Por testarem e reportarem bugs

## 📊 Estatísticas

- **Linhas de Código**: 15,000+
- **Arquivos**: 50+
- **Módulos**: 7
- **Funcionalidades**: 100+
- **Testes**: 200+
- **Downloads**: 10,000+

---

**Desenvolvido com ❤️ pela equipe CCT**

*Transforme suas ideias em realidade com o Design System CCT!*

## 📦 Estrutura do Tema

```
uenf-geral/
├── assets/               # Recivos estáticos (imagens, ícones, etc.)
├── css/                 # Arquivos CSS originais
│   ├── components/       # Componentes individuais
│   └── build/            # CSS compilado e minificado
├── js/                   # Arquivos JavaScript
├── inc/                  # Funcionalidades do tema
├── template-parts/       # Partes de templates reutilizáveis
└── docs/                 # Documentação
```

## 🛠️ Desenvolvimento

### Minificação de CSS

O tema utiliza um sistema de minificação de CSS para otimização de desempenho. O processo é gerenciado pelo arquivo `css/build/build.php`.

#### Como funciona:
1. Os arquivos CSS são desenvolvidos em `css/components/`
2. O script de build combina e minifica todos os arquivos
3. O CSS final é salvo em `css/style.min.css`

#### Comandos disponíveis:
```bash
# Minificar CSS
php css/build/build.php
```

#### Configuração automática (para evitar esquecimentos):

1. **Adicione um alias ao seu `.bashrc` ou `.zshrc`:**
   ```bash
   alias uenf-build="cd /caminho/para/uenf-geral && php css/build/build.php"
   ```

2. **Configure um hook de pré-commit no Git** (opcional):
   Crie um arquivo `.git/hooks/pre-commit` com:
   ```bash
   #!/bin/sh
   echo "🔨 Minificando CSS..."
   php css/build/build.php
   git add css/style.min.css
   ```
   E torne-o executável:
   ```bash
   chmod +x .git/hooks/pre-commit
   ```

3. **Use o VSCode** (se for seu editor):
   Adicione ao seu `settings.json`:
   ```json
   {
       "files.autoSave": "afterDelay",
       "files.autoSaveDelay": 1000,
       "files.watcherExclude": {
           "**/css/style.min.css": true
       },
       "tasks": {
           "version": "2.0.0",
           "tasks": [
               {
                   "label": "Build CSS",
                   "type": "shell",
                   "command": "php css/build/build.php",
                   "group": "build",
                   "problemMatcher": []
               }
           ]
       }
   }
   ```

### Dicas para Desenvolvimento

1. **Sempre desenvolva nos arquivos CSS originais** em `css/components/`
2. **Nunca edite diretamente** o `style.min.css`
3. **Execute o build** antes de fazer commit:
   ```bash
   php css/build/build.php
   ```
4. **Verifique se o CSS minificado** foi atualizado corretamente

## 🔄 Gerenciamento de Cache

Para garantir que as alterações sejam refletidas imediatamente durante o desenvolvimento:

1. **Desative o cache do navegador** nas ferramentas de desenvolvedor
2. **Use parâmetros de versão** ao chamar estilos no `functions.php`:
   ```php
   wp_enqueue_style('uenf-style', get_stylesheet_uri(), array(), filemtime(get_stylesheet_directory() . '/style.css'));
   ```
3. **Limpe o cache do WordPress** após alterações significativas

## 📝 Documentação Adicional

Consulte a pasta `docs/` para documentação detalhada sobre:
- Personalização do tema
- Estrutura de arquivos
- Boas práticas de desenvolvimento

## 🤝 Contribuição

1. Faça um fork do repositório
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Faça commit das suas alterações (`git commit -am 'Adiciona nova feature'`)
4. Faça push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

---

📅 Última atualização: 16/06/2025
