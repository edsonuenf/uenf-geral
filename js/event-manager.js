// Log para confirmar que o script foi carregado
console.log('[EventRouter] Script event-manager.js carregado com sucesso!');

/**
 * Roteador de Eventos para o Tema UENF
 * 
 * Este script roteia eventos para os manipuladores corretos com base nas classes dos elementos.
 * Cada componente pode registrar suas próprias classes de elementos que devem acionar seus manipuladores.
 */

class EventRouter {
    constructor() {
        this.handlers = new Map();
        this.components = new Map();
        this.initialized = false;
        
        // Classes que disparam eventos
        this.eventSources = new Set();
        
        // Mapa de classes para manipuladores
        this.classHandlers = new Map();
        
        this.init();
    }
    
    init() {
        if (this.initialized) return;
        
        console.log('[EventRouter] Inicializando roteador de eventos baseado em classes');
        
        // Configura o interceptador de eventos
        this.setupEventListeners();
        
        this.initialized = true;
    }
    
    /**
     * Registra um componente para receber eventos
     * @param {string} componentName - Nome do componente (ex: 'menu', 'shortcuts')
     * @param {Object} config - Configuração do componente
     * @param {HTMLElement} config.element - Elemento raiz do componente
     * @param {Function} config.handler - Função que processa os eventos
     * @param {Array} config.classes - Classes dos elementos que disparam eventos para este componente
     */
    registerComponent(componentName, { element, handler, classes = [] }) {
        console.group(`[EventRouter] Registrando componente: ${componentName}`);
        
        if (!element || !handler) {
            console.error(`[EventRouter] Componente ${componentName} inválido: elemento ou handler não fornecido`);
            console.groupEnd();
            return false;
        }
        
        console.log('[EventRouter] Elemento do componente:', element);
        console.log('[EventRouter] Classes fornecidas:', classes);
        
        // Armazena o componente
        this.components.set(componentName, {
            element,
            handler,
            enabled: true,
            classes: new Set(classes)
        });
        
        // Registra as classes de origem dos eventos
        classes.forEach(className => {
            if (!this.classHandlers.has(className)) {
                this.classHandlers.set(className, new Set());
            }
            this.classHandlers.get(className).add(componentName);
            console.log(`[EventRouter] Classe registrada: .${className} para ${componentName}`);
        });
        
        console.log(`[EventRouter] Componente ${componentName} registrado com sucesso`);
        console.log('[EventRouter] Componentes registrados:', Array.from(this.components.keys()));
        console.log('[EventRouter] Classes registradas:', Array.from(this.classHandlers.keys()));
        console.groupEnd();
        
        return true;
    }
    
    /**
     * Configura os listeners de eventos
     */
    setupEventListeners() {
        // Usa captura para garantir que pegamos o evento primeiro
        document.addEventListener('click', this.handleEvent.bind(this), true);
        document.addEventListener('keydown', this.handleEvent.bind(this), true);
        document.addEventListener('focusin', this.handleEvent.bind(this), true);
        
        console.log('[EventRouter] Listeners de eventos configurados');
    }
    
    /**
     * Encontra o elemento ancestral mais próximo que corresponde ao seletor
     * @param {HTMLElement} element - Elemento inicial
     * @param {string} selector - Seletor CSS
     * @returns {HTMLElement|null} - Elemento correspondente ou null
     */
    closestElement(element, selector) {
        if (!element || !selector) return null;
        
        // Usa o método nativo se disponível
        if (element.closest) {
            return element.closest(selector);
        }
        
        // Implementação alternativa para navegadores mais antigos
        while (element) {
            if (this.matchesSelector(element, selector)) {
                return element;
            }
            element = element.parentElement;
        }
        return null;
    }
    
    /**
     * Verifica se o elemento corresponde ao seletor
     * @param {HTMLElement} element - Elemento a ser verificado
     * @param {string} selector - Seletor CSS
     * @returns {boolean} - True se o elemento corresponder ao seletor
     */
    matchesSelector(element, selector) {
        const matches = element.matches || 
                       element.matchesSelector || 
                       element.webkitMatchesSelector || 
                       element.mozMatchesSelector ||
                       element.msMatchesSelector ||
                       element.oMatchesSelector;
        
        if (matches) {
            return matches.call(element, selector);
        }
        
        // Implementação alternativa para navegadores antigos
        const matchesList = (element.document || element.ownerDocument).querySelectorAll(selector);
        let i = matchesList.length;
        
        while (--i >= 0 && matchesList.item(i) !== element) {}
        return i > -1;
    }
    
