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
        
        // Iniciar arrasto
        shortcutIcon.addEventListener('mousedown', function(e) {
            if (e.button !== 0) return; // Apenas botão esquerdo do mouse
            
            isDragging = true;
            offsetY = e.clientY - shortcutIcon.getBoundingClientRect().top;
            shortcutIcon.classList.add('dragging');
            document.body.style.userSelect = 'none'; // Evita seleção de texto durante o arrasto
            
            e.preventDefault();
        });
        
        // Mover durante o arrasto
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const y = e.clientY - offsetY;
            const maxY = window.innerHeight - shortcutIcon.offsetHeight;
            
            // Limita o movimento dentro da janela
            const newY = Math.min(Math.max(0, y), maxY);
            
            shortcutIcon.style.top = newY + 'px';
            shortcutIcon.style.transform = 'none'; // Remove o transform anterior
        });
        
        // Finalizar arrasto
        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                shortcutIcon.classList.remove('dragging');
                document.body.style.userSelect = '';
                
                // Salva a posição Y para a próxima vez que a página for carregada
                const savedY = parseInt(shortcutIcon.style.top) || (window.innerHeight / 2 - 25);
                localStorage.setItem('shortcutIconY', savedY);
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
        let clickStartX = 0;
        let clickStartY = 0;
        
        // Detecta o início do clique
        shortcutIcon.addEventListener('mousedown', function(e) {
            clickStartX = e.clientX;
            clickStartY = e.clientY;
        });
        
        // Detecta o fim do clique
        shortcutIcon.addEventListener('click', function(e) {
            // Calcula a distância do movimento do mouse
            const moveX = Math.abs(e.clientX - clickStartX);
            const moveY = Math.abs(e.clientY - clickStartY);
            
            // Se o movimento for pequeno (menos de 5px), considera como clique
            if (moveX < 5 && moveY < 5) {
                e.preventDefault();
                shortcutPanel.classList.toggle('open');
            }
        });
    }
    
    // Close panel when clicking the close button
    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
            e.preventDefault();
            shortcutPanel.classList.remove('open');
        });
    }
    
    // Close panel when clicking outside
    document.addEventListener('click', function(e) {
        if (shortcutPanel && shortcutPanel.classList.contains('open') && 
            !shortcutPanel.contains(e.target) && 
            !shortcutIcon.contains(e.target)) {
            shortcutPanel.classList.remove('open');
        }
    });
    
    // Back to top functionality
    if (backToTopButton) {
        // Show/hide back to top button on scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });
        
        // Smooth scroll to top
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
