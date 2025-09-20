/**
 * JavaScript para Block Patterns
 * Adiciona interatividade e funcionalidades avançadas aos patterns
 */

(function() {
    'use strict';

    // Aguarda o DOM estar pronto
    document.addEventListener('DOMContentLoaded', function() {
        initFAQTabs();
        initFAQAccordion();
        initPricingAnimations();
        initAccessibility();
    });

    /**
     * Inicializa funcionalidade das abas FAQ
     */
    function initFAQTabs() {
        const tabContainers = document.querySelectorAll('.faq-tabs-container');
        
        tabContainers.forEach(container => {
            const tabButtons = container.querySelectorAll('.faq-tab-button');
            const tabContents = container.querySelectorAll('.faq-tab-content');
            
            tabButtons.forEach((button, index) => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                        content.style.display = 'none';
                    });
                    
                    // Add active class to clicked button and corresponding content
                    button.classList.add('active');
                    if (tabContents[index]) {
                        tabContents[index].classList.add('active');
                        tabContents[index].style.display = 'block';
                    }
                    
                    // Update button styles
                    updateTabButtonStyles(button, tabButtons);
                });
            });
        });
    }

    /**
     * Atualiza estilos dos botões de aba
     */
    function updateTabButtonStyles(activeButton, allButtons) {
        allButtons.forEach(button => {
            const link = button.querySelector('.wp-block-button__link');
            if (link) {
                if (button === activeButton) {
                    link.style.borderBottomColor = 'var(--wp--preset--color--primary)';
                    link.style.borderBottomWidth = '2px';
                    link.style.color = 'var(--wp--preset--color--primary)';
                } else {
                    link.style.borderBottomColor = 'transparent';
                    link.style.borderBottomWidth = '0';
                    link.style.color = 'var(--wp--preset--color--contrast)';
                }
            }
        });
    }

    /**
     * Inicializa funcionalidade do accordion FAQ
     */
    function initFAQAccordion() {
        const accordionContainers = document.querySelectorAll('.faq-accordion-container');
        
        accordionContainers.forEach(container => {
            const details = container.querySelectorAll('.wp-block-details');
            
            details.forEach(detail => {
                const summary = detail.querySelector('summary');
                
                if (summary) {
                    // Adiciona ícone de expansão
                    addAccordionIcon(summary);
                    
                    // Adiciona evento de toggle
                    detail.addEventListener('toggle', function() {
                        updateAccordionIcon(summary, detail.open);
                        
                        // Smooth scroll para o item aberto
                        if (detail.open) {
                            setTimeout(() => {
                                detail.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'nearest'
                                });
                            }, 100);
                        }
                    });
                }
            });
        });
    }

    /**
     * Adiciona ícone ao accordion
     */
    function addAccordionIcon(summary) {
        if (!summary.querySelector('.accordion-icon')) {
            const icon = document.createElement('span');
            icon.className = 'accordion-icon';
            icon.innerHTML = '+';
            icon.style.cssText = `
                float: right;
                font-weight: bold;
                font-size: 1.2em;
                transition: transform 0.3s ease;
                color: var(--wp--preset--color--primary);
            `;
            summary.appendChild(icon);
        }
    }

    /**
     * Atualiza ícone do accordion
     */
    function updateAccordionIcon(summary, isOpen) {
        const icon = summary.querySelector('.accordion-icon');
        if (icon) {
            icon.innerHTML = isOpen ? '−' : '+';
            icon.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }

    /**
     * Inicializa animações dos cards de pricing
     */
    function initPricingAnimations() {
        const pricingCards = document.querySelectorAll('[class*="Plano"]');
        
        // Intersection Observer para animações de entrada
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            pricingCards.forEach((card, index) => {
                // Configuração inicial para animação
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                
                observer.observe(card);
            });
        }

        // Adiciona efeito de hover nos botões
        const pricingButtons = document.querySelectorAll('.wp-block-button .wp-block-button__link');
        pricingButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    /**
     * Inicializa melhorias de acessibilidade
     */
    function initAccessibility() {
        // Adiciona navegação por teclado para abas
        const tabButtons = document.querySelectorAll('.faq-tab-button');
        tabButtons.forEach((button, index) => {
            const link = button.querySelector('.wp-block-button__link');
            if (link) {
                link.setAttribute('role', 'tab');
                link.setAttribute('tabindex', index === 0 ? '0' : '-1');
                
                link.addEventListener('keydown', function(e) {
                    handleTabKeyNavigation(e, tabButtons, index);
                });
            }
        });

        // Adiciona ARIA labels para accordion
        const accordionDetails = document.querySelectorAll('.faq-accordion-container .wp-block-details');
        accordionDetails.forEach((detail, index) => {
            const summary = detail.querySelector('summary');
            if (summary) {
                summary.setAttribute('aria-expanded', detail.open ? 'true' : 'false');
                summary.setAttribute('aria-controls', `faq-content-${index}`);
                
                const content = detail.querySelector('p');
                if (content) {
                    content.setAttribute('id', `faq-content-${index}`);
                }
                
                detail.addEventListener('toggle', function() {
                    summary.setAttribute('aria-expanded', detail.open ? 'true' : 'false');
                });
            }
        });

        // Adiciona labels para pricing cards
        const pricingCards = document.querySelectorAll('[class*="Plano"]');
        pricingCards.forEach(card => {
            const heading = card.querySelector('h3');
            const button = card.querySelector('.wp-block-button__link');
            
            if (heading && button) {
                const planName = heading.textContent.trim();
                button.setAttribute('aria-label', `Escolher plano ${planName}`);
            }
        });
    }

    /**
     * Gerencia navegação por teclado nas abas
     */
    function handleTabKeyNavigation(e, tabButtons, currentIndex) {
        let newIndex = currentIndex;
        
        switch(e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                newIndex = currentIndex > 0 ? currentIndex - 1 : tabButtons.length - 1;
                break;
            case 'ArrowRight':
                e.preventDefault();
                newIndex = currentIndex < tabButtons.length - 1 ? currentIndex + 1 : 0;
                break;
            case 'Home':
                e.preventDefault();
                newIndex = 0;
                break;
            case 'End':
                e.preventDefault();
                newIndex = tabButtons.length - 1;
                break;
            case 'Enter':
            case ' ':
                e.preventDefault();
                tabButtons[currentIndex].click();
                return;
        }
        
        if (newIndex !== currentIndex) {
            // Remove tabindex de todos os botões
            tabButtons.forEach(button => {
                const link = button.querySelector('.wp-block-button__link');
                if (link) link.setAttribute('tabindex', '-1');
            });
            
            // Adiciona tabindex e foco ao novo botão
            const newButton = tabButtons[newIndex];
            const newLink = newButton.querySelector('.wp-block-button__link');
            if (newLink) {
                newLink.setAttribute('tabindex', '0');
                newLink.focus();
            }
        }
    }

    /**
     * Utilitário para debounce
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Adiciona suporte para redimensionamento da janela
    window.addEventListener('resize', debounce(() => {
        // Reajusta layouts se necessário
        const tabContents = document.querySelectorAll('.faq-tab-content.active');
        tabContents.forEach(content => {
            content.style.minHeight = 'auto';
        });
    }, 250));

})();