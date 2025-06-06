<?php
/**
 * Classe para validação de formulários
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class UENF_Form_Validator {
    /**
     * Instância única da classe
     */
    private static $instance = null;
    
    /**
     * Erros de validação
     */
    private $errors = [];
    
    /**
     * Regras de validação
     */
    private $rules = [];
    
    /**
     * Mensagens de erro personalizadas
     */
    private $custom_messages = [];
    
    /**
     * Construtor privado para evitar instanciação direta
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Obtém a instância única da classe
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Inicializa os hooks do WordPress
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_uenf_validate_form', [$this, 'ajax_validate_form']);
        add_action('wp_ajax_nopriv_uenf_validate_form', [$this, 'ajax_validate_form']);
    }
    
    /**
     * Carrega scripts e estilos necessários
     */
    public function enqueue_scripts() {
        // CSS para estilização dos erros
        wp_enqueue_style(
            'uenf-form-validator',
            get_template_directory_uri() . '/css/components/form-validator.css',
            [],
            '1.0.0'
        );
        
        // JavaScript para validação no lado do cliente
        wp_enqueue_script(
            'uenf-form-validator',
            get_template_directory_uri() . '/js/form-validator.js',
            ['jquery'],
            '1.0.0',
            true
        );
        
        // Localiza o script com dados do AJAX
        wp_localize_script(
            'uenf-form-validator',
            'uenfFormValidator',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('uenf_form_validation_nonce'),
                'messages' => [
                    'required' => __('Este campo é obrigatório.', 'uenf-geral'),
                    'email' => __('Por favor, insira um endereço de e-mail válido.', 'uenf-geral'),
                    'minlength' => __('Este campo deve ter pelo menos %s caracteres.', 'uenf-geral'),
                    'maxlength' => __('Este campo deve ter no máximo %s caracteres.', 'uenf-geral'),
                    'equalTo' => __('Os valores não coincidem.', 'uenf-geral'),
                    'number' => __('Por favor, insira um número válido.', 'uenf-geral'),
                    'url' => __('Por favor, insira uma URL válida.', 'uenf-geral'),
                    'date' => __('Por favor, insira uma data válida.', 'uenf-geral'),
                    'time' => __('Por favor, insira um horário válido.', 'uenf-geral'),
                    'pattern' => __('O formato informado é inválido.', 'uenf-geral'),
                ]
            ]
        );
    }
    
    /**
     * Valida um formulário com base nas regras fornecidas
     * 
     * @param array $data Dados do formulário
     * @param array $rules Regras de validação
     * @param array $messages Mensagens de erro personalizadas
     * @return bool|array True se válido, array de erros se inválido
     */
    public function validate($data, $rules, $messages = []) {
        $this->errors = [];
        $this->rules = $rules;
        $this->custom_messages = $messages;
        
        foreach ($rules as $field => $field_rules) {
            $value = isset($data[$field]) ? $data[$field] : '';
            $this->validate_field($field, $value, $field_rules);
        }
        
        return empty($this->errors) ? true : $this->errors;
    }
    
    /**
     * Valida um campo individual
     */
    private function validate_field($field, $value, $rules) {
        // Se o campo não for obrigatório e estiver vazio, não valida as outras regras
        if (!in_array('required', $rules) && (is_null($value) || $value === '')) {
            return;
        }
        
        foreach ($rules as $rule) {
            $rule_parts = explode(':', $rule);
            $rule_name = $rule_parts[0];
            $rule_value = isset($rule_parts[1]) ? $rule_parts[1] : null;
            
            $method_name = 'validate_' . $rule_name;
            
            if (method_exists($this, $method_name)) {
                $this->$method_name($field, $value, $rule_value);
            }
        }
    }
    
    /**
     * Adiciona uma mensagem de erro
     */
    private function add_error($field, $rule, $params = []) {
        $message = $this->get_error_message($field, $rule, $params);
        
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        
        $this->errors[$field][] = $message;
    }
    
    /**
     * Obtém a mensagem de erro apropriada
     */
    private function get_error_message($field, $rule, $params = []) {
        // Verifica se há uma mensagem personalizada para este campo e regra
        $custom_key = $field . '.' . $rule;
        if (isset($this->custom_messages[$custom_key])) {
            return vsprintf($this->custom_messages[$custom_key], $params);
        }
        
        // Mensagens padrão
        $messages = [
            'required' => __('O campo %s é obrigatório.', 'uenf-geral'),
            'email' => __('O campo %s deve conter um endereço de e-mail válido.', 'uenf-geral'),
            'min' => __('O campo %s deve ser pelo menos %s.', 'uenf-geral'),
            'max' => __('O campo %s não pode ser maior que %s.', 'uenf-geral'),
            'minlength' => __('O campo %s deve ter pelo menos %s caracteres.', 'uenf-geral'),
            'maxlength' => __('O campo %s não pode ter mais que %s caracteres.', 'uenf-geral'),
            'numeric' => __('O campo %s deve conter apenas números.', 'uenf-geral'),
            'url' => __('O campo %s deve conter uma URL válida.', 'uenf-geral'),
            'date' => __('O campo %s deve conter uma data válida.', 'uenf-geral'),
            'time' => __('O campo %s deve conter um horário válido.', 'uenf-geral'),
            'pattern' => __('O campo %s não está no formato correto.', 'uenf-geral'),
            'equalTo' => __('Os campos %s e %s devem ser iguais.', 'uenf-geral'),
        ];
        
        $message = isset($messages[$rule]) ? $messages[$rule] : __('O campo %s é inválido.', 'uenf-geral');
        
        // Adiciona o nome do campo e outros parâmetros à mensagem
        $field_name = $this->get_field_name($field);
        array_unshift($params, $field_name);
        
        return vsprintf($message, $params);
    }
    
    /**
     * Obtém o nome amigável de um campo
     */
    private function get_field_name($field) {
        // Remove prefixos comuns
        $name = str_replace(['_', '-'], ' ', $field);
        
        // Converte para título
        return ucwords($name);
    }
    
    /**
     * Valida se o campo é obrigatório
     */
    private function validate_required($field, $value) {
        if (is_null($value) || $value === '') {
            $this->add_error($field, 'required');
        }
    }
    
    /**
     * Valida um endereço de e-mail
     */
    private function validate_email($field, $value) {
        if (!is_email($value)) {
            $this->add_error($field, 'email');
        }
    }
    
    /**
     * Valida o comprimento mínimo
     */
    private function validate_minlength($field, $value, $min) {
        if (mb_strlen($value) < $min) {
            $this->add_error($field, 'minlength', [$min]);
        }
    }
    
    /**
     * Valida o comprimento máximo
     */
    private function validate_maxlength($field, $value, $max) {
        if (mb_strlen($value) > $max) {
            $this->add_error($field, 'maxlength', [$max]);
        }
    }
    
    /**
     * Valida se é um número
     */
    private function validate_numeric($field, $value) {
        if (!is_numeric($value)) {
            $this->add_error($field, 'numeric');
        }
    }
    
    /**
     * Valida o valor mínimo
     */
    private function validate_min($field, $value, $min) {
        if ($value < $min) {
            $this->add_error($field, 'min', [$min]);
        }
    }
    
    /**
     * Valida o valor máximo
     */
    private function validate_max($field, $value, $max) {
        if ($value > $max) {
            $this->add_error($field, 'max', [$max]);
        }
    }
    
    /**
     * Valida uma URL
     */
    private function validate_url($field, $value) {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->add_error($field, 'url');
        }
    }
    
    /**
     * Valida uma data
     */
    private function validate_date($field, $value) {
        $d = DateTime::createFromFormat('Y-m-d', $value);
        if (!$d || $d->format('Y-m-d') !== $value) {
            $this->add_error($field, 'date');
        }
    }
    
    /**
     * Valida um horário
     */
    private function validate_time($field, $value) {
        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $value)) {
            $this->add_error($field, 'time');
        }
    }
    
    /**
     * Valida contra uma expressão regular
     */
    private function validate_pattern($field, $value, $pattern) {
        if (!preg_match("/^$pattern$/", $value)) {
            $this->add_error($field, 'pattern');
        }
    }
    
    /**
     * Valida se dois campos são iguais
     */
    private function validate_equalTo($field, $value, $other_field) {
        $other_value = isset($_POST[$other_field]) ? $_POST[$other_field] : null;
        
        if ($value !== $other_value) {
            $this->add_error($field, 'equalTo', [$this->get_field_name($other_field)]);
        }
    }
    
    /**
     * Processa a validação via AJAX
     */
    public function ajax_validate_form() {
        check_ajax_referer('uenf_form_validation_nonce', 'nonce');
        
        $form_id = isset($_POST['form_id']) ? sanitize_text_field($_POST['form_id']) : '';
        $field = isset($_POST['field']) ? sanitize_text_field($_POST['field']) : '';
        $value = isset($_POST['value']) ? $_POST['value'] : ''; // Não sanitizar ainda, será validado
        $rules = isset($_POST['rules']) ? (array) $_POST['rules'] : [];
        
        // Valida o campo
        $this->errors = [];
        $this->rules = [$field => $rules];
        $this->validate_field($field, $value, $rules);
        
        // Retorna os erros ou sucesso
        if (isset($this->errors[$field])) {
            wp_send_json_error([
                'field' => $field,
                'errors' => $this->errors[$field]
            ]);
        } else {
            wp_send_json_success([
                'field' => $field
            ]);
        }
    }
    
    /**
     * Renderiza os erros de validação
     */
    public static function render_errors($errors) {
        if (empty($errors)) {
            return '';
        }
        
        $output = '<div class="form-errors">';
        $output .= '<h3>' . __('Por favor, corrija os seguintes erros:', 'uenf-geral') . '</h3>';
        $output .= '<ul class="list-unstyled">';
        
        foreach ($errors as $field_errors) {
            foreach ($field_errors as $error) {
                $output .= '<li>' . esc_html($error) . '</li>';
            }
        }
        
        $output .= '</ul>';
        $output .= '</div>';
        
        return $output;
    }
}

// Inicializa o validador de formulários
function uenf_form_validator_init() {
    return UENF_Form_Validator::get_instance();
}

// Inicializa o validador quando o WordPress carregar
add_action('init', 'uenf_form_validator_init');
