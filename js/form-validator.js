/**
 * Validação de formulários no lado do cliente
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Inicializa o validador quando o documento estiver pronto
    $(document).ready(function() {
        // Aplica a validação a todos os formulários com a classe 'uenf-validate'
        $('form.uenf-validate').each(function() {
            initFormValidation($(this));
        });
    });

    /**
     * Inicializa a validação para um formulário
     */
    function initFormValidation($form) {
        // Configuração do validador
        const settings = {
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            errorElement: 'div',
            errorPlacement: function(error, element) {
                const $field = $(element).closest('.form-group, .form-floating, .form-check');
                if ($field.length) {
                    $field.append(error);
                } else {
                    $(element).after(error);
                }
            },
            highlight: function(element, errorClass, validClass) {
                const $element = $(element);
                $element.addClass(errorClass).removeClass(validClass);
                $element.closest('.form-group, .form-floating, .form-check').addClass('has-error');
            },
            unhighlight: function(element, errorClass, validClass) {
                const $element = $(element);
                $element.removeClass(errorClass).addClass(validClass);
                $element.closest('.form-group, .form-floating, .form-check').removeClass('has-error');
            },
            submitHandler: function(form) {
                // Desabilita o botão de envio para evitar múltiplos envios
                const $submitButton = $(form).find('button[type="submit"], input[type="submit"]');
                const originalText = $submitButton.html();
                
                $submitButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' +
                    uenfFormValidator.messages.processing || 'Processando...'
                );
                
                // Envia o formulário
                form.submit();
                
                // Restaura o botão após 5 segundos (caso haja um erro de rede, por exemplo)
                setTimeout(function() {
                    $submitButton.prop('disabled', false).html(originalText);
                }, 5000);
                
                return false;
            }
        };

        // Adiciona regras de validação baseadas em atributos HTML5
        $form.find('[required]').each(function() {
            const $field = $(this);
            const fieldName = $field.attr('name');
            
            if (!fieldName) return;
            
            // Adiciona a classe de validação
            $field.addClass('validate-required');
            
            // Adiciona a regra required
            if (!$field.data('rules')) {
                $field.data('rules', {});
            }
            
            $field.data('rules').required = true;
        });

        // Configura o validador
        $form.validate(settings);
        
        // Validação em tempo real
        setupRealTimeValidation($form);
        
        // Validação ao perder o foco
        setupBlurValidation($form);
        
        // Previne o envio do formulário se houver erros
        $form.on('submit', function(e) {
            if (!$form.valid()) {
                e.preventDefault();
                return false;
            }
        });
    }
    
    /**
     * Configura a validação em tempo real
     */
    function setupRealTimeValidation($form) {
        $form.on('input', 'input, select, textarea', function() {
            const $field = $(this);
            
            // Não valida campos vazios em tempo real (a menos que já tenham sido tocados)
            if ($field.val() === '' && !$field.hasClass('touched')) {
                return;
            }
            
            validateField($field);
        });
    }
    
    /**
     * Configura a validação ao perder o foco
     */
    function setupBlurValidation($form) {
        $form.on('blur', 'input, select, textarea', function() {
            const $field = $(this);
            $field.addClass('touched');
            validateField($field);
        });
    }
    
    /**
     * Valida um campo individual via AJAX
     */
    function validateField($field) {
        const fieldName = $field.attr('name');
        const fieldValue = $field.val();
        const $form = $field.closest('form');
        const formId = $form.attr('id') || '';
        
        // Obtém as regras do campo
        const rules = {};
        
        // Regras baseadas em atributos HTML5
        if ($field.attr('required')) {
            rules.required = true;
        }
        
        if ($field.attr('minlength')) {
            rules.minlength = parseInt($field.attr('minlength'));
        }
        
        if ($field.attr('maxlength')) {
            rules.maxlength = parseInt($field.attr('maxlength'));
        }
        
        if ($field.attr('min')) {
            rules.min = parseFloat($field.attr('min'));
        }
        
        if ($field.attr('max')) {
            rules.max = parseFloat($field.attr('max'));
        }
        
        if ($field.attr('pattern')) {
            rules.pattern = $field.attr('pattern');
        }
        
        // Tipo de campo
        const type = $field.attr('type') || '';
        
        if (type === 'email') {
            rules.email = true;
        } else if (type === 'url') {
            rules.url = true;
        } else if (type === 'number' || type === 'range') {
            rules.number = true;
        } else if (type === 'date') {
            rules.date = true;
        } else if (type === 'time') {
            rules.time = true;
        }
        
        // Verifica se há um campo de confirmação (senha, e-mail, etc.)
        if ($field.data('equal-to')) {
            rules.equalTo = $field.data('equal-to');
        }
        
        // Se não houver regras, não valida
        if (Object.keys(rules).length === 0) {
            return;
        }
        
        // Envia a validação para o servidor
        $.ajax({
            url: uenfFormValidator.ajaxUrl,
            type: 'POST',
            data: {
                action: 'uenf_validate_form',
                nonce: uenfFormValidator.nonce,
                form_id: formId,
                field: fieldName,
                value: fieldValue,
                rules: rules
            },
            beforeSend: function() {
                $field.addClass('validating');
                $field.next('.field-error').remove();
            },
            success: function(response) {
                $field.removeClass('validating');
                
                if (response.success) {
                    // Campo válido
                    $field.removeClass('is-invalid').addClass('is-valid');
                    $field.closest('.form-group, .form-floating, .form-check').removeClass('has-error');
                } else {
                    // Campo inválido
                    $field.removeClass('is-valid').addClass('is-invalid');
                    $field.closest('.form-group, .form-floating, .form-check').addClass('has-error');
                    
                    // Exibe a mensagem de erro
                    const $error = $('<div class="invalid-feedback"></div>');
                    $error.html(response.data.errors.join('<br>'));
                    $field.after($error);
                }
            },
            error: function() {
                $field.removeClass('validating');
                console.error('Erro ao validar o campo');
            }
        });
    }
    
    /**
     * Adiciona um método de validação personalizado para CPF
     */
    $.validator.addMethod('cpf', function(value, element) {
        // Remove caracteres não numéricos
        value = value.replace(/[^\d]+/g, '');
        
        // Verifica se todos os dígitos são iguais
        if (value.length !== 11 || !!value.match(/(\d)\1{10}/)) {
            return false;
        }
        
        // Validação do primeiro dígito verificador
        let sum = 0;
        let remainder;
        
        for (let i = 1; i <= 9; i++) {
            sum = sum + parseInt(value.substring(i - 1, i)) * (11 - i);
        }
        
        remainder = (sum * 10) % 11;
        
        if ((remainder === 10) || (remainder === 11)) {
            remainder = 0;
        }
        
        if (remainder !== parseInt(value.substring(9, 10))) {
            return false;
        }
        
        // Validação do segundo dígito verificador
        sum = 0;
        
        for (let i = 1; i <= 10; i++) {
            sum = sum + parseInt(value.substring(i - 1, i)) * (12 - i);
        }
        
        remainder = (sum * 10) % 11;
        
        if ((remainder === 10) || (remainder === 11)) {
            remainder = 0;
        }
        
        return remainder === parseInt(value.substring(10, 11));
    }, 'Por favor, insira um CPF válido.');
    
    /**
     * Adiciona um método de validação personalizado para CNPJ
     */
    $.validator.addMethod('cnpj', function(value, element) {
        // Remove caracteres não numéricos
        value = value.replace(/[^\d]+/g, '');
        
        // Verifica se todos os dígitos são iguais
        if (value.length !== 14 || /^(\d)\1+$/.test(value)) {
            return false;
        }
        
        // Validação do primeiro dígito verificador
        let size = value.length - 2;
        let numbers = value.substring(0, size);
        const digits = value.substring(size);
        let sum = 0;
        let pos = size - 7;
        
        for (let i = size; i >= 1; i--) {
            sum += numbers.charAt(size - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }
        
        let result = sum % 11 < 2 ? 0 : 11 - (sum % 11);
        
        if (result !== parseInt(digits.charAt(0))) {
            return false;
        }
        
        // Validação do segundo dígito verificador
        size = size + 1;
        numbers = value.substring(0, size);
        sum = 0;
        pos = size - 7;
        
        for (let i = size; i >= 1; i--) {
            sum += numbers.charAt(size - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }
        
        result = sum % 11 < 2 ? 0 : 11 - (sum % 11);
        
        return result === parseInt(digits.charAt(1));
    }, 'Por favor, insira um CNPJ válido.');
    
    /**
     * Adiciona um método de validação personalizado para telefone brasileiro
     */
    $.validator.addMethod('phoneBR', function(value, element) {
        // Remove caracteres não numéricos
        value = value.replace(/[^\d]+/g, '');
        
        // Verifica se é um número de telefone válido (8 ou 9 dígitos + DDD)
        return /^[1-9]{2}(?:[2-8]|9[1-9])[0-9]{3}[0-9]{4,5}$/.test(value);
    }, 'Por favor, insira um número de telefone válido.');
    
    /**
     * Adiciona um método de validação personalizado para CEP
     */
    $.validator.addMethod('cep', function(value, element) {
        // Remove caracteres não numéricos
        value = value.replace(/[^\d]+/g, '');
        
        // Verifica se é um CEP válido (8 dígitos)
        return /^\d{8}$/.test(value);
    }, 'Por favor, insira um CEP válido.');
    
    /**
     * Adiciona um método de validação personalizado para data brasileira
     */
    $.validator.addMethod('dateBR', function(value, element) {
        // Verifica o formato da data (dd/mm/yyyy)
        if (!/^\d{2}\/\d{2}\/\d{4}$/.test(value)) {
            return false;
        }
        
        // Extrai dia, mês e ano
        const parts = value.split('/');
        const day = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10);
        const year = parseInt(parts[2], 10);
        
        // Verifica se a data é válida
        if (year < 1900 || year > 2100 || month === 0 || month > 12) {
            return false;
        }
        
        const monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        
        // Ajusta para anos bissextos
        if (year % 400 === 0 || (year % 100 !== 0 && year % 4 === 0)) {
            monthLength[1] = 29;
        }
        
        return day > 0 && day <= monthLength[month - 1];
    }, 'Por favor, insira uma data válida no formato DD/MM/AAAA.');
    
})(jQuery);
