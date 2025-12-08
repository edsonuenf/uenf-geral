# 🛠️ Guia de Instalação - Tema UENF Geral

## 📋 Pré-requisitos

### 🖥️ **Requisitos do Servidor**
- **WordPress:** 6.0 ou superior
- **PHP:** 7.4 ou superior (8.0+ recomendado)
- **MySQL:** 5.7 ou superior (8.0+ recomendado)
- **Memória:** 256MB mínimo (512MB recomendado)
- **Espaço em disco:** 50MB para o tema

### 🌐 **Navegadores Suportados**
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

---

## 🚀 Métodos de Instalação

### 📥 **Método 1: Upload via WordPress Admin**

1. **Baixe o tema:**
   - Acesse o repositório GitHub
   - Clique em "Code" → "Download ZIP"
   - Salve o arquivo `uenf-geral.zip`

2. **Acesse o WordPress Admin:**
   ```
   https://seusite.com/wp-admin
   ```

3. **Navegue para Temas:**
   ```
   Aparência → Temas → Adicionar Novo
   ```

4. **Faça Upload:**
   - Clique em "Enviar Tema"
   - Selecione o arquivo `uenf-geral.zip`
   - Clique em "Instalar Agora"

5. **Ative o Tema:**
   - Clique em "Ativar" após a instalação

### 📁 **Método 2: Upload via FTP**

1. **Baixe e extraia o tema:**
   ```bash
   # Baixe o ZIP e extraia
   unzip uenf-geral.zip
   ```

2. **Conecte via FTP:**
   - Use FileZilla, WinSCP ou similar
   - Conecte ao seu servidor

3. **Navegue para a pasta de temas:**
   ```
   /public_html/wp-content/themes/
   ```

4. **Faça upload da pasta:**
   - Envie a pasta `uenf-geral` completa
   - Aguarde o upload finalizar

5. **Ative no WordPress:**
   - Vá em Aparência → Temas
   - Encontre "UENF Geral" e clique em "Ativar"

### 💻 **Método 3: Git Clone (Desenvolvedores)**

1. **Acesse a pasta de temas:**
   ```bash
   cd /caminho/para/wordpress/wp-content/themes/
   ```

2. **Clone o repositório:**
   ```bash
   git clone https://github.com/edsonuenf/uenf-geral.git
   ```

3. **Instale dependências (se necessário):**
   ```bash
   cd uenf-geral
   npm install
   composer install
   ```

4. **Ative no WordPress Admin**

---

## ⚙️ Configuração Inicial

### 🎨 **1. Configuração Básica (5 minutos)**

1. **Acesse o Customizer:**
   ```
   Aparência → Personalizar
   ```

2. **Configure Identidade do Site:**
   - Título do site: "UENF - Universidade Estadual do Norte Fluminense"
   - Descrição: "Excelência em Ensino, Pesquisa e Extensão"
   - Upload do logo da UENF

3. **Configuração de Cores:**
   - Vá para "Cores" no Customizer
   - Selecione paleta "UENF Institucional" ou configure:
     - Primária: #0066CC (Azul UENF)
     - Secundária: #004499 (Azul escuro)
     - Destaque: #FF6600 (Laranja)

4. **Configuração de Tipografia:**
   - Vá para "Tipografia"
   - Títulos: Roboto Bold
   - Texto: Open Sans Regular

### 🏗️ **2. Configuração de Layout (10 minutos)**

1. **Estrutura Principal:**
   - Vá para "Layout" no Customizer
   - Escolha "Two Column Right" (duas colunas com sidebar à direita)
   - Configure largura do container: 1200px

2. **Grid System:**
   - Colunas: 12
   - Gap: 20px
   - Max Width: 1200px

3. **Breakpoints Responsivos:**
   - Vá para "Breakpoints"
   - Selecione preset "Bootstrap"
   - Ou configure manualmente:
     - Mobile: 768px
     - Tablet: 1024px
     - Desktop: 1200px

### 🎯 **3. Configuração Avançada (15 minutos)**

1. **Design Tokens:**
   - Configure tokens centralizados
   - Exporte configurações para backup

2. **Sistema de Ícones:**
   - Faça upload de ícones personalizados da UENF
   - Configure biblioteca de ícones

