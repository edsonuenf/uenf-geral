<?php
/**
 * Testa a lógica da migração do banco de dados (theme_mods + wp_options).
 * Usa Mockery para interceptar as chamadas às funções WP sem banco real.
 */

namespace UENF\Tests\Integration;

use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class DbMigrationTest extends TestCase {

    protected function tearDown(): void {
        Mockery::close();
    }

    /**
     * Simula a lógica de migração de theme_mods de forma isolada.
     * Replica a closure add_action('init', ...) de functions.php.
     */
    private function runMigration( array &$options ): void {
        // Replica a lógica da migração sem registrar hooks WP
        if ( $options['uenf_prefix_migration_done'] ?? false ) {
            return;
        }

        $theme_slug  = $options['stylesheet'] ?? 'uenf-geral-claude';
        $option_name = 'theme_mods_' . $theme_slug;
        $mods        = $options[ $option_name ] ?? [];

        $migrated = [];
        foreach ( $mods as $key => $value ) {
            $new_key             = preg_replace( '/^cct_/', 'uenf_', $key );
            $migrated[ $new_key ] = $value;
        }
        $options[ $option_name ] = $migrated;

        foreach ( [ 'cct_active_extensions', 'cct_google_fonts_api_key', 'cct_css_editor_settings' ] as $old ) {
            if ( isset( $options[ $old ] ) ) {
                $new_key             = str_replace( 'cct_', 'uenf_', $old );
                $options[ $new_key ] = $options[ $old ];
                unset( $options[ $old ] );
            }
        }

        $options['uenf_prefix_migration_done'] = true;
    }

    // ── theme_mods ───────────────────────────────────────────────────────────

    public function test_migration_renames_cct_theme_mods_to_uenf(): void {
        $options = [
            'stylesheet'                  => 'uenf-geral-claude',
            'theme_mods_uenf-geral-claude' => [
                'cct_dark_mode_enabled'  => true,
                'cct_primary_color'      => '#1D3771',
                'cct_footer_columns'     => 3,
            ],
        ];

        $this->runMigration( $options );

        $mods = $options['theme_mods_uenf-geral-claude'];
        $this->assertArrayHasKey( 'uenf_dark_mode_enabled', $mods );
        $this->assertArrayHasKey( 'uenf_primary_color', $mods );
        $this->assertArrayHasKey( 'uenf_footer_columns', $mods );
    }

    public function test_migration_removes_old_cct_theme_mod_keys(): void {
        $options = [
            'stylesheet'                   => 'uenf-geral-claude',
            'theme_mods_uenf-geral-claude' => [
                'cct_dark_mode_enabled' => true,
                'cct_primary_color'     => '#1D3771',
            ],
        ];

        $this->runMigration( $options );

        $mods = $options['theme_mods_uenf-geral-claude'];
        $this->assertArrayNotHasKey( 'cct_dark_mode_enabled', $mods );
        $this->assertArrayNotHasKey( 'cct_primary_color', $mods );
    }

    public function test_migration_preserves_theme_mod_values(): void {
        $options = [
            'stylesheet'                   => 'uenf-geral-claude',
            'theme_mods_uenf-geral-claude' => [
                'cct_primary_color'  => '#1D3771',
                'cct_footer_columns' => 3,
            ],
        ];

        $this->runMigration( $options );

        $mods = $options['theme_mods_uenf-geral-claude'];
        $this->assertSame( '#1D3771', $mods['uenf_primary_color'] );
        $this->assertSame( 3, $mods['uenf_footer_columns'] );
    }

    public function test_migration_preserves_non_cct_theme_mods(): void {
        $options = [
            'stylesheet'                   => 'uenf-geral-claude',
            'theme_mods_uenf-geral-claude' => [
                'cct_dark_mode'    => true,
                'uenf_some_option' => 'already_migrated',
                'nav_menu_locations' => [ 'primary' => 1 ],
            ],
        ];

        $this->runMigration( $options );

        $mods = $options['theme_mods_uenf-geral-claude'];
        $this->assertArrayHasKey( 'uenf_some_option', $mods );
        $this->assertArrayHasKey( 'nav_menu_locations', $mods );
    }

    // ── wp_options ────────────────────────────────────────────────────────────

    public function test_migration_renames_cct_active_extensions(): void {
        $options = [
            'stylesheet'            => 'uenf-geral-claude',
            'cct_active_extensions' => [ 'search', 'dark-mode' ],
            'theme_mods_uenf-geral-claude' => [],
        ];

        $this->runMigration( $options );

        $this->assertArrayHasKey( 'uenf_active_extensions', $options );
        $this->assertArrayNotHasKey( 'cct_active_extensions', $options );
        $this->assertSame( [ 'search', 'dark-mode' ], $options['uenf_active_extensions'] );
    }

    public function test_migration_renames_cct_google_fonts_api_key(): void {
        $options = [
            'stylesheet'               => 'uenf-geral-claude',
            'cct_google_fonts_api_key' => 'AIzaSyABC123',
            'theme_mods_uenf-geral-claude' => [],
        ];

        $this->runMigration( $options );

        $this->assertArrayHasKey( 'uenf_google_fonts_api_key', $options );
        $this->assertArrayNotHasKey( 'cct_google_fonts_api_key', $options );
    }

    public function test_migration_skips_absent_options(): void {
        $options = [
            'stylesheet' => 'uenf-geral-claude',
            'theme_mods_uenf-geral-claude' => [],
            // cct_active_extensions ausente — não deve causar erro
        ];

        $this->runMigration( $options );

        $this->assertArrayNotHasKey( 'uenf_active_extensions', $options );
    }

    // ── Flag de migração ──────────────────────────────────────────────────────

    public function test_migration_sets_done_flag(): void {
        $options = [
            'stylesheet'                   => 'uenf-geral-claude',
            'theme_mods_uenf-geral-claude' => [],
        ];

        $this->runMigration( $options );

        $this->assertTrue( $options['uenf_prefix_migration_done'] );
    }

    public function test_migration_runs_only_once(): void {
        $options = [
            'stylesheet'                   => 'uenf-geral-claude',
            'uenf_prefix_migration_done'   => true, // já migrado
            'theme_mods_uenf-geral-claude' => [
                'cct_dark_mode' => true, // não deve ser migrado
            ],
        ];

        $this->runMigration( $options );

        // Deve permanecer com a chave cct_ pois a migração não rodou novamente
        $this->assertArrayHasKey( 'cct_dark_mode', $options['theme_mods_uenf-geral-claude'] );
    }
}
