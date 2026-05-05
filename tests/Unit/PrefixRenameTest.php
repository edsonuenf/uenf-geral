<?php
/**
 * Testa que a renomeação cct_ → uenf_ não deixou resíduos funcionais.
 * Usa análise estática (leitura de arquivos) — não precisa de WP.
 */

namespace UENF\Tests\Unit;

use PHPUnit\Framework\TestCase;

class PrefixRenameTest extends TestCase {

    private string $root;

    /** Extensões e diretórios a escanear */
    private array $phpFiles  = [];
    private array $jsFiles   = [];
    private array $cssFiles  = [];

    protected function setUp(): void {
        $this->root = dirname( __DIR__, 2 );
        $this->phpFiles = $this->collectFiles( '*.php', [ 'vendor', 'node_modules' ] );
        $this->jsFiles  = $this->collectFiles( '*.js',  [ 'vendor', 'node_modules', '*.min.js' ] );
        $this->cssFiles = $this->collectFiles( '*.css', [ 'vendor', 'node_modules', '*.min.css' ] );
    }

    // ── PHP ──────────────────────────────────────────────────────────────────

    public function test_no_cct_underscore_in_php_function_definitions(): void {
        $pattern = '/^(?:function|class|interface|trait)\s+cct_/m';
        $hits    = $this->grepFiles( $this->phpFiles, $pattern );

        $this->assertEmpty( $hits, "Definições PHP ainda com prefixo cct_:\n" . implode( "\n", $hits ) );
    }

    public function test_no_CCT_class_or_constant_definitions(): void {
        $pattern = '/\bCCT_[A-Z]/';
        $allowed = [ 'tests/' ]; // o bootstrap pode ter stubs futuros
        $hits    = $this->grepFiles( $this->phpFiles, $pattern, $allowed );

        $this->assertEmpty( $hits, "Constantes/classes PHP ainda com prefixo CCT_:\n" . implode( "\n", $hits ) );
    }

    public function test_no_cct_underscore_hook_registrations(): void {
        // add_action / add_filter / do_action / apply_filters com 'cct_'
        $pattern = '/(?:add_action|add_filter|do_action|apply_filters)\s*\(\s*[\'"]cct_/';
        $hits    = $this->grepFiles( $this->phpFiles, $pattern );

        $this->assertEmpty( $hits, "Hooks ainda registrados com prefixo cct_:\n" . implode( "\n", $hits ) );
    }

    public function test_no_cct_underscore_ajax_actions(): void {
        $pattern = '/wp_ajax(?:_nopriv)?_cct_/';
        $hits    = $this->grepFiles( $this->phpFiles, $pattern );

        $this->assertEmpty( $hits, "AJAX actions ainda com prefixo cct_:\n" . implode( "\n", $hits ) );
    }

    public function test_no_cct_wp_enqueue_handles(): void {
        // wp_enqueue_style/script com handle 'cct-'
        $pattern = '/wp_enqueue_(?:style|script)\s*\(\s*[\'"]cct-/';
        $hits    = $this->grepFiles( $this->phpFiles, $pattern );

        $this->assertEmpty( $hits, "wp_enqueue_* ainda com handle cct-:\n" . implode( "\n", $hits ) );
    }

    // ── JS ───────────────────────────────────────────────────────────────────

    public function test_no_cct_ajax_actions_in_js(): void {
        $pattern = '/action\s*:\s*[\'"]cct_/';
        // Exclui os arquivos de migração (que intencionalmente referenciam cct_)
        $exclude = [ 'extensions-manager.js', 'uenf-dark-mode.js' ];
        $hits    = $this->grepFiles( $this->jsFiles, $pattern, $exclude );

        $this->assertEmpty( $hits, "AJAX actions JS ainda com prefixo cct_:\n" . implode( "\n", $hits ) );
    }

    public function test_no_cct_nonces_in_js(): void {
        $pattern = '/[\'"]cct_nonce[\'"]/';
        $hits    = $this->grepFiles( $this->jsFiles, $pattern );

        $this->assertEmpty( $hits, "Nonces JS ainda com prefixo cct_:\n" . implode( "\n", $hits ) );
    }

    public function test_localStorage_migration_keys_present_in_dark_mode_js(): void {
        $file    = $this->root . '/js/uenf-dark-mode.js';
        $content = file_get_contents( $file );

        $this->assertStringContainsString(
            'cct_dark_mode_preference',
            $content,
            'Snippet de migração localStorage ausente em uenf-dark-mode.js'
        );
        $this->assertStringContainsString(
            'uenf_dark_mode_preference',
            $content,
            'Chave nova ausente no snippet de migração localStorage'
        );
    }

