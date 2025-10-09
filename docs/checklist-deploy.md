# 📋 Checklist de Deploy - Tema UENF

## 🔍 Pré-Deploy (Verificações Obrigatórias)

### ✅ Verificações de Código
- [ ] **Sintaxe PHP**: Verificar erros de sintaxe em todos os arquivos PHP
- [ ] **Logs de erro**: Verificar se não há erros no log do WordPress
- [ ] **Funções depreciadas**: Verificar uso de funções WordPress obsoletas
- [ ] **Segurança**: Verificar se não há vulnerabilidades de segurança
- [ ] **Escape de dados**: Verificar se todos os outputs estão escapados corretamente
- [ ] **Nonces**: Verificar se todas as ações sensíveis usam nonces

### ✅ Verificações de Performance
- [ ] **Otimização de imagens**: Verificar se todas as imagens estão otimizadas
- [ ] **Minificação CSS/JS**: Verificar se assets estão minificados
- [ ] **Cache**: Testar funcionamento com plugins de cache
- [ ] **Lazy loading**: Verificar se imagens carregam sob demanda
- [ ] **Critical CSS**: Verificar se CSS crítico está inline

### ✅ Verificações de Compatibilidade
- [ ] **Versão WordPress**: Testar com versão atual do WordPress
- [ ] **Versão PHP**: Testar com PHP 7.4+ e 8.0+
- [ ] **Plugins populares**: Testar com WooCommerce, Yoast SEO, etc.
- [ ] **Navegadores**: Testar em Chrome, Firefox, Safari, Edge
- [ ] **Dispositivos**: Testar responsividade em mobile/tablet/desktop

## 🎨 Verificações de Design e UX

### ✅ Layout e Responsividade
- [ ] **Breakpoints**: Testar todos os breakpoints responsivos
- [ ] **Tipografia**: Verificar legibilidade em todos os tamanhos
- [ ] **Cores**: Verificar contraste e acessibilidade (WCAG)
- [ ] **Espaçamentos**: Verificar consistência de margins/paddings
- [ ] **Alinhamentos**: Verificar alinhamento de elementos

### ✅ Funcionalidades de Interface
- [ ] **Navegação**: Testar todos os menus e links
- [ ] **Formulários**: Testar envio e validação de formulários
- [ ] **Busca**: Testar funcionalidade de busca
- [ ] **Comentários**: Testar sistema de comentários
- [ ] **Widgets**: Testar funcionamento de widgets

## 🔧 Verificações de Extensões

### ✅ Sistema de Extensões
- [ ] **Ativação/Desativação**: Testar toggle de cada extensão
- [ ] **Dependências**: Verificar se dependências estão funcionando
- [ ] **Performance**: Verificar impacto no carregamento
- [ ] **Conflitos**: Verificar se não há conflitos entre extensões

### ✅ Extensões Específicas
- [ ] **Modo Escuro**: Testar alternância e persistência
- [ ] **Sistema de Cores**: Testar paletas e contraste
- [ ] **Tipografia**: Testar fontes e escalas tipográficas
- [ ] **Ícones**: Testar carregamento e exibição de ícones
- [ ] **Animações**: Testar performance e acessibilidade
- [ ] **Gradientes**: Testar aplicação e fallbacks
- [ ] **Sombras**: Testar elevation system
- [ ] **Busca Personalizada**: Testar funcionalidade completa
- [ ] **Design Tokens**: Testar sincronização de variáveis
- [ ] **Padrões**: Testar biblioteca de componentes
- [ ] **Breakpoints**: Testar pontos de quebra customizados

## 🗄️ Verificações de Banco de Dados

### ✅ Configurações do Tema
- [ ] **Theme mods**: Verificar se configurações estão salvas corretamente
- [ ] **Customizer**: Testar todas as opções do customizer
- [ ] **Backup**: Criar backup das configurações atuais
- [ ] **Migração**: Testar importação/exportação de configurações

## 🚀 Processo de Deploy

### ✅ Preparação da Branch de Produção

#### Build e Compilação
- [ ] **Branch atual**: Verificar se está na branch de desenvolvimento (`sistema-de-busca`)
- [ ] **Dependências**: Executar `npm install` se necessário
- [ ] **Criar branch production**: `git checkout -b production` (primeira vez)
- [ ] **Instalar dependências**: `npm install` na branch production
- [ ] **Build de produção**: `npm run build`
- [ ] **Verificar assets**: Confirmar geração de `assets/dist/css/style.min.css` e `assets/dist/js/main.js`

#### Versionamento e Documentação
- [ ] **Versioning**: Atualizar número da versão no style.css
- [ ] **Changelog**: Atualizar arquivo de changelog
- [ ] **README**: Atualizar documentação
- [ ] **Commit assets**: `git add assets/dist/` e commit com mensagem descritiva

#### Publicação no GitHub
- [ ] **Push inicial**: `git push -u origin production` (primeira vez)
- [ ] **Push atualização**: `git push origin production` (atualizações)
- [ ] **Verificar GitHub**: Confirmar que branch production está no repositório

