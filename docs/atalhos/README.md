# Painel de Atalhos - Documentação Técnica

## 📋 Visão Geral

O Painel de Atalhos é um componente acessível que permite aos usuários acessar rapidamente links importantes do site. Ele aparece como um ícone flutuante no lado direito da tela e pode ser aberto para exibir uma lista de atalhos.

## 🎨 Estrutura de Arquivos

- `js/shortcut-panel.js` - Lógica principal do painel
- `css/components/shortcuts.css` - Estilos do painel
- `footer.php` - Inicialização do painel

## 🛠️ Configuração

### Inicialização

O painel é inicializado automaticamente quando o DOM está pronto. Não é necessária configuração adicional.

### Personalização

#### Cores

Você pode personalizar as cores do painel sobrescrevendo as variáveis CSS:

```css
:root {
  --shortcut-button-bg: #1D3771;       /* Cor de fundo do botão */
  --shortcut-panel-bg: #1D3771;        /* Cor de fundo do painel */
  --shortcut-item-bg: rgba(255,255,255,0.1); /* Fundo dos itens */
  --shortcut-item-text-color: #ffffff;  /* Cor do texto dos itens */
  --shortcut-header-text-color: #ffffff; /* Cor do cabeçalho */
}
```

#### Conteúdo

Para adicionar ou modificar os itens do painel, edite o HTML no arquivo `footer.php`.

## ♿ Acessibilidade

### Atributos ARIA

- `aria-expanded`: Indica se o painel está aberto
- `aria-hidden`: Indica se o painel está visível
- `role="dialog"`: Define o painel como uma caixa de diálogo
- `aria-modal="true"`: Indica que o painel é um modal

### Navegação por Teclado

- `Tab`: Navega entre os itens do painel
- `Shift + Tab`: Navega para trás entre os itens
- `Enter`: Ativa o item selecionado
- `Esc`: Fecha o painel

## 📱 Responsividade

O painel é totalmente responsivo e se adapta a diferentes tamanhos de tela:

- **Desktop**: Ícone flutuante no canto direito
- **Tablet**: Ícone redimensionado para melhor usabilidade
- **Mobile**: Ícone maior e mais fácil de tocar

## 🔌 API Pública

### Métodos

```javascript
// Abre o painel
window.uenfShortcutPanel.open();

// Fecha o painel
window.uenfShortcutPanel.close();

// Alterna o estado do painel
window.uenfShortcutPanel.toggle();

// Retorna true se o painel estiver aberto
const isOpen = window.uenfShortcutPanel.isOpen();
```

### Eventos

```javascript
// Painel pronto
document.addEventListener('shortcutPanelReady', (e) => {
  console.log('Painel pronto', e.detail);
});

// Painel aberto
document.addEventListener('shortcutPanelOpen', () => {
  console.log('Painel aberto');
});

// Painel fechado
document.addEventListener('shortcutPanelClose', () => {
  console.log('Painel fechado');
});
```

## 🐛 Solução de Problemas

### O painel não aparece

1. Verifique se o JavaScript está habilitado no navegador
2. Verifique o console do navegador em busca de erros
3. Certifique-se de que os arquivos JS e CSS foram carregados corretamente

### Navegação por teclado não funciona

1. Verifique se há erros de JavaScript no console
2. Certifique-se de que nenhum outro script está interferindo
3. Verifique se os atributos ARIA estão corretos

### O ícone não está posicionado corretamente

1. Verifique se há conflitos de CSS
2. Verifique se as posições salvas no localStorage estão corrompidas
3. Tente limpar o cache do navegador

## 📝 Notas de Atualização

### Versão 1.0.0

- Lançamento inicial do painel de atalhos
- Suporte a teclado e leitores de tela
- Arrastar e soltar do ícone
- API pública para controle programático

---

📅 **Última Atualização**: 17/06/2025
