# Checklist de Desenvolvimento - Tema UENF Geral
#️⃣## 🚨 Tarefas Urgentes

- [ ] Migrar gradualmente CSS inline para SCSS/CSS externos nos principais templates (ex: header.php, footer.php, search.php)
- [ ] Instalar e configurar Sass para SCSS/minificação
- [ ] Testar visual do tema após cada migração parcial
- [ ] Atualizar documentação e eliminar duplicatas
- [ ] Criar painel de configurações em "Aparência > Design" para facilitar ajustes visuais
- [ ] Revisar e aprimorar opções do Customizer (Personalizar)
- [ ] Validar .gitignore para garantir que arquivos sensíveis/build não sejam enviados
- [ ] Sincronizar branches develop/main após mudanças críticas


## 📚 Documentação Importante

- 📄 [Procedimento para Gerenciamento de Plugins](./docs/gerenciamento-de-plugins.md) - Diretrizes para adicionar novas funcionalidades via plugins
- 🎨 [Estilização de Componentes](./css/components/scrollbars.css) - Diretrizes para estilização de componentes, incluindo barras de rolagem
- 🔄 [Integração com GitHub](./docs/integracao-github-wordpress.md) - Como configurar atualizações automáticas a partir do GitHub

## 🚨 Prioridades Imediatas

### 📝 Melhorias no Customizer

📄 [Documentação Detalhada das Melhorias do Customizer](./docs/melhorias-customizer.md)

- [ ] Padronizar valores padrão de cores e fontes
  - [ ] Definir constantes para valores padrão no `functions.php`
  - [ ] Garantir consistência entre `style.css` e `customizer.php`
  - [ ] Documentar todos os valores padrão em um único local
- [ ] Melhorar a estrutura de arquivos CSS
  - [ ] Mover variáveis CSS para `css/variables.css`
  - [ ] Ajustar ordem de carregamento dos estilos
  - [ ] Garantir que o CSS do Customizer sobrescreva corretamente os estilos
- [ ] Implementar botão "Redefinir Padrões"
  - [ ] Adicionar botão no painel do Customizer
  - [ ] Implementar lógica para restaurar valores padrão
  - [ ] Adicionar confirmação antes de redefinir
- [ ] Otimizar desempenho
  - [ ] Reduzir duplicação de estilos
  - [ ] Melhorar a especificidade do CSS
  - [ ] Garantir carregamento otimizado dos estilos
- [ ] Melhorar documentação
  - [ ] Adicionar comentários explicativos no código
  - [ ] Documentar a estrutura das opções do Customizer
  - [ ] Criar guia de personalização para desenvolvedores

## 📋 Tarefas de Segurança

📄 [Documentação: Proteção contra Spam](./docs/protecao-contra-spam.md)

### ✅ Concluídas
- [x] Personalizar barras de rolagem
  - [x] Criar arquivo `scrollbars.css` com estilos personalizados
  - [x] Implementar cores do tema UENF
  - [x] Adicionar suporte a tema claro/escuro
  - [x] Garantir compatibilidade entre navegadores
  - [x] Otimizar para dispositivos móveis

- [x] Implementar validação de dados em formulários
  - [x] Criar classe de validação no servidor
  - [x] Implementar validação no cliente com JavaScript
  - [x] Adicionar estilos para mensagens de erro
  - [x] Criar métodos de validação personalizados (CPF, CNPJ, etc.)
- [x] Adicionar proteção contra CSRF com nonces
- [x] Implementar escape de saída em templates
- [x] Adicionar verificação de permissões
- [x] Criar sistema de sanitização de entradas
- [x] Implementar headers de segurança
- [x] Desabilitar edição de arquivos via painel administrativo
- [x] Remover informações de versão do WordPress
- [x] Prevenir enumeração de usuários
- [x] Desabilitar XML-RPC se não for necessário
- [x] Restringir tipos de arquivo para upload
- [x] Prevenir acesso a arquivos sensíveis

### ⏳ Pendentes
- [ ] Implementar rate limiting para formulários
- [ ] Adicionar proteção contra spam (honeypot, reCAPTCHA)
- [ ] Implementar auditoria de segurança
- [ ] Configurar políticas de segurança de conteúdo (CSP)
- [ ] Implementar autenticação de dois fatores
- [ ] Configurar permissões de arquivos e diretórios
- [ ] Implementar logging de atividades suspeitas
- [ ] Configurar backup automático
- [ ] Implementar proteção contra força bruta
- [ ] Configurar monitoramento de segurança