### ✅ Atualizações da Branch de Produção

#### Workflow de Atualização
- [ ] **Voltar para desenvolvimento**: `git checkout sistema-de-busca`
- [ ] **Fazer mudanças**: Desenvolver e commitar normalmente
- [ ] **Push desenvolvimento**: `git push origin sistema-de-busca`
- [ ] **Checkout produção**: `git checkout production`
- [ ] **Merge mudanças**: `git merge sistema-de-busca`
- [ ] **Rebuild assets**: `npm run build`
- [ ] **Commit assets**: `git add assets/dist/` e commit
- [ ] **Push produção**: `git push origin production`

### ✅ Deploy em Servidor

#### Via Git (Recomendado)
- [ ] **Clone repositório**: `git clone [repo-url]` no servidor
- [ ] **Checkout produção**: `git checkout production`
- [ ] **Verificar assets**: Confirmar que `assets/dist/` existe e está populado
- [ ] **Configurar WordPress**: Ativar tema via admin ou wp-cli

#### Via FTP/Upload
- [ ] **Download branch**: Baixar branch `production` do GitHub
- [ ] **Verificar assets**: Confirmar que `assets/dist/` está incluído
- [ ] **Upload seletivo**: Fazer upload apenas dos arquivos necessários
- [ ] **Preservar configurações**: Manter configurações existentes do WordPress

### ✅ Deploy em Produção
- [ ] **Backup**: Fazer backup completo do site
- [ ] **Manutenção**: Ativar modo de manutenção
- [ ] **Upload**: Fazer upload do novo tema
- [ ] **Ativação**: Ativar o tema atualizado
- [ ] **Verificação**: Verificar funcionamento completo
- [ ] **Cache**: Limpar todos os caches
- [ ] **Manutenção**: Desativar modo de manutenção

## 🔄 Pós-Deploy

### ✅ Verificações Finais
- [ ] **Funcionalidade**: Testar todas as funcionalidades principais
- [ ] **Performance**: Verificar tempos de carregamento
- [ ] **SEO**: Verificar se meta tags estão corretas
- [ ] **Analytics**: Verificar se tracking está funcionando
- [ ] **Formulários**: Testar envio de formulários
- [ ] **E-commerce**: Testar processo de compra (se aplicável)

### ✅ Monitoramento
- [ ] **Logs de erro**: Monitorar logs por 24h
- [ ] **Performance**: Monitorar métricas de performance
- [ ] **Uptime**: Verificar disponibilidade do site
- [ ] **Feedback**: Coletar feedback dos usuários

## 🆘 Plano de Rollback

### ✅ Em Caso de Problemas
- [ ] **Backup disponível**: Confirmar que backup está acessível
- [ ] **Processo de restore**: Documentar passos para restaurar
- [ ] **Tempo de rollback**: Definir tempo máximo para rollback
- [ ] **Comunicação**: Plano de comunicação com usuários

---

## 🔧 Dicas Importantes sobre Webpack e Assets

### ⚡ Performance e Otimização
- **Assets compilados**: Os arquivos em `assets/dist/` são otimizados para produção
- **Minificação**: CSS e JS são automaticamente minificados no build
- **Source maps**: Disponíveis apenas em desenvolvimento
- **Cache busting**: Webpack gera hashes para controle de cache

### 🚨 Problemas Comuns e Soluções

#### Assets não carregam
- **Verificar**: Se `assets/dist/` existe e contém os arquivos
- **Solução**: Executar `npm run build` novamente
- **Causa comum**: Deploy sem executar build de produção

#### Estilos não aplicados
- **Verificar**: Se `style.min.css` está sendo carregado
- **Solução**: Limpar cache do navegador e do WordPress
- **Causa comum**: Cache antigo ou path incorreto

#### JavaScript não funciona
- **Verificar**: Console do navegador para erros
- **Solução**: Verificar se `main.js` está carregado corretamente
- **Causa comum**: Dependências não resolvidas ou sintaxe ES6 não suportada

### 📁 Estrutura de Assets

```
assets/
├── src/           # Arquivos fonte (desenvolvimento)
│   ├── css/
│   ├── js/
│   └── images/
└── dist/          # Arquivos compilados (produção)
    ├── css/
    │   └── style.min.css
    └── js/
        ├── main.js
        └── style.js
```

### 🔄 Comandos Úteis

- `npm run build`: Build de produção
- `npm run dev`: Build de desenvolvimento
- `npm run watch`: Watch mode para desenvolvimento
- `npm install`: Instalar/atualizar dependências

## 📝 Notas Importantes

- **Sempre testar em ambiente de staging primeiro**
- **Fazer deploy em horários de menor tráfego**
- **Ter equipe de suporte disponível durante deploy**
- **Documentar qualquer problema encontrado**
- **Manter comunicação com stakeholders**
- **NUNCA fazer deploy sem executar `npm run build`**
- **Sempre verificar se `assets/dist/` está no repositório**

---

**Última atualização**: Janeiro 2025  
**Versão do checklist**: 1.0.0