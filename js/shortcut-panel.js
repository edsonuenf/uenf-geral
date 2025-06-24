/**
 * Painel de Atalhos - Script de Acessibilidade
 * 
 * Este script gerencia o painel de atalhos, fornecendo navegação por teclado,
 * gerenciamento de foco e atributos ARIA para melhorar a acessibilidade.
 * 
 * @version     1.0.0
 * @author      Equipe de Desenvolvimento UENF
 * @license     MIT
 * 
 * Funcionalidades principais:
 * - Navegação por teclado completa (Tab, Shift+Tab, Enter, Espaço, Esc)
 * - Gerenciamento de foco acessível
 * - Atributos ARIA para leitores de tela
 * - Arrastar e soltar do ícone com persistência
 * - Fechar ao clicar fora ou pressionar Esc
 * - Suporte a toque em dispositivos móveis
 * - Estilos de foco visíveis apenas para navegação por teclado
 * - API pública para controle programático
 * 
 * Eventos personalizados:
 * - shortcutPanelReady: Disparado quando o painel está pronto
 * - shortcutPanelOpen: Disparado quando o painel é aberto
 * - shortcutPanelClose: Disparado quando o painel é fechado
 * 
 * API Pública (acessível via window.uenfShortcutPanel):
 * - open(): Abre o painel
 * - close(): Fecha o painel
 * - toggle(): Alterna o estado do painel
 * - isOpen(): Retorna true se o painel estiver aberto
 * - version: Versão atual do script
 * 
 * Exemplo de uso:
 * ```javascript
 * // Abrir o painel
 * window.uenfShortcutPanel.open();
 * 
 * // Fechar o painel
 * window.uenfShortcutPanel.close();
 * 
 * // Verificar se está aberto
 * if (window.uenfShortcutPanel.isOpen()) {
 *     console.log('O painel está aberto');
 * }
 * ```
 */

// Elementos principais do DOM
let shortcutIcon = null;
let shortcutPanel = null;
let closeButton = null;
let firstMenuItem = null;
let lastFocusedElement = null;
let isDragging = false;
let offsetY = 0;

// Inicialização quando o DOM estiver pronto
function initShortcutPanel() {
    try {
        initializeElements();
        
        // Verifica se os elementos necessários existem
        if (!shortcutIcon || !shortcutPanel) {
            console.warn('Elementos do painel de atalhos não encontrados');
            return;
        }
        
        setupEventListeners();
        setupPanel();
        removeFocusStyles();
        
        // Adiciona classe de inicialização para estilos específicos
        document.documentElement.classList.add('shortcut-panel-initialized');
        
        // Dispara evento de inicialização
        const event = new CustomEvent('shortcutPanelReady', { 
            detail: { 
                version: '1.0.0',
                elements: { shortcutIcon, shortcutPanel, closeButton },
                announce: announce
            } 
        });
        document.dispatchEvent(event);
        
        // Anuncia que o painel está pronto para leitores de tela
        announce('Painel de atalhos carregado');
        
    } catch (error) {
        console.error('Erro ao inicializar o painel de atalhos:', error);
    }
}

/**
 * Anuncia mensagens para leitores de tela
 * @param {string} message - Mensagem a ser anunciada
 */
function announce(message, priority = 'polite') {
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', priority);
    announcement.setAttribute('role', 'status');
    announcement.setAttribute('aria-atomic', 'true');
    announcement.style.position = 'absolute';
    announcement.style.clip = 'rect(0 0 0 0)';
    announcement.style.clipPath = 'inset(50%)';
    announcement.style.height = '1px';
    announcement.style.width = '1px';
    announcement.style.overflow = 'hidden';
    announcement.style.whiteSpace = 'nowrap';
    announcement.textContent = message;
    
    document.body.appendChild(announcement);
    
    // Remove o elemento após um curto período
    setTimeout(() => {
        if (announcement.parentNode) {
            announcement.parentNode.removeChild(announcement);
        }
    }, 1000);
}

// Inicializa quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initShortcutPanel);
} else {
    // DOM já está pronto
    initShortcutPanel();
}