## 🎨 Melhorias de UX/UI

### ✅ Concluídas
- [x] Melhorar feedback visual em formulários
- [x] Adicionar validação em tempo real
- [x] Melhorar mensagens de erro
- [x] Adicionar máscaras para campos (CPF, telefone, etc.)
- [x] Melhorar acessibilidade em formulários

### ⏳ Pendentes
- [ ] Criar templates de e-mail personalizados
- [ ] Melhorar feedback durante envio de formulários
- [ ] Adicionar animações de transição
- [ ] Criar componentes de interface reutilizáveis
- [ ] Otimizar para dispositivos móveis

## 🚀 Otimizações

### ✅ Concluídas
- [x] Implementar carregamento assíncrono de scripts
- [x] Otimizar imagens automaticamente
- [x] Minificar CSS e JavaScript
- [x] Implementar cache de navegador
- [x] Otimizar consultas ao banco de dados

### ⏳ Pendentes
- [ ] Implementar carregamento lazy para imagens
- [ ] Otimizar fontes web
- [ ] Implementar service workers para modo offline
- [ ] Configurar CDN
- [ ] Otimizar CSS/JS crítico

## 📱 Recursos Responsivos

### ✅ Concluídas
- [x] Layout responsivo básico
- [x] Menu mobile
- [x] Imagens responsivas
- [x] Tipografia responsiva

### ⏳ Pendentes
- [ ] Testar em diferentes dispositivos
- [ ] Otimizar para tablets
- [ ] Melhorar experiência em telas grandes
- [ ] Implementar imagens responsivas com srcset

## 🔍 SEO

### ✅ Concluídas
- [x] Meta tags básicas
- [x] Schema.org markup
- [x] Sitemap.xml
- [x] Robots.txt

### ⏳ Pendentes
- [ ] Otimizar velocidade de carregamento
- [ ] Melhorar acessibilidade
- [ ] Implementar breadcrumbs
- [ ] Criar páginas de erro personalizadas (404, 500, etc.) a página de erro 404 já está funcionando

## 💡 Inovações e Melhorias

### Implementadas
1. **Sistema de Validação Avançado**
   - Validação no cliente e servidor
   - Suporte a máscaras e formatação
   - Feedback visual em tempo real
   - Suporte a validações personalizadas

2. **Segurança Reforçada**
   - Proteção contra XSS, CSRF e injeção SQL
   - Headers de segurança configurados
   - Controle de acesso granular
   - Sanitização de dados abrangente

3. **Otimizações de Performance**
   - Carregamento otimizado de recursos
   - Cache eficiente
   - Código minificado e otimizado
   - Boas práticas de performance implementadas

### Recomendadas
1. **Segurança**
   - Implementar autenticação de dois fatores
   - Configurar monitoramento de segurança 24/7
   - Realizar testes de penetração regulares
   - Manter todos os componentes atualizados

2. **Performance**
   - Implementar CDN para recursos estáticos
   - Otimizar imagens com WebP e AVIF
   - Implementar carregamento lazy para componentes pesados
   - Utilizar service workers para cache avançado

3. **UX/UI**
   - Implementar dark mode
   - Adicionar modo de alto contraste
   - Melhorar feedback tátil em dispositivos touch
   - Criar microinterações para melhor engajamento

4. **Acessibilidade**
   - Garantir conformidade com WCAG 2.1
   - Implementar navegação por teclado
   - Adicionar suporte a leitores de tela
   - Garantir contraste adequado

5. **Manutenção**
   - Documentar todo o código
   - Criar guia de estilo para desenvolvedores
   - Implementar testes automatizados
   - Configurar CI/CD para deploy contínuo

## 📅 Próximos Passos
1. Revisar e priorizar tarefas pendentes
2. Atribuir responsáveis
3. Definir prazos
4. Implementar em sprints
5. Testar e validar cada funcionalidade
Atualizado em: 06/06/2025

## 🛠️ Ferramentas de Build e Organização de Código

   - Comando: `npm install -g sass` ou `yarn global add sass`
   - Criar pasta `scss/` para arquivos fonte
   - Gerar arquivos minificados em `css/` automaticamente
   - Centralizar todo o CSS em arquivos `.scss`/`.css`
   - Centralizar todo o JS em arquivos `.js`
   - Garantir que arquivos minificados sejam gerados para produção