    /**
     * Manipulador principal de eventos
     * @param {Event} event - O evento a ser processado
     */
    handleEvent(event) {
        console.group(`[EventRouter] handleEvent - ${event.type}`);
        console.log('Evento recebido:', event.type, 'em', event.target);
        
        // Verifica se o evento já foi tratado
        if (event._eventRouterProcessed) {
            console.log('[EventRouter] Evento já processado, ignorando...');
            console.groupEnd();
            return;
        }
        
        // Evita processar eventos muito rapidamente
        if (this.lastEvent && event.timeStamp - this.lastEvent < 10) {
            console.log('[EventRouter] Evento muito rápido, ignorando...');
            console.groupEnd();
            return;
        }
        this.lastEvent = event.timeStamp;
        
        // Encontra o elemento que disparou o evento e suas classes
        const target = event.target;
        console.log('[EventRouter] Elemento alvo:', target);
        
        if (!target) {
            console.log('[EventRouter] Elemento alvo inválido');
            console.groupEnd();
            return;
        }
        
        if (!target.classList || target.classList.length === 0) {
            console.log('[EventRouter] Elemento sem classes, verificando ancestrais...');
            // Verifica se algum ancestral tem classes relevantes
            const ancestorWithClasses = this.findAncestorWithClasses(target);
            if (ancestorWithClasses) {
                console.log('[EventRouter] Ancestral com classes encontrado:', ancestorWithClasses);
                console.log('[EventRouter] Classes do ancestral:', Array.from(ancestorWithClasses.classList).join(', '));
            } else {
                console.log('[EventRouter] Nenhum ancestral com classes relevantes encontrado');
                console.groupEnd();
                return;
            }
        } else {
            console.log('[EventRouter] Classes do elemento:', Array.from(target.classList).join(', '));
        }
        
        // Verifica se o clique foi em um submenu ou em um de seus filhos
        const isMenuClick = this.closestElement(target, '.menu-item-has-children, .page_item_has_children, .submenu-toggle');
        if (isMenuClick) {
            console.log('[EventRouter] Clique em submenu ou filho, processando...');
            // Não retornamos mais aqui, permitindo que o evento seja processado
            // pelos manipuladores registrados
        }
        
        // Encontra todos os componentes que devem processar este evento
        const componentsToProcess = this.findComponentsForEvent(event);
        
        if (componentsToProcess.size > 0) {
            console.log('[EventRouter] Componentes para processar:', Array.from(componentsToProcess).join(', '));
            
            // Se encontrou componentes para processar o evento, previne a propagação
            // apenas se não for um clique em um item de menu ou em um de seus filhos diretos
            const isMenuTarget = this.closestElement(event.target, '.menu-item-has-children > a, .page_item_has_children > a, .submenu-toggle');
            if (!isMenuTarget) {
                // Para eventos que não são em itens de menu, prevenimos a propagação
                // para evitar que outros manipuladores interfiram
                event.stopPropagation();
            } else {
                console.log('[EventRouter] Clique em item de menu, permitindo propagação controlada');
                // Para itens de menu, prevenimos o comportamento padrão apenas
                // para evitar navegação indesejada
                event.preventDefault();
            }
            
            // Chama os manipuladores dos componentes
            componentsToProcess.forEach(componentName => {
                const component = this.components.get(componentName);
                if (component && component.enabled && component.handler) {
                    try {
                        component.handler(event, component);
                    } catch (error) {
                        console.error(`[EventRouter] Erro no manipulador do componente ${componentName}:`, error);
                    }
                }
            });
        } else {
            console.log('[EventRouter] Nenhum componente encontrado para processar o evento');
        }
        
        console.groupEnd();
    }
    
    /**
     * Encontra o primeiro ancestral com classes relevantes
     * @param {HTMLElement} element - Elemento inicial
     * @returns {HTMLElement|null} - Elemento ancestral com classes ou null
     */
    findAncestorWithClasses(element) {
        let current = element.parentElement;
        while (current && current !== document.documentElement) {
            if (current.classList && current.classList.length > 0) {
                // Verifica se alguma classe do elemento está registrada
                for (const className of current.classList) {
                    if (this.classHandlers.has(className)) {
                        return current;
                    }
                }
            }
            current = current.parentElement;
        }
        return null;
    }