// Expõe a API pública
if (window.uenfShortcutPanel === undefined) {
    window.uenfShortcutPanel = {
        open: openPanel,
        close: closePanel,
        toggle: function() {
            if (shortcutPanel.getAttribute('aria-hidden') === 'true') {
                openPanel();
            } else {
                closePanel();
            }
        },
        isOpen: function() {
            return shortcutPanel && shortcutPanel.getAttribute('aria-hidden') === 'false';
        },
        version: '1.0.0'
    };
}

/**
 * Inicializa os elementos do DOM
 */
function initializeElements() {
    shortcutIcon = document.querySelector('.shortcut-icon');
    shortcutPanel = document.querySelector('.shortcut-panel');
    closeButton = document.querySelector('.close-panel');
    
    // Verifica se os elementos principais foram encontrados
    if (!shortcutIcon || !shortcutPanel) {
        console.warn('Elementos principais do painel de atalhos não encontrados');
        return;
    }
    
    // Carrega a posição salva do ícone
    loadIconPosition();
}

/**
 * Configura os listeners de eventos
 */
function setupEventListeners() {
    if (!shortcutIcon || !shortcutPanel) return;
    
    // Eventos do ícone
    shortcutIcon.addEventListener('click', handleIconClick);
    shortcutIcon.addEventListener('keydown', handleIconKeyDown);
    shortcutIcon.addEventListener('mousedown', startDrag);
    
    // Eventos de arrasto
    document.addEventListener('mousemove', handleDrag);
    document.addEventListener('mouseup', endDrag);
    
    // Evento global para fechar ao clicar fora
    document.addEventListener('click', handleOutsideClick, true);
    
    // Evento de teclado global para fechar com ESC
    document.addEventListener('keydown', handleGlobalKeyDown);
}

/**
 * Configura o painel com atributos ARIA e eventos
 */
function setupPanel() {
    // Atributos ARIA para o ícone
    shortcutIcon.setAttribute('aria-expanded', 'false');
    shortcutIcon.setAttribute('aria-haspopup', 'dialog');
    shortcutIcon.setAttribute('aria-label', 'Abrir menu de atalhos. Pressione Enter para expandir.');
    shortcutIcon.setAttribute('role', 'button');
    shortcutIcon.setAttribute('tabindex', '0');
    
    // Atributos ARIA para o painel
    shortcutPanel.setAttribute('aria-hidden', 'true');
    shortcutPanel.setAttribute('role', 'dialog');
    shortcutPanel.setAttribute('aria-modal', 'true');
    
    // Adiciona título acessível para o painel
    let panelTitle = shortcutPanel.querySelector('h2, h3');
    if (!panelTitle) {
        panelTitle = document.createElement('h2');
        panelTitle.textContent = 'Menu de Atalhos';
        shortcutPanel.insertBefore(panelTitle, shortcutPanel.firstChild);
    }
    
    const titleId = 'shortcut-panel-title';
    panelTitle.id = titleId;
    panelTitle.style.position = 'absolute';
    panelTitle.style.clip = 'rect(0 0 0 0)';
    panelTitle.style.clipPath = 'inset(50%)';
    panelTitle.style.height = '1px';
    panelTitle.style.width = '1px';
    panelTitle.style.overflow = 'hidden';
    panelTitle.style.whiteSpace = 'nowrap';
    
    shortcutPanel.setAttribute('aria-labelledby', titleId);
    
    // Adiciona descrição para o painel
    const panelDescription = document.createElement('p');
    const descriptionId = 'shortcut-panel-description';
    panelDescription.id = descriptionId;
    panelDescription.textContent = 'Use as teclas de seta para navegar entre os itens. Pressione Enter para ativar um item ou Esc para fechar o painel.';
    panelDescription.style.position = 'absolute';
    panelDescription.style.clip = 'rect(0 0 0 0)';
    panelDescription.style.clipPath = 'inset(50%)';
    panelDescription.style.height = '1px';
    panelDescription.style.width = '1px';
    panelDescription.style.overflow = 'hidden';
    panelDescription.style.whiteSpace = 'nowrap';
    
    shortcutPanel.appendChild(panelDescription);
    shortcutPanel.setAttribute('aria-describedby', descriptionId);
    
    // Configura o botão de fechar
    if (closeButton) {
        closeButton.setAttribute('aria-label', 'Fechar menu de atalhos');
        closeButton.setAttribute('tabindex', '0');
        closeButton.addEventListener('click', closePanel);
        closeButton.addEventListener('keydown', handleCloseButtonKeyDown);
    }
    
    // Configura os itens do menu
    setupMenuItems();
}