    public function test_localStorage_migration_keys_present_in_extensions_manager(): void {
        $file    = $this->root . '/js/extensions-manager.js';
        $content = file_get_contents( $file );

        $this->assertStringContainsString(
            'cct_extensions_welcome_shown',
            $content,
            'Snippet de migração localStorage ausente em extensions-manager.js'
        );
        $this->assertStringContainsString(
            'uenf_extensions_welcome_shown',
            $content,
            'Chave nova ausente no snippet de migração'
        );
    }

    // ── CSS ──────────────────────────────────────────────────────────────────

    public function test_no_cct_dash_css_classes(): void {
        $pattern = '/\.cct-[a-z]/';
        $hits    = $this->grepFiles( $this->cssFiles, $pattern );

        $this->assertEmpty( $hits, "Classes CSS ainda com prefixo .cct-:\n" . implode( "\n", $hits ) );
    }

    // ── Nomes de arquivo ─────────────────────────────────────────────────────

    public function test_no_cct_dash_filenames_in_css(): void {
        $files = glob( $this->root . '/css/cct-*.css' ) ?: [];
        $this->assertEmpty( $files, 'Arquivos CSS com nome cct-* ainda existem: ' . implode( ', ', $files ) );
    }

    public function test_no_cct_dash_filenames_in_js(): void {
        $files = glob( $this->root . '/js/cct-*.js' ) ?: [];
        $this->assertEmpty( $files, 'Arquivos JS com nome cct-* ainda existem: ' . implode( ', ', $files ) );
    }

    public function test_renamed_css_files_exist(): void {
        $expected = [
            'css/uenf-dark-mode.css',
            'css/uenf-design-tokens.css',
            'css/uenf-animations.css',
            'css/uenf-layout-system.css',
            'css/uenf-shadows.css',
            'css/uenf-patterns.css',
            'css/uenf-gradients.css',
            'css/uenf-icons.css',
            'css/uenf-responsive-breakpoints.css',
        ];
        foreach ( $expected as $rel ) {
            $this->assertFileExists( $this->root . '/' . $rel, "Arquivo renomeado não encontrado: $rel" );
        }
    }

    public function test_renamed_js_files_exist(): void {
        $expected = [
            'js/uenf-dark-mode.js',
            'js/uenf-design-tokens.js',
            'js/uenf-animations.js',
            'js/uenf-patterns.js',
            'js/uenf-shadows.js',
            'js/uenf-gradients.js',
            'js/uenf-breakpoints-preview.js',
            'js/uenf-design-tokens-control.js',
            'js/uenf-breakpoint-manager-control.js',
            'js/uenf-responsive-breakpoints.js',
        ];
        foreach ( $expected as $rel ) {
            $this->assertFileExists( $this->root . '/' . $rel, "Arquivo renomeado não encontrado: $rel" );
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function collectFiles( string $glob, array $excludeDirs = [] ): array {
        $rii   = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $this->root ) );
        $ext   = ltrim( $glob, '*.' );
        $files = [];

        foreach ( $rii as $file ) {
            if ( ! $file->isFile() ) continue;
            if ( $file->getExtension() !== $ext ) continue;

            $path = $file->getPathname();
            foreach ( $excludeDirs as $excl ) {
                if ( str_contains( $path, DIRECTORY_SEPARATOR . $excl . DIRECTORY_SEPARATOR )
                     || str_contains( $path, '/' . $excl . '/' ) ) {
                    continue 2;
                }
                // Exclui por sufixo de arquivo (ex: *.min.js)
                if ( str_starts_with( $excl, '*.' ) && str_ends_with( $path, ltrim( $excl, '*' ) ) ) {
                    continue 2;
                }
            }
            $files[] = $path;
        }
        return $files;
    }

    private function grepFiles( array $files, string $pattern, array $excludeFragments = [] ): array {
        $hits = [];
        foreach ( $files as $path ) {
            foreach ( $excludeFragments as $excl ) {
                if ( str_contains( $path, $excl ) ) continue 2;
            }
            $lines = file( $path, FILE_IGNORE_NEW_LINES );
            foreach ( $lines as $no => $line ) {
                if ( preg_match( $pattern, $line ) ) {
                    $rel    = str_replace( $this->root . '/', '', $path );
                    $hits[] = "  {$rel}:" . ( $no + 1 ) . "  →  " . trim( $line );
                }
            }
        }
        return $hits;
    }
}
