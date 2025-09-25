# Limpeza de Temas Padrão do WordPress

## 📋 Visão Geral

O sistema de limpeza de temas padrão permite remover completamente os temas padrão do WordPress (twentytwenty, twentytwentyone, etc.) tanto dos arquivos quanto do banco de dados, otimizando a instalação e removendo código desnecessário.

## 🎯 Funcionalidades

### ✅ Verificação de Temas
- Escaneia todos os temas padrão instalados
- Identifica o tema ativo (que não será removido)
- Mostra informações detalhadas de cada tema encontrado

### 🗑️ Remoção Completa
- Remove arquivos físicos dos temas
- Limpa registros do banco de dados
- Remove configurações do customizer
- Limpa transients relacionados
- Atualiza cache de temas

### 🛡️ Proteções de Segurança
- Não remove o tema ativo
- Verificação de permissões
- Confirmação dupla antes da remoção
- Verificação de nonce para segurança AJAX

## 🚀 Como Usar

### 1. Acessar a Ferramenta
1. Faça login no WordPress Admin
2. Vá para **Tema UENF > Limpeza de Temas**
3. A página de limpeza será exibida

### 2. Verificar Temas Padrão
1. Clique em **"🔍 Verificar Temas Padrão"**
2. O sistema irá escanear e mostrar:
   - Temas padrão encontrados
   - Qual tema está ativo (protegido)
   - Quantos temas serão removidos

### 3. Remover Temas
1. Se temas foram encontrados, o botão **"🗑️ Remover Temas Padrão"** será habilitado
2. Clique no botão
3. Confirme a ação no diálogo de segurança
4. Aguarde a conclusão da remoção

## ⚠️ Avisos Importantes

### 🚨 Ação Irreversível
- A remoção é **PERMANENTE** e **NÃO PODE** ser desfeita
- Faça backup antes de executar a limpeza
- Certifique-se de que não precisa dos temas padrão

### 🛡️ Tema Ativo Protegido
- O tema atualmente ativo **NUNCA** será removido
- Mesmo que seja um tema padrão
- Isso previne quebra do site

### 📦 Temas Incluídos na Limpeza
Os seguintes temas padrão são detectados e removidos:
- twentytwentyfour
- twentytwentythree
- twentytwentytwo
- twentytwentyone
- twentytwenty
- twentynineteen
- twentyeighteen
- twentyseventeen
- twentysixteen
- twentyfifteen
- twentyfourteen
- twentythirteen
- twentytwelve
- twentyeleven
- twentyten

## 🔧 Detalhes Técnicos

### Arquivos Removidos
- Diretório completo do tema em `/wp-content/themes/`
- Todos os arquivos PHP, CSS, JS e imagens
- Subdiretórios e arquivos de configuração

### Banco de Dados Limpo
- `theme_mods_{tema}` - Configurações do customizer
- Transients relacionados ao tema
- Cache de opções atualizado

### Segurança
- Verificação de permissões `manage_options`
- Nonce de segurança para requisições AJAX
- Validação de entrada de dados
- Proteção contra acesso direto

## 📊 Interface do Usuário

### Elementos Visuais
- **Card de Verificação**: Escaneia temas instalados
- **Card de Remoção**: Executa a limpeza
- **Indicadores Visuais**: Ícones e cores para status
- **Feedback em Tempo Real**: Mensagens de sucesso/erro

### Estados dos Botões
- **Desabilitado**: Quando não há temas para remover
- **Carregando**: Durante operações AJAX
- **Habilitado**: Quando temas foram encontrados

## 🎨 Benefícios da Limpeza

### 🚀 Performance
- Reduz o número de arquivos no servidor
- Diminui o tempo de escaneamento de temas
- Menos dados no banco de dados

### 🔒 Segurança
- Remove código não utilizado
- Reduz superfície de ataque
- Elimina temas desatualizados

### 🧹 Organização
- Interface mais limpa no admin
- Foco apenas no tema em uso
- Reduz confusão para usuários

## 🛠️ Solução de Problemas

### Erro de Permissões
```
Permissões insuficientes
```
**Solução**: Certifique-se de estar logado como administrador

### Erro de Segurança
```
Erro de segurança
```
**Solução**: Recarregue a página e tente novamente

### Tema Não Removido
**Possíveis Causas**:
- Tema está ativo (proteção automática)
- Permissões de arquivo insuficientes
- Tema não é padrão do WordPress

### Verificação Manual
Para verificar se a limpeza foi bem-sucedida:
1. Vá para **Aparência > Temas**
2. Verifique se apenas seu tema ativo está listado
3. Confirme no servidor que os diretórios foram removidos

## 📝 Log de Atividades

O sistema registra as seguintes atividades:
- Temas encontrados durante verificação
- Temas removidos com sucesso
- Erros durante a remoção
- Limpeza do banco de dados

## 🔄 Integração com Outros Sistemas

### Reset de Configurações
- Compatível com o sistema de reset do tema
- Pode ser usado em conjunto com limpeza geral

### Gerenciador de Extensões
- Integrado ao menu principal do Tema UENF
- Segue os mesmos padrões de segurança

## 📞 Suporte

Se encontrar problemas:
1. Verifique as permissões de arquivo
2. Confirme que está logado como administrador
3. Verifique os logs de erro do WordPress
4. Consulte a documentação técnica

---

**Nota**: Esta ferramenta foi desenvolvida especificamente para o Tema UENF e segue todas as melhores práticas de segurança e usabilidade do WordPress.