/**
 * Configura os itens do menu com atributos e eventos de acessibilidade
 */
function setupMenuItems() {
    const menuItems = shortcutPanel.querySelectorAll('.shortcut-item');
    
    menuItems.forEach((item, index) => {
        item.setAttribute('tabindex', '0');
        item.setAttribute('role', 'menuitem');
        
        // Navegação por teclado
        item.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            } else if (e.key === 'Escape') {
                closePanel();
            }
        });
    });
    
    // Configura o gerenciamento de foco
    trapFocus(shortcutPanel);
}

/**
 * Gerencia o foco dentro do painel
 */
function trapFocus(element) {
    const focusableElements = element.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    
    if (focusableElements.length === 0) return;
    
    firstMenuItem = focusableElements[0];
    const lastFocusableElement = focusableElements[focusableElements.length - 1];
    
    element.addEventListener('keydown', function(e) {
        if (e.key !== 'Tab') return;
        
        if (e.shiftKey) {
            // Shift + Tab: volta para o último elemento
            if (document.activeElement === firstMenuItem) {
                e.preventDefault();
                lastFocusableElement.focus();
            }
        } else {
            // Tab: avança para o primeiro elemento
            if (document.activeElement === lastFocusableElement) {
                e.preventDefault();
                firstMenuItem.focus();
            }
        }
    });
}

/**
 * Manipulador de clique no ícone
 */
function handleIconClick(e) {
    e.preventDefault();
    e.stopPropagation();
    
    if (shortcutPanel.getAttribute('aria-hidden') === 'true') {
        openPanel();
    } else {
        closePanel();
    }
}

/**
 * Manipulador de teclado para o ícone
 */
function handleIconKeyDown(e) {
    if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.click();
    } else if (e.key === 'Escape' && !shortcutPanel.classList.contains('hidden')) {
        closePanel();
    }
}

/**
 * Manipulador de teclado para o botão de fechar
 */
function handleCloseButtonKeyDown(e) {
    if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        closePanel();
    } else if (e.key === 'Tab' && e.shiftKey) {
        // Se for Shift+Tab no primeiro elemento, volta para o último
        e.preventDefault();
        const focusableElements = shortcutPanel.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"]):not([tabindex="0"])'
        );
        const lastElement = focusableElements[focusableElements.length - 1];
        if (lastElement) lastElement.focus();
    }
}

/**
 * Manipulador global de teclado
 */
function handleGlobalKeyDown(e) {
    // Fecha o painel com ESC
    if (e.key === 'Escape' && shortcutPanel.getAttribute('aria-hidden') === 'false') {
        closePanel();
    }
}

/**
 * Manipulador de clique fora do painel
 */
function handleOutsideClick(e) {
    if (shortcutPanel.getAttribute('aria-hidden') === 'false' && 
        !shortcutPanel.contains(e.target) && 
        !shortcutIcon.contains(e.target)) {
        closePanel();
    }
}

/**
 * Gerencia a navegação por Tab dentro do painel
 */
function trapTabKey(e) {
    if (e.key === 'Tab') {
        const focusableElements = shortcutPanel.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length === 0) return;
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey) {
            // Shift + Tab
            if (document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            }
        } else {
            // Apenas Tab
            if (document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        }
    }
}

/**
 * Abre o painel de atalhos
 */
function openPanel() {
    if (!shortcutPanel || !shortcutIcon) return;
    
    // Salva o elemento que tinha o foco
    lastFocusedElement = document.activeElement;
    
    // Atualiza atributos ARIA
    shortcutPanel.setAttribute('aria-hidden', 'false');
    shortcutIcon.setAttribute('aria-expanded', 'true');
    
    // Mostra o painel
    shortcutPanel.classList.add('active');
    
    // Adiciona classe ao body para estilos específicos
    document.body.classList.add('shortcut-panel-open');
    
    // Desabilita a rolagem da página
    document.documentElement.style.overflow = 'hidden';
    
    // Foca no primeiro elemento do painel
    setTimeout(() => {
        const firstFocusable = closeButton || 
                             shortcutPanel.querySelector('a, button, [tabindex="0"]');
        
        if (firstFocusable) {
            firstFocusable.focus();
            announce('Menu de atalhos aberto. ' + 
                    'Use as teclas de seta para navegar. ' + 
                    'Pressione Enter para ativar um item ou Esc para fechar.');
        }
        
        // Adiciona evento para capturar tecla Tab
        document.addEventListener('keydown', trapTabKey);
    }, 10);
    
    // Dispara evento personalizado
    const event = new CustomEvent('shortcutPanelOpen');
    document.dispatchEvent(event);
}

