console.log('=== INÍCIO DO SCRIPT SHORTCUT-PANEL.JS ===');

// Verifica se os estilos estão carregados
const styles = Array.from(document.styleSheets)
    .filter(styleSheet => !styleSheet.href || styleSheet.href.includes('shortcuts.css'));

console.log('Estilos carregados:', styles.map(s => s.href || 'inline'));

// Verifica se as variáveis CSS estão disponíveis
const rootStyles = getComputedStyle(document.documentElement);
const primaryColor = rootStyles.getPropertyValue('--bs-uenf-blue').trim();
console.log('Cor primária (--bs-uenf-blue):', primaryColor || 'Não definida');

// Verifica se os elementos existem antes mesmo do DOM estar pronto
console.log('Procurando elementos...');
let shortcutIcon = document.querySelector('.shortcut-icon');
let shortcutPanel = document.querySelector('.shortcut-panel');
let closeButton = document.querySelector('.close-panel');

console.log('Elementos encontrados no carregamento inicial:', { 
    shortcutIcon: shortcutIcon ? 'Encontrado' : 'Não encontrado',
    shortcutPanel: shortcutPanel ? 'Encontrado' : 'Não encontrado',
    closeButton: closeButton ? 'Encontrado' : 'Não encontrado'
});

// Log dos estilos aplicados
if (shortcutIcon) {
    const iconStyles = window.getComputedStyle(shortcutIcon);
    console.log('Estilos do ícone:', {
        backgroundColor: iconStyles.backgroundColor,
        width: iconStyles.width,
        height: iconStyles.height,
        display: iconStyles.display,
        position: iconStyles.position,
        zIndex: iconStyles.zIndex
    });
}

// Remove estilos de foco de todos os elementos
function removeFocusStyles() {
    // Adiciona estilos inline para remover bordas de foco
    const style = document.createElement('style');
    style.textContent = `
        *:focus, *:focus-visible, *:focus-within, *:active {
            outline: none !important;
            box-shadow: none !important;
            border-color: transparent !important;
        }
        .shortcut-icon, .shortcut-panel, .shortcut-item {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }
    `;
    document.head.appendChild(style);
}

// Se algum elemento não foi encontrado, tenta novamente quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Remove estilos de foco
    removeFocusStyles();
    console.log('DOM completamente carregado');
    
    // Atualiza as referências após o DOM estar pronto
    if (!shortcutIcon) shortcutIcon = document.querySelector('.shortcut-icon');
    if (!shortcutPanel) shortcutPanel = document.querySelector('.shortcut-panel');
    if (!closeButton) closeButton = document.querySelector('.close-panel');
    
    console.log('Elementos após DOM carregado:', { 
        shortcutIcon: shortcutIcon ? 'Encontrado' : 'Não encontrado',
        shortcutPanel: shortcutPanel ? 'Encontrado' : 'Não encontrado',
        closeButton: closeButton ? 'Encontrado' : 'Não encontrado'
    });
    
    console.log('Elementos encontrados:', {
        shortcutIcon,
        shortcutPanel,
        closeButton
    });
    
    // Estilo do ícone
    if (shortcutIcon) {
        shortcutIcon.style.cursor = 'pointer';
    }
    
    if (shortcutPanel) {
        // Removendo qualquer borda que possa ter sido adicionada anteriormente
        shortcutPanel.style.border = 'none';
        console.log('Estilo do painel:', shortcutPanel.style.cssText);
    }
    
    // Draggable functionality
    if (shortcutIcon) {
        let isDragging = false;
        let offsetY = 0;
        
        // Iniciar arrasto
        shortcutIcon.addEventListener('mousedown', function(e) {
            console.log('Mouse down no ícone');
            if (e.button !== 0) return; // Apenas botão esquerdo do mouse
            
            isDragging = true;
            offsetY = e.clientY - shortcutIcon.getBoundingClientRect().top;
            shortcutIcon.classList.add('dragging');
            document.body.style.userSelect = 'none';
            
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
        
        // Mover durante o arrasto
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const y = e.clientY - offsetY;
            const maxY = window.innerHeight - shortcutIcon.offsetHeight;
            const newY = Math.min(Math.max(0, y), maxY);
            
            shortcutIcon.style.top = newY + 'px';
            shortcutIcon.style.transform = 'none';
            
            e.preventDefault();
            e.stopPropagation();
        });
        
        // Finalizar arrasto
        document.addEventListener('mouseup', function(e) {
            console.log('Mouse up');
            if (isDragging) {
                isDragging = false;
                shortcutIcon.classList.remove('dragging');
                document.body.style.userSelect = '';
                
                const savedY = parseInt(shortcutIcon.style.top) || (window.innerHeight / 2 - 25);
                localStorage.setItem('shortcutIconY', savedY);
                
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
        
        // Carrega a posição salva
        const savedY = localStorage.getItem('shortcutIconY');
        if (savedY) {
            shortcutIcon.style.top = savedY + 'px';
            shortcutIcon.style.transform = 'none';
        }
    }
    
    // Toggle panel
    if (shortcutIcon && shortcutPanel) {
        console.log('Adicionando event listeners para o painel');
        
        // Adiciona um evento de clique simples
        shortcutIcon.addEventListener('click', function(e) {
            console.log('Clique no ícone detectado!');
            console.log('Estado ANTES do toggle:', {
                classList: Array.from(shortcutPanel.classList),
                style: {
                    transform: window.getComputedStyle(shortcutPanel).transform,
                    right: window.getComputedStyle(shortcutPanel).right
                }
            });
            
            e.preventDefault();
            e.stopPropagation();
            
            // Fecha outros painéis abertos
            document.querySelectorAll('.shortcut-panel.active').forEach(panel => {
                if (panel !== shortcutPanel) {
                    panel.classList.remove('active');
                }
            });
            
            // Alterna a classe active
            shortcutPanel.classList.toggle('active');
            
            // Força o navegador a reconhecer a mudança
            void shortcutPanel.offsetWidth;
            
            console.log('Estado APÓS o toggle:', {
                classList: Array.from(shortcutPanel.classList),
                style: {
                    transform: window.getComputedStyle(shortcutPanel).transform,
                    right: window.getComputedStyle(shortcutPanel).right
                }
            });
            
            return false;
        });
    }
    
    // Close panel when clicking the close button
    if (closeButton && shortcutPanel) {
        closeButton.addEventListener('click', function(e) {
            console.log('Botão fechar clicado');
            e.preventDefault();
            e.stopPropagation();
            
            shortcutPanel.classList.remove('active');
            console.log('Painel fechado');
            
            return false;
        });
    }
    
    // Close panel when clicking outside
    document.addEventListener('click', function(e) {
        console.log('Clique global detectado');
        
        if (shortcutPanel && 
            shortcutPanel.classList.contains('active') && 
            !shortcutPanel.contains(e.target) && 
            !shortcutIcon.contains(e.target)) {
            
            console.log('Clique fora do painel detectado');
            shortcutPanel.classList.remove('active');
        }
    });
});

// Adiciona um listener global para verificar se algum evento está sendo capturado
document.addEventListener('click', function(e) {
    console.log('Clique global detectado (captura):', e.target);
}, true);