    /**
     * Encontra os componentes que devem processar um evento com base nas classes do elemento alvo
     * @param {Event} event - O evento a ser processado
     * @returns {Set} Conjunto de nomes de componentes que devem processar o evento
     */
    findComponentsForEvent(event) {
        const components = new Set();
        let element = event.target;
        
        console.group('[EventRouter] findComponentsForEvent');
        console.log('[EventRouter] Procurando manipuladores para o elemento:', element);
        console.log('[EventRouter] Classes do elemento:', element.className);
        console.log('[EventRouter] Classes registradas:', Array.from(this.classHandlers.keys()).join(', '));
        
        // Verifica se o clique foi em um item de menu ou em um de seus filhos
        const menuItem = this.closestElement(element, '.menu-item, .menu-item-has-children, .page_item_has_children, .submenu-toggle, .menu-item-has-children > a, .page_item_has_children > a');
        
        if (menuItem) {
            console.log('[EventRouter] Clique em item de menu ou filho, verificando manipuladores...');
            console.log('[EventRouter] Tag do elemento:', menuItem.tagName);
            console.log('[EventRouter] Classes do item de menu:', menuItem.className);
            
            // Adiciona as classes do item de menu à verificação
            if (menuItem.classList && menuItem.classList.length > 0) {
                console.log('[EventRouter] Verificando classes do item de menu:', Array.from(menuItem.classList).join(', '));
                
                menuItem.classList.forEach(className => {
                    if (this.classHandlers.has(className)) {
                        console.log(`[EventRouter] Encontrada classe registrada: ${className}`);
                        this.classHandlers.get(className).forEach(componentName => {
                            components.add(componentName);
                            console.log(`[EventRouter] Adicionando componente: ${componentName}`);
                        });
                    }
                });
            } else {
                console.log('[EventRouter] Item de menu sem classes');
            }
        
        // Se não encontrou componentes ainda, verifica o elemento clicado e seus ancestrais
        if (components.size === 0) {
            console.log('[EventRouter] Nenhum manipulador encontrado no item de menu, verificando ancestrais...');
            
            while (element && element !== document.documentElement) {
                if (element.classList && element.classList.length > 0) {
                    console.log(`[EventRouter] Verificando elemento:`, element);
                    console.log(`[EventRouter] Classes:`, Array.from(element.classList).join(', '));
                    
                    element.classList.forEach(className => {
                        if (this.classHandlers.has(className)) {
                            console.log(`[EventRouter] Encontrada classe registrada: ${className}`);
                            this.classHandlers.get(className).forEach(componentName => {
                                components.add(componentName);
                                console.log(`[EventRouter] Adicionando componente: ${componentName}`);
                            });
                        }
                    });
                    
                    // Se encontrou algum componente, para a busca
                    if (components.size > 0) {
                        console.log(`[EventRouter] Encontrados ${components.size} componentes para o evento`);
                        break;
                    }
                }
                
                // Vai para o elemento pai
                element = element.parentElement;
            }
        } else {
            console.log(`[EventRouter] Encontrados ${components.size} componentes no item de menu`);
        }
        
        console.log('[EventRouter] Componentes que vão processar o evento:', Array.from(components).join(', '));
        return components;
    }
    
    /**
     * Habilita/desabilita um componente
     * @param {string} componentName - Nome do componente
     * @param {boolean} enabled - Se true, habilita o componente; se false, desabilita
     */
    setComponentEnabled(componentName, enabled = true) {
        const component = this.components.get(componentName);
        if (component) {
            component.enabled = enabled;
            console.log(`[EventRouter] Componente ${componentName} ${enabled ? 'habilitado' : 'desabilitado'}`);
            return true;
        }
        return false;
    }
}

// Inicializa o roteador de eventos quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.uenfEventRouter = new EventRouter();
    
    // Exemplo de como registrar o menu (deve ser chamado pelo próprio componente do menu)
    // window.uenfEventRouter.registerComponent('menu', {
    //     element: document.querySelector('.new-menu'),
    //     handler: handleMenuEvent,
    //     classes: ['menu-item', 'submenu-toggle', 'menu-item-has-children']
    // });
    
    console.log('[EventRouter] Roteador de eventos inicializado');
});