/**
 * Fecha o painel de atalhos
 */
function closePanel() {
    if (!shortcutPanel || !shortcutIcon) return;
    
    // Atualiza atributos ARIA
    shortcutPanel.setAttribute('aria-hidden', 'true');
    shortcutIcon.setAttribute('aria-expanded', 'false');
    
    // Esconde o painel
    shortcutPanel.classList.remove('active');
    
    // Remove classe do body e restaura a rolagem
    document.body.classList.remove('shortcut-panel-open');
    document.documentElement.style.overflow = '';
    
    // Remove evento de captura de Tab
    document.removeEventListener('keydown', trapTabKey);
    
    // Anuncia que o painel foi fechado
    announce('Menu de atalhos fechado');
    
    // Retorna o foco para o último elemento que o tinha
    if (lastFocusedElement) {
        lastFocusedElement.focus();
    } else {
        shortcutIcon.focus();
    }
    
    // Dispara evento personalizado
    const event = new CustomEvent('shortcutPanelClose');
    document.dispatchEvent(event);
}

/**
 * Inicia o arrasto do ícone
 */
function startDrag(e) {
    if (e.button !== 0) return; // Apenas botão esquerdo do mouse
    
    isDragging = true;
    offsetY = e.clientY - shortcutIcon.getBoundingClientRect().top;
    
    // Adiciona classe para feedback visual
    shortcutIcon.classList.add('dragging');
    document.body.style.userSelect = 'none';
    
    e.preventDefault();
    e.stopPropagation();
}

/**
 * Manipula o arrasto do ícone
 */
function handleDrag(e) {
    if (!isDragging) return;
    
    const y = e.clientY - offsetY;
    const maxY = window.innerHeight - shortcutIcon.offsetHeight;
    const newY = Math.min(Math.max(0, y), maxY);
    
    shortcutIcon.style.top = newY + 'px';
    shortcutIcon.style.transform = 'none';
    
    e.preventDefault();
    e.stopPropagation();
}

/**
 * Finaliza o arrasto do ícone
 */
function endDrag(e) {
    if (isDragging) {
        isDragging = false;
        
        // Remove classe de feedback visual
        shortcutIcon.classList.remove('dragging');
        document.body.style.userSelect = '';
        
        // Salva a posição
        const savedY = parseInt(shortcutIcon.style.top) || (window.innerHeight / 2 - 25);
        localStorage.setItem('shortcutIconY', savedY);
        
        e.preventDefault();
        e.stopPropagation();
    }
}

/**
 * Carrega a posição salva do ícone
 */
function loadIconPosition() {
    if (!shortcutIcon) return;
    
    const savedY = localStorage.getItem('shortcutIconY');
    if (savedY) {
        shortcutIcon.style.top = savedY + 'px';
        shortcutIcon.style.transform = 'none';
    }
}

/**
 * Remove estilos de foco genéricos para melhorar a acessibilidade
 */
function removeFocusStyles() {
    // Adiciona um estilo temporário para remover outlines em elementos com classe 'no-focus-outline'
    // Isso não afeta a navegação por teclado, apenas a aparência
    const style = document.createElement('style');
    style.textContent = `
        .no-focus-outline:focus {
            outline: none !important;
        }
        
        /* Mantém o foco visível para navegação por teclado */
        .user-is-tabbing :focus-visible {
            outline: 3px solid var(--bs-uenf-blue, #1d3771) !important;
            outline-offset: 2px !important;
        }
    `;
    document.head.appendChild(style);
    
    // Detecta se o usuário está navegando por teclado
    document.addEventListener('keydown', function detectFirstTab(e) {
        if (e.key === 'Tab') {
            document.body.classList.add('user-is-tabbing');
            document.removeEventListener('keydown', detectFirstTab);
        }
    });
}

// O restante do código foi movido para funções específicas
