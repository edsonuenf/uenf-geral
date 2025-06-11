# Proteção contra Spam no Tema UENF Geral

## Índice
1. [Visão Geral](#visão-geral)
2. [Estrutura de Arquivos](#estrutura-de-arquivos)
3. [Configuração Necessária](#configuração-necessária)
4. [Fluxo de Funcionamento](#fluxo-de-funcionamento)
5. [Implementação](#implementação)
6. [Testes](#testes)
7. [Monitoramento e Manutenção](#monitoramento-e-manutenção)

## Visão Geral

Este documento descreve a implementação de um sistema de proteção contra spam que combina duas técnicas principais:

1. **Honeypot**: Campo oculto que só é preenchido por bots
2. **reCAPTCHA v3**: Verificação invisível da Google que não requer interação do usuário

## Estrutura de Arquivos

```
/var/www/html/wp-content/themes/uenf-geral/
├── inc/
│   ├── class-spam-protection.php    # Classe principal
│   └── spam-protection/            # Diretório para arquivos relacionados
│       ├── recaptcha.php            # Lógica do reCAPTCHA
│       └── honeypot.php             # Lógica do Honeypot
├── js/
│   └── spam-protection.js           # Scripts do cliente
└── css/
    └── spam-protection.css          # Estilos para mensagens
```

## Configuração Necessária

### reCAPTCHA v3

1. **Obter chaves de API**:
   - Acessar [Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin)
   - Registrar novo site (tipo reCAPTCHA v3)
   - Obter chaves:
     - Chave do site (SITE_KEY)
     - Chave secreta (SECRET_KEY)

2. **Configuração no WordPress** (adicionar ao `wp-config.php`):

```php
define('UENF_RECAPTCHA_SITE_KEY', 'sua_chave_aqui');
define('UENF_RECAPTCHA_SECRET_KEY', 'sua_chave_secreta_aqui');
define('UENF_RECAPTCHA_SCORE_THRESHOLD', 0.5); // Ponto de corte (0.0 a 1.0)
```

### Honeypot

Nenhuma configuração adicional necessária. O sistema gera nomes de campos aleatórios automaticamente.

## Fluxo de Funcionamento

1. **No carregamento da página**:
   - Carregar API do reCAPTCHA
   - Gerar token do reCAPTCHA
   - Inicializar campo honeypot

2. **No envio do formulário**:
   - Validar campo honeypot (deve estar vazio)
   - Verificar token do reCAPTCHA
   - Se ambas as verificações passarem, processar o formulário

## Implementação

### 1. HTML do Formulário

```html
<form id="meu-formulario" class="uenf-validate" method="post">
    <!-- Campos do formulário aqui -->
    
    <!-- Campo Honeypot -->
    <div class="honeypot-field" style="position: absolute; left: -9999px;">
        <label for="website">Deixe em branco</label>
        <input type="text" name="website" id="website" tabindex="-1">
    </div>
    
    <!-- Token do reCAPTCHA -->
    <input type="hidden" name="recaptcha_token" id="recaptcha-token">
    
    <button type="submit">Enviar</button>
</form>
```

### 2. JavaScript (spam-protection.js)

```javascript
class SpamProtection {
    static init() {
        this.loadRecaptcha();
        document.querySelectorAll('form.uenf-validate').forEach(form => {
            form.addEventListener('submit', this.handleFormSubmit.bind(this));
        });
    }
    
    static async loadRecaptcha() {
        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${UENF_RECAPTCHA.SITE_KEY}`;
        document.head.appendChild(script);
    }
    
    static async handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        
        if (this.checkHoneypot(form)) {
            return this.showError(form, 'Erro de verificação. Tente novamente.');
        }
        
        try {
            const token = await this.getRecaptchaToken('submit');
            form.querySelector('#recaptcha-token').value = token;
            form.submit();
        } catch (error) {
            this.showError(form, 'Erro na verificação de segurança.');
        }
    }
    
    // ... (outros métodos conforme mostrado anteriormente)
}

document.addEventListener('DOMContentLoaded', () => SpamProtection.init());
```

### 3. PHP (class-spam-protection.php)

```php
<?php
class UENF_Spam_Protection {
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_verify_recaptcha', [$this, 'verify_recaptcha']);
        add_action('wp_ajax_nopriv_verify_recaptcha', [$this, 'verify_recaptcha']);
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script(
            'uenf-spam-protection',
            get_template_directory_uri() . '/js/spam-protection.js',
            ['jquery'],
            '1.0.0',
            true
        );
        
        wp_localize_script('uenf-spam-protection', 'UENF_RECAPTCHA', [
            'SITE_KEY' => defined('UENF_RECAPTCHA_SITE_KEY') ? UENF_RECAPTCHA_SITE_KEY : '',
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('uenf_recaptcha_nonce')
        ]);
    }
    
    public function verify_recaptcha() {
        check_ajax_referer('uenf_recaptcha_nonce', 'nonce');
        
        $token = isset($_POST['token']) ? sanitize_text_field($_POST['token']) : '';
        
        if (empty($token)) {
            wp_send_json_error(['message' => 'Token não fornecido']);
        }
        
        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => UENF_RECAPTCHA_SECRET_KEY,
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ]
        ]);
        
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'Erro ao validar reCAPTCHA']);
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($body['success'] && $body['score'] >= UENF_RECAPTCHA_SCORE_THRESHOLD) {
            wp_send_json_success();
        } else {
            wp_send_json_error(['message' => 'Falha na verificação de segurança']);
        }
    }
    
    public static function verify_honeypot($data) {
        // Verifica se algum campo honeypot foi preenchido
        foreach ($data as $key => $value) {
            if (strpos($key, 'honeypot_') === 0 && !empty($value)) {
                return false;
            }
        }
        return true;
    }
}

// Inicializar
UENF_Spam_Protection::get_instance();
```

## Testes

### Testes de Honeypot
1. Preencher o campo honeypot e tentar enviar
   - Esperado: Bloqueio do envio
2. Deixar o campo honeypot vazio
   - Esperado: Permite o envio

### Testes de reCAPTCHA
1. Desabilitar JavaScript
   - Esperado: Formulário não envia
2. Enviar com pontuação baixa
   - Esperado: Bloqueio do envio
3. Enviar com pontuação alta
   - Esperado: Permite o envio

## Monitoramento e Manutenção

1. **Logs**:
   - Registrar tentativas de spam bloqueadas
   - Monitorar taxas de falsos positivos

2. **Atualizações**:
   - Manter bibliotecas atualizadas
   - Revisar logs periodicamente

3. **Ajustes**:
   - Ajustar UENF_RECAPTCHA_SCORE_THRESHOLD conforme necessário
   - Atualizar chaves do reCAPTCHA anualmente

## Solução de Problemas

### reCAPTCHA não carrega
- Verificar chave da API
- Verificar conexão com a internet
- Verificar bloqueios de conteúdo misto (HTTP/HTTPS)

### Falsos positivos
- Reduzir UENF_RECAPTCHA_SCORE_THRESHOLD
- Verificar logs para padrões comuns

### Desempenho
- Carregar reCAPTCHA apenas em páginas com formulários
- Usar async/defer nos scripts
