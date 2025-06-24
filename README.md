# Tema UENGeral

Tema WordPress personalizado para a Universidade Estadual do Norte Fluminense (UENF).

## 🚀 Painel de Atalhos

O tema inclui um painel de atalhos acessível que pode ser aberto clicando no ícone flutuante no lado direito da tela.

### Funcionalidades

- **Acessibilidade Total**: Navegação por teclado (Tab, Shift+Tab, Enter, Esc)
- **Arrastar e Soltar**: O ícone pode ser posicionado em qualquer lugar da tela
- **Responsivo**: Funciona em todos os tamanhos de tela
- **Personalizável**: Cores e estilos podem ser alterados via CSS
- **API Pública**: Controle o painel programaticamente

### Como Usar

1. O ícone do painel aparece automaticamente no canto direito da tela
2. Clique no ícone para abrir/fechar o painel
3. Use Tab/Shift+Tab para navegar entre os itens
4. Pressione Enter para ativar um item
5. Pressione Esc para fechar o painel

### API JavaScript

```javascript
// Abrir o painel
window.uenfShortcutPanel.open();

// Fechar o painel
window.uenfShortcutPanel.close();

// Alternar o painel
window.uenfShortcutPanel.toggle();

// Verificar se está aberto
if (window.uenfShortcutPanel.isOpen()) {
    console.log('O painel está aberto');
}
```

### Eventos

- `shortcutPanelReady`: Disparado quando o painel está pronto
- `shortcutPanelOpen`: Disparado quando o painel é aberto
- `shortcutPanelClose`: Disparado quando o painel é fechado

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
