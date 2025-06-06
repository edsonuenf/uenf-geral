document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.querySelector('.back-to-top');
    
    if (backToTopButton) {
        // Mostrar/ocultar o botão com base no scroll
        window.addEventListener('scroll', function() {
            // Se houver scroll, mostra o botão
            if (window.scrollY > 0) {
                backToTopButton.classList.add('visible');
            }
            // Não ocultar o botão quando estiver no topo
            if (window.scrollY === 0) {
                backToTopButton.classList.remove('visible');
            }
        });

        // Animação suave ao clicar no botão
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            // Não esconde o botão após clicar
        });
    }
});