3. **Modo Escuro:**
   - Ative modo escuro opcional
   - Configure cores para modo escuro

4. **Animações:**
   - Configure micro-interações
   - Ajuste performance para mobile

---

## 🔧 Configurações Opcionais

### 📱 **Configuração Mobile**

1. **Responsive Design:**
   - Teste em diferentes dispositivos
   - Ajuste breakpoints se necessário
   - Configure menu mobile

2. **Performance Mobile:**
   - Ative lazy loading
   - Configure compressão de imagens
   - Otimize carregamento de fontes

### 🎨 **Personalização Avançada**

1. **CSS Personalizado:**
   - Use o editor CSS integrado
   - Adicione estilos específicos da UENF
   - Configure cores institucionais

2. **JavaScript Personalizado:**
   - Adicione funcionalidades específicas
   - Configure tracking e analytics
   - Implemente integrações necessárias

---

## 🧪 Verificação da Instalação

### ✅ **Checklist Pós-Instalação**

- [ ] Tema ativado com sucesso
- [ ] Customizer carregando sem erros
- [ ] Cores configuradas corretamente
- [ ] Tipografia funcionando
- [ ] Layout responsivo
- [ ] Ícones carregando
- [ ] Animações funcionando
- [ ] Performance adequada
- [ ] Compatibilidade com plugins
- [ ] Backup das configurações

### 🔍 **Testes de Funcionalidade**

1. **Teste o Customizer:**
   ```
   Aparência → Personalizar
   → Teste cada módulo do Design System
   → Verifique preview em tempo real
   → Salve e publique alterações
   ```

2. **Teste Responsividade:**
   - Redimensione a janela do navegador
   - Teste em dispositivos móveis
   - Verifique breakpoints

3. **Teste Performance:**
   - Use Google PageSpeed Insights
   - Verifique tempo de carregamento
   - Teste em conexões lentas

---

## 🚨 Solução de Problemas

### ❌ **Problemas Comuns**

**Tema não aparece na lista:**
- Verifique se a pasta está em `/wp-content/themes/`
- Confirme que o arquivo `style.css` existe
- Verifique permissões de arquivo (755 para pastas, 644 para arquivos)

**Customizer não carrega:**
- Desative todos os plugins temporariamente
- Verifique se há erros JavaScript no console
- Limpe cache do navegador

**Erros de memória:**
- Aumente limite de memória PHP para 512MB
- Desative plugins desnecessários
- Otimize banco de dados

**Problemas de performance:**
- Ative cache de página
- Otimize imagens
- Use CDN se possível

### 🛠️ **Modo Debug**

Para diagnosticar problemas, ative o modo debug:

```php
// Adicione no wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

### 📞 **Suporte Técnico**

- **Email:** suporte@uenf.br
- **Telefone:** (22) 2739-7000
- **Issues GitHub:** [Reportar Problema](https://github.com/edsonuenf/uenf-geral/issues)

---

## 🔄 Atualizações

### 📥 **Como Atualizar o Tema**

1. **Backup Completo:**
   - Faça backup do site completo
   - Exporte configurações do Customizer
   - Salve personalizações CSS

2. **Método Git (Recomendado):**
   ```bash
   cd wp-content/themes/uenf-geral
   git pull origin main
   ```

3. **Método Manual:**
   - Baixe nova versão
   - Substitua arquivos (mantenha configurações)
   - Teste funcionalidades

### 🔔 **Notificações de Atualização**

- Siga o repositório no GitHub
- Ative notificações de releases
- Verifique changelog antes de atualizar

---

## 🎓 Próximos Passos

Após a instalação bem-sucedida:

1. **Leia a [Documentação Completa](DOCUMENTACAO-TEMA-UENF.md)**
2. **Consulte o [Manual do Usuário](MANUAL-USUARIO.md)**
3. **Explore todos os módulos do Design System CCT**
4. **Configure conteúdo específico da UENF**
5. **Teste em diferentes dispositivos**

---

**✅ Instalação concluída com sucesso! Seu tema UENF Geral está pronto para uso.**

*© 2024 Universidade Estadual do Norte Fluminense - UENF*