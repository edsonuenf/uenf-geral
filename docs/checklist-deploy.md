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

### ✅ Preparação do Pacote
- [ ] **Limpeza**: Remover arquivos de desenvolvimento (.git, node_modules, etc.)
- [ ] **Versioning**: Atualizar número da versão no style.css
- [ ] **Changelog**: Atualizar arquivo de changelog
- [ ] **README**: Atualizar documentação
- [ ] **Compactação**: Criar arquivo ZIP do tema

### ✅ Teste do Pacote
- [ ] **Extração**: Testar extração do ZIP
- [ ] **Instalação**: Testar instalação via WordPress admin
- [ ] **Ativação**: Testar ativação do tema
- [ ] **Configurações**: Verificar se configurações são mantidas

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

## 📝 Notas Importantes

- **Sempre testar em ambiente de staging primeiro**
- **Fazer deploy em horários de menor tráfego**
- **Ter equipe de suporte disponível durante deploy**
- **Documentar qualquer problema encontrado**
- **Manter comunicação com stakeholders**

---

**Última atualização**: Janeiro 2025  
**Versão do checklist**: 1.0.0