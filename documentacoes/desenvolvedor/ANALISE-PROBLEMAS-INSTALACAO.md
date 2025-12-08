# Análise de Problemas na Instalação do Tema UENF Geral

## Resumo da Investigação

Após análise detalhada da estrutura do tema e comparação entre a versão local funcional e a versão do branch main do GitHub, foram identificados vários pontos que podem causar problemas em diferentes instalações.

## Problemas Identificados

### 1. **Dependências de Build e Compilação**

**Problema:** O tema utiliza webpack para compilação de assets, mas os arquivos compilados podem não estar presentes ou atualizados no repositório.

**Arquivos Críticos:**
- `assets/dist/css/style.css`
- `assets/dist/js/main.js`
- `assets/dist/js/style.js`
- `css/style.min.css`

**Solução:**
```bash
# Na instalação de destino, execute:
npm install
npm run build

# Copie os arquivos compilados:
Copy-Item -Path 'assets\dist\css\style.css' -Destination 'css\style.min.css' -Force
```

### 2. **Dependências Node.js e PHP**

**Problema:** Versões incompatíveis ou dependências ausentes.

**Verificações Necessárias:**
- Node.js versão compatível com as dependências do `package.json`
- PHP >= 7.4 (conforme `composer.json`)
- WordPress >= 5.8

**Dependências Críticas:**
```json
{
  "webpack": "^5.73.0",
  "mini-css-extract-plugin": "^2.6.1",
  "css-loader": "^6.7.1",
  "babel-loader": "^8.4.1"
}
```

### 3. **Estrutura de Arquivos e Caminhos**

**Problema:** O tema possui múltiplas versões e estruturas duplicadas que podem causar conflitos.

**Estruturas Problemáticas:**
- `/public/build-no-addons/` - Versão sem addons
- `/public/theme-simple/` - Versão simplificada
- `/public/build/` - Build alternativo

**Recomendação:** Use apenas a estrutura principal na raiz do tema.

### 4. **Carregamento de Assets**

**Problema:** A função `cct_scripts()` no `functions.php` carrega muitos arquivos CSS/JS que podem não existir.

**Arquivos que podem estar ausentes:**
```php
// Componentes que podem falhar:
'/css/components/new-menu.css'
'/css/components/menu-enhancements.css'
'/css/components/scrollbars.css'
'/css/components/shortcuts.css'
'/css/search-modern.css'
'/js/event-manager.js'
'/components/menu/assets/js/uenf-menu-new.js'
```

### 5. **Configurações de Servidor**

**Problema:** Configurações específicas do servidor podem afetar o funcionamento.

**Verificações:**
- Permissões de arquivo (755 para diretórios, 644 para arquivos)
- Limite de memória PHP (recomendado: 512MB)
- Extensões PHP necessárias
- Configurações de cache

## Soluções Recomendadas

### Solução 1: Script de Instalação Completa

```bash
# 1. Instalar dependências
npm install
composer install

# 2. Compilar assets
npm run build

# 3. Copiar arquivos compilados
Copy-Item -Path 'assets\dist\css\style.css' -Destination 'css\style.min.css' -Force
Copy-Item -Path 'assets\dist\css\style.css' -Destination 'style.min.css' -Force

# 4. Verificar permissões (Linux/Mac)
# find . -type d -exec chmod 755 {} \;
# find . -type f -exec chmod 644 {} \;
```

### Solução 2: Verificação de Arquivos Críticos

Crie um script PHP para verificar se todos os arquivos necessários existem:

```php
<?php
// verificar-arquivos.php
$arquivos_criticos = [
    'css/style.min.css',
    'css/variables.css',
    'css/reset.css',
    'js/main.js',
    'assets/dist/css/style.css',
    'functions.php',
    'style.css'
];

foreach ($arquivos_criticos as $arquivo) {
    if (!file_exists($arquivo)) {
        echo "ERRO: Arquivo ausente - $arquivo\n";
    } else {
        echo "OK: $arquivo\n";
    }
}
?>
```

### Solução 3: Configuração de Debug

Adicione ao `wp-config.php` para identificar erros:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

### Solução 4: Versão Simplificada

Se os problemas persistirem, use a versão simplificada:

1. Copie o conteúdo de `/public/theme-simple/` para a raiz
2. Execute apenas `npm run build`
3. Ative o tema

## Checklist de Instalação

### Pré-requisitos
- [ ] WordPress >= 5.8
- [ ] PHP >= 7.4
- [ ] Node.js >= 14
- [ ] npm ou yarn
- [ ] Composer

### Instalação
- [ ] Clone/download do repositório
- [ ] `npm install`
- [ ] `composer install`
- [ ] `npm run build`
- [ ] Copiar arquivos compilados
- [ ] Verificar permissões
- [ ] Ativar tema no WordPress

### Verificação
- [ ] Tema aparece na lista
- [ ] Customizer carrega sem erros
- [ ] Frontend exibe corretamente
- [ ] Console do navegador sem erros
- [ ] Logs do WordPress sem erros

## Logs de Erro Comuns

### Erro 1: Arquivo CSS não encontrado
```
Failed to load resource: the server responded with a status of 404 (Not Found)
/wp-content/themes/uenf-geral/css/style.min.css
```
**Solução:** Execute `npm run build` e copie os arquivos compilados.

### Erro 2: JavaScript não carrega
```
Uncaught ReferenceError: $ is not defined
```
**Solução:** Verifique se o jQuery está sendo carregado corretamente.

### Erro 3: Customizer não funciona
```
PHP Fatal error: Cannot redeclare function
```
**Solução:** Verifique duplicação de funções entre arquivos.

## Contato e Suporte

Se os problemas persistirem após seguir este guia:

1. Verifique os logs de erro do WordPress
2. Teste em ambiente local primeiro
3. Compare com a versão funcional
4. Documente os erros específicos encontrados

---

**Última atualização:** $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")
**Versão do tema:** v1.0.2
**Autor:** Análise técnica baseada na estrutura do tema