### Instruções detalhadas para migração e build SCSS

1. Estrutura de arquivos SCSS:
   - `scss/style.scss`: Arquivo principal, importa todos os outros.
   - `scss/variables.scss`: Variáveis globais (cores, fontes, bordas).
   - `scss/layout.scss`: Layout base (body, container).
   - `scss/components/`: Componentes (menu, header, footer, etc.).
#### Exemplos práticos

**Exemplo de arquivo de variáveis SCSS (`scss/variables.scss`):**
```scss
$primary-color: #1d3771;
$text-color: #333333;
$border-radius: 8px;
```

**Exemplo de uso em componente (`scss/components/menu.scss`):**
```scss
.menu {
   background: $primary-color;
   color: #fff;
   border-radius: $border-radius;
   a {
      color: #fff;
      &:hover {
         color: rgba(255,255,255,0.7);
      }
   }
}
```

**Exemplo de comando para compilar e minificar:**
```bash
sass scss/style.scss css/style.min.css --style=compressed --watch
```

**Exemplo de como carregar o CSS minificado no `functions.php`:**
```php
wp_enqueue_style('uenf-style', get_template_directory_uri() . '/css/style.min.css', array(), filemtime(get_template_directory() . '/css/style.min.css'));
```

---
### Próximo passo recomendado

1. Migrar o CSS de um componente simples (ex: menu) para o SCSS correspondente.
2. Compilar o SCSS e testar o visual no navegador.
3. Validar se o CSS minificado está sendo carregado corretamente pelo tema.
4. Registrar o progresso no checklist e na documentação.

2. Migração dos estilos:
   - Mover gradualmente o CSS dos arquivos atuais para os SCSS correspondentes.
   - Manter a organização modular para facilitar manutenção.
   - Testar visual do tema a cada etapa.

3. Compilação e minificação:
   - Usar o comando:
     ```bash
     sass scss/style.scss css/style.min.css --style=compressed --watch
     ```
   - O arquivo gerado deve ser carregado no tema em vez dos antigos CSS.

4. Carregamento correto no tema:
   - No `functions.php`, garantir que o tema carregue apenas o CSS minificado gerado pelo Sass.
   - Remover referências antigas a arquivos CSS que não são mais usados.

5. Documentação e checklist:
   - Registrar cada etapa migrada e testada.
   - Atualizar a documentação do projeto conforme mudanças.
   - Eliminar duplicatas e manter apenas instruções válidas.

## 🔄 Plano de Migração Gradual para SCSS e Minificação
- [ ] Migrar arquivos CSS para SCSS por partes (componentes, layout, utilitários)
- [ ] Refatorar gradualmente arquivos PHP/HTML para remover CSS/JS inline
- [ ] Testar visual do tema a cada etapa para evitar impactos
- [ ] Validar visual em ambiente de desenvolvimento antes de publicar
- [ ] Atualizar documentação conforme cada etapa concluída
- [ ] Eliminar duplicatas e consolidar informações na documentação

## 🖌️ Aparência/Design no WordPress
- [ ] Criar painel de configurações em "Aparência > Design" para facilitar ajustes visuais
- [ ] Permitir escolha de cores, fontes, espaçamentos, logo, etc. via painel
- [ ] Documentar todas as opções disponíveis para o usuário

## 🎨 Personalizar do Tema
- [ ] Revisar e aprimorar opções do Customizer
- [ ] Adicionar novas opções de personalização (cores, layout, tipografia)
- [ ] Garantir que todas as opções estejam documentadas

## 🖼️ Alinhamento de Imagens e Listas (WordPress)
- [ ] Revisar e ajustar estrutura HTML dos blocos de conteúdo para imagens alinhadas e listas
- [ ] Garantir que o CSS de alinhamento (`alignleft`, `alignright`, `aligncenter`) está presente e funcional em `style.min.css` e `editor-style.css`
- [ ] Testar visual de imagens alinhadas com listas (ul/ol) no front-end e editor do WordPress
- [ ] Corrigir eventuais conflitos de CSS global que afetem listas ou alinhamento
- [ ] Documentar exemplos de uso correto no PRD.md

---
Atualizado em: 12/08/2025
