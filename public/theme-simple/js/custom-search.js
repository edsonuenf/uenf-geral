/**
 * Sistema de Busca Personalizado UENF
 * Versão robusta com múltiplas tentativas de inicialização
 */

(function() {
    'use strict';
    
    var searchSystem = {
        isOpen: false,
        toggleBtn: null,
        formContainer: null,
        searchField: null,
        initialized: false,
        
        // Inicializar sistema
        init: function() {
            if (this.initialized) return;
            
            console.log('[UENF Search] Tentando inicializar...');
            
            // Buscar elementos
            this.toggleBtn = document.querySelector('.search-toggle-btn');
            this.formContainer = document.querySelector('.search-form-container');
            this.searchField = document.querySelector('.search-field');
            
            console.log('[UENF Search] Elementos:', {
                toggleBtn: !!this.toggleBtn,
                formContainer: !!this.formContainer,
                searchField: !!this.searchField
            });
            
            // Se não há elementos personalizados, não interferir
            if (!this.toggleBtn && !this.formContainer) {
                console.log('[UENF Search] Busca padrão detectada - não interferindo');
                this.initialized = true;
                return true;
            }
            
            // Se há elementos personalizados mas incompletos, tentar novamente
            if (this.toggleBtn && (!this.formContainer || !this.searchField)) {
                console.warn('[UENF Search] Elementos personalizados incompletos, tentando novamente...');
                return false;
            }
            
            this.bindEvents();
            this.initialized = true;
            console.log('[UENF Search] Sistema inicializado com sucesso!');
            return true;
        },
        
        // Vincular eventos
        bindEvents: function() {
            var self = this;
            
            // Click no botão toggle (apenas se existir)
            if (this.toggleBtn) {
                this.toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('[UENF Search] Botão toggle clicado, estado atual:', self.isOpen);
                    self.toggle();
                });
            }
            
            // Permitir submit normal do formulário
            var submitBtn = document.querySelector('.search-submit');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    // Não prevenir o comportamento padrão do submit
                    console.log('[UENF Search] Botão submit clicado - enviando formulário');
                });
            }
            
            // Fechar com ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && self.isOpen) {
                    self.close();
                }
            });
            
            // Fechar clicando fora
            document.addEventListener('click', function(e) {
                if (self.isOpen && 
                    !self.formContainer.contains(e.target) && 
                    !self.toggleBtn.contains(e.target)) {
                    self.close();
                }
            });
            
            // Prevenir fechamento ao clicar no formulário
            this.formContainer.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        },
        
        // Alternar estado
        toggle: function() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },
        
        // Abrir busca
        open: function() {
            this.formContainer.style.display = 'block';
            this.isOpen = true;
            
            var self = this;
            setTimeout(function() {
                self.searchField.focus();
                if (self.searchField.value) {
                    self.searchField.select();
                }
            }, 150);
            
            console.log('[UENF Search] Busca aberta');
        },
        
        // Fechar busca
        close: function() {
            this.formContainer.style.display = 'none';
            this.isOpen = false;
            console.log('[UENF Search] Busca fechada');
        }
    };
    
    // Função para tentar inicializar
    function tryInit() {
        if (searchSystem.init()) {
            return true;
        }
        return false;
    }
    
    // Múltiplas tentativas de inicialização
    var attempts = 0;
    var maxAttempts = 10;
    
    function attemptInit() {
        attempts++;
        console.log('[UENF Search] Tentativa', attempts, 'de', maxAttempts);
        
        if (tryInit()) {
            console.log('[UENF Search] Inicializado na tentativa', attempts);
            return;
        }
        
        if (attempts < maxAttempts) {
            setTimeout(attemptInit, 500);
        } else {
            console.error('[UENF Search] Falha ao inicializar após', maxAttempts, 'tentativas');
        }
    }
    
    // Iniciar quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(attemptInit, 100);
        });
    } else {
        setTimeout(attemptInit, 100);
    }
    
    // Fallback com window.load
    window.addEventListener('load', function() {
        if (!searchSystem.initialized) {
            console.log('[UENF Search] Tentativa final no window.load');
            setTimeout(attemptInit, 200);
        }
    });
    
})();