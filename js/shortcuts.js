document.addEventListener('DOMContentLoaded', function() {
    const shortcutIcon = document.querySelector('.shortcut-icon');
    const shortcutPanel = document.querySelector('.shortcut-panel');
    const closePanel = document.querySelector('.close-panel');
    const shortcutItems = document.querySelectorAll('.shortcut-item');
    let isDragging = false;
    let startY;
    let currentY;
    let initialY;
    let topPosition = 50; // Posição inicial em porcentagem
    let clickTimeout;

    // Funções de arrastar
    function dragStart(e) {
        e.preventDefault();
        
        // Cancelar timeout de clique
        if (clickTimeout) {
            clearTimeout(clickTimeout);
        }
        
        // Verificar se o click foi no ícone ou no elemento pai
        const target = e.target.closest('.shortcut-icon');
        if (target) {
            shortcutIcon.classList.add('dragging');
            
            if (e.type === 'touchstart') {
                startY = e.touches[0].clientY;
            } else {
                startY = e.clientY;
            }
            
            initialY = startY;
            isDragging = true;
        }
    }

    function dragEnd(e) {
        isDragging = false;
        shortcutIcon.classList.remove('dragging');
        
        // Se o movimento foi pequeno, considerar como clique
        if (Math.abs(currentY - initialY) < 5) {
            shortcutPanel.classList.toggle('active');
        }
    }

    function drag(e) {
        if (isDragging) {
            e.preventDefault();
            
            let newY;
            if (e.type === 'touchmove') {
                newY = e.touches[0].clientY;
            } else {
                newY = e.clientY;
            }

            // Calcular a nova posição
            const delta = newY - initialY;
            topPosition += delta / window.innerHeight * 100;
            
            // Limitar a posição
            if (topPosition < 5) topPosition = 5;
            if (topPosition > 95) topPosition = 95;

            // Atualizar a posição
            shortcutIcon.style.top = `${topPosition}%`;
            
            // Resetar valores
            initialY = newY;
            currentY = newY;
        }
    }

    // Eventos de mouse para desktop
    shortcutIcon.addEventListener('mousedown', dragStart);
    shortcutIcon.addEventListener('mousemove', drag);
    shortcutIcon.addEventListener('mouseup', dragEnd);
    shortcutIcon.addEventListener('mouseleave', function(e) {
        if (isDragging) {
            isDragging = false;
            shortcutIcon.classList.remove('dragging');
        }
    });

    // Eventos de touch para mobile
    shortcutIcon.addEventListener('touchstart', dragStart);
    shortcutIcon.addEventListener('touchmove', drag);
    shortcutIcon.addEventListener('touchend', dragEnd);
    shortcutIcon.addEventListener('touchcancel', function(e) {
        if (isDragging) {
            isDragging = false;
            shortcutIcon.classList.remove('dragging');
        }
    });

    // Abrir/fechar painel de atalhos
    shortcutIcon.addEventListener('click', function(e) {
        e.preventDefault();
        shortcutPanel.classList.toggle('active');
    });

    closePanel.addEventListener('click', function(e) {
        e.preventDefault();
        shortcutPanel.classList.remove('active');
    });

    // Adicionar funcionalidade aos atalhos
    shortcutItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            if (url) {
                window.location.href = url;
            }
        });
    });

    // Fechar painel ao clicar fora
    document.addEventListener('click', function(e) {
        if (!shortcutPanel.contains(e.target) && !shortcutIcon.contains(e.target)) {
            shortcutPanel.classList.remove('active');
        }
    });

    // Adicionar suporte para teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            shortcutPanel.classList.remove('active');
        }
    });
});
