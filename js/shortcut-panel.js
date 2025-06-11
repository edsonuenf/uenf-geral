document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const shortcutIcon = document.querySelector('.shortcut-icon');
    const shortcutPanel = document.querySelector('.shortcut-panel');
    const closeButton = document.querySelector('.close-panel');
    const backToTopButton = document.querySelector('.back-to-top');
    
    // Draggable functionality
    if (shortcutIcon) {
        let isDragging = false;
        let offsetY = 0;
        let startY = 0;
        let startTop = 0;
        
        // Iniciar arrasto
        const startDrag = (e) => {
            const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
            
            isDragging = true;
            offsetY = clientY - shortcutIcon.getBoundingClientRect().top;
            startY = clientY;
            startTop = parseInt(shortcutIcon.style.top) || (window.innerHeight / 2 - parseInt(getComputedStyle(shortcutIcon).height) / 2);
            
            shortcutIcon.classList.add('dragging');
            document.body.style.userSelect = 'none';
            document.body.style.cursor = 'grabbing';
            
            e.preventDefault();
        };
        
        // Mover durante o arrasto
        const dragMove = (e) => {
            if (!isDragging) return;
            
            const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
            const deltaY = clientY - startY;
            const newY = startTop + deltaY;
            const maxY = window.innerHeight - shortcutIcon.offsetHeight;
            
            // Limita o movimento dentro da janela
            const boundedY = Math.min(Math.max(0, newY), maxY);
            
            shortcutIcon.style.top = `${boundedY}px`;
            shortcutIcon.style.transform = 'none';
        };
        
        // Finalizar arrasto
        const endDrag = () => {
            if (!isDragging) return;
            
            isDragging = false;
            shortcutIcon.classList.remove('dragging');
            document.body.style.userSelect = '';
            document.body.style.cursor = '';
            
            // Salva a posição Y
            const savedY = parseInt(shortcutIcon.style.top) || (window.innerHeight / 2 - parseInt(getComputedStyle(shortcutIcon).height) / 2);
            localStorage.setItem('shortcutIconY', savedY);
            
            // Remove os listeners
            document.removeEventListener('mousemove', dragMove);
            document.removeEventListener('touchmove', dragMove);
            document.removeEventListener('mouseup', endDrag);
            document.removeEventListener('touchend', endDrag);
        };
        
        // Adiciona os listeners de mouse
        shortcutIcon.addEventListener('mousedown', (e) => {
            if (e.button !== 0) return; // Apenas botão esquerdo
            startDrag(e);
            document.addEventListener('mousemove', dragMove);
            document.addEventListener('mouseup', endDrag, { once: true });
        });
        
        // Adiciona os listeners de toque
        shortcutIcon.addEventListener('touchstart', (e) => {
            startDrag(e);
            document.addEventListener('touchmove', dragMove, { passive: false });
            document.addEventListener('touchend', endDrag, { once: true, passive: true });
        }, { passive: false });
        
        // Carrega a posição salva
        const savedY = localStorage.getItem('shortcutIconY');
        if (savedY) {
            shortcutIcon.style.top = `${savedY}px`;
            shortcutIcon.style.transform = 'none';
        } else {
            // Posição inicial centralizada verticalmente
            shortcutIcon.style.top = `${(window.innerHeight - parseInt(getComputedStyle(shortcutIcon).height)) / 2}px`;
        }
    }
    
    // Toggle panel
    if (shortcutIcon && shortcutPanel) {
        let clickStartX = 0;
        let clickStartY = 0;
        let isClick = false;
        
        // Adiciona atributos ARIA para acessibilidade
        shortcutIcon.setAttribute('role', 'button');
        shortcutIcon.setAttribute('aria-label', 'Abrir painel de atalhos');
        shortcutIcon.setAttribute('aria-expanded', 'false');
        shortcutIcon.setAttribute('aria-controls', 'shortcut-panel');
        
        closeButton.setAttribute('aria-label', 'Fechar painel de atalhos');
        
        // Função para abrir o painel
        const openPanel = () => {
            shortcutPanel.classList.add('active');
            shortcutIcon.setAttribute('aria-expanded', 'true');
            closeButton.focus();
            localStorage.setItem('shortcutPanelOpen', 'true');
            
            // Adiciona um listener para fechar com a tecla ESC
            document.addEventListener('keydown', handleEscape);
        };
        
        // Função para fechar o painel
        const closePanel = () => {
            shortcutPanel.classList.remove('active');
            shortcutIcon.setAttribute('aria-expanded', 'false');
            shortcutIcon.focus();
            localStorage.setItem('shortcutPanelOpen', 'false');
            
            // Remove o listener do ESC
            document.removeEventListener('keydown', handleEscape);
        };
        
        // Função para lidar com a tecla ESC
        const handleEscape = (e) => {
            if (e.key === 'Escape' || e.key === 'Esc') {
                closePanel();
            }
        };
        
        // Detecta o início do toque/clique
        const handleStart = (e) => {
            isClick = true;
            clickStartX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            clickStartY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
        };
        
        // Detecta o fim do toque/clique
        const handleEnd = (e) => {
            if (!isClick) return;
            
            const clientX = e.type.includes('touch') ? e.changedTouches[0].clientX : e.clientX;
            const clientY = e.type.includes('touch') ? e.changedTouches[0].clientY : e.clientY;
            
            // Calcula a distância do movimento
            const moveX = Math.abs(clientX - clickStartX);
            const moveY = Math.abs(clientY - clickStartY);
            
            // Se o movimento for pequeno (menos de 5px), considera como clique
            if (moveX < 5 && moveY < 5) {
                e.preventDefault();
                e.stopPropagation();
                
                if (shortcutPanel.classList.contains('active')) {
                    closePanel();
                } else {
                    openPanel();
                }
            }
            
            isClick = false;
        };
        
        // Adiciona os listeners de clique
        shortcutIcon.addEventListener('mousedown', handleStart);
        shortcutIcon.addEventListener('click', handleEnd);
        
        // Adiciona os listeners de toque
        shortcutIcon.addEventListener('touchstart', handleStart, { passive: true });
        shortcutIcon.addEventListener('touchend', handleEnd, { passive: true });
        
        // Fechar com o botão
        closeButton.addEventListener('click', (e) => {
            e.preventDefault();
            closePanel();
        });
        
        // Fechar ao clicar fora
        document.addEventListener('click', (e) => {
            if (shortcutPanel.classList.contains('active') && 
                !shortcutPanel.contains(e.target) && 
                !shortcutIcon.contains(e.target)) {
                closePanel();
            }
        });
        
        // Verifica se o painel estava aberto anteriormente
        const isPanelOpen = localStorage.getItem('shortcutPanelOpen') === 'true';
        if (isPanelOpen) {
            openPanel();
        }
    }
    
    // Back to top functionality
    if (backToTopButton) {
        // Show/hide back to top button on scroll
        const toggleBackToTop = () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('visible');
                backToTopButton.setAttribute('aria-hidden', 'false');
            } else {
                backToTopButton.classList.remove('visible');
                backToTopButton.setAttribute('aria-hidden', 'true');
            }
        };
        
        // Verifica a posição inicial
        toggleBackToTop();
        
        // Adiciona o listener de scroll
        window.addEventListener('scroll', toggleBackToTop, { passive: true });
        
        // Smooth scroll to top
        backToTopButton.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            
            // Move o foco para o topo da página para acessibilidade
            const focusable = document.querySelector('h1, [tabindex="0"]') || document.body;
            focusable.setAttribute('tabindex', '-1');
            focusable.focus();
            
            // Remove o tabindex para não interferir na navegação por teclado
            setTimeout(() => {
                focusable.removeAttribute('tabindex');
            }, 0);
        });
    }
    
    // Adiciona uma classe ao body quando o JavaScript estiver ativo
    document.body.classList.add('js-enabled');
});
