/**
 * Shortcuts.js - Funcionalidades adicionais para o painel de atalhos
 * Este script complementa o shortcut-panel.js com funcionalidades específicas
 */
document.addEventListener('DOMContentLoaded', function() {
    const shortcutPanel = document.querySelector('.shortcut-panel');
    const shortcutItems = document.querySelectorAll('.shortcut-item');
    
    // Adicionar funcionalidades extras apenas se o painel existir
    if (!shortcutPanel) return;
    
    // Adicionar funcionalidade de clique nos itens do painel
    shortcutItems.forEach(item => {
        // Adiciona atributos ARIA para acessibilidade
        item.setAttribute('role', 'link');
        item.setAttribute('tabindex', '0');
        
        // Adiciona feedback visual ao focar
        item.addEventListener('focus', function() {
            this.classList.add('focused');
        });
        
        item.addEventListener('blur', function() {
            this.classList.remove('focused');
        });
        
        // Adiciona navegação por teclado
        item.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const url = this.getAttribute('href');
                if (url) {
                    window.location.href = url;
                }
            }
            
            // Navegação por teclado entre os itens
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                const items = Array.from(shortcutItems);
                const currentIndex = items.indexOf(this);
                let nextIndex;
                
                if (e.key === 'ArrowDown') {
                    nextIndex = (currentIndex + 1) % items.length;
                } else {
                    nextIndex = (currentIndex - 1 + items.length) % items.length;
                }
                
                items[nextIndex].focus();
            }
        });
        
        // Adiciona feedback tátil em dispositivos móveis
        item.addEventListener('touchstart', function() {
            this.classList.add('touching');
        }, { passive: true });
        
        item.addEventListener('touchend', function() {
            this.classList.remove('touching');
        }, { passive: true });
    });
    
    // Focar no primeiro item ao abrir o painel
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                const isActive = shortcutPanel.classList.contains('active');
                const firstItem = shortcutPanel.querySelector('.shortcut-item');
                
                if (isActive && firstItem) {
                    // Usa setTimeout para garantir que a transição CSS seja concluída
                    setTimeout(() => {
                        firstItem.focus();
                    }, 300);
                }
            }
        });
    });
    
    // Inicia a observação das mudanças no painel
    observer.observe(shortcutPanel, { attributes: true });
    
    // Adiciona estilos dinâmicos para feedback visual
    const style = document.createElement('style');
    style.textContent = `
        .shortcut-item.focused {
            outline: 2px solid var(--shortcut-item-hover-text-color, #ffffff);
            outline-offset: -2px;
        }
        
        .shortcut-item.touching {
            transform: scale(0.98);
            opacity: 0.9;
        }
        
        /* Garante que os itens sejam focáveis apenas quando o painel estiver visível */
        .shortcut-panel:not(.active) .shortcut-item {
            visibility: hidden;
            pointer-events: none;
        }
    `;
    document.head.appendChild(style);
});
