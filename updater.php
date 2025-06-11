<?php
/**
 * GitHub Theme Updater
 * 
 * @package UENF_Geral
 */

class UENF_Theme_Updater {
    private $current_version;
    private $theme_slug;
    private $github_username = 'UENF';
    private $github_repo = 'uenf-geral';
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
