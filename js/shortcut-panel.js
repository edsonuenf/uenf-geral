jQuery(document).ready(function($) {
    const shortcutIcon = $('.shortcut-icon')[0];
    const shortcutPanel = $('.shortcut-panel')[0];
    const closeButton = $('.close-panel')[0];
    const shortcutItems = $('.shortcut-item');
    
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
            console.log('Iniciando arraste no ícone');
            shortcutIcon.classList.add('dragging');
            
            if (e.type === 'touchstart') {
                startY = e.touches[0].clientY;
                console.log('Touch Y:', startY);
            } else {
                startY = e.clientY;
                console.log('Mouse Y:', startY);
            }
            
            initialY = startY;
            isDragging = true;
            console.log('Estado do arraste:', { isDragging, startY, initialY });
        } else {
            console.log('Clique fora do ícone, ignorando arraste');
        }
    }

    function dragEnd(e) {
        console.log('--- dragEnd ---');
        console.log('Tipo de evento:', e.type);
        console.log('Estado anterior:', { isDragging, currentY, initialY });
        
        isDragging = false;
        shortcutIcon.classList.remove('dragging');
        
        // Se o movimento foi pequeno, considerar como clique
        const movement = Math.abs(currentY - initialY);
        console.log('Movimento detectado:', movement, 'pixels');
        
        if (movement < 5) {
            console.log('Movimento pequeno (<5px) - Alternando painel');
            const wasActive = shortcutPanel.classList.contains('active');
            console.log('Estado ANTES do toggle:', wasActive ? 'ativo' : 'inativo');
            
            // Alterna a classe active
            shortcutPanel.classList.toggle('active');
            
            const isNowActive = shortcutPanel.classList.contains('active');
            console.log('Estado DEPOIS do toggle:', isNowActive ? 'ativo' : 'inativo');
            
            // Log das propriedades do painel após a mudança
            if (shortcutPanel) {
                console.log('Propriedades do painel após toggle:', {
                    'display': window.getComputedStyle(shortcutPanel).display,
                    'visibility': window.getComputedStyle(shortcutPanel).visibility,
                    'opacity': window.getComputedStyle(shortcutPanel).opacity,
                    'right': window.getComputedStyle(shortcutPanel).right,
                    'width': window.getComputedStyle(shortcutPanel).width,
                    'has-active-class': isNowActive
                });
            }
        } else {
            console.log('Movimento grande (≥5px) - Apenas arraste, não alterna painel');
        }
        
        console.log('--- FIM dragEnd ---');
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
            shortcutIcon.style.transform = 'none';
            
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
        console.log('=== CLIQUE NO ÍCONE DETECTADO ===');
        console.log('Tipo de evento:', e.type);
        console.log('Elemento clicado:', e.target);
        
        e.preventDefault();
        
        const wasActive = shortcutPanel.classList.contains('active');
        console.log('Estado ANTES do clique:', {
            panelActive: wasActive ? 'ativo' : 'inativo',
            isDragging: isDragging
        });
        
        // Força a remoção da classe dragging para garantir que não haja conflito
        shortcutIcon.classList.remove('dragging');
        
        // Alterna a visibilidade do painel
        shortcutPanel.classList.toggle('active');
        
        const isNowActive = shortcutPanel.classList.contains('active');
        console.log('Estado DEPOIS do clique:', {
            panelActive: isNowActive ? 'ativo' : 'inativo'
        });
        
        // Log das propriedades do painel após a mudança
        console.log('Propriedades do painel após clique:', {
            'display': window.getComputedStyle(shortcutPanel).display,
            'visibility': window.getComputedStyle(shortcutPanel).visibility,
            'opacity': window.getComputedStyle(shortcutPanel).opacity,
            'right': window.getComputedStyle(shortcutPanel).right,
            'width': window.getComputedStyle(shortcutPanel).width,
            'has-active-class': isNowActive
        });
        
        // Log do botão
        console.log('Propriedades do botão após clique:', {
            'right': window.getComputedStyle(shortcutIcon).right,
            'transform': window.getComputedStyle(shortcutIcon).transform,
            'has-active-class': document.querySelector('.shortcut-panel.active') !== null
        });
        
        console.log('=== FIM DO CLIQUE ===');
    });

    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
            console.log('=== BOTÃO FECHAR CLICADO ===');
            e.preventDefault();
            
            console.log('Removendo classe "active" do painel');
            shortcutPanel.classList.remove('active');
            
            console.log('Estado do painel após fechar:', {
                panelActive: shortcutPanel.classList.contains('active'),
                'display': window.getComputedStyle(shortcutPanel).display,
                'visibility': window.getComputedStyle(shortcutPanel).visibility,
                'opacity': window.getComputedStyle(shortcutPanel).opacity
            });
            
            console.log('=== FIM DO CLIQUE NO BOTÃO FECHAR ===');
        });
    }

    // Adicionar funcionalidade aos atalhos
    shortcutItems.each(function() {
        $(this).on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
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
