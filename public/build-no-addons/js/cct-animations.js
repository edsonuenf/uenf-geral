/**
 * Sistema de Animações CCT - JavaScript Frontend
 * 
 * Funcionalidades incluídas:
 * - Intersection Observer para animações on-scroll
 * - Micro-interações e efeitos de hover
 * - Transições de página
 * - Performance otimizada
 * - Acessibilidade (prefers-reduced-motion)
 * 
 * @package CCT_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Objeto principal do sistema de animações
     */
    const CCTAnimations = {
        
        // Configurações padrão
        settings: {
            enabled: true,
            duration: 0.3,
            easing: 'ease-in-out',
            reduceMotion: true,
            hoverEffects: true,
            focusEffects: true,
            loadingEffects: true,
            pageTransitions: false,
            pageTransitionType: 'fade',
            pageTransitionDuration: 0.5,
            gpuAcceleration: true,
            intersectionObserver: true
        },
        
        // Estado interno
        state: {
            initialized: false,
            observers: [],
            activeAnimations: new Set(),
            prefersReducedMotion: false,
            isTouch: false
        },
        
        // Cache de elementos
        cache: {
            animatedElements: null,
            hoverElements: null,
            focusElements: null
        },
        
        /**
         * Inicializa o sistema de animações
         */
        init: function(customSettings = {}) {
            // Merge configurações
            this.settings = { ...this.settings, ...customSettings };
            
            // Verificar se animações estão habilitadas
            if (!this.settings.enabled) {
                return;
            }
            
            // Detectar preferências do usuário
            this.detectUserPreferences();
            
            // Configurar performance
            this.setupPerformance();
            
            // Inicializar componentes
            this.initIntersectionObserver();
            this.initMicroInteractions();
            this.initPageTransitions();
            this.initCustomAnimations();
            
            // Eventos
            this.bindEvents();
            
            // Marcar como inicializado
            this.state.initialized = true;
            
            // Debug
            this.debug('Sistema de animações inicializado', this.settings);
        },
        
        /**
         * Detecta preferências do usuário
         */
        detectUserPreferences: function() {
            // Verificar prefers-reduced-motion
            this.state.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            
            // Verificar dispositivo touch
            this.state.isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
            
            // Ajustar configurações baseado nas preferências
            if (this.state.prefersReducedMotion && this.settings.reduceMotion) {
                this.settings.duration = 0.01;
                this.settings.hoverEffects = false;
                this.settings.pageTransitions = false;
            }
        },
        
        /**
         * Configura otimizações de performance
         */
        setupPerformance: function() {
            if (this.settings.gpuAcceleration) {
                // Adicionar will-change aos elementos animados
                const style = document.createElement('style');
                style.textContent = `
                    .cct-animate,
                    .cct-hover-effect,
                    .cct-page-transition {
                        will-change: transform, opacity;
                        transform: translateZ(0);
                    }
                `;
                document.head.appendChild(style);
            }
        },
        
        /**
         * Inicializa Intersection Observer
         */
        initIntersectionObserver: function() {
            if (!this.settings.intersectionObserver || !('IntersectionObserver' in window)) {
                // Fallback para navegadores sem suporte
                this.initScrollAnimations();
                return;
            }
            
            // Configurações do observer
            const observerOptions = {
                root: null,
                rootMargin: '50px',
                threshold: 0.1
            };
            
            // Criar observer
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.triggerAnimation(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            // Observar elementos com animações
            this.observeAnimatedElements(observer);
            
            // Armazenar observer
            this.state.observers.push(observer);
        },
        
        /**
         * Observa elementos animados
         */
        observeAnimatedElements: function(observer) {
            // Elementos com classe cct-animate
            const animatedElements = document.querySelectorAll('.cct-animate');
            animatedElements.forEach(element => {
                observer.observe(element);
            });
            
            // Elementos com data-animation
            const dataAnimatedElements = document.querySelectorAll('[data-animation]');
            dataAnimatedElements.forEach(element => {
                observer.observe(element);
            });
            
            // Cache dos elementos
            this.cache.animatedElements = [...animatedElements, ...dataAnimatedElements];
        },
        
        /**
         * Dispara animação em um elemento
         */
        triggerAnimation: function(element) {
            const animationType = element.dataset.animation || element.classList.contains('cct-animate') ? 'fadeIn' : null;
            const trigger = element.dataset.trigger || 'scroll';
            const delay = parseFloat(element.dataset.delay || 0);
            
            if (!animationType) return;
            
            // Aplicar delay se especificado
            if (delay > 0) {
                setTimeout(() => {
                    this.applyAnimation(element, animationType);
                }, delay * 1000);
            } else {
                this.applyAnimation(element, animationType);
            }
        },
        
        /**
         * Aplica animação a um elemento
         */
        applyAnimation: function(element, animationType) {
            // Verificar se já está animado
            if (this.state.activeAnimations.has(element)) {
                return;
            }
            
            // Adicionar à lista de animações ativas
            this.state.activeAnimations.add(element);
            
            // Aplicar classe de animação
            element.classList.add('cct-animated', `cct-${animationType}`);
            
            // Configurar will-change
            if (this.settings.gpuAcceleration) {
                element.style.willChange = 'transform, opacity';
            }
            
            // Remover will-change após animação
            const duration = this.getAnimationDuration(element);
            setTimeout(() => {
                if (this.settings.gpuAcceleration) {
                    element.style.willChange = 'auto';
                }
                this.state.activeAnimations.delete(element);
            }, duration * 1000);
            
            // Debug
            this.debug(`Animação aplicada: ${animationType}`, element);
        },
        
        /**
         * Obtém duração da animação
         */
        getAnimationDuration: function(element) {
            const customDuration = element.dataset.duration;
            if (customDuration) {
                return parseFloat(customDuration);
            }
            
            const computedStyle = window.getComputedStyle(element);
            const duration = computedStyle.animationDuration;
            
            if (duration && duration !== '0s') {
                return parseFloat(duration);
            }
            
            return this.settings.duration;
        },
        
        /**
         * Inicializa micro-interações
         */
        initMicroInteractions: function() {
            this.initHoverEffects();
            this.initFocusEffects();
            this.initLoadingEffects();
            this.initButtonEffects();
            this.initFormEffects();
        },
        
        /**
         * Inicializa efeitos de hover
         */
        initHoverEffects: function() {
            if (!this.settings.hoverEffects || this.state.isTouch) {
                return;
            }
            
            // Elementos com efeitos de hover
            const hoverElements = document.querySelectorAll('.cct-hover-effect, [data-hover-effect]');
            
            hoverElements.forEach(element => {
                const effect = element.dataset.hoverEffect || this.getHoverEffectFromClass(element);
                
                if (effect) {
                    this.setupHoverEffect(element, effect);
                }
            });
            
            // Cache dos elementos
            this.cache.hoverElements = hoverElements;
        },
        
        /**
         * Configura efeito de hover
         */
        setupHoverEffect: function(element, effect) {
            element.addEventListener('mouseenter', () => {
                this.applyHoverEffect(element, effect, true);
            });
            
            element.addEventListener('mouseleave', () => {
                this.applyHoverEffect(element, effect, false);
            });
        },
        
        /**
         * Aplica efeito de hover
         */
        applyHoverEffect: function(element, effect, isHover) {
            const className = `cct-hover-${effect}${isHover ? '-active' : ''}`;
            
            if (isHover) {
                element.classList.add(className);
            } else {
                element.classList.remove(className);
            }
        },
        
        /**
         * Obtém efeito de hover da classe
         */
        getHoverEffectFromClass: function(element) {
            const classes = element.classList;
            const hoverClasses = ['lift', 'glow', 'tilt', 'zoom', 'slide_up'];
            
            for (const hoverClass of hoverClasses) {
                if (classes.contains(`cct-hover-${hoverClass}`)) {
                    return hoverClass;
                }
            }
            
            return null;
        },
        
        /**
         * Inicializa efeitos de foco
         */
        initFocusEffects: function() {
            if (!this.settings.focusEffects) {
                return;
            }
            
            // Elementos focáveis
            const focusableElements = document.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            focusableElements.forEach(element => {
                element.addEventListener('focus', () => {
                    this.applyFocusEffect(element, true);
                });
                
                element.addEventListener('blur', () => {
                    this.applyFocusEffect(element, false);
                });
            });
            
            // Cache dos elementos
            this.cache.focusElements = focusableElements;
        },
        
        /**
         * Aplica efeito de foco
         */
        applyFocusEffect: function(element, isFocused) {
            if (isFocused) {
                element.classList.add('cct-focus-active');
            } else {
                element.classList.remove('cct-focus-active');
            }
        },
        
        /**
         * Inicializa efeitos de carregamento
         */
        initLoadingEffects: function() {
            if (!this.settings.loadingEffects) {
                return;
            }
            
            // Elementos de carregamento
            const loadingElements = document.querySelectorAll('.cct-loading, [data-loading]');
            
            loadingElements.forEach(element => {
                const loadingType = element.dataset.loading || 'spinner';
                this.applyLoadingEffect(element, loadingType);
            });
        },
        
        /**
         * Aplica efeito de carregamento
         */
        applyLoadingEffect: function(element, type) {
            element.classList.add('cct-loading-active', `cct-loading-${type}`);
        },
        
        /**
         * Remove efeito de carregamento
         */
        removeLoadingEffect: function(element) {
            const loadingClasses = Array.from(element.classList).filter(cls => cls.startsWith('cct-loading'));
            element.classList.remove(...loadingClasses);
        },
        
        /**
         * Inicializa efeitos de botão
         */
        initButtonEffects: function() {
            // Efeito ripple
            const rippleButtons = document.querySelectorAll('.cct-button-ripple, [data-ripple]');
            
            rippleButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    this.createRippleEffect(button, e);
                });
            });
            
            // Efeito pulse
            const pulseButtons = document.querySelectorAll('.cct-button-pulse, [data-pulse]');
            
            pulseButtons.forEach(button => {
                button.addEventListener('mouseenter', () => {
                    button.classList.add('cct-pulse-active');
                });
                
                button.addEventListener('mouseleave', () => {
                    button.classList.remove('cct-pulse-active');
                });
            });
        },
        
        /**
         * Cria efeito ripple
         */
        createRippleEffect: function(element, event) {
            const rect = element.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;
            
            const ripple = document.createElement('span');
            ripple.className = 'cct-ripple';
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.5);
                border-radius: 50%;
                transform: scale(0);
                animation: cct-ripple-animation 0.6s ease-out;
                pointer-events: none;
            `;
            
            // Garantir posição relativa no elemento pai
            if (getComputedStyle(element).position === 'static') {
                element.style.position = 'relative';
            }
            
            element.appendChild(ripple);
            
            // Remover ripple após animação
            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.parentNode.removeChild(ripple);
                }
            }, 600);
        },
        
        /**
         * Inicializa efeitos de formulário
         */
        initFormEffects: function() {
            // Inputs com foco animado
            const animatedInputs = document.querySelectorAll('.cct-input-animated, [data-input-animation]');
            
            animatedInputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.classList.add('cct-input-focused');
                });
                
                input.addEventListener('blur', () => {
                    if (!input.value) {
                        input.classList.remove('cct-input-focused');
                    }
                });
                
                // Verificar se já tem valor
                if (input.value) {
                    input.classList.add('cct-input-focused');
                }
            });
            
            // Checkboxes animados
            const animatedCheckboxes = document.querySelectorAll('.cct-checkbox-animated, [data-checkbox-animation]');
            
            animatedCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    if (checkbox.checked) {
                        checkbox.classList.add('cct-checkbox-checked');
                    } else {
                        checkbox.classList.remove('cct-checkbox-checked');
                    }
                });
            });
        },
        
        /**
         * Inicializa transições de página
         */
        initPageTransitions: function() {
            if (!this.settings.pageTransitions) {
                return;
            }
            
            // Interceptar cliques em links
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href]');
                
                if (link && this.shouldTransition(link)) {
                    e.preventDefault();
                    this.performPageTransition(link.href);
                }
            });
            
            // Transição de entrada na página atual
            this.performEnterTransition();
        },
        
        /**
         * Verifica se deve fazer transição
         */
        shouldTransition: function(link) {
            const href = link.getAttribute('href');
            
            // Não fazer transição para:
            if (
                !href ||
                href.startsWith('#') ||
                href.startsWith('mailto:') ||
                href.startsWith('tel:') ||
                href.includes('wp-admin') ||
                link.target === '_blank' ||
                link.hostname !== window.location.hostname
            ) {
                return false;
            }
            
            return true;
        },
        
        /**
         * Executa transição de página
         */
        performPageTransition: function(url) {
            const transitionType = this.settings.pageTransitionType;
            const duration = this.settings.pageTransitionDuration;
            
            // Aplicar transição de saída
            document.body.classList.add('cct-page-transitioning', `cct-transition-${transitionType}-out`);
            
            // Navegar após transição
            setTimeout(() => {
                window.location.href = url;
            }, duration * 1000);
        },
        
        /**
         * Executa transição de entrada
         */
        performEnterTransition: function() {
            const transitionType = this.settings.pageTransitionType;
            
            document.body.classList.add('cct-page-transitioning', `cct-transition-${transitionType}-in`);
            
            // Remover classes após transição
            setTimeout(() => {
                document.body.classList.remove('cct-page-transitioning', `cct-transition-${transitionType}-in`);
            }, this.settings.pageTransitionDuration * 1000);
        },
        
        /**
         * Inicializa animações customizadas
         */
        initCustomAnimations: function() {
            // Animações baseadas em scroll
            this.initScrollAnimations();
            
            // Animações de parallax
            this.initParallaxAnimations();
            
            // Animações de contador
            this.initCounterAnimations();
        },
        
        /**
         * Inicializa animações de scroll (fallback)
         */
        initScrollAnimations: function() {
            if (this.settings.intersectionObserver && 'IntersectionObserver' in window) {
                return; // Usar Intersection Observer quando disponível
            }
            
            // Fallback para scroll events
            let ticking = false;
            
            const handleScroll = () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        this.checkScrollAnimations();
                        ticking = false;
                    });
                    ticking = true;
                }
            };
            
            window.addEventListener('scroll', handleScroll, { passive: true });
        },
        
        /**
         * Verifica animações no scroll
         */
        checkScrollAnimations: function() {
            const elements = document.querySelectorAll('.cct-animate:not(.cct-animated)');
            const windowHeight = window.innerHeight;
            
            elements.forEach(element => {
                const rect = element.getBoundingClientRect();
                
                if (rect.top < windowHeight * 0.8) {
                    this.triggerAnimation(element);
                }
            });
        },
        
        /**
         * Inicializa animações de parallax
         */
        initParallaxAnimations: function() {
            const parallaxElements = document.querySelectorAll('.cct-parallax, [data-parallax]');
            
            if (parallaxElements.length === 0) {
                return;
            }
            
            let ticking = false;
            
            const handleParallax = () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        this.updateParallax(parallaxElements);
                        ticking = false;
                    });
                    ticking = true;
                }
            };
            
            window.addEventListener('scroll', handleParallax, { passive: true });
        },
        
        /**
         * Atualiza efeito parallax
         */
        updateParallax: function(elements) {
            const scrollTop = window.pageYOffset;
            
            elements.forEach(element => {
                const speed = parseFloat(element.dataset.parallaxSpeed || 0.5);
                const yPos = -(scrollTop * speed);
                
                element.style.transform = `translateY(${yPos}px)`;
            });
        },
        
        /**
         * Inicializa animações de contador
         */
        initCounterAnimations: function() {
            const counterElements = document.querySelectorAll('.cct-counter, [data-counter]');
            
            counterElements.forEach(element => {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.animateCounter(entry.target);
                            observer.unobserve(entry.target);
                        }
                    });
                });
                
                observer.observe(element);
            });
        },
        
        /**
         * Anima contador
         */
        animateCounter: function(element) {
            const target = parseInt(element.dataset.counter || element.textContent);
            const duration = parseInt(element.dataset.counterDuration || 2000);
            const start = 0;
            const startTime = performance.now();
            
            const updateCounter = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const current = Math.floor(start + (target - start) * easeOutQuart);
                
                element.textContent = current.toLocaleString();
                
                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target.toLocaleString();
                }
            };
            
            requestAnimationFrame(updateCounter);
        },
        
        /**
         * Vincula eventos
         */
        bindEvents: function() {
            // Redimensionamento da janela
            window.addEventListener('resize', this.debounce(() => {
                this.handleResize();
            }, 250));
            
            // Mudança de orientação
            window.addEventListener('orientationchange', () => {
                setTimeout(() => {
                    this.handleResize();
                }, 100);
            });
            
            // Visibilidade da página
            document.addEventListener('visibilitychange', () => {
                this.handleVisibilityChange();
            });
        },
        
        /**
         * Manipula redimensionamento
         */
        handleResize: function() {
            // Recalcular animações se necessário
            this.state.isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
            
            // Reinicializar hover effects se mudou para/de touch
            if (this.settings.hoverEffects) {
                this.initHoverEffects();
            }
        },
        
        /**
         * Manipula mudança de visibilidade
         */
        handleVisibilityChange: function() {
            if (document.hidden) {
                // Pausar animações quando página não está visível
                this.pauseAnimations();
            } else {
                // Retomar animações
                this.resumeAnimations();
            }
        },
        
        /**
         * Pausa animações
         */
        pauseAnimations: function() {
            document.body.classList.add('cct-animations-paused');
        },
        
        /**
         * Retoma animações
         */
        resumeAnimations: function() {
            document.body.classList.remove('cct-animations-paused');
        },
        
        /**
         * API pública para adicionar animação
         */
        animate: function(element, animationType, options = {}) {
            if (typeof element === 'string') {
                element = document.querySelector(element);
            }
            
            if (!element) {
                this.debug('Elemento não encontrado para animação');
                return;
            }
            
            // Aplicar opções
            if (options.duration) {
                element.style.animationDuration = options.duration + 's';
            }
            
            if (options.delay) {
                element.style.animationDelay = options.delay + 's';
            }
            
            if (options.easing) {
                element.style.animationTimingFunction = options.easing;
            }
            
            // Aplicar animação
            this.applyAnimation(element, animationType);
        },
        
        /**
         * API pública para remover animação
         */
        removeAnimation: function(element, animationType) {
            if (typeof element === 'string') {
                element = document.querySelector(element);
            }
            
            if (!element) {
                return;
            }
            
            element.classList.remove('cct-animated', `cct-${animationType}`);
            this.state.activeAnimations.delete(element);
        },
        
        /**
         * Utilitário debounce
         */
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        /**
         * Debug helper
         */
        debug: function(message, data = null) {
            if (window.cctAnimationsDebug) {
                console.log('[CCT Animations]', message, data);
            }
        },
        
        /**
         * Destroy - limpa recursos
         */
        destroy: function() {
            // Limpar observers
            this.state.observers.forEach(observer => {
                observer.disconnect();
            });
            
            // Limpar cache
            this.cache = {
                animatedElements: null,
                hoverElements: null,
                focusElements: null
            };
            
            // Resetar estado
            this.state.initialized = false;
            this.state.activeAnimations.clear();
            
            this.debug('Sistema de animações destruído');
        }
    };
    
    // Expor globalmente
    window.CCTAnimations = CCTAnimations;
    
    // Auto-inicializar se configurações estão disponíveis
    $(document).ready(function() {
        if (typeof cctAnimations !== 'undefined') {
            CCTAnimations.init(cctAnimations.settings);
        }
    });
    
})(jQuery);

/**
 * CSS Keyframes para animações
 * Injetado via JavaScript para melhor performance
 */
(function() {
    const animationCSS = `
        /* Animações base */
        @keyframes cct-fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes cct-fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        @keyframes cct-slideInUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes cct-slideInDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes cct-slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes cct-slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes cct-scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        @keyframes cct-scaleOut {
            from { transform: scale(1); opacity: 1; }
            to { transform: scale(0); opacity: 0; }
        }
        
        @keyframes cct-rotateIn {
            from { transform: rotate(-180deg); opacity: 0; }
            to { transform: rotate(0deg); opacity: 1; }
        }
        
        @keyframes cct-bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
        
        @keyframes cct-flipInX {
            0% { transform: perspective(400px) rotateX(90deg); opacity: 0; }
            40% { transform: perspective(400px) rotateX(-20deg); }
            60% { transform: perspective(400px) rotateX(10deg); opacity: 1; }
            80% { transform: perspective(400px) rotateX(-5deg); }
            100% { transform: perspective(400px) rotateX(0deg); opacity: 1; }
        }
        
        @keyframes cct-flipInY {
            0% { transform: perspective(400px) rotateY(90deg); opacity: 0; }
            40% { transform: perspective(400px) rotateY(-20deg); }
            60% { transform: perspective(400px) rotateY(10deg); opacity: 1; }
            80% { transform: perspective(400px) rotateY(-5deg); }
            100% { transform: perspective(400px) rotateY(0deg); opacity: 1; }
        }
        
        /* Efeito ripple */
        @keyframes cct-ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        /* Efeito pulse */
        @keyframes cct-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* Classes de animação */
        .cct-fadeIn { animation: cct-fadeIn var(--cct-animation-duration, 0.3s) var(--cct-animation-easing, ease-in-out); }
        .cct-fadeOut { animation: cct-fadeOut var(--cct-animation-duration, 0.3s) var(--cct-animation-easing, ease-in-out); }
        .cct-slideInUp { animation: cct-slideInUp var(--cct-animation-duration, 0.5s) var(--cct-animation-easing, ease-out); }
        .cct-slideInDown { animation: cct-slideInDown var(--cct-animation-duration, 0.5s) var(--cct-animation-easing, ease-out); }
        .cct-slideInLeft { animation: cct-slideInLeft var(--cct-animation-duration, 0.5s) var(--cct-animation-easing, ease-out); }
        .cct-slideInRight { animation: cct-slideInRight var(--cct-animation-duration, 0.5s) var(--cct-animation-easing, ease-out); }
        .cct-scaleIn { animation: cct-scaleIn var(--cct-animation-duration, 0.4s) var(--cct-animation-easing, ease-out); }
        .cct-scaleOut { animation: cct-scaleOut var(--cct-animation-duration, 0.4s) var(--cct-animation-easing, ease-in); }
        .cct-rotateIn { animation: cct-rotateIn var(--cct-animation-duration, 0.6s) var(--cct-animation-easing, ease-in-out); }
        .cct-bounceIn { animation: cct-bounceIn var(--cct-animation-duration, 0.8s) var(--cct-animation-easing, ease-out); }
        .cct-flipInX { animation: cct-flipInX var(--cct-animation-duration, 0.7s) var(--cct-animation-easing, ease-in-out); }
        .cct-flipInY { animation: cct-flipInY var(--cct-animation-duration, 0.7s) var(--cct-animation-easing, ease-in-out); }
        
        /* Estados de micro-interações */
        .cct-pulse-active { animation: cct-pulse 1s ease-in-out infinite; }
        
        /* Transições de página */
        .cct-page-transitioning { pointer-events: none; }
        
        .cct-transition-fade-out { opacity: 0; transition: opacity var(--cct-page-transition-duration, 0.5s) ease-in-out; }
        .cct-transition-fade-in { opacity: 0; animation: cct-fadeIn var(--cct-page-transition-duration, 0.5s) ease-in-out; }
        
        /* Pausar animações */
        .cct-animations-paused * {
            animation-play-state: paused !important;
        }
        
        /* Acessibilidade */
        @media (prefers-reduced-motion: reduce) {
            .cct-animate,
            .cct-hover-effect,
            .cct-page-transition {
                animation: none !important;
                transition: none !important;
            }
        }
    `;
    
    // Injetar CSS
    const style = document.createElement('style');
    style.textContent = animationCSS;
    document.head.appendChild(style);
})();