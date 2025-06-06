# Integração do GitHub com Atualizações do WordPress

Este documento descreve como configurar atualizações automáticas para temas e plugins do WordPress diretamente do GitHub.

## 📋 Visão Geral

Permite que temas e plugins hospedados no GitHub sejam atualizados automaticamente através do painel administrativo do WordPress, mostrando notificações quando novas versões estiverem disponíveis.

## 🛠️ Configuração para Temas

### 1. Estrutura Básica

Crie um arquivo `updater.php` no diretório do seu tema com o seguinte conteúdo:

```php
<?php
/**
 * GitHub Theme Updater
 */

class UENF_Theme_Updater {
    private $current_version;
    private $theme_slug;
    private $github_username = 'seu-usuario';
    private $github_repo = 'seu-tema';
    private $api_url;

    public function __construct() {
        $this->theme_slug = get_template();
        $this->current_version = wp_get_theme($this->theme_slug)->get('Version');
        $this->api_url = "https://api.github.com/repos/{$this->github_username}/{$this->github_repo}/releases/latest";
        
        add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update'));
    }

    public function check_for_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $remote_version = $this->get_remote_version();
        
        if ($remote_version && version_compare($this->current_version, $remote_version, '<')) {
            $theme_data = wp_get_theme($this->theme_slug);
            $theme_slug = $theme_data->get_template();
            
            $transient->response[$theme_slug] = array(
                'theme' => $theme_slug,
                'new_version' => $remote_version,
                'url' => "https://github.com/{$this->github_username}/{$this->github_repo}",
                'package' => "https://github.com/{$this->github_username}/{$this->github_repo}/archive/refs/tags/{$remote_version}.zip",
            );
        }
        
        return $transient;
    }

    private function get_remote_version() {
        $response = wp_remote_get($this->api_url, array(
            'headers' => array(
                'Accept' => 'application/vnd.github.v3+json',
            ),
        ));
        
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }
        
        $release_data = json_decode(wp_remote_retrieve_body($response));
        return isset($release_data->tag_name) ? $release_data->tag_name : false;
    }
}

// Inicializar o updater
add_action('after_setup_theme', function() {
    new UENF_Theme_Updater();
});
```

### 2. Inclua o Updater no functions.php

Adicione esta linha ao seu `functions.php`:

```php
// Carregar o sistema de atualização do GitHub
require_once get_template_directory() . '/updater.php';
```

## 🔌 Configuração para Plugins

### 1. Estrutura Básica

Crie um arquivo `updater.php` no diretório do seu plugin:

```php
<?php
/**
 * GitHub Plugin Updater
 */

if (!class_exists('Meu_Plugin_Updater')) {
    class Meu_Plugin_Updater {
        private $current_version;
        private $plugin_slug;
        private $github_username = 'seu-usuario';
        private $github_repo = 'meu-plugin';
        private $api_url;
        private $plugin_file;

        public function __construct($plugin_file) {
            $this->plugin_file = $plugin_file;
            $this->plugin_slug = plugin_basename($plugin_file);
            $this->current_version = get_plugin_data($plugin_file)['Version'];
            $this->api_url = "https://api.github.com/repos/{$this->github_username}/{$this->github_repo}/releases/latest";
            
            add_filter('pre_set_site_transient_update_plugins', array($this, 'check_for_update'));
        }

        public function check_for_update($transient) {
            if (empty($transient->checked)) {
                return $transient;
            }

            $remote_version = $this->get_remote_version();
            
            if ($remote_version && version_compare($this->current_version, $remote_version, '<')) {
                $plugin_data = get_plugin_data($this->plugin_file);
                
                $transient->response[$this->plugin_slug] = (object) array(
                    'slug' => $this->plugin_slug,
                    'new_version' => $remote_version,
                    'url' => $plugin_data['PluginURI'],
                    'package' => "https://github.com/{$this->github_username}/{$this->github_repo}/releases/download/{$remote_version}/{$this->github_repo}-{$remote_version}.zip",
                );
            }
            
            return $transient;
        }

        private function get_remote_version() {
            $response = wp_remote_get($this->api_url, array(
                'headers' => array(
                    'Accept' => 'application/vnd.github.v3+json',
                ),
            ));
            
            if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
                return false;
            }
            
            $release_data = json_decode(wp_remote_retrieve_body($response));
            return isset($release_data->tag_name) ? $release_data->tag_name : false;
        }
    }
}

// Inicializar o updater
add_action('plugins_loaded', function() {
    new Meu_Plugin_Updater(__FILE__);
});
```

## 🚀 Como Usar

1. **Crie Releases no GitHub**:
   - Vá para a aba "Releases" no seu repositório
   - Clique em "Draft a new release"
   - Crie uma tag de versão (ex: `1.0.0`)
   - Adicione notas da versão
   - Faça upload dos arquivos do tema/plugin compactados

2. **Para Repositórios Privados**:
   - Gere um token de acesso em GitHub > Settings > Developer settings > Personal access tokens
   - Adicione o token à URL da API:
     ```php
     $this->api_url = add_query_arg('access_token', 'SEU_TOKEN_AQUI', $this->api_url);
     ```

## 🔒 Boas Práticas de Segurança

1. **Nunca exponha tokens** no código-fonte
2. Use variáveis de ambiente para armazenar credenciais
3. Valide todas as respostas da API
4. Implemente tratamento de erros adequado
5. Use HTTPS para todas as requisições

## 🔄 Fluxo de Trabalho Recomendado

1. Desenvolva em uma branch de desenvolvimento
2. Teste todas as alterações
3. Crie uma release no GitHub
4. Atualize o número da versão no cabeçalho do tema/plugin
5. O WordPress notificará os usuários sobre a atualização disponível

## 📝 Notas Adicionais

- Certifique-se de que a tag da versão no GitHub corresponda à versão no cabeçalho do tema/plugin
- Para temas, o diretório principal do tema dentro do ZIP deve ter o mesmo nome do slug do tema
- Para plugins, o arquivo principal do plugin deve estar na raiz do ZIP

## 🔗 Links Úteis

- [GitHub Releases API](https://docs.github.com/en/rest/releases/releases)
- [WordPress Plugin API](https://developer.wordpress.org/plugins/wordpress-org/plugin-developer-faq/)
- [WordPress Theme Updates](https://developer.wordpress.org/themes/advanced-topics/theme-updates/)
